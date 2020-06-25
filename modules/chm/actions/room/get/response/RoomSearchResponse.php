<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\response;

/**
 * Ответ со списком найденных комнат.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomSearchResponse
{
	/** @var int Общее количество. */
	public $total;

	/** @var RoomSearchResponseItem[] Список номеров. */
	public $items = [];
}
