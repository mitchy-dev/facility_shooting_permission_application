<?php

require "functions.php";

debug('ログアウトします');

$_SESSION = [];
session_destroy();

redirect('login.php');
