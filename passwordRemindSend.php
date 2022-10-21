<?php

require('functions.php');
startPageDisplay();


if (!empty($_POST)) {
  $email = $_POST['email'];
  validEmail($email, 'email');


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
$pageTitle = 'パスワードを忘れた方';
require "head.php";
require "header.php";
?>


  <main class="l-main">
    <div class="c-user-management__wrapper">
      <h1 class="c-main__title"><?php
        echo $pageTitle; ?></h1>
      <p class="c-main__message">ご登録されているメールアドレス宛にパスワード再設定のご案内
        （パスワード再発行用のURLと認証キー）を送信します。</p>
      <!--      <p class="c-main__message &#45;&#45;error">エラーが発生しました。時間をおいてやり直してください。</p>-->
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


        <button class="c-button --full-width c-button__primary" type="button">
          パスワード再設定の案内メールを送信する
        </button>
      </form>
    </div>

  </main>

<?php
require "footer.php"; ?>