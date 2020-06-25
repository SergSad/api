<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

use chm\modules\chm\controllers\ChmController;
use common\yii\base\Model;
use Throwable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Описание ответа ченел менеджера на запрос
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerResponse
{
	/**
	 * Результат выполнения запроса:
	 * - true  : если запрос выполнен без ошибок;
	 * - false : если в результате выполнения были ошибки.
	 *
	 * @var bool
	 */
	public $success = false;

	/**
	 * Данные, передаваемые в ответе.
	 * Здесь обязательно должен быть какой-то класс, ну или null, если данные не предусмотрены.
	 *
	 * @var mixed|null
	 */
	public $data;

	/**
	 * Массив ошибок.
	 * Содержит тексты ошибок с их кодами.
	 *
	 * @var ChannelManagerResponseError[]
	 */
	public $errors = [];

	/**
	 * Данные о пагинации
	 *
	 * @var ChannelManagerPagination|null
	 */
	public $pagination = null;

	/**
	 * Добавление ошибки на основе возникшего исключения.
	 *
	 * @param Throwable $exception Возникшее исключение
	 * @param ChannelManagerRequest|null $request Запрос, который был обработан, или NULL, если ошибка возникла ещё до инициализации объекта запроса
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function setErrorByException(Throwable $exception, ?ChannelManagerRequest $request = null)
	{
		// -- Всегда добавляем информацию об ошибках в запросе - эти ошибки могут привести к другим ошибкам
		if (null !== $request) {
			$this->addRequestValidationErrors($request);
		}
		// -- -- -- --

		// -- Выводим текст ошибки только в debug-режиме
		$error = new ChannelManagerResponseError;
		$error->code = $exception->statusCode ?? 500;
		$message = $exception->getMessage() ?: 'Internal server error';
		$error->message = (YII_DEBUG ? $exception->__toString() : $message);
		$this->errors[] = $error;
		// -- -- -- --
	}

	/**
	 * Метод для установки компонента пагинации в ответ
	 *
	 * @param string $action Алиас экшена
	 * @param int $total	 Общее количество элементов
	 * @param int $page		 Текущая страница
	 * @param int $pageSize	 Размер страницы (кол-во элементов на одной странице)
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function setPagination(string $action, int $total, int $page, int $pageSize): void
	{
		$this->pagination               = new ChannelManagerPagination();
		$this->pagination->total        = $total;
		$this->pagination->page         = $page;
		$this->pagination->pageSize     = $pageSize;
		$this->pagination->pages        = intval(ceil($this->pagination->total / $this->pagination->pageSize));
		$this->pagination->prevPageLink = null;
		$this->pagination->nextPageLink = null;

		$paramsWithoutPage = Yii::$app->request->get();
		ArrayHelper::remove($paramsWithoutPage, 'page');

		if ($this->pagination->page > 1) {
			$page           = $this->pagination->page - 1;
			$paramsWithPage = $paramsWithoutPage;

			if (1 !== $page) {// Первая страница должна быть как обычная в URL
				$paramsWithPage['page'] = $page;
			}

			$this->pagination->prevPageLink = Url::to(ChmController::getUrlRoute($action, $paramsWithPage));
		}

		if ($this->pagination->page < $this->pagination->pages) {
			$page           = $this->pagination->page + 1;
			$paramsWithPage = $paramsWithoutPage;

			$paramsWithPage['page'] = $page;

			$this->pagination->nextPageLink = Url::to(ChmController::getUrlRoute($action, $paramsWithPage));
		}
	}

	/**
	 * Добавление в ответ ошибки валидации запроса
	 * @param ChannelManagerRequest $request Запрос, который был обработан
	 * @param array 				$path	 Абсолютный путь до модели согласно корню JSON данных
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	private function addRequestValidationErrors(ChannelManagerRequest $request, array $path = [])
	{
		// -- Пробрасываем ошибки основной модели
		foreach ($request->getErrors() as $attribute => $errors) {
			foreach ($errors as $error) {
				// -- Подменяем название атрибута в виде абсолютногопути в JSON, чтобы локализовать место ошибки
				$error = preg_replace(
					ChannelManagerRequest::PATH_MARKER_REGEXP,
					implode('.', array_merge($path, [$attribute])),
					$error
				);
				// -- -- -- --

				$e              = new ChannelManagerResponseError;
				$e->code        = 400;
				$e->message     = $error;
				$this->errors[] = $e;
			}
		}
		// -- -- -- --

		// -- Пробрасываем ошибки вложенных моделей
		foreach ($request->attributes as $attribute => $value) {
			if ($value instanceof Model) {
				$this->addRequestValidationErrors($value, array_merge($path, [$attribute]));
			}
			elseif (is_array($value)) {
				foreach ($value as $attributeInner => $valueInner) {
					if ($valueInner instanceof Model) {
						$this->addRequestValidationErrors($valueInner, array_merge($path, [$attribute . '[' . $attributeInner . ']']));
					}
				}
			}
		}
		// -- -- -- --
	}
}
