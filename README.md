# Rese -飲食店予約サービス-  

*トップ画面*


*マイページ*


*店舗詳細・予約画面*



## 作成した目的
お気に入り飲食店の管理を身近に！  
事前決済・評価もできる予約サービスです。  

## アプリケーションURL
http://ec2-43-207-42-6.ap-northeast-1.compute.amazonaws.com/  


## 他のリポジトリ
開発環境用  

## 機能一覧
ユーザー認証機能  
メールログイン機能  
飲食店検索  
お気に入り機能  
予約,予約日時変更,取り消し機能  
予約用QRコード表示  
店舗評価機能  
予約リマインダーメール機能  
  
*管理者用*  
店舗代表者作成機能  
ユーザーへお気に入りを促すメール送信
  
*店舗代表者用*  
店舗作成機能  
代表店舗の予約確認  


## 使用技術（実行環境）
AWS EC2: Amazon Linux2
Apache: 2.4.56
Docker: 20.10.23
Docker Compose: 2.17.3
フロントエンド: Nuxt.js 2.15.8  
バックエンド: Laravel 8.x  
firebase: 9.19.1  
  
*依存関係* 
  @chenfengyuan/vue-qrcode: 1  
  @nuxtjs/auth-next: 5.0.0-1667386184.dfbbb54  
  @nuxtjs/axios: 5.13.6  
  @nuxtjs/firebase: 8.2.2  
  nuxt-stripe-module: 3.2.0  
  vee-validate: 3  
  stripe/stripe-php: 10.12  
  tymon/jwt-auth: 1.0@dev  


## テーブル設計


## ER図


## 環境構築
・.env.exampleを.envにコピーし、環境設定を行ってください。

*テストデータ準備*
UsersTableSeeder , AreasTableSeeder , GenresTableSeeder　, ShopsTableSeeder , CoursesTableSeeder  php artisan db:seed  


*ゲストログインには下記テストユーザーをご利用下さい*
*テスト決済には下記テスト決済カード番号をご利用下さい*

## firebase認証用テストユーザー
(UsersTableSeederへ登録済み)

管理者　　　メールアドレス：admin@gest.com　パスワード：adminpass  
店舗代表者　メールアドレス：manager@gest.com　パスワード：managerpass  
ユーザー1　 メールアドレス：gest@gest.com　パスワード：gestpass  
ユーザー2　 メールアドレス：gest2@gest.com　パスワード：gestpass  

## stripeテスト決済番号 

テスト決済カード番号  4242 4242 4242 4242  
(有効期限は任意の未来の日付を、CVC,ZIPコードは任意の番号を入力ください)  