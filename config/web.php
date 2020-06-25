<?php

declare(strict_types=1);

namespace chm\config;

use chm\modules\chm\ChannelManagerModule;
use chm\modules\chm\components\ChannelManagerUser;
use common\components\activityLogger\Manager as LogManager;
use common\models\db\RefUser;
use lav45\activityLogger\DbStorage as LogDbStorage;
use lav45\activityLogger\LogMessage;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\UrlManager;

$config = [
	'homeUrl'    => Yii::getAlias('@chmUrl'),
	'components' => [
		'request' => [
			'enableCsrfCookie' => false,
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			],
		],
		'response' => [
			'format' => Response::FORMAT_JSON,
			'formatters' => [
				'json' => [
					'class' => 'yii\web\JsonResponseFormatter',
					'prettyPrint' => YII_DEBUG,
					'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
				],
			],
		],
		'urlManager' => [
			'class'           => UrlManager::class,
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
		],
		'user' => [
			'class'         => ChannelManagerUser::class,
			'identityClass' => RefUser::class,
		],
		'activityLogger' => [
			'class' => LogManager::class,
			'messageClass' => [
				'class' => LogMessage::class,
				'env'   => 'chm',
			],
		],
		'activityLoggerStorage' => [
			'class' => LogDbStorage::class,
		],
	],
];

$config = ArrayHelper::merge($config, require(ChannelManagerModule::CONFIG_WEB));

return $config;
