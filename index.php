<?php

require( 'functions.php' );
startPageDisplay();

//$_GET['region_id'] = 1;
//$_GET['prefecture_id'] = 1;
//$_GET['page'] = 1;

//DBからデータを取得する
$regionId     = ! empty( $_GET['region_id'] ) ? $_GET['region_id'] : 0;
$prefectureId = ! empty( $_GET['prefecture_id'] ) ? $_GET['prefecture_id'] : 0;
$page         = ! empty( $_GET['page'] ) ? $_GET['page'] : 1;
if ( ! is_numeric( $regionId ) || ! is_numeric( $prefectureId ) || ! is_numeric( $page ) ) {
  redirect( 'index.php' );
}
$viewData = fetchFacilitiesWithPrefectureId( $regionId, $prefectureId, $page );
if ( ! empty( $viewData['total_page_number'] ) ) {
  $paging = paging( $page, $viewData['total_page_number'] );
}
//地域データの取得
//この関数の引数に地域のidを受け取って、県名の表示を絞り込む
$regionsAndPreferences = fetchRegionsAndPrefectures( $regionId );
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
            if ( $regionId == 0 ) {
              echo 'selected';
            } ?>>全国
            </option>
            <?php
            if ( ! empty( $regionsAndPreferences['regions'] ) ): ?>
              <?php
              foreach ( $regionsAndPreferences['regions'] as $key => $value ): ?>
                <option value="<?php
                echo sanitize( $value['region_id'] ); ?>" class="c-select__option" <?php
                if ( $regionId == $value['region_id'] ) {
                  echo 'selected';
                } ?>><?php
                  echo sanitize( $value['name'] ); ?></option>
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
            if ( $prefectureId == 0 ) {
              echo 'selected';
            } ?>>全域
            </option>
            <?php
            if ( ! empty( $regionsAndPreferences['prefectures'] ) ): ?>
              <?php
              foreach ( $regionsAndPreferences['prefectures'] as $key => $value ): ?>
                <option value="<?php
                echo sanitize( $value['prefecture_id'] ); ?>" class="c-select__option" <?php
                if ( $prefectureId == $value['prefecture_id'] ) {
                  echo 'selected';
                } ?>><?php
                  echo sanitize( $value['name'] ); ?></option>
              <?php
              endforeach; ?>
            <?php
            endif; ?>
          </select>
        </div>
      </div>

    </div>

    <div class="c-results-count__container">
      <?php
      if ( ! empty( $viewData['number_of_contents'] ) ): ?>
        <p class="c-results-count">
          <?php
          echo sanitize( $viewData['number_of_tops_of_content'] ); ?>-<?php
          echo sanitize( $viewData['number_of_tails_of_content'] ); ?>件 / <?php
          echo sanitize( $viewData['number_of_contents'] ); ?>件中
        </p>
      <?php
      endif; ?>
    </div>

  </div>
  <?php
  if ( ! empty( $viewData['contents'] ) ): ?>
    <div class="p-card__container">
      <?php
      foreach ( $viewData['contents'] as $key => $value ): ?>
        <div class="p-card__layout">
          <div class="p-card">
            <a href="facilityDetail.php?facility_id=<?php
            echo sanitize( $value['facility_id'] ) . appendGetParameter( array( 'facility_id' ) ) ?>"
               class="p-card__link"
               target="_blank">
              <div class="p-card__head">
                <?php
                if ( empty( $value['thumbnail_path'] ) ): ?>
                  <div class="p-card__alternate-image-text">NO IMAGE</div>
                <?php
                endif; ?>
                <img src="<?php
                echo sanitize(
                        showImage(
                                $value['thumbnail_path'],
                                getAlternateImagePath( './alternateFacilityThumbnails' )
                        )
                ); ?>"
                     alt="海岸の写真" class="p-card__img">
              </div>
              <div class="p-card__foot">
                <div class="p-card__title-container">
                  <h2 class="p-card__title"><?php
                    echo sanitize( $value['facility_name'] ); ?></h2>
                </div>
                <div class="p-card__sub-title-container">
                  <p class="p-card__sub-title"><?php
                    echo sanitize( $value['name'] ); ?></p>
                </div>
              </div>
            </a>
          </div>
        </div>
      <?php
      endforeach; ?>

    </div>


    <?php
    if ( ! empty( $paging ) ): ?>
      <div class="c-paging__layout">
        <ul class="c-paging__list">
          <?php
          if ( $page != 1 ): ?>
            <li class="c-paging__item"><a href="index.php?page=1<?php
              echo sanitize( appendGetParameter( array( 'page' ) ) ); ?>">&lt;</a></li>
          <?php
          endif; ?>
          <?php
          for ( $i = $paging['firstPageNumber']; $i <= $paging['lastPageNumber']; ++ $i ): ?>
            <li class="c-paging__item <?php
            if ( $i == $page ) {
              echo 'is-active';
            } ?>">
              <p>
                <a href="index.php?page=<?php
                echo sanitize( $i . appendGetParameter( array( 'page' ) ) ) ?>"><?php
                  echo sanitize( $i ); ?>
                </a>
              </p>
            </li>
          <?php
          endfor; ?>
          <?php
          if ( $page != $viewData['total_page_number'] ): ?>
            <li class="c-paging__item"><a href="index.php?page=<?php
              echo sanitize( $viewData['total_page_number'] . appendGetParameter( array( 'page' ) ) ); ?>">&gt;</a></li>
          <?php
          endif; ?>
        </ul>
      </div>
    <?php
    endif; ?>

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
