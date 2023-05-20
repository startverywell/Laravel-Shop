/* javascriptのコードを記載 */

var test_flag = false;
// var domain_name = 'https://admin.formstylee.com/';
var domain_name = 'https://styleboard.xbiz.jp/';
if (test_flag) {
    // domain_name = 'http://192.168.158.165/';
    domain_name = 'http://127.0.0.1:8000/';
}

var EPPZScrollTo =
{
    /**
     * Helpers.
     */
    documentVerticalScrollPosition: function()
    {
        if (self.pageYOffset) return self.pageYOffset; // Firefox, Chrome, Opera, Safari.
        if (document.documentElement && document.documentElement.scrollTop) return document.documentElement.scrollTop; // Internet Explorer 6 (standards mode).
        if (document.body.scrollTop) return document.body.scrollTop; // Internet Explorer 6, 7 and 8.
        return 0; // None of the above.
    },

    viewportHeight: function()
    { return (document.compatMode === "CSS1Compat") ? document.documentElement.clientHeight : document.body.clientHeight; },

    documentHeight: function()
    { return (document.height !== undefined) ? document.height : document.body.offsetHeight; },

    documentMaximumScrollPosition: function()
    { return this.documentHeight() - this.viewportHeight(); },

    elementVerticalClientPositionById: function(id)
    {
        var element = document.getElementById(id);
        var rectangle = element.getBoundingClientRect();
        return rectangle.top;
    },

    /**
     * Animation tick.
     */
    scrollVerticalTickToPosition: function(currentPosition, targetPosition)
    {
        var filter = 0.2;
        var fps = 60;
        var difference = parseFloat(targetPosition) - parseFloat(currentPosition);

        // Snap, then stop if arrived.
        var arrived = (Math.abs(difference) <= 0.5);
        if (arrived)
        {
            // Apply target.
            scrollTo(0.0, targetPosition);
            return;
        }

        // Filtered position.
        currentPosition = (parseFloat(currentPosition) * (1.0 - filter)) + (parseFloat(targetPosition) * filter);

        // Apply target.
        scrollTo(0.0, Math.round(currentPosition));

        // Schedule next tick.
        setTimeout("EPPZScrollTo.scrollVerticalTickToPosition("+currentPosition+", "+targetPosition+")", (1000 / fps));
    },

    /**
     * For public use.
     *
     * @param id The id of the element to scroll to.
     * @param padding Top padding to apply above element.
     */
    scrollVerticalToElementById: function(id, padding)
    {
        var element = document.getElementById(id);
        if (element == null)
        {
            console.warn('Cannot find element with id \''+id+'\'.');
            return;
        }

        var targetPosition = this.documentVerticalScrollPosition() + this.elementVerticalClientPositionById(id) - padding;
        var currentPosition = this.documentVerticalScrollPosition();

        // Clamp.
        var maximumScrollPosition = this.documentMaximumScrollPosition();
        if (targetPosition > maximumScrollPosition) targetPosition = maximumScrollPosition;

        // Start animation.
        this.scrollVerticalTickToPosition(currentPosition, targetPosition);
    }
};

function newline(str) {
    str = str.replace(/(?:\r\n|\r|\n)/g, '<br>');
    return str;
}

function threeDigitComma(val) {
    var disp_price = "";
    if(val){
        var num = String(val).replace(/(\d)(?=(\d\d\d)+$)/g, "$1,");
        disp_price = num;
    }
    return disp_price;
}

var current = 1;
var delay_time = 1500;
var total_questions = -1;
var question_order = -1;
var profile_img_url = '';
var avart_name = '';
var progress_status = -1;
var initial_data = null;
var gradient_attrs = [
    'linear-gradient(0deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 100%)', 
    'linear-gradient(160deg, rgba(63,227,220,1) 0%, rgba(51,129,251,1) 50%, rgba(112,109,246,1) 100%)', 
    'linear-gradient(90deg, rgba(250,116,149,1) 0%, rgba(253,223,65,1) 100%)', 
    'linear-gradient(130deg, rgba(240,147,251,1) 0%, rgba(244,87,108,1) 100%)', 
    'linear-gradient(160deg, rgba(32,211,252,1) 0%, rgba(182,32,254,1) 100%)', 
    'linear-gradient(180deg, rgba(243,244,135,1) 0%, rgba(151,250,194,1) 100%)'
];
var items_count = 0;
var price_count = 0;
var items = [];
var items_price = [];
var items_title = [];
var userAnswers = [];
var ecFlg = 0;
var product = new Object();
var product = {};
/* function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function fsleep(ms) {
    await sleep(ms);
} */

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
  
