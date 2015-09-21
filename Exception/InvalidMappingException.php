<?php

namespace steevanb\DevBundle\Exception;

class InvalidMappingException extends \Exception
{
    /**
     * @param string $em
     * @param string $error
     */
    public function __construct($em, $error)
    {
        parent::__construct('[EntityManager : ' . $em . '] ' . $error);
    }
}
