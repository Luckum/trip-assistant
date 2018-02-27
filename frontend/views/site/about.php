<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = $content ? $content->title : '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $content ? $content->content : ''; ?>
</div>
