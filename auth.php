<?php

require "functions.php";

if (!empty($_SESSION['login_limit'])) {
    if (time() < $_SESSION['login_limit']) {
//        要求されたページを表示
        $_SESSION['login_limit'] = time() + WEEK;
        if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
            redirect('my-page.html');
        }
    } else {
        if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
            redirect('login.html');
        }
    }
} else {
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        redirect('login.html');
    }
}

