<?php
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookCommand;

/* @var $this yii\web\View */
/* @var UpdateBookCommand $model */
/* @var array $authorIdNameList */

$this->title = 'Book management';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update book';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title"><?php echo 'Update book'?></h5>
            </div>
            <div class="panel-body">
                <?php echo $this->render('_form', [
                    'model' => $model,
                    'isNewRecord' => false,
                    'authorIdNameList' => $authorIdNameList,
                ]);?>
            </div>
        </div>
    </div>
</div>