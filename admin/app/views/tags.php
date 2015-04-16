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
                    <form action="<?php echo $root; ?>/update_tags" class="form-horizontal" method="post">
                        <div class="row">
                            <div class="form-group col-lg-8">
                                <label for="tags">Tags</label>
                                <select required name="tags[]" id="tags" class="select2 tags form-control" multiple="multiple">
                                    <?php
                                    foreach ($data as $tag) {
                                        ?>
                                        <option selected value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <button name="addpost" value="addpost" type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update Tags
                            </button>
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

