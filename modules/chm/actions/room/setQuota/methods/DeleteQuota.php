<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\setQuota\methods;

use chm\modules\chm\actions\ChangeDataInterface;
use chm\modules\chm\actions\room\setQuota\request\DeleteRoomQuotaRequest;
use common\jobs\HomePricesRefreshJob;
use common\models\db\RegRoomQuota;
use Yii;

/**
 * Класс для удаления квоты
 *
 * @author  Sergey Sadovin <sadovin.serj@gmail.com>
 */
class DeleteQuota implements ChangeDataInterface
{
	/** @var DeleteRoomQuotaRequest данные */
	public $request;

	/**
	 * @param DeleteRoomQuotaRequest $request
	 */
	public function __construct(DeleteRoomQuotaRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Удалить квоту
	 *
	 * @return bool
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(): bool
	{
		$quota = RegRoomQuota::findOne([
			RegRoomQuota::ATTR_ROOM_ID     => $this->request->roomId,
			RegRoomQuota::ATTR_ACTIVE_DATE => $this->request->date,
		]);

		$result = $quota->delete();
		if($result){
			Yii::$app->queue->push(new HomePricesRefreshJob([
				HomePricesRefreshJob::ATTR_HOME_ID => $this->request->roomId,
			]));
		}

		return !!$result;
	}
}