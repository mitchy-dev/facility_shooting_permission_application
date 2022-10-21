<?php

require('functions.php');
startPageDisplay();
require "auth.php";

//$dbFacilityData = fetchFacilityData($_SESSION['user_id']);
$dbUserData = array();
$dbPrefectures = fetchPrefectures();

if (!empty($_POST)) {
  if (empty($errorMessages)) {
    debug('');
    try {
      $dbh = dbConnect();
      $sql = '';
      $data = array();
      if (!empty(queryPost($dbh, $sql, $data))) {
        redirect('profileEdit.php');
      }
    } catch (Exception $e) {
      exceptionHandler($e);
    }
  }
}


endPageDisplay();
?>
<?php
$pageTitle = '海岸の登録';
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
          echo keepInputAndDatabase('facility_name', $dbUserData);
          ?>">
          <!--          <p class="c-input__counter">0/10<j/p>-->
        </div>

        <div class="c-input__container">
          <span class="c-status-label --orange">必須</span>
          <span class="c-input__label">写真(メイン）</span>
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
              if (keepInputAndDatabase('prefecture_id', $dbUserData) == 0) echo 'selected' ?>>未選択
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
          echo keepInputAndDatabase('facility_address', $dbUserData);
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
          echo keepInputAndDatabase('shooting_fee', $dbUserData);
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
          echo keepInputAndDatabase('url_of_facility_information_page', $dbUserData);
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
            相談先を先に作成する必要があります。<br>
            作成すると以下のセレクトボックスから選択できるようになります。
          </p>
          <p class="c-input__help-message">
            (例)湘南藤沢フィルムコミッション
          </p>
          <!--          <p class="c-input__error-message">error</p>-->
          <div class="c-select__wrap--register">
            <select name="region" id="" class="c-select__box--register">
              <option value="0" class="c-select__option">事前相談先が登録されていません</option>
              <option value="2" class="c-select__option">撮影申請先と同じ</option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="" class="c-input__label">撮影申請先</label>
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
              <option value="0" class="c-select__option">撮影申請不要</option>
              <option value="2" class="c-select__option">神奈川県横須賀土木事務所</option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <button class="c-button --full-width c-button__primary u-mb-24" type="submit">
          登録する
        </button>
        <button class="c-button --full-width c-button__secondary u-mb-24" type="submit">
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