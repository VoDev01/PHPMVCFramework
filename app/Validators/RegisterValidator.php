<?php

namespace App\Validators;

use App\Core\Validator;

class RegisterValidator extends Validator
{
    protected function rules() : array
    {
        return [
            'name' => ['required', 'min:3', 'max:25'],
            'surname' => ['max:25'],
            'email' => ['required', 'min:10', 'max:40'],
            'password' => ['required', 'min:10', 'max:30']
        ];
    }
}