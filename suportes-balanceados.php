<?php

$pairs = function ($x) {
    return match ($x) {
        "(" => ")",
        "[" => "]",
        "{" => "}",
        default => false
    };
};

$string_param = "ahfushf(jkfsdkjf)";


$arr = str_split($string_param);

foreach ($arr as $char) {
    if ($pairs($char)) {
        
    }
}