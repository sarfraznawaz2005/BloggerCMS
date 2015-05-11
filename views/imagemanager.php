<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>

    <link href="<?php echo $root; ?>/assets/js/plugins/lightbox/ekko-lightbox.min.css" rel="stylesheet">
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
                    <form enctype="multipart/form-data" action="<?php echo $root; ?>/add_image" class="form-horizontal" method="post">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="col-xs-2" for="image">Choose Image</label>

                                <div class="col-xs-6">
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="col-xs-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th><strong>Image</strong></th>
                            <th class="text-center"><strong>Actions</strong></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data as $key => $image) {
                            $fullPath = $settings['url'] . '/images/' . basename($image['image']);
                            ?>
                            <tr>
                                <td>
                                    <div class="pull-left"><a href="<?php echo $image['image']; ?>" data-toggle="lightbox"><img src="<?php echo $image['image']; ?>" alt="" width="100" height="100"/></a></div>
                                    <div class="pull-left" style="margin-left: 20px;"><?php echo $fullPath; ?></div>
                                </td>
                                <td width="80" align="center">
                                    <a title="Delete" class="confirm-delete" data-label="Image" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_image/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
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

<script src="<?php echo $root; ?>/assets/js/plugins/lightbox/ekko-lightbox.min.js"></script>

<script>
    $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

</script>

</body>
</html>

