<?php

namespace App\Http\Controllers;

use File;
use App\Models\Answer;
use App\Models\AnswerType;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\AnswerImage;
use App\Models\Status;
use App\Models\Survey;
use App\Models\Widget;
use App\Libraries\Pixabay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use MarkSitko\LaravelUnsplash\Facades\Unsplash;
use GuzzleHttp\Client;

class SurveyController extends Controller
{
    private $pixabay;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->pixabay = $pixabay;
    }

    public function index(Request $request)
    {
        if(Auth::user()->isAdmin()) {
            $surveys = Survey::simplePaginate(20);
        } else {
            $surveys = Survey::join('users', 'surveys.user_id', 'users.id')
                ->groupBy(['surveys.id','surveys.title', 'surveys.description', 'surveys.status','surveys.background_color',
                    'surveys.char_color', 'surveys.border_color', 'surveys.callout_color', 'surveys.gradient_color', 
                    'surveys.profile_path', 'surveys.token', 'surveys.progress_status', 'surveys.qrcode_show', 
                    'surveys.brand_description', 'surveys.brand_name', 'surveys.brand_description_show', 'surveys.title_show',
                    'surveys.created_at', 'surveys.updated_at', 'surveys.user_id'])
                ->where('users.id',Auth::user()->id)
                ->select('surveys.*')
                ->simplePaginate(20);
        }
        return view('admin/survey/index',['surveys' => $surveys]);
    }

    public function add(Request $request)
    {
        $survey = new Survey();
        $statuses = Status::all();
        $question_types = QuestionType::all();
        $answer_types = AnswerType::all();
        $photo = Unsplash::randomPhoto()
                            // ->orientation('portrait')
                            ->term('nature')
                            ->count(30)
                            ->toJson();
        // $client = new Client();
        // $response = $client->request('GET', 'https://pixabay.com/api/', [
        //     'query' => [
        //         'key' => '17536358-8449fef0d1b11ed2de3c3a9b6',
        //         'q' => 'nature',
        //         'per_page' => 30
        //     ],
        // ]);

        // $body = $response->getBody();
        // $pixa_photo = json_decode($body->getContents());
        return view('admin/survey/form',
            [
                'survey' => $survey,
                'statuses' =>$statuses,
                'question_types' =>$question_types,
                'answer_types' =>$answer_types,
                'photo' => $photo,
                'pixa_photo' => []
                // 'pixa_photo' => $pixa_photo->hits
            ]);
    }

    public function edit(Request $request, $id)
    {
        $survey = Survey::find($id);
        $statuses = Status::all();
        $question_types = QuestionType::all();
        $answer_types = AnswerType::all();
        $question_list = Question::where('survey_id', $survey->id)->get();
        $answer_list = Answer::where('survey_id', $survey->id)->get();
        $photo = Unsplash::randomPhoto()
                            // ->orientation('portrait')
                            ->term('nature')
                            ->count(30)
                            ->toJson();
        // $client = new Client();
        // $response = $client->request('GET', 'https://pixabay.com/api/', [
        //     'query' => [
        //         'key' => '17536358-8449fef0d1b11ed2de3c3a9b6',
        //         'q' => 'nature',
        //         'per_page' => 30
        //     ],
        // ]);

        // $body = $response->getBody();
        // $pixa_photo = json_decode($body->getContents());
        return view('admin/survey/form',
            [
                'survey' => $survey,
                'statuses' =>$statuses,
                'question_types' =>$question_types,
                'answer_types' =>$answer_types,
                'questions' => $question_list,
                'answers' => $answer_list,
                'photo' => $photo,
                'pixa_photo' => []
                // 'pixa_photo' => $pixa_photo->hits
            ]);
    }

    public function imageSearch(Request $request)
    {
        $photo = Unsplash::randomPhoto()
                ->term($request->post('search_key'))
                // ->color('black_and_white')
                ->count(30)
                ->toJson();    
        // $client = new Client();
        // $response = $client->request('GET', 'https://pixabay.com/api/', [
        //     'query' => [
        //         'key' => '17536358-8449fef0d1b11ed2de3c3a9b6',
        //         'q' => $request->post('search_key'),
        //         'per_page' => 30
        //     ],
        // ]);

        // $body = $response->getBody();
        // $pixa_photo = json_decode($body->getContents()); 
        return view('admin/survey/imageview',
            [
                'photo' => $photo,
                'pixa_photo' => []
                // 'pixa_photo' => $pixa_photo->hits
            ]);
    }

    public function imageSearchAll(Request $request)
    {
        $type = $request->post('type');
        $photo = Unsplash::randomPhoto()
                ->term($request->post('search_key'))
                // ->color('black_and_white')
                ->count(30)
                ->toJson();    
        // $client = new Client();
        // $response = $client->request('GET', 'https://pixabay.com/api/', [
        //     'query' => [
        //         'key' => '17536358-8449fef0d1b11ed2de3c3a9b6',
        //         'q' => $request->post('search_key'),
        //         'per_page' => 30
        //     ],
        // ]);

        // $body = $response->getBody();
        // $pixa_photo = json_decode($body->getContents()); 
        if($type == 'all') $view = 'admin/survey/searchview';
        else if($type == 'unsplash') $view = 'admin/survey/unsplash';
        else if($type == 'pixabay') $view = 'admin/survey/pixabay';
        return view($view,
            [
                'photo' => $photo,
                'pixa_photo' => []
                // 'pixa_photo' => $pixa_photo->hits
            ]);
    }

    public function delete(Request $request, $id)
    {
        $result = Survey::where('id',$id)->delete();
        return redirect()->route('admin.surveys');
    }

    public function save(Request $request)
    {
        $survey;
        if($request->get('id') != null) {
            $survey = Survey::find($request->get('id'));
        } else {
            $survey = new Survey();
        }

		// フォルダの作成
        $surver_folder = 'uploads/surveys/' . $survey->id . '/';
        if(!File::exists($surver_folder)) {
            File::makeDirectory($surver_folder);
        }        
        $img_brand_path = $surver_folder . 'brand/';
		if(!File::exists($img_brand_path)) {
            File::makeDirectory($img_brand_path);
        }
        $img_qrcode_path = $surver_folder . 'qrcode/';
        if(!File::exists($img_qrcode_path)) {
            File::makeDirectory($img_qrcode_path);
        }

        $survey->title = $request->get('title');
        $survey->status = $request->get('status');
        $survey->description = $request->get('description');
        $survey->background_color = $request->get('background_color');
        $survey->char_color = $request->get('char_color');
        $survey->border_color = $request->get('border_color');
        $survey->gradient_color = $request->get('gradient_color');
        $survey->progress_status = $request->get('progress_status') == true ? 1 : 0;
        $survey->qrcode_show = $request->get('qrcode_show') == true ? 1 : 0;
        $survey->brand_description_show = $request->get('brand_description_show') == true ? 1 : 0;
        $survey->title_show = $request->get('title_show') == true ? 1 : 0;
        $survey->brand_description = $request->get('brand_description');
        $survey->brand_name = $request->get('brand_name');
        $survey->callout_color = $request->get('callout_color');
        $survey->widget_image = $request->get('widget_image');
        $survey->widget_text = $request->get('widget_text');
        $survey->widget_height = $request->get('widget_height');
        $survey->widget_height_unit = $request->get('widget_height_unit');
        $survey->widget_width = $request->get('widget_width');
        $survey->widget_width_unit = $request->get('widget_width_unit');
        $survey->widget_position = $request->get('widget_position');
        $survey->widget_text_color = $request->get('widget_text_color');
        $survey->widget_bg_color = $request->get('widget_bg_color');
        $survey->widget_show = $request->get('widget_show');
        if($request->get('id') == null)
            $survey->token = Str::random(20);

        if (empty($survey->user_id)) {
            $survey->user_id = Auth::user()->id;
        }
        $profile_file = $request->file('profile_path');

        if ($profile_file != null) {
            if (strtolower($profile_file->getClientOriginalExtension()) == 'png'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpg'
                || strtolower($profile_file->getClientOriginalExtension()) == 'gif'
                || strtolower($profile_file->getClientOriginalExtension()) == 'jpeg'
            ) {
                $profile_file->move($img_brand_path, str_replace(' ','_', $profile_file->getClientOriginalName()));
                $survey->profile_path = $img_brand_path . str_replace(' ','_', $profile_file->getClientOriginalName());
            }
        }
        $survey->save();

        $questions = $request->get('questions');
        $question_files = $request->file('questions');
        $question_ids = [];
        if($questions != null) {
            $q_keys = array_keys($questions);
            $ord = 0;
            foreach ($q_keys as $key) {
                $item = $questions[$key];
                if (isset($item['id'])) {
                    $question = Question::find($item['id']);
                } else {
                    $question = new Question();
                }
                $question->option == 0;

                if (array_key_exists('option', $item)) {
                    $question->option = $item['option'];
                }

                if ($question->option == 0) {
                    $question->answer_count = 100;
                }
                elseif ($question->option == 1) {
                    $question->answer_count = $item['answer_count_less_10'];
                }
                elseif ($question->option == 2) {
                    $question->answer_count = $item['answer_count_above_10'];
                }

                $question->title = $item['title'];
                if(isset($item['sub_title']))
                    $question->sub_title = $item['sub_title'];
                else
                    $question->sub_title = "";
                $question->type = $item['type'];
                $question->survey_id = $survey->id;
                $question->ord = $ord;
                $question->save();
                $question_ids[]=$question->id;
                if (isset($question_files[$key]['file_url'])) {
                    $question_file = $question_files[$key]['file_url'];
                    if ($question_file != null) {
                        if (strtolower($question_file->getClientOriginalExtension()) == 'png'
                            || strtolower($question_file->getClientOriginalExtension()) == 'jpg'
                            || strtolower($question_file->getClientOriginalExtension()) == 'gif'
                            || strtolower($question_file->getClientOriginalExtension()) == 'jpeg'
                        ) {
                            $question_file->move('uploads/questions/' . $question->id, str_replace(' ', '_', $question_file->getClientOriginalName()));
                            $question->file_url = 'uploads/questions/' . $question->id . '/' . str_replace(' ', '_', $question_file->getClientOriginalName());
                            $question->save();
                        }
                    }
                }
                // if(isset($questions[$key]['movie_file_tmp']))
                //     var_dump($questions[$key]['movie_file_tmp']);

                if(isset($questions[$key]['movie_file_tmp'])) {
                    if($questions[$key]['movie_file_tmp'] == '-') {
                        // if (isset($question_files[$key]['movie_file'])) {
                            $movie_file = Question::find($question->id);
                            if($movie_file != null) {
                                @unlink($movie_file->movie_file);
                            }
                            $question->movie_file = '';
                            $question->save();
                        // }
                    } else {
                        if (isset($question_files[$key]['movie_file'])) {
                            $question_file = $question_files[$key]['movie_file'];
                            if ($question_file != null) {
                                if (strtolower($question_file->getClientOriginalExtension()) == 'mp4'
                                    || strtolower($question_file->getClientOriginalExtension()) == 'avi'
                                    || strtolower($question_file->getClientOriginalExtension()) == 'gif'
                                ) {
                                    $question_file->move('uploads/questions/' . $question->id . '/movie', str_replace(' ', '_', $question_file->getClientOriginalName()));
                                    $question->movie_file = 'uploads/questions/' . $question->id . '/movie/' . str_replace(' ', '_', $question_file->getClientOriginalName());
                                    $question->save();
                                }
                            }
                        }
                    }
                }

                $question->movie_source = isset($questions[$key]['movie_source']) ? $questions[$key]['movie_source'] : '';
                $question->movie_url = isset($questions[$key]['movie_url']) ? $questions[$key]['movie_url'] : '';
                $question->save();


                $answer_ids = [];
                if(isset($item['answers'])) {
                    $answers = $item['answers'];
                    $a_keys = array_keys($answers);
                    $a_ord = 0;
                    foreach ($a_keys as $a_key){
                        $answerItem =  $answers[$a_key];
                        if (isset($answerItem['id'])) {
                            $answerModel = Answer::find($answerItem['id']);
                        } else {
                            $answerModel = new Answer();
                        }
                        $answerModel->title = $answerItem['title'];
                        $answerModel->value = $answerItem['value'];
                        $answerModel->type = $answerItem['type'];
                        if(isset($answerItem['parent_id'])){
                            $answerModel->parent_id = $answerItem['parent_id'];
                        }
                        if(isset($answerItem['next_question_id'])){
                            $answerModel->next_question_id = $answerItem['next_question_id'];
                        }
                        $answerModel->survey_id = $survey->id;
                        $answerModel->question_id = $question->id;
                        $answerModel->ord = $a_ord;
                        $answerModel->save();

                        // unsplash img load
                        if(strpos($answerItem['image'],'images.unsplash.com/')){
                            $answerModel->file_url = $answerItem['image'];
                            $answerModel->save();
                        } else {
                            if (isset($question_files[$key]['answers'][$a_key]['file_url'])) {
                                $answer_file = $question_files[$key]['answers'][$a_key]['file_url'];
                                if ($answer_file != null) {
                                    if (strtolower($answer_file->getClientOriginalExtension()) == 'png'
                                        || strtolower($answer_file->getClientOriginalExtension()) == 'jpg'
                                        || strtolower($answer_file->getClientOriginalExtension()) == 'gif'
                                        || strtolower($answer_file->getClientOriginalExtension()) == 'jpeg'
                                    ) {
                                        $answer_file->move('uploads/answers/' . $answerModel->id, str_replace(' ', '_', $answer_file->getClientOriginalName()));
                                        $answerModel->file_url = 'uploads/answers/' . $answerModel->id . '/' . str_replace(' ', '_', $answer_file->getClientOriginalName());
                                        $answerModel->save();
                                    }
                                }
                            }
                        }
                        
                        if (isset($question_files[$key]['answers'][$a_key]) and isset($question_files[$key]['answers'][$a_key]['sub_images'])) {
                            $answer_sub_files = $question_files[$key]['answers'][$a_key]['sub_images'];
                            $answer_sub_files_unsulash = $answerItem['sub_images'];
                            foreach ($answer_sub_files_unsulash as $sub_file_key => $answer_sub_file){
                                $answerImageModel = new AnswerImage();
                                $answerImageModel->answer_id = $answerModel->id;
                                $answerImageModel->sub_file_url = '_';
                                // unsplash img load
                                if(strpos($answer_sub_file,'images.unsplash.com/') && !isset($answer_sub_files[$sub_file_key])){
                                    $answerImageModel->sub_file_url = $answer_sub_file;
                                    $answerImageModel->save();
                                } else
                                    continue;
                            }
                            foreach ($answer_sub_files as $sub_file_key => $answer_sub_file){
                                $answerImageModel = new AnswerImage();
                                $answerImageModel->answer_id = $answerModel->id;
                                $answerImageModel->sub_file_url = '_';
                                $answerImageModel->save();

                                // unsplash img load
                                if(strpos($answer_sub_files_unsulash[$sub_file_key],'images.unsplash.com/')){
                                    $answerImageModel->sub_file_url = $answer_sub_files_unsulash[$sub_file_key];
                                    $answerImageModel->save();
                                } else {
                                
                                    if (strtolower($answer_sub_file->getClientOriginalExtension()) == 'png'
                                        || strtolower($answer_sub_file->getClientOriginalExtension()) == 'jpg'
                                        || strtolower($answer_sub_file->getClientOriginalExtension()) == 'gif'
                                        || strtolower($answer_sub_file->getClientOriginalExtension()) == 'jpeg'
                                    ) {
                                        $answer_sub_file->move('uploads/answers/sub_images/' .  $answerModel->id . "_" . $answerImageModel->id, $sub_file_key . str_replace(' ', '_', $answer_sub_file->getClientOriginalName()));
                                        $answerImageModel->sub_file_url = 'uploads/answers/sub_images/' . $answerModel->id . "_" . $answerImageModel->id . '/' . $sub_file_key . str_replace(' ', '_', $answer_sub_file->getClientOriginalName());
                                        $answerImageModel->save();
                                    }
                                }
                            }
                        } elseif (isset($answerItem['sub_images'])){
                            $answer_sub_files_unsulash = $answerItem['sub_images'];
                            foreach ($answer_sub_files_unsulash as $sub_file_key => $answer_sub_file){
                                $answerImageModel = new AnswerImage();
                                $answerImageModel->answer_id = $answerModel->id;
                                $answerImageModel->sub_file_url = '_';
                                // unsplash img load
                                if(strpos($answer_sub_file,'images.unsplash.com/')){
                                    $answerImageModel->sub_file_url = $answer_sub_file;
                                    $answerImageModel->save();
                                } else
                                    continue;
                            }
                        }
                        $answer_ids[] = $answerModel->id;
                        $a_ord++;
                    }
                }

                if(count($answer_ids)>0){
                    Answer::where('question_id',$question->id)->whereNotIn('id', $answer_ids)->delete();
                }else{
                    Answer::where('question_id',$question->id)->delete();
                }

                $ord++;
            }
        }

        $remove_images = $request->get('remove_images');
        if(isset($remove_images)) {
            foreach($remove_images as $image_id) {
                $remove_image_file = AnswerImage::find($image_id);
                if($remove_image_file != null) {
                    @unlink($remove_image_file->sub_file_url);
                }
                if (isset($remove_image_file)) {
                    $remove_image_file->delete();
                }
            }
        }

        // QRコードの処理
        QrCode::size(100)->generate('https://styleboard.xbiz.jp/client/?id=' . $survey->token, $img_qrcode_path . 'qrcode.svg');

        if(count($question_ids) > 0) {
            Question::where('survey_id',$survey->id)->whereNotIn('id', $question_ids)->delete();
            Answer::where('survey_id',$survey->id)->whereNotIn('question_id', $question_ids)->delete();
        }else{
            Question::where('survey_id',$survey->id)->delete();
            Answer::where('survey_id',$survey->id)->delete();
        }

        $statuses = Status::all();
        $question_types = QuestionType::all();
        $answer_types = AnswerType::all();
        $question_list = Question::where('survey_id', $survey->id)->get();
        $answer_list = Answer::where('survey_id', $survey->id)->get();

        return redirect('/admin/survey/edit/' . $survey->id);
    }

    public function getSurvey(Request $request, $id)
    {
        $query = Survey::find($id);
        return response()->json($query);
    }

    public function saveWidget(Request $request)
    {
        $widget = Widget::where('widget_id', $request->id)->first();
        if ($widget) {
            $widget->widget_id = $request->id;
            $widget->context = $request->widget_context;
        } else {
            $widget = new Widget();
            $widget->widget_id = $request->id;
            $widget->context = $request->widget_context;
        }
        $widget->save();
        return response()->json(array('success' => true));
    }
}
