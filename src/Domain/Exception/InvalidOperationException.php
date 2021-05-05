<?php
declare(strict_types=1);

namespace Tpay\Domain\Exception;

class InvalidOperationException extends \Exception
{
    public static function transferredAmountsNotEqual(): InvalidOperationException
    {
        return new self('Balance in the operation is not equal to 0. Amount subtracted from payer account should be transferred to corresponding receiver account.');
    }

    public static function payerAccountHasInsufficientBalance(): InvalidOperationException
    {
        return new self('Cannot execute transfer. Not enough funds');
    }
}
