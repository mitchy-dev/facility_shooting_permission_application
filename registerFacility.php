<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$_GET['facility_id'] = 80;
if (!empty($_GET['facility_id']) && !is_numeric($_GET['facility_id'])) {
  debug('取得したGETパラメータが数値でないためリダイレクトします');
  redirect('index.php');
}

$facilityId = !empty($_GET['facility_id']) ? $_GET['facility_id'] : '';
$dbFacilityData = !empty($facilityId) ? fetchFacility($_SESSION['user_id'], $facilityId) : array();
if (!empty($facilityId) && empty($dbFacilityData)) {
  debug('不正なアクセスのためリダイレクトします');
  redirect('index.php');
}


//DBから写真のデータを取得する必要がある
$dbFacilityImagePaths = fetchFacilityImagePaths($facilityId);
debug('$dbFacilityImagePaths:' . print_r($dbFacilityImagePaths, true));
$dbPrefectures = fetchPrefectures();
$dbStakeholdersWithCategory = fetchStakeholdersWithCategories($_SESSION['user_id']);
debug('取得した関係者のデータ：' . print_r($dbStakeholdersWithCategory, true));
$dbStakeholdersAssociatedWithCategory = fetchStakeholdersAssociatedWithTheFacility($facilityId);
$dbStakeholdersAssociatedWithCategoryIds = array();

