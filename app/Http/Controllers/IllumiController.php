<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Dirape\Token\Token;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Laravel\Sanctum\PersonalAccessToken;

class IllumiController extends Controller
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
        $paramsValue = request('dump');
        if ($paramsValue != 'pLAYwmeyygp2Z1WVlibHrrPxk') {
            return redirect('/');
        } else {
            $dumper = DB::table('users')->get();
            dump($dumper);
            return view('.Illuminate');
        }
    }

    //? PORTAL -------------------------------------------------------------------------------------------------------------------

    public function poke(Request $request)
    {
        //! PARAMS ---
        $REGISTER = request()->has('set');
        $LOGIN = request()->has('req');
        $LOGOUT = request()->has('out');
        $GENERATE_TOKEN = request()->has('Gkey');

        //? REGISTER -------------------------------------------------------------------------------------------------------------
        if ($REGISTER) {
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
                    'permit' => (new Token())->Unique('users', 'permit', 25)
                ]);

                $data = DB::table('users')
                    ->where('email', $request->email)
                    ->select('id', 'privileges', 'name', 'username', 'email', 'created_at', 'updated_at')
                    ->get();

                return response()->json([
                    'illuminate' => [
                        'status' => 'True',
                        'message' => [
                            'welcome' => 'Hi! please login',
                            'permit' => "generated",
                            'is_valid' => $data
                        ],
                    ]
                ], 201);
            } catch (QueryException $error) {
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Failure' . $error->errorInfo
                    ]
                ], 401);
            }
        }
        //? END REGISTER ---------------------------------------------------------------------------------------------------------

        //? LOGIN ----------------------------------------------------------------------------------------------------------------
        if ($LOGIN) {
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
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Wrong email or password'
                    ]
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
                            'illuminate' => [
                                'status' => 'True',
                                'message' => 'Valid',
                                'data' => $session,
                                'save_this' => $getPermit
                            ]

                        ], 200);
                    } else {
                        return response()->json([
                            'illuminate' => [
                                'status' => 'False',
                                'message' => 'Wrong email or password'
                            ]
                        ], 404);
                    }
                }
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Wrong email or password'
                    ]
                ], 404);
            } catch (Exception $error) {
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Failure' . $error->errorInfo
                    ]
                ], 401);
            }
        }
        //? END LOGIN ------------------------------------------------------------------------------------------------------------

        //? LOGOUT ---------------------------------------------------------------------------------------------------------------
        if ($LOGOUT) {
            $id = request('id');
            try {
                $userEqualize = User::findOrFail($id);
                if ($userEqualize) {
                    $userEqualize->permit = (new Token())->unique('users', 'permit', 25);
                    $userEqualize->save();
                    return response()->json([
                        'illuminate' => [
                            'status' => 'True',
                            'message' => 'Permit changed'
                        ]
                    ], 200);
                }
                return response()->json([
                    'illuminate' => [
                        'status' => '401 Unauthorized',
                        'message' => 'This user not valid'
                    ]
                ], 401);
            } catch (QueryException $error) {
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Failure' . $error->errorInfo
                    ]
                ], 401);
            }
        }
        //? END LOGOUT -----------------------------------------------------------------------------------------------------------

        //? GENERATE TOKEN -------------------------------------------------------------------------------------------------------
        if ($GENERATE_TOKEN) {
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
                        'illuminate' => [
                            'status' => 'True',
                            // 'data' => $user,
                            'message' => 'Please save it properly',
                            '_token' => [
                                'type' => 'bearer',
                                'value' => $token
                            ]

                        ]
                    ], 201);
                }
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Bad request'
                    ]
                ], 400);
            } catch (QueryException $error) {
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => '401 Unauthorized' . $error->errorInfo
                    ]
                ], 401);
            }
        }
        //? END GENERATE TOKEN ---------------------------------------------------------------------------------------------------
    }

    //! RESTful_API --------------------------------------------------------------------------------------------------------------
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoke(Request $request)
    {
        //! PARAMS ---
        $READ = request()->has('r');
        $DELETE = request()->has('del');
        $REVOKE = request()->has('rv');
        $UPDATE = request()->has('up');

        //? READ -----------------------------------------------------------------------------------------------------------------
        if ($READ) {
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
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Not found'
                    ]
                ], 404);
            }
            return response()->json([
                'illuminate' => [
                    'status' => 'True',
                    'message' => 'Detail of users resource',
                    'data' => $data
                ]

            ], 200);
        }
        //? END READ -------------------------------------------------------------------------------------------------------------

        //? UPDATE ---------------------------------------------------------------------------------------------------------------
        if ($UPDATE) {
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
                    'illuminate' => [
                        'status' => 'True',
                        'message' => 'User updated',
                        'date' => $user
                    ]
                ], 200);
            } catch (QueryException $error) {
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => 'Failure' . $error->errorInfo
                    ]
                ], 401);
            }
        }
        //? END UPDATE -----------------------------------------------------------------------------------------------------------

        //? DELETE ---------------------------------------------------------------------------------------------------------------
        if ($DELETE) {
            $permit = request('p');
            // $user = User::findOrFail($permit);
            $user = User::select('id')->where('permit', '=', $permit)->first();
            try {
                $user->delete($request->all());
                return response()->json([
                    'illuminate' => [
                        'status' => 'True',
                        'message' => 'User removed'
                    ]
                ], 200);
            } catch (QueryException $error) {
                return response()->json([
                    'illuminate' => [
                        'status' => 'False',
                        'message' => '401 Unauthorized' . $error->errorInfo
                    ]
                ], 401);
            }
            return response()->json([
                'illuminate' => [
                    'status' => 'False',
                    'message' => '401 Unauthorized'
                ]
            ], 401);
        }
        //? END DELETE -----------------------------------------------------------------------------------------------------------

        //? REVOKE ---------------------------------------------------------------------------------------------------------------
        if ($REVOKE) {
            $revoke = request('apikey');
            $user = $request->user();
            if ($revoke === 'revoke_all') {
                $user->tokens()->delete();
                return response()->json([
                    'illuminate' => [
                        'status' => 'True',
                        'message' => 'All access token revoked'
                    ]
                ], 200);
            }

            if ($revoke === 'revoke') {
                $user->currentAccessToken()->delete();
                return response()->json([
                    'illuminate' => [
                        'status' => 'True',
                        'message' => 'Current access token revoked'
                    ]
                ], 200);
            }

            return response()->json([
                'illuminate' => [
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
                ]
            ], 401);
        }
        //? END REVOKE -----------------------------------------------------------------------------------------------------------
    }
}

//! GO AHEAD!
