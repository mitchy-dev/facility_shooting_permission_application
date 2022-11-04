<?php

//////////////////////////////////////////////
//エラー設定
//////////////////////////////////////////////
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', 'php.log');

//////////////////////////////////////////////
//ログの出力設定
//////////////////////////////////////////////
$isEnableDebug = true;

function debug($string)
{
    global $isEnableDebug;
    if ($isEnableDebug) {
        error_log('デバッグ：' . $string);
    }
}

//////////////////////////////////////////////
//セッションの設定
//////////////////////////////////////////////

//セッションの期限
const DAY = 60 * 60 * 24;
const WEEK = DAY * 7;
const MONTH = WEEK * 4;

session_save_path('/var/tmp');
ini_set('session.gc_maxlifetime', MONTH);
ini_set('session.cookie_lifetime', MONTH);

//セッションの開始
session_start();
session_regenerate_id();

//セッションの開始ログ
function startPageDisplay()
{
    debug('================================');
    debug(basename($_SERVER['PHP_SELF']) . 'の表示処理開始');
    if (!empty($_SESSION['user_id'])) {
        debug('ログインユーザーID：' . print_r($_SESSION['user_id'], true));
    }
    debug('現在のタイムスタンプ：' . time());
    if (!empty($_SESSION['login_time']) && !empty($_SESSION['login_limit'])) {
        debug('ログイン有効期限：' . ($_SESSION['login_time'] + $_SESSION['login_limit']));
    }
}

//ページ表示の終了ログ
function endPageDisplay()
{
    debug(basename($_SERVER['PHP_SELF']) . 'の表示処理終了');
//    debug('================================');
}

//////////////////////////////////////////////
//バリデーション
//////////////////////////////////////////////

//エラー文の格納用の配列
$errorMessages = array();

//エラー文
const ERROR = array(
    'EMPTY' => '入力が必須の項目です。',
    'EMAIL' => 'Emailの形式に合致しません',
    'MAX_LENGTH' => '文字以下でご入力ください',
    'MIN_LENGTH' => '文字以上でご入力ください',
    'HALF' => '半角英数字でご入力ください',
    'PASSWORD_MATCH' => 'パスワードとパスワード再入力が合致しません',
    'MATCHING_WITH_PASSWORD_HASH_VALUE' => '登録されているパスワードと一致しません',
    'AGREEMENT' => '利用規約とプライバシーポリシーへの同意が必要です',
    'EXCEPTION' => '不具合が発生しました。時間をおいてやり直してください。',
    'QUERY_POST_FALSE' => '不具合が発生しました。お手数ですがお問い合わせください',
    'LOGIN' => 'メールアドレスもしくはパスワードが違います',
    'DUPLICATE_EMAIL' => 'メールアドレスに誤りがあります。ご確認いただき、正しく変更してください。',
    '' => '',
    '' => '',
);

const SUCCESS = array(
    'SIGN_UP' => 'ユーザー登録しました',
    'LOGIN' => 'ログインしました',
    'PROFILE_EDIT' => 'プロフィールを更新しました',
    'FILE_UPLOAD' => 'ファイルが正常にアップロードされました',
    'PASSWORD_CHANGE' => 'パスワードを変更しました',
    'REGISTERED_STAKEHOLDER' => '登録しました',
    'REGISTERED' => '登録しました',
    'UPDATE_STAKEHOLDER' => '更新しました',
    'UPDATE' => '更新しました',
    '' => '',
    '' => '',
);
//エラーメッセージ表示関数
function getErrorMessage($key)
{
    global $errorMessages;
    if (!empty($errorMessages[$key])) {
        echo $errorMessages[$key];
    }
}

//エラー時のCSSのクラス属性の付与
function addErrorClass($key)
{
    global $errorMessages;
    if (!empty($errorMessages[$key])) {
        echo 'error';
    }
}

//空チェック
function validEmpty($value, $key, $message = ERROR['EMPTY'])
{
    global $errorMessages;
    if (empty($value)) {
        $errorMessages[$key] = $message;
    }
}

