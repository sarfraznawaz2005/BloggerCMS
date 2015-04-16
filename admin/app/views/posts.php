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
                    <a href="<?php echo $root; ?>/get_addpost" class="btn btn-primary">Add Post</a>
                    <br/><br/>

                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <li class="active"><a href="#published" data-toggle="tab"><i class="fa fa-eye"></i>
                                Published</a></li>
                        <li><a href="#drafts" data-toggle="tab"><i class="fa fa-pencil-square-o"></i> Drafts</a></li>
                        <li><a href="#trashed" data-toggle="tab"><i class="fa fa-trash-o"></i> Trashed</a></li>
                    </ul>

                    <div id="my-tab-content" class="tab-content">
                        <div class="tab-pane active" id="published">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th><strong>Post Title</strong></th>
                                    <th><strong>Category</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                foreach ($data as $key => $post) {

                                    if ($post['status'] !== 'publised') {
                                        continue;
                                    }

                                    ?>
                                    <tr>
                                        <td>
                                            <a title="Edit" href="<?php echo $root; ?>/edit_post/<?php echo $key; ?>"><?php echo $post['title']; ?></a>
                                        </td>
                                        <td><?php echo $post['category']; ?></td>
                                        <td width="100" align="center">
                                            <a title="Edit" href="<?php echo $root; ?>/edit_post/<?php echo $key; ?>"><i class="fa fa-2x fa-pencil"></i></a>
                                            <a title="Delete" class="confirm-delete2" data-label="Post" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_post/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>

                            </table>
                        </div>
                        <div class="tab-pane" id="drafts">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th><strong>Post Title</strong></th>
                                    <th><strong>Category</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                foreach ($data as $key => $post) {

                                    if ($post['status'] !== 'draft') {
                                        continue;
                                    }

                                    ?>
                                    <tr>
                                        <td>
                                            <a title="Edit" href="<?php echo $root; ?>/edit_post/<?php echo $key; ?>"><?php echo $post['title']; ?></a>
                                        </td>
                                        <td><?php echo $post['category']; ?></td>
                                        <td width="150" align="center">
                                            <a style="margin-right: 15px;" title="Publish" href="<?php echo $root; ?>/publish_post/<?php echo $key; ?>"><i class="fa fa-2x fa-eye"></i></a>
                                            <a title="Edit" href="<?php echo $root; ?>/edit_post/<?php echo $key; ?>"><i class="fa fa-2x fa-pencil"></i></a>
                                            <a title="Delete" class="confirm-delete2" data-label="Post" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_post/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>

                            </table>
                        </div>
                        <div class="tab-pane" id="trashed">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th><strong>Post Title</strong></th>
                                    <th><strong>Category</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                foreach ($data as $key => $post) {

                                    if ($post['status'] !== 'trashed') {
                                        continue;
                                    }

                                    ?>
                                    <tr>
                                        <td>
                                            <a title="Edit" href="<?php echo $root; ?>/edit_post/<?php echo $key; ?>"><?php echo $post['title']; ?></a>
                                        </td>
                                        <td><?php echo $post['category']; ?></td>
                                        <td width="100" align="center">
                                            <a title="Restore" href="<?php echo $root; ?>/restore_post/<?php echo $key; ?>"><i class="fa fa-2x fa-refresh"></i></a>
                                            <a title="Delete Permanently" class="confirm-delete" data-label="Post" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_post_trashed/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
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
    </div>

</div>

<?php require_once 'includes/foot.php' ?>

<script src="<?php echo $root; ?>/app/js/dt-filter.js"></script>
<script>

    $(function () {
        $('.table').not('.nodatatable').dataTable({
            "bAutoWidth": false,
            "iDisplayLength": 25,
            "ordering": false,
            "bFilter": true,
            "bRetrieve": true,
            "bLengthChange": false
        });

    });
</script>

</body>
</html>

