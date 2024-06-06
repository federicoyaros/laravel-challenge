<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    public function validate($request, $rules)
    {
        return Validator::make($request->all(), $rules);          
    }
}