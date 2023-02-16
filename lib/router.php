<?php

namespace Lib;
use Lib\App;
class Router {
    public static function get($route, $callback) {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            return;
        }
        self::on($route, $callback);
    }
    public static bool $match = false;
    public static function post($route, $callback) {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            return;
        }
        self::on($route, $callback);
    }
    public static function on($regex, $cb) {
        $params = explode("?", $_SERVER['REQUEST_URI'])[0];
        // $params = $_SERVER['REQUEST_URI'];
        $params = (stripos($params, "/") !== 0) ? "/" . $params : $params;
        $regex = str_replace('/', '\/', $regex);
        self::$match = preg_match('/^' . ($regex) . '$/', $params, $matches, PREG_OFFSET_CAPTURE);
        if (self::$match) {
            // first value is normally the route, lets remove it
            array_shift($matches);
            // Get the matches as parameters
            $params = array_map(function ($param) {
                return $param[0];
            }, $matches);
            $params = array_merge($params, $_GET);
            $app = new App();
            $app->handler($cb, new Request($params), new Response());
            // $cb(new Request($params), new Response());
        }
    }
}
