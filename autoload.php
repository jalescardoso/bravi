<?php
class Loader {
	public static function loader_system() {
		spl_autoload_register(function ($className) {
			$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
			$caminho = __DIR__ . "/$className.php";
			if (file_exists($caminho))
				require_once $caminho;
			$caminho = __DIR__ . "/" . strtolower($className) . ".php";
			if (file_exists($caminho))
				require_once $caminho;
		});
	}
}
Loader::loader_system();