//メールアドレス
function validEmailFormat($value, $key, $message = ERROR['EMAIL'])
{
    global $errorMessages;
    if (!preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|', $value)) {
        $errorMessages[$key] = $message;
    }
}

//最大文字数
function validMaxLength($value, $key, $maxLength = 256, $message = ERROR['MAX_LENGTH'])
{
    global $errorMessages;
    if (mb_strlen($value) > $maxLength) {
        $errorMessages[$key] = $maxLength . $message;
    }
}

//最小文字数
function validMinLength($value, $key, $minLength = 6, $message = ERROR['MIN_LENGTH'])
{
    global $errorMessages;
    if (mb_strlen($value) < $minLength) {
        $errorMessages[$key] = $minLength . $message;
    }
}

//半角英数字
function validHalf($value, $key, $message = ERROR['HALF'])
{
    global $errorMessages;
    if (!preg_match('/^[0-9a-zA-Z]*$/', $value)) {
        $errorMessages[$key] = $message;
    }
}

//一致確認
function validMatch($value, $value2, $key, $message = ERROR['PASSWORD_MATCH'])
{
    global $errorMessages;
    if ($value !== $value2) {
        $errorMessages[$key] = $message;
    }
}

function validPasswordMatch($password, $hash, $key, $message = ERROR['MATCHING_WITH_PASSWORD_HASH_VALUE'])
{
    global $errorMessages;
    if (!password_verify($password, $hash)) {
        $errorMessages[$key] = $message;
    }
}

