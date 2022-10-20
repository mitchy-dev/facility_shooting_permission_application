<?php

require('functions.php');
startPageDisplay();
require "auth.php";

if (!empty($_POST)) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $extendLogin = !empty($_POST['extend-login']) ? $_POST['extend-login'] : false;

  validEmail($email, 'email');
  validEmpty($password, 'password');

  if (empty($errorMessages)) {
    debug('ログイン認証します。');
    try {
      $dbh = dbConnect();
      $sql = 'select user_id, email, password, organization, department, representative_title, representatives_name, person_in_charge, phone_number, comment, avatar_path, has_facility_registration_authority
from users where email = :email and is_deleted = false';
      $data = array(
              ':email' => $email,
      );
      $sth = queryPost($dbh, $sql, $data);
      $result = $sth->fetch();
      if (password_verify($password, $result['password'])) {
        debug('認証できました');
        $_SESSION['login_time'] = time();
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['message'] = SUCCESS['LOGIN'];
       
        if (!empty($extendLogin)) {
          debug('次回ログインを省略にチェックがあります');
          $_SESSION['login_limit'] = time() + MONTH;
        } else {
          $_SESSION['login_limit'] = time() + WEEK;
        }
        redirect('index.php');
      } else {
        debug('認証できませんでした');
        $errorMessages['common'] = ERROR['LOGIN'];
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}

endPageDisplay();
?>
<?php
$pageTitle = 'ログイン';
require "head.php";
require "header.php";
?>


<main class="l-main">
  <div class="c-user-management__wrapper">
    <h1 class="c-main__title"><?php
      echo $pageTitle; ?></h1>
    <p class="c-main__message --error"><?php
      getErrorMessage('common'); ?></p>
    <form method="post" class="u-mb-24">
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
        <!--        <p class="c-input__help-message">6文字以上の半角英数字を入力してください</p>-->
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

      <div class="c-checkbox__container">
        <label for="extend-login" class="c-checkbox__label">
          <input type="checkbox" class="c-checkbox__body" name="extend-login" id="extend-login" <?php
          if (!empty($_POST['extend-login'])) {
            echo 'checked';
          } ?>>
          <span class="c-checkbox__name">次回ログインを省略する</span>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage('extend-login'); ?>
          </p>
        </label>
      </div>

      <button type="submit" class="c-button --full-width c-button__primary" type="button">
        登録する
      </button>
    </form>
    <div class="u-mb-24 u-text-right">
      <a href="passwordRemindSend.html" class="c-text c-text__link --underline">パスワードを忘れた方はコチラ</a>
    </div>

  </div>

</main>

<?php
require "footer.php"; ?>
