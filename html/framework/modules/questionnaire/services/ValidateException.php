<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 02.11.17
 * Time: 15:44
 */

namespace app\modules\questionnaire\services;


use DomainException;
use Throwable;

/**
 * Class ValidateException
 * @package app\modules\questionnaire\services
 */
class ValidateException extends DomainException
{
    /**
     * @var array
     */
    private $errors;

    public function __construct(
        array $errors,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}