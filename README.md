nginx-http2-php-mysql
===

『[docker-compose による NGINX + HTTP/2 + PHP-FPM7 + MySQL 環境の構築方法](https://tech.recruit-mp.co.jp/infrastructure/post-12795/)』で紹介しているサンプルコードです。

- Step.1) NGINX だけのシンプルな静的 web サーバを構築
- Step.2) PHP 環境を構築
- Step.3) PHP と MySQL を接続
- Step.4) HTTP/2 に対応させる

[DockerでPHP5-Alpineの開発環境（MySQL、Redis)を作る](https://qiita.com/idani/items/891c6747e90c9eb8fe40)
- Step.5) PHP5対応
- Step.6) Xdebug対応
- Step.7) Redis導入

[DockerでPHP-alpineのメール配信テスト(SMTP/sendmail)環境を構築する](https://qiita.com/idani/items/e703b8810db219bd57fa)
- Step.8) mail(SMTP/Sendmail)対応 その１
- Step.9) mail(SMTP/Sendmail)対応 その２

[Docker環境のMailDevでSMTP認証をする](https://qiita.com/idani/items/ed363e9fdc97d79d4b47)
- Step.10) mail SMTP AUTH対応