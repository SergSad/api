<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\response;

/**
 * Вложенное DTO ответа запроса "getBookings". Описание одного бронирования
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsResponseBooking
{
	/** @var string Серийный номер отеля */
	public $hotelId;

	/** @var string Название отеля */
	public $hotelName;

	/** @var GetBookingsResponseBookingPrice[] Информация о стоймости по дням. */
	public $prices;

	/** @var string Имя заказчика */
	public $userFullName;

	/** @var string Почтовый адрес заказчика. */
	public $userEmail;

	/** @var string Телефон заказчика. */
	public $userPhone;

	/** @var string Комментарий пользователя. */
	public $userComment;

	/** @var string[] Лечебные программы. */
	public $treatmentPrograms;

	/** @var int Количество взрослых гостей. */
	public $adultGuestsCounts;

	/** @var int Количество детей гостей. */
	public $childrenGuestsCounts;

	/** @var string Цена */
	public $totalPrice;

	/** @var string Статус бронирования */
	public $status;

	/** @var string Дата создания. */
	public $insertDate;

	/** @var string Дата прибытия. */
	public $arrivalDate;

	/** @var string Дата отъезда. */
	public $departureDate;

	/** @var string Схема оплаты. */
	public $prepaySchemeName;
}
