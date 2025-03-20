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



    public function register(RegisterRequest $request){
        $userData = $request->validated();
        $user = $this->userService->registerUser($userData);
        
        
        if ($user) {
            
            return response()->json([
                'message' => 'User registered successfully', 
                'user' => $user
            ], 201);
        }

        return response()->json([
            'message' => 'User registration failed. Email exists.'
        
        
        ], 422);
    }



    public function login(LoginRequest $request){
        
             $userData = $request->validated();

          $result = $this->userService->login($userData);

        return response()->json($result, $result['success'] ? 200 : 401);


    }



    public function logout(){
    
        $result = $this->userService->logout();

        // Return the response
        return response()->json($result, $result['success'] ? 200 : 500);
    }









 }