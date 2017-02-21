(function ($) {

  var shopdata = null;
  var shop = null;
  var city = null;
  $.ajax({
    url: './data.json',
    success: function (res) {
      shopdata = res;
      var cityOptions = {};
      for (city in res) {
        $('select[name="city"]').append($('<option></option>').attr('value', city).text(city));
      }
      $('select[name="city"]').selectmenu({
        change: function (event, ui) {
          var shopes = shopdata[$(this).val()];
          $('select[name="desktop"]').html('');
          for (var index in shopes) {
            var shop = shopes[index];
            $('select[name="desktop"]').append($('<option></option>').attr('value', shop).text(shop));
          }
          $('select[name="desktop"]').selectmenu('refresh');
        }
      });
      $('select[name="desktop"], select[name="city"]').selectmenu({});
    },
  });

  $('.submit').click(function () {
    postUserSubmit($('input[name="phone"]').val(), $('select[name="city"]').val(), $('select[name="desktop"]').val());
  });

  function postUserSubmit(phone, city, shop) {
    var data = {phone: phone, city: city, shop: shop};
    var phoneReg = /^1[3|4|5|7|8][0-9]{9}$/;
    if (phone.trim().length <= 0 || !phoneReg.test(phone)) {
      alert('请输入正确的手机号码');
      return ;
    } else if (city == '' || shop == '') {
      alert('请选择城市或店铺');
      return ;
    }
    $.ajax({
      url: '/admin.php?method=submituser',
      data: data,
      method: 'POST',
      dataType: 'json',
      success: function (res) {
        console.log(res);
        if (res['success']) {
          window.location.href = '/success.html';
        } else {
          window.location.href = '/failed.html';
        }
      }
    });
  }

  window.goHome = function () { 
    window.location.href = "/index.html";
  };



})(jQuery);