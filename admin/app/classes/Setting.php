<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 3/30/2015
 * Time: 4:30 PM
 */
class Setting implements Crud
{
    private $metaFile = 'data/settings.json';
    private $followFile = 'data/follow.json';
    private $customValuesFile = 'data/customvalues.json';

    public function get()
    {
        global $app;
        $data = MetaDataWriter::getFileData($this->metaFile);
        $data['customValues'] = MetaDataWriter::getFileData($this->customValuesFile);
        $data['follow'] = MetaDataWriter::getFileData($this->followFile);

        $app->render('settings.php', array('title' => 'Settings', 'data' => $data));
    }

    public function update()
    {
        global $app;
        $post = $app->request()->post();

        $post['only_titles'] = isset($post['only_titles']) ? 'true' : '';
        $post['url'] = rtrim(trim($post['url']), '/');

        MetaDataWriter::updateFileData($this->metaFile, $post);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function updateFollow()
    {
        global $app;
        $post = $app->request()->post();

        MetaDataWriter::updateFileData($this->followFile, $post);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function addCustomValue()
    {
        global $app;
        $post = $app->request()->post();

        MetaDataWriter::updateFileData($this->customValuesFile, $post, true);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function remove()
    {
        global $app;

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        $data = MetaDataWriter::getFileData($this->customValuesFile);
        unset($data[$id]);

        MetaDataWriter::writeData($this->customValuesFile, $data);

        $app->flash('info', 'Deleted Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function add()
    {
    }

    public function edit()
    {
    }
}