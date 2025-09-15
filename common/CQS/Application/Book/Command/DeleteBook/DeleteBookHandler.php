<?php

namespace common\CQS\Application\Book\Command\DeleteBook;

use common\CQS\Application\Book\Interface\BookAuthorAssignRepositoryInterface;
use common\CQS\Application\Book\Interface\BookRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;
use common\CQS\Domain\Interface\Storage\FileStorageInterface;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;

final class DeleteBookHandler
{
    public function __construct(
        private BookRepositoryInterface             $bookRepository,
        private BookAuthorAssignRepositoryInterface $bookAuthorAssignRepository,
        private FileStorageInterface                $fileStorage
    )
    {

    }

    public function run(DeleteBookCommand $command): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$command->validate()) {
                throw new ValidationException($command->getFirstErrorModel());
            }

            $model = $this->bookRepository->findOneById($command->id);

            if (!$model) {
                throw new NotFoundHttpException();
            }

            $this->bookRepository->deleteOrException($model);
            $this->bookAuthorAssignRepository->clearBookAuthors($model->id);

            $this->fileStorage->delete($model->cover_image);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw new $e;
        }
    }
}