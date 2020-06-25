<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

use common\yii\validators\DefaultValueValidator;
use common\yii\validators\IntegerValidator;
use common\yii\validators\IntValValidator;
use common\yii\validators\TrimValidator;
use yii\db\Query;

/**
 * Класс для поисковых запросов ченел менеджера.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerSearchRequest extends ChannelManagerRequest
{
	/** @var int номер страницы. */
	public $page = 1;
	public const ATTR_PAGE = 'page';
	public const MAX_PAGE  = 1000;

	/** @var int размер страницы. */
	public $pageSize;
	public const ATTR_PAGE_SIZE     = 'pageSize';
	public const STANDARD_PAGE_SIZE = 10;
	public const MAX_PAGE_SIZE      = 100;

	/** @var Query */
	public $searchQuery = null;

	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function rules()
	{
		return [
			[static::ATTR_PAGE,      TrimValidator         ::class],
			[static::ATTR_PAGE,      IntegerValidator      ::class, IntegerValidator::ATTR_MIN => 1],
			[static::ATTR_PAGE,      IntegerValidator      ::class, IntegerValidator::ATTR_MAX => static::MAX_PAGE],
			[static::ATTR_PAGE,      IntValValidator       ::class],
			[static::ATTR_PAGE,      DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => 1],
			[static::ATTR_PAGE_SIZE, TrimValidator         ::class],
			[static::ATTR_PAGE_SIZE, IntegerValidator      ::class, IntegerValidator::ATTR_MIN => 1],
			[static::ATTR_PAGE_SIZE, IntegerValidator      ::class, IntegerValidator::ATTR_MAX => static::MAX_PAGE_SIZE],
			[static::ATTR_PAGE_SIZE, IntValValidator       ::class],
			[static::ATTR_PAGE_SIZE, DefaultValueValidator ::class, DefaultValueValidator::ATTR_VALUE => static::STANDARD_PAGE_SIZE],
		];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function afterValidate()
	{
		$this->initSearchQuery();
		parent::afterValidate();
	}

	/**
	 * Переопределяемый метод инициализации поискового запроса
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	protected function initSearchQuery(): void
	{

	}

	/**
	 * Метод для добавления фильтрации по временным атрибутам
	 *
	 * @param string $filterAttribute Атрибут, к которому применяется фильтр
	 * @param string|null $startDatetime Стартовое время для фильтра
	 * @param string|null $endDatetime Конечное время для фильтра
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	protected function setDatetimeFilterToSearchQuery(string $filterAttribute, ?string $startDatetime, ?string $endDatetime): void
	{
		if (null === $this->searchQuery) {
			return;
		}

		if (null !== $startDatetime && null === $endDatetime) {
			$this->searchQuery->andFilterWhere([
				'>=',
				$filterAttribute,
				$startDatetime,
			]);
		}
		if (null === $startDatetime && null !== $endDatetime) {
			$this->searchQuery->andFilterWhere([
				'<=',
				$filterAttribute,
				$endDatetime,
			]);
		}
		if (null !== $startDatetime && null !== $endDatetime) {
			$this->searchQuery->andFilterWhere([
				'between',
				$filterAttribute,
				$startDatetime,
				$endDatetime,
			]);
		}
	}
}
