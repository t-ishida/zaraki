<?php
namespace Zaraki;

/**
 * Interface Session
 * @package Zaraki
 */
interface Session
{
    /**
     * @param array $values
     * @return mixed
     */
    public function set(array $values);

    /**
     * @return array
     */
    public function get();

    /**
     * destroy session
     */
    public function clear();


    /**
     * regenerate id
     */
    public function regenerateId();

    public function flush();
}