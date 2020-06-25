<?php

declare(strict_types=1);

namespace chm\modules\chm\controllers;

use chm\modules\chm\actions\booking\get\GetBookingsAction;
use chm\modules\chm\actions\ChannelManagerActionInterface;
use chm\modules\chm\actions\hotel\get\GetHotelsAction;
use chm\modules\chm\actions\meal\get\GetMealsAction;
use chm\modules\chm\actions\price\get\GetPricesAction;
use chm\modules\chm\actions\price\set\SetPriceAmountsAction;
use chm\modules\chm\actions\price\set\SetPricePenaltiesAction;
use chm\modules\chm\actions\price\set\SetPriceRestrictionsAction;
use chm\modules\chm\actions\room\get\GetRoomsAction;
use chm\modules\chm\actions\room\setQuota\SetRoomQuotaAction;
use chm\modules\chm\actions\tariff\get\GetTariffsAction;
use chm\modules\chm\actions\tariff\set\SetTariffAction;
use chm\modules\chm\components\ChannelManagerResponse;
use chm\modules\chm\exceptions\BadGatewayException;
use chm\modules\chm\models\LogChmRequest;
use common\yii\web\Controller;
use Throwable;
use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use zalatov\yii2\extend\exceptions\ValidationException;

/**
 * Основной контроллер обработки запросов.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChmController extends Controller
{
	public const ACTION_GET_HOTELS             = 'getHotels';
	public const ACTION_GET_ROOMS              = 'getRooms';
	public const ACTION_GET_TARIFFS            = 'getTariffs';
	public const ACTION_GET_MEALS              = 'getMeals';
	public const ACTION_GET_BOOKINGS           = 'getBookings';
	public const ACTION_GET_PRICES             = 'getPrices';
	public const ACTION_SET_TARIFF             = 'setTariff';
	public const ACTION_SET_ROOM_QUOTA         = 'setRoomQuota';
	public const ACTION_SET_PRICE_AMOUNT       = 'setPriceAmounts';
	public const ACTION_SET_PRICE_RESTRICTIONS = 'setPriceRestrictions';
	public const ACTION_SET_PRICE_PENALTIES    = 'setPricePenalties';

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function __construct(string $id, $module, array $config = [])
	{
		$this->enableCsrfValidation = false;

		parent::__construct($id, $module, $config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function actions(): array
	{
		return [
			static::ACTION_GET_HOTELS    		  => GetHotelsAction::class,
			static::ACTION_GET_ROOMS     		  => GetRoomsAction::class,
			static::ACTION_GET_TARIFFS   		  => GetTariffsAction::class,
			static::ACTION_GET_MEALS     		  => GetMealsAction::class,
			static::ACTION_GET_BOOKINGS  		  => GetBookingsAction::class,
			static::ACTION_GET_PRICES    		  => GetPricesAction::class,
			static::ACTION_SET_ROOM_QUOTA		  => SetRoomQuotaAction::class,
			static::ACTION_SET_PRICE_AMOUNT		  => SetPriceAmountsAction::class,
			static::ACTION_SET_PRICE_RESTRICTIONS => SetPriceRestrictionsAction::class,
			static::ACTION_SET_PRICE_PENALTIES	  => SetPricePenaltiesAction::class,
			static::ACTION_SET_TARIFF	          => SetTariffAction::class,
		];
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function runAction($id, $params = []): ChannelManagerResponse
	{

		// -- Обрабатываем запрос
		$response = $this->runActionInner($id, $params);
		// -- -- -- --

		// -- Логируем данные о запросе и ответе
		try {
			$log           = new LogChmRequest();
			$log->request  = Yii::$app->request->rawBody;
			$log->response = Json::encode($response, JSON_UNESCAPED_UNICODE);

			if (false === $log->save()) {
				throw new ValidationException($log);
			}
		}
		catch (Throwable $e) {// Нельзя, чтобы бросались исключения - ответ должен быть всегда
			Yii::$app->errorHandler->logException($e);
		}

		// -- -- -- --

		return $response;
	}

	/**
	 * todo РЕФАКТОРИНГ
	 *
	 * @param string $actionId Название экшена
	 * @param array  $params Параметры
	 *
	 * @return ChannelManagerResponse
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	private function runActionInner(string $actionId, array $params): ChannelManagerResponse
	{
		try {
			// -- Проверяем авторизацию
			$identity = Yii::$app->user->getIdentity();
			if (null === $identity) {
				throw new UnauthorizedHttpException('Unauthorized');
			}
			if (!Yii::$app->user->login($identity)) {
				throw new UnauthorizedHttpException('Unauthorized');
			}
			// -- -- -- --

			// -- Выполняем обработку в зависимости от типа запроса
			if (Yii::$app->request->isPost || Yii::$app->request->isPut) {
				// -- Получаем JSON данные
				$json = Yii::$app->request->rawBody;
				$json = json_decode($json, true);

				if (false === is_array($json)) {
					throw new BadRequestHttpException('Входные данные не являются правильным JSON.');
				}

				$params = array_merge($params, $json);
			}
			elseif (false === (Yii::$app->request->isGet || Yii::$app->request->isDelete)) {
				throw new MethodNotAllowedHttpException('Method Not Allowed');
			}
			// -- -- -- --

			// -- Проверяем запрошенное действие
			if (false === array_key_exists($actionId, $this->actions())) {
				throw new BadGatewayException('Bad Gateway');
			}

			/** @var ChannelManagerActionInterface $action */
			$action = Yii::createObject($this->actions()[$actionId]);
			// -- -- -- --

			// -- Заполняем данные в модель запроса, чтобы было понятно, с чем работаем в конкретном действии
			$request = $action->initRequest();

			$request->load($params);// Не во всех случаях приходят данные, поэтому не проверям, были ли они загружены

			if (false === $request->validate()) {
				throw new UnprocessableEntityHttpException();
			}
			// -- -- -- --

			$response = $action->run($request);
		}
		catch (Throwable $exception) {
			Yii::$app->errorHandler->logException($exception);

			$response = new ChannelManagerResponse();
			$response->setErrorByException($exception, $request ?? null);
		}

		return $response;
	}
}
