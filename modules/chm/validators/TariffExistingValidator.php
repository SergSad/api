<?php

declare(strict_types=1);

namespace chm\modules\chm\validators;

use common\models\db\RefHomeTariff;
use yii\validators\Validator;

/**
 * Валидация тарифа на существование
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class TariffExistingValidator extends Validator
{
	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function validateAttribute($model, $attribute)
	{
		if (null === $model->$attribute) {
			return;
		}

		$existingTariff = RefHomeTariff::findOne($model->$attribute);
		if (null === $existingTariff) {
			$model->addError($attribute, Yii::t('chm', 'Тариф с искомым ID несуществует'));
		}
	}
}
