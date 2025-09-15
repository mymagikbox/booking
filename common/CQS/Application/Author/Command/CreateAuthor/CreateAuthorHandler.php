<?php
declare(strict_types=1);

namespace common\CQS\Application\Author\Command\CreateAuthor;

use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;
use Exception;
use Yii;

final class CreateAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
    )
    {
    }

    public function run(CreateAuthorCommand $command): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (!$command->validate()) {
                throw new ValidationException($command->getFirstErrorModel());
            }

            $this->authorRepository->createOrException($command);

            // may be Event CreateAuthorEvent for client notice

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw new $e;
        }
    }
}