<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Camera;
use App\Models\Room;

class CameraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $id, Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        $createData = $request->all();
        $createData['user_id'] = $user->id;
        $createData['room_id'] = $id;

        //バリデーションの検証
        $validationResult = Camera::createValidator($createData);

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        $room = Room::find($id);

        //$idで指定された部屋が存在しているか？ || 部屋のuser_idとログインユーザーのidが一致しているか？
        if (empty($room) || $room->user_id !== $user->id) {
            return response()->json(['error' => '存在しない部屋です'], 422);
        }

        return response()->json(Camera::create($createData));
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

        return Camera::findCameraObjByRoomId($id);
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

        $camera =  Camera::find($id);
        //$idで指定された部屋が存在しているか？ || 部屋のuser_idとログインユーザーのidが一致しているか？
        if (empty($camera) || $camera->user_id !== $user->id) {
            return response()->json(['error' => '存在しないカメラです'], 422);
        }


        $updateData = $request->all();
        $updateData['user_id'] = $user->id;
        $updateData['id'] = $id;

        //バリデーションの検証
        $validationResult = Camera::updateValidator($updateData);


        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        return response()->json($camera->update($request->all()));
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
