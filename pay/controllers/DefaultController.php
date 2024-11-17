<?php

namespace app\modules\pay\controllers;

use yii\web\Controller;
use app\modules\pay\models\Payment;

/**
 * Default controller for the `pay` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        $model = new Payment;
        $model->amount = 12575;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {

            return $this->render('thanks', ['model' => $model]);

        } else {

            $module = \Yii::$app->controller->module;
            \Stripe\Stripe::setApiKey($module->secret_key);

            $intent = \Stripe\PaymentIntent::create([
              'amount' => $model->amount,
              'currency' => 'usd',
            ]);
            $model->payment_id = $intent->id;

            return $this->render('index', ['model' => $model, 'intent' => $intent, 'publishable_key' => $module->publishable_key]);
        }

    }
}
