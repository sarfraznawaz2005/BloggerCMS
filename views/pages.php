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
                    <a href="<?php echo $root; ?>/get_addpage" class="btn btn-primary">Add Page</a>
                    <br/><br/>

                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th><strong>Page Title</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data as $key => $post) {
                            ?>
                            <tr>
                                <td>
                                    <a title="Edit" href="<?php echo $root; ?>/edit_page/<?php echo $key; ?>"><?php echo $post['title']; ?></a>
                                </td>
                                <td width="100" align="center">
                                    <a title="Edit" href="<?php echo $root; ?>/edit_page/<?php echo $key; ?>"><i class="fa fa-2x fa-pencil"></i></a>
                                    <a title="Delete" class="confirm-delete2" data-label="Page" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_page/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
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