function addQ() {
    // $('#content .row-a').each(function() {
    //     $(this).css('min-height', '0');
    // });

    var bouncing_html = '<div class="loadingContainer"><div class="ball1"></div><div class="ball2"></div><div class="ball3"></div></div>';
    var q_html = '<div id="q-' + current + '" class="row row-q" style="display: none;">';
    // q_html += '<div class="avatar"><img src="./assets/img/avatar-default.jpg" alt="" /></div>';
    // q_html += '<div class="avatar-wrapper"><div class="avatar"><img src="' + profile_img_url + '" alt="" /></div><p class="avatar-name">' + avart_name + '</p></div>';

    // var min_height = 'style="min-height: calc(100vh - ' + $('.site-header').outerHeight() + 'px);"';
    // q_html += '<div class="q-area"' + min_height + '>';
    q_html += '<div class="q-area"><div style="display:flex;">';
    if (profile_img_url != null && avart_name != null) {
        q_html += '<div class="avatar-wrapper"><div class="avatar" style="background-image: url(' + modifyImageUrl(profile_img_url) + ');"></div><p class="avatar-name">' + avart_name + '</p></div>';
    }
    q_html += '<div id="q-txt-row-main" class="q-txt-row"><div id="q-txt-main" class="q-txt">' + bouncing_html + '</div></div>';
    q_html += '</div>';
    q_html += '<div id="q-txt-row-sub" class="q-txt-row"><div id="q-txt-sub" class="q-txt">' + bouncing_html + '</div></div>';
    q_html += '<div class="q-a-area" style="display: none;"></div></div></div>';
    q_html += '</div>';
    $('#chatview').append(q_html);
}

function addA(a_height = 0) {
    var bouncing_html = '<div class="loadingContainer"><div class="ball1"></div><div class="ball2"></div><div class="ball3"></div></div>';
    // var q_html = '<div id="a-' + current + '" class="row row-a" style="min-height: ' + a_height + 'px;">';
    var q_html = '<div id="a-' + current + '" class="row row-a">';
    q_html += '<div class="a-area"><div class="a-txt">' + bouncing_html + '</div></div>';
    q_html += '<input id="a-input-' + current + '" type="hidden" name="answers[]" />';
    q_html += '</div>';
    $('#chatview').append(q_html);
}

var img_loaded = false;
var tid = setTimeout(timer01, 1000);
function timer01() {
    if (img_loaded) {
        // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
        img_loaded = false;
    }
    tid = setTimeout(timer01, 1000);
}

function modifyImageUrl(imageUrl) {
    var newUrl = imageUrl;
    if (newUrl != null) {
        newUrl = newUrl.replace("(", "%28");
        newUrl = newUrl.replace(")", "%29");
    }
    return newUrl;
}

