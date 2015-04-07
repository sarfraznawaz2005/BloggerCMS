<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="active">
                <a class="active" href="<?php echo $root; ?>/"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-pencil"></i> Posts<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo $root; ?>/get_addpost">Add Post</a>
                    </li>
                    <li>
                        <a href="<?php echo $root; ?>/get_posts">View Posts</a>
                    </li>
                    <li>
                        <a href="<?php echo $root; ?>/get_categories">Categories</a>
                    </li>
                    <li>
                        <a href="<?php echo $root; ?>/get_tags">Tags</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-files-o"></i> Pages<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo $root; ?>/get_addpage">Add Page</a>
                    </li>
                    <li>
                        <a href="<?php echo $root; ?>/get_pages">View Pages</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="<?php echo $root; ?>/get_imagemanager"><i class="fa fa-image"></i> Image Manager</a>
            </li>

            <li>
                <a href="<?php echo $root; ?>/get_settings"><i class="fa fa-cog"></i> Settings</a>
            </li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>