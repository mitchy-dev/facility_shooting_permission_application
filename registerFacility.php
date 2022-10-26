<?php

require('functions.php');
startPageDisplay();
require "auth.php";

//$_GET['facility_id'] = 1;
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


$dbPrefectures = fetchPrefectures();
$dbStakeholdersWithCategory = fetchStakeholdersWithCategories($_SESSION['user_id']);
debug('取得した関係者のデータ：' . print_r($dbStakeholdersWithCategory, true));

if (!empty($_POST)) {
  debug('POST:' . print_r($_POST, true));
  $facilityName = $_POST['facility_name'];
  $thumbnailPath = keepFilePath('thumbnail_path', 'thumbnail_path', $dbFacilityData);
  $prefectureId = $_POST['prefecture_id'];
  $facilityAddress = $_POST['facility_address'];
  $shootingFee = $_POST['shooting_fee'];
  $urlOfFacilityInformationPage = $_POST['url_of_facility_information_page'];
  $titleOfFacilityInformationPage = 'test';
  $published = !empty($_POST['published']) ? 1 : 0;


  if (empty($errorMessages)) {
    debug('');
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
        $stakeholderId = $dbh->lastInsertId();

//        if (!empty($stakeholderCategory)) {
//          debug('関係者のカテゴリが入力されています');
//          foreach ($stakeholderCategory as $key => $value) {
//            $sql = 'insert into stakeholder_categorization(stakeholder_id, stakeholder_category_id, created_at) values (:stakeholder_id, :stakeholder_category_id, :created_at)';
//            $data = array(
//                    ':stakeholder_id' => $stakeholderId,
//                    ':stakeholder_category_id' => $value,
//                    ':created_at' => date('Y-m-d H:i:s'),
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

       
        <div class="c-input__container">
          <span class="c-status-label --orange">必須</span>
          <span class="c-input__label">写真(メイン）</span>
          <!--        <p class="c-input__sub-label">コメント時に表示されます</p>-->
          <!--  <p class="c-input__help-message">help message</p>-->
          <p class="c-input__error-message"><?php
            echo getErrorMessage('thumbnail_path'); ?></p>
          <label class="c-image-upload__label --facility js-drag-area" for="thumbnail_path">
            ここに画像をドラッグ
            <input class="c-image-upload__body js-image-upload" type="file" name="thumbnail_path" id="thumbnail_path"
                   accept=".jpg, .peg, .png">
            <input type="hidden" name="max_file_size" value="<?php
            echo 2 * MEGA_BYTES; ?>">
            <img class="c-image-upload__img js-image-preview" src="<?php
            if (!empty($dbFacilityData['thumbnail_path'])) {
              echo $dbFacilityData['thumbnail_path'];
            } ?>" style="<?php
            if (!empty($dbFacilityData['avatar_path'])) {
              echo 'display:block;';
            } ?>" alt="">
          </label>
        </div>


        <div class="c-input__container">
          <!--          <span class="c-status-label &#45;&#45;orange">必須</span>-->
          <span class="c-input__label">写真</span>
          <!--  <p class="c-input__sub-label">sub-label</p>-->
          <!--  <p class="c-input__help-message">help message</p>-->
          <!--  <p class="c-input__error-message">error</p>-->
          <label class="c-image-upload__label --facility js-drag-area" for="image-uploads">
            ここに画像をドラッグ
            <input class="c-image-upload__body js-drag-area" type="file" class="" name="image-uploads"
                   id="image-uploads"
                   accept=".jpg, .peg, .png">
            <img class="c-image-upload__img" src="img/sample.jpg" alt="">
          </label>
        </div>
        <div class="c-input__container">
          <!--          <span class="c-status-label &#45;&#45;orange">必須</span>-->
          <span class="c-input__label">写真</span>
          <!--  <p class="c-input__sub-label">sub-label</p>-->
          <!--  <p class="c-input__help-message">help message</p>-->
          <!--  <p class="c-input__error-message">error</p>-->
          <label class="c-image-upload__label --facility js-drag-area" for="image-uploads">
            ここに画像をドラッグ
            <input class="c-image-upload__body js-drag-area" type="file" class="" name="image-uploads"
                   id="image-uploads"
                   accept=".jpg, .peg, .png">
            <img class="c-image-upload__img" src="img/sample.jpg" alt="">
          </label>
        </div>

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
              <option value="0" class="c-select__option">必要</option>
              <option value="2" class="c-select__option">不要</option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="" class="c-input__label">撮影前の事前相談先</label>
          <p class="c-input__sub-label">
            撮影の相談先を先に作成する必要があります。<br>
            作成すると以下のセレクトボックスから選択できるようになります。
          </p>
          <p class="c-input__help-message">
            (例)湘南藤沢フィルムコミッション
          </p>
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="region" id="" class="c-select__box--register">
              <?php
              if (!empty($dbStakeholdersWithCategory)): ?>
                <option value="" class="c-select__option">撮影の相談先はない</option>
                <option value="0" class="c-select__option">撮影申請先と同じ</option>
                <?php
                foreach ($dbStakeholdersWithCategory as $key => $value): ?>
                  <?php
                  if (!empty($value['categories'])): ?>
                    <?php
                    $categoryIds = array_column($value['categories'], 'category_id');
                    debug('各関係者に紐付いているカテゴリのID:' . print_r($categoryIds, true));
                    if (in_array(1, $categoryIds)): ?>
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
                <option value="" class="c-select__option">事前相談先が登録されていません</option>
              <?php
              endif; ?>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
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
                <option value="" class="c-select__option">撮影申請先が登録されていません</option>
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