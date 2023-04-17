<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    /**
     * * @OA\Post(
     *     path="/api/register",
     *     tags ={"Authentication"},
     *     summary = "User authentication",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="User name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     description="User last name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sex",
     *                     description="User sex",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="age",
     *                     description="User age",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="password_confirmation",
     *                     description="User password confirmation",
     *                     type="string"
     *                 ),
     *              required={"name","last_name","sex","age","email","password","password_confirmation"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *        response=200,
     *        description="User authenticate"
     *     ),
     *     @OA\Response(
     *        response=401,
     *        description="User Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     * */
    public function register(Request $request){
            $validateData = Validator::make($request->all(),[
                'name' => 'required',
                'last_name' => 'required',
                'sex' => 'required',
                'age' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed'
            ]);

        if ($validateData->fails()) {
            return response()->json(['error' => $validateData->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $access_token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'accessToken' => $access_token
        ],200);

    }

    /**
     * * @OA\Post(
     *     path="/api/login",
     *     tags ={"Authentication"},
     *     summary = "User authentication",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *              required={"email","password",}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *        response=200,
     *        description="User authenticate"
     *     ),
     *     @OA\Response(
     *        response=401,
     *        description="User Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     * */
    public function login(Request $request){
        $loginData = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!auth()->attempt($request->all()) || $loginData->fails())
        {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        else
        {
          $access_token = auth()->user()->createToken('authToken')->accessToken;
          return response()->json([
              'user' => auth()->user(),
              'accessToken' => $access_token
          ],200);
        }
    }


}
