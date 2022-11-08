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
      <p><a href="index.php<?php
        echo appendGetParameter(array('facility_id'), false); ?>">&lt戻る</a></p>
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

      <!--相談先-->
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
      <div class="p-facility-detail__container">
        <h2 class="p-facility-detail__sub-title">撮影申請先（３件）</h2>
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
                <!--              申請先の表示箇所-->
                <!--             元の記述-->
                <div class="p-facility-stakeholder__container">
                  <div class="p-facility-stakeholder__title-container">
                    <img src="img/ooui_user-avatar.svg" alt="" class="p-facility-stakeholder__title-icon">
                    <h3 class="p-facility-stakeholder__title">
                      <a href="<?php
                      $value['url_of_shooting_application_guide']; ?>">
                        <?php
                        echo sanitize($value['organization'] . ' ' . $value['department']); ?>
                      </a>
                    </h3>
                  </div>
                  <h4 class="p-facility-stakeholder__sub-title">撮影申請</h4>
                  <!--                  To Do 申請画面で申請案内のHP名を保存する処理を記述-->
                  <ul class="p-facility-stakeholder__list">
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/information-sharp.svg" alt="" class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">海岸の許認可　ー　神奈川県ホームページ</p>
                    </li>

                    <li class="p-facility-stakeholder__item">
                      <img src="/img/emojione-monotone_japanese-application-button.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">FAX、窓口、郵送</p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/jam_document.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">
                        リンク先の<a href="<?php
                        echo sanitize($value['url_of_application_format']); ?>">「<?php
                          echo sanitize($value['title_of_application_format']); ?>」</a></p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_calendar-clock-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">
                        <?php
                        echo sanitize($value['application_deadline']); ?>
                      </p>
                    </li>
                  </ul>
                  <h4 class="p-facility-stakeholder__sub-title">連絡</h4>
                  <ul class="p-facility-stakeholder__list">
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/akar-icons_phone.svg" alt="" class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">
                        <a href="tel:">
                          <?php
                          echo sanitize($value['phone_number']); ?>
                        </a>
                      </p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_mail-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">info@gmail.com</p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_form-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">
                        <a href="<?php
                        echo sanitize($value['url_of_contact_form']); ?>">
                          問い合わせフォーム
                        </a>
                      </p>
                    </li>
                  </ul>
                </div>

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