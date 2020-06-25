<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\response;

use common\models\db\RefFacility;
use common\models\db\RefHome;
use common\models\db\RefHomeRoomLnkBed;
use common\models\db\RefHomeRoomOccupancy;
use common\models\db\RefHomeTariff;

/**
 * Информация о комнате.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomSearchResponseItem
{
	/** @var string Идентификатор. */
	public $id;

	/** @var string Серийный номер отеля */
	public $hotelId;

	/** @var string Название комнаты. */
	public $name;

	/** @var string Размер номера в кв.м. */
	public $size;

	/** @var float Стандартное максимальное размещение для номера. */
	public $occupancy;

	/** @var array Возможные варианты размещения в номере. */
	public $occupancyList;

	/** @var int Описание */
	public $description;

	/** @var int Флаг: для курящих / для некурящих */
	public $smoking;

	/** @var array список услуг номера */
	public $facilities;

	/** @var array список кроватей */
	public $beds;
}
