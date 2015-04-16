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
                    <form action="<?php echo $root; ?>/update_category/<?php echo $id; ?>" class="form-horizontal" method="post">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="col-xs-2" for="name">New Category Title</label>

                                <div class="col-xs-6">
                                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo $data; ?>">
                                </div>
                                <div class="col-xs-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update
                                        Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/foot.php' ?>
</body>
</html>

