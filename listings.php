<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$viewData = fetchListOfRegisteredFacilities($_SESSION['user_id']);
//var_dump($viewData);

endPageDisplay();
?>
<?php
$pageTitle = '登録した海岸';
require "head.php";
require "header.php";
?>

  <main class="l-main">
    <?php
    require "sidebar.php"; ?>

    <div class="l-main__my-page">
      <h1 class="c-main__title u-text-center"><?php
        echo $pageTitle ?></h1>

      <?php
      if (!empty($viewData)): ?>
        <?php
        foreach ($viewData as $key => $value): ?>
          <div class="p-card__layout --my-page">
            <div class="p-card">
              <a href="facilityDetail.php" class="p-card__link">
                <div class="p-card__head">
                  <img src="<?php
                  echo showFacilityImage($value['thumbnail_path']); ?>" alt="海岸の写真" class="p-card__img">
                </div>
                <div class="p-card__foot">
                  <div class="p-card__title-container">
                    <h2 class="p-card__title"><?php
                      echo sanitize($value['facility_name']); ?></h2>
                  </div>
                  <div class="p-card__sub-title-container">
                    <p class="p-card__sub-title"><?php
                      echo sanitize($value['prefecture']); ?></p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        <?php
        endforeach; ?>
      <?php
      else: ?>
        <div class="c-alternate-text__container">
          <p class="c-alternate-text">登録されている海岸はありません</p>
        </div>
      <?php
      endif; ?>
    </div>
  </main>

<?php
require "footer.php"; ?>