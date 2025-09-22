<?php

namespace common\CQS\Application\Author\Command\DeleteAuthor;

use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Application\Book\Interface\BookAuthorAssignRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;

final class DeleteAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface           $authorRepository,
        private BookAuthorAssignRepositoryInterface $bookAuthorAssignRepository,
    )
    {
    }

    public function run(DeleteAuthorCommand $command): void
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

            $this->authorRepository->deleteOrException($model);
            $this->bookAuthorAssignRepository->clearAuthorFromBooks($model->id);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw $e;
        }
    }
}