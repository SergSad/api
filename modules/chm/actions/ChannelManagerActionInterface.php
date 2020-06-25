<?php

declare(strict_types=1);

namespace chm\modules\chm\actions;

use chm\modules\chm\components\ChannelManagerRequest;
use chm\modules\chm\components\ChannelManagerResponse;

/**
 * Интерфейс API действия для обработки запросов из ченел менеджера.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
interface ChannelManagerActionInterface
{
	/**
	 * Инициализация объекта, в который будут занесены входящие данные.
	 * Этим объектом переданные данные будут проверены.
	 * Если ошибок во входных данных не будет, этот объект будет передан непосредственно в обработчик.
	 *
	 * @return ChannelManagerRequest
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function initRequest(): ChannelManagerRequest;

	/**
	 * Запуск обработки.
	 *
	 * @param ChannelManagerRequest $request Данные, пришедшие из запроса
	 *
	 * @return ChannelManagerResponse
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(ChannelManagerRequest $request): ChannelManagerResponse;
}
