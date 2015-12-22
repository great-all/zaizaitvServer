<?php
namespace backend\controllers;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'backend\models\mysql\UserModel';
}
