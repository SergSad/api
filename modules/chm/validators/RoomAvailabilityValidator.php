<?php

declare(strict_types=1);

namespace chm\modules\chm\validators;

use common\models\db\RefHome;
use common\models\db\RefHomeRoom;
use yii\db\Query;
use yii\validators\Validator;

/**
 * Валидация комнаты по его ID на доступность в ЧМ
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomAvailabilityValidator extends Validator
{
	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function validateAttribute($model, $attribute)
	{
		$isRoomAvailableForChm = (new Query())
			->from(RefHomeRoom::tableName())
			->innerJoin(
				RefHome::tableName(),
				RefHome::tableName() . '.' . RefHome::ATTR_ID . ' = ' . RefHomeRoom::tableName() . '.' . RefHomeRoom::ATTR_HOME_ID)
			->where([
				RefHomeRoom::tableName() . '.' . RefHomeRoom::ATTR_ID => $model->$attribute,
				RefHome::tableName() . '.' . RefHome::ATTR_CHM_AVAILABLE => true,
			])
			->exists();

		if (false === $isRoomAvailableForChm) {
			$this->addError($model, $attribute, 'Отель комнаты не поддерживается ЧМ');
		}
	}
}
