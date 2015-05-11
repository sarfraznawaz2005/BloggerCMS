<div id="strips" class="progress" style="height: 2px;">
    <div class="progress-bar progress-bar-primary" style="width: 25%;"></div>
    <div class="progress-bar progress-bar-success" style="width: 25%;"></div>
    <div class="progress-bar progress-bar-warning" style="width: 25%;"></div>
    <div class="progress-bar progress-bar-danger" style="width: 25%;"></div>
</div>

<?php if (isset($flash['info'])) { ?>
<div class="alert alert-info" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong><i class="fa fa-info-circle"></i> <?php echo $flash['info']; ?></strong>
</div>
<?php } ?>