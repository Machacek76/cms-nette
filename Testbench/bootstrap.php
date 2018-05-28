<?php

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/com/CacheCleaner.php';

\test\com\CacheCleaner::clean(__DIR__ . '/_temp');

Testbench\Bootstrap::setup(__DIR__ . '/_temp', function (\Nette\Configurator $configurator) {
	$configurator->createRobotLoader()->addDirectory([
		__DIR__ . '/../app',
	])->register();

	$configurator->addParameters([
		'appDir' => __DIR__ . '/../app',
    ]);
    

    $configurator->addConfig(__DIR__ . '/../app/config/config.neon');
    $configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
    $configurator->addConfig(__DIR__ . '/../app/config/extensions.neon');
    $configurator->addConfig(__DIR__ . '/../app/config/forms.neon');
    $configurator->addConfig(__DIR__ . '/config/tests.neon');

});