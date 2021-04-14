<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Dirape\Token\Token;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class IlluminateController extends Controller
{
    //! WEB ----------------------------------------------------------------------------------------------------------------------
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function illuminate()
    {
        return view('.Illuminate');
    }

    public function illumi()
    {
        $dumper = DB::table('users')->get();
        dump($dumper);
        return view('.Illuminate');
    }

    //! RESTful_API --------------------------------------------------------------------------------------------------------------
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //? REGISTER ---
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:15',
            'username' => 'required|min:5|max:15|unique:users,username',
            'email' => 'required|email:rfc,dns,strict|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->confirm_password),
                'permit' => (new Token())->Unique('users', 'permit', 15)
            ]);

            $data = DB::table('users')
                ->where('email', $request->email)
                ->select('id', 'privileges', 'name', 'username', 'email', 'created_at', 'updated_at')
                ->get();

            return response()->json([
                'status' => 'True',
                'message' => [
                    'welcome' => 'Hi! please login',
                    'permit' => "generated"
                ],
                'is_valid' => $data
            ], 201);
        } catch (QueryException $error) {
            return response()->json([
                'status' => 'False',
                'message' => 'Failure' . $error->errorInfo
            ], 401);
        }
    }

    //? LOGIN ---
    public function login(Request $request)
    {
        $permit = request('wp');
        $wpEqualize = User::select('password')->where('email', '=', $request->email)->first();
        if (password_verify($permit, $wpEqualize->password)) {
            $getPermit = User::select('permit')->where('email', '=', $request->email)->first();
        }
        if (!password_verify($permit, $wpEqualize->password)) {
            $getPermit = null;
        }

        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'False',
                'message' => 'Wrong email or password'
            ], 401);
        }

        try {
            $equalizeEmail = User::selectRaw("Count(*) as result")->where('email', '=', $request->email)->first();

            if (intval($equalizeEmail->result) > 0) {
                $equalizePassword = User::select('password')->where('email', '=', $request->email)->first();
                if (password_verify($request->password, $equalizePassword->password)) {
                    // $session = User::where('email', $request->email)->get();
                    $session = DB::table('users')
                        ->where('email', $request->email)
                        ->select('id', 'privileges', 'name', 'username', 'email', 'created_at', 'updated_at')
                        ->get();
                    return response()->json([
                        'status' => 'True',
                        'message' => 'User is valid',
                        'session' => $session,
                        "data" => $getPermit
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'False',
                        'message' => 'Wrong email or password'
                    ], 404);
                }
            }
            return response()->json([
                'status' => 'False',
                'message' => 'Wrong email or password'
            ], 404);
        } catch (Exception $error) {
            return response()->json([
                'status' => 'False',
                'message' => $error
            ], 401);
        }
    }

    //? UPDATE --
    public function update(Request $request)
    {
        $permit = request('p');
        $user = User::select('id')->where('permit', '=', $permit)->first();
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:20',
            'username' => 'required|min:5|max:20|unique:users,username',
            'email' => 'required|email:rfc,dns,strict|unique:users,email'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        try {
            $user->update($request->all());
            return response()->json([
                'status' => 'True',
                'message' => 'user updated',
                'date' => $user
            ], 200);
        } catch (QueryException $error) {
            return response()->json([
                'status' => 'False',
                'message' => 'Failure' . $error->errorInfo
            ], 401);
        }
    }

    //? READ ---
    public function show()
    {
        $user = request('p');
        // $data = User::findOrFail($user);
        $data = User::select(
            'id',
            'privileges',
            'name',
            'username',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        )->where('permit', '=', $user)->first();
        if ($data == null) {
            return response()->json([
                'status' => 'False',
                'message' => 'what are you looking for?'
            ], 404);
        }
        return response()->json([
            'status' => 'True',
            'message' => 'Detail of users resource',
            'data' => $data
        ], 200);
    }

    //? DELETE
    public function destroy(Request $request)
    {
        $permit = request('p');
        // $user = User::findOrFail($permit);
        $user = User::select('id')->where('permit', '=', $permit)->first();
        try {
            $user->delete($request->all());
            return response()->json([
                'status' => 'True',
                'message' => 'user removed'
            ], 200);
        } catch (QueryException $error) {
            return response()->json([
                'status' => 'False',
                'message' => '401 Unauthorized' . $error->errorInfo
            ], 401);
        }
        return response()->json([
            'status' => 'False',
            'message' => '401 Unauthorized'
        ], 401);
    }

    //? GENERATE TOKEN
    public function generate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required|string|min:5|max:15',
            'password' => 'required|min:5'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 401);
        }

        try {
            $equalizePassword = User::select('password')->where('username', '=', $request->username)->first();
            if (password_verify($request->password, $equalizePassword->password)) {
                $user = User::where('username', $request->username)->first();
                $token = $user->createToken('apikey')->plainTextToken;
                return response()->json([
                    'status' => 'True',
                    // 'data' => $user,
                    '_token' => [
                        'message' => 'please save it properly',
                        'type' => 'bearer',
                        'value' => $token
                    ]
                ], 201);
            }
            return response()->json([
                'status' => 'False',
                'message' => 'bad request'
            ], 400);
        } catch (QueryException $error) {
            return response()->json([
                'status' => 'False',
                'message' => '401 Unauthorized' . $error->errorInfo
            ], 401);
        }
    }

    public function revoke(Request $request)
    {
        $revoke = request('apikey');
        $user = $request->user();
        if ($revoke === 'revoke_all') {
            $user->tokens()->delete();
            return response()->json([
                'status' => 'True',
                'message' => 'All access token revoked'
            ], 200);
        }

        if ($revoke === 'revoke') {
            $user->currentAccessToken()->delete();
            return response()->json([
                'status' => 'True',
                'message' => 'Current access token revoked'
            ], 200);
        }

        return response()->json([
            'status' => 'False',
            'message' => "Enter this parameter to confirm",
            'params' => [
                'revoke_current_token' => [
                    'key' => 'apikey',
                    'value' => 'revoke',
                ],
                'revoke_all' => [
                    'key' => 'apikey',
                    'value' => 'revoke_all',
                ]
            ]
        ], 401);
    }
}
