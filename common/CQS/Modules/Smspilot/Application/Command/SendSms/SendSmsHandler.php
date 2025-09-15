<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Application\Command\SendSms;


use common\CQS\Modules\Smspilot\Application\Command\SendSms\Respond\SendSmsRespond;
use common\CQS\Modules\Smspilot\Domain\Interface\SmspilotHttpClientInterface;
use Throwable;
use Yii;

class SendSmsHandler
{
    public function __construct(
        private readonly SmspilotHttpClientInterface $httpClient
    )
    {
    }

    public function run(SendSmsCommand $command): ?SendSmsRespond
    {
        try {
            $respondData = $this->httpClient->runSendSmsCommand($command);

            return SendSmsRespond::fromRespond($respondData);
        } catch (Throwable $e) {
            Yii::error(self::class . " error class.\n
                Detail:\n
                {$e->getTraceAsString()}\n
                Message:\n
                {$e->getMessage()}\n
            ");

            throw $e;
        }
    }
}