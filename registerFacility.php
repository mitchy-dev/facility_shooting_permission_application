<?php

require( 'functions.php' );
startPageDisplay();
require "auth.php";

//$_GET['facility_id'] = 92;
if ( ! empty( $_GET['facility_id'] ) && ! is_numeric( $_GET['facility_id'] ) ) {
  debug( '取得したGETパラメータが数値でないためリダイレクトします' );
  redirect( 'index.php' );
}

$facilityId     = ! empty( $_GET['facility_id'] ) ? $_GET['facility_id'] : '';
$dbFacilityData = ! empty( $facilityId ) ? fetchFacility( $_SESSION['user_id'], $facilityId ) : array();
if ( ! empty( $facilityId ) && empty( $dbFacilityData ) ) {
  debug( '不正なアクセスのためリダイレクトします' );
  redirect( 'index.php' );
}


//DBから写真のデータを取得する必要がある
$dbFacilityImagePaths = fetchFacilityImagePaths( $facilityId );
//debug('$dbFacilityImagePaths:' . print_r($dbFacilityImagePaths, true));
$dbPrefectures              = fetchPrefectures();
$dbStakeholdersWithCategory = fetchStakeholdersWithCategories( $_SESSION['user_id'] );
//debug('取得した関係者のデータ：' . print_r($dbStakeholdersWithCategory, true));
$dbPriorConsultationsWithCategory      = array();
$dbApplicationDestinationsWithCategory = array();
if ( ! empty( $dbStakeholdersWithCategory ) ) {
  foreach ( $dbStakeholdersWithCategory as $key => $value ) {
    if ( ! empty( $value['categories'] ) ) {
      $value['categoryIds'] = array_column( $value['categories'], 'category_id' );
      if ( in_array( 1, $value['categoryIds'] ) ) {
        $dbPriorConsultationsWithCategory[] = $value;
      }
    }
  }
  unset( $key, $value );
  foreach ( $dbStakeholdersWithCategory as $key => $value ) {
    if ( ! empty( $value['categories'] ) ) {
      $value['categoryIds'] = array_column( $value['categories'], 'category_id' );
      if ( in_array( 2, $value['categoryIds'] ) ) {
        $dbApplicationDestinationsWithCategory[] = $value;
      }
    }
  }
  unset( $key, $value );
}

debug( '$dbPriorConsultationsWithCategory:' . print_r( $dbPriorConsultationsWithCategory, true ) );
debug( '$dbApplicationDestinationsWithCategory:' . print_r( $dbApplicationDestinationsWithCategory, true ) );

$dbStakeholdersAssociatedWithCategory = fetchStakeholdersAssociatedWithTheFacility( $facilityId );
$dbApplicationDestinations            = array();
$dbPriorConsultations                 = array();

$dbStakeholdersAssociatedWithCategoryIds = array();

