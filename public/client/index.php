<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FORMSTYLEE</title>

    <link rel="stylesheet" href="./libs/slick/slick.css">
    <link rel="stylesheet" href="./libs/slick/slick-theme.css">

    <!-- javascript -->
    <script src="./libs/jquery/jquery-3.4.1.min.js"></script>
    <script src="./libs/slick/slick.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <!-- css -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/cart.css">
    <link rel="stylesheet" href="./assets/css/stripe.css">
</head>

<?php
$survey_id = (isset($_GET['id'])) ? $_GET['id'] : -1;
$test_flag = false;
// $domain_name = 'https://admin.formstylee.com/';
$domain_name = 'https://styleboard.xbiz.jp/';
if ($test_flag) {
//    $domain_name = 'http://192.168.158.165/';
    $domain_name = 'http://127.0.0.1:8000/';
}
?>

<body>
    <header id="header" class="header">
        <div class="site-header">
            <div class="site-header-inner">
                <div class="brand-wrapper">
                    <div id="brand" class="brand"><img src="" alt="" /></div>
                    <p id="brand-name" class="brand-name"></p>
                </div>
                <div id="brand-desc" class="brand-desc"></div>
                <div id="title-desc" class="title-desc">
                    <h1 id="title" class="title"><span></span></h1>
                    <p id="description" class="description"></p>
                </div>
                <div id="btn-start" class="btn-start">START</div>
                <div id="progress-row" class="progress-row">
                    <div class="point"></div>
                    <div id="progress-inner" class="progress-inner"></div>
                    <div class="point"></div>
                </div>
                <div id="qrcode" class="qrcode">
                    <img src="" alt="" />
                </div>
                <div class="credit_img">
                    <img src="./assets/img/credit.png" alt="" />
                </div>
            </div>
        </div>
    </header>
    <div id="content" class="content">
        
        <script type="text/javascript">var submitted=false;</script>
        <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;" onload="if(submitted) {window.location='thanks.php';}"></iframe>

        <form action="<?=$domain_name;?>api/v1/client/save" method="POST" target="hidden_iframe" onsubmit="submitted=true;">
            <input type="hidden" name="survey_id" value="<?=$survey_id;?>" />
            <div id="chatview" class="chatview">
            </div>
            <div id="chatview-modal" class="chatview-modal"></div>
            <div id="end-anchor"></div>
        </form>
    </div>
    <div id="loading-area">
        <div class="loader-wrapper">
            <div class="loader">Loading...</div>
        </div>
    </div>

    <div class="total">

        <div class="row cart-item-list">
            <div class="dflex_center col-12 text-center order-title">
                <div class="font-bold">ショッピングカート</div>
            </div>
            <img id="cart-close-btn" class="close_icon" src='./assets/img/close_icon.png' alt='' />
            <div class="col" id="cart-item-list"></div>
            <div class="col total-cart-flame" id="total-cart"></div>
            <div class="col-12 text-center">
                <a href="#" id="pay-now" class="btn btn-sm btn-primary btn-modal">注文する</a>
            </div>
        </div>

        <div class="row text-center item-line" id="view-card-setting" style="display:none;">
            <div class="col">
                <div class="items_count text-light text-center" id="items_count"></div>
            </div>
            <div class="col">
                <a class="btn view-card-btn">カートを見る</a>
            </div>
            <div class="col">
                <div class="cart_bar text-light text-center" id="total_result"></div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        var survey_id = '<?=$survey_id;?>';
    </script>
    <script src="./assets/js/script.js"></script>
    <!-- <div class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="stripe-form">
                <form id="payment-form">
                    <div id="input_form_item"></div>
                    <div id="payment-element">
                    </div>
                    <button id="button-text" type='button'>
                        <div class="spinner hidden" id="spinner"></div>
                        <span>送信</span>
                    </button>
                    <div id="payment-message" class="hidden"></div>
                </form>
            </div>  
        </div>
    </div> -->
    <div class="modal-quantity-select">
        <div class="modal-content-quantity-select">
            <span class="close-quantity-select">&times;</span>
            <div class="modal-select-item-form">
                <div id="select-item-form">
                    <div id="select-item-element"></div>
                </div>
            </div>  
        </div>
    </div>
</body>
</html>