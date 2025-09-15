<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Application\Command\SendSms\Respond;

use common\CQS\Modules\Smspilot\Domain\Enum\SendSmsStatusEnum;

/**
 * {"server_id":"9316849","phone":"79087964781","price":"1.31","status":"0"}
 */
class SmsRespondSendItem
{
    public function __construct(
        private int               $serverId,
        private int               $phone,
        private float             $price,
        private SendSmsStatusEnum $status,
        private ?string           $error = null,
    )
    {
    }

    public static function fromRespond(array $data): ?self
    {
        if (count($data)) {
            return new self(
                $data['server_id'],
                $data['phone'],
                $data['price'],
                SendSmsStatusEnum::from($data['status']),
                $data['error_ru'] ?? null,
            );
        }

        return null;
    }

    public function getServerId(): int
    {
        return $this->serverId;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStatus(): SendSmsStatusEnum
    {
        return $this->status;
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}