if ( ! empty( $dbStakeholdersAssociatedWithCategory ) ) {
  foreach ( $dbStakeholdersAssociatedWithCategory as $key => $value ) {
    if ( $value['stakeholder_category_id'] == 1 ) {
      $dbPriorConsultations[] = $value;
    } elseif ( $value['stakeholder_category_id'] == 2 ) {
      $dbApplicationDestinations[] = $value;
    }

//    入力値保持用に相談先と申請先のidの配列を生成
    if ( ! empty( $dbPriorConsultations ) ) {
      $dbStakeholdersAssociatedWithCategoryIds['prior_consultation'] = array_column(
              $dbPriorConsultations,
              'stakeholder_id'
      );
    }

    if ( ! empty( $dbApplicationDestinations ) ) {
      $dbStakeholdersAssociatedWithCategoryIds['application_destination'] = array_column(
              $dbApplicationDestinations,
              'stakeholder_id'
      );
    }
  }
}
//
if ( ! empty( $_POST['published'] ) ) {
  debug( 'POST:' . print_r( $_POST, true ) );
//  debug('FILES:' . print_r($_FILES, true));
  $facilityName   = $_POST['facility_name'];
  $facilityImages = reArrayFiles( $_FILES['facility_image'] );
//  debug('facilityImages:' . print_r($facilityImages, true));
  if ( ! empty( $facilityImages ) ) {
    foreach ( $facilityImages as $key => $value ) {
      $facilityImagePaths[] = keepFilePath(
              $value,
              'common',
              ! empty( $dbFacilityImagePaths[ $key ] ) ? $dbFacilityImagePaths[ $key ] : ''
      );
    }
    unset( $key, $value );
  }
  $thumbnail                = ! empty( $facilityImages ) ? array_values( $facilityImages )[0] : '';
  $thumbnailPath            = keepFilePath(
          $thumbnail,
          'common',
          ! empty( $dbFacilityData['thumbnail_path'] ) ?
                  $dbFacilityData['thumbnail_path'] : '',
          308,
          219
  );
  $prefectureId             = $_POST['prefecture_id'];
  $facilityAddress          = ! empty( $_POST['facility_address'] ) ? $_POST['facility_address'] : '';
  $urlOfFacilityLocationMap = ! empty( $_POST['url_of_facility_location_map'] ) ? $_POST['url_of_facility_location_map'] : '';
  $facilityLocation         = ! empty( $_POST['url_of_facility_location_map'] ) ? extractCoordinatesFromUrl( $_POST['url_of_facility_location_map'] ) : array();
  if ( empty( $facilityLocation ) && ! empty( $dbFacilityData['X(facility_location)'] ) ) {
    $facilityLocation = array(
            'lat' => $dbFacilityData['X(facility_location)'],
            'lon' => $dbFacilityData['Y(facility_location)']
    );
  }
  $shootingFee                    = $_POST['shooting_fee'];
  $urlOfFacilityInformationPage   = $_POST['url_of_facility_information_page'];
  $titleOfFacilityInformationPage = ! empty( $_POST['url_of_facility_information_page'] ) ? fetchTitleFromURL(
          $_POST['url_of_facility_information_page']
  ) : '';
  $published                      = $_POST['published'] === 'published' ? 1 : 0;
  $isNeedConsultationOfShooting   = $_POST['is_need_consultation_of_shooting'];
  $isNeedApplicationOfShooting    = $_POST['is_need_application_of_shooting'];

  $priorConsultation      = ! empty( $_POST['prior_consultation'] ) ? $_POST['prior_consultation'] : array();
  $applicationDestination = ! empty( $_POST['application_destination'] ) ? $_POST['application_destination'] : array();


  if ( empty( $errorMessages ) ) {
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();
      if ( ! empty( $dbFacilityData ) ) {
        debug( '海岸の情報を更新します' );
        $sql  = "update facilities set 
                      facility_name = :facility_name, 
                      thumbnail_path = :thumbnail_path, 
                      prefecture_id = :prefecture_id, 
                      facility_address = :facility_address, 
                      url_of_facility_location_map = :url_of_facility_location_map,
                      facility_location = ST_GeomFromText(CONCAT('POINT(',:lat,' ',:lon,')')),
                      shooting_fee = :shooting_fee, 
                      url_of_facility_information_page = :url_of_facility_information_page, 
                      title_of_facility_information_page = :title_of_facility_information_page, 
                      is_need_consultation_of_shooting = :is_need_consultation_of_shooting,
                      is_need_application_of_shooting = :is_need_application_of_shooting,
                      published = :published
                    where user_id = :user_id and 
                      facility_id = :facility_id and 
                      is_deleted = false";
        $data = array(
                ':facility_name'                      => $facilityName,
                ':thumbnail_path'                     => $thumbnailPath,
                ':prefecture_id'                      => $prefectureId,
                ':facility_address'                   => $facilityAddress,
                ':url_of_facility_location_map'       => $urlOfFacilityLocationMap,
                ':lat'                                => $facilityLocation['lat'],
                ':lon'                                => $facilityLocation['lon'],
                ':shooting_fee'                       => $shootingFee,
                ':url_of_facility_information_page'   => $urlOfFacilityInformationPage,
                ':title_of_facility_information_page' => $titleOfFacilityInformationPage,
                ':is_need_consultation_of_shooting'   => $isNeedConsultationOfShooting,
                ':is_need_application_of_shooting'    => $isNeedApplicationOfShooting,
                ':published'                          => $published,
                ':facility_id'                        => $facilityId,
                ':user_id'                            => $_SESSION['user_id'],
        );
        if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
          throw new Exception( ERROR['EXCEPTION'] );
        }

//        画像のパスの更新
        if ( $facilityImagePaths != $dbFacilityImagePaths ) {
          $sql  = 'delete from facility_images where facility_id = :facility_id';
          $data = array( ':facility_id' => $facilityId );
          if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
            throw new Exception( ERROR['EXCEPTION'] );
          }
          if ( ! empty( $facilityImagePaths ) ) {
            foreach ( $facilityImagePaths as $key => $value ) {
              $sql  = 'insert into facility_images(facility_id, image_path, created_at) values (:facility_id, :image_path, :created_at)';
              $data = array(
                      ':facility_id' => $facilityId,
                      ':image_path'  => $value,
                      ':created_at'  => date( 'Y-m-d H:i:s' ),
              );
              if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
                throw new Exception( ERROR['EXCEPTION'] );
              }
            }
            unset( $key, $value );
          }
        }

