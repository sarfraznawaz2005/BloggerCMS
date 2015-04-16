<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
</head>
<body>

<div id="wrapper">
    <!-- Navigation -->
    <?php require_once 'includes/nav.php' ?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $title; ?></h1>
                <?php require_once 'includes/line.php' ?>

                <div class="content">
                    <form action="<?php echo $root; ?>/update_page/<?php echo $id; ?>" class="form-horizontal" method="post">
                        <div class="row">
                            <div class="form-group col-lg-8">
                                <label for="title">Page Title</label>
                                <input required type="text" id="title" name="title" class="form-control" value="<?php echo $data['title']; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label for="body">Page Content</label>
                                <textarea required id="body" name="body" class="form-control" rows="15"><?php echo $data['body']; ?></textarea>
                            </div>
                        </div>


                        <div class="row">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update
                                Page
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/foot.php' ?>

<script>
    (function () {
        $("textarea#body").pagedownBootstrap({"help": function () { window.open('http://daringfireball.net/projects/markdown/syntax'); }});
        $('.wmd-preview').addClass('well');
    })();
</script>

</body>
</html>

