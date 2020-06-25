<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

/**
 * Информация об ошибке.
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerResponseError
{
	/** @var int Код ошибки. */
	public $code;

	/** @var string Текст ошибки. */
	public $message;
}
