<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m200523_105919_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'remoteID' => $this->string('32')->notNull()->unique(),
            'numCode' => $this->integer('16')->notNull()->unique(),
            'charCode' => $this->string('8')->notNull(),
            'nominal' => $this->integer(),
            'lastValue' => $this->float(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            //remoteID <Valute ID="R01010">
            //numCode <NumCode>036</NumCode>
            //chainCode <CharCode>AUD</CharCode>
            //nominal <Nominal>1</Nominal>
            //name <Name>Австралийский доллар</Name>
            //value <Value>46,8373</Value>
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
    }
}
