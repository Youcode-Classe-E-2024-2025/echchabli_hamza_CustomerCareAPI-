<?php

namespace App\Http\Controllers;

 use App\Services\UserService;
 use App\Http\Requests\RegisterRequest;
 use App\Http\Requests\LoginRequest;


 class AuthController extends Controller{


    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *             @OA\Property(property="role", type="string", example="client")
 *         )
 *     ),
 *     @OA\Response(response=201, description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object"),
 *             @OA\Property(property="token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */



    public function register(RegisterRequest $request){
        $userData = $request->validated();
        $user = $this->userService->registerUser($userData);
        
        
        if ($user) {
            $token = $user->createToken('YourAppName')->plainTextToken;
            return response()->json([
                'message' => 'User registered successfully', 
                'user' => $user ,
                'token' => $token
            ], 201);
        }

        return response()->json([
            'message' => 'User registration failed. Email exists.'
        
        
        ], 422);
    }

/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Login user",
 *     tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object"),
 *             @OA\Property(property="token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */

 public function login(LoginRequest $request)
 {
    

     $userData = $request->validated();
 
     $result = $this->userService->login($userData);
     
     if ($result['success']) {
        
         $user = $result['user']; 
         $token = $user->createToken('YourAppName')->plainTextToken;
 
         
         return response()->json([
             'message' => 'Login successful',
             'user' => $user,
             'token' => $token 
         ], 200);
     }
 
     // Return failed login response
     return response()->json($result, 401);
 }
 
/**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout user",
 *     description="Revokes all tokens for the authenticated user",
 *     operationId="logout",
 *     tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Logged out successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */

    public function logout(){
    
        $result = $this->userService->logout();

        // Return the response
        return response()->json($result, $result['success'] ? 200 : 500);
    }









 }