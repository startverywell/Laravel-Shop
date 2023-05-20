var q_index = 0;
var a_index = 0;
(function ($) {
    $('#btnAddQuestion').click(function () {
        $('#modalAddQuestion').modal('toggle');
        let selectedType = $('#optAddQuestion').children("option:selected").val();
        const textVal = $('#txtAddQuestion').val();
        if(q_index === 0 ){
            q_index = $('#questions-container').children('.question').length;
        }else{
            q_index++;
        }
        let q_ord = $('#questions-container').children('.question').length + 1;
        if (selectedType == 1) {
            var answer_count_options = ""
            for(var i = 1; i <= 10; i++) {
                answer_count_options += `<option value="${i}">${i}</option>`
            }
            $('#questions-container').append(`
                <div class="question" id="question_${q_index}">
                    <input type="hidden" value="${selectedType}" name="questions[q_${q_index}][type]">
                    <div class="row form-group ">
                        <label class="col-md-1 ml-2 pl-1 col-form-label d-flex align-items-center">質問${q_ord}:</label>
                        <div class="col-md-3">
                            <input type="text" placeholder="質問" class="form-control" value="${textVal}" name="questions[q_${q_index}][title]" required>
                        </div>
                        
                        <div class="col-md-6 d-flex align-items-center question-options">
                            <div class="d-flex align-items-center">
                                <input type="radio" class="p-1 answers-option" name="questions[q_${q_index}][option]" value="1" id="${q_index}_answer_count_less_10"/>
                                <label for="${q_index}_answer_count_less_10" class="block-label p-1">回答数</label>
                                <select class="form-control" name="questions[q_${q_index}][answer_count_less_10]" 
                                    style="width: 60px;" disabled>
                                   ${answer_count_options}
                                </select>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="p-1 answers-option" name="questions[q_${q_index}][option]" value="2" id="${q_index}_answer_count_above_10" />
                                <label for="${q_index}_answer_count_above_10" class="block-label p-1">指定する<br>(10点以上)</label>
                                <input min="0" type="number" style="width: 60px; height: 35px" name="questions[q_${q_index}][answer_count_above_10]" value="" disabled/>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="radio" class="p-1 answers-option" name="questions[q_${q_index}][option]" value="0" id="${q_index}_answer_count_no_limit" checked />
                                <label for="${q_index}_answer_count_no_limit" class="block-label p-1">指定しない</label>
                            </div>
                        </div>

                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger" onclick="onDelete('question_${q_index}')"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div id="answers_${q_index}" class="d-flex flex-wrap">

                        </div>
                        <div class="d-block mb-5">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAddAnswer" data-answerid="answers_${q_index}">+</button>
                            <button type="button" class="btn btn-outline-warning ml-auto" data-toggle="modal" data-target="#modalAddSubQuestion" data-subQueid="answers_${q_index}">+</button>
                        </div>
                    </div>
                </div>
            `);
        }else if (selectedType == 2) {
            $('#questions-container').append(`
                <div class="question" id="question_${q_index}">
                <input type="hidden" value="${selectedType}" name="questions[q_${q_index}][type]">
                <div class="row form-group">

                <label  class="ml-2 pl-1 col-form-label d-flex align-items-center">質問${q_ord}:</label>
                <div class="col-md-10">
                <input type="text" placeholder="質問" class="form-control" value="${textVal}" name="questions[q_${q_index}][title]" required>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger" onclick="onDelete('question_${q_index}')"><i class="fa fa-times"></i></button>
                </div>
                </div>
                <div class="row form-group">
                <div class="col-md">
                <input type="file" class="form-control" name="questions[q_${q_index}][file_url]">
                </div>
                </div>
                <div class="d-flex mb-2">
                    <div id="answers_${q_index}" class="d-flex flex-wrap">

                    </div>
                    <div class="d-block mb-5">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAddAnswer" data-answerid="answers_${q_index}">+</button>
                        <button type="button" class="btn btn-outline-warning ml-auto" data-toggle="modal" data-target="#modalAddSubQuestion" data-subQueid="answers_${q_index}">+</button>
                    </div>

                </div>
                </div>
            `);
        } else if (selectedType == 3) {
            $('#questions-container').append(`
                <div class="question" id="question_${q_index}">
                <input type="hidden" value="${selectedType}" name="questions[q_${q_index}][type]">
                <div class="row form-group">

                <label  class="ml-2 pl-1 col-form-label d-flex align-items-center">質問${q_ord}:</label>
                <div class="col-md-10">
                <input type="text" placeholder="質問" class="form-control" value="${textVal}" name="questions[q_${q_index}][title]" required>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger" onclick="onDelete('question_${q_index}')"><i class="fa fa-times"></i></button>
                </div>
                </div>
                <div class="row form-group">
                <div class="col-md-10">
                    <div class="form-group">
                        <input type="file" class="form-control-file d-none" name="questions[q_${q_index}][movie_file]" id="questions[q_${q_index}][movie_file]${q_index}">
                        <input type="hidden" name="questions[q_${q_index}][movie_file_tmp]" data-name="questions[q_${q_index}][movie_file]${q_index}" data-index="questions[q_${q_index}][movie_file_tmp]" id="questions[q_${q_index}][movie_file_tmp]${q_index}" value="-">
                        <label for="questions[q_${q_index}][movie_file]${q_index}" class="form-control-label btn btn-primary">動画を選択してください。</label>
						<p class="text-danger">（* テキスト）</p>
						<a href="https://cloudconvert.com/mov-to-mp4">https://cloudconvert.com/mov-to-mp4</a>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" onclick="onDeleteMovie('questions[q_${q_index}][movie_file]', '${q_index}')">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                <textarea class="form-control" name="questions[q_${q_index}][movie_source]"  placeholder="ソースコード(iframe)でアップ（1024以下の文字を入力してください。）"></textarea>
                </div>
                <div class="col-md-12 mt-2">
                <input type="text" class="form-control" name="questions[q_${q_index}][movie_url]"  placeholder="URLでアップ(YoutubeなどのURL)">
                </div>
                </div>
                <div class="d-flex mb-2">
                    <div id="answers_${q_index}" class="d-flex flex-wrap">

                    </div>
                    <div class="d-block mb-5">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAddAnswer" data-answerid="answers_${q_index}">+</button>
                        <button type="button" class="btn btn-outline-warning ml-auto" data-toggle="modal" data-target="#modalAddSubQuestion" data-subQueid="answers_${q_index}">+</button>
                    </div>

                </div>
                </div>
            `);

            $("input[type=file]").on('change', function () {
                var file = $(this).prop('files')[0];
                var id = $(this).attr('id');
                $('label[for="' + id + '"]').text(file.name);
                $('input[data-name="' + id + '"]').val(file.name);
            });
        }

    });

    $('input[name="background_color"]').change(function () {
        $('#preview').css('background', $(this).val());
    })

    // $('input[name="char_color"]').change(function () {
    //     $('#preview-text').css('background', $(this).val());
    // })

    $('input[name="border_color"]').change(function () {
        $('#preview-img').css('border', `4px solid ` + $(this).val());
    })

    $('#questions-container').on('click', '[data-toggle="modal"]', function (e) {
        let $target = $(e.target);
        let value = $target.data('answerid');
        $('#container-id').val(value);
        $('#sub-container-id').val(value);console.log(value);
    });

    $('#btnAddAnswer').on('click',function (e) {
        $('#modalAddAnswer').modal('toggle');
        let selectedType = $('#optAddAnswer').children("option:selected").val();
        const textVal = $('#txtAddAnswer').val();
        const a_container = $('#container-id').val();
        const aq_index = a_container.substr(8);console.log(aq_index)
        if(q_index === 0 ){
            a_index = $('.answer').length;
        }else{
            a_index++;
        }
        if(selectedType === "1" || selectedType === "3") {
            $(`#${a_container}`).append(`
                <div class="answer card mr-2 mb-2" id="_answer_${a_index}">
                    <div class="card-header d-flex justify-content-between p-2">
                        <span>回答</span>
                        <a class="text-danger" onclick="onDelete('_answer_${a_index}')"><i class="fa fa-times"></i></a>
                    </div>
                    <div class="card-body p-2">
                        <input type="hidden" value="${selectedType}" name="questions[q_${aq_index}][answers][a_${a_index}][type]">
                        <textarea placeholder="回答" class="form-control" name="questions[q_${aq_index}][answers][a_${a_index}][title]" required>${textVal}</textarea>
                        <input placeholder="値" class="form-control mt-1" name="questions[q_${aq_index}][answers][a_${a_index}][value]" value="" />
                    </div>
                </div>
            `);
        } else if (selectedType === "2") {
            $(`#${a_container}`).append(`
                <div class="answer card mr-2 mb-2" id="_answer_${a_index}">
                    <div id="_answer_images_${a_index}"></div>
                    <input id="_answer_images_${a_index}_count" value="0" hidden>
                    <div class="card-header d-flex justify-content-between p-2">
                        <span>回答</span>
                        <div class="btn" style="padding: 0" onclick="addSubAnswerImage(${aq_index}, ${a_index})">
                            <img src="${plus_image_path}" style="width: 25px; height: 25px;"/>
                        </div>
                        <a class="text-danger" onclick="onDelete('_answer_${a_index}')"><i class="fa fa-times"></i></a>
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex">
                            <div class="card-img-top mb-1" style="position: relative;">
                                <img class="card-img-top mb-1" style="position: relative; width:auto; max-width:100%; height:100%;" src="" id="${aq_index}_${a_index}_main_img" ondrop="imgDrop(event)" ondragover="imgAllowDrop(event)"/>
                            </div>
                            <div class="d-flex" style="flex-direction: column; flex-flow: row wrap; height: 220px;" id="${aq_index}_${a_index}_image_container">
                            </div>
                        </div>
                        <input type="hidden" value="${selectedType}" name="questions[q_${aq_index}][answers][a_${a_index}][type]">
                        <input type="file" class="form-control mb-2" name="questions[q_${aq_index}][answers][a_${a_index}][file_url]" onchange="handleMainImgChange(event, ${aq_index}, ${a_index})">
                        <input type="hidden" class="form-control mb-2" name="questions[q_${aq_index}][answers][a_${a_index}][image]" id="${aq_index}_${a_index}_main_img_hidden">
                        <textarea placeholder="回答" class="form-control" name="questions[q_${aq_index}][answers][a_${a_index}][title]" required>${textVal}</textarea>
                        <input placeholder="値" class="form-control mt-1" name="questions[q_${aq_index}][answers][a_${a_index}][value]" value="" />
                    </div>
                </div>
            `);
        }

    });

    $('#btnAddSubQuestion').on('click',function (e) {
        $('#modalAddSubQuestion').modal('toggle');
        let selectedType = $('#optAddSubQuestion').children("option:selected").val();
        const textVal = $('#txtAddSubQuestion').val();
        const a_container = $('#sub-container-id').val();
        $('button[data-answerid=' + a_container + ']').attr('disabled', 'disabled');
        const aq_index = a_container.substr(11);
        if(q_index === 0 ) {
            a_index = $('.answer').length;
        }else {
            a_index++;
        }

        $(`#${a_container}`).append(`
            <div class="row form-group" id="_sub_que_${aq_index}">
                <label class="col-md-2 pl-1 col-form-label d-flex align-items-center"></label>
                <div class="col-md-9">
                    <textarea placeholder="サーブ質問" class="form-control" name="questions[q_${aq_index}][sub_title]" required>${textVal}</textarea>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger" onclick="onSubDelete('${aq_index}')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `);
    });

    $('input#chk-qrcode-show').change(function () {
        if ($(this).is(':checked')) {
            $('input[name=qrcode_show]').val(1);
        } else {
            $('input[name=qrcode_show]').val(0);
        }
    })
    
    $('input#chk-brand-description-show').change(function () {
        if ($(this).is(':checked')) {
            $('input[name=brand_description_show]').val(1);
        } else {
            $('input[name=brand_description_show]').val(0);
        }
    })

    $('input#chk-title-show').change(function () {
        if ($(this).is(':checked')) {
            $('input[name=title_show]').val(1);
        } else {
            $('input[name=title_show]').val(0);
        }
    })

    $('input#chk_widget_show').change(function () {
        if ($(this).is(':checked')) {
            $('input[name=widget_show]').val(1);
        } else {
            $('input[name=widget_show]').val(0);
        }
    })

})(jQuery);

