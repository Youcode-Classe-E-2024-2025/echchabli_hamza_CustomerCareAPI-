<?php

use App\Models\User;

class UserRepo {


    private $userModel;

   public function __construct(User $userModel) {

    $this->userModel = $userModel;
        
    }
 
    public function createUser($data) {
        return $this->userModel->create($data);
    }


}