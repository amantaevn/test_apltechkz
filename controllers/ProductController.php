<?php


namespace app\controllers;

use app\models\Product;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class ProductController extends ActiveController
{
    public $modelClass = Product::class;

    public function ViewAction($id)
    {
        return Product::FindOne($id);
    }

    public function actionIndex()
    {
        $products = Product::findProductTable();
        if (count($products) > 0) {
            return array('status' => true, 'data' => $products);
        } else {
            return array('status' => false, 'data' => 'Данные не найдены');
        }
    }

    public function actionCreate()
    {
        $product = new Product();
        $product->scenario = Product:: SCENARIO_CREATE;
        $product->attributes = \yii::$app->request->post();
        if ($product->validate()) {
            $product->save();
            return array('status' => true, 'data' => 'Student record is successfully updated');
        } else {
            return array('status' => false, 'data' => $product->getErrors());
        }
    }

    public function actionUpdate($id)
    {
        $product = Product::find()->where(['ID' => $id])->one();
        if (count($product) > 0) {
            $product->attributes = \yii::$app->request->post();
            $product->save();
            return array('status' => true, 'data' => 'Product record is updated successfully');
        } else {
            return array('status' => false, 'data' => 'Product not Found');
        }
    }

    public function actionDelete($id)
    {
        $product = Product::find()->where(['ID' => $id])->one();
        if (count($product) > 0) {
            $product->delete();
            return array('status' => true, 'data' => 'Product record is successfully deleted');
        } else {
            return array('status' => false, 'data' => 'Product Not Found');
        }
    }

    public function actionBrand()
    {
        $brand_name = Yii::$app->request->get('message');
        $products = Product::findMaxAndMinPrice($brand_name);
        if (count($products) > 0) {
            return array('status' => true, 'data' => $products);
        } else {
            return array('status' => false, 'data' => 'Данные не найдены');
        }
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'update' || $action === 'delete' || $action === 'create') {
            if ($this->checkAuthorized())
                throw new \yii\web\ForbiddenHttpException(sprintf('Action %s не доступен для не авторизованных пользователей', $action));
        }
    }

    private function checkAuthorized()
    {
        Yii::$app->user->isGuest ? true : false;
    }
}