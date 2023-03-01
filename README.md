# 海岸ロケ

## 概要

海岸に特化したロケ地の検索サービスです。<br >
PHPでフルスクラッチ開発をしました。<br >
現状、レスポンシブ対応していないためPC以外ではレイアウトが崩れます。

### ランディングページ

<p>
<a href="https://kaigan-loca.com">
<img src="img/home_image.png" alt="LP画像" />
</a>
</p> 

### サービストップ

<p>
<a href="https://kaigan-loca.com">
<img src="img/index_image.png" alt="サービストップ画像" />
</a>
</p> 

### サービスURL

<p>
<a href="https://kaigan-loca.com">
<img src="img/ogp_image.png" alt="OGP画像" />
</a>
</p> 

# 使用技術

## 構成

- PHP 2.5.7
- MySQL 5.7
- jQuery 3.6.1
- Apache
- ConoHa Wing

## ER図

<p>
<a href="https://kaigan-loca.com">
<img src="img/er_image.png" alt="ER図" />
</a>
</p> 

## テーブル

1. ユーザー
2. 海岸
3. 海岸の写真
4. ロケの相談・申請先
5. ロケの相談・申請先のカテゴリテーブル
6. 地域
7. 都道府県
8. １と３の中間テーブル
9. ３と５の中間テーブル

## 機能一覧

PHP（スクラッチ）とjQueryで実装しています。<br>
海岸の情報を提供するメディアサービスなので、コンテンツは「海岸」になります。

- ユーザー登録、ログイン機能
    - セッション管理
- 海岸（コンテンツ）の登録・編集機能
    - 新規登録画面と編集画面を同一画面で実装
    - 海岸名の重複チェック
        - 登録済みの海岸名かDBへ照合(Ajax)
        - 編集前の海岸名をチェックから除外(jquery.cookie.js)
    - 画像アップロード
        - 画像のライブプレビュー（jQuery）
        - 画像のリサイズ（PHP）
        - ドラッグアンドドロップでのアップロード
    - ロケの事前相談先と申請先の登録機能
        - 中間テーブルを用いて複数登録
    - 下書き機能
- 海岸（コンテンツ）削除機能
    - 削除実行前にモーダルで注意喚起(jQuery)
    - DBデータの削除をトランザクションで実行
- 海岸（コンテンツ）詳細表示
    - 画像スライダー（jQuery）
        - クロージャを利用し、保守性を向上
- 海岸(
- 一覧機能
    - ページング
    - 表示中の件数表示
    - 都道府県での絞り込み
- マイページ機能
    - 登録した海岸一覧
    - 下書き一覧
    - ロケの申請先一覧
    - プロフィール編集機能
- ロケの事前相談先と申請先の登録機能
- いいね機能(Ajax)
    - ランキング機能

# デザイン

## Figmaファイル

<iframe style="border: 1px solid rgba(0, 0, 0, 0.1);" width="800" height="450" src="https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2Fafq9radNpVOPp4KibYrhfM%2FApp-Design%3Fnode-id%3D0%253A1%26t%3D47bJY2Svgs5cEJFU-1" allowfullscreen></iframe>