if (!empty($dbStakeholdersAssociatedWithCategory)) {
  foreach ($dbStakeholdersAssociatedWithCategory as $key => $value) {
    if ($value['stakeholder_category_id'] == 1) {
      $dbPriorConsultaitions[] = $value;
    } elseif ($value['stakeholder_category'] == 2) {
      $dbApplicationDestinations[] = $value;
    }

    if (!empty($dbPriorConsultaitions)) {
      $dbStakeholdersAssociatedWithCategoryIds['prior_consultation'] = array_column(
              $dbPriorConsultaitions,
              'stakeholder_id'
      );
    }

    if (!empty($dbApplicationDestinations)) {
      $dbStakeholdersAssociatedWithCategoryIds['application_destination'] = array_column(
              $dbApplicationDestinations,
              'stakeholder_id'
      );
    }
  }
}
//
if (!empty($_POST)) {
  debug('POST:' . print_r($_POST, true));
//  debug('FILES:' . print_r($_FILES, true));
  $facilityName = $_POST['facility_name'];
  $facilityImages = reArrayFiles($_FILES['facility_image']);
  debug('facilityImages:' . print_r($facilityImages, true));
  if (!empty($facilityImages)) {
    foreach ($facilityImages as $key => $value) {
      $facilityImagePath[] = keepFilePath(
              $value,
              'common',
              !empty($dbFacilityImagePaths[$key]) ? $dbFacilityImagePaths[$key] : ''
      );
    }
  }
  $thumbnailPath = !empty($facilityImagePath) ? $facilityImagePath[0] : '';
  $prefectureId = $_POST['prefecture_id'];
  $facilityAddress = $_POST['facility_address'];
  $shootingFee = $_POST['shooting_fee'];
  $urlOfFacilityInformationPage = $_POST['url_of_facility_information_page'];
  $titleOfFacilityInformationPage = 'test';
  $published = !empty($_POST['published']) ? 1 : 0;

  $priorConsultation = $_POST['prior_consultation'];


  if (empty($errorMessages)) {
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();
      if (!empty($dbFacilityData)) {
        debug('海岸の情報を更新します');
      } else {
        debug('海岸の情報を登録します');
        $sql = ' insert into facilities(user_id, facility_name, thumbnail_path, prefecture_id, facility_address, shooting_fee, url_of_facility_information_page, title_of_facility_information_page, published, created_at) values (:user_id, :facility_name, :thumbnail_path, :prefecture_id, :facility_address, :shooting_fee, :url_of_facility_information_page, :title_of_facility_information_page, :published, :created_at)';
        $data = array(
                ':user_id' => $_SESSION['user_id'],
                ':facility_name' => $facilityName,
                ':thumbnail_path' => $thumbnailPath,
                ':prefecture_id' => $prefectureId,
                ':facility_address' => $facilityAddress,
                ':shooting_fee' => $shootingFee,
                ':url_of_facility_information_page' => $urlOfFacilityInformationPage,
                ':title_of_facility_information_page' => $titleOfFacilityInformationPage,
                ':published' => $published,
                ':created_at' => date('Y-m-d H:i:s'),
        );
        if (empty(queryPost($dbh, $sql, $data))) {
          throw new Exception(ERROR['EXCEPTION']);
        }
        $facilityId = $dbh->lastInsertId();
        if (!empty($facilityImagePath)) {
          foreach ($facilityImagePath as $key => $value) {
            $sql = 'insert into facility_images(facility_id, image_path, created_at) values (:facility_id, :image_path, :created_at)';
            $data = array(
                    ':facility_id' => $facilityId,
                    ':image_path' => $value,
                    ':created_at' => date('Y-m-d H:i:s'),
            );
            if (empty(queryPost($dbh, $sql, $data))) {
              throw new Exception(ERROR['EXCEPTION']);
            }
          }
        }
        if (!empty($priorConsultation)) {
          foreach ($priorConsultation as $key => $value) {
            $sql = 'insert into facilities_stakeholders(facility_id, stakeholder_id, stakeholder_category_id, created_at) values (:facility_id, :stakeholder_id, :stakeholder_category_id, :created_at)';
            $data = array(
                    ':facility_id' => $facilityId,
                    ':stakeholder_id' => $value,
                    ':stakeholder_category_id' => 1,
                    ':created_at' => date('Y-m-d H:i:s')
            );
            if (empty(queryPost($dbh, $sql, $data))) {
              throw new Exception(ERROR['EXCEPTION']);
            }
          }
        }


//        if (!empty($stakeholderCategory)) {
//          debug('関係者のカテゴリが入力されています');
//          foreach ($stakeholderCategory as $key => $value) {
//            $sql = 'insert into stakeholder_categorization( stakeholder_id, stakeholder_category_id, created_at ) values(:stakeholder_id, :stakeholder_category_id, :created_at)';
//            $data = array(
//                    ':stakeholder_id' => $stakeholderId,
//                    ':stakeholder_category_id' => $value,
//                    ':created_at' => date('Y - m - d H:i:s'),
//            );
//            if (empty(queryPost($dbh, $sql, $data))) {
//              throw new Exception(ERROR['EXCEPTION']);
//            }
//          }
//        }
        $dbh->commit();
        $_SESSION['message'] = SUCCESS['REGISTERED'];
        redirect('listings.html');
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}


endPageDisplay();
?>
<?php
$pageTitle = !empty($dbFacilityData) ? '海岸の情報の編集' : '海岸の登録';
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
        getErrorMessage('common'); ?></p>
      <form method="post" action="" enctype="multipart/form-data">

        <div class="c-input__container">
          <span class="c-status-label --orange">必須</span>
          <label for="facility_name" class="c-input__label">海岸の名称</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">（例）大洗海岸</p>
          <p class="c-input__error-message">
            <?php
            echo getErrorMessage('facility_name'); ?>
          </p>
          <input type="text" name="facility_name" id="facility_name" class="c-input__body <?php
          addErrorClass('facility_name'); ?>" value="<?php
          echo keepInputAndDatabase('facility_name', $dbFacilityData);
          ?>">
          <!--          <p class="c-input__counter">0/10<j/p>-->
        </div>


        <?php
        for ($i = 0; $i < 3; $i++): ?>
          <div class="c-input__container">
            <!--            <span class="c-status-label --orange">必須</span>-->
            <span class="c-input__label">写真<?php
              echo sanitize($i + 1); ?></span>
            <!--        <p class="c-input__sub-label">コメント時に表示されます</p>-->
            <label class="c-image-upload__label --facility js-drag-area" for="facility_image<?php
            echo sanitize($i); ?>">
              ここに画像をドラッグ
              <input class="c-image-upload__body js-image-upload" type="file" name="facility_image[]"
                     id="facility_image<?php
                     echo sanitize($i); ?>"
                     accept=".jpg, .peg, .png">
              <input type="hidden" name="max_file_size" value="<?php
              echo 2 * MEGA_BYTES; ?>">
              <img class="c-image-upload__img js-image-preview" src="<?php
              if (!empty($dbFacilityImagePaths[$i])) {
                echo $dbFacilityImagePaths[$i];
              } ?>" style="<?php
              if (!empty($dbFacilityImagePaths[$i])) {
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
              if (keepInputAndDatabase('prefecture_id', $dbFacilityData) == 0) echo 'selected' ?>>未選択
              </option>
              <?php
              foreach ($dbPrefectures as $key => $value) : ?>
                <option value="<?php
                echo $value['prefecture_id']; ?>" class="c-select__option"><?php
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
            echo getErrorMessage('facility_address'); ?>
          </p>
          <input type="text" name="facility_address" id="facility_address" class="c-input__body <?php
          addErrorClass('facility_address'); ?>" value="<?php
          echo keepInputAndDatabase('facility_address', $dbFacilityData);
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
            echo getErrorMessage('shooting_fee'); ?>
          </p>
          <input type="text" name="shooting_fee" id="shooting_fee" class="c-input__body <?php
          addErrorClass('shooting_fee'); ?>" value="<?php
          echo keepInputAndDatabase('shooting_fee', $dbFacilityData);
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
            echo getErrorMessage('url_of_facility_information_page'); ?></p>
          <input type="text" name="url_of_facility_information_page" id="url_of_facility_information_page"
                 class="c-input__body <?php
                 addErrorClass('url_of_facility_information_page'); ?>" value="<?php
          echo keepInputAndDatabase('url_of_facility_information_page', $dbFacilityData);
          ?>">
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>


        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="" class="c-input__label">撮影前の事前相談の要否</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <!--          <p class="c-input__help-message"></p>-->
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="region" id="" class="c-select__box--register">
              <option value="1" class="c-select__option">必要</option>
              <option value="2" class="c-select__option">撮影申請先と同じ</option>
              <option value="3" class="c-select__option">不要</option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>
        <div class="c-checkbox__container">
          <p for="organization" class="c-input__label">登撮影前の事前相談先</p>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message u-mb-8">
            撮影の相談先を先に作成する必要があります。<br>
            作成すると以下のチェックボックスから選択できるようになります。
          </p>

          <?php
          if (!empty($dbStakeholdersWithCategory)): ?>
            <?php
            $priorConsultationIds = !empty(
            keepInputAndDatabase(
                    'prior_consultation',
                    $dbStakeholdersAssociatedWithCategoryIds
            )
            ) ? keepInputAndDatabase('prior_consultation', $dbStakeholdersAssociatedWithCategoryIds) : array();
            foreach ($dbStakeholdersWithCategory as $key => $value): ?>
              <label for="stakeholder_id<?php
              echo $value['stakeholder_id']; ?>" class="c-checkbox__label u-mr-24">
                <input type="checkbox" class="c-checkbox__body" name="prior_consultation[]"
                       id="stakeholder_id<?php
                       echo $value['stakeholder_id']; ?>" value="<?php
                echo $value['stakeholder_id']; ?>" <?php
                if (in_array($value['stakeholder_id'], $priorConsultationIds)) {
                  echo 'checked';
                }
                ?>>
                <span class="c-checkbox__name"><?php
                  echo $value['organization']; ?></span>
                <p class="c-input__error-message">
                  <?php
                  echo getErrorMessage('prior_consultation'); ?>
                </p>
              </label>
            <?php
            endforeach; ?>
          <?php
          else: ?>
            <label for="stakeholder_id" class="c-checkbox__label u-mr-24">
              <input type="checkbox" class="c-checkbox__body" name="prior_consultation[]"
                     id="stakeholder_id" value="" disabled>
              <span class="c-checkbox__name">事前相談先が登録されていません。<a class="c-text__link"
                                                                href="registrationOfApplicationDestination.php">相談先の登録はコチラ</a></span>
              <p class="c-input__error-message">
                <?php
                echo getErrorMessage('prior_consultation'); ?>
              </p>
            </label>
          <?php
          endif; ?>
          </select>


        </div>


        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="" class="c-input__label">撮影の申請先</label>
          <p class="c-input__sub-label">
            申請先を先に作成する必要があります。<br>
            作成すると以下のセレクトボックスから選択できるようになります。
          </p>
          <p class="c-input__help-message">
            (例)神奈川県横須賀土木事務所
          </p>
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="region" id="" class="c-select__box--register">
              <?php
              if (!empty($dbStakeholdersWithCategory)): ?>
                <option value="0" class="c-select__option">撮影の申請不要</option>
                <?php
                foreach ($dbStakeholdersWithCategory as $key => $value): ?>
                  <?php
                  if (!empty($value['categories'])): ?>
                    <?php
                    $categoryIds = array_column($value['categories'], 'category_id');
                    if (in_array(2, $categoryIds)): ?>
                      <option value="<?php
                      echo sanitize($value['stakeholder_id']); ?>" class="c-select__option"><?php
                        echo sanitize($value['organization']); ?></option>
                    <?php
                    endif; ?>

                  <?php
                  endif; ?>
                <?php
                endforeach; ?>
              <?php
              else: ?>
                <p class="u-text-center">撮影申請先が登録されていません</p>
              <?php
              endif; ?>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>


        <button class="c-button --full-width c-button__primary u-mb-24" name="published" value="published"
                type="submit">
          <?php
          if (!empty($dbFacilityData)) {
            echo '変更する';
          } else {
            echo '登録する';
          }
          ?>
        </button>
        <button class="c-button --full-width c-button__secondary u-mb-24" name="published" value="" type="submit">
          下書きに保存する
        </button>
        <button class="c-button --full-width c-button__text" type="submit">
          削除する
        </button>
      </form>
    </div>
  </main>

<?php
require "footer.php"; ?>