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
$errorMassages = array();

//エラー文
const ERROR = array(
    'EMPTY' => '入力が必須の項目です。',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
);

//エラーメッセージ表示関数
function getErrorMessage($key)
{
    global $errorMassages;
    if (!empty($errorMassages[$key])) {
        echo $errorMassages[$key];
    }
}

//エラー時のCSSのクラス属性の付与
function addErrorClass($key)
{
    global $errorMassages;
    if (!empty($errorMassages[$key])) {
        echo 'error';
    }
}

//空チェック
function validEmpty($value, $key)
{
    global $errorMassages;
    if (empty($value)) {
        $errorMassages[$key] = ERROR['EMPTY'];
    }
}
///
///
