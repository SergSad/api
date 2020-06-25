<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\assembler;

use chm\modules\chm\actions\room\get\response\RoomSearchResponseBed;
use chm\modules\chm\actions\room\get\response\RoomSearchResponseFacility;
use chm\modules\chm\actions\room\get\response\RoomSearchResponseItem;
use chm\modules\chm\actions\room\get\response\RoomSearchResponseOccupancy;
use common\models\db\RefHomeRoom;
use common\models\db\RefHomeRoomLnkFacility;
use common\yii\helpers\DateHelper;

/**
 * Подстановка значений для комнаты
 *
 * @author  Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomResponseItemAssembler
{
	/**
	 * @param RefHomeRoom $room объект комната
	 *
	 * @return RoomSearchResponseItem
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assemble(RefHomeRoom $room): RoomSearchResponseItem
	{
		$item                = new RoomSearchResponseItem();
		$item->id            = $room->id;
		$item->hotelId       = $room->getHome()->serial_number;
		$item->name          = $room->name;
		$item->size          = $room->size;
		$item->occupancy     = $room->occupancy;
		$item->occupancyList = $this->assembleOccupancies($room);
		$item->description   = $room->description;
		$item->smoking       = $room->smoking;
		$item->facilities    = $this->assembleFacilities($room);
		$item->beds          = $this->assembleBeds($room);

		return $item;
	}

	/**
	 * Получить варианты размещения
	 *
	 * @param RefHomeRoom $room модель комнаты
	 *
	 * @return array
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assembleOccupancies(RefHomeRoom $room)
	{
		$result      = [];
		$occupancies = $room->getOccupancies();

		foreach ($occupancies as $occupancy) {
			$item             = new RoomSearchResponseOccupancy();
			$item->occupancy  = $occupancy->occupancy;
			$item->isExtraBed = $occupancy->is_extra_bed;
			$item->name       = $occupancy->name;

			$result[] = $item;
		}

		return $result;
	}

	/**
	 * Получить услуги
	 *
	 * @param RefHomeRoom $room модель комнаты
	 *
	 * @return array
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assembleFacilities(RefHomeRoom $room)
	{
		$result     = [];

		$facilities = RefHomeRoomLnkFacility::find()
			->andWhere([RefHomeRoomLnkFacility::ATTR_ROOM_ID => $room->id])
			->all()
		;

		foreach ($facilities as $facility) {
			/** @var RefHomeRoomLnkFacility $facility */
			if ($facility->facility->delete_stamp !== DateHelper::ZERO_DATETIME) {
				continue;
			}

			$item              = new RoomSearchResponseFacility();
			$item->id          = $facility->facility_id;
			$item->name        = $facility->facility->name;
			$item->isImportant = $facility->facility->is_important;
			$item->price       = $facility->price;

			$result[] = $item;
		}

		return $result;
	}

	/**
	 * Получить кровати
	 *
	 * @param RefHomeRoom $room модель комнаты
	 *
	 * @return array
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assembleBeds(RefHomeRoom $room)
	{
		$result = [];
		$beds   = $room->getBeds();

		foreach ($beds as $bed) {
			$item        = new RoomSearchResponseBed();
			$item->id    = $bed->bed->id;
			$item->name  = $bed->bed->name;
			$item->count = $bed->count;

			$result[] = $item;
		}

		return $result;
	}
}
