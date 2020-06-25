<?php

declare(strict_types=1);

namespace chm\modules\chm\actions;

/**
 * Интерфейс действия для каждого из методов.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
interface ChangeDataInterface
{
	/**
	 * Запуск записи.
	 *
	 * @return bool
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function run(): bool;
}
