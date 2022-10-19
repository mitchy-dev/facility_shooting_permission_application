<body>
<div class="l-wrapper">
  <header class="l-header">
    <div class="c-header-title">
      <a href="index.php" class="c-header-title__link">
        海岸ロケ
      </a>
    </div>
    <ul class="c-header-nav">
      <?php
      if (empty($_SESSION['login_limit'])) : ?>
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
        echo getSessionFlash('message'); ?></span>
    </div>
  </header>

