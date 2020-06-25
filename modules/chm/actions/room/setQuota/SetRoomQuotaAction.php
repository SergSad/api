<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\setQuota;

use chm\modules\chm\actions\ChannelManagerActionInterface;
use chm\modules\chm\actions\room\setQuota\methods\DeleteQuota;
use chm\modules\chm\actions\room\setQuota\methods\SetQuotas;
use chm\modules\chm\actions\room\setQuota\request\DeleteRoomQuotaRequest;
use chm\modules\chm\actions\room\setQuota\request\SetRoomQuotaRequest;
use chm\modules\chm\components\ChannelManagerRequest;
use chm\modules\chm\components\ChannelManagerResponse;
use Yii;
use yii\web\MethodNotAllowedHttpException;

/**
 * Установка квот
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class SetRoomQuotaAction implements ChannelManagerActionInterface
{
	/**
	 * Фабрика реквестов
	 *
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function initRequest(): ChannelManagerRequest
	{
		switch (Yii::$app->request->method) {
			case ChannelManagerRequest::METHOD_POST:
			case ChannelManagerRequest::METHOD_PUT:
				return new SetRoomQuotaRequest;
			case ChannelManagerRequest::METHOD_DELETE:
				return new DeleteRoomQuotaRequest;
		}
		throw new MethodNotAllowedHttpException('Method Not Allowed');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param ChannelManagerRequest $request
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(ChannelManagerRequest $request): ChannelManagerResponse
	{
		$response = new ChannelManagerResponse();

		if ($request instanceof SetRoomQuotaRequest) {
			$response->success = (new SetQuotas($request))->run();
		}
		elseif ($request instanceof DeleteRoomQuotaRequest) {
			$response->success = (new DeleteQuota($request))->run();
		}

		return $response;
	}
}
