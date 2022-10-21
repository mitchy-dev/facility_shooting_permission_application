<?php

require('functions.php');
startPageDisplay();
require "auth.php";

$dbUserData = fetchUserData($_SESSION['user_id']);

if (!empty($_POST)) {
  $organization = $_POST['organization'];
  $representativeTitle = $_POST['representative_title'];
  $representativesName = $_POST['representatives_name'];
  $department = $_POST['department'];
  $personInCharge = $_POST['person_in_charge'];
  $phoneNumber = $_POST['phone_number'];
  $comment = $_POST['comment'];
  $avatarPath = keepFilePath('avatar_path', 'avatar_path', $dbUserData);


  validMaxLength($organization, 'organization');
  validMaxLength($representativeTitle, 'representativeTitle');
  validMaxLength($representativesName, 'representativesName');
  validMaxLength($department, 'department');
  validMaxLength($personInCharge, 'personInCharge');
  validMaxLength($phoneNumber, 'phoneNumber');
  validMaxLength($comment, 'comment');

  if (empty($errorMessages)) {
    debug('プロフィールを更新します');
    try {
      $dbh = dbConnect();
      $sql = 'update users set
                 organization = :organization,
                 representative_title = :representative_title,
                 representatives_name = :representatives_name,
                 department = :department,
                 person_in_charge = :person_in_charge,
                 phone_number = :phone_number,
                 comment = :comment,
                 avatar_path = :avatar_path
              where
                    user_id = :user_id and
                    is_deleted = false';
      $data = array(
              ':organization' => $organization,
              ':representative_title' => $representativeTitle,
              ':representatives_name' => $representativesName,
              ':department' => $department,
              ':person_in_charge' => $personInCharge,
              ':phone_number' => $phoneNumber,
              ':comment' => $comment,
              ':avatar_path' => $avatarPath,
              ':user_id' => $_SESSION['user_id'],

      );
      if (!empty(queryPost($dbh, $sql, $data))) {
        $_SESSION['message'] = SUCCESS['PROFILE_EDIT'];

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
      <form method="post" action="">
        <div class="c-input__container">
          <span class="c-status-label --orange">必須</span>
          <label for="" class="c-input__label">海岸の名称</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">（例）大洗海岸</p>
          <!--          <p class="c-input__error-message">error</p>-->
          <input type="text" class="c-input__body" placeholder="">
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
          <label for="" class="c-input__label">所在地</label>
          <p class="c-input__sub-label">都道府県</p>
          <!--          <p class="c-input__help-message">（例）https://www.oarai-info.jp/</p>-->
          <!--          <p class="c-input__error-message">error</p>-->
          <!--          <input type="text" class="c-input__body" placeholder="">-->
          <div class="c-select__wrap--register">
            <select name="region" id="" class="c-select__box--register">
              <option value="0" class="c-select__option">全国</option>
              <option value="1" class="c-select__option">北海道</option>
              <option value="2" class="c-select__option">東北</option>
              <option value="2" class="c-select__option">神奈川県</option>
            </select>
          </div>
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="" class="c-input__label">撮影料</label>
          <!--          <p class="c-input__sub-label">sub-label</p>-->
          <p class="c-input__help-message">（例）基本無料</p>
          <!--          <p class="c-input__error-message">error</p>-->
          <input type="text" class="c-input__body" placeholder="">
          <!--          <p class="c-input__counter">0/10</p>-->
        </div>

        <div class="c-input__container">
          <!--          <span class="c-status-label">ラベル</span>-->
          <label for="" class="c-input__label">海岸の案内ページのURL</label>
          <p class="c-input__sub-label">
            アクセス、駐車場、トイレなどの情報が掲載されているホームページのURL
            自治体や観光協会のホームページを想定しています。
          </p>
          <p class="c-input__help-message">
            (例)https://www.ibarakiguide.jp/db-kanko/oaraikaigan.html
          </p>
          <!--          <p class="c-input__error-message">error</p>-->
          <input type="text" class="c-input__body" placeholder="">
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

        <button class="c-button --full-width c-button__primary u-mb-24" type="button">
          登録する
        </button>
        <button class="c-button --full-width c-button__secondary u-mb-24" type="button">
          下書きに保存する
        </button>
        <button class="c-button --full-width c-button__text" type="button">
          削除する
        </button>
      </form>
    </div>
  </main>

<?php
require "footer.php"; ?>