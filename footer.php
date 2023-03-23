<footer id="footer" class="l-footer u-text-center">
  <ul class="c-footer-nav">
    <li class="c-footer-nav__list"><a href="terms.php" class="c-footer-nav__item">利用規約</a></li>
    <li class="c-footer-nav__list"><a href="policy.php" class="c-footer-nav__item">プライバシーポリシー</a></li>
    <li class="c-footer-nav__list"><a href="https://www.twitter.com/messages/compose?recipient_id=1513111003638398979"
                                      class="c-footer-nav__item" target="_blank">お問い合わせ</a></li>
  </ul>
  <div class="c-footer-copyright">
    <p class="c-footer-copyright__item">©2023 みっちー</p>
  </div>
</footer>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
<script>
  $(function () {
    //ヘッダーの高さ分コンテンツを下げる（固定ヘッダー）
    var height = $('.js-header').outerHeight();
    $('.js-lower-header').css('margin-top', height);


    //フッターの固定
    var $footer = $('#footer');
    console.log($footer);

    if (window.innerHeight > $footer.offset().top + $footer.outerHeight()) {
      $footer.attr({
        'style': 'position:fixed;top:' + (window.innerHeight - $footer.outerHeight()) + 'px;'
      });
    }


    //文字数カウント
    $('.js-count').keyup(function () {
      var count = $(this).val().length;
      $('.js-counter').text(count);
    });

    //  バリデーション
    $('.js-valid-email').keyup(function () {
      var $errorMassage = $(this).siblings('.c-input__error-message');

      if ($(this).val().length === 0) {
        $(this).addClass('error');
        $errorMassage.text('入力必須です。');
      } else {
        $(this).removeClass('error');
        $errorMassage.text('');

      }
    })
    $('.js-valid-required').keyup(function () {
      var $errorMassage = $(this).siblings('.c-input__error-message');

      if ($(this).val()) {
        $(this).removeClass('error');
        $errorMassage.text('');
        $('.js-disabled-submit').removeAttr('disabled');
      } else {
        $(this).addClass('error');
        $errorMassage.text('入力必須です。');
        $('.js-disabled-submit').attr('disabled', 'disabled');
      }
    })

    //海岸名の重複チェック
    $('.js-valid-registered').keyup(function () {
      if ($(this).val().length > 0) {
        var $errorMassage = $(this).siblings('.c-input__error-message');
        var that = $(this);

        $.ajax({
          type: 'post',
          dataType: 'json',
          url: 'validRegistered.php',
          data: {
            facility_name: $(this).val()
          }
        }).then(function (data) {
          console.log(data);
          if (data) {
            if (data.isRegistered) {
              that.addClass('error');
            } else {
              that.removeClass('error');
            }
            $errorMassage.text(data.msg);
          }
        })
      }
    })

    //  フラッシュメッセージ
    var $flashMessage = $('.js-flash-message');
    if ($flashMessage.text().trim() !== '') {
      $flashMessage.parent().slideToggle('slow');
      $flashMessage.parent().slideToggle(3000);
    }

    //  画像のドラッグ中のフォームの変化
    var $dragArea = $('.js-drag-area');

    $dragArea.on('dragover', function (e) {
      e.preventDefault();
      $(this).addClass('--on-dragging');
    });

    $dragArea.on('dragleave', function (e) {
      e.preventDefault();
      $(this).removeClass('--on-dragging');
    });

    //画像のプレビュー機能
    $('.js-image-upload').on('change', function (e) {
      e.preventDefault();
      $dragArea.removeClass('--on-dragging');

      var $imagePreview = $(this).siblings('.js-image-preview');
      var fileReader = new FileReader();

      fileReader.addEventListener("load", function () {
        $imagePreview.attr('src', this.result).show();

      });

      fileReader.readAsDataURL(this.files[0]);

    })

    //  一覧の絞り込み機能
    $('.js-region').on('change', function () {
      var params = {};

      // URLからパラメーター解析＆取得
      var query = window.location.href.split("?")[1];
      if (query) {
        var rawParams = query.split('&');
        rawParams.forEach(function (prm, i) {
          var kv = prm.split('=');
          params[kv[0]] = kv[1];
        });
      }

      var url = 'index.php?page=1&region_id=' + $(this).val();
      // Getパラメーターのキー名と値を全部表示
      if (query) {
        Object.keys(params).forEach(function (name, i) {
          if (name !== 'page' && name !== 'region_id' && name !== 'prefecture_id') {
            url += '&' + name + '=' + this[name];
          }
        }, params);
      }
      window.location.href = url;
    });

    $('.js-prefecture').on('change', function () {
      // window.location.href = 'index.php?region_id=' + $('.js-region').val() + '&prefecture_id=' + $(this).val();
      var params = {};

      // URLからパラメーター解析＆取得
      var query = window.location.href.split("?")[1];
      if (query) {
        var rawParams = query.split('&');
        rawParams.forEach(function (prm, i) {
          var kv = prm.split('=');
          params[kv[0]] = kv[1];
        });
      }

      var url = 'index.php?page=1&prefecture_id=' + $(this).val();
      // Getパラメーターのキー名と値を全部表示
      if (query) {
        Object.keys(params).forEach(function (name, i) {
          if (name !== 'page' && name !== 'prefecture_id') {
            url += '&' + name + '=' + this[name];
          }
        }, params);
      }
      window.location.href = url;
    })
    //  画像切り替え
    var $imageMain = $('.js-image-main');
    $('.js-image-thumbnail').on('click', function () {
      $imageMain.attr('src', $(this).attr('src'));
    })

    //  モーダル
    $('.js-show-modal').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      $('.js-modal-cover').show();
      var $modalTarget = $('.js-modal-target');
      var modalWidth = $modalTarget.outerWidth();
      var windowWidth = $(window).width();

      $modalTarget.css('margin-left', (windowWidth - modalWidth) / 2 + 'px');
    })

    $('.js-hide-modal').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $('.js-modal-cover').hide();
      // $('.js-modal-target').hide();
    })

    /*
      画像スライダー
      クロージャについて
      無名関数で囲むため、slider()を実行しないと、変数定義を含め中の処理が実行されない
      即時関数は関数定義、関数実行を記述するのを(function(){})()と記述することで簡略化している

     */
    var slider = (function () {
      var counter = 1;
      if (counter === 1) {
        $('.js-slider-prev').hide();
      }
      var slideWidth = $('.js-slider-item').width();
      var totalNumberOfSlides = $('.js-slider-item').length;
      var sliderContainerWidth = slideWidth * totalNumberOfSlides;
      var $sliderContainer = $('.js-slider-container');
      var duration = 300;

      $sliderContainer.width(sliderContainerWidth);
      $('.js-slider-next').on('click', function () {
        if (counter < totalNumberOfSlides) {
          //  スライダーの帯を移動させる
          $sliderContainer.animate({
            left: '-=' + slideWidth + 'px'
          }, duration);
          counter++;
          console.log(counter);
        }
        if (counter >= totalNumberOfSlides) {
          $(this).hide();
        }
        if (counter > 1) {
          $('.js-slider-prev').show();

        }
      });

      $('.js-slider-prev').on('click', function () {
        if (counter > 1) {
          $sliderContainer.animate({
            left: '+=' + slideWidth + 'px'
          }, duration);
          counter--;
          console.log(counter);
        }
        if (counter < totalNumberOfSlides) {
          $('.js-slider-next').show();
        }
        if (counter === 1) {
          $(this).hide();
        }
      });
    })();

  });
</script>

</body>
</html>