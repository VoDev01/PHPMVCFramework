<?php

namespace App\Core;

class Model
{
    public function __construct(public Database $database)
    {
        
    }
    public function loadData($data)
    {
        foreach($data as $key => $value)
        {
            if(property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
        }
    }
}