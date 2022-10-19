<?php

require('functions.php');
startPageDisplay();
require "auth.php";


endPageDisplay();
?>
<?php
$pageTitle = 'マイページ';
require "head.php";
require "header.php";
?>


  <main class="l-main">
    <section class="l-sidebar__container">
      <ul class="c-sidebar__list">
        <li class="c-sidebar__item --active --border-top"><a href="">お気に入り</a></li>
      </ul>
      <h3 class="c-sidebar__list-title">海岸の管理</h3>
      <ul class="c-sidebar__list">
        <li class="c-sidebar__item"><a href="">登録した海岸</a></li>
        <li class="c-sidebar__item"><a href="">下書き</a></li>
        <li class="c-sidebar__item"><a href="">海岸の登録</a></li>
      </ul>
      <h3 class="c-sidebar__list-title">事前相談・撮影申請先</h3>
      <ul class="c-sidebar__list">
        <li class="c-sidebar__item"><a href="">登録した情報</a></li>
        <li class="c-sidebar__item"><a href="">事前相談・撮影申請先の登録</a></li>
      </ul>
      <h3 class="c-sidebar__list-title">個人設定</h3>
      <ul class="c-sidebar__list">
        <li class="c-sidebar__item"><a href="">プロフィール</a></li>
        <li class="c-sidebar__item"><a href="">パスワード</a></li>
        <li class="c-sidebar__item"><a href="">メールアドレス</a></li>
        <li class="c-sidebar__item"><a href="">退会</a></li>
      </ul>
    </section>
    <div class="l-main__my-page">
      <h1 class="c-main__title u-text-center"><?php
        echo $pageTitle; ?></h1>
      <div class="p-my-page__avatar__container">
        <img src="img/member_photo_noimage_thumb.png" alt="">
      </div>
      <p class="p-my-page__user-name">茨城県土木事務所</p>
      <table class="p-my-mage__number-of-registrations">
        <tr>
          <td>登録した海岸</td>
          <td>10</td>
        </tr>
        <tr>
          <td>申請先</td>
          <td>1</td>
        </tr>
        <tr>
          <td>事前相談先</td>
          <td>1</td>
        </tr>
      </table>

    </div>
  </main>

<?php
require "footer.php"; ?>