$(function () {
  $('.js-open_br').click(function () {
    $("body").addClass("no_scroll_br");
    var id = $(this).data('id'); // 何番目（モーダルウィンドウ）か認識
    $('#overlay_br, .modal-window_br, .modal_button_br').fadeIn();
  });
  // オーバーレイクリックでもモーダルを閉じるように
  $('.Product_close_br , #overlay_br').click(function () {
    $("body").removeClass("no_scroll_br");
    $('#overlay_br, .modal-window_br, .modal_button_br').fadeOut();
  });
});