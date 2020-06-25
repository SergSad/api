<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\request;

use chm\modules\chm\components\ChannelManagerSearchRequest;
use common\models\db\RefHome;
use common\models\db\RefHomeRoom;
use common\yii\helpers\DateHelper;
use common\yii\validators\DefaultValueValidator;
use common\yii\validators\IntegerValidator;
use common\yii\validators\NumberValidator;
use common\yii\validators\UuidValidator;

/**
 * {@inheritdoc}
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomSearchRequest extends ChannelManagerSearchRequest
{

	/** @var int|null Серийный номер отеля. */
	public $hotelId;
	public const ATTR_HOTEL_ID = 'hotelId';

	/** @var int|null Идентификатор номера. */
	public $roomId;
	public const ATTR_ROOM_ID = 'roomId';

	/** @var float|null Размер номера в кв. м. */
	public $size;
	public const ATTR_SIZE = 'size';

	/** @var int|null Стандартное максимальное размещение для номера */
	public $occupancy;
	public const ATTR_OCCUPANCY = 'occupancy';

	/** @var int|null Флаг: для курящих / для некурящих */
	public $smoking;
	public const ATTR_SMOKING = 'smoking';

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function rules(): array
	{
		return array_merge(
			parent::rules(),
			[
				[static::ATTR_HOTEL_ID,  IntegerValidator      ::class],
				[static::ATTR_ROOM_ID,   UuidValidator         ::class],
				[static::ATTR_SIZE,      NumberValidator       ::class, NumberValidator::ATTR_MIN => 0],
				[static::ATTR_SIZE,      NumberValidator       ::class, NumberValidator::ATTR_MAX => 10000],
				[static::ATTR_OCCUPANCY, IntegerValidator      ::class, IntegerValidator::ATTR_MIN => 1],
				[static::ATTR_OCCUPANCY, IntegerValidator      ::class, IntegerValidator::ATTR_MAX => 10],
				[static::ATTR_SMOKING,   IntegerValidator      ::class],
				[static::ATTR_SMOKING,   DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => RefHomeRoom::NO_SMOKING],
			]
		);
	}

	/**
	 * Инициализация запроса поиска
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	protected function initSearchQuery(): void
	{
		if (null !== $this->searchQuery) {
			return;
		}
		$queryHomeTable = RefHome::tableName() . '.';
		$queryRoomTable = RefHomeRoom::tableName() . '.';

		$query = RefHomeRoom::find()
			->innerJoin(RefHome::tableName(), $queryHomeTable . RefHome::ATTR_ID . ' = ' . $queryRoomTable . RefHomeRoom::ATTR_HOME_ID)
			->where([
				$queryHomeTable . RefHome::ATTR_DELETE_STAMP  => DateHelper::ZERO_DATETIME,
				$queryHomeTable . RefHome::ATTR_STATUS  => [
					RefHome::STATUS_NEW,
					RefHome::STATUS_ACCEPTED,
					RefHome::STATUS_MODERATION,
					RefHome::STATUS_REPEATED_MODERATION,
				],
				$queryHomeTable . RefHome::ATTR_CHM_AVAILABLE  => true,
				$queryRoomTable . RefHomeRoom::ATTR_DELETE_STAMP => DateHelper::ZERO_DATETIME,
			])
			->groupBy($queryRoomTable . RefHomeRoom::ATTR_ID)
		;

		if (null !== $this->hotelId) {
			$query->andWhere([
				$queryHomeTable . RefHome::ATTR_SERIAL_NUMBER => $this->hotelId,
			])
			;
		}

		$query->andFilterWhere([$queryRoomTable . RefHomeRoom::ATTR_ID => $this->roomId]);
		$query->andFilterWhere([$queryRoomTable . RefHomeRoom::ATTR_SIZE => $this->size]);
		$query->andFilterWhere([$queryRoomTable . RefHomeRoom::ATTR_OCCUPANCY => $this->occupancy]);
		$query->andFilterWhere([$queryRoomTable . RefHomeRoom::ATTR_SMOKING => $this->smoking]);

		$this->searchQuery = $query;
	}
}
