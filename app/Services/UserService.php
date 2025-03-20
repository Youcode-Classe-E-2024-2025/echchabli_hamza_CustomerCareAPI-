<?php

namespace App\Services;

use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;


class UserService {

    protected $userRepository;

    public function __construct(UserRepo $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $userData)
    {
        try {
            $userData['password'] = bcrypt($userData['password']);
    
       
            $user = $this->userRepository->createUser($userData);
    
             return $user;

        } catch (\Exception $e) {
           

            return [
                'success' => false,
                'message' => 'Failed to register user. Please try again.',
                'error' => $e->getMessage(), 
            ];
        }
    }


  

    public function login(array $userData)
    {
        try {
           
            if (Auth::attempt($userData)) {


                $user = Auth::user();
    
                $token = $user->createToken('authToken')->plainTextToken;
    
                return [
                    'success' => true,
                      'token' => $token,
                    'user' => $user,
                ];
            }
    
            return [
                'success' => false,
                'message' => 'Invalid credentials.',
            ];

        } catch (\Exception $e) {
        
            return [
                'success' => false,
                'message' => 'Failed to log in. Please try again.',
                'error' => $e->getMessage(),
            ];
        }
    }
    

    public function logout()
{
    try {
      
        $user = Auth::user();

       
        $user->currentAccessToken()->delete();

        return [
            'success' => true,
            'message' => 'User logged out successfully.',
        ];
    } catch (\Exception $e) {
        
        return [
            'success' => false,
            'message' => 'Failed to log out. Please try again.',
            'error' => $e->getMessage(),
        ];
    }
}



}