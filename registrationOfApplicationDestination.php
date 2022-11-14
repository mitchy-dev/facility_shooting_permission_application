<?php

require('functions.php');
startPageDisplay();
require "auth.php";

//$_GET['stakeholder_id'] = 78;
if (!empty($_GET['stakeholder_id']) && !is_numeric($_GET['stakeholder_id'])) {
  debug('取得したGETパラメータが数値でないためリダイレクトします');
  redirect('index.php');
}

$stakeholderId = !empty($_GET['stakeholder_id']) ? $_GET['stakeholder_id'] : '';
$dbStakeholderData = !empty($stakeholderId) ? fetchStakeholder($_SESSION['user_id'], $stakeholderId) : array();
if (!empty($stakeholderId) && empty($dbStakeholderData)) {
  debug('該当するデータがないためリダイレクトします');
  redirect('index.php');
}

$stakeholderCategories = fetchStakeholderCategories();
//var_dump($stakeholderCategories);
$stakeholderCategorizations = !empty($stakeholderId) ? fetchStakeholderCategorizations($stakeholderId) : array();
var_dump($stakeholderCategorizations);

if (!empty($_POST)) {
  debug('POST:' . print_r($_POST, true));
  $stakeholderCategory = !empty($_POST['stakeholder_category']) ? $_POST['stakeholder_category'] : array();
  $organization = !empty($_POST['organization']) ? $_POST['organization'] : '';
  $department = !empty($_POST['department']) ? $_POST['department'] : '';
  $avatarPath = keepFilePath($_FILES['avatar_path'], 'avatar_path', $dbStakeholderData['avatar_path']);
  $urlOfShootingApplicationGuide = !empty($_POST['url_of_shooting_application_guide']) ? $_POST['url_of_shooting_application_guide'] : '';
  $titleOfShootingApplicationGuide = !empty($_POST['url_of_shooting_application_guide']) ? fetchTitleFromURL(
          $_POST['url_of_shooting_application_guide']
  ) : '';
  $applicationDeadline = !empty($_POST['application_deadline']) ? $_POST['application_deadline'] : '';
  $phoneNumber = !empty($_POST['phone_number']) ? $_POST['phone_number'] : '';
  $email = !empty($_POST['email']) ? $_POST['email'] : '';
  $urlOfContactForm = !empty($_POST['url_of_contact_form']) ? $_POST['url_of_contact_form'] : '';
  $urlOfApplicationFormat = !empty($_POST['url_of_application_format']) ? $_POST['url_of_application_format'] : '';
  $titleOfApplicationFormat = !empty($_POST['title_of_application_format']) ? $_POST['title_of_application_format'] : '';

  validEmpty($organization, 'organization');

  if (empty($errorMessages)) {
    try {
      $dbh = dbConnect();
      $dbh->beginTransaction();
      if (!empty($stakeholderId)) {
        debug('事前相談先・申請先の情報を更新します');
        $sql = 'update stakeholders set
                    organization = :organization,
                    department = :department,
                    avatar_path = :avatar_path,
                    url_of_shooting_application_guide = :url_of_shooting_application_guide,
                    title_of_shooting_application_guide = :title_of_shooting_application_guide,
                    application_deadline = :application_deadline,
                    phone_number = :phone_number,
                    email = :email,
                    url_of_contact_form = :url_of_contact_form,
                    url_of_application_format = :url_of_application_format,
                    title_of_application_format = :title_of_application_format
                where 
                    user_id = :user_id and stakeholder_id = :stakeholder_id and is_deleted = false';
        $data = array(
                ':organization' => $organization,
                ':department' => $department,
                ':avatar_path' => $avatarPath,
                ':url_of_shooting_application_guide' => $urlOfShootingApplicationGuide,
                ':title_of_shooting_application_guide' => $titleOfShootingApplicationGuide,
                ':application_deadline' => $applicationDeadline,
                ':phone_number' => $phoneNumber,
                ':email' => $email,
                ':url_of_contact_form' => $urlOfContactForm,
                ':url_of_application_format' => $urlOfApplicationFormat,
                ':title_of_application_format' => $titleOfApplicationFormat,
                ':user_id' => $_SESSION['user_id'],
                ':stakeholder_id' => $stakeholderId,
        );
        if (empty(queryPost($dbh, $sql, $data))) {
          throw new Exception(ERROR['EXCEPTION']);
        }
        if ($stakeholderCategory !== $stakeholderCategorizations) {
//          現在のレコードを削除
          $sql = 'delete from stakeholder_categorization where stakeholder_id = :stakeholder_id';
          $data = array(
                  ':stakeholder_id' => $stakeholderId,
          );
          if (empty(queryPost($dbh, $sql, $data))) {
            throw new Exception(ERROR['EXCEPTION']);
          }
//          レコードの追加
          if (!empty($stakeholderCategory)) {
            debug('関係者のカテゴリが入力されています');
            foreach ($stakeholderCategory as $key => $value) {
              $sql = 'insert into stakeholder_categorization(stakeholder_id, stakeholder_category_id, created_at) values (:stakeholder_id, :stakeholder_category_id, :created_at)';
              $data = array(
                      ':stakeholder_id' => $stakeholderId,
                      ':stakeholder_category_id' => $value,
                      ':created_at' => date('Y-m-d H:i:s'),
              );
              if (empty(queryPost($dbh, $sql, $data))) {
                throw new Exception(ERROR['EXCEPTION']);
              }
            }
          }
        }
        $dbh->commit();
        $_SESSION['message'] = SUCCESS['UPDATE_STAKEHOLDER'];
        redirect('registeredContactAndApplication.php');
      } else {
        debug('事前相談先・申請先の情報を登録します');
        $sql = 'insert into stakeholders (
                    user_id, organization, department, avatar_path, url_of_shooting_application_guide,
                    title_of_shooting_application_guide, application_deadline, phone_number, email,
                    url_of_contact_form, url_of_application_format, title_of_application_format, created_at
                    ) values (
                    :user_id, :organization, :department, :avatar_path, :url_of_shooting_application_guide,
                    :title_of_shooting_application_guide, :application_deadline, :phone_number, :email,
                    :url_of_contact_form, :url_of_application_format, :title_of_application_format, :created_at
                    )';
        $data = array(
                ':user_id' => $_SESSION['user_id'],
                ':organization' => $organization,
                ':department' => $department,
                ':avatar_path' => $avatarPath,
                ':url_of_shooting_application_guide' => $urlOfShootingApplicationGuide,
                ':title_of_shooting_application_guide' => $titleOfShootingApplicationGuide,
                ':application_deadline' => $applicationDeadline,
                ':phone_number' => $phoneNumber,
                ':email' => $email,
                ':url_of_contact_form' => $urlOfContactForm,
                ':url_of_application_format' => $urlOfApplicationFormat,
                ':title_of_application_format' => $titleOfApplicationFormat,
                ':created_at' => date("Y-m-d H:i:s"),
        );
        if (empty(queryPost($dbh, $sql, $data))) {
          throw new Exception(ERROR['EXCEPTION']);
        }
        $stakeholderId = $dbh->lastInsertId();
        if (!empty($stakeholderCategory)) {
          debug('関係者のカテゴリが入力されています');
          foreach ($stakeholderCategory as $key => $value) {
            $sql = 'insert into stakeholder_categorization(stakeholder_id, stakeholder_category_id, created_at) values (:stakeholder_id, :stakeholder_category_id, :created_at)';
            $data = array(
                    ':stakeholder_id' => $stakeholderId,
                    ':stakeholder_category_id' => $value,
                    ':created_at' => date('Y-m-d H:i:s'),
            );
            if (empty(queryPost($dbh, $sql, $data))) {
              throw new Exception(ERROR['EXCEPTION']);
            }
          }
        }
        $dbh->commit();
        $_SESSION['message'] = SUCCESS['REGISTERED_STAKEHOLDER'];
        redirect('registeredContactAndApplication.php');
      }
    } catch (Exception $e) {
      $dbh->rollBack();
      exceptionHandler($e);
    }
  }
}


