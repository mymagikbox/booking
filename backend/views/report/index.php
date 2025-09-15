<?php

use common\widgets\Alert;
use yii\grid\GridView;

/* @var yii\web\View $this */
/* @var $dataProvider */
/* @var $searchModel */

$this->title = 'Report management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo 'Top book authors' ?></h5>
        <div class="heading-elements">
        </div>
    </div>
    <div class="panel-body">
        <?php echo Alert::widget([
            'autoFill' => true,
            'icon' => 'icon fa fa-warning',
            'delay' => 5000,
        ]); ?>
    </div>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => ''],
        'columns' => [
            'id',
            'username',
            'count',
        ],
    ]); ?>
</div>
