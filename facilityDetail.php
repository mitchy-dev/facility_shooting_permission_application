<?php

require('functions.php');
startPageDisplay();

//$_GET['facility_id'] = 89;
if (empty($_GET['facility_id']) || !is_numeric($_GET['facility_id'])) {
  debug('GETパラメータが有効でないためリダイレクトします');
  redirect('index.php');
}
$facilityId = !empty($_GET['facility_id']) ? $_GET['facility_id'] : '';
$viewData = !empty($facilityId) ? fetchFacilityAndStakeholdersAndImagePaths($facilityId) : array();
if (!empty($facilityId) && empty($viewData)) {
  debug('不正なアクセスのためリダイレクトします');
  redirect('index.php');
}
var_dump($viewData);
//$viewData['application_destinations'] = array();

endPageDisplay();
?>
<?php
$pageTitle = '海岸詳細';
require "head.php";
require "header.php";
?>


  <!--    メイン画像-->
  <div class="p-facility-image__container">
    <img src="<?php
    echo sanitize(showFacilityImage($viewData['thumbnail_path'])); ?>" alt="" class="p-facility-image__main">
  </div>
  <main class="l-main">

    <!--    サムネイル画像-->
    <div class="p-facility-detail__wrapper">
      <div class="p-facility-thumbnail__container">
        <ul class="p-facility-thumbnail__list">
          <?php
          foreach ($viewData['images'] as $key => $value): ?>
            <li class="p-facility-thumbnail__item">
              <img src="<?php
              echo sanitize(showFacilityImage($value['image_path'])); ?>">
            </li>
          <?php
          endforeach; ?>
        </ul>
      </div>

      <!--    説明-->
      <h1 class="p-facility-detail__title">
        <?php
        echo sanitize($viewData['facility_name']); ?>
      </h1>

      <?php
      if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] === $viewData['user_id']): ?>
        <button class="c-button --full-width c-button__secondary u-mb-48">
          <a href="registerFacility.php?facility_id=<?php
          echo sanitize($facilityId); ?>">
            掲載情報を変更する
          </a>
        </button>
      <?php
      endif; ?>
      <hr class="c-hr">

      <div class="p-facility-description__container">
        <p class="p-facility-description__title">
          施設に関すること
        </p>
        <dl class="p-facility-description__list">
          <dt>所在地</dt>
          <dd>神奈川県<?php
            echo sanitize($viewData['facility_address']); ?></dd>
          <dt>アクセス・駐車場・トイレなど</dt>
          <dd class="end">
            <a href="<?php
            echo sanitize($viewData['url_of_facility_information_page']); ?>">
              <?php
              if (empty($viewData['title_of_facility_information_page']) && !empty($viewData['url_of_facility_information'])) {
                echo sanitize($viewData['url_of_facility_information_page']);
              }
              echo sanitize($viewData['title_of_facility_information_page']); ?>
            </a>

          </dd>
          <dt>撮影料</dt>
          <dd><?php
            echo sanitize($viewData['shooting_fee']); ?></dd>
        </dl>
      </div>

      <hr class="c-hr">

      <div class="p-facility-description__container">
        <p class="p-facility-description__title">
          撮影の事前相談
        </p>
        <?php
        switch ($viewData['is_need_consultation_of_shooting']) {
          case 0:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">情報が登録されていません</p>
            </div>
            <?php
            break;
          case 1:
            if (!empty($viewData['prior_consultations'])) :
              ?>
              <?php
              foreach ($viewData['prior_consultations'] as $key => $value): ?>
                <dl class="p-facility-description__list">
                  <dt>相談先</dt>
                  <dd>
                    <a href="<?php
                    echo sanitize($value['url_of_shooting_application_guide']); ?>">
                      <?php
                      echo sanitize($value['organization'] . ' ' . $value['department']); ?>
                    </a>
                  </dd>
                  <dt>電話番号</dt>
                  <dd><?php
                    echo sanitize($value['phone_number']); ?></dd>
                  <dt>メールアドレス</dt>
                  <dd><?php
                    echo sanitize($value['email']); ?></dd>
                </dl>
              <?php
              endforeach;
            else:
              ?>
              <div class="c-alternate-text__container">
                <p class="c-alternate-text">情報が登録されていません</p>
              </div>
            <?php
            endif;
            break;
          case 2:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">撮影申請先にご相談ください</p>
            </div>
            <?php
            break;
          case 3:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">相談不要です</p>
            </div>
            <?php
            break;
        }
        ?>
      </div>
      <hr class="c-hr">

      <div class="p-facility-description__container">
        <p class="p-facility-description__title">
          撮影許可の申請
        </p>
        <?php
        switch ($viewData['is_need_application_of_shooting']) {
          case 0:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">情報が登録されていません</p>
            </div>
            <?php
            break;
          case 1:
            if (!empty($viewData['application_destinations'])):
              ?>
              <?php
              foreach ($viewData['application_destinations'] as $key => $value): ?>
                <dl class="p-facility-description__list">
                  <dt>
                    申請期限
                  </dt>
                  <dd>
                    <?php
                    echo sanitize($value['application_deadline']); ?>
                  </dd>
                  <dt>
                    申請先
                  </dt>
                  <dd>
                    <a href="<?php
                    $value['url_of_shooting_application_guide']; ?>"></a>
                    <?php
                    echo sanitize($value['organization'] . ' ' . $value['department']); ?>
                  </dd>
                  <dt>
                    申請様式
                  </dt>
                  <dd>
                    リンク先の<a href="<?php
                    echo sanitize($value['url_of_application_format']); ?>">「<?php
                      echo sanitize($value['title_of_application_format']); ?>」</a>
                  </dd>
                  <dt>
                    <a href="<?php
                    echo sanitize($value['url_of_contact_form']); ?>">
                      問い合わせフォーム
                    </a>
                  </dt>
                  <dd>
                    <a href="tel:">
                      <?php
                      echo sanitize($value['phone_number']); ?>
                    </a>
                  </dd>
                  <dt>
                    申請方法
                  </dt>
                  <dd>
                  </dd>
                </dl>
              <?php
              endforeach;
            else: ?>
              <div class="c-alternate-text__container">
                <p class="c-alternate-text">情報が登録されていません</p>
              </div>
            <?php
            endif;
            break;
          case 2:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">撮影申請不要です</p>
            </div>
            <?php
            break;
        } ?>


      </div>
      <hr class="c-hr">
      <!--    コメント欄-->

    </div>

  </main>

<?php
require "footer.php"; ?>