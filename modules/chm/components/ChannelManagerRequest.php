<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

use common\models\db\RefHome;
use common\yii\base\Model;
use yii\db\Query;

/**
 * Базовый класс для всех запросов ченел менеджера.
 * Класс наследуется от Model, чтобы загрузить и проверить переданные данные.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerRequest extends Model
{
	/**
	 * Регулярка для того, чтобы заменить маркер (название атрибута) на полный "путь" атрибута во всём JSON объекте.
	 * Так как приходит многоуровневый JSON, хочется максимально локализовать объект и атрибут, в котором возникла ошибка.
	 */
	public const PATH_MARKER_REGEXP = '/(?<=«).+?(?=»)/';

	public const METHOD_GET    = 'GET';
	public const METHOD_POST   = 'POST';
	public const METHOD_PUT    = 'PUT';
	public const METHOD_DELETE = 'DELETE';

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
	 * Возращает ID отеля по его серийному номеру
	 *
	 * @param int $serialNumber серийный номер отеля
	 * @return string|null
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	protected function getHomeIdBySerialNumber(int $serialNumber): ?string
	{
		return (new Query())
			->select(RefHome::ATTR_ID)
			->from(RefHome::tableName())
			->where([RefHome::ATTR_SERIAL_NUMBER => $serialNumber])
			->scalar() ?? null;
	}
}
