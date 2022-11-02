<?php

require('functions.php');
startPageDisplay();

//DBからデータを取得する
$viewData = fetchFacilities();
//var_dump($viewData);
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
          <select name="region" class="c-select__box">
            <option value="0" class="c-select__option">全国</option>
            <option value="1" class="c-select__option">北海道</option>
            <option value="2" class="c-select__option">東北</option>
          </select>
        </div>
      </div>

      <div class="c-filters-column">
        <div class="c-select__wrap">
          <select name="region" class="c-select__box">
            <option value="0" class="c-select__option">全域</option>
            <option value="1" class="c-select__option">北海道</option>
            <option value="2" class="c-select__option">東北</option>
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
      <!--      <div class="p-card__layout">-->
      <!--        <div class="p-card">-->
      <!--          <a href="" class="p-card__link">-->
      <!--            <div class="p-card__head">-->
      <!--              <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">-->
      <!--            </div>-->
      <!--            <div class="p-card__foot">-->
      <!--              <div class="p-card__title-container">-->
      <!--                <h2 class="p-card__title">真鶴海岸</h2>-->
      <!--              </div>-->
      <!--              <div class="p-card__sub-title-container">-->
      <!--                <p class="p-card__sub-title">神奈川県</p>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </a>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="p-card__layout">-->
      <!--        <div class="p-card">-->
      <!--          <a href="" class="p-card__link">-->
      <!--            <div class="p-card__head">-->
      <!--              <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">-->
      <!--            </div>-->
      <!--            <div class="p-card__foot">-->
      <!--              <div class="p-card__title-container">-->
      <!--                <h2 class="p-card__title">真鶴海岸</h2>-->
      <!--              </div>-->
      <!--              <div class="p-card__sub-title-container">-->
      <!--                <p class="p-card__sub-title">神奈川県</p>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </a>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="p-card__layout">-->
      <!--        <div class="p-card">-->
      <!--          <a href="" class="p-card__link">-->
      <!--            <div class="p-card__head">-->
      <!--              <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">-->
      <!--            </div>-->
      <!--            <div class="p-card__foot">-->
      <!--              <div class="p-card__title-container">-->
      <!--                <h2 class="p-card__title">真鶴海岸</h2>-->
      <!--              </div>-->
      <!--              <div class="p-card__sub-title-container">-->
      <!--                <p class="p-card__sub-title">神奈川県</p>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </a>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="p-card__layout">-->
      <!--        <div class="p-card">-->
      <!--          <a href="" class="p-card__link">-->
      <!--            <div class="p-card__head">-->
      <!--              <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">-->
      <!--            </div>-->
      <!--            <div class="p-card__foot">-->
      <!--              <div class="p-card__title-container">-->
      <!--                <h2 class="p-card__title">真鶴海岸</h2>-->
      <!--              </div>-->
      <!--              <div class="p-card__sub-title-container">-->
      <!--                <p class="p-card__sub-title">神奈川県</p>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </a>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="p-card__layout">-->
      <!--        <div class="p-card">-->
      <!--          <a href="" class="p-card__link">-->
      <!--            <div class="p-card__head">-->
      <!--              <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">-->
      <!--            </div>-->
      <!--            <div class="p-card__foot">-->
      <!--              <div class="p-card__title-container">-->
      <!--                <h2 class="p-card__title">真鶴海岸</h2>-->
      <!--              </div>-->
      <!--              <div class="p-card__sub-title-container">-->
      <!--                <p class="p-card__sub-title">神奈川県</p>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </a>-->
      <!--        </div>-->
      <!--      </div>-->
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
