<?php

namespace App\Core;

use App\Core\Application;
use App\Core\Request;

require_once Application::$ROOT_DIR . '/config/rulesmessages.php';

abstract class Validator
{
    public array $errors;

    public function validate(Request $request)
    {
        foreach ($this->rules() as $field => $rules)
        {
            $value = $request->body()[$field];
            foreach ($rules as $rule)
            {
                $ruleName = $rule;
                if (!is_string($ruleName))
                {
                    $ruleName = $rule[0];
                }
                if (str_contains($ruleName, ':'))
                {
                    $ruleName = explode(':', $ruleName);

                    if ($ruleName[0] === 'min' && strlen($value) < $ruleName[1])
                    {
                        $errors[$field] = $GLOBALS['rulesMessages']['min'];
                    }
                    if ($ruleName[0] === 'max' && strlen($value) > $ruleName[1])
                    {
                        $errors[$field] = $GLOBALS['rulesMessages']['max'];
                    }
                }
            }
        }
    }

    public abstract function rules(): array;
}
