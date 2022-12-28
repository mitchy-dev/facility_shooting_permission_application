<body class="<?php
if ( basename( $_SERVER['PHP_SELF'] ) === 'home.php' ) {
  echo '--home';
} ?>">
<div class="l-wrapper">
  <header class="l-header <?php
  if ( basename( $_SERVER['PHP_SELF'] ) === 'home.php' ) {
    echo '--home';
  } ?> js-header">
    <div class="l-header__inner">

      <div class="c-header-title">
        <a href="index.php" class="c-header-title__link">
          海岸ロケ
        </a>
      </div>
      <ul class="c-header-nav">
        <li class="c-header-nav__item c-button c-button__text">
          <a href="home.php">
            ホーム
          </a>
        </li>
        <li class="c-header-nav__item c-button c-button__text">
          <a href="index.php">
            サービストップ
          </a>
        </li>
        <li class="c-header-nav__item c-button c-button__text">
          <a href="home.php#notification">
            お知らせ
          </a>
        </li>
        <li class="c-header-nav__item c-button c-button__text">
          <a href="https://www.twitter.com/messages/compose?recipient_id=1513111003638398979" target="_blank">
            お問い合わせ
          </a>
        </li>
        <?php
        if ( empty( $_SESSION['login_limit'] ) ) : ?>
          <li class="c-header-nav__item c-button c-button__text">
            <a href="login.php">
              ログイン
            </a>
          </li>
          <li class="c-header-nav__item c-button c-button__secondary">
            <a href="signUp.php">
              ユーザー登録
            </a>
          </li>
        <?php
        else: ?>
          <li class="c-header-nav__item c-button c-button__text">
            <a href="logout.php">
              ログアウト
            </a>
          </li>
          <li class="c-header-nav__item c-button c-button__secondary">
            <a href="mypage.php">
              マイページ
            </a>
          </li>
        <?php
        endif; ?>
      </ul>
      <div class="c-notification-bar__container">
        <span class="c-notification-bar__message js-flash-message"><?php
          echo getSessionFlash( 'message' ); ?></span>
      </div>
    </div>
  </header>

