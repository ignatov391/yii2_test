<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency_history".
 *
 * @property int $id
 * @property int|null $nominal
 * @property float $value
 * @property int $currency_id
 * @property string $created_at
 *
 * @property Currency $currency
 */
class CurrencyHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'currency_id', 'created_at'], 'required'],
            [['nominal', 'currency_id'], 'default', 'value' => null],
            [['nominal', 'currency_id'], 'integer'],
            [['value'], 'number'],
            [['created_at'], 'safe'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nominal' => 'Nominal',
            'value' => 'Value',
            'currency_id' => 'Currency ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public function fields()
    {
        // $fields = parent::fields();
        return [
            'nominal',
            'value',
            'created_at',
        ];
    }

}
