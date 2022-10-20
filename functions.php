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
    debug('================================');
}

//ページ表示の終了ログ
function endPageDisplay()
{
    debug('================================');
    debug(basename($_SERVER['PHP_SELF']) . 'の表示処理終了');
    debug('================================');
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
    '' => '',
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
function exceptionHandler($e)
{
    global $errorMessages;
    $errorMessages['common'] = ERROR['EXCEPTION'];
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
        $sql = 'select organization, representative_title, representatives_name, department, person_in_charge, phone_number, comment, avatar_path, has_facility_registration_authority
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


//////////////////////////////////////////////
//ファイルサイズ用の定数
//////////////////////////////////////////////
const KIRO_BYTES = 1024;
const MEGA_BYTES = KIRO_BYTES * 1024;
//
///
///
///
///
