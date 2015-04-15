<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <?php require_once 'brand.php' ?>

    <?php if ($app->view()->getData('blogURL')) { ?>
    <div class="pull-right" style="margin-top: 2px; margin-right: 10px;">
        <a target="_blank" href="<?php echo $app->view()->getData('blogURL'); ?>" class="btn btn-primary btn-lg"><i class="fa fa-eye"></i> View Blog</a>
    </div>
    <?php } ?>

    <form method="post" action="<?php echo $root ?>/generate">
        <div class="pull-right" style="margin-top: 2px; margin-right: 10px;">
            <button type="button" id="generate_blog" class="btn btn-success btn-lg"><i class="fa fa-paper-plane"></i> Generate Blog
            </button>
        </div>
    </form>


    <?php require_once 'side.php' ?>
</nav>