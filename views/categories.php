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
                    <form action="<?php echo $root; ?>/add_category" class="form-horizontal" method="post">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="col-xs-2" for="name">New Category Title</label>

                                <div class="col-xs-6">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-xs-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Add
                                        Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th><strong>Category Name</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data as $key => $value) {
                            ?>
                            <tr>
                                <td><?php echo $value; ?></td>
                                <td width="100" align="center">
                                    <a title="Edit" href="<?php echo $root; ?>/edit_category/<?php echo $key; ?>"><i class="fa fa-2x fa-pencil"></i></a>
                                    <a title="Delete" class="confirm-delete" data-label="Category" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_category/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/foot.php' ?>
</body>
</html>