function validDuplicateEmail($value, $key)
{
    debug('登録済みのメールアドレスか確認します。');
    try {
        $dbh = dbConnect();
        $sql = 'select count(*) from users where email = :email and is_deleted = false';
        $data = array(
            ':email' => $value,
        );
        $sth = queryPost($dbh, $sql, $data);
        $result = $sth->fetch();
        if (!empty(array_shift($result))) {
            global $errorMessages;
            $errorMessages[$key] = ERROR['DUPLICATE_EMAIL'];
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

function validEmail($value, $key)
{
    global $errorMessages;
    validEmpty($value, $key);
    validMaxLength($value, $key);
    if (empty($errorMessages[$key])) {
        validEmailFormat($value, $key);
    }
}

function validPassword($value, $key)
{
    global $errorMessages;
    validEmpty($value, $key);
    if (empty($errorMessages[$key])) {
        validMinLength($value, $key);
    }
    validMaxLength($value, $key);
    if (empty($errorMessages[$key])) {
        validHalf($value, $key);
    }
}

//////////////////////////////////////////////
//DB操作
//////////////////////////////////////////////
//DB接続
function dbConnect()
{
    $dsn = 'mysql:dbname=facility_shooting_permission_application;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );

    return new PDO($dsn, $user, $password, $options);
}

//クエリ実行
function queryPost($dbh, $sql, $data)
{
    $sth = $dbh->prepare($sql);
    if ($sth->execute($data)) {
        debug('クエリが成功しました：' . print_r($sth, true));
        return $sth;
    } else {
        global $errorMessages;
        $errorMessages['common'] = ERROR['QUERY_POST_FALSE'];
        debug('クエリが失敗しました:' . print_r($sth->errorInfo(), true));
        return false;
    }
}

//例外処理
function exceptionHandler($e, $key = 'common', $message = ERROR['EXCEPTION'])
{
    global $errorMessages;
    $errorMessages[$key] = $message;
    debug('例外処理：' . $e->getMessage());
}

///
//////////////////////////////////////////////
//入力値保持
//////////////////////////////////////////////

function keepInputAndDatabase($key, $dataFetchedFromDatabase = array(), $useGetMethod = false)
{
    $method = $useGetMethod ? $_GET : $_POST;

    if (array_key_exists($key, $dataFetchedFromDatabase)) {
        if (array_key_exists($key, $method) && $method[$key] !== $dataFetchedFromDatabase[$key]) {
            return $method[$key];
        } else {
            return $dataFetchedFromDatabase[$key];
        }
    } else {
        if (array_key_exists($key, $method)) {
            return $method[$key];
        }
    }
}

function keepFilePath($file, $errorMessageKey = 'common', $dbData = array())
{
    if (!empty($file['name'])) {
        return uploadImage($file, $errorMessageKey);
    } elseif (!empty($dbData)) {
        return $dbData;
    } else {
        return '';
    }
}

//////////////////////////////////////////////
//リダイレクト
//////////////////////////////////////////////

function redirect($pageName)
{
    header('Location:' . $pageName);
    exit();
}

///
//////////////////////////////////////////////
//フラッシュメッセージ
//////////////////////////////////////////////
function getSessionFlash($key)
{
    if (!empty($_SESSION[$key])) {
        $message = $_SESSION[$key];
        $_SESSION[$key] = '';
        return $message;
    }
}


//////////////////////////////////////////////
//データの取得
//////////////////////////////////////////////
//ユーザー情報
function fetchUserData($userId)
{
    debug('ログインユーザーのデータを取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select password, organization, representative_title, representatives_name, department, person_in_charge, phone_number, comment, avatar_path, has_facility_registration_authority
from users where user_id = :user_id and is_deleted = false';
        $data = array(
            ':user_id' => $userId,

        );
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetch();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//地域
//最初の表示用
function fetchRegionsAndPrefectures($regionId = 0)
{
    debug('地域と都道府県データを取得します');
    try {
        $result = array();
        $dbh = dbConnect();

        $sql = 'select region_id, name from regions';
        $data = array();
//        if (!empty($regionId)) {
//            $sql .= ' where region_id = :region_id';
//            $data['region_id'] = $regionId;
//        }
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            $result['regions'] = $sth->fetchAll();
        }

        $sql2 = 'select prefecture_id, region_id, name from prefectures';
        $data2 = array();
        if (!empty($regionId)) {
            $sql2 .= ' where region_id = :region_id';
            $data2['region_id'] = $regionId;
        }
        $sth2 = queryPost($dbh, $sql2, $data2);
        if (!empty($sth2)) {
            $result['prefectures'] = $sth2->fetchAll();
        }
        return $result;
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//県名
function fetchPrefectures()
{
    debug('都道府県データを取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select prefecture_id, name from prefectures';
        $data = array();
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetchAll();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}


//相談先・申請先の情報
function fetchStakeholder($userId, $stakeholderId)
{
    debug('相談・申請先の情報を取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select organization, department, avatar_path, url_of_shooting_application_guide, title_of_shooting_application_guide, application_deadline, phone_number, email, url_of_contact_form, url_of_application_format, title_of_application_format
from stakeholders where user_id = :user_id and stakeholder_id = :stakeholder_id and is_deleted = false';
        $data = array(
            ':user_id' => $userId,
            ':stakeholder_id' => $stakeholderId
        );
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetch();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//関係者の種別の取得
function fetchStakeholderCategories()
{
    debug('関係者の種別を取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select stakeholder_category_id, name from stakeholder_categories';
        $data = array();
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetchAll();
        } else {
            false;
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

function fetchStakeholderCategorizations($stakeholderId)
{
    debug('関係者の種別を取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select stakeholder_category_id from stakeholder_categorization where stakeholder_id = :stakeholder_id';
        $data = array(
            ':stakeholder_id' => $stakeholderId
        );
        $sth = queryPost($dbh, $sql, $data);
        $records = $sth->fetchAll();
        if (!empty($records)) {
            return array_column($records, 'stakeholder_category_id');
        } else {
            return array();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//相談・申請先の情報の取得
function fetchStakeholdersWithCategories($userId)
{
    debug('関係者とその登録区分の一覧情報を取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select * from stakeholders where user_id = :user_id and is_deleted = false';
        $data = array(':user_id' => $userId);
        $sth = queryPost($dbh, $sql, $data);
        $stakeholders = $sth->fetchAll();

        if (!empty($stakeholders)) {
            foreach ($stakeholders as $key => $value) {
                $sql2 = ' select stakeholder_id, scn.stakeholder_category_id as category_id, name as category_name from stakeholder_categorization as scn left join stakeholder_categories sc on sc.stakeholder_category_id = scn.stakeholder_category_id where scn.stakeholder_id = :stakeholder_id ';
                $data2 = array(
                    ':stakeholder_id' => $value['stakeholder_id']
                );
                $sth2 = queryPost($dbh, $sql2, $data2);
                if (!empty($sth2)) {
                    $categories = $sth2->fetchAll();
                    $value['categories'] = $categories;
                    $result[] = $value;
                } else {
                    $result[] = $value;
                }
            }
            return $result;
        } else {
            return false;
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//海岸のデータの取得
function fetchFacility($userId, $facilityId)
{
    debug('海岸の情報を取得します');
    try {
        $dbh = dbConnect();
        $sql = ' select * from facilities where user_id = :user_id and facility_id = :facility_id and is_deleted = false; ';
        $data = array(
            ':user_id' => $userId,
            ':facility_id' => $facilityId
        );
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetch();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

function fetchFacilitiesWithPrefectureId($regionId = 0, $prefectureId = 0, $currentPageNumber = 1)
{
    debug('トップページに表示する海岸のデータを取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select * from facilities as f right join prefectures as p on f.prefecture_id = p.prefecture_id where f.published = 1 and f.is_deleted = false';
        $data = array();
        if (!empty($regionId)) {
            $sql .= ' and p.region_id = :region_id';
            $data[':region_id'] = $regionId;
        }
        if (!empty($prefectureId)) {
            $sql .= ' and p.prefecture_id = :prefecture_id';
            $data[':prefecture_id'] = $prefectureId;
        }
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetchAll();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//登録した海岸の一覧を取得
function fetchListOfRegisteredFacilities($userId)
{
    debug('登録した海岸の情報を取得します');
    try {
        $dbh = dbConnect();
        $sql = ' select f.facility_id, f.facility_name, f.thumbnail_path, p.name as prefecture from facilities as f left join prefectures as p on f.prefecture_id = p.prefecture_id where f.user_id = :user_id and f.is_deleted = false; ';
        $data = array(
            ':user_id' => $userId,
        );
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetchAll();
        } else {
            return array();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//海岸の写真のパスを取得
function fetchFacilityImagePaths($facilityId)
{
    debug('海岸の写真を取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select image_path from facility_images where facility_id = :facility_id';
        $data = array(
            ':facility_id' => $facilityId
        );
        $sth = queryPost($dbh, $sql, $data);
        $records = $sth->fetchAll();
        if (!empty($records)) {
            return array_column($records, 'image_path');
        } else {
            return array();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//相談先・申請先の関係者の取得
function fetchStakeholdersAssociatedWithTheFacility($facilityId)
{
    debug('登録されている相談先と申請先の関係者IDを取得します');
    try {
        $dbh = dbConnect();
        $sql = ' select stakeholder_id, stakeholder_category_id from facilities_stakeholders where facility_id = :facility_id';
        $data = array(
            ':facility_id' => $facilityId
        );
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            return $sth->fetchAll();
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

function fetchFacilityAndStakeholdersAndImagePaths($facilityId)
{
    debug('海岸とその関係者のデータを取得します');
    try {
        $dbh = dbConnect();
        $sql = 'select facility_id, user_id, facility_name, thumbnail_path, facility_address, shooting_fee, url_of_facility_information_page, title_of_facility_information_page, is_need_consultation_of_shooting, is_need_application_of_shooting, created_at, updated_at, name as prefecture_name from facilities as f left join prefectures as p on f.prefecture_id = p.prefecture_id where f.facility_id = :facility_id and f.published = true and f.is_deleted = false';
        $data = array(
            ':facility_id' => $facilityId
        );
        $sth = queryPost($dbh, $sql, $data);
        if (!empty($sth)) {
            $result = $sth->fetch();
        }

//        関係者データの取得
        $sql2 = 'select fs.stakeholder_category_id,fs.stakeholder_id, s.organization, s.department, s.avatar_path, s.url_of_shooting_application_guide, s.title_of_shooting_application_guide, s.application_deadline, s.phone_number, s.email, s.url_of_contact_form, s.url_of_application_format, s.title_of_application_format from facilities_stakeholders as fs left  join stakeholders as s on fs.stakeholder_id = s.stakeholder_id where fs.facility_id = :facility_id and s.is_deleted = false';
        $data2 = array(
            ':facility_id' => $facilityId
        );
        $sth = queryPost($dbh, $sql2, $data2);
        if (!empty($sth)) {
            $stakeholders = $sth->fetchAll();
            if (!empty($stakeholders)) {
                foreach ($stakeholders as $key => $value) {
                    if ($value['stakeholder_category_id'] == 1) {
                        $result['prior_consultations'][] = $value;
                    } elseif ($value['stakeholder_category_id'] == 2) {
                        $result['application_destinations'][] = $value;
                    }
                }
            }
        }

//        施設の写真を取得
        $sql3 = 'select image_path from facility_images where facility_id = :facility_id';
        $data3 = array(
            ':facility_id' => $facilityId
        );
        $sth = queryPost($dbh, $sql3, $data3);
        if (!empty($sth)) {
            $images = $sth->fetchAll();
            if (!empty($images)) {
                $result['images'] = $images;
            }
        }
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

//////////////////////////////////////////////
//ファイルアップロード
//////////////////////////////////////////////
//
//ファイルサイズ用の定数
const KIRO_BYTES = 1024;
const MEGA_BYTES = KIRO_BYTES * 1024;
//アップロード関数
function uploadImage($file, $errorMessageKey)
{
    if (isset($file['error']) && is_int($file['error'])) {
        debug('画像のアップロードを開始します');
        try {
            switch ($file['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // ファイル未選択
                    throw new RuntimeException('ファイルが選択されていません');
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default:
                    throw new RuntimeException('その他のエラーが発生しました');
            }

            // $_FILES[$key]['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
            $type = @exif_imagetype($file['tmp_name']);
            if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                throw new RuntimeException('画像形式が未対応です');
            }

            // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
            $path = sprintf('uploads/%s%s', sha1_file($file['tmp_name']), image_type_to_extension($type));
            if (!move_uploaded_file($file['tmp_name'], $path)) {
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            chmod($path, 0644);
            return $path;
        } catch (RuntimeException $e) {
            exceptionHandler($e, $errorMessageKey, $e->getMessage());
        }
    }
}

//複数アップロード時に配列の形式を使いやすくする関数
//https://www.php.net/manual/ja/features.file-upload.multiple.php#53240
function reArrayFiles($file_post)
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

//////////////////////////////////////
//文字列操作
//////////////////////////////////////////////
//サニタイズ
function sanitize($string)
{
    return htmlspecialchars($string, ENT_NOQUOTES);
}


//////////////////////////////////////
//画像の表示
//////////////////////////////////////////////
//海岸画像の表示
function showFacilityImage($path)
{
    if (!empty($path)) {
        return $path;
    } else {
        return 'img/1920 887.jpg';
    }
}


//////////////////////////////////////
//外部サイトからの情報取得
//////////////////////////////////////////////
//GoogleMap
function fetchGoogleMapUrl($address)
{
    $address = urlencode($address);
    $zoom = 15;
    return "https://maps.google.co.jp/maps?q={$address}&z={$zoom}";
}

//サイト名取得
function fetchTitleFromURL($url)
{
    $options = stream_context_create(array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false
        )
    ));
    $source = @file_get_contents($url, false, $options);
    $html = mb_convert_encoding($source, 'UTF-8', 'auto');
    var_dump($source);
    var_dump($html);
    if (preg_match('/<title>(.*?)<\/title>/', $html, $result) !== false) {
        var_dump($result);
        return $result[1];
    }
}