$(document).ready(function() {
    $(this).scrollTop(0);

    $('#progress-row').hide();
    if (survey_id == -1) return;

    url = domain_name + 'api/v1/survey/get/' + survey_id;
    var request = jQuery.get(url, function(data) {
        $('#brand').css('background-image', 'url(' + domain_name + modifyImageUrl(data.brand_logo_path) + ')');
        $('#title span').text(data.title);
        $('#brand-name').text(data.brand_name);

        if (data.brand_description != null && data.brand_description != "") {
            $('#brand-desc').show();
            $('#brand-desc').html(newline(data.brand_description));
        }

        if (data.description != null && data.description != "") {
            $('#description').show();
            $('#description').html(newline(data.description));
        }

        if (data.callout_color == null || data.callout_color == "") {
            data.callout_color = "#ffffff";
        }

        if (data.qrcode_show) {
            $('#qrcode img').attr('src', domain_name + 'uploads/surveys/' + data.id + '/qrcode/qrcode.svg');
        }

        data.user_profile_url = data.user_profile_url && data.user_profile_url[0] == '/'? data.user_profile_url.substring(1): data.user_profile_url;
        profile_img_url = domain_name + data.user_profile_url;
        avart_name = data.user_profile_name;
        progress_status = data.progress_status;

        // add_first_question(data);
        initial_data = data;

        total_questions = data.question_count;
        gradient_idx = data.gradient_color;
        var prog_html = '';
        for (i = 0; i < total_questions; i++) {
            prog_html += '<div class="prog-bar-wrap"><div id="prog-bar-' + (i + 1) + '" class="prog-bar"><span></span></div></div>';
        }
        $('#progress-inner').append(prog_html);

        var prog_bar_w = (100 / total_questions) + "%";

        style_string = '<style>';
        style_string += 'body { background-color: ' + data.background_color + ';}';
        // style_string += '.row.row-q .avatar-wrapper .avatar-name {color: ' + data.char_color + ';}';
        style_string += '.row.row-q .q-area .q-txt-row .q-txt {color: ' + data.char_color + '; background: ' + data.callout_color + ';}';
        style_string += '.row.row-q .q-area .q-txt-row .q-txt:before { border-color: transparent ' + data.callout_color + ' transparent transparent;} ';
        // style_string += '.row.row-q .q-area .q-txt-row .q-txt:after { border-color: transparent ' + data.callout_color + ' transparent transparent;} ';
        // style_string += '.row.row-q .q-area .q-a-area .q-a-list { background: rgba(' + hexToRgb(data.border_color).r + ',' + hexToRgb(data.border_color).g + ',' + hexToRgb(data.border_color).b + ', 0.3);}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-list { background: ' + gradient_attrs[gradient_idx] + ';}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-list .q-a-item { color: ' + data.char_color + '; background: ' + data.callout_color + ';}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-list .q-a-item.selected { border-color: ' + data.border_color + ';}';
        // style_string += '.row.row-q .q-area .q-a-area .q-a-form-fields { background: rgba(' + hexToRgb(data.border_color).r + ',' + hexToRgb(data.border_color).g + ',' + hexToRgb(data.border_color).b + ', 0.3);}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-form-fields { background: ' + gradient_attrs[gradient_idx] + ';}';
        style_string += '.row.row-q .q-area .q-a-area .q-a-form-fields p {color: ' + data.char_color + ';}';
        style_string += '.row.row-q .q-area .q-a-area input[type=submit] { border-color: ' + data.border_color + ';} ';
        style_string += '.row.row-a .a-area .a-txt {color: ' + data.char_color + '; background: ' + data.callout_color + ';}';
        style_string += '.row.row-a .a-area .a-txt:before { border-color: transparent transparent transparent ' + data.callout_color + ';} ';
        // style_string += '.row.row-a .a-area .a-txt:after { border-color: transparent transparent transparent ' + data.callout_color + ';} ';

        style_string += 'header .site-header { background-color: ' + data.background_color + ';} ';
        style_string += 'header .btn-start { color: ' + data.char_color + '; background: ' + data.callout_color + ';} ';

        if (data.title_show)
            style_string += 'header .title-desc .title span { color: ' + data.char_color + '; background: ' + data.callout_color + ';} ';
        else style_string += 'header .title-desc .title span { color: ' + data.char_color + '; background: transparent; box-shadow: none; }';

        // style_string += 'header .title-desc .description { color: ' + data.char_color + ';} ';
        style_string += 'header .brand-name { color: ' + data.char_color + ';} ';

        if (data.brand_description_show)
            style_string += 'header .brand-desc { color: ' + data.char_color +  '; background: ' + data.callout_color + ';} ';
        else style_string += 'header .brand-desc { color: ' + data.char_color +  '; background: transparent; box-shadow: none; } ';
        
        style_string += 'header .progress-row .point { background-color: ' + data.border_color + ';} ';
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap { width: ' + prog_bar_w + ';} ';
        /* style_string += 'header .progress-row .progress-inner .prog-bar-wrap { border-color: ' + data.border_color + ';} ';
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap:nth-child(1) { border-color: ' + data.border_color + ';} '; */
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap .prog-bar { background: linear-gradient(to left, #d9d9d9 50%, ' + data.border_color + ' 50%) right;} ';
        style_string += 'header .progress-row .progress-inner .prog-bar-wrap .prog-bar.confirmed span { background: ' + data.border_color + ';}';
        style_string += '</style>';
        $('head').append(style_string);

        setTimeout(function() {
            $('#loading-area').hide();
        }, 100);
    })
    .done(function(data) {
    })
    .fail(function() {
    })
    .always(function() {
    });
});

$('#btn-start').click(function() {
    $('body').addClass('started');

    $('#qrcode').hide();
    $('.credit_img').hide();
    $('#btn-start').hide();
    $('#brand-desc').slideUp(100);
    $('#title-desc').slideUp(100);

    if (progress_status == 1) {
        // style_string += 'header .progress-row {display: none;}';
        $('#progress-row').slideDown(100);
    }

    setTimeout(function() {
        $('header').css('padding-top', '0px');
        // $('.content').css('padding-top', $('.site-header').outerHeight() * 1.2);
        // $('.content').css('padding-bottom', '1500px');

        add_first_question(initial_data);
    }, 100);
});

function add_first_question(data) {
    console.log('formstylee_qa_frame_' + survey_id)
    console.log(document.getElementsByClassName('formstylee_qa_frame_' + survey_id))
    addQ();

    $('#q-' + current).show();
    // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);

    var top_offset = $('#q-' + current + ' .q-area').offset().top - $('.site-header').height() - 100;

    if (screen.width <= 480) {
        var top_offset = $('#q-' + current + ' .q-area').offset().top - $('.site-header').height() - 100;
    } else if (screen.width <= 100) {
        var top_offset = $('#q-' + current + ' .q-area').offset().top - $('#header').height() - 10;
    }
    
    $('body, html').animate({
        scrollTop: top_offset
    }, 300);
    $(document).trigger('get-question', [data.first_question.id]);
}

$(document).bind('get-question', function (event, id) {
    url = domain_name + 'api/v1/questions/get/' + id;
    var request = jQuery.get(url, function (data) {
        if (!data.is_next) {
            alert('質問の設定に問題があります。 管理者に連絡してください。');
        }
        else {
            question_order = data.ord;
            setTimeout(function() {
                $('#q-' + current + ' .q-txt-row#q-txt-row-main .q-txt').html(newline(data.title));
    
                if (data.type == 1 && !data.next_question_id) {
                    current++;
                    addQ();
                    $('#q-' + current).show();
                    ecFlg = 0;
                    $(document).trigger('show-last-message', [data]);
                }
                else {
                    if (data.sub_title == null || data.sub_title == '') {
                        $(document).trigger('show-answer-list', [data]);
                    } else {
                        $(document).trigger('show-add-question', [data]);
                    }
                }

                $('.content').attr('style', 'padding-top: 40px!important');
                $('.brand-wrapper').attr('style', 'display: none; margin-bottom: 10px!important');
            }, delay_time);
        }
    })
    .done(function(data) {
    })
    .fail(function() {     
        alert('')   
    })
    .always(function() {
    });
});

$(document).bind('show-add-question', function(event, data) {
    $('#q-' + current + ' .q-txt-row#q-txt-row-sub').show();
    setTimeout(function() {
        $('#q-' + current + ' .q-txt-row#q-txt-row-sub .q-txt').html(newline(data.sub_title));
        $(document).trigger('show-answer-list', [data]);
    }, delay_time);
});

function getYoutubeID(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
      ? match[2]
      : null;
}

const getMeta = (url, cb) => {
    const img = new Image();
    img.onload = () => cb(null, img);
    img.onerror = (err) => cb(err);
    img.src = url;
};

current_answer_count = [];
current_answer_titles = [];
current_answers = [];

$(document).bind('show-answer-list', function(event, data) {
    type = data.type;
    answers = data.answers;
    next_id = data.next_question_id;
    a_area_html = '<div class="q-a-area-wrapper">';
    current_answer_count[data.id] = 0;
    current_answer_titles[data.id] = [];
    current_answers[data.id] = answers;
    if (type == 2) { // 画像
        if (data.file_url) {
            a_area_html += '<div class="q-a-img"><div class="q-a-img-wrapper"><img src="' + domain_name + data.file_url + '"></div></div>';
        }
    } else if (type == 3) { // 動画
        a_area_html += '<div class="q-a-video"><div class="q-a-video-wrapper">';
        if (data.movie_file != null && data.movie_file != '') {
            a_area_html += '<video controls autoplay loop muted>';
            a_area_html += '<source src="' + domain_name + data.movie_file + '" type="video/mp4">';
            a_area_html += '</video>';
        } else if (data.movie_source != null && data.movie_source != '') {
            a_area_html += newline(data.movie_source);
        } else if (data.movie_url != null && data.movie_url != '') {
            var youtube_id = getYoutubeID(data.movie_url);
            if (youtube_id != null) {
                var youtube_iframe = '<iframe width="100%" height="315" src="//www.youtube.com/embed/' + youtube_id + '?rel=0&autoplay=1&mute=1&loop=1" frameborder="0" allowfullscreen></iframe>';
                a_area_html += youtube_iframe;
            } else {
                a_area_html += '<video controls autoplay loop muted>';
                a_area_html += '<source src="' + data.movie_url + '" type="video/mp4">';
                a_area_html += '</video>';
            }
        }

        a_area_html += '</div></div>';
    }

    a_area_html += '<div class="q-a-list">';
    for (i = 0; i < answers.length; i++) {
        if (answers[i].type != 2) {
            a_area_html += `<div class="q-a-item" id="${data.id}-${answers[i].id}"><span class="product_name">` + newline(answers[i].title) + '</span></div>';
        } else {
            var index = 0;
            if (answers[i].answer_images && answers[i].answer_images.length >= 1) {
                a_area_html += `<div class="q-a-item" id="${data.id}-${answers[i].id}"><div class="single-item"><div class="mod-slick-loop"><div class="q-a-item-img-wrapper"><div id="`+ data.id + '-' + answers[i].id + '-' + index + '" class="q-a-item-img" style="background-image: url(' + domain_name + modifyImageUrl(answers[i].file_url) + ')"></div></div>';
                index++;
                answers[i].answer_images.forEach(function(sub_image) {
                    a_area_html += '<div class="q-a-item-img-wrapper"><div id="'+ data.id + '-' + answers[i].id + '-' + index + '" class="q-a-item-img" style="background-image: url(' + domain_name + modifyImageUrl(sub_image.sub_file_url) + ')"></div></div>';
                    index++;
                })
                a_area_html += '</div></div><p class="fw600 fs16 product_name">' + newline(answers[i].title) + '</p>';
            }
            else {
                a_area_html += `<div class="q-a-item" id="${data.id}-${answers[i].id}"><div class="q-a-item-img-wrapper"><div id="`+ data.id + '-' + answers[i].id + '-' + index + '" class="q-a-item-img" style="background-image: url(' + domain_name + modifyImageUrl(answers[i].file_url) + '"></div></div><p class="fw600 fs16 product_name">' + newline(answers[i].title) + '</p>';
            }

            // [answers[i].file_url].concat(answers[i].answer_images.map(sub_image => sub_image.sub_file_url))
            // .forEach((url, index_) => {
            //     var elem_id = `${data.id}-${answers[i].id}-${index_}`;
            //     var func_id = `${data.id}_${answers[i].id}_${index_}`;
            //     a_area_html += `
            //     <script>    
            //         getMeta('${domain_name}${modifyImageUrl(url)}', (err, img) => {
            //             if (img.naturalHeight > img.naturalWidth) {
            //                 $('#${elem_id}').css("background-size", "100% auto")
            //             }
            //         });

            //         function updateStyle${func_id}(x) {
            //             if (x.matches) {
            //                 getMeta('${domain_name}${modifyImageUrl(url)}', (err, img) => {
            //                     document.getElementById('${elem_id}').style.height = 180 * img.naturalHeight/img.naturalWidth + "px";
            //                     $('#${elem_id}').css("background-size", "100%")
            //                 });
            //             } else {
            //                 document.getElementById('${elem_id}').style.height = "180px";
            //                 getMeta('${domain_name}${modifyImageUrl(url)}', (err, img) => {
            //                     if (img.naturalHeight > img.naturalWidth) {
            //                         $('#${elem_id}').css("background-size", "100% auto")
            //                     }
            //                     else $('#${elem_id}').css("background-size", "100% 180px")
            //                 });
            //             }
            //         }

            //         var x${func_id} = window.matchMedia("(max-width: 480px)")
            //         updateStyle${func_id}(x${func_id});
            //         x${func_id}.addListener(updateStyle${func_id});
            //     </script>
            //     `;
            // })

            [answers[i].file_url].concat(answers[i].answer_images.map(sub_image => sub_image.sub_file_url))
            .forEach((url, index_) => {
                var elem_id = `${data.id}-${answers[i].id}-${index_}`;
                a_area_html += `
                <script>    
                    getMeta('${domain_name}${modifyImageUrl(url)}', (err, img) => {
                        if (img) {
                            if (img.naturalHeight > img.naturalWidth) {
                                // $('#${elem_id}').css("background-size", "contain")
                                $('#${elem_id}').css("background-size", "cover")
                            }
                            else $('#${elem_id}').css("background-size", "cover")
                        }
                    });
                </script>
                `;
            })
          
          
            if(answers[i].value != '' && answers[i].value != null && answers[i].value != 0) {
                a_area_html += '<p>¥' + newline(threeDigitComma(answers[i].value)) + '(税込)</p>';
            }
            a_area_html += '</div>';

            if (answers[i].answer_images && answers[i].answer_images.length >= 1) {
                a_area_html += `
                    <script>
                        $('.mod-slick-loop').slick({
                            dots: true,
                            infinite: true,
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            adaptiveHeight: true,
                            cssEase: 'linear',
                            accessibility: false,
                            pauseOnHover: false
                        });
                    </script>
                    `;
            }
        }
    }
    a_area_html += '</div></div>';
    a_area_html += `<style>
    .slick-prev {
        left: 1px !important;
        z-index: 1;
    }
    .slick-next {
        right: 1px !important;
        z-index: 1;
    }
    </style>`;

    setTimeout(function() {
        $('#q-' + current + ' .q-a-area').show();
        $('#q-' + current + ' .q-a-area').html(a_area_html);
        
        // slide right
        var options = {direction: "right" };
        $('#q-' + current + ' .q-a-area').effect("slide", options, 500);

        $('#q-' + current + ' .q-a-area .q-a-img .q-a-img-wrapper img').one("load", function() {
            // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
            img_loaded = true;
        });
        $('#q-' + current + ' .q-a-area .q-a-list .q-a-item .q-a-item-img img').one("load", function() {
            // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
            img_loaded = true;
        });
        // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);

        // question click
        $('#q-' + current + ' .q-a-list .q-a-item').click(function(e) {
            if (e.target.tagName == "BUTTON") return;

            const current_q_index_elem = $(this).closest('.row-q');
            const current_q_index = current_q_index_elem.attr('id').replace('q-', '');
            let img_id = $(this).attr('id');

            // question id
            const q_id = img_id.split('-')[0];
            const cur_answers = current_answers[q_id];

            // cancel selection
            if ($(this).hasClass('selected')) {
                // answer id
                const a_idx_ = img_id.split('-')[1];

                // remove input for answer
                $(`#answers_an_${a_idx_}`).remove();
                $(this).removeClass('selected');
                $(this).parent().removeClass('processed');

                current_answer_count[data.id]--;
                const cur_answer = cur_answers.find(item => item.id == a_idx_);
                current_answer_titles[q_id] = current_answer_titles[q_id].filter(title => {
                    if (cur_answer.title == title) {
                        return false;
                    }
                    return true;
                });

                return;
            }
            // end cancel selection

            if ($(this).parent().hasClass('processed')) {
                return;
            }

            $(this).addClass('selected');

            sel_a_idx = $('#q-' + current_q_index + ' .q-a-list .q-a-item').index(this);
            current_answer_count[data.id]++;
            current_answer_titles[data.id].push(cur_answers[sel_a_idx].title);
            if (current_answer_count[data.id] < data.answer_count && (data.option == 1 || data.option == 2)) {
                var a_html = `<input type="hidden" id="answers_an_${cur_answers[sel_a_idx].id}" name="answers[]" value="${cur_answers[sel_a_idx].id}"/>`;
                $('#chatview').append(a_html);
                return;
            }

            $(this).parent().addClass('processed');
            // current_answer_count[data.id] = 0;

            // $(this).parent().parent().parent().parent().css('min-height', 'auto');
            // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
            if(cur_answers[sel_a_idx].value != '' && cur_answers[sel_a_idx].value != null && cur_answers[sel_a_idx].value != 0) {
                ecFlg = 1;
                var select_item = '<div>';
                    select_item += '<div class="col select-modal-img"><img src="' + domain_name + cur_answers[sel_a_idx].file_url + '"></div>';
                    select_item += '<div class="col text-center fw600">'+cur_answers[sel_a_idx].title+'</div>';
                    select_item += '<div class="col text-center fs14">¥'+threeDigitComma(cur_answers[sel_a_idx].value)+'(税込)</div>';
                        select_item += '<div class="col mt-2">';
                            select_item += '<div class="input-group-select-modal">';
                                select_item += '<span class="input-group-btn">';
                                    select_item += '<button type="button" id="update-quantity-left-minus-' + current_q_index + '" class="update-quantity-left-minus btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="id">';
                                        select_item += '<span class="glyphicon glyphicon-minus">-</span>';
                                    select_item += '</button>';
                                select_item += '</span>';
                                select_item += '<input type="text" id="quantity_id_select_' + current_q_index + '" name="quantity_id_select" class="form-control input-number" value="1" min="1" max="50" style="text-align: center">';
                                select_item += '<span class="input-group-btn">';
                                    select_item += '<button type="button" id="update-quantity-right-plus-' + current_q_index + '" class="update-quantity-right-plus btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="id">';
                                        select_item += '<span class="glyphicon glyphicon-plus">+</span>';
                                    select_item += '</button>';
                                select_item += '</span>';
                            select_item += '</div>';
                        select_item += '</div>';
                    select_item += '<button id="cart_in_' + current_q_index + '" data-question="' + q_id + '" data-answer="' + sel_a_idx + '"class="btn-select-modal" type="button">';
                        select_item += '<span>カートに入れる</span>';
                    select_item += '</button>';
                select_item += '</div>';

                if (current_q_index == current) {
                    $('#select-item-element').append(select_item);
                    $('.modal-quantity-select').show();
    
                    var cost = Number(cur_answers[sel_a_idx].value);
                    product[cur_answers[sel_a_idx].title] = { num: Number($('#quantity_id_select_' + (current_q_index)).val()), cost: cost};
                }
                else {
                    $('#update-quantity-right-plus-cart-wrapper-' + current_q_index).remove();
                    delete(product[cur_answers[sel_a_idx].title]);
                    setTimeout(function() {
                        $('#a-' + current_q_index + ' .a-txt').html('「' + current_answer_titles[data.id].join(', ') + '」です。');
                    });
                }
            } else {
                var a_height = $(window).height() - ($(this).parent().parent().parent().parent().outerHeight() + $('.site-header').outerHeight() - 0);
                if (current_q_index == current) {
                    addA(a_height);
                    // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 0);
    
                    $('body, html').animate({
                        scrollTop: $('#a-' + current_q_index).offset().top - $('.site-header').height()
                    }, 300);
                }
               
                setTimeout(function() {
                    $('#a-' + current_q_index + ' .a-txt').html('「' + current_answer_titles[data.id].join(', ') + '」です。');
                    $('#a-input-' + current_q_index).val(cur_answers[sel_a_idx].id);
                    // current_answer_titles = [];

                    for (i = 0; i < (question_order + 1); i++) {
                        var prog_bar_id = "#prog-bar-" + (i + 1);
                        if (!$(prog_bar_id).hasClass('confirmed')) {
                            $(prog_bar_id).addClass('confirmed');
                        }
                    }

                    if (cur_answers[sel_a_idx].next_question_id != 0 && cur_answers[sel_a_idx].next_question_id) {
                        next_id = cur_answers[sel_a_idx].next_question_id;
                    }
                    
                    if (current_q_index != current) {
                        return;
                    }

                    current++;
                    addQ();
                    $('#q-' + current).show();
                    // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);

                    // $('body, html').animate({
                    //     scrollTop: $('#q-' + current).offset().top + $('#q-' + current + ' .avatar-wrapper').height() - $('.site-header').height()
                    // }, 300);
                    
                    if (next_id != null && next_id != 0) {
                        $(document).trigger('get-question', [next_id]);
                    } else {
                        $(document).trigger('show-last-message', [data]);
                    }
                }, delay_time);
            }

            $('#update-quantity-left-minus-' + current).on('click', function(){
                var decNum = Number($('#quantity_id_select_' + (current)).val()) - 1;
                if (decNum < 1) {
                    decNum = 1;
                }
                $('#quantity_id_select_' + (current)).val(decNum);
                var cost = Number(answers[sel_a_idx].value);
                product[answers[sel_a_idx].title] = { num: decNum, cost: cost};
            });
            $('#update-quantity-right-plus-' + current).on('click', function(){
                var addNum = Number($('#quantity_id_select_' + (current)).val()) + 1;
                $('#quantity_id_select_' + (current)).val(addNum);
                var cost = Number(answers[sel_a_idx].value);
                product[answers[sel_a_idx].title] = { num: addNum, cost: cost};
            });

            $('#cart_in_' + current).on('click', function(){
                $('.total').show();
                item_count = Number($('#quantity_id_select_' + (current)).val());
                items[current] = item_count;
                items_price[current] = Number(answers[sel_a_idx].value);
                items_title[current] = answers[sel_a_idx].title;
                items_count += Number($('#quantity_id_select_' + (current)).val());
                price_count += Number(answers[sel_a_idx].value) * item_count;

                $('#items_count').html(items_count+ ' 点');
                $('#total_result').html('<div class="fs12">トータル</div>' + threeDigitComma(price_count) + '円');

                //add to cart item
                var new_item = '<div class="row border-bottom id="update-quantity-right-plus-cart-wrapper-' + current + '"">';
                        new_item += '<div class="col"><span class="bold">'+answers[sel_a_idx].title+'</span><br>¥'+threeDigitComma(answers[sel_a_idx].value)+'(税込)</div>';
                        new_item += '<div class="col">';
                            new_item += '<div class="input-group">';
                                new_item += '<span class="input-group-btn">';
                                    new_item += '<button type="button" id="update-quantity-left-minus-cart-' + current + '" class="update-quantity-left-minus-cart" btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="id">';
                                        new_item += '<span class="glyphicon glyphicon-minus">-</span>';
                                    new_item += '</button>';
                                new_item += '</span>';
                                new_item += '<input type="text" id="quantity_id_select_cart_' + (current) + '" name="quantity_id_select_cart" class="form-control input-number" value="' + item_count + '" min="1" max="100" style="text-align: center">';
                                new_item += '<span class="input-group-btn">';
                                    new_item += '<button type="button" id="update-quantity-right-plus-cart-' + current + '" class="update-quantity-right-plus-cart" btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="id">';
                                        new_item += '<span class="glyphicon glyphicon-plus">+</span>';
                                    new_item += '</button>';
                                new_item += '</span>';
                            new_item += '</div>';
                        new_item += '</div>';
                    new_item += '</div>';
                $('#cart-item-list').append(new_item);
                $('.modal-quantity-select').hide();
                $('#select-item-element').empty();

                $('#total-cart').text('トータル'+threeDigitComma(price_count)+'円');

                var cost = Number(answers[sel_a_idx].value);
                product[answers[sel_a_idx].title] = { num: Number($('#quantity_id_select_cart_' + (current)).val()), cost: cost};

                var a_height = $(window).height() - ($(this).parent().parent().parent().parent().outerHeight() + $('.site-header').outerHeight() + 0);
                
                addA(a_height);
                // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 0);

                $('body, html').animate({
                    scrollTop: $('#a-' + current).offset().top - $('.site-header').height()
                }, 300);

                setTimeout(function() {
                    // $('#a-' + current + ' .a-txt').html('「' + answers[sel_a_idx].title + '」です。');
                    $('#a-' + current + ' .a-txt').html('「' + current_answer_titles[data.id].join(', ') + '」です。');
                    $('#a-input-' + current).val(answers[sel_a_idx].id);

                    for (i = 0; i < (question_order + 1); i++) {
                        var prog_bar_id = "#prog-bar-" + (i + 1);
                        if (!$(prog_bar_id).hasClass('confirmed')) {
                            $(prog_bar_id).addClass('confirmed');
                        }
                    }

                    if (answers[sel_a_idx].next_question_id != 0 && answers[sel_a_idx].next_question_id) {
                        next_id = answers[sel_a_idx].next_question_id;
                    }

                    current++;
                    addQ();
                    $('#q-' + current).show();

                    // $('body, html').animate({
                    //     scrollTop: $('#q-' + current).offset().top + $('#q-' + current + ' .avatar-wrapper').height() - $('.site-header').height()
                    // }, 300);
                    
                    if (next_id != null && next_id != 0) {
                        // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
                        $(document).trigger('get-question', [next_id]);
                    } else {
                        // アンケート終了時
                        $(document).trigger('show-last-message', [data]);
                    }
                }, delay_time);

                // カート内
                $("#update-quantity-left-minus-cart-"+current).on('click', function(){
                    var index = Number($(this).attr("id").replace('update-quantity-left-minus-cart-', ''));
                    var decNum = Number($('#quantity_id_select_cart_' + index).val()) - 1;
                    if (decNum < 0) {
                        decNum = 0;
                    } else {
                        items_count = items_count - 1;
                    }
                    $('#quantity_id_select_cart_' + index).val(decNum);
                    var diff = decNum - Number(items[index]);
                    items[index] = decNum;
                    price_count += (Number(items_price[index])*diff);

                    $('#total-cart').text('トータル'+threeDigitComma(price_count)+'円');
                    product[items_title[index]] = { num: Number(items[index]), cost: Number(items_price[index])};
                });
                $("#update-quantity-right-plus-cart-"+current).on('click', function(){
                    var index = $(this).attr("id").replace('update-quantity-right-plus-cart-', '');
                    var addNum = Number($('#quantity_id_select_cart_' + index).val()) + 1;
                    $('#quantity_id_select_cart_' + index).val(addNum);
                    var diff = addNum - Number(items[index]);
                    items[index] = addNum;
                    price_count += (Number(items_price[index])*diff);

                    $('#total-cart').text('トータル'+threeDigitComma(price_count)+'円');
                    items_count = items_count + 1;
                    product[items_title[index]] = { num: Number(items[index]), cost: Number(items_price[index])};
                });
            });
        });
    }, delay_time);

});

$(document).bind('show-last-message', function(event, data) {
    // console.log('最後のメッセージです。');
    if (ecFlg) {
        return;
    }
    setTimeout(function() {
        $('#q-' + current + ' .q-txt').html('アンケート内容を送信してください。');
        var form_html = '<div class="q-a-form-fields-wrapper"><div class="q-a-form-fields">';
        form_html += '<div class="q-a-form-field-row"><p>メールアドレス</p><input type="email" placeholder="test@mail.com" name="email" required/></div>';
        form_html += '<div class="q-a-form-field-row"><p>お名前</p><input type="text" placeholder="山田 太郎" name="full_name" required/></div>';
        form_html += '<div class="q-a-form-field-row"><p>郵便番号</p><input type="text" placeholder="111-1111" name="zip_code" /></div>';
        form_html += '<div class="q-a-form-field-row"><p>住所</p><input type="text" placeholder="東京都渋谷区" name="address" /></div>';
        form_html += '<div class="q-a-form-field-row"><p>電話番号</p><input type="tel" placeholder="03-1234-5678" name="phone_number" /></div>';
        form_html += '<input type="submit" value="送信する"></div>';
        form_html += '</div>';
        $('#q-' + current + ' .q-a-area').append(form_html);
        $('#q-' + current + ' .q-a-area').show();
        // EPPZScrollTo.scrollVerticalToElementById('end-anchor', 20);
    }, delay_time);
});

$(document).ready(function () {
    var modal = $('.modal-quantity-select');
    var span = $('.close-quantity-select');

    span.click(function () {
        // 一度選択したものを解除
        var selectId = $(this).parent().children().children().children().children().children('button').attr("id");
        var selectIdNum = selectId.match(/\d/g).join("");
        const q_id = $('#cart_in_' + selectIdNum).attr('data-question');
        const a_index = $('#cart_in_' + selectIdNum).attr('data-answer');

        modal.hide();
        omitProperty(product)($('#q-' + selectIdNum + ' .q-a-list .q-a-item.selected .product_name').text());
        $('#q-' + selectIdNum + ' .q-a-list .q-a-item').parent().removeClass('processed');
        $('#q-' + selectIdNum + ' .q-a-list .q-a-item').removeClass('selected');
        $('#select-item-element').empty();

        var a_height = $(window).height() - ($(this).parent().parent().parent().parent().outerHeight() + $('.site-header').outerHeight() - 0);
        addA(a_height);
        $('body, html').animate({
            scrollTop: $('#a-' + current).offset().top - $('.site-header').height()
        }, 300);
        console.log(current_answer_titles, q_id)

        setTimeout(function() {
            $('#a-' + current + ' .a-txt').html('「' + current_answer_titles[q_id].join(', ') + '」です。');
            $('#a-input-' + current).val(answers[a_index].id);

            for (i = 0; i < (question_order + 1); i++) {
                var prog_bar_id = "#prog-bar-" + (i + 1);
                if (!$(prog_bar_id).hasClass('confirmed')) {
                    $(prog_bar_id).addClass('confirmed');
                }
            }
            // $('#a-' + current + ' .a-area').hide();

            // question id
            current++;
            addQ();
            $('#q-' + current).show();

            if (next_id != null && next_id != 0) {
                $(document).trigger('get-question', [next_id]);
            } else {
                $(document).trigger('show-last-message', [data]);
            }
        }, delay_time);
    });
    $('.view-card-btn').click(function (e) {
        if(items_count > 0) {
            e.preventDefault();
            $('.item-line').hide();
            $('.cart-item-list').toggle();
        }
    });
    $('#cart-close-btn').click(function (e) {
        if(items_count > 0) {
            e.preventDefault();
            $('.item-line').show();
            $('.cart-item-list').toggle();

            $('#items_count').html(items_count+ ' 点');
            $('#total_result').html('<div class="fs12">トータル</div>' + threeDigitComma(price_count) + '円');
        }
    });
    const omitProperty = (obj) => (key) => {
        const clone = Object.assign(obj);
        delete clone[key];
        return clone;
      }
});

$(document).ready(function () {
    $('#pay-now').click(function (e) {
        e.preventDefault();
        // アンケート回答
        var resultUserForm = $('[id^=a-input-]').map(function () {
            return $(this).val();
        });
        var userAnswerData = [];
        for (let i = 0;i < resultUserForm.length; i++) {
            userAnswerData[i] = resultUserForm[i];
        }

        product = JSON.stringify(product);

        var params = {
            "id": survey_id,
            "price": price_count,
            "answer": userAnswerData,
            "cart": encodeURIComponent(product),
        };
        post("/client/pay.php", params);
    });    
});

function post(path, params, method='post') {

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    const form = document.createElement('form');
    form.method = method;
    form.action = path;

    for (const key in params) {
        if (params.hasOwnProperty(key)) {
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = key;
        hiddenField.value = params[key];

        form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

$(document).ready(function () {
    var modal = $('.modal');
    var btn = $('.btn-modal');
    var span = $('.close');

    btn.click(function () {
      modal.show();
    });

    span.click(function () {
      modal.hide();
    });

    $(window).on('click', function (e) {
      if ($(e.target).is('.modal')) {
        modal.hide();
      }
    });
  });

$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: domain_name + 'api/v1/setting',
        timeout: 10000,
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        data: {}
    })
    .done(function (data) {
        // console.log(data.creditCardPaymentFlg);
        if(data.creditCardPaymentFlg == "1") {
            $("#view-card-setting").show();
        }
    })
    .fail(function (err) {
        console.log(err.message);
    });

});