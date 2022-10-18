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
    debug('セッションID：' . session_id());
    if (!empty($_SESSION['login_limit'])) {
        debug('現在のタイムスタンプ：' . time());
        debug('セッションの有効期限：' . (time() + MONTH));
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
    '' => '',
    '' => '',
    '' => '',
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
function validEmail($value, $key, $message = ERROR['EMAIL'])
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
function validMinLength($value, $key, $minLength = 8, $message = ERROR['MIN_LENGTH'])
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


///
///
///
///
///
///
///
///
///
///
///
///
///
