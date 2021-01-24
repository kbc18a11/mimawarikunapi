<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    private static $ruleMessages = [
        'required' => '必須項目です。',
        'max' => '255文字以下入力してください',
        'min' => '8文字以上入力してください',
        'unique' => '既にほかのユーザーが利用しています',
        'email' => 'メールアドレスを入力してください',
        'confirmed' => 'パスワードの確認入力が一致しません',
        'image' => '画像を指定してください'
    ];

    /**
     * UserController@storeのバリデーション
     * 
     * @param array $input
     * @return array
     */
    public static function createValidator(array $input = [])
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }

    /**
     * UserController@storeのバリデーション
     * 
     * @param array $input
     * @return array
     */
    public static function loginValidator(array $input = [])
    {
        $rules = [
            'email' => ['required'],
            'password' => ['required'],
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }

    public function indexValidator(array $input = [])
    {
        # code...
    }

    public static function login(string $email, string $password)
    {
        $userData = self::where('email', $email)->first();

        if (!$userData || !Hash::check($password, $userData->password)) {
            return false;
        }
        return true;
    }


    public static function loginCheck($token)
    {
        if (self::where('api_token', $token)->first()) {
            return true;
        }
        return false;
    }

    public static function findUserDataByEmail(string $email)
    {
        return self::where('email', $email)->first();
    }

    public static function findIdByToken($token)
    {
        return self::where('api_token', $token)->first()->id;
    }
}
