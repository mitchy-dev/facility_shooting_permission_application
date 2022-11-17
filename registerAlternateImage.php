<?php

require('functions.php');
startPageDisplay();
require "auth.php";


if ($_SESSION['user_id'] != 1) {
  debug('アクセス権限がないためリダイレクトします');
  redirect('index.php');
}

if (!empty($_POST)) {
  debug('POST:' . print_r($_POST, true));
//  debug('FILES:' . print_r($_FILES, true));
  $facilityImages = reArrayFiles($_FILES['facility_image']);
//  debug('facilityImages:' . print_r($facilityImages, true));
  if (!empty($facilityImages)) {
    foreach ($facilityImages as $key => $value) {
      $facilityImagesPath[] = uploadImage(
              $value,
              'common',
              1440,
              1028,
              90,
              'alternateFacilityImages'
      );
    }
    unset($key, $value);
  }

  $thumbnails[] = !empty($facilityImages) ? array_values($facilityImages)[0] : '';
  $thumbnails[] = !empty($facilityImages[1]) ? array_values($facilityImages)[1] : '';
  $thumbnails[] = !empty($facilityImages[2]) ? array_values($facilityImages)[2] : '';
  if (!empty($thumbnails)) {
    foreach ($thumbnails as $key2 => $value2) {
      $thumbnailsPath[] = uploadImage(
              $value2,
              'common',
              308,
              219,
              90,
              'alternateFacilityThumbnails'
      );
    }
    unset($key2, $value2);
  }
  $_SESSION['message'] = SUCCESS['REGISTERED'];
  redirect('registerAlternateImage.php');
}

endPageDisplay();
?>
<?php
$pageTitle = '代替画像の登録';
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
              echo 3 * MEGA_BYTES; ?>">
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

        <button class="c-button --full-width c-button__primary u-mb-24" name="published" value="published"
                type="submit">
          アップロードする
        </button>
      </form>
    </div>
  </main>

<?php
require "footer.php"; ?>