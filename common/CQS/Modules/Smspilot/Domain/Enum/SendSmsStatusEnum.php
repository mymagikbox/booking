<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Domain\Enum;

enum SendSmsStatusEnum: int
{
    case STATUS_ERROR = -2; // ошибка
    case STATUS_ACCEPT = 0; // принято
    case STATUS_POSTPONED = 3; // отложено
}
