<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\response;

/**
 * Вложенное DTO ответа запроса "getBookings". Описание цены для одного бронирования
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsResponseBookingPrice
{
	/** @var string */
	public $roomName;

	/** @var string */
	public $bookingDate;

	/** @var string */
	public $price;

}
