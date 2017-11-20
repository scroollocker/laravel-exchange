<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function userList() {
        return view('admin.user-list');
    }

    public function currencyList() {
        return view('admin.currency-list');
    }

    public function settingsList() {
        return view('admin.settings-list');
    }

    public function getUserList() {
        $users = User::where('isAdmin', '<>', '1')->get();

        return response()->json(array(
            'status' => true,
            'users' => $users->toArray()
        ));
    }

    public function blockUser(Request $request) {
        $userId = $request->user_id;
        $blockUser = $request->block_user;
        try {
            $user = User::where('id', $userId)->first();
            if (is_null($user)) {
                throw new Exception('Пользователь не наден');
            }
            $user->blocked = $blockUser;
            $user->save();
            return response()->json(array(
                'status' => true
            ));
        }
        catch(Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

}
