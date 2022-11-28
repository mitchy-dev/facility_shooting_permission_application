<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$dbUserData = fetchUserData($_SESSION['user_id']);
$organization = !empty($dbUserData['organization']) ? $dbUserData['organization'] : '';
$representativeTitle = !empty($dbUserData['representative_title']) ? $dbUserData['representative_title'] : '';
$department = !empty($dbUserData['department']) ? $dbUserData['department'] : '';

$registeredFacilitiesCount = fetchRegisteredFacilitiesCount($_SESSION['user_id']);
$registeredStakeholdersCount = fetchRegisteredStakeholdersCount($_SESSION['user_id']);
endPageDisplay();
?>
<?php
$pageTitle = 'マイページ';
require "head.php";
require "header.php";
?>


  <main class="l-main">
    <?php
    require "sidebar.php"; ?>

    <div class="l-main__my-page">
      <h1 class="c-main__title u-text-center"><?php
        echo $pageTitle; ?></h1>
      <div class="p-my-page__avatar__container">
        <img src="<?php
        echo sanitize(showImage($dbUserData['avatar_path'], '/img/user-avatar.png')); ?>" alt="">
      </div>
      <p class="p-my-page__user-name"><?php
        echo sanitize($organization . $department); ?></p>
      <table class="p-my-mage__number-of-registrations">
        <tr>
          <td>登録した海岸</td>
          <td><?php
            echo sanitize($registeredFacilitiesCount); ?></td>
        </tr>
        <tr>
          <td>登録した事前相談・撮影申請先</td>
          <td><?php
            echo sanitize($registeredStakeholdersCount); ?></td>
        </tr>
      </table>

    </div>
  </main>

<?php
require "footer.php"; ?>