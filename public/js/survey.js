$(function() {
    $('.gradient_background').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
});

$('select[name="gradient_color"]').on('change', function() {
   $('.gradient_background').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
    $('.preview-gradient').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
});

$('input[name=border_color]').on('change', function() {
    $('#preview-text').css('border', '1px solid ' + $(this).val())
})
$('input[name=char_color]').on('change', function() {
    $('#preview-text').css('color', $(this).val());
})
$('input[name=callout_color]').on('change', function() {
    $('#preview-text').css('background', $(this).val());
})

$('.answers-option').on('change', function() {
    var question_id = this.id.split('_')[0];
    if(this.id.includes('_answer_count_above_10')) {
        $(`select[name='questions[q_${question_id}][answer_count_less_10]']`).prop('hidden', true);
        $(`input[name='questions[q_${question_id}][answer_count_above_10]']`).prop('hidden', false);
    }
    if(this.id.includes('_answer_count_less_10')) {
        $(`select[name='questions[q_${question_id}][answer_count_less_10]']`).prop('hidden', false);
        $(`input[name='questions[q_${question_id}][answer_count_above_10]']`).prop('hidden', true);
    }
    if(this.id.includes('_answer_count_no_limit')) {
        $(`select[name='questions[q_${question_id}][answer_count_less_10]']`).prop('hidden', true);
        $(`input[name='questions[q_${question_id}][answer_count_above_10]']`).prop('hidden', true);
    }
})

document.getElementById('All').style.display = "block";

$('#image_search_button').click(function(e){
    let search_key = $('#image_search_text').val();

    $.ajax({
        type:'POST',
        url:image_search_url, // Replace with your own URL
        data:{
            search_key:search_key,
            '_token': csrfToken 
        }, // Replace with your own data
        success:function(res){
            $('#search_result').html(res);
            document.getElementById('All').style.display = "block";
        }
    });
})


function openWidgetIcon() {
    $('#widget_file_input').click();
}

function handleFileSelect(evt) {
    let files = evt.target.files; // FileList object

    // use the 1st file from the list
    let f = files[0];
    
    let reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        return function(e) {
            jQuery( '#widget_img' ).attr("src", e.target.result );
            jQuery( 'input[name="widget_image"]' ).val(e.target.result);
            widgetPreview();
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
}

document.getElementById('widget_file_input').addEventListener('change', handleFileSelect, false);

$(`textarea[name="widget_text"], #chk_widget_show, input[name="widget_text_color"]
    , input[name="widget_bg_color"], input[name="widget_width"], select[name="widget_width_unit"]
    , input[name="widget_height"], select[name="widget_height_unit"]`).on("change", widgetPreview);

$(`input[name="widget_position"]`).on("click", widgetPreview);

var data_key = false;

function hexToRgbA(hex){
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',1)';
    }
    throw new Error('Bad Hex');
}

