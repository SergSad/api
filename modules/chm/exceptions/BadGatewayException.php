<?php

declare(strict_types=1);

namespace chm\modules\chm\exceptions;

use yii\web\HttpException;

/**
 * Class BadGatewayException
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class BadGatewayException extends HttpException
{
	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		parent::__construct(502, $message, $code, $previous);
	}

}
