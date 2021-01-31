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
        'unique' => '既にほかの部屋が存在しています',
        'exists' => '存在しない部屋です'
    ];

    public static function createValidator(array $input = [])
    {
        # code...
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:rooms'],
            'class' => ['required', 'string', 'max:255']
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }

    public static function updateValidator(array $input = [])
    {
        # code...
        $rules = [
            'id' => ['required', 'exists:rooms'],
            'name' => ['required', 'string', 'max:255',],
            'class' => ['required', 'string', 'max:255']
        ];

        $room = self::find($input['id']);
        //$idで指定された部屋が存在しているか？ && $idで指定された部屋の名前と指定された名前が一緒か？
        if ($room && $room->name !== $input['name']) {
            array_push($rules['name'], 'unique:rooms');
        }

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }

    public static function deleteValidator(array $input = [])
    {
        # code...
        $rules = [
            'id' => ['required', 'exists:rooms'],
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }
}
