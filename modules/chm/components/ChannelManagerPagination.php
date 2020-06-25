<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

/**
 * Данные о пагинации.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerPagination
{
	/** @var int Всего найдено записей. */
	public $total;

	/** @var int Текущая страница. */
	public $page = 1;

	/** @var int Размер страницы. */
	public $pageSize;

	/** @var int Общее количество страниц. */
	public $pages;

	/** @var string|null Ссылка на следующую страницу. */
	public $nextPageLink;

	/** @var string|null Ссылка на предыдущую страницу. */
	public $prevPageLink;
}
