<?php

namespace App\Core;

use App\Core\Application;
use App\Core\Request;

abstract class Validator
{
    public array $errors = [];
    private $rulesMessages;

    public function __construct() {
        include_once Application::$ROOT_DIR . '/config/rulesmessages.php';
        $this->rulesMessages = $rulesMessages;
    }

    public function validate(Request $request): array|Validator
    {
        $this->errors = [];
        foreach ($this->rules() as $field => $rules)
        {
            $value = $request->body()[$field];
            $rules = array_reverse($rules);
            foreach ($rules as $rule)
            {
                if (!is_string($rule))
                {
                    foreach($rule as $nestedRule)
                    {
                        $this->addErrors($nestedRule, $field, $value);
                    }
                }
                else
                {
                    $this->addErrors($rule, $field, $value);
                }
            }
        }
        if($this->errors !== [])
        {
            foreach($this->errors as $key => $error)
            {
                $field = ucwords($key); 
                $this->errors[$key] = "$field invalid - $error";
            }
            return $this;
        }
        return $request->body();
    }

    protected abstract function rules(): array;

    private function addErrors(string $rule, $field, $value)
    {
        if (str_contains($rule, ':'))
        {
            $rule = explode(':', $rule);

            if ($rule[0] === 'min' && strlen($value) < $rule[1])
            {
                $this->errors[$field] = str_replace('x', $rule[1], $this->rulesMessages['min']);
            }
            if ($rule[0] === 'max' && strlen($value) > $rule[1])
            {
                $this->errors[$field] = str_replace('x', $rule[1], $this->rulesMessages['max']);
            }
        }
        else
        {
            if($rule === 'required' && ($value === null || $value === "") && $rule !== 'nullable')
            {
                $this->errors[$field] = $this->rulesMessages['required'];
            }
            if($rule === 'lowercase' && $value !== strtolower($value))
            {
                $this->errors[$field] = $this->rulesMessages['lowercase'];
            }
            if($rule === 'uppercase' && $value !== strtoupper($value))
            {
                $this->errors[$field] = $this->rulesMessages['uppercase'];
            }
            if($rule === 'prohibited' && ($value !== null || $value !== ""))
            {
                $this->errors[$field] = $this->rulesMessages['prohibited'];
            }
            if($rule === 'string' && !is_string($value))
            {
                $this->errors[$field] = $this->rulesMessages['string'];
            }
            if($rule === 'url' && !filter_var($value, FILTER_VALIDATE_URL))
            {
                $this->errors[$field] = $this->rulesMessages['url'];
            }
            if($rule === 'phone' && !preg_match('/^\+?\d{1}[ -]?\(?\d{3}\)?[ -]?\d{2}[ -]?\d{2}[ -]?\d{3}$/', $value))
            {
                $this->errors[$field] = $this->rulesMessages['phone'];
            }
        }
    }
}