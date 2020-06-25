<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\setQuota\request;

use common\yii\base\Model;
use common\yii\validators\DateValidator;
use common\yii\validators\IntegerValidator;
use yii\validators\RequiredValidator;

/**
 * {@inheritdoc}
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class SetQuotaRequest extends Model
{
	/** @var string Дата. */
	public $date;
	public const ATTR_DATE = 'date';

	/** @var int Квота */
	public $quota;
	public const ATTR_QUOTA = 'quota';

	/** Формат даты, который ожидается. */
	private const DATE_FORMAT = 'Y-m-d';

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function formName(): string
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function rules(): array
	{
		return [
			[static::ATTR_QUOTA,    RequiredValidator         ::class,],
			[static::ATTR_QUOTA,    IntegerValidator          ::class, IntegerValidator::ATTR_MIN => 0],
			[static::ATTR_DATE,     RequiredValidator         ::class],
			[static::ATTR_DATE,     DateValidator             ::class, DateValidator::ATTR_FORMAT => 'php:' . static::DATE_FORMAT],
		];
	}
}
