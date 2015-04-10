<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 3/30/2015
 * Time: 4:31 PM
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
        $data[$id]['generated'] = '';

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
        $pagePath = '../public/page/' . getSlugName($data[$id]['title']) . '.html';
        @unlink($pagePath);

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