<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Command\UpdateBook;

use common\CQS\Application\Book\Interface\BookAuthorAssignRepositoryInterface;
use common\CQS\Application\Book\Interface\BookRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;
use common\CQS\Domain\Interface\Storage\FileStorageInterface;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

final class UpdateBookHandler
{
    public function __construct(
        private BookRepositoryInterface             $bookRepository,
        private BookAuthorAssignRepositoryInterface $bookAuthorAssignRepository,
        private FileStorageInterface                $fileStorage
    )
    {
    }

    /**
     * @throws \yii\db\Exception
     * @throws NotFoundHttpException
     */
    public function run(UpdateBookCommand $command): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = $this->bookRepository->findOneById($command->id);

            if (!$model) {
                throw new NotFoundHttpException();
            }

            $command->uploadImg = UploadedFile::getInstance($command, 'uploadImg');

            if (!$command->validate()) {
                throw new ValidationException($command->getFirstErrorModel());
            }

            if ($command->uploadImg && file_exists($command->uploadImg->tempName)) {
                $this->fileStorage->delete($model->cover_image);

                $command->cover_image = $this->fileStorage->put(
                    $command->uploadImg->tempName,
                    $command->uploadImg->name
                );
            }

            $this->bookRepository->updateOrException($model, $command);

            // may be refactoring
            $this->bookAuthorAssignRepository->clearBookAuthors($model->id);
            foreach ($command->authorIdList as $authorId) {
                $this->bookAuthorAssignRepository->createOrException($authorId, $model->id);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw $e;
        }
    }
}