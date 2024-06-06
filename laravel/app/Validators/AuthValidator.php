<?php

namespace App\Validators;

use App\Interfaces\AuthValidatorInterface;

class AuthValidator extends BaseValidator implements AuthValidatorInterface
{
    public function validateLogin($request)
    {
        return $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    public function validateRegister($request)
    {
        return $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
        ]);
    }
}