<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\ActiveRecord\Repository;

use common\CQS\Application\Author\Command\CreateAuthor\CreateAuthorCommand;
use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Application\Author\Command\UpdateAuthor\UpdateAuthorCommand;
use common\CQS\Domain\Entity\Author;
use common\CQS\Domain\Exception\DeleteException;
use common\CQS\Domain\Exception\SaveException;
use yii\helpers\ArrayHelper;

final class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAllAuthorIdNameList(): array
    {
        return ArrayHelper::map(
            Author::find()->All(),
            'id',
            'username'
        );
    }

    public function findOneById(int $id): Author
    {
        return Author::find()
            ->where(['id' => $id])
            ->one();
    }

    public function createOrException(CreateAuthorCommand $command): Author
    {
        $model = new Author($command->getAttributes());

        if(!$model->save(false)) {
            throw new SaveException();
        }

        return $model;
    }

    public function updateOrException(Author $model, UpdateAuthorCommand $command): void
    {
        $model->setAttributes($command->getAttributes());

        if(!$model->save(false)) {
            throw new SaveException();
        }
    }

    public function deleteOrException(Author $model): void
    {
        $delRows = $model->delete();

        if(!$delRows) {
            throw new DeleteException();
        }
    }
}