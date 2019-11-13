<?php

namespace app\controllers;

use app\models\RegisterForm;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;

class RegisterController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();

        $modelLoaded = $model->load(Yii::$app->request->post());
        if ($modelLoaded) {
            if (Yii::$app->request->isAjax) {
                $model->validateUniqueEmail('email', []);
                $result = [Html::getInputId($model, 'email') => $model->getErrors('email')];
                return $this->asJson($result);
            } else if ($model->register()) {
                return $this->goHome();
            }
        }

        $model->password = '';
        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
