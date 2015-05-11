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

use BloggerCMS\Post;
use BloggerCMS\Page;
use BloggerCMS\Category;
use BloggerCMS\Tag;
use BloggerCMS\Image;

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
$app->get('/get_settings', 'BloggerCMS\Setting:get');
$app->post('/update_setting', 'BloggerCMS\Setting:update');
$app->post('/update_follow_setting', 'BloggerCMS\Setting:updateFollow');
$app->post('/add_customvalue_settings', 'BloggerCMS\Setting:addCustomValue');
$app->get('/remove_customvalue_setting/:param', 'BloggerCMS\Setting:remove');

// categories
$app->get('/get_categories', 'BloggerCMS\Category:get');
$app->post('/add_category', 'BloggerCMS\Category:add');
$app->get('/edit_category/:param', 'BloggerCMS\Category:edit');
$app->post('/update_category/:param', 'BloggerCMS\Category:update');
$app->get('/remove_category/:param', 'BloggerCMS\Category:remove');

//tags
$app->get('/get_tags', 'BloggerCMS\Tag:get');
$app->post('/update_tags', 'BloggerCMS\Tag:update');

// posts
$app->get('/get_addpost', 'BloggerCMS\Post:getAdd');
$app->post('/add_post', 'BloggerCMS\Post:add');
$app->get('/get_posts', 'BloggerCMS\Post:get');
$app->get('/remove_post/:param', 'BloggerCMS\Post:remove');
$app->get('/remove_post_trashed/:param', 'BloggerCMS\Post:removeTrashed');
$app->get('/restore_post/:param', 'BloggerCMS\Post:restore');
$app->get('/publish_post/:param', 'BloggerCMS\Post:publish');
$app->get('/edit_post/:param', 'BloggerCMS\Post:edit');
$app->post('/update_post/:param', 'BloggerCMS\Post:update');

// pages
$app->get('/get_addpage', 'BloggerCMS\Page:getAdd');
$app->post('/add_page', 'BloggerCMS\Page:add');
$app->get('/get_pages', 'BloggerCMS\Page:get');
$app->get('/edit_page/:param', 'BloggerCMS\Page:edit');
$app->post('/update_page/:param', 'BloggerCMS\Page:update');
$app->get('/remove_page/:param', 'BloggerCMS\Page:remove');

// images manager
$app->get('/get_imagemanager', 'BloggerCMS\Image:get');
$app->post('/add_image', 'BloggerCMS\Image:add');
$app->get('/remove_image/:param', 'BloggerCMS\Image:remove');

// generate blog.json core blog file
$app->get('/generate', 'BloggerCMS\Generator:generateBlog');

