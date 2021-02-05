<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Camera extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'name',
        'usetype',
    ];

    private static $ruleMessages = [
        'required' => '必須項目です。',
        'max' => '255文字以下入力してください',
        'unique' => '既にほかのカメラの名前が存在しています',
        'exists' => '存在しない部屋です'
    ];


    public static function afterRoomCreateValidator(array $input = [])
    {
        # code...
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'usetype' => ['required'],
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }

    public static function createValidator(array $input = [])
    {
        # code...
        $rules = [
            'room_id' => ['required', 'exists:rooms,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('cameras')->where('room_id', $input['room_id'])],
            'usetype' => ['required'],
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }


    public static function updateValidator(array $input = [])
    {
        # code...
        $rules = [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('cameras')->ignore($input['id'])
                    ->where('room_id', $input['room_id'])
            ],
            'usetype' => ['required'],
        ];

        # code...
        return Validator::make($input, $rules, self::$ruleMessages);
    }

    public static function findCameraObjByRoomId(int $roomId)
    {
        return self::where('room_id', $roomId)->get();
    }
}