//        相談先の更新
        if ( $priorConsultation != $dbPriorConsultations ) {
          debug( '相談先の更新をします' );
//          DBが空の場合は削除を実行する必要がない、また、DBのデータがpostと一緒なら実行する必要ない
          $sql  = 'delete from facilities_stakeholders where facility_id = :facility_id and stakeholder_category_id = :stakeholder_category_id';
          $data = array(
                  ':facility_id'             => $facilityId,
                  ':stakeholder_category_id' => 1
          );
          if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
            throw new Exception( ERROR['EXCEPTION'] );
          }
          if ( ! empty( $priorConsultation ) ) {
            foreach ( $priorConsultation as $key => $value ) {
              $sql  = 'insert into facilities_stakeholders(facility_id, stakeholder_id, stakeholder_category_id, created_at) values (:facility_id, :stakeholder_id, :stakeholder_category_id, :created_at)';
              $data = array(
                      ':facility_id'             => $facilityId,
                      ':stakeholder_id'          => $value,
                      ':stakeholder_category_id' => 1,
                      ':created_at'              => date( 'Y-m-d H:i:s' ),
              );
              if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
                throw new Exception( ERROR['EXCEPTION'] );
              }
            }
            unset( $key, $value );
          }
        }

        //        申請先の更新
        if ( $applicationDestination != $dbApplicationDestinations ) {
          $sql2  = ' delete from facilities_stakeholders where facility_id = :facility_id and stakeholder_category_id = :stakeholder_category_id; ';
          $data2 = array(
                  ':facility_id'             => $facilityId,
                  ':stakeholder_category_id' => 2
          );
          if ( empty( queryPost( $dbh, $sql2, $data2 ) ) ) {
            throw new Exception( ERROR['EXCEPTION'] );
          }
          if ( ! empty( $applicationDestination ) ) {
            foreach ( $applicationDestination as $key2 => $value2 ) {
              $sql2  = 'insert into facilities_stakeholders(facility_id, stakeholder_id, stakeholder_category_id, created_at) 
values (:facility_id, :stakeholder_id, :stakeholder_category_id, :created_at)';
              $data2 = array(
                      ':facility_id'             => $facilityId,
                      ':stakeholder_id'          => $value2,
                      ':stakeholder_category_id' => 2,
                      ':created_at'              => date( 'Y-m-d H:i:s' ),
              );
              if ( empty( queryPost( $dbh, $sql2, $data2 ) ) ) {
                throw new Exception( ERROR['EXCEPTION'] );
              }
            }
            unset( $key, $value );
          }
        }
        $dbh->commit();
        $_SESSION['message'] = SUCCESS['UPDATE'];
        redirect( 'facilityDetail.php?facility_id=' . sanitize( $facilityId ) );
      } else {
        debug( '海岸の情報を登録します' );
        $sql = "insert into facilities(user_id, facility_name, thumbnail_path, prefecture_id, facility_address, url_of_facility_location_map, facility_location, shooting_fee, url_of_facility_information_page, title_of_facility_information_page, published, is_need_consultation_of_shooting, is_need_application_of_shooting, created_at) values (:user_id, :facility_name, :thumbnail_path, :prefecture_id, :facility_address, :url_of_facility_location_map, ST_GeomFromText(CONCAT('POINT(',:lat,' ',:lon,')')), :shooting_fee, :url_of_facility_information_page, :title_of_facility_information_page, :published, :is_need_consultation_of_shooting, :is_need_application_of_shooting, :created_at)";
        if ( empty( $facilityLocation ) ) {
          $facilityLocation['lat'] = null;
          $facilityLocation['lon'] = null;
        }
        $data = array(
                ':user_id'                            => $_SESSION['user_id'],
                ':facility_name'                      => $facilityName,
                ':thumbnail_path'                     => $thumbnailPath,
                ':prefecture_id'                      => $prefectureId,
                ':facility_address'                   => $facilityAddress,
                ':url_of_facility_location_map'       => $urlOfFacilityLocationMap,
                ':lat'                                => $facilityLocation['lat'],
                ':lon'                                => $facilityLocation['lon'],
                ':shooting_fee'                       => $shootingFee,
                ':url_of_facility_information_page'   => $urlOfFacilityInformationPage,
                ':title_of_facility_information_page' => $titleOfFacilityInformationPage,
                ':published'                          => $published,
                ':is_need_consultation_of_shooting'   => $isNeedConsultationOfShooting,
                ':is_need_application_of_shooting'    => $isNeedApplicationOfShooting,
                ':created_at'                         => date( 'Y-m-d H:i:s' ),
        );
        if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
          throw new Exception( ERROR['EXCEPTION'] );
        }
        $facilityId = $dbh->lastInsertId();
        if ( ! empty( $facilityImagePaths ) ) {
          foreach ( $facilityImagePaths as $key => $value ) {
            $sql  = 'insert into facility_images(facility_id, image_path, created_at) values (:facility_id, :image_path, :created_at)';
            $data = array(
                    ':facility_id' => $facilityId,
                    ':image_path'  => $value,
                    ':created_at'  => date( 'Y-m-d H:i:s' ),
            );
            if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
              throw new Exception( ERROR['EXCEPTION'] );
            }
          }
          unset( $key, $value );
        }
        if ( ! empty( $priorConsultation ) ) {
          foreach ( $priorConsultation as $key => $value ) {
            $sql  = 'insert into facilities_stakeholders(facility_id, stakeholder_id, stakeholder_category_id, created_at) 
values (:facility_id, :stakeholder_id, :stakeholder_category_id, :created_at)';
            $data = array(
                    ':facility_id'             => $facilityId,
                    ':stakeholder_id'          => $value,
                    ':stakeholder_category_id' => 1,
                    ':created_at'              => date( 'Y-m-d H:i:s' )
            );
            if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
              throw new Exception( ERROR['EXCEPTION'] );
            }
          }
          unset( $key, $value );
        }
        if ( ! empty( $applicationDestination ) ) {
          foreach ( $applicationDestination as $key => $value ) {
            $sql  = 'insert into facilities_stakeholders(facility_id, stakeholder_id, stakeholder_category_id, created_at) 
values (:facility_id, :stakeholder_id, :stakeholder_category_id, :created_at)';
            $data = array(
                    ':facility_id'             => $facilityId,
                    ':stakeholder_id'          => $value,
                    ':stakeholder_category_id' => 2,
                    ':created_at'              => date( 'Y-m-d H:i:s' )
            );
            if ( empty( queryPost( $dbh, $sql, $data ) ) ) {
              throw new Exception( ERROR['EXCEPTION'] );
            }
          }
          unset( $key, $value );
        }
        $dbh->commit();
        $_SESSION['message'] = SUCCESS['REGISTERED'];
        redirect( 'facilityDetail.php?facility_id=' . $facilityId );
      }
    } catch ( Exception $e ) {
      exceptionHandler( $e );
    }
  }
}

