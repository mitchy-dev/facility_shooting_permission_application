<?php

if (!empty($_SESSION['login_limit'])) {
    debug('ログイン済ユーザーです');
    if (time() < $_SESSION['login_limit']) {
        debug('ログイン有効期限内です。');
        $_SESSION['login_limit'] = time() + WEEK;
        if (basename($_SERVER['PHP_SELF']) === 'login.php') {
            debug('マイページに遷移します');
            redirect('mypage.php');
        }
    } else {
        debug('ログイン有効期限が切れています。ログインページに遷移します');
        if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
            redirect('login.php');
        }
    }
} else {
    debug('未ログインユーザーです');
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        redirect('login.php');
    }
}

