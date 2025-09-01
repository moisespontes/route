<?php

namespace DevPontes\Route\Exception;

use Exception;
use Throwable;

/**
 * Class DevPontes ErrorRoute
 *
 * @author Moises Pontes <sesiom_assis@hotmail.com>
 * @package DevPontes\Route\Exception
 */
class ErrorRoute extends Exception
{
    /**
     * ErrorRoute constructor.
     *
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
