<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    //
    public function login(Request $request)
    {
        //バリデーションの検証
        $validationResult = User::loginValidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        if (!User::login($request->email, $request->password)) {
            return response()->json(['error' => 'メールアドレスとパスワードが違います'], 401);
        }

        # code...

        return response()->json(User::findUserDataByEmail($request->email));
    }
}
