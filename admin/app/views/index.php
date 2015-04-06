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

                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o"></i> Quick Statistics
                        </div>
                        <div class="panel-body" style="padding: 0 !important;">
                            <table class="table table-responsive nodatatable">
                                <thead>
                                <tr>
                                    <th><strong>Item</strong></th>
                                    <th><strong>Count</strong></th>
                                </tr>
                                </thead>
                                <tr>
                                    <td>Total Published Posts</td>
                                    <td><strong><?php echo $data['totalPostsPublished']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Total Drafted Posts</td>
                                    <td><strong><?php echo $data['totalPostsDrafts']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Total Posts (including trashed ones)</td>
                                    <td><strong><?php echo $data['totalPosts']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Total Pages</td>
                                    <td><strong><?php echo $data['totalPages']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Total Categories</td>
                                    <td><strong><?php echo $data['totalCategories']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Total Tags</td>
                                    <td><strong><?php echo $data['totalTags']; ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/foot.php' ?>
</body>
</html>

