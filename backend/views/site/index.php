<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent" style="margin-bottom: 40px">
        <h1 class="display-4">Admin panel!</h1>

        <p class="lead">Welcome</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Book</h2>

                <p>Book info.</p>

                <p><a class="btn btn-outline-secondary" href="<?php echo Url::to('/book/index');?>">Go to book &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Author</h2>

                <p>Author info.</p>

                <p><a class="btn btn-outline-secondary" href="<?php echo Url::to('/author/index');?>">Go to author &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Report</h2>

                <p>Top 10 authors</p>

                <p><a class="btn btn-outline-secondary" href="<?php echo Url::to('/report/index');?>">Report top 10 &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
