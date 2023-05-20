<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\AnswerType;
use App\Models\Client;
use App\Models\ClientAnswer;
use App\Models\Question;
use App\Models\Setting;
use App\Models\QuestionType;
use App\Models\Status;
use App\Models\Survey;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class ApiController extends Controller
{
    // URL: api/v1/survey/get/{id}
    // フロントエンドから届いたパラメター($id)に基づき
    // アンケートのデータを取得する
    public function getSurvey(Request $request, $id) {        
        $query = Survey::where('token', $id)->first();
        $question = Question::where('survey_id', $query->id)->orderBy('ord')->first();
        $question_count = Question::where('survey_id', $query->id)->count();

        $query['first_question'] = $question;               // アンケート内の最初の質問データ
        $query['question_count'] = $question_count;         // アンケート内の質問の数
        $query['brand_logo_path'] = $query['profile_path']; // ブランドロゴ画像のパス

        $user_profile_url = '';
	    $user_profile_name = '';
        if($query != null) {
            $user = User::find($query->user_id);
            if($user != null) {
                $user_profile_url = $user->profile_url;     // アンケート作成者のプロフィールのURL
	            $user_profile_name = $user->full_name;      // アンケート作成者のユーザ名
                if(!isset($user->full_name)) {
                    $user_profile_name = $user->name;  
                }   
            }
        }
        $query['user_profile_url'] = $user_profile_url;
	    $query['user_profile_name'] = $user_profile_name;

        return response()->json($query);
    }

    // URL: api/v1/answers/get/{id}
    // アンケートの回答データを取得する
    public function getAnswer(Request $request, $id) {
        $query = Answer::find($id);
        return response()->json($query);
    }

    // URL: api/v1/answers/get-list/{qid}
    // アンケートの回答一覧を取得する
    public function getAnswers(Request $request, $qid) {
        $query = Answer::where('question_id', $qid)->get();
        return response()->json($query);
    }

    // URL: api/v1/questions/get/{id}
    // アンケートの質問データを取得する
    public function getQuestion(Request $request, $id) {
        $query = Question::find($id);
        if ($query) {
            $query['is_next'] = true;

            $answers = Answer::where('question_id', $id)->get();
            $next_question = Question::where(['survey_id' => $query->survey_id, 'ord' => $query->ord +1])->first();
            $query['answers'] = $answers; // 質問に対する回答のリストの取得
    
            foreach($answers as $answer) {
                $answer['answer_images'] = $answer->answer_images;
            }
    
            if($next_question != null) {
                $query['next_question_id'] = $next_question->id;
            } else{
                $query['next_question_id'] = null;
            }
        }
        else {
            $query = array();
            $query['is_next'] = false;
        }

        return response()->json($query);
    }

    // ユーザのアンケート結果をデータベースに保存する
    public function saveAnswers(Request $request){
        $client = new Client();

        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->full_name = $request->get('full_name');
        $client->zip_code = $request->get('zip_code');
        $client->address = $request->get('address');
        $client->phone_number = $request->get('phone_number');
        $client->survey_id = $request->get('survey_id');

        if (empty($client->name)) {
            $client->name = $client->full_name;
        }
        $client->save();

        $answers = $request->get('answers');
        if (!is_array($answers)) {
            $answers = explode(",", $answers);
        }

        $answerModels = [];
        foreach ($answers as $answer){
            $clientAnswer = new ClientAnswer();
            $clientAnswer->client_id = $client->id;
            $clientAnswer->answer_id = $answer;
            $clientAnswer->save();
            $answerModels[] = $clientAnswer;
        }
        $this->sendMail($client, $answerModels);
    }

    // メールの送信
    private function sendMail($client, $answers) {
        $survey = $client->survey != null ? $client->survey->title : '';
        $content = "<h2>ユーザ設問回答</h2>";
        $content .= "<p><span>ユーザ名</span><span>$client->name</span></p>";
        $content .= "<p><span>メールアドレス</span><span>$client->email</span></p>";
        $content .= "<p><span>名前</span><span>$client->full_name</span></p>";
        $content .= "<p><span>郵便番号</span><span>$client->zip_code</span></p>";
        $content .= "<p><span>住所</span><span>$client->address</span></p>";
        $content .= "<p><span>電話番号</span><span>$client->phone_number</span></p>";
        $content .= "<p><span>設問</span><span>$survey</span></p>";
        $content .= "<table><thead><th>質問</th><th>回答</th></thead><tbody>";
        foreach ($answers as $answer) {
            $q_item = $answer->answer->question != null ? $answer->answer->question->title : '';
            $a_item = $answer->answer != null ? $answer->answer->title : '';
            $content .= "<tr><td>$q_item</td><td>$a_item</td></tr>";
        }
        $content .= "</tbody></table>";
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = "sv179.xbiz.ne.jp";
            $mail->Port = 465;
            $mail->Username = "info@formstylee.com";
            $mail->Password = "HappySaaS";
            $mail->setFrom("info@formstylee.com", "formstylee");
            $mail->Timeout = 30;
            $mail->Subject = "設問回答";
            $mail->MsgHTML($content);
            $mail->addAddress($client->email, $client->full_name);
            
            $survey = Survey::where('token', $client->survey_id)->first();
            if (!empty($survey)) {
                $user_mail_name = $survey->user->full_name;
                if (empty($user_mail_name)) {
                    $user_mail_name = $survey->user->name;
                }
                $mail->addAddress($survey->user->email, $user_mail_name);
            }

            $mail->send();
        } catch (phpmailerException $e) {
            dd($e);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function createPaymentIntent(Request $request)
    {
        // try {
        //     \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        //     $res = \Stripe\PaymentIntent::create([
        //         'amount' => $request->amount,
        //         'currency' => 'jpy',
        //         'payment_method_types' => ['card']
        //     ]);
        //     return response()->json(['message' => '支払い情報を登録しました', 'response' => $res]);

        // } catch (\Exception$e) {
        //     return response()->json(['message' => $e->getMessage()], 422);
        // }
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $customer = \Stripe\Customer::create(array(
                'card' => $request->token
            ));
            $charge = \Stripe\Charge::create(array(
                'amount' => $request->total,
                'currency' => 'jpy',
                'customer' => $customer->id,
            ));
        } catch(\Stripe\Exception\CardException $e) {
            return response()->json(['message' => $e->getError()->message,'status' => 'fail'], 200);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['message' => 'An invalid request occurred.','status' => 'fail'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Another problem occurred, maybe unrelated to Stripe.','status' => 'fail'], 200);
        }
    }

    public function getSetting() {
        $creditCardPaymentFlg = Setting::where('code', 'credit_card_payment')->first()->flag;
        return response()->json(['creditCardPaymentFlg' => $creditCardPaymentFlg]);
    }

    public function test() {
        $test = false;
        
        return response()->json([
            'result' => 'OK'
        ]);
    }

    public function getWidget(Request $request)
    {
        $widget = Widget::where('widget_id', $request->id)->first();
        return response()->json(['data' => $widget->context]);
    }
}
