<?php

require('functions.php');
startPageDisplay();

if (!empty($_POST)) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $reenterPassword = $_POST['reenter-password'];
  $agreement = !empty($_POST['agreement']) ? $_POST['agreement'] : false;

  validEmail($email, 'email');
  if (empty($errorMessages['email'])) {
    validDuplicateEmail($email, 'email');
  }
  validPassword($password, 'password');
  validMatch($password, $reenterPassword, 'reenterPassword');
  validEmpty($agreement, 'agreement', ERROR['AGREEMENT']);


  if (empty($errorMessages)) {
    try {
      $dbh = dbConnect();
      $sql = 'insert into users(email, password, created_at) VALUES (:email, :password, :created_at)';
      $data = array(
              ':email' => $email,
              ':password' => password_hash($password, PASSWORD_DEFAULT),
              ':created_at' => date("Y-m-d H:i:s"),
      );
      if (!empty(queryPost($dbh, $sql, $data))) {
        $_SESSION['login_time'] = time();
        $_SESSION['login_limit'] = time() + WEEK;
        $_SESSION['message'] = SUCCESS['SIGN_UP'];
        $_SESSION['user_id'] = $dbh->lastInsertId();

        redirect('index.html');
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}

endPageDisplay();
?>
<?php
$pageTitle = 'ユーザー登録';
require "head.php";
require "header.php";
?>

<main class="l-main">
  <div class="c-user-management__wrapper">
    <h1 class="c-main__title"><?php
      echo $pageTitle; ?></h1>
    <p class="c-main__message --error"><?php
      getErrorMessage('common'); ?></p>
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
               addErrorClass('email'); ?>" value="<?php
        if (!empty($_POST['email'])) {
          echo $_POST['email'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="password" class="c-input__label">パスワード</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">6文字以上の半角英数字を入力してください</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('password'); ?>
        </p>
        <input type="password" name="password" id="password" class="c-input__body <?php
        addErrorClass('password'); ?>" value="<?php
        if (!empty($_POST['password'])) {
          echo $_POST['password'];
        } ?>">
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
        addErrorClass('reenterPassword'); ?>" value="<?php
        if (!empty($_POST['reenter-password'])) {
          echo $_POST['reenter-password'];
        } ?>">
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
          <input type="checkbox" class="c-checkbox__body" name="agreement" id="agreement" <?php
          if (!empty($_POST['agreement'])) {
            echo 'checked';
          } ?>>
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

<?php
require "footer.php"; ?>
