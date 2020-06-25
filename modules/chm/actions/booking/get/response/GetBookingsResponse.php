<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\response;

/**
 * DTO ответа запроса "getBookings"
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsResponse
{
	/** @var int Общее количество. */
	public $total;

	/** @var GetBookingsResponseBooking[] список бронирований */
	public $items;
}
