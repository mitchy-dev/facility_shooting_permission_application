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
      <!--      編集ボタン-->
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

      <!--      施設情報-->
      <div class="p-facility-detail__container">
        <h1 class="p-facility-detail__title">
          <?php
          echo sanitize($viewData['facility_name']); ?>
        </h1>
        <ul class="p-facility-detail__list">
          <li class="p-facility-detail__item">
            <img src="img/bx_map.svg" alt="" class="p-facility-detail__icon">
            <a href="<?php
            echo fetchGoogleMapUrl(sanitize($viewData['facility_address']));
            ?>" target="_blank" class="p-facility-detail__text">
              <?php
              echo sanitize($viewData['prefecture_name'] . $viewData['facility_address']);
              ?>
            </a>
          </li>
          <li class="p-facility-detail__item">
            <img src="img/information-sharp.svg" alt="" class="p-facility-detail__icon">
            <a href="<?php
            echo sanitize($viewData['url_of_facility_information_page']); ?>" class="p-facility-detail__text">
              <?php
              if (empty($viewData['title_of_facility_information_page']) && !empty($viewData['url_of_facility_information'])) {
                echo sanitize($viewData['url_of_facility_information_page']);
              }
              echo sanitize($viewData['title_of_facility_information_page']); ?>
              藤沢市観光協会</a>
          </li>
          <li class="p-facility-detail__item">
            <img src="img/majesticons_yen-circle-line.svg" alt="" class="p-facility-detail__icon">
            <p class="p-facility-detail__text">
              <?php
              echo sanitize($viewData['shooting_fee']); ?>
            </p>
          </li>
        </ul>
      </div>

      <!--撮影申請-->
      <div class="p-facility-detail__container">
        <h2 class="p-facility-detail__sub-title">撮影の事前相談（２件）</h2>
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
                <!--              この部分で相談先情報を表示-->
                <div class="p-facility-stakeholder__container">
                  <div class="p-facility-stakeholder__title-container">
                    <img src="img/ooui_user-avatar.svg" alt="" class="p-facility-stakeholder__title-icon">
                    <h3 class="p-facility-stakeholder__title">
                      <a href="<?php
                      echo sanitize($value['url_of_shooting_application_guide']); ?>">
                        <?php
                        echo sanitize($value['organization'] . ' ' . $value['department']); ?>
                      </a>
                    </h3>
                  </div>

                  <ul class="p-facility-stakeholder__list">
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/akar-icons_phone.svg" alt="" class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">
                        <?php
                        echo sanitize($value['phone_number']); ?></p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_mail-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">
                        <?php
                        echo sanitize($value['email']); ?></p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_form-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">問い合わせフォーム</p>
                    </li>
                  </ul>
                </div>
             
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

      <!--      撮影許可-->
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
      <!--    コメント欄-->

    </div>

  </main>

<?php
require "footer.php"; ?>