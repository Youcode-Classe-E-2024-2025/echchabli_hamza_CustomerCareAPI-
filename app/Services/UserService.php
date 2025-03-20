<?php


use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;


class UserService {

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
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
    



}