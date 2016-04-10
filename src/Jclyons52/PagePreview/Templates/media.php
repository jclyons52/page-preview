<div class="media">
    <?php if ($image) : ?>
        <div class="media-left">
            <a href="<?= $url ?>">
                <img class="media-object" src="<?= $image ?>" alt="<?= $title ?>">
            </a>
        </div>
    <?php endif; ?>
    <div class="media-body">
        <a href="<?= $url ?>">
            <h4 class="media-heading"><?= $title ?></h4>
            <?= $body ?>
        </a>
    </div>
</div>