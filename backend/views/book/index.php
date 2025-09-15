<?php
use yii\helpers\Html;
use common\widgets\Alert;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider  */

$this->title = 'Book management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo 'Book management'?></h5>
        <div class="heading-elements">
            <?php echo Html::a('Create', ['create'], ['class' => 'btn bg-green-800']) ?>
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
            'id',
            'title',
            'year',
            [
                'class' => ActionColumn::class,
                'headerOptions' => ['width' => '100px'],
                'contentOptions' => ['style'=>'text-align: center;'],
                'header' => Yii::t('widget', 'DT_ACTIONS'),
            ],
        ],
    ]); ?>
</div>
