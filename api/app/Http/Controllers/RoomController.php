<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use App\Models\Camera;

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

    public function state(Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        //何件取得するかの指定がされてないか？
        if (empty($request->limit)) {
            # code...
            return response()->json(Room::state($user->id));
        }
        return response()->json(Room::state($user->id, $request->limit));
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
                'roomError' => $validationResult->messages()
            ], 422);
        }


        //カメラの情報は、存在するか？
        if (!empty($request->cameras)) {
            //カメラのバリデーション
            $camerasError = [
                'result' => false,
                'errors' => []
            ];

            foreach ($request->cameras as $cameraData) {
                //バリデーションの検証
                $validationResult = Camera::afterRoomCreateValidator($cameraData);

                //バリデーションの結果が駄目か？
                if ($validationResult->fails()) {
                    $errorMessage = $validationResult->messages();
                    array_push($camerasError['errors'], ['id' => $cameraData['id'], 'error' => $errorMessage]);
                }
            }

            //エラーは存在するか？
            if (count($camerasError['errors'])) {
                return response()->json($camerasError, 422);
            }
        }

        //部屋の作成
        $createRoomData = [
            'user_id' => $user->id,
            'name' => $request->name,
            'class' => $request->class
        ];
        $roomData = Room::create($createRoomData);

        //カメラの情報は、存在するか？
        if (!empty($request->cameras)) {
            //カメラの作成
            foreach ($request->cameras as $createCameraData) {
                $createCameraData['user_id'] = $user->id;
                $createCameraData['room_id'] = $roomData->id;

                Camera::create($createCameraData);
            }
        }

        return response()->json(['result' => true]);
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

        $room['cameras'] = Camera::where('room_id', $id)->get();

        return response()->json($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        //バリデーションの検証
        $validationResult = Room::updateValidator([
            'id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'class' => $request->class
        ]);

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        $room = Room::find($id);

        if ($room->user_id !== $user->id) {
            return response()->json([
                'result' => false,
                'error' => [
                    'id' => '編集できない部屋です'
                ]
            ], 422);
        }

        //カメラの情報は、存在するか？
        if (!empty($request->cameras)) {
            //カメラのバリデーション
            $camerasError = [
                'result' => false,
                'errors' => []
            ];

            foreach ($request->cameras as $cameraData) {
                $cameraData['id'] = $id;
                $cameraData['room_id'] = $id;

                //バリデーションの検証
                $validationResult = Camera::updateValidator($cameraData);

                //バリデーションの結果が駄目か？
                if ($validationResult->fails()) {
                    $errorMessage = $validationResult->messages();
                    array_push($camerasError['errors'], ['id' => $cameraData['id'], 'error' => $errorMessage]);
                }
            }

            //エラーは存在するか？
            if (count($camerasError['errors'])) {
                return response()->json($camerasError, 422);
            }
        }

        //カメラの情報は、存在するか？
        if (!empty($request->cameras)) {
            //カメラの作成
            foreach ($request->cameras as $updateCameraData) {
                $createCameraData['user_id'] = $user->id;
                $createCameraData['room_id'] = $room->id;

                $camera = Camera::find($updateCameraData['id']);
                $camera->update($updateCameraData);
            }
        }

        $room->update($request->all());
        return response()->json(['result' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = User::find(User::findIdByToken($request->header('token')));
        if (!$user) {
            return response()->json(['error' => 'ログインしてないユーザです'], 401);
        }

        //バリデーションの検証
        $validationResult = Room::deleteValidator(['id' => $id]);

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'result' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        $room = Room::find($id);


        if ($room->user_id !== $user->id) {
            return response()->json([
                'result' => false,
                'error' => [
                    'id' => '編集できない部屋です'
                ]
            ], 422);
        }

        Camera::where('room_id', $id)->delete();

        return response()->json($room->delete());
    }
}
