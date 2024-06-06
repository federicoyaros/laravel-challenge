<?php

namespace App\Interfaces;

interface GifValidatorInterface
{
    public function validateGetGifs($request);
   
    public function validateCreateFavourite($request);
}