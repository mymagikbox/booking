<?php
use common\CQS\Application\Author\Command\UpdateAuthor\UpdateAuthorCommand;

/* @var $this yii\web\View */
/* @var UpdateAuthorCommand $model */

$this->title = 'Author management';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update author';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title"><?php echo 'Update author'?></h5>
            </div>
            <div class="panel-body">
                <?php echo $this->render('_form', [
                    'model' => $model,
                    'isNewRecord' => false,
                ]);?>
            </div>
        </div>
    </div>
</div>