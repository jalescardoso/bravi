<?php

namespace controller;

use Lib\{Request, Response, Factory};

class SuportesController {
    function __construct(
        private Factory $factory
    ) {
    }
    public function editAction(Request $req, Response $res) {
        $res->status(200)->renderView('suportes-balanceados.phtml');
    }
    function suporteBalanceadosAction(Request $req, Response $res) {
        $string_param = $req->getBody()['string'];
        $checkFunction = function ($string_param) {
            $pairs = function ($x) {
                return match ($x) {
                    "(" => ")",
                    "[" => "]",
                    "{" => "}",
                    default => false
                };
            };
            $arr = str_split($string_param);
            foreach ($arr as $i => &$char) {
                if (in_array($char, ['{', '[', '('])) {
                    $new_string = implode($arr);
                    $suporte_aberto_indice = $i;
                    $str_para_frente = substr($new_string, $suporte_aberto_indice + 1, strlen($new_string));
                    $check_suporte_fechado = strpos($str_para_frente, $pairs($char));
                    if ($check_suporte_fechado === false) {
                        throw new \Exception("não fechou o suporte $char");
                    }
                    $suporte_fechado_indice = strpos($new_string, $pairs($char));
                    $arr[$suporte_aberto_indice] = 0;
                    $arr[$suporte_fechado_indice] = 0;
                }
            }
            $new_string = implode($arr);
            if (strpos($new_string, ')') !== false) throw new \Exception("Suporte ) não foi aberto");
            if (strpos($new_string, ']') !== false) throw new \Exception("Suporte ] não foi aberto");
            if (strpos($new_string, '}') !== false) throw new \Exception("Suporte } não foi aberto");
        };
        try {
            $checkFunction($string_param);
            $res->status(200)->toJSON(['valido' => true, 'mensagem' => $string_param]);
        } catch (\Throwable $e) {
            $res->status(200)->toJSON(['valido' => false, 'mensagem' => $e->getMessage()]);
        }
    }
}
