<?php

namespace App\Classes;

use Exception;
use PDO;
use PDOException;

/**
 * DB class
 */
class DB
{
    private static $_instance = null;

    private $_pdo,
        $_stmt,
        $_resultSet,
        $_count = 0,
        $_error = false;

    private function __construct()
    {
        try {
            // Data source name
            $dsn = 'mysql:host=' . Config::get('DB_HOST') . ';port=' . Config::get('DB_PORT') . ';dbname=' . Config::get('DB_DATABASE');

            // Options 
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ];

            // Connect to the database
            $this->_pdo = new PDO($dsn, Config::get('DB_USERNAME'), Config::get('DB_PASSWORD'), $options);
        } catch (PDOException $e) {
            exit('Connection Failed: ' . $e->getMessage());
        }
    }


    /**
     * Returns an instance of the DB::class
     *
     * @return object
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Performs database query
     *
     * @param string $sql
     * @param array $params
     * @return object
     */
    public function query(string $sql, $params = [])
    {
        try {
            // Reset error
            $this->_error = false;

            // Prepare statement
            $this->_stmt = $this->_pdo->prepare($sql);

            if (count($params)) {
                $index = 1; // param counter

                // Bind values to parameters if any
                foreach ($params as $value) {
                    $this->_stmt->bindValue($index, $value);
                    $index++;
                }
            }

            // Execute the prepared statement
            $this->_stmt->execute();

            // Set resultset
            if (stripos($sql, 'SELECT') === 0) {
                $this->_resultSet = $this->_stmt->fetchAll();
            }

            // Set count
            $this->_count = $this->_stmt->rowCount();
        } catch (PDOException $e) {
            // Update error
            $this->_error = true;

            echo 'Error: ' . $e->getMessage();
        }

        return $this;
    }

    /**
     * Performs an action on a database table
     *
     * @param string $action
     * @param string $table
     * @param array $fields
     * @param array $where
     * @return boolean|object
     */
    public function action(string $action, string $table, $fields = [], $where = [])
    {
        $sql = "{$action}";

        if (count($fields)) {
            foreach ($fields as $field) {
                $sql = str_replace('*', '', $sql) . "{$field}";
                if (next($fields)) {
                    $sql .= ", ";
                }
            }
        }

        $sql .= " FROM `{$table}`";

        if (count($where) === 3) {
            $field     = $where[0];
            $operator  = $where[1];
            $value     = $where[2];
            $operators = ['=', '>', '<', '>=', '<=', '!='];

            if (!in_array($operator, $operators)) {
                throw new Exception("Unsupported operator {$operator} passed.");
            }

            $sql .= " WHERE {$field} {$operator} ?";
        }

        if (!$this->query($sql, [$value ?? null])->error()) {
            return $this;
        }

        return false;
    }

    /**
     * Inserts a record into a database table
     *
     * @param string $table
     * @param array $data
     * @return boolean
     */
    public function insert(string $table, array $data)
    {
        if (count($data)) {
            $fields = '`';
            $values = '';

            $keys = array_keys($data);
            $fields .= implode('`, `', $keys) . '`';

            foreach ($data as $value) {
                $values .= '?';

                if (next($data)) {
                    $values .= ', ';
                }
            }

            $sql = "INSERT INTO `{$table}` ({$fields}) VALUES ({$values})";

            if (!$this->query($sql, $data)->error()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Updates a record on the database table
     *
     * @param string $table
     * @param integer $id
     * @param array $data
     * @return boolean
     */
    public function update(string $table, int $id, array $data)
    {
        if (count($data)) {
            $set = '';

            foreach ($data as $field => $value) {
                $set .= "`{$field}` = ?";

                if (next($data)) {
                    $set .= ", ";
                }
            }

            $sql = "UPDATE `{$table}` SET {$set} WHERE `id` = ?";

            if (!$this->query($sql, array_merge($data, [$id]))->error()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the result set rows
     *
     * @param string $table
     * @param array $fields
     * @param array $where
     * @return boolean|object
     */
    public function get(string $table, $fields = [], $where = [])
    {
        try {
            return $this->action('SELECT *', $table, $fields, $where);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Returns true if a record exists in a database table, otherwise false
     *
     * @param string $table
     * @param array $where
     * @return boolean
     */
    public function exists(string $table, $where = [])
    {
        $get = $this->get($table, [], $where);

        if ($get && $this->count()) {
            return true;
        }

        return false;
    }

    /**
     * Deletes a row from the database table
     *
     * @param string $table
     * @param array $where
     * @return boolean|object
     */
    public function delete(string $table, $where = [])
    {
        try {
            return $this->action('DELETE', $table, [], $where);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Returns an array containing the result set rows
     *
     * @return array
     */
    public function result()
    {
        return $this->_resultSet;
    }

    /**
     * Returns the first row from the result set rows
     *
     * @return void
     */
    public function first()
    {
        return $this->result()[0];
    }

    /**
     * Returns an array containing the result set rows
     *
     * An alias of result() method
     * 
     * @return array
     */
    public function all()
    {
        return $this->result();
    }

    /**
     * Returns the number of rows affected by the last query
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Returns error
     *
     * @return boolean
     */
    public function error()
    {
        return $this->_error;
    }
}