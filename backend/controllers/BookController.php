<?php
declare(strict_types=1);

namespace backend\controllers;

use common\core\controller\BaseAdminController;
use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Application\Book\Command\CreateBook\CreateBookCommand;
use common\CQS\Application\Book\Command\CreateBook\CreateBookHandler;
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookCommand;
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookHandler;
use Exception;
use kartik\alert\AlertInterface;
use Yii;
use yii\web\Response;

class BookController extends BaseAdminController
{
    public function __construct(
        $id,
        $module,
        private CreateBookHandler $createBookHandler,
        private UpdateBookHandler $updateBookHandler,
        private AuthorRepositoryInterface $authorRepository,
        $config = [],
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
        $command = new CreateBookCommand();

        if (Yii::$app->request->isPost) {
            if ($command->load(Yii::$app->request->post())) {
                try {
                    $this->createBookHandler->run($command);

                    Yii::$app->session->setFlash(AlertInterface::TYPE_SUCCESS, $this->getSuccessCreateMessage());

                    return $this->redirect(['index']);
                } catch (Exception $e) {
                    Yii::$app->session->setFlash(AlertInterface::TYPE_DANGER, $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $command,
            'authorIdNameList' => $this->authorRepository->getAllAuthorIdNameList(),
        ]);
    }

    public function actionUpdate(int $id)
    {
        if (Yii::$app->request->isPost) {
            $command = UpdatebookCommand::create($id);

            if ($command->load(Yii::$app->request->post())) {
                try {
                    $this->updateBookHandler->run($command);

                    Yii::$app->session->setFlash(AlertInterface::TYPE_SUCCESS, $this->getSuccessUpdateMessage());
                    return $this->redirect(['index']);
                } catch (Exception $e) {
                    Yii::$app->session->setFlash(AlertInterface::TYPE_DANGER, $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'authorIdNameList' => $this->authorRepository->getAllAuthorIdNameList(),
        ]);
    }

    public function actionDelete($id)
    {

    }
}