<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\setQuota\request;

use chm\modules\chm\components\ChannelManagerRequest;
use chm\modules\chm\validators\RoomAvailabilityValidator;
use common\yii\validators\UuidValidator;
use yii\validators\RequiredValidator;
use yii\web\UnprocessableEntityHttpException;

/**
 * {@inheritdoc}
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class SetRoomQuotaRequest extends ChannelManagerRequest
{
	/** @var string Идентификатор номера. */
	public $roomId;
	public const ATTR_ROOM_ID = 'roomId';

	/** @var SetQuotaRequest[] Квоты */
	public $quotas;
	public const ATTR_QUOTAS = 'quotas';

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
		];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return bool
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function load($data, $formName = null)
	{
		if (isset($data[static::ATTR_QUOTAS])) {
			foreach ($data[static::ATTR_QUOTAS] as $quota) {
				$quotaForm = new SetQuotaRequest();
				if (false === $quotaForm->load($quota) || false === $quotaForm->validate()) {
					throw new UnprocessableEntityHttpException($quotaForm->getErrors());
				}
				$this->quotas[] = $quotaForm;
			}
		}

		return parent::load($data, $formName);
	}
}
