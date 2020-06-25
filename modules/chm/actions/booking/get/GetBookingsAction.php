<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\booking\get;

use chm\modules\chm\actions\booking\get\assembler\GetBookingsResponseAssembler;
use chm\modules\chm\actions\booking\get\request\GetBookingsRequest;
use chm\modules\chm\actions\ChannelManagerActionInterface;
use chm\modules\chm\components\ChannelManagerRequest;
use chm\modules\chm\components\ChannelManagerResponse;
use chm\modules\chm\controllers\ChmController;

/**
 * Поиск бронирований.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetBookingsAction implements ChannelManagerActionInterface
{
	/** @var GetBookingsResponseAssembler */
	private $getBookingsResponseAssembler;

	/**
	 * GetBookingsAction constructor.
	 * @param GetBookingsResponseAssembler $getBookingsResponseAssembler ассемблер для сборки ответа GetBookingsResponse
	 */
	public function __construct(GetBookingsResponseAssembler $getBookingsResponseAssembler)
	{
		$this->getBookingsResponseAssembler = $getBookingsResponseAssembler;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function initRequest(): ChannelManagerRequest
	{
		return new GetBookingsRequest;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param GetBookingsRequest $request
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(ChannelManagerRequest $request): ChannelManagerResponse
	{
		$response          = new ChannelManagerResponse();
		$response->success = true;
		$response->data    = $this->getBookingsResponseAssembler->assemble($request);
		$response->setPagination(
			ChmController::ACTION_GET_BOOKINGS,
			$request->searchQuery->count(),
			$request->page,
			$request->pageSize
		);

		return $response;
	}
}
