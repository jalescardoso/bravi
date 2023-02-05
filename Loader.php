<?php
class Loader {
	public static function loader_system() {
		spl_autoload_register(function ($_className) {
			$caminho = __DIR__ . "/$_className.php";
			if (file_exists($caminho))
				require_once $caminho;
			$caminho = __DIR__ . "/" . strtolower($_className) . ".php";
			if (file_exists($caminho))
				require_once $caminho;
		});
	}
}
Loader::loader_system();
