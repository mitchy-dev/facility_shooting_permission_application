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
$pageTitle = '登録した情報';
require "head.php";
require "header.php";
?>

<main class="l-main">
  <?php
  require "sidebar.php"; ?>

  <div class="l-main__my-page">
    <h1 class="c-main__title u-text-center"><?php
      echo $pageTitle; ?></h1>
    <div class="p-registered-applications__container">
      <a href="" class="p-registered-applications__link">
        <div class="u-overflow-hidden">
          <p class="p-registered-applications__title">湘南藤沢フィルムコミッション</p>
          <span class="c-status-label u-float-right">相談先</span>
        </div>
        <p class="p-registered-applications__text">電話：046−875−1234</p>
      </a>
    </div>

    <div class="p-registered-applications__container">
      <a href="" class="p-registered-applications__link">
        <div class="u-overflow-hidden">
          <p class="p-registered-applications__title">茨城県土木事務所</p>
          <span class="c-status-label --orange  u-float-right">申請先</span>
        </div>
        <p class="p-registered-applications__text">電話：046−875−1234</p>
      </a>
    </div>
  </div>
</main>


<?php
require "footer.php"; ?>
