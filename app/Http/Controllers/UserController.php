<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    public function login() {
        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (Auth::attempt($credentials)) {
            return response()->json(['success' => $this->successResponse(User::find(Auth::id()))]);
        }

        return response()->json([
            'error' => 'Unauthorised',
            'message' => 'Wrong email or password.'
        ], 401);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        return response()->json(['success' => $this->successResponse($user)]);
    }

    public function getDetails() {
        return response()->json(['success' => Auth::user()]);
    }

    public function getBookings(): \Illuminate\Support\Collection {
        $bookings = DB::table('bookings')
                      ->join('meeting_rooms', 'bookings.room_id', '=', 'meeting_rooms.id')
                      ->select('bookings.*', 'meeting_rooms.name')
                      ->where('user_id', '=', Auth::id())
                      ->where('end_at', '>=', date('Y-m-d H:i:s'))
                      ->orderBy('start_at')
                      ->get();
        return $bookings->groupBy('name');
    }

    private function successResponse(User $user) {
        $freshToken = $user->createToken('MyApp');
        $success['user'] = $user;
        $success['token'] = $freshToken->accessToken;
        $success['expiresAt'] = $freshToken->token->expires_at;

        return $success;
    }
}
