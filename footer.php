<footer id="footer" class="l-footer u-text-center">
  <ul class="c-footer-nav">
    <li class="c-footer-nav__list --active"><a href="" class="c-footer-nav__item">利用規約</a></li>
    <li class="c-footer-nav__list"><a href="" class="c-footer-nav__item">プライバシーポリシー</a></li>
    <li class="c-footer-nav__list"><a href="" class="c-footer-nav__item">お問い合わせ</a></li>
  </ul>
  <div class="c-footer-copyright">
    <p class="c-footer-copyright__item">©2023 みっちー</p>
  </div>
</footer>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
<script>
  $(function () {
    //フッターの固定
    var $footer = $('#footer');
    console.log($footer);

    if (window.innerHeight > $footer.offset().top + $footer.outerHeight()) {
      $footer.attr({
        'style': 'position:fixed;top:' + (window.innerHeight - $footer.outerHeight()) + 'px;'
      });
    }


    //文字数カウント
    $('.js-count').keyup(function () {
      var count = $(this).val().length;
      $('.js-counter').text(count);
    });

    //  バリデーション
    $('.js-valid-email').keyup(function () {
      var $errorMassage = $(this).siblings('.c-input__error-message');

      if ($(this).val().length === 0) {
        $(this).addClass('error');
        $errorMassage.text('入力必須です。');
      } else {
        $(this).removeClass('error');
        $errorMassage.text('');

      }
    })

    //  フラッシュメッセージ
    var $flashMessage = $('.js-flash-message');
    if ($flashMessage.text().trim() !== '') {
      $flashMessage.parent().slideToggle('slow');
      $flashMessage.parent().slideToggle(3000);


    }


  });
</script>

</body>
</html>