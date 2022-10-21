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
  $avatarPath = keepFilePath('avatar_path', 'avatar_path', $dbUserData);


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
    <h1 class="c-main__title u-text-center">撮影申請先の登録</h1>
    <form method="post" action="">
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">組織名</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）千葉県</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10<j/p>-->
      </div>


      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">部署名</label>
        <!--          <p class="c-input__sub-label">撮影申請時に必要な情報です</p>-->
        <p class="c-input__help-message">（例)山武土木事務所</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <div class="c-input__container">
        <!--  <span class="c-status-label">ラベル</span>-->
        <p class="c-input__label">画像</p>
        <!--  <p class="c-input__sub-label">sub-label</p>-->
        <!--  <p class="c-input__help-message">help message</p>-->
        <!--  <p class="c-input__error-message">error</p>-->
        <label class="c-image-upload__label" for="image-uploads">
          ここに画像をドラッグ
          <input class="c-image-upload__body" type="file" class="" name="image-uploads" id="image-uploads"
                 accept=".jpg, .peg, .png">
          <img class="c-image-upload__img" src="img/sample.jpg" alt="">
        </label>
      </div>


      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">撮影申請の案内ページのURL</label>
        <!--          <p class="c-input__sub-label">撮影申請時に必要な情報です</p>-->
        <p class="c-input__help-message">（例）https://www.pref.chiba.lg.jp/cs-sanbu/kanrishinnsei.html</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">申請期限</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）撮影日の１０日前まで</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">電話番号</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）0475-54-1132</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">メールアドレス</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <!--          <p class="c-input__help-message">（例）</p>-->
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">連絡用フォームのURL</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">
          （例）https://www.pref.chiba.lg.jp/forms/faq.html?item3=http%3A%2F%2Fwww.pref.chiba.lg.jp%2Fcs-sanbu%2Fkanrishinnsei.html&item4=15644&item5=18510250</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">撮影申請の受付方法</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">
          （例）申請フォーム、メール、FAX、郵送</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">申請様式のURL</label>
        <p class="c-input__sub-label">様式のファイルが掲載されているページのURLを記載してください</p>
        <p class="c-input__help-message">
          （例）https://www.pref.chiba.lg.jp/cs-sanbu/kanrishinnsei.html</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>
      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="" class="c-input__label">申請様式名</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">
          （例）海岸使用申出書</p>
        <!--          <p class="c-input__error-message">error</p>-->
        <input type="text" class="c-input__body" placeholder="">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <button class="c-button --full-width c-button__primary" type="button">
        登録する
      </button>
    </form>
  </div>
</main>

<?php
require "footer.php"; ?>
