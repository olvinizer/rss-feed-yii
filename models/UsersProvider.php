<?php


namespace app\models;

/**
 * UsersProvider provides access to users list, saved in JSON format
 * @package app\models
 */
class UsersProvider
{
    public $filepath;

    /**
     * UsersProvider constructor.
     * @param string $basePath JSON file location
     */
    public function __construct($basePath = null)
    {
        if (!$basePath) {
            $basePath = \Yii::getAlias('@app/resources');
        }
        if (!file_exists($basePath)) {
            mkdir($basePath);
        }
        $this->filepath = "$basePath/users.json";
    }

    public function getUser($email)
    {
        $users = $this->getUsers();
        return isset($users[$email]) ? $users[$email] : null;
    }

    public function addUser($id, $data)
    {
        $users = $this->getUsers();
        $users[$id] = $data;
        $this->saveUsers($users);
        return $data;
    }

    protected function getUsers()
    {
        $users = [];
        if (file_exists($this->filepath)) {
            $json = file_get_contents($this->filepath);
            $users = json_decode($json, true);
            if (!$users) {
                $users = [];
            }
        }
        return $users;
    }


    protected function saveUsers($users)
    {
        file_put_contents($this->filepath, json_encode($users));
    }
}
