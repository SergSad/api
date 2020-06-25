<?php

declare(strict_types=1);

namespace chm\modules\chm;

use common\yii\base\Module;

/**
 * Модуль для работы с API ченел менеджера
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerModule extends Module
{
	/** Идентификатор модуля в конфиге Yii. */
	public const ID_CONFIG = 'chm';

	public const CONFIG_BASE    = __DIR__ . '/config/base.php';
	public const CONFIG_CONSOLE = __DIR__ . '/config/console.php';
	public const CONFIG_WEB     = __DIR__ . '/config/web.php';
}