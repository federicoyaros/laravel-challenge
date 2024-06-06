<?php

namespace App\Interfaces;

interface AuthValidatorInterface
{
    public function validateLogin($request);   

    public function validateRegister($request);
}