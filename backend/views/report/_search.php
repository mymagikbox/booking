<?php

use common\CQS\Application\Report\Query\TopAuthors\TopAuthorsQuery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var TopAuthorsQuery $model */
?>
<div class="panel panel-default mb-5 mt-3">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo 'SEARCH'; ?></h5>
    </div>
    <div class="panel-body">
        <div class="table-search-fields">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => [Yii::$app->controller->route],
            ]); ?>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->field($model, 'year')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-6">
                    <?php echo $form->field($model, 'limit')->textInput(['maxlength' => true]); ?>
                </div>
            </div>
            <div class="btn-group mt-3">
                <?php echo Html::submitButton( 'SEARCH', ['class' => 'btn btn-sm btn-success']); ?>
                <?php echo Html::a('RESET', ["/" . Yii::$app->controller->route], ['class' => 'btn btn-sm btn-danger']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
