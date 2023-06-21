# ベースとなるDockerイメージを指定
FROM php:8.1-fpm

# NGINXをインストール
RUN apt-get update && apt-get install -y nginx

# PHPの拡張機能をインストール
RUN docker-php-ext-install pdo_mysql

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ワーキングディレクトリを指定
WORKDIR /var/www/html

# アプリケーションのソースコードをコピー
COPY . /var/www/html

# Composerを使って依存関係をインストール
RUN composer install

# NGINXの設定ファイルをコピー
COPY default.conf /etc/nginx/sites-available/default

# ポート80を公開
EXPOSE 80

# コンテナ起動時に実行されるコマンドを指定
CMD ["nginx", "-g", "daemon off;"]
