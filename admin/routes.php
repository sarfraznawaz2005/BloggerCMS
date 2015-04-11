<?php
/**
 * BloggerCMS - Easiest Static Blog Generator
 *
 * @author      Sarfraz Ahmed <sarfraznawaz2005@gmail.com>
 * @copyright   2015 Sarfraz Ahmed
 * @link        https://bloggercms.github.io
 * @version     1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
 
///////////////////////////////////////////////////////////
//  Routes File
///////////////////////////////////////////////////////////

// dashboard
$app->get(
   '/',
   function () use ($app) {
   
        set_time_limit(0);

       $post = new Post();
       $page = new Page();
       $category = new Category();
       $tag = new Tag();
       $image = new Image();

       $data['totalPosts'] = $post->getTotalPostsCount();
       $data['totalPostsPublished'] = $post->getTotalPostsCountPublished();
       $data['totalPostsDrafts'] = $post->getTotalPostsCountDrafts();
       $data['totalPages'] = $page->getTotalPagesCount();
       $data['totalCategories'] = $category->getTotalCategoriesCount();
       $data['totalTags'] = $tag->getTotalTagsCount();
       $data['totalImages'] = $image->getTotalImagesCount();

       // latest posts/updates from offical BloggerCMS blog
       try {
           $feedUrl = 'https://bloggercms.github.io/rss.xml';
           $feeds = file_get_contents($feedUrl);
           $xml = new SimpleXmlElement($feeds);

           $articles = array();
           foreach ($xml->channel->item as $item) {
               $item = (array) $item;
               $articles[] = array('title' => $item['title'], 'link' => $item['link']);
           }

           $data['articles'] = array_slice($articles, 0, 7);
       } catch (Exception $e) {

       }

       $app->render('index.php', array('title' => 'Dashboard', 'data' => $data));
   }
);

// settings
$app->get('/get_settings', 'Setting:get');
$app->post('/update_setting', 'Setting:update');
$app->post('/update_follow_setting', 'Setting:updateFollow');
$app->post('/add_customvalue_settings', 'Setting:addCustomValue');
$app->get('/remove_customvalue_setting/:param', 'Setting:remove');

// categories
$app->get('/get_categories', 'Category:get');
$app->post('/add_category', 'Category:add');
$app->get('/edit_category/:param', 'Category:edit');
$app->post('/update_category/:param', 'Category:update');
$app->get('/remove_category/:param', 'Category:remove');

//tags
$app->get('/get_tags', 'Tag:get');
$app->post('/update_tags', 'Tag:update');

// posts
$app->get('/get_addpost', 'Post:getAdd');
$app->post('/add_post', 'Post:add');
$app->get('/get_posts', 'Post:get');
$app->get('/remove_post/:param', 'Post:remove');
$app->get('/remove_post_trashed/:param', 'Post:removeTrashed');
$app->get('/restore_post/:param', 'Post:restore');
$app->get('/publish_post/:param', 'Post:publish');
$app->get('/edit_post/:param', 'Post:edit');
$app->post('/update_post/:param', 'Post:update');

// pages
$app->get('/get_addpage', 'Page:getAdd');
$app->post('/add_page', 'Page:add');
$app->get('/get_pages', 'Page:get');
$app->get('/edit_page/:param', 'Page:edit');
$app->post('/update_page/:param', 'Page:update');
$app->get('/remove_page/:param', 'Page:remove');

// images manager
$app->get('/get_imagemanager', 'Image:get');
$app->post('/add_image', 'Image:add');
$app->get('/remove_image/:param', 'Image:remove');

// generate blog.json core blog file
$app->get('/generate', 'Generate:generateBlog');