function onDelete(id) {
    result = window.confirm('本当に削除しますか？')
    if(result) {
        $(`#${id}`).remove();
    }
}

function onSubDelete(id) {
    result = window.confirm('本当に削除しますか？')
    if(result) {
        $('#_sub_que_' + id).remove();
    }

    $('button[data-answerid=subQues-div' + id + ']').removeAttr('disabled');
}

$(function () {
    $("input[type=file]").on('change', function () {
        var file = $(this).prop('files')[0];
        var id = $(this).attr('id');
        if(file.size > 10485760) {
            alert('10MB以下のファイルを選択してください。');
            return;
        }

        $('label[for="' + id + '"]').text(file.name);
        $('input[data-name="' + id + '"]').val(file.name);
    });
});

function onDeleteMovie(name, id) {
    $('[name="' + name + '"]').val('');
    $('[data-index="' + name + '"]').val('-');
    $('label[for="' + name + id + '"]').text('動画を選択してください。');
}

function handleMainImgChange(event, q_index, a_index) {
    var file = event.target.files[0];
    const reader = new FileReader();
    let file_tag_id = '#'+q_index+'_'+a_index+'_main_img_hidden';
    
    $(file_tag_id).val(file.name);
    
    reader.addEventListener("load", () => {
        const preview = document.getElementById(`${q_index}_${a_index}_main_img`);
        // convert image file to base64 string
        preview.src = reader.result;
    }, false);
    
    if (file) {
        reader.readAsDataURL(file);
        // $('input[type="hidden"][name="'+file_tag_name+'"]').val(file.name);
    }
}

function imgAllowDrop(ev) {
    ev.preventDefault();
}
  
function imgDrag(ev) {
    ev.dataTransfer.setData("drag_src", ev.target.src);
}
  
function imgDrop(ev) {
    let img_id = ev.target.id;
    let file_tag_id = '#'+img_id+'_hidden';
    ev.preventDefault();
    var data = ev.dataTransfer.getData("drag_src");
    ev.target.src = data;
    $(file_tag_id).val(data);
}