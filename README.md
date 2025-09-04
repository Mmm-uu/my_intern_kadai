# インターン課題環境構築手順

## Dockerの基本知識
Dockerの基本的な概念については、以下のリンクを参考にしてください：
- [Docker入門（1）](https://qiita.com/Sicut_study/items/4f301d000ecee98e78c9)
- [Docker入門（2）](https://qiita.com/takusan64/items/4d622ce1858c426719c7)

## セットアップ手順

1. **リポジトリをクローン**
   ```bash
   git clone <リポジトリURL>
   ```

2. **dockerディレクトリに移動**
   ```bash
   cd docker
   ```

3. **データベース名の設定**
   `docker-compose.yml` 内の `db` サービスにある `MYSQL_DATABASE` の値を、各自任意のデータベース名に設定してください。
   
   例:
   ```yaml
   environment:
     MYSQL_ROOT_PASSWORD: root
     MYSQL_DATABASE: <your_database_name>  # 任意のデータベース名を指定
   ```

4. **Dockerイメージのビルド**
   ```bash
   docker-compose build
   ```

5. **コンテナの起動**
   ```bash
   docker-compose up -d
   ```
6. **ブラウザからlocalhostにアクセス**

## PHP周りのバージョン
- **PHP**: 7.3
- **FuelPHP**: 1.8

## ログについて
- **アクセスログ**: Dockerのコンテナのログ
- **FuelPHPのエラーログ**: /var/www/html/intern_kadai/fuel/app/logs/
  - 年月日ごとにログが管理されている
  - tail -f {見たいログファイル}でログを出力

## MySQLコンテナ設定
このプロジェクトには、MySQLを使用するDBコンテナが含まれています。設定は以下の通りです。

- **MySQLバージョン**: 8.0
- **ポート**: `3306`
- **環境変数**:
  - `MYSQL_ROOT_PASSWORD`: root
  - `MYSQL_DATABASE`: 各自設定したデータベース名

### アクセス情報
- **ホスト**: `localhost`
- **ポート**: `3306`
- **ユーザー名**: `root`
- **パスワード**: `root`
- **データベース名**: 各自設定した名前



## データベース作成SQL

- **actionsテーブル**<br>
CREATE TABLE `actions` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `user_id` INT NOT NULL,
   `name` VARCHAR(10) NOT NULL,
   `frequency` INT NOT NULL,
   `color` CHAR(7) NOT NULL,
   `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `deleted` TINYINT(1) NOT NULL DEFAULT 0,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


- **recordsテーブル**<br>
CREATE TABLE `records` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `action_id` INT NOT NULL,
   `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `status` TINYINT(1) NOT NULL DEFAULT 0,
   `next_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


- **displayテーブル**<br>
CREATE TABLE `display` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `action_id` INT NOT NULL,
   `start_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `current_streak` INT UNSIGNED NOT NULL DEFAULT 0,
   `last_completed_at` DATETIME DEFAULT NULL,
   `next_target_at` DATETIME DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


- **usersテーブル**<br>
CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `group` INT(11) NOT NULL DEFAULT 1,
    `email` VARCHAR(255) NOT NULL,
    `last_login` VARCHAR(25) DEFAULT NULL,
    `login_hash` VARCHAR(255) DEFAULT NULL,
    `profile_fields` TEXT,
    `created_at` INT(11) NOT NULL DEFAULT 0,
    `updated_at` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


## 1日に1回手動で以下を実行したいです
- /intern_kadai/fuel/app/tasks/daily_update.php
- php oil refine daily_update
