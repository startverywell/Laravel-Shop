var total_price = 0;
var total_num = 0;
$(document).ready(function() {
    $('#progress-row').hide();
    if (survey_id == -1) return;

    url = domain_name + 'api/v1/survey/get/' + survey_id;
    var request = jQuery.get(url, function(data) {
      $('#brand').css('background-image', 'url(' + domain_name + data.brand_logo_path + ')');
    })
    .done(function(data) {
    })
    .fail(function() {        
    })
    .always(function() {
    });
});


function threeDigitComma(val) {
  var disp_price = "";
  if(val){
      var num = String(val).replace(/(\d)(?=(\d\d\d)+$)/g, "$1,");
      disp_price = num;
  }
  return disp_price;
}

const stripe = Stripe('pk_live_51Lqrq2CmBq9bVhsp2WpP00VmeUgImrDLGQBQDkv8RcCu0YIYSiB6gveRAEjMT4PYAAd17SHuBxzjNnTrse6YIcgw00Zxfs3lhB');
$(document).ready(function () {
    // $('#pay-now').click(function (e) {
  // e.preventDefault();
  // -------------------
  // $.ajax({
  //     type: "POST",
  //     url: 'admin.formstylee.com/api/v1/payment-intent/create',
  //     timeout: 10000,
  //     headers: {
  //         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     data: {"amount": price_count}
  // })
  // .done(function (data) {
      // console.log(data.response.id);
  // const elements = stripe.elements({
  //     clientSecret: data.response.client_secret
  // });
  // const paymentElement = elements.create("payment");
  // paymentElement.mount("#payment-element");

  const elements = stripe.elements();

  const elementStyles = {
      base: {
          fontFamily: "sans-serif",
          lineHeight: "42px",
          height: "42px",
          fontSize: "14px",
          "::placeholder": {
              color: "#aaa"
          },
          ":-webkit-autofill": {
              color: "#e39f48"
          }
      },
      invalid: {
          color: "red"
      }
  };
  // カード番号
  const cardNumber = elements.create('cardNumber', {
    style: elementStyles,
  });
  cardNumber.mount('#card-number');

  // カードの有効期限
  const cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
  });
  cardExpiry.mount('#card-expiry');

  // カードのCVC入力
  const cardCvc = elements.create('cardCvc', {
    style: elementStyles,
  });
  cardCvc.mount('#card-cvc');


  $('#input_form_item').empty();
  var form_html = '<div class="q-a-form-fields-wrapper"><div class="q-a-form-fields">';
  form_html += '<div class="q-a-form-field-row"><p>メールアドレス</p><input type="email" id="email" placeholder="test@mail.com" name="email" aria-required="true" class="user-info-input-wrap" /></div>';
  form_html += '<div class="err-meg" id="err-meg-email"></div>';
  form_html += '<div class="q-a-form-field-row"><p>お名前</p><input type="text" id="full_name" placeholder="山田 太郎" name="full_name" aria-required="true" class="user-info-input-wrap" /></div>';
  form_html += '<div class="err-meg" id="err-meg-name"></div>';
  form_html += '<div class="q-a-form-field-row"><p>郵便番号</p><input type="text" id="zip_code" placeholder="111-1111" name="zip_code" aria-required="true" class="user-info-input-wrap" /></div>';
  form_html += '<div class="err-meg" id="err-meg-zip"></div>';
  form_html += '<div class="q-a-form-field-row"><p>住所</p><input type="text" id="address" placeholder="東京都渋谷区" name="address" aria-required="true" class="user-info-input-wrap" /></div>';
  form_html += '<div class="err-meg" id="err-meg-address"></div>';
  form_html += '<div class="q-a-form-field-row"><p>電話番号</p><input type="tel" id="phone_number" placeholder="03-1234-5678" name="phone_number" aria-required="true" class="user-info-input-wrap" /></div>';
  form_html += '<div class="err-meg" id="err-meg-tel"></div>';
  form_html += '</div>';
  $('#input_form_item').append(form_html);

  var cartData = JSON.parse(cart);
  var count = 1;
  Object.keys(cartData).forEach(function(val){
    
    var new_item = '<div class="row border-bottom">';
          new_item += '<div class="col"><span class="bold">'+val+'</span><br>¥'+threeDigitComma(cartData[val].cost)+'(税込)</div>';
          new_item += '<div class="col">';
              new_item += '<div class="input-group">';
                  new_item += '<span class="input-group-btn">';
                      new_item += '<button type="button" id="update-quantity-left-minus-creadit-' + count + '" class="update-quantity-left-minus-creadit" btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="id">';
                          new_item += '<span class="glyphicon glyphicon-minus">-</span>';
                      new_item += '</button>';
                  new_item += '</span>';
                  new_item += '<input type="text" id="quantity_id_select_creadit_' + (count) + '" name="quantity_id_select_creadit" class="form-control input-number" value="' + cartData[val].num + '" min="1" max="100" style="text-align: center">';
                  new_item += '<span class="input-group-btn">';
                      new_item += '<button type="button" id="update-quantity-right-plus-creadit-' + count + '" class="update-quantity-right-plus-creadit" btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="id">';
                          new_item += '<span class="glyphicon glyphicon-plus">+</span>';
                      new_item += '</button>';
                  new_item += '</span>';
              new_item += '</div>';
          new_item += '</div>';
      new_item += '</div>';
    $('#cart_item').append(new_item);

    total_price += cartData[val].num * cartData[val].cost;
    total_num += cartData[val].num

    var cartPaymentWrap = '<span>' + total_num+'件</span><span>'+threeDigitComma(total_price)+'円</span>';
    $('#total_result').html(cartPaymentWrap);
    $('#price-conp').html('(' + threeDigitComma(total_price) + '円)');

    $('#update-quantity-left-minus-creadit-' + count).on('click', function(){
      var indexNum = $(this).attr('id').replace('update-quantity-left-minus-creadit-', '');
      var decNum = Number($('#quantity_id_select_creadit_' + (indexNum)).val()) - 1;
      if (decNum <= 0) {
          decNum = 0;
      }
      var diff = decNum - cartData[val].num;
      cartData[val].num = decNum;
      total_price += (Number(cartData[val].cost)*diff);
      total_num = decNum

      var cartPaymentWrap = '<span>' + total_num+'件</span><span>'+threeDigitComma(total_price)+'円</span>';
      $('#total_result').html(cartPaymentWrap);
      $('#price-conp').html('(' + threeDigitComma(total_price) + '円)');
      $('#quantity_id_select_creadit_' + (indexNum)).val(decNum);
    });

    $('#update-quantity-right-plus-creadit-' + count).on('click', function(){
      var indexNum = $(this).attr('id').replace('update-quantity-right-plus-creadit-', '');
      var addNum = Number($('#quantity_id_select_creadit_' + (indexNum)).val()) + 1;
      $('#quantity_id_select_creadit_' + (indexNum)).val(addNum);
      var diff = addNum - cartData[val].num;
      cartData[val].num = addNum;
      total_price += (Number(cartData[val].cost)*diff);
      total_num = addNum

      var cartPaymentWrap = '<span>' + total_num+'件</span><span>'+threeDigitComma(total_price)+'円</span>';
      $('#total_result').html(cartPaymentWrap);
      $('#price-conp').html('(' + threeDigitComma(total_price) + '円)');
    });
    count++;
  });

  if (window.matchMedia( "(min-width: 600px)" ).matches) {
    $('#cart_item-flame').height($('#payment-form').height()-80);
  }

  $('#button-text').bind('click', function (e) {
      e.preventDefault();

      $(this).attr('disabled', true);

      var errFlg = false;
      $('#err-meg-email').text('');
      $('#err-meg-name').text('');
      $('#err-meg-zip').text('');
      $('#err-meg-address').text('');
      $('#err-meg-tel').text('');
      if ($('#email').val() === null || $('#email').val() === "" || $('#email').val() === undefined) {
          errFlg = true;
          $('#err-meg-email').text('メールアドレスを入力してください');
      }
      if ($('#full_name').val() === null || $('#full_name').val() === "" || $('#full_name').val() === undefined) {
          errFlg = true;
          $('#err-meg-name').text('名前を入力してください');
      }
      if ($('#zip_code').val() === null || $('#zip_code').val() === "" || $('#zip_code').val() === undefined) {
          errFlg = true;
          $('#err-meg-zip').text('郵便番号を入力してください');
      }
      if ($('#address').val() === null || $('#address').val() === "" || $('#address').val() === undefined) {
          errFlg = true;
          $('#err-meg-address').text('住所を入力してください');
      }
      if ($('#phone_number').val() === null || $('#phone_number').val() === "" || $('#phone_number').val() === undefined) {
          errFlg = true;
          $('#err-meg-tel').text('電話番号を入力してください');
      }
      
      if (errFlg) {
        $('body, html').scrollTop(0);
        $(this).attr('disabled', false);
        return;
      }
      
      // 決済
      // $.ajax({
      //     type: "POST",
      //     url: domain_name + 'api/v1/payment-intent/create',
      //     timeout: 10000,
      //     headers: {
      //         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
      //     },
      //     data: {"amount": 10000}
      // })
      // .done(function (data) {
      //     const elements = stripe.elements({
      //         clientSecret: data.response.client_secret
      //     });
      //     stripe.confirmPayment({
      //         elements,
      //         redirect: 'if_required',
      //     })
      //     .then(function (result) {
      //         // alert(result.paymentIntent.status);
      //         // $('.modal').hide();
      //         if (result.error) {
      //             console.log(result.error.message);
      //             alert('決済に失敗しました。');
      //         } else {
      //             location.reload();
      //         }
      //     });
      //   });
      var name = document.querySelector('#card-name');
      var additionalData = {
        name: name ? name.value : undefined,
      };
      stripe.createToken(cardNumber, additionalData).then(function(result) {
        if (result.token) {
          $.ajax({
            type: "POST",
            url: domain_name + 'api/v1/payment-intent/create',
            timeout: 10000,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              "token": result.token.id,
              "total": total_price,
            }
          })
          .done(function (data) {
              if (data.status === 'fail') {
                  console.log(data.message);
                  $(this).attr('disabled', false);
                  var payment_message = "<img src='./assets/img/error.png' alt='' />";
                  payment_message += '<p>何らかの事情で決済が出来ませんでした。<br/>お手数ですが、<br/>再度 お手続きをお願い致します。</p>';
                  $('#payment-conp-message').append(payment_message);
                  $('.modal-message').show();
              } else {
                // フォーム送信
                $.ajax({
                  type: "POST",
                  url: domain_name + 'api/v1/client/save',
                  timeout: 10000,
                  headers: {
                      'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                  },
                  data: {
                      "email": $('#email').val(),
                      "full_name": $('#full_name').val(),
                      "zip_code": $('#zip_code').val(),
                      "address": $('#address').val(),
                      "phone_number": $('#phone_number').val(),
                      "survey_id": survey_id,
                      "answers": userAnswerData,
                  }
                }).done(function (data) {

                });
                var payment_message = "<img src='./assets/img/ok.png' alt='' />";
                payment_message += '<p>決済が完了致しました。<br/>このたびのご注文<br/>まことにありがとうございました。</p>';
                $('#payment-conp-message').append(payment_message);
                $('.modal-message').show();
              }
          })
        } else {
          // Otherwise, un-disable inputs.
          $(this).attr('disabled', false);
          var payment_message = "<img src='./assets/img/error.png' alt='' />";
          payment_message += '<p>何らかの事情で決済が出来ませんでした。<br/>お手数ですが、<br/>再度 お手続きをお願い致します。</p>';
          $('#payment-conp-message').append(payment_message);
          $('.modal-message').show();
        }

      });                     
  })
  // })
  // .fail(function (err) {
  //     console.log(err.message);
  // });

    // });
});

$(document).ready(function () {
  var span = $('.close-quantity-select');
  span.click(function () {
    $('.modal-message').hide();
    location.href="/client/?id=" + survey_id;
  });
});