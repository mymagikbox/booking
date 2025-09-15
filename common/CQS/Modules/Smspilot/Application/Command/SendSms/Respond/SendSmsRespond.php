<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Application\Command\SendSms\Respond;

use Exception;
use RuntimeException;

/**
 * success send
 * {"send":[
* {"server_id":"9316849","phone":"79087964781","price":"1.31","status":"0"}
* ], "balance":"2935.50", "cost":"1.31", "server_packet_id":"9316849"}
 * -----------
 * {"error": {"code": "111", "description": "Invalid phone", "description_ru": "Неправильный номер телефона"}}
 */
class SendSmsRespond
{
    /**
     * @param SmsRespondSendItem[] $send
     * @param float $balance
     * @param float $cost
     * @param float $serverPacketId
     */
    public function __construct(
        private array $send,
        private float $balance,
        private float $cost,
        private float $serverPacketId,
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromRespond(array $data): ?self
    {
        if(count($data) && isset($data['send'])) {
            return new self(
                array_map(function (array $item) {
                    return SmsRespondSendItem::fromRespond($item);
                }, $data['send']),
                $data['balance'],
                $data['cost'],
                $data['server_packet_id'],
            );
        } else {
            if(isset($data['error']['description_ru'])) {
                throw new Exception("Error to send sms, detail: " . $data['error']['description_ru']);
            } else {
                throw new RuntimeException('Error to send sms without respond');
            }
        }

        return null;
    }

    public function getSend(): array
    {
        return $this->send;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function getServerPacketId(): float
    {
        return $this->serverPacketId;
    }
}