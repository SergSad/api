<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get\assembler;

use chm\modules\chm\actions\booking\get\request\GetBookingsRequest;
use chm\modules\chm\actions\booking\get\response\GetBookingsResponse;

/**
 * Класс-ассемблер для формирование DTO GetBookingsResponse
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsResponseAssembler
{
	/** @var GetBookingsResponseBookingAssembler */
	private $getBookingAssembler;

	/**
	 * GetBookingsResponseAssembler constructor.
	 * @param GetBookingsResponseBookingAssembler $getBookingAssembler ассемблер для сборки внутреннего компонента GetBookingsResponseBooking
	 */
	public function __construct(GetBookingsResponseBookingAssembler $getBookingAssembler)
	{
		$this->getBookingAssembler = $getBookingAssembler;
	}

	/**
	 * @param GetBookingsRequest   $request Обрабатываемый запрос
	 * @return GetBookingsResponse
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assemble(GetBookingsRequest $request): GetBookingsResponse
	{
		$query = $request->searchQuery
			->offset(($request->page - 1) * $request->pageSize)
			->limit($request->pageSize);

		$result = $query->all();

		$response        = new GetBookingsResponse();
		$response->total = count($result);
		foreach ($result as $booking) {
			$response->items[] = $this->getBookingAssembler->assemble($booking);
		}

		return $response;
	}
}
