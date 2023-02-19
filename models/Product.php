<?php


namespace app\models;
use yii\db\ActiveRecord;

class Product  extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return '{{%product}}';
    }

    public function rules()
    {
        return [
            [['name', 'category_name', 'brand_name', 'price', 'rrp_price', 'status'], 'required'],
            [['name', 'category_name', 'brand_name'], 'string', 'max' => 250]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'category_name', 'brand_name', 'price', 'rrp_price', 'status'];
        return $scenarios;
    }
}