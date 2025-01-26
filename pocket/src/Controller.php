<?php

namespace Roocket\PocketCore;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class Controller
{
    protected function validate(array $data , array $rules , array $messages = []) : Validation
    {
        $validator = new Validator($messages);

        // make it
        $validation = $validator->make($data , $rules);

        // then validate
        $validation->validate();

        return $validation;
    }

    protected function render(string $view ,array $data = [])
    {
        return (new View)->render($view , $data);
    }
}
