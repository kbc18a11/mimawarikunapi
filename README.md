# mimawarikunapi
## ※APIに関することは、`api`ディレクトリの中の`README.md`を見てください
## 起動・停止方法
```
コンテナ起動
docker-compose up -d

コンテナ停止
docker-compose down
```

## 初回起動限定時の操作 
```
コンテナビルド
docker-compose build

dbコンテナに入る
docker exec -it db bash

mylsqlにログイン
mysql -u root -p

.envのMYSQL_ROOT_PASSWORDの内容をパスワードをして入力
Enter password:

mysqlからログアウト
exit

dbコンテナから退出
exit

phpコンテナに入る
docker exec -it php bash

./storageの権限を開放
chmod -R 777 ./storage

dbのテーブルを構築
php artisan migrate
```

## Laravel側で、`permission denied(権限エラー)`が出た場合、以下のコマンドを実行
```
phpのコンテナに入る
docker-compose exec php bash

./storageの権限を開放
chmod -R 777 ./storage
```
