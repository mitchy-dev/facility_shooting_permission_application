<?php

require('functions.php');
startPageDisplay();
require "auth.php";

if (!empty($_POST)) {
  debug('POSTされました');
  $agreement = !empty($_POST['agreement']) ? $_POST['agreement'] : false;

  validEmpty($agreement, 'agreement', ERROR['AGREEMENT']);

  if (empty($errorMessages)) {
    debug('退会処理を開始します。');
    try {
      $dbh = dbConnect();
      $sql = 'update users set is_deleted = true where user_id = :user_id';
      $data = array(
              ':user_id' => $_SESSION['user_id']
      );
      if (!empty(queryPost($dbh, $sql, $data))) {
        debug('ログアウトします');
        $_SESSION = [];
        session_destroy();
        redirect('signUp.php');
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}


endPageDisplay();
?>
<?php
$pageTitle = '退会';
require "head.php";
require "header.php";
?>
<main class="l-main">
  <div class="c-user-management__wrapper">
    <h1 class="c-main__title"><?php
      echo $pageTitle; ?></h1>
    <p class="c-main__message">下記の同意事項をご確認のうえ、チェックを入れてお進みください。</p>
    <form method="post" class="u-mb-24">

      <div class="c-checkbox__container">
        <label for="agreement" class="c-checkbox__label">
          <input type="checkbox" class="c-checkbox__body" name="agreement" id="agreement" <?php
          if (!empty($_POST['agreement'])) {
            echo 'checked';
          } ?>>
          <span class="c-checkbox__name">退会後は会員登録内容は全て削除されます。</span>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage('agreement'); ?>
          </p>
        </label>
      </div>


      <div class="p-button-horizontal-wrapper">
        <button type="button"
                class="c-button c-button__primary p-button--withdrawal-horizontal-width-width u-float--left">
          <a href="mypage.php">
            退会しない
          </a>
        </button>
        <button type="submit" class="c-button c-button__text p-button--withdrawal-horizontal-width u-float--left"
        >
          退会する
        </button>
      </div>
    </form>

  </div>

</main>

<?php
require "footer.php"; ?>
