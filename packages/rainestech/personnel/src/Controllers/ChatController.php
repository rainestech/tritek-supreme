<?php


namespace Rainestech\Personnel\Controllers;

use Rainestech\AdminApi\Controllers\BaseApiController;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\Personnel\Entity\Friends;
use Rainestech\Personnel\Requests\ChatFriendRequest;

class ChatController extends BaseApiController
{
    public function friends() {
        $resp = [];
        $friends = Friends::where('uId', auth('api')->id())->get();

        foreach ($friends as $friend) {
            $data = new \stdClass();
            $data->id = $friend->friend->id;
            $data->name = $friend->friend->name;
            $data->avatar = $friend->friend->passport ? $friend->friend->passport->link : '';

            $resp[] = $data;
        }

        return response()->json($resp);
    }

    public function saveFriends(ChatFriendRequest $request) {
        $friends = new Friends();
        $friends->uId = auth('api')->id();
        $friends->fId = $request->input('fId');
        $friends->save();

        return response()->json($friends);
    }

    public function contacts() {
        $resp = [];
        foreach (Users::all() as $friend) {
            $data = new \stdClass();
            $data->id = $friend->id;
            $data->name = $friend->name;
            $data->avatar = $friend->passport ? $friend->friend->passport->link : '';

            $resp[] = $data;
        }

//        'id' => $friend->id,
//            'name' => $friend->name,
//            'avatar' => $friend->passport ? $friend->friend->passport->link : '' ];

        return response()->json($resp);
    }
}
