<?php


namespace app\models;
use yii\db\ActiveRecord;

class Product  extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
}