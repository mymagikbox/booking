<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Application\Command\SendSms;

use common\CQS\Domain\Interface\ArrayableInterface;

class SendSmsCommand implements ArrayableInterface
{
    public function __construct(
        private string $message,
        private int $phone,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'send' => $this->message,
            'phone' => $this->phone,
        ];
    }
}