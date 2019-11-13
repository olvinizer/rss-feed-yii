<?php

namespace app\models;

use Yii;
use yii\base\Model;


class RegisterForm extends Model
{
    public $email;
    public $password;


    /**
     * Email and password must be filled.
     * Email acts as user ID un Username, and must be unique
     * Password must be at least 6 characters long
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'validateUniqueEmail'],
            ['password', 'validatePasswordStrength'],
        ];
    }

    public function validatePasswordStrength($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (strlen($this->password) < 6) {
                $this->addError($attribute, 'Password is too weak. Must be at least 6 symbols.');
            }
        }
    }

    public function validateUniqueEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findByUsername($this->email);
            if ($user) {
                $this->addError($attribute, 'This email is registered already.');
            }
        }
    }

    /**
     * If user data is valid, save it to storage and do auto login
     * @return bool
     */
    public function register()
    {
        if ($this->validate()) {
            User::addUser($this->email, $this->password);
            $user = User::findByUsername($this->email);
            return Yii::$app->user->login($user, 0);
        }
        return false;
    }


}
