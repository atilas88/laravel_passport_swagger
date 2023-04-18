<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder;
class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('client');
    }

    /**
     * Display a listing of the resource.
     *
     *  * @OA\Get(
     *       path="/api/users",
     *      tags ={"Users"},
     *      summary = "Show user list",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *           response=200,
     *          description="Show all tasks"
     *     ),
     *     @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     *
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'tasks' => $users
        ],200);
    }


    /**
     * Display the specified resource.
     *
     * * @OA\Get(
     *     path="/api/users/{id}",
     *     tags ={"Users"},
     *     summary = "Show user info",
     *     security={{"passport":{}}},
     *     @OA\Parameter (
     *      description = "Parameter to search the user",
     *      in = "path",
     *      name = "id",
     *      required = true,
     *      @OA\Schema (type = "integer"),
     *      @OA\Examples( example = "int", value = "1", summary = "Enter a user id ")
     *    ),
     *     @OA\Response(
     *      response = 200,
     *      description = "Show user info"
     *    ),
     *     @OA\Response(
     *      response = 404,
     *      description = "The user has not been found"
     *    ),
     *   @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *       description = "An error occurred"
     *    )
     * )
     */
    public function show($id)
    {
        $user = User::find($id);
        if(is_null($user))
        {
            return response()->json([
                'message' => 'The user has been not found',
            ],404);
        }
        else
        {
            return response()->json([
                'message' => 'User found successfully',
                'user' => $user
            ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * * * @OA\Put(
     *     path="/api/users/{id}",
     *     tags ={"Users"},
     *     summary = "Update user",
     *     security={{"passport":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema (type = "integer"),
     *         @OA\Examples( example = "int", value = "1", summary = "Enter a user id")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="sex",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="age",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Glendis",
     *                     "last_name":"Fournier Aguilera",
     *                     "sex":"femenino",
     *                     "age":"28",
     *                     "email":"gfa@gmail.com",
     *                     "password":1234567
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *        response=200,
     *        description="User authenticate"
     *     ),
     *     @OA\Response(
     *        response=401,
     *        description="User Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="User not found"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     *
     */
    public function update(Request $request, $id)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'age' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validateData->fails()) {
            return response()->json(['error' => $validateData->errors()], 401);
        }
        $user = User::find($id);
        if(is_null($user))
        {
            return response()->json([
                'message' => 'The user has been not found',
            ],404);
        }
        else
        {
            $user->update($request->all());
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * * @OA\Delete (
     *     path="/api/users/{id}",
     *     tags ={"Users"},
     *     summary = "Delete a user",
     *     security={{"passport":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *          @OA\Examples( example = "int", value = "1", summary = "Enter a user id")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User delete successfully"
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="The user has been not found"
     *      ),
     *      @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(is_null($user))
        {
            return response()->json([
                'message' => 'The user has been not found',
            ],404);
        }
        else
        {
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully',
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *       path="/api/details/ageavg",
     *      tags ={"Users"},
     *      summary = "Show user age average",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *           response=200,
     *          description="Show user age average"
     *     ),
     *     @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     */
    public function ageAvg()
    {
        $usersAge = User::all()->average('age');

        if($usersAge > 0)
        {
            return response()->json([
                'message' => 'User age average',
                'avg' => $usersAge
            ],200);
        }
        else
        {
            return response()->json([
                'message' => 'No users found',
            ],404);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *       path="/api/details/countbysex",
     *      tags ={"Users"},
     *      summary = "Show users count by sex",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *           response=200,
     *          description="Show users count by sex"
     *     ),
     *     @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     */
    public function countBySex()
    {
        $female = User::where('sex','femenino')->count();
        $male = User::where('sex','masculino')->count();

        return response()->json([
            'message' => 'Count users by sex',
            'female' => $female,
            'male' => $male
        ],200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *       path="/api/details/oldestuser",
     *      tags ={"Users"},
     *      summary = "Show oldest user",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *           response=200,
     *          description="Show oldest user"
     *     ),
     *     @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     */
    public function maxAgeUser()
    {
        /*$maxUserAge = User::all()->max('age');
        $oldest = User::where('age',$maxUserAge)->get();*/
        $oldest = User::where('age',function (Builder $query) {
            $query->selectRaw('max(u.age)')->from('users as u');
        })->get();
        return response()->json([
            'message' => 'Oldest user',
            'Oldest' => $oldest,
        ],200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *       path="/api/details/younger",
     *      tags ={"Users"},
     *      summary = "Show younger user",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *           response=200,
     *          description="Show younger user"
     *     ),
     *     @OA\Response(
     *           response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *       response = "default",
     *      description = "An error occurred"
     *    )
     * )
     */
    public function minAgeUser()
    {
        $younger = User::where('age',function (Builder $query) {
            $query->selectRaw('min(u.age)')->from('users as u');
        })->get();
        return response()->json([
            'message' => 'Younger user',
            'Younger' => $younger,
        ],200);
    }

}
