<?php

require( 'functions.php' );
startPageDisplay();

//$_GET['facility_id'] = 89;
if ( empty( $_GET['facility_id'] ) || ! is_numeric( $_GET['facility_id'] ) ) {
  debug( 'GETパラメータが有効でないためリダイレクトします' );
  redirect( 'index.php' );
}
$facilityId = ! empty( $_GET['facility_id'] ) ? $_GET['facility_id'] : '';
$viewData   = ! empty( $facilityId ) ? fetchFacilityAndStakeholdersAndImagePaths( $facilityId ) : array();
if ( ( ! empty( $facilityId ) && empty( $viewData ) ) || ( $viewData['published'] === 0 && $viewData['user_id'] != $_SESSION['user_id'] ) ) {
  debug( '不正なアクセスのためリダイレクトします' );
  redirect( 'index.php' );
}

if ( ! empty( $viewData['url_of_facility_location_map'] ) ) {
  $mapUrl = $viewData['url_of_facility_location_map'];
} elseif ( ! empty( $viewData['X(facility_location)'] ) && ! empty( $viewData['Y(facility_location)'] ) ) {
  $mapUrl = fetchGoogleMapUrl( $viewData['X(facility_location)'], $viewData['Y(facility_location)'] );
} else {
  $mapUrl = '';
}

//$viewData['facility_name'] = '';
//$viewData['facility_name_kana'] = '';
//$viewData['url_of_facility_information_page'] = 'https://www.fta-shonan.jp/';
//$viewData['title_of_facility_information_page'] = '観光協会';

