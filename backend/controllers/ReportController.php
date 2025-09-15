<?php
declare(strict_types=1);

namespace backend\controllers;

use common\core\controller\BaseAdminController;
use common\CQS\Application\Report\Query\TopAuthors\TopAuthorsFetcher;
use common\CQS\Application\Report\Query\TopAuthors\TopAuthorsQuery;
use Exception;
use kartik\alert\AlertInterface;
use Yii;
use yii\data\ArrayDataProvider;

class ReportController extends BaseAdminController
{
    public function __construct(
        $id,
        $module,
        $config = [],
        private TopAuthorsFetcher $topAuthorsFetcher,
    )
    {
        parent::__construct($id, $module, $config);
    }


    public function actionIndex()
    {
        $query = new TopAuthorsQuery();

        $dataProvider = new ArrayDataProvider();

        try {
            $query->load(Yii::$app->request->queryParams);

            $response = $this->topAuthorsFetcher->fetch($query);

            $dataProvider->allModels = $response ? $response->toArray() : [];
        } catch (Exception $e) {
            Yii::$app->session->setFlash(AlertInterface::TYPE_DANGER, $e->getMessage());
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $query,
        ]);
    }
}