endPageDisplay();
?>
<?php
$pageTitle = !empty($stakeholderId) ? '事前相談・撮影申請先の編集' : '事前相談・撮影申請先の登録';
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
      <div class="c-checkbox__container">
        <p for="organization" class="c-input__label">登録種別</p>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message u-mb-8">撮影申請先が撮影相談先を兼ねる場合は両方チェックしてください</p>

        <?php
        foreach ($stakeholderCategories as $key => $value): ?>
          <label for="stakeholder_category<?php
          echo $value['stakeholder_category_id']; ?>" class="c-checkbox__label u-mr-24">
            <input type="checkbox" class="c-checkbox__body" name="stakeholder_category[]"
                   id="stakeholder_category<?php
                   echo $value['stakeholder_category_id']; ?>" value="<?php
            echo $value['stakeholder_category_id']; ?>" <?php
            if (in_array($value['stakeholder_category_id'], $stakeholderCategorizations, true)) {
              echo 'checked';
            } ?>>
            <span class="c-checkbox__name"><?php
              echo $value['name']; ?></span>
            <p class="c-input__error-message">
              <?php
              echo getErrorMessage('stakeholder_category[]'); ?>
            </p>
          </label>
        <?php
        endforeach; ?>
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="organization" class="c-input__label">組織名</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）神奈川県藤沢土木事務所</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('organization'); ?>
        </p>
        <input type="text" name="organization" id="organization"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('organization'); ?>" value="<?php
        echo keepInputAndDatabase('organization', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>

      <div class="c-input__container">
        <!--                <span class="c-status-label">ラベル</span>-->
        <label for="department" class="c-input__label">担当部署名</label>
        <!--                <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）許認可指導課</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('department'); ?>
        </p>
        <input type="text" name="department" id="department"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('department'); ?>" value="<?php
        echo keepInputAndDatabase('department', $dbStakeholderData); ?>">
        <!--                <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>

      <div class="c-input__container">
        <p class="c-input__label">画像</p>
        <!--        <p class="c-input__sub-label">コメント時に表示されます</p>-->
        <!--  <p class="c-input__help-message">help message</p>-->
        <p class="c-input__error-message"><?php
          echo getErrorMessage('avatar_path'); ?></p>
        <label class="c-image-upload__label js-drag-area" for="avatar_path">
          ここに画像をドラッグ
          <input class="c-image-upload__body js-image-upload" type="file" name="avatar_path" id="avatar_path"
                 accept=".jpg, .peg, .png">
          <input type="hidden" name="max_file_size" value="<?php
          echo 2 * MEGA_BYTES; ?>">
          <img class="c-image-upload__img js-image-preview" src="<?php
          if (!empty($dbStakeholderData['avatar_path'])) {
            echo $dbStakeholderData['avatar_path'];
          } ?>" style="<?php
          if (!empty($dbStakeholderData['avatar_path'])) {
            echo 'display:block;';
          } ?>" alt="">
        </label>
      </div>


      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="url_of_shooting_application_guide" class="c-input__label">撮影申請の案内ページのURL</label>
        <p class="c-input__sub-label"><?php
          echo sanitize(HELP['ENTER_URL']); ?></p>
        <p class="c-input__help-message">
          (例)https://www.pref.kanagawa.jp/docs/ex5/kaigan/kyoka.html
        </p>
        <p class="c-input__error-message"><?php
          echo getErrorMessage('url_of_shooting_application_guide'); ?></p>
        <input type="text" name="url_of_shooting_application_guide" id="url_of_shooting_application_guide"
               class="c-input__body <?php
               addErrorClass('url_of_shooting_application_guide'); ?>" value="<?php
        echo keepInputAndDatabase('url_of_shooting_application_guide', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>


      <div class="c-input__container">
        <!--          <span class="c-status-label --orange">必須</span>-->
        <label for="application_deadline" class="c-input__label">申請期限</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）撮影日の１０日前まで</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('application_deadline'); ?>
        </p>
        <input type="text" name="application_deadline" id="application_deadline" class="c-input__body <?php
        addErrorClass('application_deadline'); ?>" value="<?php
        echo keepInputAndDatabase('application_deadline', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter">0/10<j/p>-->
      </div>


      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="phone_number" class="c-input__label">電話番号</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <p class="c-input__help-message">（例）03-1234-1234</p>
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('phone_number'); ?>
        </p>
        <input type="text" name="phone_number" id="phone_number"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('phone_number'); ?>" value="<?php
        echo keepInputAndDatabase('phone_number', $dbStakeholderData); ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>


      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="email" class="c-input__label">メールアドレス</label>
        <!--          <p class="c-input__sub-label">sub-label</p>-->
        <!--          <p class="c-input__help-message">help message</p>-->
        <p class="c-input__error-message">
          <?php
          echo getErrorMessage('email'); ?>
        </p>
        <input type="email" name="email" id="email"
               class="c-input__body js-count js-valid-email <?php
               addErrorClass('email'); ?>" value="<?php
        if (!empty($_POST['email'])) {
          echo $_POST['email'];
        } ?>">
        <!--          <p class="c-input__counter"><span class="js-counter">0</span>/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="url_of_contact_form" class="c-input__label">連絡用フォームのURL</label>
        <!--        <p class="c-input__sub-label"></p>-->
        <p class="c-input__help-message">
          (例)https://dshinsei.e-kanagawa.lg.jp/140007-u/profile/userLogin_initDisplay.action?nextURL=CqTLFdO4voYnxt4ulafS2O3BB%2BFN7Gi4KMXGdBKterQfdrrJ0c3uG49wiM9ZpSVrEPgn8SiNxXCv%0D%0A1FXxyDAFxYzNnOoECHreFqLsJJO%2B9FMTfO4V8%2B8fadqRrdu72h8T8%2B%2Fv33%2FT%2B4o%3D%0D%0A
        </p>
        <p class="c-input__error-message"><?php
          echo getErrorMessage('url_of_facility_information_page'); ?></p>
        <input type="text" name="url_of_contact_form" id="url_of_contact_form"
               class="c-input__body <?php
               addErrorClass('url_of_contact_form'); ?>" value="<?php
        echo keepInputAndDatabase('url_of_contact_form', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="shooting_application_acceptance_type_tag_id" class="c-input__label">撮影申請の受付方法</label>
        <!--        <p class="c-input__sub-label"></p>-->
        <p class="c-input__help-message">
          （例）申請フォーム、メール、FAX、郵送
        </p>
        <p class="c-input__error-message"><?php
          echo getErrorMessage('shooting_application_acceptance_type_tag_id'); ?></p>
        <input type="text" name="shooting_application_acceptance_type_tag_id"
               id="shooting_application_acceptance_type_tag_id"
               class="c-input__body <?php
               addErrorClass('shooting_application_acceptance_type_tag_id'); ?>" value="<?php
        echo keepInputAndDatabase('shooting_application_acceptance_type_tag_id', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="url_of_application_format" class="c-input__label">申請様式のURL</label>
        <p class="c-input__sub-label">様式のファイルが掲載されているページのURLを記載してください</p>
        <p class="c-input__help-message">
          (例)https://www.pref.kanagawa.jp/docs/ex5/kaigan/kyoka.html
        </p>
        <p class="c-input__error-message"><?php
          echo getErrorMessage('url_of_application_format'); ?></p>
        <input type="text" name="url_of_application_format" id="url_of_application_format"
               class="c-input__body <?php
               addErrorClass('url_of_application_format'); ?>" value="<?php
        echo keepInputAndDatabase('url_of_application_format', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>

      <div class="c-input__container">
        <!--          <span class="c-status-label">ラベル</span>-->
        <label for="title_of_application_format" class="c-input__label">申請様式名</label>
        <!--        <p class="c-input__sub-label"></p>-->
        <p class="c-input__help-message">
          (例)海岸一時使用届
        </p>
        <p class="c-input__error-message"><?php
          echo getErrorMessage('title_of_application_format'); ?></p>
        <input type="text" name="title_of_application_format" id="title_of_application_format"
               class="c-input__body <?php
               addErrorClass('title_of_application_format'); ?>" value="<?php
        echo keepInputAndDatabase('title_of_application_format', $dbStakeholderData);
        ?>">
        <!--          <p class="c-input__counter">0/10</p>-->
      </div>


      <button class="c-button --full-width c-button__primary" type="submit">
        <?php
        if (!empty($stakeholderId)) {
          echo '変更する';
        } else {
          echo '登録する';
        }
        ?>
      </button>
    </form>
  </div>
</main>

<?php
require "footer.php"; ?>
