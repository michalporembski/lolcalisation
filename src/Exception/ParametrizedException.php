<?php

namespace App\Exception;

/**
 * Class ParametrizedException
 *
 * @package App\Exception
 */
class ParametrizedException extends \Exception
{
    /**
     * @var array
     */
    private $params;

    /**
     * ParametrizedException constructor.
     *
     * @param string $message message
     * @param array $params params
     * @param int $code code
     * @param \Exception|null $previous previous exception
     */
    public function __construct($message, $params = [], $code = 0, \Exception $previous = null)
    {
        $this->params = $params;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get exception params
     *
     * @return array
     */
    final public function getParams(): array
    {
        return $this->params;
    }
}
