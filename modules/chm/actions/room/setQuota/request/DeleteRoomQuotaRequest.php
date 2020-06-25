<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\setQuota\request;

use chm\modules\chm\components\ChannelManagerRequest;
use chm\modules\chm\validators\RoomAvailabilityValidator;
use common\yii\validators\DateValidator;
use common\yii\validators\UuidValidator;
use yii\validators\RequiredValidator;

/**
 * {@inheritdoc}
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class DeleteRoomQuotaRequest extends ChannelManagerRequest
{
	/** @var string Идентификатор номера. */
	public $roomId;
	public const ATTR_ROOM_ID = 'roomId';

	/** @var string Дата. */
	public $date;
	public const ATTR_DATE = 'date';

	/** Формат даты, который ожидается. */
	private const DATE_FORMAT = 'Y-m-d';

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function rules(): array
	{
		return [
			[static::ATTR_ROOM_ID,  RequiredValidator         ::class],
			[static::ATTR_ROOM_ID,  RoomAvailabilityValidator ::class],
			[static::ATTR_ROOM_ID,  UuidValidator             ::class],
			[static::ATTR_DATE,     RequiredValidator         ::class],
			[static::ATTR_DATE,     DateValidator             ::class, DateValidator::ATTR_FORMAT => 'php:' . static::DATE_FORMAT],
		];
	}
}
