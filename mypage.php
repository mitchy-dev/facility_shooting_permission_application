<?php

require('functions.php');
startPageDisplay();
require "auth.php";


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
        <img src="img/member_photo_noimage_thumb.png" alt="">
      </div>
      <p class="p-my-page__user-name">茨城県土木事務所</p>
      <table class="p-my-mage__number-of-registrations">
        <tr>
          <td>登録した海岸</td>
          <td>10</td>
        </tr>
        <tr>
          <td>申請先</td>
          <td>1</td>
        </tr>
        <tr>
          <td>事前相談先</td>
          <td>1</td>
        </tr>
      </table>

    </div>
  </main>

<?php
require "footer.php"; ?>