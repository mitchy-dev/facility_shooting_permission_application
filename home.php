<?php

require( 'functions.php' );
startPageDisplay();


endPageDisplay();
?>
<?php
$pageTitle = 'ホーム';
require "head.php";
require "header.php";
?>

<main class="l-main --home">
  <section class="c-first-view__wrapper">
    <div class="u-block-center">
      <div class="c-first-view__column">
        <h2 class="c-first-view__heading">ロケに合う海岸が<br>すぐに見つかる！</h2>
        <p class="c-first-view__body">写真で一覧 / ロケの申込先がわかる</p>
        <button class="c-button c-button__ctr">海岸を探す</button>
      </div>
      <div class="c-first-view__main-visual">
        <img src="img/MacBook%20Pro%2016%203.png"></img>
      </div>
    </div>
  </section>
  <section class="c-home__block">
    <h2 class="c-home__title">ロケ地探し、こんな面倒ありませんか？</h2>
    <div class="c-home__content-wrap">
      <div class="c-speech-bubble">
        <p class="c-speech-bubble__body">条件に合う場所を探す<br>のに時間がかかる...</p>
      </div>
      <div class="c-speech-bubble">
        <p class="c-speech-bubble__body">ロケの許可をもらう先を<br>探すのが大変...</p>
      </div>
      <div class="c-speech-bubble">
        <p class="c-speech-bubble__body">納期に間に合う場所を<br>知りたい...</p>
      </div>
    </div>
  </section>
  <section class="c-home__block">
    <h2 class="c-home__title">海岸ロケでできること</h2>
    <div class="c-home__content-wrap">
      <div class="c-home-result-card__container">
        <div class="c-home-result-card__head">
          <img src="img/Rectangle%2018.jpg"></img>
        </div>
        <div class="c-home-result-card__foot">
          <h3 class="c-home-result-card__header">ロケに合う場所が見つかる</h3>
          <p class="c-home-result-card__body">海岸の一覧の写真で、どんな画が撮れるか比べながら検索できます。また、海岸を近い順に並べられるので移動時間なども考慮したロケ地探しができます。</p>
        </div>
      </div>
      <div class="c-home-result-card__container">
        <div class="c-home-result-card__head">
          <img src="img/Rectangle%2018-2.jpg"></img>
        </div>
        <div class="c-home-result-card__foot">
          <h3 class="c-home-result-card__header">ロケの申請先がわかる！</h3>
          <p class="c-home-result-card__body">
            ロケの直接の申請先が分かるので、面倒な問い合わせ作業から開放されます。事前相談先も載っているのでどんな関係者がいるのかひと目で分かります。
          </p>
        </div>
      </div>
      <div class="c-home-result-card__container">
        <div class="c-home-result-card__head">
          <img src="img/Rectangle%2018-3.jpg"></img>
        </div>
        <div class="c-home-result-card__foot">
          <h3 class="c-home-result-card__header">スケジュール調整に役立つ！</h3>
          <p class="c-home-result-card__body">
            各海岸のロケの申込み期限が分かります。ロケの許可にかかる期間が分かるのでロケのスケジュール
          </p>
        </div>
      </div>
    </div>
  </section>
  <section class="c-home__block --bgcolor-blue">
    <h2 class="c-home__title">ぜひご利用ください！</h2>
    <button class="c-button c-button__ctr">海岸を探す</button>
  </section>
  <section class="c-home__block">
    <h2 class="c-home__title">ご利用の流れ</h2>
    <div class="c-home__content-wrap">
      <div class="c-home-quick-start-guide__wrap">
        <div class="c-home-quick-start-guide__image"><img src="img/Vectorsearch.svg" alt=""></div>
        <h3 class="c-home-quick-start-guide__title">検索する</h3>
      </div>
      <div class="c-home-quick-start-guide__wrap">
        <div class="c-home-quick-start-guide__image"><img src="img/fluent-mdl2_compare-uneven.svg" alt=""></div>
        <h3 class="c-home-quick-start-guide__title">比較する</h3>
      </div>
      <div class="c-home-quick-start-guide__wrap">
        <div class="c-home-quick-start-guide__image"><img src="img/Vectordetail.svg" alt=""></div>
        <h3 class="c-home-quick-start-guide__title">詳細を見る</h3>
      </div>
    </div>
  </section>
  <section class="c-home__block">
    <h2 class="c-home__title">よくあるご質問</h2>
    <div class="c-faq">
      <p class="c-faq__body">Q：利用料はかかりますか？</p>
      <p class="c-faq__body">A：無料でご利用いただけます</p>
    </div>
    <div class="c-faq">
      <p class="c-faq__body">Q：スマートフォンで見れますか？</p>
      <p class="c-faq__body">A：現在PCのみ対応しております。スマートフォンへの対応は今後行う予定です。</p>
    </div>
  </section>
  <section class="c-home__block">
    <h2 class="c-home__title">お知らせ</h2>
    <div class="c-notification">
      <span class="c-notification__date">2022.12.27</span>
      <span class="c-notification__tag">ニュース</span>
      <span class="c-notification__body">α版を公開しました</span>
    </div>
    <div class="c-notification">
      <span class="c-notification__date">2022.12.27</span>
      <span class="c-notification__tag">ニュース</span>
      <span class="c-notification__body">α版を公開しました</span>
    </div>
  </section>
</main>

<?php
require "footer.php"; ?>
