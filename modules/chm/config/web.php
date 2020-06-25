<?php

declare(strict_types=1);

namespace chm\modules\chm\config;

use chm\modules\chm\ChannelManagerModule;
use chm\modules\chm\controllers\ChmController;
use ReflectionClass;
use yii\web\UrlManager;

return [
	'components' => [
		'urlManager' => [
			'class'           => UrlManager::class,
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				//добавление правил
			],
		],
	],
	'modules' => [
		ChannelManagerModule::ID_CONFIG => [
			ChannelManagerModule::ATTR_CONTROLLER_NAMESPACE => (new ReflectionClass(ChmController::class))->getNamespaceName(),
		],
	],
];
