<?php
// @codingStandardsIgnoreStart
?>
<div class="my-custom-div">
    <div class="media">
        <?php if ($images[0]) : ?>
            <div class="media-left">
                <a href="<?= $url ?>">
                    <img class="media-object" src="<?= $images[0] ?>" alt="<?= $title ?>">
                </a>
            </div>
        <?php endif; ?>
        <div class="media-body">
            <a href="<?= $url ?>">
                <h4 class="media-heading"><?= $title ?></h4>
                <?= $description ?>
            </a>
        </div>
    </div>
</div>
<?php
// @codingStandardsIgnoreEnd
?>