<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Domain\Interface;

use common\CQS\Modules\Smspilot\Application\Command\SendSms\SendSmsCommand;

interface SmspilotHttpClientInterface
{
    public function runSendSmsCommand(SendSmsCommand $command): array;
}