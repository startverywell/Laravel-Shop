@extends('layouts.admin', ['title' => 'アンケート'])
@section('js')
    <script src="{{ asset('js/survey.js') }}"></script>
@endsection
<?php
echo '<script>';
echo 'var GradientList = ' . json_encode(GRADIENT_COLOR) . ';';
echo 'var image_search_url = "' . route("admin.survey.imagesearch") . '";';
echo 'var csrfToken = "'.csrf_token().'";';
echo '</script>';
?>
@section('main-content')
    <div class="row">
        <div class="col-8">
            <form class="" method="post" id="form" action="{{ route('admin.survey.save') }}" enctype="multipart/form-data">
                @csrf
                <input type="submit" class="btn btn-primary" value="保存">
                <input type="hidden" name="qrcode_show" value="{{$survey['qrcode_show']}}">
                <input type="hidden" name="brand_description_show" value="{{$survey['brand_description_show']}}">
                <input type="hidden" name="title_show" value="{{$survey['title_show']}}">
                <div id="remove_images">
                </div>
                @if (isset($survey['id']))
                    <div class="row">
                        <lable class="col-md-2 col-form-label">ID</lable>
                        <input type="hidden" name="id" value="{{$survey['id']}}">
                        <div class="col-md-6 d-flex align-items-center">{{$survey['id']}}</div>
                    </div>
                @endif
                <div class="form-group row">
                    <lable class="col-md-1 col-form-label">色設定</lable>
                    <div class="col-md-7 align-items-center">
                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <lable class="col-form-label mr-3">全体背景:</lable>
                                <input class="" type="color" name="background_color"
                                       value="{{ isset($survey['background_color']) ? $survey['background_color'] : '#eeebff' }}">
                                <lable class="col-form-label mx-3">文字:</lable>
                                <input class="" type="color" name="char_color"
                                       value="{{ isset($survey['char_color']) ? $survey['char_color'] : '#785cff' }}">
                                <lable class="col-form-label mx-3">枠カラー:</lable>
                                <input class="" type="color" name="border_color".
                                       value="{{ isset($survey['border_color']) ? $survey['border_color'] : '#785cff' }}">
                                <lable class="col-form-label mx-3">背景:</lable>
                                <input class="" type="color" name="callout_color"
                                       value="{{ isset($survey['callout_color']) ? $survey['callout_color'] : '#785cff' }}">
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <lable class="col-md-4 col-form-label mr-0">グラデーション:</lable>
                            <div class="col-md-2">
                                <span class="gradient_background"></span>
                            </div>
                            <div class="col-md-5">
                                <select name="gradient_color" class="form-control">
                                    @foreach(GRADIENT_COLOR as $key => $item)
                                        <option value="{{ $key }}" {{ (isset($survey['gradient_color']) && $survey['gradient_color'] == $key) ? 'selected' : '' }}>{{ $item[1] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center form-inline">
                        <div class="row">
                            <div class="col-md-12">
                                <lable class="col-form-label mr-3">ステータス:</lable>
                                <select class="form-control" name="status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}" {{ $status->id == $survey['status'] ? 'selected' : ''}}>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <lable class="col-form-label mr-3">進捗状況ステータスバー:</lable>
                                <div class="switch_box box_1 d-flex align-items-center">
                                    <input type="checkbox" class="switch_1" name="progress_status" {{ (isset($survey['progress_status']) && $survey['progress_status'] == 1) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @if (isset($survey['profile_path']))
                    <div>
                        <img src="{{asset($survey['profile_path'])}}" class="fs-profile-image">
                    </div>
                @endif
                <div class="form-group row">
                    <lable class="col-md-3 col-form-label">ブランドロゴ</lable>
                    <div class="col-md-6 d-flex align-items-center">
                        <input type="file" name="profile_path">
                    </div>
                </div>
                <div class="form-group row">
                    <lable class="col-md-3 col-form-label">　　ブランド名 <br>(会社またはユーザ名)
                    </lable>
                    <div class="col-md-6 d-flex align-items-center">
                        <input type="text" name="brand_name" class="form-control" value="{{ isset($survey['brand_name']) ? $survey['brand_name'] : '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <lable class="col-md-3 col-form-label">紹介文: </lable>
                    <div class="col-md-9 d-flex align-items-center">
                        <textarea class="form-control" placeholder="紹介文" name="brand_description">{{ isset($survey['brand_description']) ? $survey['brand_description'] : '' }}</textarea>
                        <div class="mb-1" style="display: flex; align-items: center;">
                            <div class="switch_box box_1 d-flex align-items-center mx-2">
                                <input id="chk-brand-description-show" type="checkbox" class="switch_1" {{ (isset($survey['brand_description_show']) && $survey['brand_description_show'] == 1) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="">タイトル: </label>
                    <div class="col-md-10 d-flex align-items-center">
                        <input class="form-control" type="text" placeholder="タイトル" name="title"
                               value="{{ isset($survey['title']) ? $survey['title'] : '' }}" required>
                        <div class="mb-1" style="display: flex; align-items: center;">
                            <div class="switch_box box_1 d-flex align-items-center mx-2">
                                <input id="chk-title-show" type="checkbox" class="switch_1" {{ (isset($survey['title_show']) && $survey['title_show'] == 1) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="">説明: </label>
                    <div class="col-md-10">
                        <textarea class="form-control" placeholder="説明" name="description">{{ isset($survey['description']) ? $survey['description'] : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="">小計のプレフィックス: </label>
                    <div class="col-md-9">
                        <input classmodalAddQuestion="form-control" type="text" placeholder="円" name="totalPrefix" value="" />
                    </div>
                </div>
                <div id="questions-container">
                    <script>
                        function getMeta(url, cb) {
                            const img = new Image();
                            img.onload = () => cb(null, img);
                            img.onerror = (err) => cb(err);
                            img.src = url;
                        };
                    </script>
                    @php
                        $q_index = 0;
                        $a_index = 0;
                    @endphp
                    @if (isset($questions))
                        @foreach( $questions as $question)
                            @php
                                $next_questions = [['id'=>0, 'title'=>'']];
                                foreach ($questions as $q_item){
                                    if ($question->ord < $q_item->ord){
                                        $next_questions[] = ['id' => $q_item->id, 'title'=>$q_item->title];
                                    }
                                }
                            @endphp

                            <div class="question" id="question_{{$q_index}}">
                                <input type="hidden" value="{{$question->id}}" name="questions[q_{{$q_index}}][id]">
                                <input type="hidden" value="{{$question->type}}" name="questions[q_{{$q_index}}][type]">
                                <div class="row form-group ">
                                    <label class="col-md-2 pl-1 col-form-label d-flex align-items-center">質問{{$q_index + 1}}:</label>
                                    <div class="col-md-3">
                                        <textarea placeholder="質問" class="form-control" name="questions[q_{{$q_index}}][title]" required>{{$question->title}}</textarea>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center question-options">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" class="p-1 answers-option" name="questions[q_{{$q_index}}][option]" value="1" id="{{$q_index}}_answer_count_less_10"
                                                {{ $question->option == 1?  'checked' : '' }}
                                            />
                                            <label for="{{$q_index}}_answer_count_less_10" class="block-label p-1">回答数</label>
                                            <select class="form-control" name="questions[q_{{$q_index}}][answer_count_less_10]" 
                                                style="width: 60px;" {{ $question->option != 1?  'hidden' : '' }}>
                                                @foreach (range(1, 10) as $num)
                                                    <option value="{{ $num }}"  
                                                        {{ $question->answer_count == $num?  'selected' : '' }}
                                                    >{{ $num }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-center">

                                            <input type="radio" class="p-1 answers-option" name="questions[q_{{$q_index}}][option]" value="2" id="{{$q_index}}_answer_count_above_10"
                                                {{ $question->option == 2?  'checked' : '' }} />
                                            <label for="{{$q_index}}_answer_count_above_10" class="block-label p-1">指定する<br>(10点以上)</label>
                                            <input min="11" type="number" style="width: 60px; height: 35px" name="questions[q_{{$q_index}}][answer_count_above_10]" value="{{ $question->option == 2? $question->answer_count: '' }}" {{ $question->option != 2?  'hidden' : '' }}/>
                                        </div>
                                        <div>
                                            <input type="radio" class="p-1 answers-option" name="questions[q_{{$q_index}}][option]" value="0" id="{{$q_index}}_answer_count_no_limit"
                                                {{ $question->option == 0?  'checked' : '' }}
                                            <label for="{{$q_index}}_answer_count_no_limit" class="block-label p-1">指定しない</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-danger" onclick="onDelete('question_{{$q_index}}')">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <div id="subQues-div{{ $q_index }}">
                                    @if(isset($question->sub_title) && $question->sub_title != "")
                                        <div class="row form-group " id="_sub_que_{{ $q_index }}">
                                            <label class="col-md-2 pl-1 col-form-label d-flex align-items-center">サーブ質問{{$q_index + 1}}:</label>
                                            <div class="col-md-9">
                                                <textarea placeholder="サーブ質問" class="form-control" name="questions[q_{{$q_index}}][sub_title]" required>{{$question->sub_title}}</textarea>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger" onclick="onDelete('_sub_que_{{$q_index}}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if($question->type == 3)
									<?php
									if(isset($question->movie_file) && $question->movie_file != "") {
										$file_name = explode('/', $question->movie_file);
										$file_name = $file_name[count($file_name) - 1];
									} else {
										$file_name = '動画を選択してください。';
                                    }
									?>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input type="file" class="form-control-file d-none" style="opacity: 0;" name="questions[q_{{$q_index}}][movie_file]"  id="questions[q_{{$q_index}}][movie_file]{{$q_index}}">
                                                <input type="hidden" name="questions[q_{{$q_index}}][movie_file_tmp]" data-name="questions[q_{{$q_index}}][movie_file]{{$q_index}}" data-index="questions[q_{{$q_index}}][movie_file]" id="questions[q_{{$q_index}}][movie_file_tmp]{{$q_index}}" value="{{ !empty($question->movie_file) ? $question->movie_file : '-'  }}">
                                                <label for="questions[q_{{$q_index}}][movie_file]{{$q_index}}" class="form-control-label btn btn-primary">{{ $file_name }}</label>
												<p class="text-danger">（* テキスト）</p>
												<a href="https://cloudconvert.com/mov-to-mp4">https://cloudconvert.com/mov-to-mp4</a>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-danger" onclick="onDeleteMovie('questions[q_{{$q_index}}][movie_file]', '{{$q_index}}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <textarea class="form-control" name="questions[q_{{$q_index}}][movie_source]" placeholder="ソースコード(iframe)でアップ（1024以下の文字を入力してください。）">{{ isset($question->movie_source) ? $question->movie_source : '' }}</textarea>
                                    </div>
                                    <div class="col-md-12 mt-2 mb-2">
                                        <input type="text" class="form-control" name="questions[q_{{$q_index}}][movie_url]" value="{{ isset($question->movie_url) ? $question->movie_url : '' }}" placeholder="URLでアップ(YoutubeなどのURL)">
                                    </div>
                                @endif

                                @if ($question->type == 2)
                                    @if ($question->file_url != null)
                                        <div class="row">
                                            <img src="{{ asset($question->file_url) }}" class="col fs-question-image mb-2">
                                        </div>
                                    @endif
                                    <div class="row form-group">
                                        <div class="col-md">
                                            <input type="file" class="form-control"
                                                   name="questions[q_{{$q_index}}][file_url]">
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex mb-1">
                                    <div id="answers_{{$q_index}}" class="d-flex flex-wrap">
                                        @php
                                            $parents = [['id' => 0, 'title' => '']];
                                            foreach($answers as $item){
                                                if($item->question_id == $question->id && $item->type == 3){
                                                    $parents[] = ['id' => $item->id, 'title' => $item->title];
                                                }
                                            }
                                        @endphp
                                        @foreach($answers as $answer)
                                            @if ($answer->question_id == $question->id)
                                                <div class="answer card mr-2 mb-2" id="_answer_{{$a_index}}" style="width: {{ 240 + 110 * ceil(count($answer->answer_images)/2) }}px">
                                                    <div id="_answer_images_{{$a_index}}">
                                                    </div>
                                                    <input id="_answer_images_{{$a_index}}_count" value="{{ count($answer->answer_images) }}" hidden>
                                                    <div class="card-header d-flex justify-content-between p-2">
                                                        <span>回答</span>
                                                        <div class="btn" style="padding: 0" onclick="addSubAnswerImage({{$q_index}}, {{$a_index}})">
                                                            <img src="{{ asset('/img/plus.png') }}" style="width: 25px; height: 25px;"/>
                                                        </div>
                                                        <a class="text-danger" onclick="onDelete('_answer_{{$a_index}}')"><i class="fa fa-times"></i></a>
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <input type="hidden" value="{{$answer->id}}" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][id]">
                                                        <input type="hidden" value="{{$answer->type}}" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][type]">
                                                        @if ($answer->type == 2)
                                                            <div class="d-flex">
                                                                @if ($answer->file_url != null)
                                                                <div class="card-img-top mb-1" style="position: relative;">
                                                                    @if (!strpos($answer->file_url,'images.unsplash.com/'))
                                                                        <img class="card-img-top mb-1" style="position: relative;" src="{{ asset($answer->file_url) }} "
                                                                        id="{{$q_index}}_{{$a_index}}_main_img" ondrop="imgDrop(event)" ondragover="imgAllowDrop(event)"/>
                                                                    @else
                                                                        <img class="card-img-top mb-1" style="position: relative;" src="{{ $answer->file_url }} "
                                                                        id="{{$q_index}}_{{$a_index}}_main_img" ondrop="imgDrop(event)" ondragover="imgAllowDrop(event)"/>
                                                                    @endif
                                                                        <!-- <button class="btn" style="position: absolute; right: 10px; top: 10px;">
                                                                        <img src="{{ asset('/img/remove.png') }}" style="width: 20px; height: 20px; background-color: rgba(0, 0, 0, 0.5);"></img>
                                                                    </button> -->
                                                                </div>
                                                                <script>
                                                                    getMeta("{{ asset($answer->file_url) }}", (err, img) => {
                                                                        if (img.naturalHeight > img.naturalWidth) {
                                                                            document.getElementById("{{$q_index}}_{{$a_index}}_main_img").classList.add("object-contain");
                                                                        }
                                                                    });
                                                                </script>
                                                                @endif
                                                                <div class="d-flex" style="flex-direction: column; flex-flow: row wrap; height: 220px;" id="{{$q_index}}_{{$a_index}}_image_container">
                                                                    @foreach($answer->answer_images as $answer_image)
                                                                    <div style="position: relative;" id="{{ 'image_url_' . $answer_image->id }}" >
                                                                        @if (!strpos($answer_image->sub_file_url,'images.unsplash.com/'))
                                                                            <img class="card-img-top mb-1" style="position: relative; height: 106px; width: 106px; margin: 2px; border-radius: 5px;" id="{{ 'image_url_content_' . $answer_image->id }}" src="{{ asset($answer_image->sub_file_url) }}">
                                                                        @else
                                                                            <img class="card-img-top mb-1" style="position: relative; height: 106px; width: 106px; margin: 2px; border-radius: 5px;" id="{{ 'image_url_content_' . $answer_image->id }}" src="{{ $answer_image->sub_file_url }}">    
                                                                        @endif
                                                                        <button class="btn" style="position: absolute; right: -8px; top: -8px; padding:0" onclick="removeSubAnswerImage(event, {{$answer_image->id}})">
                                                                            <img src="{{ asset('/img/remove.png') }}" style="width: 15px; height: 15px;"></img>
                                                                        </button>
                                                                        <input name="${image_input_name}" type="hidden" 
                                                                            id="{{ 'image_url_content_' . $answer_image->id.'_hidden' }}"
                                                                            value = "{{$answer_image->sub_file_url}}"
                                                                        />
                                                                    </div>
                                                                    <script>
                                                                        getMeta("{{ asset($answer_image->sub_file_url) }}", (err, img) => {
                                                                            if (img.naturalHeight > img.naturalWidth) {
                                                                                document.getElementById("{{ 'image_url_content_' . $answer_image->id }}>img").classList.add("object-contain");
                                                                            }
                                                                        });
                                                                    </script>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <input type="hidden" class="form-control mb-2" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][image]" value="{{$answer->file_url}}" id="{{$q_index}}_{{$a_index}}_main_img_hidden">     
                                                            <input type="file"  onchange="handleMainImgChange(event, {{$q_index}}, {{$a_index}})" class="form-control mb-1" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][file_url]">
                                                        @endif
                                                        <textarea placeholder="回答" class="form-control" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][title]" required>{{$answer->title}}</textarea>
                                                        <input placeholder="値" class="form-control mt-1" 
                                                            name="questions[q_{{$q_index}}][answers][a_{{ $a_index }}][value]" 
                                                            value="{{$answer->value}}" />    
                                                        @if (count($parents) > 1 && $answer->type != 3)
                                                            集団
                                                            <select class="form-control mt-1" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][parent_id]">
                                                                @foreach($parents as $item)
                                                                    <option value="{{ $item['id'] }}" {{$item['id'] == $answer->parent_id ? 'selected' : ''}}>{{ $item['title'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                        @if (count($next_questions) > 1)
                                                            次の問題
                                                            <select class="form-control mt-1" name="questions[q_{{$q_index}}][answers][a_{{$a_index}}][next_question_id]">
                                                                @foreach($next_questions as $n_item)
                                                                    <option value="{{ $n_item['id'] }}" {{$n_item['id'] == $answer->next_question_id ? 'selected' : ''}}>{{ $n_item['title'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                @php $a_index++;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex mb-5">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAddAnswer" data-answerid="answers_{{$q_index}}">+</button>
                                    <button type="button" class="btn btn-outline-warning ml-auto" {{ isset($question->sub_title) && $question->sub_title != "" ? 'disabled' : '' }} data-toggle="modal" data-target="#modalAddSubQuestion" data-answerid="subQues-div{{$q_index}}">+</button>
                                </div>
                            </div>
                            @php
                                $q_index++;
                            @endphp
                        @endforeach
                    @endif
                </div>

            </form>
            <div>
                <button class="btn btn-primary" id="btn-add-survey" data-toggle="modal" data-target="#modalAddQuestion">
                    +
                </button>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div>
                    <div class="card-left">
                        <h6 class="form-control-label m-3">
                            URL: <br>
                            <a href="https://styleboard.xbiz.jp/client/?id={{ $survey['token'] }}" target="_blank" style="word-break: break-all;">https://styleboard.xbiz.jp/client/?id={{ $survey['token'] }}</a>
                        </h6>
                    </div>
                    <div class="card-right">
                        <div class="qr-code-row" style="display: flex; width: 100%;">
                            <div class="qr-code-img">
                                
                                    <img src="{{ asset('img/qr_code.png') }}">
                               
                            </div>
                            <div class="qr-code-setting" style="width: calc(100% - 120px); padding: 0 0 0 20px;">
                                <div class="qr-code-setting-head mb-1" style="display: flex; align-items: center;">
                                    <span>OFF</span>
                                    <div class="switch_box box_1 d-flex align-items-center mx-2">
                                        <input id="chk-qrcode-show" type="checkbox" class="switch_1" {{ (isset($survey['qrcode_show']) && $survey['qrcode_show'] == 1) ? 'checked' : '' }}>
                                    </div>
                                    <span>ON</span>
                                </div>
                                <div class="qr-code-setting-desc">
                                    <p class="text-left" style="max-width: 250px;">※ONにするとWebサイトのトップにQRコードを表示します。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="card-header" style="margin-top: 10px;">
                    プレビュー
                </h5>

                <div class="card-body preview" id="preview"
                     style="background: {{ isset($survey['background_color']) ? $survey['background_color'] : '#eeebff' }}">
                    <div class="row">
                        <div class="col-1">
                            @if (isset($survey['profile_path']))
                                <div>
                                    <img src="{{asset($survey['profile_path'])}}" class="fs-profile-image small">
                                </div>
                            @endif
                        </div>
                        <div class="col-10">
                            <div id="preview-text" class="ml-4"
                                style="border: 1px solid {{ isset($survey['border_color']) ? $survey['border_color'] : '#785cff' }}; background: {{ isset($survey['callout_color']) ? $survey['callout_color'] : '#785cff' }}; color: {{ isset($survey['char_color']) ? $survey['char_color'] : '#785cff' }}">
                                テキストプレビュー
                            </div>
                            <div class="card" id="preview-img"
                                 style="border: 4px solid {{ isset($survey['border_color']) ? $survey['border_color'] : '#785cff' }}">
                                <img class="card-img-top" src="{{asset('img/preview_img.png')}}">
                                <div class="card-body preview-gradient" style="background: {{ isset($survey['gradient_color']) ? GRADIENT_COLOR[$survey['gradient_color']][0] : 'white' }};">
                                    テキストプレビュー
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex;">
                    <div class="card-left p-3 col-12">
                        <h5 class="card-header">
                            ウィジェット設定
                        </h5>
                        <div class="mt-2 widget">
                            <div class="row">
                                <div class="col-md-5">
                                    ウィジェットアイコン
                                    <div class="d-flex mt-2">
                                        <div class="icons">
                                            <input form="form" type="file" id="widget_file_input" hidden>
                                            <input hidden form="form" name="widget_image" value="{{ $survey['widget_image']}}">
                                            <div class="d-flex" style="justify-content: center" onclick="openWidgetIcon()">
                                                <div class="add-icon-btn mb-2">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="description">アップロード</div>
                                        </div>
                                        <div class="icons">
                                            <div class="d-flex" style="justify-content: center">
                                                <div class="mb-2">
                                                    @if ($survey['widget_image'])
                                                        <img form="form" id="widget_img" class="widget-img" src="{{ $survey['widget_image'] }} "/>
                                                    @else
                                                        <img form="form" id="widget_img" class="widget-img"/>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="description">現在の表示</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    メッセージ
                                    <textarea form="form" id="widget_text" name="widget_text" class="form-control mt-2" style="height: 70px; min-width: 100%">{{ $survey['widget_text'] }}</textarea>
                                </div>
                            </div>
                            <input type="hidden" form="form" name="widget_show" value="{{$survey['widget_show']}}">
                            <div class="row mt-4">
                                <div class="col-md-5">
                                    <div class="col-md-12 d-flex">
                                        <lable class="col-form-label mr-3">ON/OFF:</lable>
                                        <div class="switch_box box_1 d-flex align-items-center">
                                            <input type="checkbox" class="switch_1" id="chk_widget_show" {{ (isset($survey['widget_show']) && $survey['widget_show'] == 1) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row d-flex">
                                        <div class="col-md-6">
                                            テキストカラー
                                            <input form="form" class="" type="color" name="widget_text_color"
                                                value="{{ isset($survey['widget_text_color']) ? $survey['widget_text_color'] : '#5D9CEB' }}">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            背景カラー
                                            <input form="form" class="" type="color" name="widget_bg_color"
                                                value="{{ isset($survey['widget_bg_color']) ? $survey['widget_bg_color'] : '#5D9CEB' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            サイズ
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    Width (横)
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input form="form" type="text" name="widget_width" value="{{ $survey['widget_width'] }}" class="form-control" placeholder="">
                                            <select form="form" class="form-control" name="widget_width_unit" >
                                                <option value="px" {{ ($survey['widget_width_unit'] == 'px' or $survey['widget_width'] == NULL)?  'selected' : '' }} >px</option>
                                                <option value="%" {{ $survey['widget_width_unit'] == '%'?  'selected' : '' }} >%</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    Height (縦）
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input form="form" type="text" name="widget_height" value="{{ $survey['widget_height'] }}" class="form-control" placeholder="">
                                            <select form="form" class="form-control" name="widget_height_unit" >
                                                <option value="px" {{ ($survey['widget_height_unit'] == 'px' or $survey['widget_height_unit'] == NULL)?  'selected' : '' }} >px</option>
                                                <option value="%" {{ $survey['widget_height_unit'] == '%'?  'selected' : '' }}>%</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-5">
                                    <div class="text-center">
                                        表示位置
                                    </div>
                                    <div class="d-flex align-items-center" style="justify-content: space-between">
                                        <div>
                                            Left &nbsp <span style="color: #777">---</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input type="radio" form="form" class="m-1 answers-option" name="widget_position" value="left" {{ ($survey['widget_position'] == 'left' or $survey['widget_position'] == NULL)?  'checked' : '' }}/>
                                            <input type="radio" form="form" class="m-1 answers-option" name="widget_position" value="center" {{ $survey['widget_position'] == 'center'?  'checked' : '' }}/>
                                            <input type="radio" form="form" class="m-1 answers-option" name="widget_position" value="right" {{ $survey['widget_position'] == 'right'?  'checked' : '' }}/>
                                        </div>
                                        <div>
                                            <span style="color: #777">---</span> &nbsp Right
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        Center
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4" style="background-color: white-smoke; border: 1px solid grey; overflow: auto; min-height: 400px" id="widget_preview"></div>
                        </div>
                    </div>
                </div>

<?php
$token = htmlspecialchars($survey['token']);
?>
<script>
var token = "{{ $token }}";
var widget_save_path = "{{url('admin/survey/widget')}}";
var widget_get_path = "{{url('api/v1/survey/widget')}}";
</script>
                <div style="display: flex;">
                    <div class="card-left p-3 col-12">
                        <h5 class="card-header">
                            ソースコード
                        </h5>
                        <div class="mt-2">
                            <textarea id="copytag" class="form-control" style="height: 150px; min-width: 100%"></textarea>
                            width、heightの値は貼り付けサイトに応じて変更してください。
                            <div class="text-right">
                                <input type="button" id="copytag" class="btn btn-primary mt-1" value="コピー" onclick="copyTag();">
                            </div>
                        </div>
<script>
function copyTag() {
	var textarea = document.getElementById("copytag");
	textarea.select();
	document.execCommand("copy");
	alert('クリップボードにコピーしました。');
}
var remove_image_path = "{{ asset('/img/remove.png') }}";
var plus_image_path = "{{ asset('/img/plus.png') }}";
</script>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 splash-container">
            <!-- <form method="POST" action="{{ route('admin.survey.save') }}"> -->
                <div class="input-group mt-2">
                    @csrf
                    <input type="text" class="form-control" placeholder="Search"  id="image_search_text">
                    <button class="btn btn-success" id="image_search_button">Search</button>
                    <!-- <button class="btn btn-success" id="collasp">Hide</button> -->
                </div>  
            <!-- </form> -->
            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'All')" id="image_select_all">All</button>
                <button class="tablinks" onclick="openCity(event, 'Unsplash')">Unsplash</button>
                <button class="tablinks" onclick="openCity(event, 'Pixabay')">Pixabay</button>
            </div>
            <div id="search_result">
                <div id="All" class="tabcontent active">
                    <div class="card example-1 square scrollbar-dusty-grass square thin">
                        <div id="images-wrapper">
                            @foreach ($photo as $key => $value)
                                <img src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="p-1 select_image"  draggable="true" ondragstart="imgDrag(event)">
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="Unsplash" class="tabcontent">
                    <div class="card example-1 square scrollbar-dusty-grass square thin">
                        <div id="images-wrapper">
                            @foreach ($photo as $key => $value)
                                <img src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="p-1 select_image"  draggable="true" ondragstart="imgDrag(event)">
                            @endforeach
                        </div>
                    </div> 
                </div>
                <div id="Pixabay" class="tabcontent">
                    <div class="card example-1 square scrollbar-dusty-grass square thin">
                        <div id="images-wrapper">
                            @foreach ($pixa_photo as $key => $value)
                                <img src="{{$value->imageURL}}" class="p-1 select_image"  draggable="true" ondragstart="imgDrag(event)">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
    @include('admin/survey/modal', ['title' => '質問追加', 'modal_id' => 'AddQuestion', 'items' => $question_types])
    @include('admin/survey/modal', ['title' => '回答追加', 'modal_id' => 'AddAnswer', 'items' => $answer_types])
    @include('admin/survey/modal', ['title' => '回答追加', 'modal_id' => 'AddSubQuestion', 'items' => $answer_types])
    {{--    @include('admin/survey/subQue_Modal', ['title' => 'サーブ質問追加', 'modal_id' => 'AddSubQuestion'])--}}

@endsection
