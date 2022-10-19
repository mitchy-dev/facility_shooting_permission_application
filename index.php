<?php

require('functions.php');
startPageDisplay();


endPageDisplay();
?>
<?php
$pageTitle = 'マイページ';
require "head.php";
require "header.php";
?>

<main class="l-main">
  <div class="c-tools-container">

    <div class="c-filters-container">

      <div class="c-filters-column">
        <div class="c-select__wrap">
          <select name="region" class="c-select__box">
            <option value="0" class="c-select__option">関東</option>
            <option value="1" class="c-select__option">北海道</option>
            <option value="2" class="c-select__option">東北</option>
          </select>
        </div>
      </div>

      <div class="c-filters-column">
        <div class="c-select__wrap">
          <select name="region" class="c-select__box">
            <option value="0" class="c-select__option">神奈川県</option>
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
  <div class="p-card__container">
    <div class="p-card__layout">
      <div class="p-card">
        <a href="facilityDetail.html" class="p-card__link">
          <div class="p-card__head">
            <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">
          </div>
          <div class="p-card__foot">
            <div class="p-card__title-container">
              <h2 class="p-card__title">真鶴海岸</h2>
            </div>
            <div class="p-card__sub-title-container">
              <p class="p-card__sub-title">神奈川県</p>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="p-card__layout">
      <div class="p-card">
        <a href="" class="p-card__link">
          <div class="p-card__head">
            <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">
          </div>
          <div class="p-card__foot">
            <div class="p-card__title-container">
              <h2 class="p-card__title">真鶴海岸</h2>
            </div>
            <div class="p-card__sub-title-container">
              <p class="p-card__sub-title">神奈川県</p>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="p-card__layout">
      <div class="p-card">
        <a href="" class="p-card__link">
          <div class="p-card__head">
            <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">
          </div>
          <div class="p-card__foot">
            <div class="p-card__title-container">
              <h2 class="p-card__title">真鶴海岸</h2>
            </div>
            <div class="p-card__sub-title-container">
              <p class="p-card__sub-title">神奈川県</p>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="p-card__layout">
      <div class="p-card">
        <a href="" class="p-card__link">
          <div class="p-card__head">
            <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">
          </div>
          <div class="p-card__foot">
            <div class="p-card__title-container">
              <h2 class="p-card__title">真鶴海岸</h2>
            </div>
            <div class="p-card__sub-title-container">
              <p class="p-card__sub-title">神奈川県</p>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="p-card__layout">
      <div class="p-card">
        <a href="" class="p-card__link">
          <div class="p-card__head">
            <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">
          </div>
          <div class="p-card__foot">
            <div class="p-card__title-container">
              <h2 class="p-card__title">真鶴海岸</h2>
            </div>
            <div class="p-card__sub-title-container">
              <p class="p-card__sub-title">神奈川県</p>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="p-card__layout">
      <div class="p-card">
        <a href="" class="p-card__link">
          <div class="p-card__head">
            <img src="img/sample.jpg" alt="海岸の写真" class="p-card__img">
          </div>
          <div class="p-card__foot">
            <div class="p-card__title-container">
              <h2 class="p-card__title">真鶴海岸</h2>
            </div>
            <div class="p-card__sub-title-container">
              <p class="p-card__sub-title">神奈川県</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div class="c-alternate-text__container">
    <p class="c-alternate-text">検索条件に合致する海岸はありませんでした</p>
  </div>
  <div class="c-paging__layout">
    <ul class="c-paging__list">
      <li class="c-paging__item"><a href="facilityDetail.html">&lt;</a></li>
      <li class="c-paging__item is-active"><a href="facilityDetail.html">1</a></li>
      <li class="c-paging__item"><a href="facilityDetail.html">2</a></li>
      <li class="c-paging__item"><a href="facilityDetail.html">3</a></li>
      <li class="c-paging__item"><a href="facilityDetail.html">4</a></li>
      <li class="c-paging__item"><a href="facilityDetail.html">5</a></li>
      <li class="c-paging__item"><a href="facilityDetail.html">&gt;</a></li>
    </ul>
  </div>
</main>

<?php
require "footer.php"; ?>