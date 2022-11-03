<?php

require('functions.php');
startPageDisplay();

//$_GET['region_id'] = 1;
//$_GET['prefecture_id'] = 1;

//DBからデータを取得する
$regionId = !empty($_GET['region_id']) ? $_GET['region_id'] : 0;
$prefectureId = !empty($_GET['prefecture_id']) ? $_GET['prefecture_id'] : 0;
$viewData = fetchFacilitiesWithPrefectureId($regionId, $prefectureId);
//var_dump($viewData);

//地域データの取得
//この関数の引数に地域のidを受け取って、県名の表示を絞り込む
$regionsAndPreferences = fetchRegionsAndPrefectures($regionId);
//var_dump($regionsAndPreferences);
endPageDisplay();
?>
<?php
$pageTitle = 'ホーム';
require "head.php";
require "header.php";
?>

<main class="l-main">
  <div class="c-tools-container">

    <div class="c-filters-container">


      <div class="c-filters-column">
        <div class="c-select__wrap">
          <select name="region" class="c-select__box js-select js-region">
            <option value="0" class="c-select__option" <?php
            if ($regionId == 0) {
              echo 'selected';
            } ?>>全国
            </option>
            <?php
            if (!empty($regionsAndPreferences['regions'])): ?>
              <?php
              foreach ($regionsAndPreferences['regions'] as $key => $value): ?>
                <option value="<?php
                echo sanitize($value['region_id']); ?>" class="c-select__option" <?php
                if ($regionId == $value['region_id']) {
                  echo 'selected';
                } ?>><?php
                  echo sanitize($value['name']); ?></option>
              <?php
              endforeach; ?>
            <?php
            endif; ?>
            <!--            <option value="2" class="c-select__option">東北</option>-->
          </select>
        </div>
      </div>
      <div class="c-filters-column">
        <div class="c-select__wrap">
          <select name="prefecture" class="c-select__box js-select js-prefecture">
            <option value="0" class="c-select__option" <?php
            if ($prefectureId == 0) {
              echo 'selected';
            } ?>>全域
            </option>
            <?php
            if (!empty($regionsAndPreferences['prefectures'])): ?>
              <?php
              foreach ($regionsAndPreferences['prefectures'] as $key => $value): ?>
                <option value="<?php
                echo sanitize($value['prefecture_id']); ?>" class="c-select__option" <?php
                if ($prefectureId == $value['prefecture_id']) {
                  echo 'selected';
                } ?>><?php
                  echo sanitize($value['name']); ?></option>
              <?php
              endforeach; ?>
            <?php
            endif; ?>
          </select>
        </div>
      </div>

    </div>

    <div class="c-results-count__container">
      <p class="c-results-count">20件 / 100件中</p>
    </div>

  </div>
  <?php
  if (!empty($viewData)): ?>
    <div class="p-card__container">
      <?php
      foreach ($viewData as $key => $value): ?>
        <div class="p-card__layout">
          <div class="p-card">
            <a href="facilityDetail.php?facility_id=<?php
            echo sanitize($value['facility_id']); ?>" class="p-card__link">
              <div class="p-card__head">
                <img src="<?php
                echo sanitize($value['thumbnail_path']); ?>" alt="海岸の写真" class="p-card__img">
              </div>
              <div class="p-card__foot">
                <div class="p-card__title-container">
                  <h2 class="p-card__title"><?php
                    echo sanitize($value['facility_name']); ?></h2>
                </div>
                <div class="p-card__sub-title-container">
                  <p class="p-card__sub-title"><?php
                    echo sanitize($value['prefecture_id']); ?></p>
                </div>
              </div>
            </a>
          </div>
        </div>
      <?php
      endforeach; ?>

    </div>


    <div class="c-paging__layout">
      <ul class="c-paging__list">
        <li class="c-paging__item"><a href="facilityDetail.php">&lt;</a></li>
        <li class="c-paging__item is-active"><a href="facilityDetail.php">1</a></li>
        <li class="c-paging__item"><a href="facilityDetail.php">2</a></li>
        <li class="c-paging__item"><a href="facilityDetail.php">3</a></li>
        <li class="c-paging__item"><a href="facilityDetail.php">4</a></li>
        <li class="c-paging__item"><a href="facilityDetail.php">5</a></li>
        <li class="c-paging__item"><a href="facilityDetail.php">&gt;</a></li>
      </ul>
    </div>

  <?php
  else: ?>
    <div class="c-alternate-text__container">
      <p class="c-alternate-text">検索条件に合致する海岸はありませんでした</p>
    </div>
  <?php
  endif; ?>
</main>

<?php
require "footer.php"; ?>
