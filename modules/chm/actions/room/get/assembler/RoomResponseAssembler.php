<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get\assembler;

use chm\modules\chm\actions\room\get\request\RoomSearchRequest;
use chm\modules\chm\actions\room\get\response\RoomSearchResponse;

/**
 * Class GetHotelResponseAssembler
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class RoomResponseAssembler
{
	/** @var RoomResponseItemAssembler класс для получения комнаты */
	private $assembler;

	/**
	 * RoomResponseAssembler constructor.
	 *
	 * @param RoomResponseItemAssembler $roomAssembler механизм получения комнаты
	 */
	public function __construct(RoomResponseItemAssembler $roomAssembler)
	{
		$this->assembler = $roomAssembler;
	}

	/**
	 * @param RoomSearchRequest $request Запрос
	 *
	 * @return RoomSearchResponse
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function assemble(RoomSearchRequest $request): RoomSearchResponse
	{
		$query = $request->searchQuery
			->offset(($request->page - 1) * $request->pageSize)
			->limit($request->pageSize);

		$result = $query->all();

		$response        = new RoomSearchResponse();
		$response->total = count($result);
		foreach ($result as $room) {
			$response->items[] = $this->assembler->assemble($room);
		}

		return $response;
	}
}
