<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\assembler;

use chm\modules\chm\actions\booking\get\response\GetBookingsResponseBooking;
use common\models\db\RefBooking;
use common\models\db\RefBookingProgram;
use common\models\db\RefBookingRoom;
use common\models\db\RefBookingRoomTblGuest;
use common\models\db\RefHomeTreatmentProgram;
use common\yii\helpers\DateHelper;
use yii\db\Query;

/**
 * Класс-ассемблер для формирование DTO GetBookingsResponseBooking
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsResponseBookingAssembler
{
	/** @var GetBookingsResponseBookingPriceAssembler */
	private $bookingPriceAssembler;

	/**
	 * GetBookingsResponseBookingAssembler constructor.
	 * @param GetBookingsResponseBookingPriceAssembler $bookingPriceAssembler ассемблер для сборки внутреннего компонента GetBookingsResponseBookingPrice
	 */
	public function __construct(GetBookingsResponseBookingPriceAssembler $bookingPriceAssembler)
	{
		$this->bookingPriceAssembler = $bookingPriceAssembler;
	}

	/**
	 * @param RefBooking $booking		  Обрабатываемый объект бронирования
	 * @return GetBookingsResponseBooking
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assemble(RefBooking $booking): GetBookingsResponseBooking
	{
		$responseBooking = new GetBookingsResponseBooking;
		$responseBooking->hotelId = $booking->getHome()->serial_number;
		$responseBooking->hotelName = $booking->getHome()->name;
		$responseBooking->prices = array_map(
			function (RefBookingRoom $room) {
				return $this->bookingPriceAssembler->assemble($room);
			},
			$booking->getRooms()
		);
		$responseBooking->userFullName = $booking->getUserInfo($booking)->getFullName();
		$responseBooking->userEmail = $booking->getUserInfo($booking)->getEmail();
		$responseBooking->userPhone = $booking->getUserInfo($booking)->getPhone();
		$responseBooking->userComment = $booking->user_comment;
		$responseBooking->treatmentPrograms = $this->getTreatmentProgram($booking);
		$responseBooking->adultGuestsCounts = $booking->getGuestsCountsByTypes()[RefBookingRoomTblGuest::TYPE_ADULT];
		$responseBooking->childrenGuestsCounts = $booking->getGuestsCountsByTypes()[RefBookingRoomTblGuest::TYPE_CHILD];
		$responseBooking->totalPrice = $booking->price;
		$responseBooking->status = $booking->status;
		$responseBooking->insertDate = DateHelper::utc($booking->insert_stamp);
		$responseBooking->arrivalDate = $booking->arrival_date;
		$responseBooking->departureDate = $booking->departure_date;
		$responseBooking->prepaySchemeName = $booking->getPrepayScheme() ? $booking->getPrepayScheme()->name : '';

		return $responseBooking;
	}

	/**
	 * todo похорошему - репозиторий
	 * @param RefBooking $booking Объект бронирования для по которому ищем лечебные программы
	 * @return string[]
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	private function getTreatmentProgram(RefBooking $booking): array
	{
		return (new Query())
			->from(RefHomeTreatmentProgram::tableName())
			->select(RefHomeTreatmentProgram::tableName() . '.' . RefHomeTreatmentProgram::ATTR_NAME)
			->leftJoin(
				RefBookingProgram::tableName(),
				RefHomeTreatmentProgram::tableName() . '.' . RefHomeTreatmentProgram::ATTR_ID . '=' . RefBookingProgram::tableName() . '.' . RefBookingProgram::ATTR_PROGRAM_ID
			)
			->where([
				RefBookingProgram::ATTR_BOOKING_ID => $booking->id,
			])
			->column();
	}
}
