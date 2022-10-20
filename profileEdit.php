<?php

require('functions.php');
startPageDisplay();
require "auth.php";

if (!empty($_POST)) {
  $organization = $_POST['organization'];
  $representativeTitle = $_POST['representative_title'];
  $representativesName = $_POST['representatives_name'];
  $department = $_POST['department'];
  $personInCharge = $_POST['person_in_charge'];
  $phoneNumber = $_POST['phone_number'];
  $comment = $_POST['comment'];

  validMaxLength($organization, 'organization');
  validMaxLength($representativeTitle, 'representativeTitle');
  validMaxLength($representativesName, 'representativesName');
  validMaxLength($department, 'department');
  validMaxLength($personInCharge, 'personInCharge');
  validMaxLength($phoneNumber, 'phoneNumber');
  validMaxLength($comment, 'comment');

  if (empty($errorMessages)) {
    debug('プロフィールを更新します');
    try {
      $dbh = dbConnect();
      $sql = 'update users set 
                 organization = :organization, 
                 representative_title = :representative_title, 
                 representatives_name = :representatives_name, 
                 department = :department, 
                 person_in_charge = :person_in_charge, 
                 phone_number = :phone_number, 
                 comment = :comment 
              where 
                    user_id = :user_id and 
                    is_deleted = false';
      $data = array(
              ':organization' => $organization,
              ':representative_title' => $representativeTitle,
              ':representatives_name' => $representativesName,
              ':department' => $department,
              ':person_in_charge' => $personInCharge,
              ':phone_number' => $phoneNumber,
              ':comment' => $comment,
              ':user_id' => $_SESSION['user_id'],

      );
      if (!empty(queryPost($dbh, $sql, $data))) {
        $_SESSION['message'] = SUCCESS['PROFILE_EDIT'];

        redirect('profileEdit.php');
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}


endPageDisplay();
?>
<?php
$pageTitle = 'プロフィール';
require "head.php";
require "header.php";
?>

<main class="l-main">
  <?php
  require "sidebar.php"; ?>

  <div class="l-main__my-page">
    <h1 class="c-main__title u-text-center">プロフィール</h1>
    <p class="c-main__message">今後追加予定の撮影申請機能で必要となる情報を入力するフォームです。</p>
    <p class="c-main__message --error"><?php
      getErrorMessage('common'); ?></p>

    <form method="post" action="">
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="organization" class="c-input__label">組織名</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）株式会社海岸ロケ</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('organization'); ?>
        </p>
        <input type="text" name="organization" id="organization"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('organization'); ?>" value="<?php
        if (!empty($_POST['organization'])) {
          echo $_POST['organization'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="representative_title" class="c-input__label">代表者肩書</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）代表取締役</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('representative_title'); ?>
        </p>
        <input type="text" name="representative_title" id="representative_title"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('representative_title'); ?>" value="<?php
        if (!empty($_POST['representative_title'])) {
          echo $_POST['representative_title'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="representatives_name" class="c-input__label">代表者名</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）浦島　太郎</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('representatives_name'); ?>
        </p>
        <input type="text" name="representatives_name" id="representatives_name"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('representatives_name'); ?>" value="<?php
        if (!empty($_POST['representatives_name'])) {
          echo $_POST['representatives_name'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>
      <div class="c-input__container">
        <!--                <span class="c-status-label">ラベル</span>-->
        <label for="department" class="c-input__label">担当部署名</label>
        <!--                <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）企画課</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('department'); ?>
        </p>
        <input type="text" name="department" id="department"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('department'); ?>" value="<?php
        if (!empty($_POST['department'])) {
          echo $_POST['department'];
        } ?>">
        <!--                <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="person_in_charge" class="c-input__label">担当者名</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）三年　寝太郎</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('person_in_charge'); ?>
        </p>
        <input type="text" name="person_in_charge" id="person_in_charge"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('person_in_charge'); ?>" value="<?php
        if (!empty($_POST['person_in_charge'])) {
          echo $_POST['person_in_charge'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="phone_number" class="c-input__label">電話番号</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）03-1234-1234</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('phone_number'); ?>
        </p>
        <input type="text" name="phone_number" id="phone_number"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('phone_number'); ?>" value="<?php
        if (!empty($_POST['phone_number'])) {
          echo $_POST['phone_number'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="comment" class="c-input__label">メモ</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）組織内での共有事項など</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('comment'); ?>
        </p>
        <input type="text" name="comment" id="comment"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('comment'); ?>" value="<?php
        if (!empty($_POST['comment'])) {
          echo $_POST['comment'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>


      <div class="c-input__container">
        <p class="c-input__label">画像</p>
        <p class="c-input__sub-label">コメント時に表示されます</p>
        <!--  <p class="c-input__help-message">help message</p>-->
        <!--  <p class="c-input__error-message">error</p>-->
        <label class="c-image-upload__label js-drag-area" for="image-uploads">
          ここに画像をドラッグ
          <input class="c-image-upload__body" type="file" class="" name="image-uploads" id="image-uploads"
                 accept=".jpg, .peg, .png">
          <img class="c-image-upload__img" src="img/sample.jpg" alt="">
        </label>
      </div>
      <button class="c-button --full-width c-button__primary" type="submit">
        変更する
      </button>
    </form>
  </div>
</main>

<?php
require "footer.php"; ?>
