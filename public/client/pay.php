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

    <!-- javascript -->
    <script src="./libs/jquery/jquery-3.4.1.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <!-- css -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/cart.css">
    <link rel="stylesheet" href="./assets/css/stripe.css">
</head>

<?php
$survey_id = (isset($_POST['id'])) ? $_POST['id'] : -1;
$userAnswerData = (isset($_POST['answer'])) ? $_POST['answer'] : -1;
$price = (isset($_POST['price'])) ? $_POST['price'] : -1;
$cart = (isset($_POST['cart'])) ? $_POST['cart'] : -1;
?>
<body>
  <header id="header" class="header header-payment">
      <div class="site-header">
          <div class="site-header-inner">
              <div class="brand-wrapper">
                  <div id="brand" class="brand"><img src="" alt="" /></div>
                  <p id="brand-name" class="brand-name"></p>
              </div>
          </div>
      </div>
  </header>
  <script type="text/javascript">
      var survey_id = '<?=$survey_id;?>';
      var userAnswerData = '<?=$userAnswerData;?>';
      var price_count = '<?=$price;?>';
      var cart = decodeURIComponent('<?=$cart;?>');
  </script>
  <script src="./assets/js/pay_script.js?v=1"></script>
  <div class="pay-body">
      <div>
          <div class="stripe-form">
              <form id="payment-form">
                  <div id="input_form_item"></div>
                  <!-- <div id="payment-element"> -->
                      <!--Stripe.js injects the Payment Element-->
                  <!-- </div> -->
                  <div class="payment-title">お支払い情報</div>
                  <div class="payment-content">
                    <div class="field">
                      <label for="name">カード名義</label>
                      <div><input type="text" id="card-name" /></div>
                    </div>
                    <div class="field">
                      <label for="card-number">カード番号</label>
                      <div id="card-number"></div>
                    </div>
                    <div class="field">
                    <label for="card-expiry">有効期限</label>
                      <div id="card-expiry"></div>
                    </div>
                    <div class="field">
                    <label for="card-cvc">セキュリティーコード</label>
                      <div id="card-cvc"></div>
                    </div>
                    <button id="button-text" type='button'>
                        <div class="spinner hidden" id="spinner"></div>
                        <span>注文確定</span><span id="price-conp"></span>
                    </button>
                    <div id="payment-message" class="hidden"></div>
                  </div>
              </form>
              <div>
                <div class="text-center bold oder-flame">ご注文内容をご確認いただきましてお手続きをお願い致します</div>
                <div id="cart_item-flame">
                  <div class="cart_item_bg">
                    <div id="cart_item" class="cart_item"></div>
                    <div class="mt-20 item-line">
                      <div class="total_result" id="total_result"></div>
                    </div>
                  </div>
                </div>
              </div>
          </div>  
      </div>
  </div>
  <div class="modal-message">
        <div class="modal-content-message">
            <span class="close-quantity-select">&times;</span>
            <div class="modal-select-item-form">
                <div id="payment-conp-message"></div>
            </div>  
        </div>
    </div>
</body>
</html>