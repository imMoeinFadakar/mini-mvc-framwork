<?php

namespace Roocket\PocketCore;

class Request
{
    public function getMethod() : string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl() : string
    {
        $url = $_SERVER['REQUEST_URI'];
        $position = strpos($url, '?');
        if($position !== false) {
            $url = substr($url, 0, $position);
        }

        return $url;
    }

    public function isGet() : bool
    {
        return $this->getMethod() === 'get';
    }

    public function isPost() : bool
    {
        return $this->getMethod() === 'post';
    }

    public function all() : array
    {
        $data = [];
        if($this->isGet()) {
            $data = filter_input_array(INPUT_GET , FILTER_SANITIZE_SPECIAL_CHARS);
        }

        if($this->isPost()) {
            $data = filter_input_array(INPUT_POST , FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $data;
    }

    public function query(string $key) : ?string
    {
        return filter_input(INPUT_GET , $key , FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    }

    public function input(string $key) : ?string
    {
        return $this->all()[$key] ?? null;
    }
}
