<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\get;

use chm\modules\chm\actions\ChannelManagerActionInterface;
use chm\modules\chm\actions\room\get\assembler\RoomResponseAssembler;
use chm\modules\chm\actions\room\get\request\RoomSearchRequest;
use chm\modules\chm\components\ChannelManagerRequest;
use chm\modules\chm\components\ChannelManagerResponse;
use chm\modules\chm\controllers\ChmController;

/**
 * Поиск комнат.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class GetRoomsAction implements ChannelManagerActionInterface
{
	/** @var RoomResponseAssembler */
	private $assembler;

	/**
	 * {@inheritdoc}
	 *
	 * @param RoomResponseAssembler $response sss
	 */
	public function __construct(RoomResponseAssembler $response)
	{
		$this->assembler = $response;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function initRequest(): ChannelManagerRequest
	{
		return new RoomSearchRequest;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param RoomSearchRequest $request
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(ChannelManagerRequest $request): ChannelManagerResponse
	{
		$response          = new ChannelManagerResponse();
		$response->success = true;
		$response->data    = $this->assembler->assemble($request);

		$response->setPagination(
			ChmController::ACTION_GET_ROOMS,
			$request->searchQuery->count(),
			$request->page,
			$request->pageSize
		);

		return $response;
	}
}
