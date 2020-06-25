<?php

declare(strict_types=1);

namespace chm\modules\chm\config;

return [
	'controllerMap' => [
		'migrate' => [
			'migrationPath' => [
				dirname(__DIR__) . '/migrations',
			],
		],
	],
];
