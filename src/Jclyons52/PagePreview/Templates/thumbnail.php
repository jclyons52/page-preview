<?php
// @codingStandardsIgnoreStart
?>
<div class="row">
    <div class="col-xs-12">
        <div class="thumbnail">
            <? if ($media) : ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="<?= $media['url'] ?>"></iframe>
                </div>
            <? else : ?>
                <img src="<?= $images[0] ?>" alt="<?= $title ?>">
            <? endif; ?>
            <div class="caption">
                <h3><?= $title ?></h3>
                <p><?= $description ?></p>
                <p><a href="<?= $url ?>" class="btn btn-primary" role="button">View</a></p>
            </div>
        </div>
    </div>
</div>
<?php
// @codingStandardsIgnoreEnd
?>