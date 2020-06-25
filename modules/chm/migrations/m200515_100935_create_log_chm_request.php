<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * @author Sergey Sadovin <sadovin.serj@gmail.com>
 */
class m200515_100935_create_log_chm_request extends Migration
{
	private const TABLE_NAME = 'log_chm_request';

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function safeUp()
	{
		$this->createTable(static::TABLE_NAME, [
			'id'           => 'UUID NOT NULL DEFAULT uuid_generate_v4()',
			'insert_stamp' => 'TIMESTAMP NOT NULL DEFAULT TIMEZONE(\'UTC\', NOW())',
			'request'      => 'JSON NOT NULL',
			'response'     => 'JSON NOT NULL',
		]);

		$this->addPrimaryKey('pk-' . static::TABLE_NAME, static::TABLE_NAME, ['id']);

		$this->addCommentOnColumn(static::TABLE_NAME, 'id',           'Идентификатор');
		$this->addCommentOnColumn(static::TABLE_NAME, 'insert_stamp', 'Дата и время добавления');
		$this->addCommentOnColumn(static::TABLE_NAME, 'request',      'Данные в запросе (JSON)');
		$this->addCommentOnColumn(static::TABLE_NAME, 'response',     'Данные в ответе (JSON)');

		$this->addCommentOnTable(static::TABLE_NAME, 'Лог запросов из ченел менеджера.');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function safeDown()
	{
		$this->dropTable(static::TABLE_NAME);
	}
}
