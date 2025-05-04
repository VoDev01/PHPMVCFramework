<?php

namespace App\Validators;

use App\Core\Validator;

class RegisterValidator extends Validator
{
    public function rules() : array
    {
        return [
            'name' => ['min:3', 'max:25'],
            'surname' => ['min:3', 'max:25'],
            'email' => ['min:10', 'max:40'],
            'password' => ['min:10', 'max:30']
        ];
    }
}