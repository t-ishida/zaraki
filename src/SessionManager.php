<?php
namespace Zaraki;

/**
 * Class SessionManager
 * @package Zaraki
 */
class SessionManager
{
    private $session = null;
    private $values = null;
    private $flushed = false;

    /**
     * SessionManager constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->values = $session->get();
        $this->values or $this->values = [];
        isset($this->values['__stash'])   or $this->values['__stash']   = [];
        isset($this->values['__session']) or $this->values['__session'] = [];
    }

    /**
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public function get($key, $default = null)
    {
        $result = $this->query($this->values['__stash'], $key, $default);
        unset($this->values['__stash'][$key]);
        return $result;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->values['__stash'] = $this->setRecursive($this->values['__stash'], $key, $value);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setPermanent($key, $value)
    {
        $this->values['__session'] = $this->setRecursive($this->values['__session'], $key, $value);
    }

    /**
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public function getPermanent($key, $default = null)
    {
        return $this->query($this->values['__session'], $key, $default);
    }

    /**
     * @param array $values
     * @param $key
     * @param $value
     * @return array
     */
    public function setRecursive(array $values, $key, $value)
    {
        if (!isset($key) || !is_string($key)) {
            throw new \InvalidArgumentException('$key is undefined or not string');
        }
        if (is_scalar($key) && strpos($key, '.') === false) {
            $values[$key] = $value;
        } else {
            $keys = explode('.', $key);
            $key  = array_shift($keys);
            isset($values[$key]) or $values[$key] = [];
            $values[$key] = $this->setRecursive($values[$key], implode('.', $keys), $value);
        }
        return $values;
    }

    /**
     * @param array $values
     * @param $key
     * @param $default
     * @return array|mixed|null
     */
    public function query(array $values, $key, $default)
    {
        if (!isset($key) || !is_string($key)) {
            throw new \InvalidArgumentException('$key is undefined or not string');
        }
        if (isset($values[$key])) return $values[$key];
        $tmp = $values;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($tmp) || !isset($tmp[$segment])){
                $tmp = null;
                break;
            } elseif (isset($tmp[$segment])) {
                $tmp = $tmp[$segment];
            } else {
                $tmp = null;
                break;
            }
        }
        return isset($tmp) ? $tmp : $default;
    }

    /**
     * regenerate id
     */
    public function regenerateId()
    {
        $this->session->regenerateId();
    }

    /**
     * flush
     */
    public function flush()
    {
        if ($this->flushed) {
            return ;
        }
        $this->flushed = true;
        if (!$this->values['__stash'] && !$this->values['__session']) {
            $this->session->clear();
        } else {
            $this->session->set($this->values);
            $this->session->flush();
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    public function __destruct()
    {
        $this->flush();
    }
}