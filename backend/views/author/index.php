<?php
use yii\helpers\Html;
use common\widgets\Alert;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\JobArea */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('view', 'TITLE_JOB_AREA_MANAGEMENT');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><i class="fa <?= $searchModel->getModelIcon();?> text-red"></i> <?= Yii::t('view', 'TITLE_JOB_AREA_MANAGEMENT')?></h5>
        <div class="heading-elements">
            <?= Html::a('<i class="fa fa-plus-square"></i> '.Yii::t('view', 'BTN_CREATE'), ['create'], ['class' => 'btn bg-green-800']) ?>
        </div>
    </div>

    <div class="panel-body">
        <?php echo Alert::widget([
            'autoFill' => false,
            'icon' => 'icon fa fa-warning',
            'delay' => 5000,
        ]);?>
    </div>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => ''],
        'sortColumn' => 1,
        'columns' => [
            [
                'attribute' => 'id',
                'format' => 'html',
                'value' => function ($model) {
                    return  $model->title;
                },
            ],
            [
                'class' => ActionColumn::class,
                'headerOptions' => ['width' => '100px'],
                'contentOptions' => ['style'=>'text-align: center;'],
                'header' => Yii::t('widget', 'DT_ACTIONS'),
            ],
        ],
    ]); ?>
</div>
