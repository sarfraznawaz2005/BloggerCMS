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
class Image implements Crud
{
    private $metaFile = 'data/images.json';
    private $settingsFile = 'data/settings.json';

    private $imagesDir = 'public/images/';

    public function get()
    {
        global $app;
        $data = MetaDataWriter::getFileData($this->metaFile);
        $settings = MetaDataWriter::getFileData($this->settingsFile);

        // create images folder if one doesn't exist
        if (!file_exists($this->imagesDir) && !mkdir($this->imagesDir)) {
            echo "Error: could not make $this->imagesDir directory";
            exit;
        }

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

    public function getTotalImagesCount()
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