<?php

declare(strict_types=1);

namespace chm\modules\chm\actions\room\setQuota\methods;

use chm\modules\chm\actions\ChangeDataInterface;
use chm\modules\chm\actions\room\setQuota\request\SetRoomQuotaRequest;
use common\jobs\HomePricesRefreshJob;
use common\models\db\RegRoomQuota;
use Yii;

/**
 * Класс для записи квот
 *
 * @author  Sergey Sadovin <sadovin.serj@gmail.com>
 */
class SetQuotas implements ChangeDataInterface
{
	/** @var SetRoomQuotaRequest данные */
	public $request;

	/**
	 * @param SetRoomQuotaRequest $request
	 */
	public function __construct(SetRoomQuotaRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Обновить квоты
	 *
	 * @return bool
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(): bool
	{
		$quotas = [];
		foreach ($this->request->quotas as $quotaRequest) {
			$quotas[] = "('{$quotaRequest->date}', '{$this->request->roomId}', '{$quotaRequest->quota}')";
		}

		$TABLE_NAME      = RegRoomQuota::tableName();
		$COL_ACTIVE_DATE = RegRoomQuota::ATTR_ACTIVE_DATE;
		$COL_ROOM_ID     = RegRoomQuota::ATTR_ROOM_ID;
		$COL_QUOTA       = RegRoomQuota::ATTR_QUOTA;

		$VALUES = implode($quotas, ', ');

		$sql = (<<<SQL
			INSERT INTO $TABLE_NAME ($COL_ACTIVE_DATE, $COL_ROOM_ID, $COL_QUOTA) 
			VALUES $VALUES
			ON CONFLICT ($COL_ACTIVE_DATE, $COL_ROOM_ID) DO UPDATE 
			  SET $COL_QUOTA = EXCLUDED.$COL_QUOTA;
SQL
		);

		$result = !!RegRoomQuota::getDb()->createCommand($sql)->execute();

		if ($result) {
			Yii::$app->queue->push(new HomePricesRefreshJob([
				HomePricesRefreshJob::ATTR_HOME_ID => $this->request->roomId,
			]));
		}

		return $result;
	}
}
