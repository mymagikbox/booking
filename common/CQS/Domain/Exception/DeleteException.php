<?php
declare(strict_types=1);

namespace common\CQS\Domain\Exception;

use DomainException;
use JetBrains\PhpStorm\Pure;
use Throwable;

class DeleteException extends DomainException
{
    #[Pure]
    public function __construct(
        string $message = "Can not delete model",
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}