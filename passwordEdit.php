<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$dbUserData = fetchUserData($_SESSION['user_id']);

if (!empty($_POST)) {
  $password = $_POST['password'];
  $newPassword = $_POST['new-password'];
  $reenterNewPassword = $_POST['reenter-new-password'];

  validEmpty($password, 'password');
  if (empty($errorMessages['password'])) {
    validPasswordMatch($password, $dbUserData['password'], 'password');
  }
  validPassword($newPassword, 'new-password');
  validMatch($reenterNewPassword, $newPassword, 'reenter-new-password');


  if (empty($errorMessages)) {
    try {
      $dbh = dbConnect();
      $sql = 'update users set password = :password where user_id = :user_id and is_deleted = false';
      $data = array(
              ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
              ':user_id' => $_SESSION['user_id']
      );
      if (!empty(queryPost($dbh, $sql, $data))) {
        $_SESSION['message'] = SUCCESS['PASSWORD_CHANGE'];
        redirect('mypage.php');
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}

endPageDisplay();
?>
<?php
$pageTitle = 'パスワード変更';
require "head.php";
require "header.php";
?>


<main class="l-main">
  <div class="c-user-management__wrapper">
    <h1 class="c-main__title"><?php
      echo $pageTitle; ?></h1>
    <!--      <p class="c-main__message">タイトル下メッセージ</p>-->
    <!--      <p class="c-main__message &#45;&#45;error">エラーが発生しました。時間をおいてやり直してください。</p>-->
    <form method="post">
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="password" class="c-input__label">現在のパスワード</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <!--        <p class="c-input__help-message">6文字以上の半角英数字を入力してください</p>-->
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('password'); ?>
        </p>
        <input type="password" name="password" id="password" class="c-input__body <?php
        addErrorClass('password'); ?>" value="<?php
        echo keepInputAndDatabase('password');
        ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="new-password" class="c-input__label">新しいパスワード</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">6文字以上の半角英数字を入力してください</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('new-password'); ?>
        </p>
        <input type="password" name="new-password" id="new-password" class="c-input__body <?php
        addErrorClass('new-password'); ?>" value="<?php
        echo keepInputAndDatabase('new-password'); ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="reenter-new-password" class="c-input__label">新しいパスワード(再入力）</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">確認のためご入力ください</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('reenter-new-password'); ?>
        </p>
        <input type="password" name="reenter-new-password" id="reenter-new-password" class="c-input__body <?php
        addErrorClass('reenter-new-password'); ?>" value="<?php
        echo keepInputAndDatabase('reenter-new-password'); ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>


      <button class="c-button --full-width c-button__primary u-mb-24" type="submit">
        変更する
      </button>
    </form>
    <div class="u-mb-24 u-text-right">
      <a href="passwordRemindSend.php" class="c-text c-text__link --underline">パスワードを忘れた方はコチラ</a>
    </div>
  </div>

</main>

<?php
require "footer.php"; ?>