if ( ! empty( $_POST['delete'] ) ) {
  if ( empty( $errorMessages ) || ! empty( $dbFacilityData ) ) {
    debug( '海岸のデータを削除します' );
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();
      $sql              = 'delete from facility_images where facility_id = :facility_id';
      $data             = array(
              ':facility_id' => $facilityId,
      );
      $sth              = queryPost( $dbh, $sql, $data );
      $sql              = 'delete from facilities_stakeholders where facility_id = :facility_id';
      $sth2             = queryPost( $dbh, $sql, $data );
      $sql              = 'delete from facilities where facility_id = :facility_id and user_id = :user_id and is_deleted = false';
      $data[':user_id'] = $dbFacilityData['user_id'];
      $sth3             = queryPost( $dbh, $sql, $data );
      if ( ! empty( $sth ) && ! empty( $sth2 ) && ! empty( $sth3 ) ) {
        $dbh->commit();
        $_SESSION['message'] = SUCCESS['DELETED'];
        redirect( 'mypage.php' );
      }
    } catch ( Exception $e ) {
      exceptionHandler( $e );

      if ( ! empty( $dbh ) ) {
        $dbh->rollback();
      }
    }
  }
}

endPageDisplay();
?>
<?php
$pageTitle = ! empty( $dbFacilityData ) ? '海岸の情報の編集' : '海岸の登録';
require "head.php";
require "header.php";
?>

  <main class="l-main">
    <?php
    require "sidebar.php"; ?>
    <div class="l-main__my-page">
      <h1 class="c-main__title u-text-center"><?php
        echo $pageTitle; ?></h1>
      <p class="c-main__message --error"><?php
        getErrorMessage( 'common' ); ?></p>
      <form method="post" action="" enctype="multipart/form-data">

        <div class="c-input__container">
          <span class="c-status-label --orange">必須</span>
          <label for="facility_name" class="c-input__label">海岸の名称</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">（例）大洗海岸</p>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage( 'facility_name' ); ?>
          </p>
          <input type="text" name="facility_name" id="facility_name"
                 class="c-input__body js-valid-registered js-valid-required <?php
                 addErrorClass( 'facility_name' ); ?>" value="<?php
          echo sanitize( keepInputAndDatabase( 'facility_name', $dbFacilityData ) );
          ?>">
          <!--          <p class="c-input__counter">0/10<j/p>-->
        </div>


        <?php
        for ( $i = 0; $i < 3; $i ++ ): ?>
          <div class="c-input__container">
            <!--            <span class="c-status-label --orange">必須</span>-->
            <span class="c-input__label">写真<?php
              echo sanitize( $i + 1 ); ?></span>
            <!--        <p class="c-input__sub-label">コメント時に表示されます</p>-->
            <label class="c-image-upload__label --facility js-drag-area" for="facility_image<?php
            echo sanitize( $i ); ?>">
              ここに画像をドラッグ
              <input class="c-image-upload__body js-image-upload" type="file" name="facility_image[]"
                     id="facility_image<?php
                     echo sanitize( $i ); ?>"
                     accept=".jpg, .peg, .png">
              <input type="hidden" name="max_file_size" value="<?php
              echo 3 * MEGA_BYTES; ?>">
              <img class="c-image-upload__img js-image-preview" src="<?php
              if ( ! empty( $dbFacilityImagePaths[ $i ] ) ) {
                echo sanitize( $dbFacilityImagePaths[ $i ] );
              } ?>" style="<?php
              if ( ! empty( $dbFacilityImagePaths[ $i ] ) ) {
                echo 'display:block;';
              } ?>" alt="">
            </label>
          </div>
        <?php
        endfor; ?>


        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="prefecture_id" class="c-input__label">所在地</label>
          <p class="c-input__sub-label">都道府県</p>
          <!--          <p class="c-input__help-message">（例）https://www.oarai-info.jp/</p>-->
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="prefecture_id" id="" class="c-select__box--register">
              <option value="0" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'prefecture_id', $dbFacilityData ) == 0 )
                echo 'selected' ?>>未選択
              </option>
              <?php
              foreach ( $dbPrefectures as $key => $value ) : ?>
                <option value="<?php
                echo $value['prefecture_id']; ?>" class="c-select__option" <?php
                if ( keepInputAndDatabase(
                             'prefecture_id',
                             $dbFacilityData
                     ) == $value['prefecture_id'] )
                  echo 'selected' ?>><?php
                  echo $value['name']; ?></option>
              <?php
              endforeach; ?>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label --orange">必須</span>-->
          <label for="facility_address" class="c-input__label">所在地（市区町村以降）</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">（例）東茨城郡大洗町磯浜</p>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage( 'facility_address' ); ?>
          </p>
          <input type="text" name="facility_address" id="facility_address" class="c-input__body <?php
          addErrorClass( 'facility_address' ); ?>" value="<?php
          echo sanitize( keepInputAndDatabase( 'facility_address', $dbFacilityData ) );
          ?>">
          <!--          <p class="c-input__counter">0/10<j/p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label --orange">必須</span>-->
          <label for="url_of_facility_location_map" class="c-input__label">海岸の地図へのリンク</label>
          <p class="c-input__sub-label">GoogleMapやYahoo!地図のURLを入力してください</p>
          <p class="c-input__help-message">（例）
            <a href="https://www.google.co.jp/maps/place/%E5%A4%A7%E6%B4%97%E6%B5%B7%E5%B2%B8/@36.3108804,140.5450083,13z/data=!4m9!1m2!2m1!1z5aSn5rSX5rW35bK4!3m5!1s0x60223159743ed687:0x2bff399cd419c6eb!8m2!3d36.3191068!4d140.5922725!15sCgzlpKfmtJfmtbflsriSAQViZWFjaOABAA"
               target="_blank">
              https://www.google.co.jp/maps/place/%E5%A4%A7%E6%B4%97%E6%B5%B7%E5%B2%B8/@36.3108804,140.5450083,13z/data=!4m9!1m2!2m1!1z5aSn5rSX5rW35bK4!3m5!1s0x60223159743ed687:0x2bff399cd419c6eb!8m2!3d36.3191068!4d140.5922725!15sCgzlpKfmtJfmtbflsriSAQViZWFjaOABAA
            </a>
          </p>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage( 'url_of_facility_location_map' ); ?>
          </p>
          <input type="text" name="url_of_facility_location_map" id="url_of_facility_location_map"
                 class="c-input__body <?php
                 addErrorClass( 'facility_address' ); ?>" value="<?php
          echo sanitize( keepInputAndDatabase( 'url_of_facility_location_map', $dbFacilityData ) );
          ?>">
          <!--          <p class="c-input__counter">0/10<j/p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="shooting_fee" class="c-input__label">撮影料</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">（例）基本無料</p>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage( 'shooting_fee' ); ?>
          </p>
          <input type="text" name="shooting_fee" id="shooting_fee" class="c-input__body <?php
          addErrorClass( 'shooting_fee' ); ?>" value="<?php
          echo sanitize( keepInputAndDatabase( 'shooting_fee', $dbFacilityData ) );
          ?>">
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="url_of_facility_information_page" class="c-input__label">海岸の案内ページのURL</label>
          <p class="c-input__sub-label">
            アクセス、駐車場、トイレなどの情報が掲載されているホームページのURL
            自治体や観光協会のホームページを想定しています。
          </p>
          <p class="c-input__help-message">
            (例)https://www.ibarakiguide.jp/db-kanko/oaraikaigan.html
          </p>
          <p class="c-input__error-message"><?php
            echo getErrorMessage( 'url_of_facility_information_page' ); ?></p>
          <input type="text" name="url_of_facility_information_page" id="url_of_facility_information_page"
                 class="c-input__body <?php
                 addErrorClass( 'url_of_facility_information_page' ); ?>" value="<?php
          echo sanitize( keepInputAndDatabase( 'url_of_facility_information_page', $dbFacilityData ) );
          ?>">
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>


        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="is_need_consultation_of_shooting" class="c-input__label">撮影前の事前相談の要否</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <!--          <p class="c-input__help-message"></p>-->
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="is_need_consultation_of_shooting" id="" class="c-select__box--register">
              <option value="0" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_consultation_of_shooting', $dbFacilityData ) == 0 ) {
                echo 'selected';
              } ?>>未選択
              </option>
              <option value="1" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_consultation_of_shooting', $dbFacilityData ) == 1 ) {
                echo 'selected';
              } ?>>必要
              </option>
              <option value="2" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_consultation_of_shooting', $dbFacilityData ) == 2 ) {
                echo 'selected';
              } ?>>撮影申請先と同じ
              </option>
              <option value="3" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_consultation_of_shooting', $dbFacilityData ) == 3 ) {
                echo 'selected';
              } ?>>不要
              </option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <!--相談先ここから-->
        <div class="c-checkbox__container">
          <p for="organization" class="c-input__label">登撮影前の事前相談先</p>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message u-mb-8">
            撮影の相談先を先に作成する必要があります。
            <br>
            <a class="c-text__link"
               href="registrationOfApplicationDestination.php" target="_blank">相談先の登録はコチラ</a>
            <br>
            作成すると以下にチェックボックスが表示され選択できるようになります。
          </p>

          <?php
          if ( ! empty( $dbPriorConsultationsWithCategory ) ):
            ?>
            <?php
            $priorConsultationIds = ! empty(
            keepInputAndDatabase(
                    'prior_consultation',
                    $dbStakeholdersAssociatedWithCategoryIds
            )
            ) ? keepInputAndDatabase( 'prior_consultation', $dbStakeholdersAssociatedWithCategoryIds ) : array();
            foreach ( $dbPriorConsultationsWithCategory as $key => $value ): ?>
              <label for="prior_consultation_id<?php
              echo $value['stakeholder_id']; ?>" class="c-checkbox__label u-mr-24">
                <input type="checkbox" class="c-checkbox__body" name="prior_consultation[]"
                       id="prior_consultation_id<?php
                       echo $value['stakeholder_id']; ?>" value="<?php
                echo $value['stakeholder_id']; ?>" <?php
                if ( in_array( $value['stakeholder_id'], $priorConsultationIds ) ) {
                  echo 'checked';
                }
                ?>>
                <span class="c-checkbox__name"><?php
                  echo $value['organization']; ?></span>
                <p class="c-input__error-message">
                  <?php
                  echo getErrorMessage( 'prior_consultation' ); ?>
                </p>
              </label>
            <?php
            endforeach; ?>
          <?php
          else: ?>
            <p>事前相談先が登録されていません</p>
          <?php
          endif; ?>
        </div>

        <!--相談先ここまで-->
        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="is_need_application_of_shooting" class="c-input__label">撮影申請の要否</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <!--          <p class="c-input__help-message"></p>-->
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="is_need_application_of_shooting" id="" class="c-select__box--register">
              <option value="0" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_application_of_shooting', $dbFacilityData ) == 0 ) {
                echo 'selected';
              } ?>>未選択
              </option>
              <option value="1" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_application_of_shooting', $dbFacilityData ) == 1 ) {
                echo 'selected';
              } ?>>必要
              </option>
              <option value="2" class="c-select__option" <?php
              if ( keepInputAndDatabase( 'is_need_application_of_shooting', $dbFacilityData ) == 2 ) {
                echo 'selected';
              } ?>>不要
              </option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>
        <div class="c-checkbox__container">
          <p for="organization" class="c-input__label">撮影の申請先</p>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message u-mb-8">
            撮影の申請先の情報を先を作成する必要があります。
            <br>
            <a class="c-text__link"
               href="registrationOfApplicationDestination.php" target="_blank">申請先の登録はコチラ</a>
            <br>
            作成すると以下のチェックボックス表示され選択できるようになります。
          </p>

          <?php
          if ( ! empty( $dbApplicationDestinationsWithCategory ) ):
            ?>
            <?php
            $applicationDestinationIds = ! empty(
            keepInputAndDatabase(
                    'application_destination',
                    $dbStakeholdersAssociatedWithCategoryIds
            )
            ) ? keepInputAndDatabase( 'application_destination', $dbStakeholdersAssociatedWithCategoryIds ) : array();
            foreach ( $dbApplicationDestinationsWithCategory as $key => $value ): ?>
              <label for="application_destination_id<?php
              echo $value['stakeholder_id']; ?>" class="c-checkbox__label u-mr-24">
                <input type="checkbox" class="c-checkbox__body" name="application_destination[]"
                       id="application_destination_id<?php
                       echo $value['stakeholder_id']; ?>" value="<?php
                echo $value['stakeholder_id']; ?>" <?php
                if ( in_array( $value['stakeholder_id'], $applicationDestinationIds ) ) {
                  echo 'checked';
                }
                ?>>
                <span class="c-checkbox__name"><?php
                  echo $value['organization']; ?></span>
                <p class="c-input__error-message">
                  <?php
                  echo getErrorMessage( 'application_destination' ); ?>
                </p>
              </label>
            <?php
            endforeach; ?>
          <?php
          else: ?>
            <p>申請先が登録されていません</p>
          <?php
          endif; ?>
        </div>
        <!--申請先ここまで-->

        <button class="c-button --full-width c-button__primary u-mb-24 js-disabled-submit" name="published"
                value="published"
                type="submit">
          <?php
          if ( ! empty( $dbFacilityData ) ) {
            echo '変更して公開する';
          } else {
            echo '登録して公開する';
          }
          ?>
        </button>
        <button class="c-button --full-width c-button__secondary u-mb-24 js-disabled-submit" name="published"
                value="unpublished"
                type="submit">
          下書きに保存する
        </button>
        <button class="c-button --full-width c-button__text js-disabled-submit js-show-modal" name="delete"
                value="delete" type="submit">
          削除する
        </button>
      </form>
    </div>
  </main>
  <!--モーダル-->
  <div class="c-modal__cover js-modal-cover">
    <div class="c-modal__wrapper js-modal-target">
      <h2 class="c-modal__title">この海岸を削除しますか？</h2>
      <p class="c-modal__body">削除すると復活させることはできません。</p>
      <form method="post">
        <button class="c-button c-button__warning js-hide-modal">キャンセル</button>
        <button name="delete" value="delete" type="submit" class="c-button c-button__text --warning u-ml-24">削除する
        </button>
      </form>
    </div>
  </div>
<?php
require "footer.php"; ?>