<?php
declare(strict_types=1);

namespace common\CQS\Application\Author\Command\UpdateAuthor;

use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;

final class UpdateAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
    )
    {
    }

    /**
     * @throws \yii\db\Exception
     * @throws NotFoundHttpException
     */
    public function run(UpdateAuthorCommand $command): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$command->validate()) {
                throw new ValidationException($command->getFirstErrorModel());
            }

            $model = $this->authorRepository->findOneById($command->id);

            if (!$model) {
                throw new NotFoundHttpException();
            }

            $this->authorRepository->updateOrException($model, $command);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw new $e;
        }
    }
}