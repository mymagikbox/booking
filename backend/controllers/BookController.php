<?php
declare(strict_types=1);

namespace backend\controllers;

use common\core\controller\BaseAdminController;
use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Application\Book\Command\CreateBook\CreateBookCommand;
use common\CQS\Application\Book\Command\CreateBook\CreateBookHandler;
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookCommand;
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookHandler;
use common\CQS\Application\Book\Event\BookCreatedEvent;
use common\CQS\Domain\Interface\Event\AsyncEventDispatcherInterface;
use common\CQS\Domain\Interface\Event\SyncEventDispatcherInterface;
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
        private AsyncEventDispatcherInterface $asyncEventDispatcher,
        private SyncEventDispatcherInterface $syncEventDispatcher,
        $config = [],
    )
    {
        parent::__construct($id, $module, $config);
    }


    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionTest()
    {
        $id = 12;
        $title = 'Book 4';
        $authorIdList = [
            1, 3, 5
        ];

        try {
            $this->asyncEventDispatcher->dispatch(
                new BookCreatedEvent(
                    $id,
                    $title,
                    $authorIdList,
                ),
                BookCreatedEvent::eventName()
            );
            print_r("Book created");
        } catch (Exception $e) {
            print_r($e->getMessage());
        }

        return '';
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