<?php

if (version_compare(phpversion(), '7.2', '<')) {
    die("Sorry but the tools require at least PHP 7.2, you have ".phpversion()." installed\n");
}

spl_autoload_register(function ($fqcn) {
	// namespace autoloader
	$class = implode('/', array_slice(explode('\\', $fqcn), 1));

    $file = __DIR__ . '/' . $class . '.php';

    if (strlen($class) && file_exists($file)) {
        return require_once($file);
    }

	// old autoloader (deprecated)
	$search = [
		__DIR__ . '/',
		__DIR__ . '/Exceptions/',
		__DIR__ . '/Config/',
	];

	foreach($search as $base){
		$file = $base . $fqcn . '.php';

		if(file_exists($file)){
			return require_once($file);
		}
	}
});

if(!isset($showErrors)) $showErrors = false;

$cli = new DDT\CLI($argv, $showErrors);

\Shell::setDebug($cli->hasArg('debug'));
\Text::setQuiet($cli->hasArg('quiet'));

return $cli;
