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
                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <li class="active"><a href="#settings" data-toggle="tab"><i class="fa fa-cog"></i>
                                General Settings</a></li>
                        <li><a href="#follow" data-toggle="tab"><i class="fa fa-twitter"></i> Follow Settings</a>
                        <li><a href="#custom" data-toggle="tab"><i class="fa fa-code"></i> Custom Values</a>
                        </li>
                    </ul>

                    <div id="my-tab-content" class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <br/>

                            <form action="<?php echo $root; ?>/update_setting" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="url">Blog URL</label>
                                        <input required type="text" id="url" name="url" class="form-control" value="<?php echo $data['url']; ?>">

                                        <p class="help-block">This will be used for linking. Example:
                                            http://www.myblog.com</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="title">Blog Title</label>
                                        <input type="text" id="title" name="title" class="form-control" value="<?php echo $data['title'] ?: 'My Awesome Blog!' ?>">

                                        <p class="help-block">This will be title of your blog.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="tagline">Blog Tagline</label>
                                        <input type="text" id="tagline" name="tagline" class="form-control" value="<?php echo $data['tagline'] ?: 'Welcome to my awesome blog!' ?>">

                                        <p class="help-block">Shortly describe what the blog is for.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="author">Author Name</label>
                                        <input type="text" id="author" name="author" class="form-control" value="<?php echo $data['author'] ?: 'Blog Author' ?>">

                                        <p class="help-block">This will be used as author name for all posts.</p>
                                    </div>
                                </div>

                                <!--
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="number_posts">Number of Posts</label>
                                        <input type="number" id="number_posts" name="number_posts" class="form-control" value="<?php //echo $data['number_posts'] ?: '10' ?>">

                                        <p class="help-block">Number of blog posts to show on homepage.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <input type="checkbox" id="only_titles" name="only_titles" <?php //echo $data['only_titles'] === 'true' ? 'checked' : ''; ?>>
                                        <label for="only_titles">Display Post Titles Only</label>

                                        <p class="help-block">This will show only post titles on homepage.</p>
                                    </div>
                                </div>
                                -->

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="layout">Layout Name</label>
                                        <input type="text" id="layout" name="layout" class="form-control" value="<?php echo $data['layout'] ?: 'default' ?>">

                                        <p class="help-block">The layout to be used for blog.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="theme">Theme</label>

                                        <select name="theme" id="theme" class="form-control">
                                            <option <?php echo $data['theme'] === 'bootstrap.min.css' ? 'selected' : '' ?> value="bootstrap.min.css">
                                                Default
                                            </option>
                                            <option <?php echo $data['theme'] === 'journal.min.css' ? 'selected' : '' ?> value="journal.min.css">
                                                Journal
                                            </option>
                                            <option <?php echo $data['theme'] === 'slate.min.css' ? 'selected' : '' ?> value="slate.min.css">
                                                Slate
                                            </option>
                                            <option <?php echo $data['theme'] === 'superhero.min.css' ? 'selected' : '' ?> value="superhero.min.css">
                                                Superhero
                                            </option>
                                            <option <?php echo $data['theme'] === 'darkly.min.css' ? 'selected' : '' ?> value="darkly.min.css">
                                                Darkly
                                            </option>
                                            <option <?php echo $data['theme'] === 'yeti.min.css' ? 'selected' : '' ?> value="yeti.min.css">
                                                Yeti Flat
                                            </option>
                                            <option <?php echo $data['theme'] === 'cerulean.min.css' ? 'selected' : '' ?> value="cerulean.min.css">
                                                Cerulean
                                            </option>
                                            <option <?php echo $data['theme'] === 'lavish.css' ? 'selected' : '' ?> value="lavish.css">
                                                Lavish
                                            </option>
                                            <option <?php echo $data['theme'] === 'united.min.css' ? 'selected' : '' ?> value="united.min.css">
                                                United
                                            </option>
                                        </select>

                                        <p class="help-block">The theme to be used for blog. Only applicable for default
                                            theme.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="disqus">Disqus Shortname</label>
                                        <input type="text" id="disqus" name="disqus" class="form-control" value="<?php echo $data['disqus']; ?>">

                                        <p class="help-block">This will be used to add Disqus commenting to posts.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="analytics">Google Analytics Tracking ID</label>
                                        <input type="text" id="analytics" name="analytics" class="form-control" value="<?php echo $data['analytics']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update
                                    </button>
                                </div>

                            </form>
                        </div>

                        <div class="tab-pane" id="follow">
                            <br/>

                            <form action="<?php echo $root; ?>/update_follow_setting" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="twitter">Twitter URL</label>
                                        <input type="url" id="twitter" name="twitter" class="form-control" value="<?php echo $data['follow']['twitter']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="linkedin">Linkedin URL</label>
                                        <input type="url" id="linkedin" name="linkedin" class="form-control" value="<?php echo $data['follow']['linkedin']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="google">Google+ URL</label>
                                        <input type="url" id="google" name="google" class="form-control" value="<?php echo $data['follow']['google']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="facebook">Facebook URL</label>
                                        <input type="url" id="facebook" name="facebook" class="form-control" value="<?php echo $data['follow']['facebook']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="github">Github URL</label>
                                        <input type="url" id="github" name="github" class="form-control" value="<?php echo $data['follow']['github']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update
                                    </button>
                                </div>

                            </form>
                        </div>

                        <div class="tab-pane" id="custom">
                            <br/>

                            <p class="text-justify text-primary" style="font-size: 14px;">
                                <i class="fa fa-info-circle"></i> With custom values, you can add any arbitrary
                                custom values such
                                as Google search, Stackoverflow flair, Disqus commenting code or ANY text or html you
                                want.
                            </p>

                            <br/>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#custommodal">
                                <i class="fa fa-plus-circle"></i> Add Todo Item
                            </button>

                            <hr/>

                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th><strong>Unique Id</strong></th>
                                    <th><strong>Title</strong></th>
                                    <th><strong>Value</strong></th>
                                    <th class="text-center"><strong>Actions</strong></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                foreach ($data['customValues'] as $key => $customValue) {
                                    ?>
                                    <tr>
                                        <td><?php echo $customValue['id']; ?></td>
                                        <td><?php echo $customValue['title']; ?></td>
                                        <td><?php echo htmlentities($customValue['value'], ENT_QUOTES) ?></td>
                                        <td width="80" align="center">
                                            <a title="Delete" class="confirm-delete" data-label="Custom Value" data-id="<?php echo $key; ?>" href="<?php echo $root; ?>/remove_customvalue_setting/<?php echo $key; ?>"><i class="fa fa-2x fa-trash-o" data-original-title="Delete" data-placement="bottom"></i></a>
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

<!-- modals start -->
<div class="modal fade" id="custommodal">
    <div class="modal-dialog">
        <form method="post" action="<?php echo $root ?>/add_customvalue_settings">
            <div class="modal-content">
                <div class="modal-header label-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-code"></i> <span
                           class="text-white bold">Add Custom Value</span></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control"
                               title="Title"
                               name="title"
                               id="title"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="value">Value</label>
                        <textarea class="editor form-control" rows="10"
                                  name="value"
                                  id="value"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close
                    </button>
                    <button type="submit" id="btnAddTodo" class="btn btn-success"><i class="fa fa-save"></i> Add Custom
                        Value
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- modals end -->


<?php require_once 'includes/foot.php' ?>
</body>
</html>

