<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\assembler;

use chm\modules\chm\actions\booking\get\response\GetBookingsResponseBookingPrice;
use common\models\db\RefBookingRoom;
use common\models\db\RegBookingRoomPrice;

/**
 * Класс-ассемблер для формирование DTO GetBookingsResponseBookingPrice
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsResponseBookingPriceAssembler
{
	/**
	 * @param RefBookingRoom $bookingRoom Обрабатываемый объект бронирования
	 * @return GetBookingsResponseBookingPrice
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assemble(RefBookingRoom $bookingRoom): GetBookingsResponseBookingPrice
	{
		 $bookingPrice = new GetBookingsResponseBookingPrice;
		 $bookingPrice->roomName = $bookingRoom->getRoomInfo()->name;

		 $regBookingRoomPrice = RegBookingRoomPrice::findOne([RegBookingRoomPrice::ATTR_BOOKING_ROOM_ID => $bookingRoom->id]);
		 $bookingPrice->bookingDate = $regBookingRoomPrice->booking_date;
		 $bookingPrice->price = $regBookingRoomPrice->price;

		 return $bookingPrice;
	}
}
