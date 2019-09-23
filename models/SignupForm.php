<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $username;
    public $firsname;
    public $lastname;
    public $password;
    public $rememberMe = true;


    /**
     * @return array the validation rules.
     */

     public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
        ];
    }

    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password','firstname','lastname'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean']
        ];
    }

      public static function tableName()
    {
        return 'signupform';
    }

}