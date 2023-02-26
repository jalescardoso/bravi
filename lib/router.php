<?php

namespace Lib;
use Lib\App;
class Router {
    public static bool $notFound = true;
    public static function get($route, $callback) {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            return;
        }
        self::on($route, $callback);
    }
    public static function post($route, $callback) {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            return;
        }
        self::on($route, $callback);
    }
    public static function delete($route, $callback) {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE') !== 0) {
            return;
        }
        self::on($route, $callback);
    }
    public static function on($regex, $cb) {
        $params = explode("?", $_SERVER['REQUEST_URI'])[0];
        $params = (stripos($params, "/") !== 0) ? "/" . $params : $params;
        $regex = str_replace('/', '\/', $regex);
        $match = preg_match('/^' . ($regex) . '$/', $params, $matches, PREG_OFFSET_CAPTURE);
        if ($match) {
            self::$notFound = false;
            array_shift($matches);
            $params = array_map(function ($param) {
                return $param[0];
            }, $matches);
            $app = new App();
            $app->handle($cb, new Request($params), new Response());
        }
    }
}
