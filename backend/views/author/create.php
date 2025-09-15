<?php
use common\CQS\Application\Author\Command\CreateAuthor\CreateAuthorCommand;

/* @var $this yii\web\View */
/* @var CreateAuthorCommand $model */

$this->title = 'Author management';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create author';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title"><?php echo 'Create author'?></h5>
            </div>
            <div class="panel-body">
                <?php echo $this->render('_form', [
                    'model' => $model,
                    'isNewRecord' => true,
                ]);?>
            </div>
        </div>
    </div>
</div>