function getWidgetMarkup(area) {
    var position = document.querySelector('input[name="widget_position"]:checked').value;
    var zoom_str = area == 'preview'? "width: 200%; height: 200%; transform: scale(0.5); transform-origin: 0 0;": ''
    var show = $('input[name="widget_show"]').val() == 1;
    var body_css = area == 'preview'? " body {margin: 0!important} ": '';
    
    return  `<style>
        ${body_css}
        .container_${token} { position: fixed!important; z-index: 10000; bottom: 0; top: 2px; left: 10px; margin-top: -10px!important; width: 100%; height: 100%; display: flex; justify-content: ${position}; align-items: end; ${zoom_str}}
        .layout_${token} {text-align: center;}
        .layout_inner_${token} {text-align: left;}
        .formstylee_widget_${token} { display: flex; justify-content: ${position}; margin: 0 20px 2px 20px;}
        .formstylee_qa_frame_${token} { border: none; border-radius: 10px;  margin: 25px; margin-bottom: 0; width: ${$('input[name="widget_width"]').val() + $('select[name="widget_width_unit"]').val()}; height:${$('input[name="widget_height"]').val()}${$('select[name="widget_height_unit"]').val()} }
        .formstylee_widget_message_${token} { 
            color: ${hexToRgbA($('input[name="widget_text_color"]').val())}; 
            background-color: ${hexToRgbA($('input[name="widget_bg_color"]').val())}; 
            display: inline-block;
            position: relative;
            margin: 10px 0 5px;
            border-radius: 22px;
            line-height: 12px;
            height: 45px;
            box-shadow: 0px 3px 3.5px 0px rgba(0, 0, 0, 0.5);
        }
        .formstylee_widget_img_${token} {
            margin: 3px 10px;
        }
        .formstylee_widget_inner_${token} {

        }
        .icon_close_${token} {
            z-index: 21000;
        }
        .icon_close_${token}:hover {
            color: grey;
        }
        .close_${token} {
            position: relative;
            display: flex;
            justify-content: flex-start;
            width: 80%;
            height: 0;
            padding: 0 3%;
            z-index: 21000;
        }
        .icon_close_${token} img {
            width: 35px;
            height: 35px;
        }
        @media screen and (max-width: 450px) {
            .icon_close_${token} img {
                width: 23px;
                height: 23px;
                position: absolute;
                top: 15px;
            }
            .container_${token} { 
                ${area != 'preview'? `
                    position: fixed!important; 
                    bottom: 0px; 
                    top: 0px; 
                    left: 0px; 
                    margin-top: 0!important; 
                    display: block; 
                `: ''}
            }
            .formstylee_widget_${token} {
                ${area != 'preview'? `
                    bottom: 0;
                    position: fixed;
                `: ''}
            }
            .formstylee_qa_frame_${token} { 
                margin: 0px;
                width: 100%;
                height: 100vh;
                position: absolute;
                z-index: 20000;
            } 
        }
    </style>
    <div class="container_${token}">
        <div class="layout_${token}">
            <div class="close_${token}">
                <div class="icon_close_${token}"><img src="https://styleboard.xbiz.jp/img/widget_close.png"></img></div>
            </div>
            <div class="layout_inner_${token}">
                <iframe 
                    class="formstylee_qa_frame_${token}" 
                    style="display: none;"
                    src="https://styleboard.xbiz.jp/client/?id=${token}">
                </iframe>
                <div class="formstylee_widget_${token}">
                    <div id="formstylee_close_widget_message_${token}" style="margin-right: 5px; margin-top: 0px; ${!show? 'display: none;': ''}">
                        <img id="formstylee_close_widget_img_${token}" src="${remove_image_path}" width="17px" height="17px"/>
                    </div>
                    <div style="display: flex;">
                        <div class="formstylee_widget_message_${token}" style="${!show? 'display: none;': ''}"> 
                            <div style="display: flex; font-weight:800; justify-content: center; align-items: center; padding: 0px 30px; height: 100%;">
                                <div>${$('textarea[name="widget_text"]').val()}</div>
                            </div>
                        </div>
                        <img class="formstylee_widget_img_${token}" src="${$('input[name="widget_image"]').val()}" width="60px" height="60px"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
    // src="https://styleboard.xbiz.jp/client/?id=${token}">
}

function widgetPreview() {
    var copytag_str = getWidgetMarkup('copytag').replace(/>\s+</g,'><').replace(/\s\s+/g, ' ');
    var main_action_str = `
        document.getElementsByClassName('formstylee_widget_message_${token}')[0].addEventListener('click', openWidget_${token}, false);
        document.getElementsByClassName('formstylee_widget_img_${token}')[0].addEventListener('click', openWidget_${token}, false);
        document.getElementById('formstylee_close_widget_img_${token}').addEventListener('click', handleRemoveMessage${token}, false);
        document.getElementsByClassName('icon_close_${token}')[0].addEventListener('click', closeWidget_${token}, false);
        var close_element = document.getElementsByClassName('icon_close_${token}')[0];
        var element = document.getElementsByClassName('formstylee_qa_frame_${token}')[0];
        var widget = document.getElementsByClassName('formstylee_widget_${token}')[0];
        close_element.style.visibility="hidden";
        
        function openWidget_${token}() {
            element.style.display="block";
            close_element.style.visibility="visible";
            widget.style.display = "none";
        }
        function closeWidget_${token}(e) {
            e.preventDefault();
            console.log('close');
            element.style.display="none";
            close_element.style.visibility="hidden";
            widget.style.display = "block";
        }
        function handleRemoveMessage${token}(event) {
            event.preventDefault();
            var element = document.getElementsByClassName('formstylee_widget_message_${token}')[0];
            element.remove();

            document.getElementById('formstylee_close_widget_message_${token}').remove();
        }`;
    var action_str = `
        <script>
        fetch("${widget_get_path}?id=${token}", {headers: {"Content-Type": "application/json"}})
        .then((response) => response.json())
        .then(data => {
            document.getElementsByTagName('body')[0].innerHTML += data.data;
            ${main_action_str}
        });
        </script>
    `.replace(/>\s+</g,'><').replace(/\s\s+/g, ' ');

    $('#copytag').text(action_str);

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: widget_save_path,
        data: { id: token, widget_context: copytag_str },
        type: 'post',
        dataType: 'json',
        success: (data) => {
            console.log('success')
        }
    })

    copytag_str = getWidgetMarkup('preview').replace(/>\s+</g,'><').replace(/\s\s+/g, ' ');
    var iframe = document.createElement('iframe');
    iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(copytag_str + `<script>${main_action_str}</script>`);
    iframe.style.width = "100%";
    document.getElementById('widget_preview').innerHTML = "";
    document.getElementById('widget_preview').appendChild(iframe);
}

widgetPreview();

var sub_images_index = 0;
function addSubAnswerImage(q_index, a_index) {
    var image_input_name = `questions[q_${q_index}][answers][a_${a_index}][sub_images][${sub_images_index}]`;
    var image_input_id = `sub_images_${q_index}_${a_index}_${sub_images_index}`;
    document.getElementById('_answer_images_' + a_index).insertAdjacentHTML('beforeend', `
        <input hidden name="${image_input_name}" type="file" 
            data-container="${q_index}_${a_index}_image_container" 
            data-a_index="${a_index}"
            id="${image_input_id}"
        />
        <input name="${image_input_name}" type="hidden" 
            data-container="${q_index}_${a_index}_image_container" 
            data-a_index="${a_index}"
            id="${image_input_id}_hidden"
        />
    `);
    $(`#${image_input_id}`).click();
    $(`#${image_input_id}`).change(answerImageUpload)
    sub_images_index ++;
}

