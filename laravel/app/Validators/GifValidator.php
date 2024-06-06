<?php

namespace App\Validators;

use App\Interfaces\GifValidatorInterface;

class GifValidator extends BaseValidator implements GifValidatorInterface
{
    public function validateGetGifs($request)
    {
        return $this->validate($request, [
            'query' => 'required|string',
            'offset' => 'nullable|numeric',
            'limit' => 'nullable|numeric',
        ]);
    }

    public function validateCreateFavourite($request)
    {
        return $this->validate($request, [
            'gif_id' => 'required|string',
            'alias' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);
    }
}