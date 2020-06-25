<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\request;

use chm\modules\chm\components\ChannelManagerSearchRequest;
use common\models\db\RefBooking;
use common\models\db\RefHome;
use common\models\db\RefHomeType;
use common\yii\validators\DateValidator;
use common\yii\validators\DefaultValueValidator;
use common\yii\validators\IntegerValidator;
use common\yii\validators\RangeValidator;
use common\yii\validators\TrimValidator;

/**
 * Модель запроса метода поиска бронирований
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsRequest extends ChannelManagerSearchRequest
{
	/** @var int|null ID отеля. */
	public $hotelId;
	public const ATTR_HOTEL_ID = 'hotelId';

	/** @var string статус бронирования. */
	public $status;
	public const ATTR_STATUS = 'status';

	/** @var string Начальная дата добавления брони. */
	public $insertTimeStart;
	const ATTR_INSERT_TIME_START = 'insertTimeStart';

	/** @var string Конечная дата добавления брони. */
	public $insertTimeEnd;
	const ATTR_INSERT_TIME_END = 'insertTimeEnd';

	/** @var string Начальная дата заезда. */
	public $arrivalDateStart;
	const ATTR_ARRIVAL_DATE_START = 'arrivalDateStart';

	/** @var string Конечная дата заезда. */
	public $arrivalDateEnd;
	const ATTR_ARRIVAL_DATE_END = 'arrivalDateEnd';

	/** @var string Начальная дата выезда. */
	public $departureDateStart;
	const ATTR_DEPARTURE_DATE_START = 'departureDateStart';

	/** @var string Конечная дата выезда. */
	public $departureDateEnd;
	const ATTR_DEPARTURE_DATE_END = 'departureDateEnd';

	/**
	 * {@inheritDoc}
	 */
	public function rules()
	{
		return array_merge(
			parent::rules(),
			[
				[static::ATTR_HOTEL_ID, 			IntegerValidator	  ::class],
				[static::ATTR_STATUS,   			RangeValidator  	  ::class, RangeValidator::ATTR_RANGE => array_keys(RefBooking::getStatusVariants())],
				[static::ATTR_INSERT_TIME_START,	TrimValidator         ::class],
				[static::ATTR_INSERT_TIME_START,  	DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => null],
				[static::ATTR_INSERT_TIME_END,      TrimValidator         ::class],
				[static::ATTR_INSERT_TIME_END,  	DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => null],
				[static::ATTR_ARRIVAL_DATE_START,   DateValidator		  ::class, DateValidator::ATTR_FORMAT => 'php:Y-m-d'],
				[static::ATTR_ARRIVAL_DATE_START,  	DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => null],
				[static::ATTR_ARRIVAL_DATE_END,   	DateValidator		  ::class, DateValidator::ATTR_FORMAT => 'php:Y-m-d'],
				[static::ATTR_ARRIVAL_DATE_END,  	DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => null],
				[static::ATTR_DEPARTURE_DATE_START, DateValidator		  ::class, DateValidator::ATTR_FORMAT => 'php:Y-m-d'],
				[static::ATTR_DEPARTURE_DATE_START, DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => null],
				[static::ATTR_DEPARTURE_DATE_END,   DateValidator		  ::class, DateValidator::ATTR_FORMAT => 'php:Y-m-d'],
				[static::ATTR_DEPARTURE_DATE_END,   DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => null],
			]
		);
	}

	/**
	 * {@inheritDoc}
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	protected function initSearchQuery(): void
	{
		if (null !== $this->searchQuery) {
			return;
		}

		$query = RefBooking::find()
			->leftJoin(
				RefHome::tableName(),
				RefHome::tableName() . '.' . RefHome::ATTR_ID . '=' . RefBooking::tableName() . '.' . RefBooking::ATTR_HOTEL_ID
			)
			->andWhere([RefBooking::ATTR_HOTEL_TYPE_ID => RefHomeType::ID_HOTEL])
			->andWhere([
				RefHome::tableName() . '.' . RefHome::ATTR_STATUS  => [
					RefHome::STATUS_NEW,
					RefHome::STATUS_ACCEPTED,
					RefHome::STATUS_MODERATION,
					RefHome::STATUS_REPEATED_MODERATION,
				],
				RefHome::tableName() . '.' . RefHome::ATTR_CHM_AVAILABLE  => true,
			])
			->orderBy([RefBooking::ATTR_ARRIVAL_DATE => SORT_DESC]);

		$query->andFilterWhere([RefHome::tableName() . '.' . RefHome::ATTR_SERIAL_NUMBER => $this->hotelId]);
		$query->andFilterWhere([RefBooking::tableName() . '.' . RefBooking::ATTR_STATUS => $this->status]);

		$this->searchQuery = $query;

		$this->setDatetimeFilterToSearchQuery(
			RefBooking::tableName() . '.' . RefBooking::ATTR_INSERT_STAMP,
			$this->insertTimeStart,
			$this->insertTimeEnd
		);
		$this->setDatetimeFilterToSearchQuery(
			RefBooking::tableName() . '.' . RefBooking::ATTR_ARRIVAL_DATE,
			$this->arrivalDateStart,
			$this->arrivalDateEnd
		);
		$this->setDatetimeFilterToSearchQuery(
			RefBooking::tableName() . '.' . RefBooking::ATTR_DEPARTURE_DATE,
			$this->departureDateStart,
			$this->departureDateEnd
		);
	}
}
