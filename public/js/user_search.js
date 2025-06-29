$(function () {
  // ユーザー検索ページ：検索条件の追加
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
    $('.search_arrow').toggleClass('open');
  });

  // プロフィールページ：選択科目の登録（ボタン or 矢印を押したら動作）
  $('.subject_edit_btn, .subject_arrow').click(function () {
    $('.subject_inner').slideToggle();
    $('.subject_arrow').toggleClass('open');
  });
});
