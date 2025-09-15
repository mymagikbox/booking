<?php

use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model */

$this->title = 'Author management';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Delete author';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title"><?php echo "Delete author {$model->username}"; ?></h5>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="box-body">
                    <?php echo Alert::widget([
                        'autoFill' => false,
                        'type' => 'alert-danger',
                        'title' => 'ATTENTION',
                        'showSeparator' => true,
                        'body' => "Are you sure to delete author {$model->username}",
                    ]); ?>
                    <?php echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'title',
                                'format' => 'html',
                                'value' => $model->username,
                            ],
                        ],
                    ]); ?>
                </div>
                <div class="box-footer">
                    <?php echo Html::submitButton('Delete', ['class' => 'btn bg-green-800']); ?>
                    <?php echo Html::a('Cancel', ['index'], ['class' => 'btn btn-default pull-right']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>