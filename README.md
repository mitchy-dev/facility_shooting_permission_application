# 海岸ロケ

## 概要

海岸に特化したロケ地の検索サービス<br >
Web開発の基本を抑えるためにスクラッチで開発(PHP）<br >

現状、レスポンシブ対応していないためPC以外ではレイアウトが崩れます。

### ランディングページ

<p>
<a href="https://kaigan-loca.com">
<img src="https://user-images.githubusercontent.com/103357793/222030432-10372aab-b6d2-4fea-a17f-aac6ddfa95ff.png" alt="LP画像" />
</a>
</p> 

### サービストップ

<p>
<a href="https://kaigan-loca.com">
<img src="https://user-images.githubusercontent.com/103357793/222030523-4aa6f6ae-202b-4c55-89f0-92462fb295c4.png" alt="サービストップ画像" />
</a>
</p> 

### サービスURL

<p>
<a href="https://kaigan-loca.com">
<img src="https://user-images.githubusercontent.com/103357793/222030615-3c0b021a-fde8-42eb-ace9-168a1ebbc7cd.png" alt="OGP画像" />
</a>
</p> 

# 使用技術

## 構成

- PHP 7.2.8
- MySQL 5.7.23
- jQuery 3.6.1
- Apache 2.2.34
- ConoHa Wing（レンタルサーバー）

## ER図

<p>
<a href="https://kaigan-loca.com">
<img src="https://user-images.githubusercontent.com/103357793/222044832-e4069eab-a8e0-4039-b242-79029dca28e5.png" alt="ER図" />
</a>
</p> 

## テーブル

1. ユーザー
2. 海岸（コンテンツ）
3. 海岸の写真
4. ロケの相談・申請先
5. ロケの相談・申請先のカテゴリテーブル
6. 地域
7. 都道府県
8. １と３の中間テーブル
9. ３と５の中間テーブル

## 機能一覧

PHP（スクラッチ）とjQueryで実装<br>
海岸の情報を提供するメディアサービスなので、コンテンツは「海岸」になります。

- ユーザー登録
- ログイン・ログアウト機能
- 退会機能
- コンテンツの登録・編集機能
    - 新規登録画面と編集画面を同一画面で実装
    - コンテンツ名の重複チェック
        - 登録済みのコンテンツ名かDBへ照合(Ajax)
        - 編集時：編集前のコンテンツ名を重複チェックから除外(jquery.cookie.js)
    - 画像アップロード
        - 画像のライブプレビュー（jQuery）
        - 画像のリサイズ（PHP）
        - ドラッグアンドドロップでのアップロード
    - ロケの事前相談先と申請先の登録機能
        - 中間テーブルを用いて複数登録
    - 下書き機能
- コンテンツ削除機能
    - 削除実行前にモーダルで注意喚起(jQuery)
    - DBデータの削除をトランザクションで実行
- コンテンツ詳細表示
    - 画像スライダー（jQuery）
        - クロージャを利用し、保守性を担保
- コンテンツ一覧機能
    - ページング
    - 表示中の件数表示
    - 都道府県での絞り込み
- マイページ機能
    - 登録したコンテンツ一覧
    - コンテンツの下書き一覧
    - ロケの申請先一覧
    - プロフィール編集機能
- ロケの事前相談先と申請先の登録機能

# デザイン

## Figmaファイル

<p>
<a href="https://www.figma.com/file/afq9radNpVOPp4KibYrhfM/App-Design?node-id=0%3A1&t=AsMXxV7R1GlZGTQp-1">
<img src="https://user-images.githubusercontent.com/103357793/222037744-f0a4d1bc-d968-4f61-9d60-8d1dd53a3c37.png" alt="figma" />
</a>
</p> 
