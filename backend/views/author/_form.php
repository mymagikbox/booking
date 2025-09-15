<?php

use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var bool $isNewRecord */
/* @var $model */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>
    <div class="box-body">
        <?php echo Alert::widget([
            'autoFill' => false,
            'icon' => 'icon fa fa-warning',
            'delay' => 5000,
        ]); ?>
        <div class="mb-3">
            <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="box-footer mt-5">
        <?php echo Html::submitButton($isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-md btn-success']) ?>
        <?php echo Html::a('Cancel', ['index'], ['class' => 'btn btn-outline-secondary pull-right']) ?>
    </div>
<?php ActiveForm::end(); ?>