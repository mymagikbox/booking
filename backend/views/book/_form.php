<?php

use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var bool $isNewRecord */
/* @var array $authorIdNameList */
/* @var $model */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>
<div class="box-body">
    <?php echo Alert::widget([
        'icon' => 'icon fa fa-warning',
        'delay' => 5000,
    ]); ?>
    <div class="mb-3">
        <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="mb-3">
        <?php echo $form->field($model, 'year')->textInput() ?>
    </div>
    <div class="mb-3">
        <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    </div>
    <div class="mb-3">
        <?php echo $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="mb-3">
        <?php echo $form->field($model, 'coverImageFile')->fileInput() ?>
    </div>

    <div class="mb-3">
        <?php echo $form->field($model, 'authorIdList')->dropDownList(
            $authorIdNameList, // Your data for the dropdown, e.g., ['value1' => 'Label 1', 'value2' => 'Label 2']
            ['multiple' => 'multiple', 'size' => 5] // 'size' is optional, determines the number of visible options
        ); ?>
    </div>

</div>

<div class="box-footer mt-5">
    <?php echo Html::submitButton($isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-md btn-success']) ?>
    <?php echo Html::a('Cancel', ['index'], ['class' => 'btn btn-outline-secondary pull-right'])?>
</div>
<?php ActiveForm::end(); ?>