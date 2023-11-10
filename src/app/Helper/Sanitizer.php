<?php

namespace App\Helper;

class Sanitizer
{
    static function sanitize(string $data)
    {
        $sanitizer = new \Elegant\Sanitizer\Sanitizer(["data" => $data], ["data" => "trim|strip_tags|escape"]);
        return $sanitizer->sanitize()['data'];
    }
}