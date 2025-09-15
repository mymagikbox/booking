<?php
declare(strict_types=1);

namespace backend\controllers;

use common\core\controller\BaseAdminController;
use common\CQS\Application\Author\Command\CreateAuthor\CreateAuthorCommand;
use common\CQS\Application\Author\Command\CreateAuthor\CreateAuthorHandler;
use common\CQS\Application\Author\Command\DeleteAuthor\DeleteAuthorCommand;
use common\CQS\Application\Author\Command\DeleteAuthor\DeleteAuthorHandler;
use common\CQS\Application\Author\Command\UpdateAuthor\UpdateAuthorCommand;
use common\CQS\Application\Author\Command\UpdateAuthor\UpdateAuthorHandler;
use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use Exception;
use kartik\alert\AlertInterface;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AuthorController extends BaseAdminController
{
    public function __construct(
        $id,
        $module,
        $config = [],
        private CreateAuthorHandler $createAuthorHandler,
        private UpdateAuthorHandler $updateAuthorHandler,
        private DeleteAuthorHandler $deleteAuthorHandler,
        private AuthorRepositoryInterface $authorRepository,
    )
    {
        parent::__construct($id, $module, $config);
    }


    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionCreate(): string|Response
    {
        $command = new CreateAuthorCommand();

        if (Yii::$app->request->isPost) {
            if ($command->load(Yii::$app->request->post())) {
                try {
                    $this->createAuthorHandler->run($command);

                    Yii::$app->session->setFlash(AlertInterface::TYPE_SUCCESS, $this->getSuccessCreateMessage());

                    return $this->redirect(['index']);
                } catch (Exception $e) {
                    Yii::$app->session->setFlash(AlertInterface::TYPE_DANGER, $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $command,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->authorRepository->findOneById($id);

        if(!$model) {
            throw new NotFoundHttpException();
        }

        $command = UpdateAuthorCommand::create($id);

        if (Yii::$app->request->isPost) {
            if ($command->load(Yii::$app->request->post())) {
                try {
                    $this->updateAuthorHandler->run($command);

                    Yii::$app->session->setFlash(AlertInterface::TYPE_SUCCESS, $this->getSuccessUpdateMessage());
                    return $this->redirect(['index']);
                } catch (Exception $e) {
                    Yii::$app->session->setFlash(AlertInterface::TYPE_DANGER, $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }



    public function actionDelete(int $id)
    {
        if (Yii::$app->request->isPost) {
            $command = DeleteAuthorCommand::create($id);

            if ($command->load(Yii::$app->request->post())) {
                try {
                    $this->deleteAuthorHandler->run($command);

                    Yii::$app->session->setFlash(AlertInterface::TYPE_SUCCESS, $this->getSuccessDeleteMessage());
                    return $this->redirect(['index']);
                } catch (Exception $e) {
                    Yii::$app->session->setFlash(AlertInterface::TYPE_DANGER, $e->getMessage());
                }
            }
        }

        $model = $this->authorRepository->findOneById($id);

        if(!$model) {
            throw new NotFoundHttpException();
        }

        return $this->render('delete', [
            'model' => $model
        ]);
    }
}