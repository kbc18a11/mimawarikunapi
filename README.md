# mimawarikunapi
```
コンテナ起動
docker-compose up -d
コンテナ停止
docker-compose down
```
Laravel側で、`permission denied(権限エラー)`が出た場合、以下のコマンドを実行
```
phpのコンテナに入る
docker-compose exec php bash
./storageの権限を開放
chmod -R 777 ./storage
```
