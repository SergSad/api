<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\response;

/**
 * Информация о кровати
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomSearchResponseBed
{
	/** @var int Идентификатор кровати */
	public $id;

	/** @var int Количество кровати */
	public $count;

	/** @var string Наименование */
	public $name;
}
