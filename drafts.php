<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$viewData = fetchListOfRegisteredFacilities($_SESSION['user_id'], false);

endPageDisplay();
?>
<?php
$pageTitle = '下書き';
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
          <div class="p-card__layout--two-column">
            <div class="p-card">
              <a href="facilityDetail.php?facility_id=<?php
              echo sanitize($value['facility_id']) . appendGetParameter(array('facility_id')) ?>" class="p-card__link"
                 target="_blank">
                <div class="p-card__head">
                  <?php
                  if (empty($value['thumbnail_path'])): ?>
                    <div class="p-card__alternate-image-text">NO IMAGE</div>
                  <?php
                  endif; ?>
                  <img src="<?php
                  echo sanitize(
                          showFacilityImage(
                                  $value['thumbnail_path'],
                                  getAlternateImagePath('./alternateFacilityThumbnails')
                          )
                  ); ?>"
                       alt="海岸の写真" class="p-card__img">
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
          <p class="c-alternate-text">下書き中の海岸はありません</p>
        </div>
      <?php
      endif; ?>
    </div>
  </main>

<?php
require "footer.php"; ?>