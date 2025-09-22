<?php
declare(strict_types=1);

namespace console\controllers;

use common\CQS\Domain\Interface\Event\AsyncEventConsumerInterface;
use Throwable;
use yii\console\ExitCode;

/***
 * php yii consume-event/run
 */
class ConsumeEventController extends BaseController
{
    public function __construct(
        $id,
        $module,
        private AsyncEventConsumerInterface $asyncEventConsumer,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function actionRun(): int
    {
        $this->writeInfo('Starting event consumer...');

        // try {
            $this->asyncEventConsumer->consume();
        /*} catch (Throwable $e) {
            $this->writeError('Error: ' . $e->getMessage());

            return ExitCode::UNSPECIFIED_ERROR;
        }*/

        return ExitCode::OK;
    }
}