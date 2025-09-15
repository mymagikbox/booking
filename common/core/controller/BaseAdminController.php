<?php

namespace common\core\controller;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class BaseAdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        /*'matchCallback' => function () {
                            return Yii::$app->user->getIdentity()->haveAccessToAdminPanel();
                        },*/
                    ]
                ],
            ],
        ];
    }

    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            if ($action->id == 'error') {
                if (Yii::$app->user->isGuest) {
                    $this->layout = 'error';
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    function getSuccessCreateMessage(): string
    {
        return 'Success created';
    }

    function getSuccessUpdateMessage(): string
    {
        return 'Success updated';
    }

    function getSuccessDeleteMessage(): string
    {
        return 'Success deleted';
    }
}