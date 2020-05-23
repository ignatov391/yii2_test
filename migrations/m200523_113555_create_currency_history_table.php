<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency_history}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%currency}}`
 */
class m200523_113555_create_currency_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_history}}', [
            'id' => $this->primaryKey(),
            'remoteID' => $this->string('32')->notNull(),// для контроля
//            'chainCode' => $this->string('8')->notNull(),
            'nominal' => $this->integer(),
            'value' => $this->float()->notNull(),
            'currency_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        // creates index for column `currency_id`
        $this->createIndex(
            '{{%idx-currency_history-currency_id}}',
            '{{%currency_history}}',
            'currency_id'
        );

        // add foreign key for table `{{%currency}}`
        $this->addForeignKey(
            '{{%fk-currency_history-currency_id}}',
            '{{%currency_history}}',
            'currency_id',
            '{{%currency}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%currency}}`
        $this->dropForeignKey(
            '{{%fk-currency_history-currency_id}}',
            '{{%currency_history}}'
        );

        // drops index for column `currency_id`
        $this->dropIndex(
            '{{%idx-currency_history-currency_id}}',
            '{{%currency_history}}'
        );

        $this->dropTable('{{%currency_history}}');
    }
}
