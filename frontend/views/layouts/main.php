<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\Page;
use common\models\Service;
use common\models\Review;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="header">
        <a class="home-link" href="<?= Url::to(['/']) ?>"><?= Yii::$app->params['siteName']; ?></a>
    </div>
    
    <div class="col-md-3">
        <div class="left-menu">
            <div class="menu-item">
                <div class="menu-item-inner">
                    <a href="<?= Url::to(['/about']); ?>">About Us</a>
                </div>
            </div>
            <?php $services = Service::find()->orderBy('priority ASC')->all(); ?>
            <?php if ($services): ?>
                <?php foreach ($services as $service): ?>
                    <div class="menu-item">
                        <div class="menu-item-inner">
                            <a href="<?= Url::to(['/service/' . $service->slug]); ?>"><?= $service->name; ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container col-md-6">
        <div class="content-text">
            <?= $content ?>
        </div>
    </div>
    
    <div class="col-md-3" style="margin-bottom: 20px;;">
        <?php if (Yii::$app->controller->id != 'review'): ?>
            <div class="reviews-right">
                <strong>Reviews:</strong>
                <?php $reviews = Review::getLastReviews(); ?>
                <?php if ($reviews): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <strong><?= date('j F Y', strtotime($review->published_at)) . ", "; ?></strong>
                            <strong><?= $review->author; ?></strong>
                            <div>
                                <?= $review->message; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <p><a href="<?= Url::to(['/review']); ?>">See all reviews...</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="footer col-md-12">
    <div class="container">
        <b>e-mail:</b> <a href="mailto:<?= Yii::$app->params['infoEmail']; ?>"><?= Yii::$app->params['infoEmail']; ?></a> <b>tel.:</b> <?= Yii::$app->params['phone']; ?>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