function answerImageUpload(event) {
    var container = $(event.target).data("container" );
    var answer_card = '_answer_' + $(event.target).data("a_index" );
    var a_index = $(event.target).data("a_index" );

    let files = event.target.files; // FileList object

    // use the 1st file from the list
    let f = files[0];
    let sub_img_hidden_id = '#'+event.target.id + '_hidden';
    $(sub_img_hidden_id).val(f.name);
    let reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        return function(e) {
            var image_count = Number($(`#_answer_images_${a_index}_count`).val()) + 1;
            $(`#_answer_images_${a_index}_count`).val(image_count);
            $('#' + answer_card).css('width', String(240 + 110 * Math.ceil(image_count/2)) + 'px');
            document.getElementById(container).innerHTML += `
            <div style="position: relative;" id="image_url_${sub_images_index}_created" >
                <img class="card-img-top mb-1" style="position: relative; height: 106px; width: 106px; margin: 2px; border-radius: 5px;" src="${e.target.result}" ondrop="imgDrop(event)" ondragover="imgAllowDrop(event)" id="${event.target.id}">
                <button class="btn" style="position: absolute; right: -8px; top: -8px; padding: 0;" onclick="removeCreatedSubAnswerImage(event, ${sub_images_index}, '${container}')">
                    <img src="${remove_image_path}" style="width: 15px; height: 15px;"></img>
                </button>
            </div>`;
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
}

var remove_images_index = 0;
function removeSubAnswerImage(event, image_id) {
    event.preventDefault();
    
    document.getElementById('image_url_' + image_id).style.display = 'none';
    document.getElementById('remove_images').innerHTML += `<input hidden name="remove_images[${remove_images_index}]" type="text" value="${image_id}" />`;
    remove_images_index ++;
}

function removeCreatedSubAnswerImage(event, image_id, container) {
    event.preventDefault();
    document.getElementById('image_url_' + image_id + '_created').style.display = 'none';
    $(`input[data-container='${container}']`).remove();
}

function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function openNav() {
    document.getElementById("mySidenav").style.width = "14rem";
}
  
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function imgAddDrop(ev) {
    let div_id = ev.target.id;
    let ids = div_id.split('_');
    let q_index = ids[0];
    let a_index = ids[1];
    if(q_index == 'sub') return true;
    if(q_index == 'image') {
        let div = $("#"+div_id).parent();
        let parent_div_id = $(div).attr('id');
        if(parent_div_id.split('_')[0] == 'image'){
            let div1 = $("#"+parent_div_id).parent();
            div_id = $(div1).attr('id');
        } else {
            div_id = parent_div_id;
        }
        ids = div_id.split('_');
        q_index = ids[0];
        a_index = ids[1];
    }
    var image_input_name = `questions[q_${q_index}][answers][a_${a_index}][sub_images][${sub_images_index}]`;
    var image_input_id = `sub_images_${q_index}_${a_index}_${sub_images_index}`;
    ev.preventDefault();
    var data = ev.dataTransfer.getData("drag_src");

    document.getElementById('_answer_images_' + a_index).insertAdjacentHTML('beforeend', `
        <input hidden name="${image_input_name}" type="file" 
            data-container="${q_index}_${a_index}_image_container" 
            data-a_index="${a_index}"
            id="${image_input_id}"
            value="${data}"
        />
        <input name=${image_input_name} type="hidden" 
            id="${image_input_id}_hidden"
            value="${data}"
        />
    `);
    var answer_card = '#_answer_'+a_index;
    var image_count = Number($(`#_answer_images_${a_index}_count`).val()) + 1;
    $(`#_answer_images_${a_index}_count`).val(image_count);
    $(answer_card).css('width', String(240 + 110 * Math.ceil(image_count/2)) + 'px');
    document.getElementById(div_id).innerHTML += `
        <div style="position: relative;" id="image_url_${sub_images_index}_created" >
            <img class="card-img-top mb-1" style="position: relative; height: 106px; width: 106px; margin: 2px; border-radius: 5px;" src="${data}" ondrop="imgDrop(event)" ondragover="imgAllowDrop(event)" id="${image_input_id}">
            <button class="btn" style="position: absolute; right: -8px; top: -8px; padding: 0;" onclick="removeCreatedSubAnswerImage(event, ${sub_images_index}, '${div_id}')">
                <img src="${remove_image_path}" style="width: 15px; height: 15px;"></img>
            </button>
        </div>`;
    sub_images_index ++;
}

function imageScroll(type)
{
    var scrollHeight = $(`#card_image_view_${type}_view`).height();
    var scrollPosition = $(`#card_image_view_${type}`).height() + $(`#card_image_view_${type}`).scrollTop();
    if ((scrollHeight - scrollPosition) / scrollHeight < 0.1 && !data_key) {
        console.log("You have reached the bottom!");
        data_key = true;
        data_key = true;
        let search_key = $('#image_search_text').val();
        $('.loading').show();
        $.ajax({
            type:'POST',
            url:image_search_all, // Replace with your own URL
            data:{
                search_key:search_key,
                type:type,
                '_token': csrfToken 
            }, // Replace with your own data
            success:function(res){
                $(`#card_image_view_${type}_view`).append(res);
                data_key = false;
                console.log('----------end--------');
                $('.loading').hide();
            }
        });
    }
}

// add button drag & drop
function addButtonDrop(e, q_index, a_index)
{
    let div_id = `${q_index}_${a_index}_image_container`;
    var image_input_name = `questions[q_${q_index}][answers][a_${a_index}][sub_images][${sub_images_index}]`;
    var image_input_id = `sub_images_${q_index}_${a_index}_${sub_images_index}`;
    e.preventDefault();
    var data = e.dataTransfer.getData("drag_src");

    document.getElementById('_answer_images_' + a_index).insertAdjacentHTML('beforeend', `
        <input hidden name="${image_input_name}" type="file" 
            data-container="${q_index}_${a_index}_image_container" 
            data-a_index="${a_index}"
            id="${image_input_id}"
            value="${data}"
        />
        <input name=${image_input_name} type="hidden" 
            id="${image_input_id}_hidden"
            value="${data}"
        />
    `);
    var answer_card = '#_answer_'+a_index;
    var image_count = Number($(`#_answer_images_${a_index}_count`).val()) + 1;
    $(`#_answer_images_${a_index}_count`).val(image_count);
    $(answer_card).css('width', String(240 + 110 * Math.ceil(image_count/2)) + 'px');
    document.getElementById(div_id).innerHTML += `
        <div style="position: relative;" id="image_url_${sub_images_index}_created" >
            <img class="card-img-top mb-1" style="position: relative; height: 106px; width: 106px; margin: 2px; border-radius: 5px;" src="${data}" ondrop="imgDrop(event)" ondragover="imgAllowDrop(event)" id="${image_input_id}">
            <button class="btn" style="position: absolute; right: -8px; top: -8px; padding: 0;" onclick="removeCreatedSubAnswerImage(event, ${sub_images_index}, '${div_id}')">
                <img src="${remove_image_path}" style="width: 15px; height: 15px;"></img>
            </button>
        </div>`;
    sub_images_index ++;
}

$('#image_search_close').click(function(){
    $('#image_search_text').val('');
});


const input = document.getElementById('image_search_text');

input.addEventListener('keydown', function(event) {
    // check if the enter key was pressed (key code 13)
    if (event.keyCode === 13) {
        // do something when enter key is pressed
        let search_key = $('#image_search_text').val();

        $.ajax({
            type:'POST',
            url:image_search_url, // Replace with your own URL
            data:{
                search_key:search_key,
                '_token': csrfToken 
            }, // Replace with your own data
            success:function(res){
                $('#search_result').html(res);
                document.getElementById('All').style.display = "block";
            }
        });
    }
});

$('.image-container').click(function(e){
    if($(this).attr('data-zoom') == 1){
        $(this).removeClass('image-zoom-in');
        $(this).removeClass('image-zoom-in1');
        $(this).attr('data-zoom',0);
        return true;
    }
    $('.image-container').each(function() {
        $(this).removeClass('image-zoom-in');
        $(this).removeClass('image-zoom-in1');
    });
    if($(this).attr('data-left') == 0)
        $(this).addClass('image-zoom-in');
    else 
        $(this).addClass('image-zoom-in1');
    $(this).attr('data-zoom',1);
});

function imageClick(e){
    if($(e).attr('data-zoom') == 1){
        $(e).removeClass('image-zoom-in');
        $(e).removeClass('image-zoom-in1');
        $(e).attr('data-zoom',0);
        return true;
    }
    $('.image-container').each(function() {
        $(this).removeClass('image-zoom-in');
        $(this).removeClass('image-zoom-in1');
    });
    if($(e).attr('data-left') == 0)
        $(e).addClass('image-zoom-in');
    else 
        $(e).addClass('image-zoom-in1');
    $(e).attr('data-zoom',1);
}