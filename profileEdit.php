<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$dbUserData = fetchUserData($_SESSION['user_id']);

if (!empty($_POST)) {
  $organization = $_POST['organization'];
  $representativeTitle = $_POST['representative_title'];
  $representativesName = $_POST['representatives_name'];
  $department = $_POST['department'];
  $personInCharge = $_POST['person_in_charge'];
  $phoneNumber = $_POST['phone_number'];
  $comment = $_POST['comment'];
  $avatarPath = keepFilePath($_FILES['avatar_path'], 'avatar_path', $dbUserData['avatar_path']);


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
                 comment = :comment,
                 avatar_path = :avatar_path
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
              ':avatar_path' => $avatarPath,
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

    <form method="post" action="" enctype="multipart/form-data">
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
        echo keepInputAndDatabase('organization', $dbUserData);
        ?>">
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
        echo keepInputAndDatabase('representative_title', $dbUserData);
        ?>">
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
        echo keepInputAndDatabase('representatives_name', $dbUserData); ?>">
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
        echo keepInputAndDatabase('department', $dbUserData); ?>">
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
        echo keepInputAndDatabase('person_in_charge', $dbUserData); ?>">
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
        echo keepInputAndDatabase('phone_number', $dbUserData); ?>">
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
        echo keepInputAndDatabase('comment', $dbUserData); ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>


      <div class="c-input__container">
        <p class="c-input__label">画像</p>
        <!--        <p class="c-input__sub-label">コメント時に表示されます</p>-->
        <!--  <p class="c-input__help-message">help message</p>-->
        <p class="c-input__error-message"><?php
          echo getErrorMessage('avatar_path'); ?></p>
        <label class="c-image-upload__label js-drag-area" for="avatar_path">
          ここに画像をドラッグ
          <input class="c-image-upload__body js-image-upload" type="file" name="avatar_path" id="avatar_path"
                 accept=".jpg, .peg, .png">
          <input type="hidden" name="max_file_size" value="<?php
          echo 2 * MEGA_BYTES; ?>">
          <img class="c-image-upload__img js-image-preview" src="<?php
          if (!empty($dbUserData['avatar_path'])) {
            echo $dbUserData['avatar_path'];
          } ?>" style="<?php
          if (!empty($dbUserData['avatar_path'])) {
            echo 'display:block;';
          } ?>" alt="">
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
