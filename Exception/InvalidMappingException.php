<?php

declare(strict_types=1);

namespace steevanb\DevBundle\Exception;

class InvalidMappingException extends \Exception
{
    public function __construct(string $em, string $error)
    {
        parent::__construct('[EntityManager : ' . $em . '] ' . $error);
    }
}