endPageDisplay();
?>
<?php
$pageTitle = '海岸詳細';
require "head.php";
require "header.php";
?>

  <!--    メイン画像-->
  <div class="p-facility-image__container">
    <ul class="p-facility-image__list js-slider-container">
      <?php
      if ( ! empty( $viewData['images'] ) ): ?>
        <?php
        foreach ( $viewData['images'] as $key => $value ) : ?>
          <li class="p-facility-image__item js-slider-item">
            <img src="<?php
            echo sanitize( showImage( $value['image_path'],
                    getAlternateImagePath( './alternateFacilityImages' ) ) ); ?>"
                 class="p-facility-image__main js-image-main" alt="">
          </li>
        <?php
        endforeach; ?>
      <?php
      endif; ?>
    </ul>
    <ul class="slider__control">
      <li class="slider__control-item --right">
        <button type="button"
                class="slider__arrow slider__arrow-body slider__arrow-body--right js-slider-next"></button>
      </li>
      <li class="slider__control-item --left">
        <button type="button" class="slider__arrow slider__arrow-body slider__arrow-body--left js-slider-prev"></button>
      </li>
    </ul>
  </div>

  <main class="l-main">
    <!--    サムネイル画像-->
    <div class="p-facility-detail__wrapper">
      <div class="p-facility-thumbnail__container">
        <ul class="p-facility-thumbnail__list">
          <?php
          if ( ! empty( $viewData['images'] ) ) : ?>
            <?php
            foreach ( $viewData['images'] as $key => $value ): ?>
              <li class="p-facility-thumbnail__item">
                <?php
                if ( empty( $value['image_path'] ) ): ?>
                  <div class="p-facility-thumbnail__alternate-image-text">NO IMAGE</div>
                <?php
                endif; ?>
                <img src="<?php
                echo sanitize(
                        showImage( $value['image_path'], getAlternateImagePath( './alternateFacilityThumbnails' ) )
                ); ?>" class="js-image-thumbnail">
              </li>
            <?php
            endforeach; ?>
          <?php
          else: ?>
            <?php
            for ( $i = 0; $i < 3; $i ++ ) : ?>
              <li class="p-facility-thumbnail__item">
                <div class="p-facility-thumbnail__alternate-image-text">NO IMAGE</div>
                <img src="<?php
                echo sanitize( showImage( '', getAlternateImagePath( './alternateFacilityThumbnails' ) ) ); ?>"
                     class="js-image-thumbnail">
              </li>
            <?php
            endfor; ?>
          <?php
          endif; ?>
        </ul>
      </div>

      <!--      編集ボタン-->
      <?php
      if ( ! empty( $_SESSION['user_id'] ) && $_SESSION['user_id'] === $viewData['user_id'] ): ?>
        <a class="c-button --full-width c-button__secondary u-mb-48 u-display-block"
           href="registerFacility.php?facility_id=<?php
           echo sanitize( $facilityId ) . appendGetParameter( array( 'facility_id' ) ); ?>">
          掲載情報を変更する
        </a>
      <?php
      endif; ?>

      <!--      施設情報-->
      <div class="p-facility-detail__container">
        <h1 class="p-facility-detail__title">
          <?php
          if ( ! empty( $viewData['facility_name'] ) && ! empty( $viewData['facility_name_kana'] ) ) {
            echo sanitize( $viewData['facility_name'] . '(' . $viewData['facility_name_kana'] . ')' );
          } elseif ( ! empty( $viewData['facility_name'] ) ) {
            echo sanitize( $viewData['facility_name'] );
          }
          ?>
        </h1>
        <ul class="p-facility-detail__list">
          <li class="p-facility-detail__item">
            <img src="img/bx_map.svg" alt="" class="p-facility-detail__icon">
            <?php
            if ( ! empty( $mapUrl ) ): ?>
              <a href="<?php
              echo sanitize( $mapUrl ); ?>" target="_blank" class="p-facility-detail__text --link">
                <?php
                echo sanitize( $viewData['prefecture_name'] . $viewData['facility_address'] );
                ?>
              </a>
            <?php
            else: ?>
              <p class="p-facility-detail__text">
                <?php
                echo sanitize( $viewData['prefecture_name'] . $viewData['facility_address'] );
                ?>
              </p>
            <?php
            endif; ?>

          </li>
          <li class="p-facility-detail__item">
            <img src="img/information-sharp.svg" alt="" class="p-facility-detail__icon">
            <a href="<?php
            if ( ! empty( $viewData['url_of_facility_information_page'] ) ) {
              echo sanitize( $viewData['url_of_facility_information_page'] );
            } ?>" class="p-facility-detail__text --link">
              <?php
              if ( ! empty( $viewData['title_of_facility_information_page'] ) ) {
                echo sanitize( $viewData['title_of_facility_information_page'] );
              } elseif ( ! empty( $viewData['url_of_facility_information_page'] ) ) {
                echo sanitize( $viewData['url_of_facility_information_page'] );
              } ?>
            </a>
          </li>
          <li class="p-facility-detail__item">
            <img src="img/majesticons_yen-circle-line.svg" alt="" class="p-facility-detail__icon">
            <p class="p-facility-detail__text">
              <?php
              if ( ! isset( $viewData['shooting_fee'] ) ) {
                echo sanitize( $viewData['shooting_fee'] );
              }
              ?>
            </p>
          </li>
        </ul>
      </div>

      <!--相談先-->
      <div class="p-facility-detail__container">
        <h2 class="p-facility-detail__sub-title">撮影の事前相談<?php
          if ( ! empty( $viewData['prior_consultations'] ) ) {
            echo '（' . count( $viewData['prior_consultations'] ) . '件）';
          } ?></h2>
        <?php
        switch ( $viewData['is_need_consultation_of_shooting'] ) {
          case 0:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">情報が登録されていません</p>
            </div>
            <?php
            break;
          case 1:
            if ( ! empty( $viewData['prior_consultations'] ) ) :
              ?>
              <?php
              foreach ( $viewData['prior_consultations'] as $key => $value ): ?>
                <!--              この部分で相談先情報を表示-->
                <div class="p-facility-stakeholder__container">
                  <div class="p-facility-stakeholder__title-container">
                    <img src="img/ooui_user-avatar.svg" alt="" class="p-facility-stakeholder__title-icon">
                    <h3 class="p-facility-stakeholder__title --link">
                      <a href="<?php
                      if ( ! empty( $value['url_of_department_page'] ) ) {
                        echo sanitize( $value['url_of_department_page'] );
                      } ?>" target="_blank">
                        <?php
                        if ( ! empty( $value['organization'] ) ) {
                          echo sanitize( $value['organization'] );
                        }
                        if ( ! empty( $value['department'] ) ) {
                          echo sanitize( ' ' . $value['department'] );
                        }
                        ?>
                      </a>
                    </h3>
                  </div>

                  <ul class="p-facility-stakeholder__list">
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/akar-icons_phone.svg" alt="" class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="tel:<?php
                        if ( ! empty( $value['phone_number'] ) ) {
                          echo sanitize( $value['phone_number'] );
                        } ?>">
                          <?php
                          if ( ! empty( $value['phone_number'] ) ) {
                            echo sanitize( $value['phone_number'] );
                          } ?>
                        </a>
                      </p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_mail-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="mailto:<?php
                        if ( ! empty( $value['email'] ) ) {
                          echo sanitize( $value['email'] );
                        } ?>">
                          <?php
                          if ( ! empty( $value['email'] ) ) {
                            echo sanitize( $value['email'] );
                          } ?>
                        </a>
                      </p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_form-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="<?php
                        if ( ! empty( $value['url_of_contact_form'] ) ) {
                          echo sanitize( $value['url_of_contact_form'] );
                        } ?>" target="_blank">
                          問い合わせフォーム
                        </a>
                      </p>
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
        <h2 class="p-facility-detail__sub-title">撮影申請先<?php
          if ( ! empty( $viewData['application_destinations'] ) ) {
            echo '（' . count( $viewData['application_destinations'] ) . '件）';
          } ?></h2>
        <?php
        switch ( $viewData['is_need_application_of_shooting'] ) {
          case 0:
            ?>
            <div class="c-alternate-text__container">
              <p class="c-alternate-text">情報が登録されていません</p>
            </div>
            <?php
            break;
          case 1:
            if ( ! empty( $viewData['application_destinations'] ) ):
              ?>
              <?php
              foreach ( $viewData['application_destinations'] as $key => $value ): ?>
                <!--              申請先の表示箇所-->
                <div class="p-facility-stakeholder__container">
                  <div class="p-facility-stakeholder__title-container">
                    <img src="img/ooui_user-avatar.svg" alt="" class="p-facility-stakeholder__title-icon">
                    <h3 class="p-facility-stakeholder__title --link">
                      <a href="<?php
                      if ( ! empty( $value['url_of_department_page'] ) ) {
                        echo sanitize( $value['url_of_department_page'] );
                      } ?>" target="_blank">
                        <?php
                        if ( ! empty( $value['organization'] ) ) {
                          echo sanitize( $value['organization'] );
                        }
                        if ( ! empty( $value['department'] ) ) {
                          echo sanitize( ' ' . $value['department'] );
                        }
                        ?>
                      </a>
                    </h3>
                  </div>
                  <h4 class="p-facility-stakeholder__sub-title">撮影申請</h4>
                  <!--                  To Do 申請画面で申請案内のHP名を保存する処理を記述-->
                  <ul class="p-facility-stakeholder__list">
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/information-sharp.svg" alt="" class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="<?php
                        if ( ! empty( $value['url_of_shooting_application_guide'] ) ) {
                          echo sanitize( $value['url_of_shooting_application_guide'] );
                        } ?>" target="_blank">案内：
                          <?php
                          if ( ! empty( $value['title_of_shooting_application_guide'] ) ) {
                            echo sanitize( $value['title_of_shooting_application_guide'] );
                          } elseif ( ! empty( $value['url_of_shooting_application_guide'] ) ) {
                            echo sanitize( $value['url_of_shooting_application_guide'] );
                          }
                          ?>
                        </a>
                      </p>
                    </li>

                    <li class="p-facility-stakeholder__item">
                      <img src="/img/emojione-monotone_japanese-application-button.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">方法：<?php
                        if ( ! empty( $value['type_of_application_method'] ) ) {
                          echo sanitize( $value['type_of_application_method'] );
                        } ?></p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/jam_document.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="<?php
                        echo sanitize( $value['url_of_application_format'] ); ?>" target="_blank">様式：リンク先の「<?php
                          echo sanitize( $value['title_of_application_format'] ); ?>」</a></p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_calendar-clock-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text">期限：
                        <?php
                        if ( ! empty( $value['application_deadline'] ) ) {
                          echo sanitize( $value['application_deadline'] );
                        } ?>
                      </p>
                    </li>
                  </ul>
                  <h4 class="p-facility-stakeholder__sub-title">連絡</h4>
                  <ul class="p-facility-stakeholder__list">
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/akar-icons_phone.svg" alt="" class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="tel:<?php
                        if ( ! empty( $value['phone_number'] ) ) {
                          echo sanitize( $value['phone_number'] );
                        } ?>">
                          <?php
                          if ( ! empty( $value['phone_number'] ) ) {
                            echo sanitize( $value['phone_number'] );
                          } ?>
                        </a>
                      </p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_mail-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="mailto:<?php
                        if ( ! empty( $value['email'] ) ) {
                          echo sanitize( $value['email'] );
                        } ?>">
                          <?php
                          if ( ! empty( $value['email'] ) ) {
                            echo sanitize( $value['email'] );
                          } ?>
                        </a>
                      </p>
                    </li>
                    <li class="p-facility-stakeholder__item">
                      <img src="/img/fluent_form-24-regular.svg" alt=""
                           class="p-facility-stakeholder__item-icon">
                      <p class="p-facility-stakeholder__item-text --link">
                        <a href="<?php
                        if ( ! empty( $value['url_of_contact_form'] ) ) {
                          echo sanitize( $value['url_of_contact_form'] );
                        } ?>" target="_blank">
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