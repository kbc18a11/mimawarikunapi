<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Room extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'class',
    ];

    private static $ruleMessages = [
        'required' => '必須項目です。',
        'max' => '255文字以下入力してください',
        'unique' => '既にほかのカメラが存在しています',
    ];

    public static function createValidator(array $input = [])
    {
        # code...
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:rooms'],
            'class' => ['required', 'string', 'max:255']
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages, self::$ruleMessages);
    }

    public static function updateValidator(array $input = [])
    {
        # code...
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:rooms'],
            'class' => ['required', 'string', 'max:255']
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages, self::$ruleMessages);
    }
}
