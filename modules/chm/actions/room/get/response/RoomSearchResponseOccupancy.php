<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\response;

/**
 * Информация о варианте размещения в номере.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomSearchResponseOccupancy
{
	/** @var int Кол-во человек */
	public $occupancy;

	/** @var bool Дополнительная кровать */
	public $isExtraBed;

	/** @var string Название варианта. */
	public $name;
}
