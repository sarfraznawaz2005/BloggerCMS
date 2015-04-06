<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 3/30/2015
 * Time: 4:31 PM
 */
class Category implements Crud
{
    private $metaFile = 'data/categories.json';

    public function get()
    {
        global $app;

        $data = MetaDataWriter::getFileData($this->metaFile);
        sort($data);

        $app->render('categories.php', array('title' => 'Categories', 'data' => $data));
    }

    public function add()
    {
        global $app;
        $post = $app->request()->post();

        MetaDataWriter::addDataFlat($this->metaFile, $post);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function edit()
    {
        global $app;

        $data = MetaDataWriter::getFileData($this->metaFile);
        sort($data);

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        $app->render('editcategory.php', array('title' => 'Edit Category', 'data' => $data[$id], 'id' => $id));
    }

    public function update()
    {
        global $app;
        $post = $app->request()->post();

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];
        $value = $post['name'];

        MetaDataWriter::updateItemData($this->metaFile, $id, $value, true);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function remove()
    {
        global $app;

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        MetaDataWriter::removeItemData($this->metaFile, $id, true);

        $app->flash('info', 'Deleted Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function getTotalCategoriesCount()
    {
        $data = MetaDataWriter::getFileData($this->metaFile);
        return count($data);
    }

}