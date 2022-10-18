<?php

require('functions.php');
startPageDisplay();

if (!empty($_POST)) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $reenterPassword = $_POST['reenter-password'];
  $agreement = !empty($_POST['agreement']) ? $_POST['agreement'] : false;

  validEmpty($email, 'email', ERROR['EMPTY']);
  validEmpty($password, 'password', ERROR['EMPTY']);
  validEmpty($reenterPassword, 'reenterPassword', ERROR['EMPTY']);
  validEmpty($agreement, 'agreement', ERROR['AGREEMENT']);
//  validCheckBox($agreement, 'agreement');
//  var_dump($agreement);


  if (empty($errorMessages)) {
    validMaxLength($email, 'email');
    if (empty($errorMessages['email'])) {
      validEmail($email, 'email', ERROR['EMAIL']);
    }

    validMaxLength($password, 'password');
    if (empty($errorMessages['password'])) {
      validMinLength($password, 'password');
    }
    if (empty($errorMessages['password'])) {
      validHalf($password, 'password');
    }

    if (empty($errorMessages)) {
      validMatch($password, $reenterPassword, 'reenterPassword');
      if (empty($errorMessages)) {
//        DB操作
      }
    }
  }
}

endPageDisplay();
?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="destyle.css">
  <link rel="stylesheet" href="style.css">

  <title>ユーザー登録</title>
</head>
<body>
<div class="l-wrapper">
  <header class="l-header">
    <div class="c-header-title">
      <a href="" class="c-header-title__link">
        海岸ロケ
      </a>
    </div>
    <ul class="c-header-nav">
      <li class="c-header-nav__item c-button c-button__text">
        <a>
          ログイン
        </a>
      </li>
      <li class="c-header-nav__item c-button c-button__secondary">
        <a>
          ユーザー登録
        </a>
      </li>
    </ul>
  </header>

  <main class="l-main">
    <div class="c-user-management__wrapper">
      <h1 class="c-main__title">ユーザー登録</h1>
      <form method="post">
        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="email" class="c-input__label">メールアドレス</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <!--          <p class="c-input__help-message">help message</p>-->
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage('email'); ?>
          </p>
          <input type="email" name="email" id="email"
                 class="c-input__body js-count js-valid-email <?php
                 addErrorClass('email'); ?>">
          <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="password" class="c-input__label">パスワード</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">8文字以上の半角英数字を入力してください</p>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage('password'); ?>
          </p>
          <input type="password" name="password" id="password" class="c-input__body <?php
          addErrorClass('password'); ?>">
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="reenter-password" class="c-input__label">パスワード再入力</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <!--                              <p class="c-input__help-message">help message</p>-->
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage('reenterPassword'); ?>
          </p>
          <input type="password" name="reenter-password" id="reenter-password" class="c-input__body <?php
          addErrorClass('reenterPassword'); ?>">
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="u-mb-24">
          <p class="c-text">
            <span class="c-text__link --underline">利用規約</span>
            ・
            <span class="c-text__link --underline">プライバシーポリシー</span>
            について
          </p></div>

        <div class="c-checkbox__container">
          <label for="agreement" class="c-checkbox__label">
            <input type="checkbox" class="c-checkbox__body" name="agreement" id="agreement">
            <span class="c-checkbox__name">同意する</span>
            <p class="c-input__error-message">
              <?php
              echo getErrorMessage('agreement'); ?>
            </p>
          </label>
        </div>
        <button type="submit" class="c-button --full-width c-button__primary">
          登録する
        </button>
      </form>
      <!--      <p class="c-main__message">タイトル下メッセージ</p>-->
      <!--      <p class="c-main__message &#45;&#45;error">エラーが発生しました。時間をおいてやり直してください。</p>-->
    </div>

  </main>

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
<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js"
        integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
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


  });
</script>

</body>
</html>