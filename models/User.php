<?php

namespace app\models;

/**
 * Class User use UsersProvider as storage for access to users storage
 * @package app\models
 */
class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    protected static $usersProvider;

    /**
     * For users storage used JSON file. UsersProvider provides access to it.
     * @return UsersProvider
     */
    public static function getUsersProvider()
    {
        if (!self::$usersProvider) {
            self::$usersProvider = new UsersProvider();
        }
        return self::$usersProvider;
    }

    public static function addUser($username, $password)
    {
        $data = compact('username', 'password');
        $data['id'] = $username;
        return self::getUsersProvider()->addUser($username, $data);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $data = self::getUsersProvider()->getUser($id);
        return $data ? new static($data) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findIdentity($token);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findIdentity($username);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
