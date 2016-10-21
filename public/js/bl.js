
$(document).ready(function(){

  $(document.body).click(function () {
    $('.bl-header .opened').removeClass('opened');
    $('.bl-header .sub-nav').hide();
  })

  $('.bl-header .with-items > a').click(function (evt) {
    evt.preventDefault();
    if ($(this).hasClass('opened')) {
      return;
    }
    var _this = this;
    setTimeout(function () {
      if ($(_this).hasClass('opened')) {
        return;
      }
      $(_this).addClass('opened');
      $(_this).next('.sub-nav').show();
    }, 10);
  });

  $('.bl-header .show-menu').click(function (evt) {
    evt.preventDefault();
    $('.bl-header').addClass('opened');
  });

  $('.bl-header .close').click(function (evt) {
    evt.preventDefault();
    $('.bl-header.opened').removeClass('opened');
  })
});
