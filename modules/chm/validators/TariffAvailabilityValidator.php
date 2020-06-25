<?php

declare(strict_types=1);

namespace chm\modules\chm\validators;

use common\models\db\RefHome;
use common\models\db\RefHomeTariff;
use yii\db\Query;
use yii\validators\Validator;

/**
 * Валидация тарифа по его ID на доступность в ЧМ
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class TariffAvailabilityValidator extends Validator
{
	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function validateAttribute($model, $attribute)
	{
		$isTariffAvailableForChm = (new Query())
			->from(RefHomeTariff::tableName())
			->innerJoin(
				RefHome::tableName(),
				RefHome::tableName() . '.' . RefHome::ATTR_ID . ' = ' . RefHomeTariff::tableName() . '.' . RefHomeTariff::ATTR_HOME_ID)
			->where([
				RefHomeTariff::tableName() . '.' . RefHomeTariff::ATTR_ID => $model->$attribute,
				RefHome::tableName() . '.' . RefHome::ATTR_CHM_AVAILABLE => true,
			])
			->exists();

		if (false === $isTariffAvailableForChm) {
			$this->addError($model, $attribute, 'Отель тарифа не поддерживается ЧМ');
		}
	}
}
