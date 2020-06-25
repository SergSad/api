<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

use common\models\db\RefUserToken;

/**
 * Репозиторий для получения токенов, относящихся к авторизации в API ЧМ.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerTokenRepository
{

	/**
	 * Получение модели токена по содержимому токена.
	 *
	 * @param string $token Содержимое токена
	 *
	 * @return RefUserToken|null
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function findByToken(string $token): ?RefUserToken {
		$model = RefUserToken::find()
			->byToken($token)
			->byType(RefUserToken::TYPE_CHM_AUTH)
			->one()
		;/** @var RefUserToken $model */

		return $model;
	}

	/**
	 * Получение модели токена по идентификатору пользователя.
	 *
	 * @param string $userId Идентификатор пользователя
	 *
	 * @return RefUserToken|null
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function findByUser(string $userId): ?RefUserToken {
		$model = RefUserToken::find()
			->where([RefUserToken::ATTR_USER_ID => $userId])
			->byType(RefUserToken::TYPE_CHM_AUTH)
			->one()
		;/** @var RefUserToken $model */

		return $model;
	}

	/**
	 * Создание нового токена.
	 *
	 * Важно!
	 * Не может быть несколько токенов для одного и того же пользователя.
	 * Не может, потому что тогда непонятно будет, какой именно токен выбирать.
	 * Поэтому этот метод удалит все предыдущие токены, чтобы оставить только один.
	 *
	 * @param string $userId Идентификатор пользователя
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function create(string $userId) {
		RefUserToken::deleteAll([
			RefUserToken::ATTR_USER_ID => $userId,
			RefUserToken::ATTR_TYPE    => RefUserToken::TYPE_CHM_AUTH,
		]);

		RefUserToken::create($userId, RefUserToken::TYPE_CHM_AUTH);
	}
}
