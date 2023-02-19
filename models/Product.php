<?php


namespace app\models;
use yii\db\ActiveRecord;

class Product  extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const IN_STOCK = 1;
    const ON_ORDER = 2;

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

    public static function findMaxAndMinPrice($brand_name)
    {
        $data = [];
        $max_product = self::find()->where(['brand_name' => $brand_name])->orderBy(['price' => SORT_DESC])->one();
        $min_product = self::find()->where(['brand_name' => $brand_name])->orderBy(['price' => SORT_ASC])->one();
        $data['min']['id'] = $min_product->id;
        $data['min']['name'] = $min_product->name;
        $data['min']['price'] = $min_product->price;
        $data['max']['id'] = $max_product->id;
        $data['max']['name'] = $max_product->name;
        $data['max']['price'] = $max_product->price;

        return $data;
    }

    public static function findProductTable()
    {
        $data = [];
        $products = self::find()->all();
        foreach ($products as $product) {
            $data[$product->id]['name'] = $product->name;
            $data[$product->id]['category_name'] = $product->name;
            $data[$product->id]['brand_name'] = $product->brand_name;
            $data[$product->id]['price'] = $product->price;
            $data[$product->id]['status'] = 'В наличии';
            if (in_array($product->name, ['Dell', 'Lenovo']))
            {
                $data[$product->id]['price'] = $product->rrp_price;
            }
            if ($product->status == self::IN_STOCK) {
                $data[$product->id]['status'] = 'Под заказ';
                $data[$product->id]['price'] = 'Цена по запросу';
            }
        }
        return $data;
    }
}