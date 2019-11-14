<?php

use yii\data\Pagination;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $feed SimpleXMLElement */
/* @var $pagination Pagination */
/* @var $top [] */
$this->registerLinkTag([
    'rel' => 'shortcut icon',
    'href' => (string)$feed->icon,
    'type' => 'image/x-icon'
]);
?>
    <h1><?php echo (string)$feed->title; ?> <img src="<?php echo (string)$feed->logo; ?>"/></h1>
    <h2><?php echo (string)$feed->subtitle; ?></h2>
    <small><a href="<?php echo (string)$feed->author->uri; ?>"><?php echo $feed->author->name; ?></a></small><br>
    <a href="mailto:<?php echo (string)$feed->author->email; ?>" class="glyphicon glyphicon-envelope"
       title="Send email"></a>
    <hr>
    <h3>Top 10 words</h3>
<?php foreach ($top as $word => $count) : ?>
    <span class="badge badge-pill badge-success"><?php echo "$word ($count)"; ?></span>
<?php endforeach; ?>
    <hr>
    <div>
        <?php
        $from = $pagination->page * $pagination->getPageSize();
        $to = $from + $pagination->getPageSize() + 1;
        $entries = $feed->xpath("atom:entry[position()>$from and position()<$to]");
        foreach ($entries as $entry) {
            ?>
            <div>
                <h4><a href="<?php echo (string)$entry->link['href']; ?>"><?php echo (string)$entry->title; ?></a></h4>
                <p><?php echo (string)$entry->summary; ?></p>
                <small><a href="<?php echo (string)$entry->author->uri; ?>"><?php echo (string)$entry->author->name; ?></a>
                    <?php echo date('d.m.Y H:i', strtotime((string)$entry->updated)); ?>
                </small>
            </div><br><br>
            <?php
        }
        ?>
    </div>
<?php
echo LinkPager::widget(['pagination' => $pagination]);
