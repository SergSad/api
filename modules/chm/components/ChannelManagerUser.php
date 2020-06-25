<?php

declare(strict_types=1);

namespace chm\modules\chm\components;

use common\models\db\RefUser;
use Yii;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * Свой собственный компонент web-пользователя для точки входа API ЧМ.
 *
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class ChannelManagerUser extends User {

	/** @var bool|RefUser|null Экземпляр объекта пользователя. */
	private $identityInstance = false;

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function init() {
		$this->loginUrl      = null;
		$this->identityClass = RefUser::class;
		$this->enableSession = false;

		parent::init();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function getIdentity($autoRenew = true): ?RefUser {
		if (false === $this->identityInstance) {
			$this->identityInstance = null;

			$auth = Yii::$app->request->headers->get('Authorization');
			$auth = trim((string)$auth);
			if ('' === $auth) {// Bearer *************
				return null;
			}
			$auth = explode(' ', $auth);
			if (2 !== count($auth)) {
				return null;
			}

			if ('Bearer' !== $auth[0]) {
				return null;
			}

			$token = (new ChannelManagerTokenRepository())->findByToken($auth[1]);
			if (null !== $token) {
				$user  = RefUser::getModel($token->user_id);
				$roles = Yii::$app->authManager->getRolesByUser($token->user_id);
				// Проверим существует ли роль
				if (in_array($user::ROLE_CHANNEL_MANAGER, array_keys($roles))) {
					$this->identityInstance = $user;
				}
			}
		}

		return $this->identityInstance;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function setIdentity($identity) {
		$this->identityInstance = $identity;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function login(IdentityInterface $identity, $duration = 0): bool {
		if ($this->beforeLogin($identity, false, $duration)) {
			$this->switchIdentity($identity, $duration);
			$this->afterLogin($identity, false, $duration);
		}

		return (false === $this->getIsGuest());
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function switchIdentity($identity, $duration = 0) {
		$this->setIdentity($identity);
	}
}
