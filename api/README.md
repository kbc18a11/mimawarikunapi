# APIドキュメント

### ログイン
URI：`http://localhost:8085/api/login`
#### リクエストボディ
```
{
    email:メールアドレス,
    password:パスワード
}
```
#### レスポンス
##### 正常時
```
{
    "id": id番号,
    "name": ユーザー名,
    "email": メールアドレス,
    "email_verified_at": null,
    "api_token": 認証トークン,
    "created_at": "2021-01-25T01:28:18.000000Z",
    "updated_at": "2021-01-25T01:28:18.000000Z",
    "profile_photo_url": "https://ui-avatars.com/api/?name=a&color=7F9CF5&background=EBF4FF"
}
```
##### エラー時
```
リクエストボディが不足してる場合
{
    "result": false,
    "error": {
        "email": [
            "必須項目です。"
        ],
        "password": [
            "必須項目です。"
        ]
    }
}
パスワードやメールアドレスが違う場合
{
    "error": "メールアドレスとパスワードが違います"
}
```
