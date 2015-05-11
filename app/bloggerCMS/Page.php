<?php namespace BloggerCMS;

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
class Page
{
    private $metaFile = 'data/pages.json';

    public function getAdd()
    {
        global $app;
        $app->render('addpage.php', array('title' => 'Add Page'));
    }

    public function add()
    {
        global $app;
        $post = $app->request()->post();

        // add post
        $post['slug'] = getSlugName($post['title']);

        MetaDataWriter::updateFileData($this->metaFile, $post, true);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function get()
    {
        global $app;
        $data = MetaDataWriter::getFileData($this->metaFile);

        $app->render('pages.php', array('title' => 'View Pages', 'data' => $data));
    }

    public function edit()
    {
        global $app;

        $data = MetaDataWriter::getFileData($this->metaFile);

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        //pretty_print($data[$id]);

        $app->render('editpage.php', array('title' => 'Edit Page', 'data' => $data[$id], 'id' => $id));
    }

    public function update()
    {
        global $app;

        $data = MetaDataWriter::getFileData($this->metaFile);
        $post = $app->request()->post();

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        // update values
        foreach ($post as $key => $value) {
            $data[$id][$key] = $value;
        }

        $data[$id]['slug'] = getSlugName($post['title']);

        MetaDataWriter::writeData($this->metaFile, $data);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }


    public function remove()
    {
        global $app;

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        $data = MetaDataWriter::getFileData($this->metaFile);

        // remove page from disk
        $pagePath = 'public/page/' . getSlugName($data[$id]['title']);
        @rrmdir($pagePath);

        unset($data[$id]);

        MetaDataWriter::writeData($this->metaFile, $data);

        $app->flash('info', 'Deleted Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function getTotalPagesCount()
    {
        $data = MetaDataWriter::getFileData($this->metaFile);
        return count($data);
    }

}