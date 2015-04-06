<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 4/1/2015
 * Time: 6:10 PM
 */
class Tag
{
    private $metaFile = 'data/tags.json';

    public function get()
    {
        global $app;
        $data = MetaDataWriter::getFileData($this->metaFile);
        //pretty_print($data);

        $app->render('tags.php', array('title' => 'Tags', 'data' => $data));
    }

    public function update()
    {
        global $app;
        $post = $app->request()->post();
        //pretty_print($post['tags']);

        MetaDataWriter::writeData($this->metaFile, $post['tags']);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function getTotalTagsCount()
    {
        $data = MetaDataWriter::getFileData($this->metaFile);
        return count($data);
    }

    public function remove()
    {
    }

    public function add()
    {
    }

    public function edit()
    {
    }
}