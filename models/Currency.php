<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string|null $name
 * @property string $remoteID
 * @property int $numCode
 * @property string $charCode
 * @property int|null $nominal
 * @property float|null $lastValue
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CurrencyHistory[] $currencyHistories
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remoteID', 'numCode', 'charCode', 'created_at', 'updated_at'], 'required'],
            [['numCode', 'nominal'], 'default', 'value' => null],
            [['numCode', 'nominal'], 'integer'],
            [['lastValue'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['remoteID'], 'string', 'max' => 32],
            [['charCode'], 'string', 'max' => 8],
            [['numCode'], 'unique'],
            [['remoteID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'remoteID' => 'Remote ID',
            'numCode' => 'Num Code',
            'charCode' => 'Char Code',
            'nominal' => 'Nominal',
            'lastValue' => 'Last Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CurrencyHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyHistories()
    {
        return $this->hasMany(CurrencyHistory::className(), ['currency_id' => 'id']);
    }

    public function fields()
    {
        $fields = parent::fields();

        // подмена имени
        $fields['value'] = $fields['lastValue'];
        // удаляем небезопасные поля
        unset($fields['id'], $fields['created_at'], $fields['lastValue']);

        return $fields;
    }

    /**
     * @return array|string[]
     */
    public function extraFields()
    {
        /* TODO реализовать */
        return ['currencyHistories'];
    }
}
