<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$viewData = fetchStakeholdersWithCategories($_SESSION['user_id']);
//var_dump($viewData);

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
    <?php
    if (!empty($viewData)): ?>
      <?php
      foreach ($viewData as $key => $value): ?>
        <div class="p-registered-applications__container">
          <a href="<?php
          echo 'registrationOfApplicationDestination.php?stakeholder_id=' . $value['stakeholder_id']; ?>"
             class="p-registered-applications__link">
            <div class="u-overflow-hidden">
              <p class="p-registered-applications__title"><?php
                echo $value['organization']; ?></p>
              <?php
              if (!empty($value['categories'])):?>
                <?php
                foreach ($value['categories'] as $key2 => $value2): ?>
                  <span class="c-status-label u-float-right u-ml-16 <?php
                  if ($value2['category_id'] == 2) {
                    echo '--orange';
                  } ?>"><?php
                    echo $value2['category_name']; ?></span>
                <?php
                endforeach; ?>
              <?php
              endif; ?>
            </div>
            <p class="p-registered-applications__text">電話：<?php
              echo $value['phone_number']; ?></p>
          </a>
        </div>
      <?php
      endforeach; ?>
    <?php
    endif; ?>

  </div>
</main>


<?php
require "footer.php"; ?>
