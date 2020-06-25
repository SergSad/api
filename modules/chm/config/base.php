<?php

declare(strict_types=1);

namespace chm\modules\chm\config;

use chm\modules\chm\ChannelManagerModule;

return [
	'modules' => [
		ChannelManagerModule::ID_CONFIG => [
			ChannelManagerModule::ATTR_CLASS => ChannelManagerModule::class,
		],
	],
];
