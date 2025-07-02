$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    const likeBtn = $(this);
    const post_id = likeBtn.attr('post_id');
    const count = Number($('.like_counts' + post_id).text());

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: post_id,
      },
    }).done(function (res) {
      // 見た目切り替え：白抜き → 赤塗りつぶし
      likeBtn
        .removeClass('far like_btn')
        .addClass('fas fa-heart un_like_btn text-danger');

      $('.like_counts' + post_id).text(count + 1);
    }).fail(function () {
      console.log('like失敗');
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    const unLikeBtn = $(this);
    const post_id = unLikeBtn.attr('post_id');
    const count = Number($('.like_counts' + post_id).text());

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: post_id,
      },
    }).done(function (res) {
      // 見た目切り替え：赤塗りつぶし → 白抜き
      unLikeBtn
        .removeClass('fas fa-heart un_like_btn text-danger')
        .addClass('far fa-heart like_btn');

      $('.like_counts' + post_id).text(count - 1);
    }).fail(function () {
      console.log('unlike失敗');
    });
  });

  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
