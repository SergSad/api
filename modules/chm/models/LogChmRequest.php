<?php

declare(strict_types=1);

namespace chm\modules\chm\models;

use common\yii\behaviors\TimestampUTCBehavior;
use common\yii\db\ActiveRecord;

/**
 * Лог запросов из ченел менеджера.
 *
 * @property string $id           Идентификатор
 * @property string $insert_stamp Дата и время добавления
 * @property string $request      Данные в запросе (JSON)
 * @property string $response     Данные в ответе (JSON)
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class LogChmRequest extends ActiveRecord
{
	public const ATTR_ID           = 'id';
	public const ATTR_INSERT_STAMP = 'insert_stamp';
	public const ATTR_REQUEST      = 'request';
	public const ATTR_RESPONSE     = 'response';

	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public static function tableName()
	{
		return 'log_chm_request';
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function behaviors(): array
	{
		return [
			[
				TimestampUTCBehavior::ATTR_CLASS                => TimestampUTCBehavior::class,
				TimestampUTCBehavior::ATTR_CREATED_AT_ATTRIBUTE => static::ATTR_INSERT_STAMP,
			],
		];
	}
}
