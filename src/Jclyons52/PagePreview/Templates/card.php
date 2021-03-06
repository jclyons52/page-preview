<!-- Wide card with share menu button -->
<style>
.demo-card-wide.mdl-card {
  width: 512px;
}
.demo-card-wide > .mdl-card__title {
  color: #fff;
  height: 176px;
  background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.6)), url('<?= $images[0] ?>');
  background-size:100%;
  color: yellow;

}
.demo-card-wide > .mdl-card__menu {
  color: #fff;
}
</style>

<div class="demo-card-wide mdl-card mdl-shadow--2dp">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text"><?= $title ?></h2>
  </div>
  <div class="mdl-card__supporting-text">
   <?= $description ?>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="<?= $url ?>">
      View
    </a>
  </div>
</div>