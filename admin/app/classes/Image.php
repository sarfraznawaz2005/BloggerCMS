<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 3/30/2015
 * Time: 4:30 PM
 */
class Image implements Crud
{
    private $metaFile = 'data/images.json';
    private $settingsFile = 'data/settings.json';

    private $imagesDir = '../public/images/';

    public function get()
    {
        global $app;
        $data = MetaDataWriter::getFileData($this->metaFile);
        $settings = MetaDataWriter::getFileData($this->settingsFile);
        //pretty_print($data);

        $app->render('imagemanager.php', array('title' => 'Image Manager', 'data' => $data, 'settings' => $settings));
    }

    public function add()
    {
        global $app;

        $message = '';
        $image = $_FILES['image'];

        if ($image['name']) {

            if (!$image['error']) {

                $file_name = basename($image['name']);
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_name = time() . uniqid() . '.' . $ext;

                $valid_file = true;
                if ($image['size'] > (5242880)) //can't be larger than 5 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                }

                if ($valid_file) {
                    move_uploaded_file($image['tmp_name'], $this->imagesDir . $file_name);

                    $data = array('image' => $this->imagesDir . $file_name);
                    MetaDataWriter::updateFileData($this->metaFile, $data, true);
                    $message = 'File uploaded successfully!';
                }

            } //if there is an error...
            else {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  ' . $image['error'];
            }
        }

        $app->flash('info', $message);
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function remove()
    {
        global $app;

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        $data = MetaDataWriter::getFileData($this->metaFile);
        $image = $data[$id]['image'];
        unset($data[$id]);

        MetaDataWriter::writeData($this->metaFile, $data);
        @unlink($image);

        $app->flash('info', 'Deleted Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function getTotalTagsCount()
    {
        $data = MetaDataWriter::getFileData($this->metaFile);
        return count($data);
    }

    public function edit()
    {
    }

    public function update()
    {
    }
}