<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\response;

/**
 * Информация об услуге номера.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomSearchResponseFacility
{
	/** @var int Идентификатор услуги */
	public $id;

	/** @var string Название услуги. */
	public $name;

	/** @var bool Является ли значимой */
	public $isImportant;

	/** @var string Цена */
	public $price;
}
