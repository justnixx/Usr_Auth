<?php

namespace App\Classes;

class User
{
    private $_db,
        $_sessionName,
        $_cookieName,
        $_cookieLifeTime,
        $_isLoggedIn = false,
        $_data,
        $_sessionTable = 'users_session',
        $_table = 'users';

    /**
     * Initializes the User
     */
    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('SESSION_NAME');
        $this->_cookieName = Config::get('COOKIE_NAME');
        $this->_cookieLifeTime = Config::get('COOKIE_LIFETIME');

        if (!$user && Session::has($this->_sessionName)) {
            $this->_isLoggedIn = true;
            $this->_data = $this->find(Session::get($this->_sessionName))->first();
        } else if ($user) {
            $this->_data = $this->find($user)->first();
        }
    }

    /**
     * Creates a new user
     *
     * @param array $data
     * @return boolean
     */
    public function create(array $data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    /**
     * Updates user information
     *
     * @param integer $userId
     * @param array $data
     * @return boolean
     */
    public function update(int $userId, array $data)
    {
        return $this->_db->update($this->_table, $userId, $data);
    }

    /**
     * Attempts to log user in
     *
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function login(string $username = null, string $password = null, $rememberMe = false)
    {
        if (!$username && !$password && Cookie::has($this->_cookieName)) {
            $cookieToken = Cookie::get($this->_cookieName);
            $storedToken = $this->_db->get($this->_sessionTable, [], ['token', '=', $cookieToken]);

            if (1 == $storedToken->count()) {
                Session::put(Config::get('SESSION_NAME'), $storedToken->first()->user_id);
                return true;
            }
        }

        if ($this->_db->exists($this->_table, ['username', '=', $username])) {
            $user = $this->find($username)->first();
            $hash = $user->password;

            if (Hash::check($password, $hash)) {
                if ($rememberMe) {
                    $token = Hash::rand();

                    if (!$this->_db->exists($this->_sessionTable, ['user_id', '=', $user->id])) {
                        $this->_db->insert($this->_sessionTable, [
                            'user_id' => $user->id,
                            'token'   => $token
                        ]);
                    } else {
                        $token = $this->_db->get($this->_sessionTable, ['token'], ['user_id', '=', $user->id])->first()->token;
                    }

                    Cookie::put($this->_cookieName, $token, $this->_cookieLifeTime);
                }

                Session::put(Config::get('SESSION_NAME'), $user->id);
                return true;
            }
        }

        return false;
    }

    /**
     * Attempts to log a user out
     *
     * @return boolean
     */
    public function logout()
    {
        if (Session::has($this->_sessionName)) {
            $this->_db->delete($this->_sessionTable, ['user_id', '=', $this->data()->id]);

            Cookie::delete($this->_cookieName);
            Session::delete($this->_sessionName);
            return true;
        }

        return false;
    }

    /**
     * Finds a user with the given id
     *
     * @param integer $id
     * @return object
     */
    public function find($user = null)
    {
        if (!$user) {
            return;
        }

        $field = (is_numeric($user)) ? 'id' : 'username';
        return $this->_db->get($this->_table, [], [$field, '=', $user]);
    }

    /**
     * Returns the user data
     *
     * @return object
     */
    public function data()
    {
        return $this->_data;
    }

    /**
     * Returns true if the user is logged in, otherwise false
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}