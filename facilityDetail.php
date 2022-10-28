<?php

require('functions.php');
startPageDisplay();
$facilityId = 1;

endPageDisplay();
?>
<?php
$pageTitle = '海岸詳細';
require "head.php";
require "header.php";
?>


  <!--    メイン画像-->
  <div class="p-facility-image__container">
    <img src="img/1920%20887.jpg" alt="" class="p-facility-image__main">
  </div>
  <main class="l-main">

    <!--    サムネイル画像-->
    <div class="p-facility-detail__wrapper">
      <div class="p-facility-thumbnail__container">
        <ul class="p-facility-thumbnail__list">
          <li class="p-facility-thumbnail__item">
            <img src="img/sample.jpg">
          </li>
          <li class="p-facility-thumbnail__item">
            <img src="img/sample.jpg">
          </li>
          <li class="p-facility-thumbnail__item">
            <img src="img/sample.jpg">
          </li>
        </ul>
      </div>

      <!--    説明-->
      <h1 class="p-facility-detail__title">
        江ノ島海岸
      </h1>

      <button class="c-button --full-width c-button__secondary u-mb-48">
        <a href="registerFacility.php?facility_id=<?php
        echo sanitize($facilityId); ?>">
          掲載情報を変更する
        </a>
      </button>
      <hr class="c-hr">

      <div class="p-facility-description__container">
        <p class="p-facility-description__title">
          施設に関すること
        </p>
        <dl class="p-facility-description__list">
          <dt>所在地</dt>
          <dd>神奈川県藤沢市江ノ島</dd>
          <dt>アクセス・駐車場・トイレなど</dt>
          <dd class="end">藤沢市観光協会</dd>
        </dl>
      </div>

      <hr class="c-hr">

      <div class="p-facility-description__container">
        <p class="p-facility-description__title">
          撮影の事前相談
        </p>
        <dl class="p-facility-description__list">
          <dt>相談先</dt>
          <dd>湘南藤沢フィルムコミッション</dd>
          <dt>電話番号</dt>
          <dd>046-834-1234</dd>
          <dt>FAX</dt>
          <dd>046-834-1234</dd>
          <dt>メールアドレス</dt>
          <dd>enoshima@gmial.com</dd>
        </dl>
      </div>

      <hr class="c-hr">

      <div class="p-facility-description__container">
        <p class="p-facility-description__title">
          撮影許可の申請
        </p>
        <dl class="p-facility-description__list">
          <dt>
            申請期限
          </dt>
          <dd>
            撮影日の10日前まで
          </dd>
          <dt>
            撮影料
          </dt>
          <dd>
            ￥０
          </dd>
          <dt>
            申請先
          </dt>
          <dd>
            神奈川県藤沢土木事務所
          </dd>
          <dt>
            申請様式
          </dt>
          <dd>
            リンク先の「海岸一時使用届」
          </dd>
          <dt>
            問い合わせフォーム
          </dt>
          <dd>
            046-845-1234
          </dd>
          <dt>
            申請方法
          </dt>
          <dd>
            メール
          </dd>
        </dl>
      </div>
      <hr class="c-hr">
      <!--    コメント欄-->

    </div>

  </main>

<?php
require "footer.php"; ?>