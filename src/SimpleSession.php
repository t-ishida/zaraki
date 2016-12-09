<?php
namespace Zaraki;

/**
 * Class SimpleSession
 * @package Zaraki
 */
class SimpleSession implements Session
{

    /**
     * SimpleSession constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * @param array $values
     * @return mixed
     */
    public function set(array $values)
    {
        $_SESSION = $values;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $_SESSION;
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        return session_destroy();
    }

    /**
     * regenerate id
     */
    public function regenerateId()
    {
        return session_regenerate_id(true);
    }

    /**
     * write Close
     */
    public function flush()
    {
        return session_write_close();
    }
}