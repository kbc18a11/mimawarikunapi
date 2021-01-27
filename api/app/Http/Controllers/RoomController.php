<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        //何件取得するかの指定がされてないか？
        if (empty($request->limit)) {
            # code...
            return response()->json(Room::where('user_id', $user->id)->orderBy('name', 'asc')->paginate(5));
        }
        return response()->json(Room::where('user_id', $user->id)->orderBy('name', 'asc')->paginate($request->limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        //バリデーションの検証
        $validationResult = Room::createValidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        $createData = [
            'user_id' => $user->id,
            'name' => $request->name,
            'class' => $request->class
        ];

        return response()->json(Room::create($createData));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        $room = Room::find($id);

        //$idで指定された部屋が存在しているか？ || 部屋のuser_idとログインユーザーのidが一致しているか？
        if (empty($room) || $room->user_id !== $user->id) {
            return response()->json(['error' => '存在しない部屋です'], 422);
        }

        return response()->json($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        //バリデーションの検証
        $validationResult = Room::createValidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
