<?php

declare(strict_types=1);

namespace chm\config;

use chm\modules\chm\ChannelManagerModule;
use yii\helpers\ArrayHelper;

$config = [
	'id'       => 'chm',
	'basePath' => dirname(__DIR__),
];

$config = ArrayHelper::merge($config, require(ChannelManagerModule::CONFIG_BASE));

return $config;
