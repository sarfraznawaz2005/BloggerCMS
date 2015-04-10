<?php

/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 4/2/2015
 * Time: 1:26 PM
 */
class Generate
{
    // data files
    private $metaFile = 'data/blog.json';
    private $postsFile = 'data/posts.json';
    private $pagesFile = 'data/pages.json';
    private $settingsFile = 'data/settings.json';
    private $customValuesFile = 'data/customvalues.json';
    private $followFile = 'data/follow.json';

    // other vars
    private $publicDir = '../public/';

    private $parser = null;

    public function generateBlog()
    {
        set_time_limit(0);

        global $app;
        $message = "Blog has been generated in <strong>public</strong> folder <i class='fa fa-smile-o'></i>";

        $this->parser = new Parsedown();

        $data = $this->getData();

        $layout = $data['settings']['layout'] ?: 'default';
        $layoutDir = $app->view->getData('layoutsDir') . $layout . '/';

        // first copy all contents of template to public folder
        $this->copy_directory($layoutDir, $this->publicDir);
        
        // delete mustache particals folders from public folder
        $this->rrmdir($this->publicDir . 'partials/');

        // delete tpl from public dir
        $tplFiles = glob($this->publicDir . '/*.mustache');

        foreach ($tplFiles as $tplFile) {
            @unlink($tplFile);
        }

        // now create actual html pages
        $mustache = new Mustache_Engine(
           array(
              'loader' => new Mustache_Loader_FilesystemLoader($layoutDir),
              'partials_loader' => new Mustache_Loader_FilesystemLoader($layoutDir . '/partials')
           )
        );

        $tplFiles = glob($layoutDir . '/*.mustache');

        foreach ($tplFiles as $tplFile) {
            $fileName = basename($tplFile);
            $fileName = str_replace('.mustache', '', $fileName);

            if ($fileName === 'category' ||
               $fileName === 'post' ||
               $fileName === 'page' ||
               $fileName === 'archive' ||
               $fileName === 'tag'
            ) {
                continue;
            }

            $template = $mustache->loadTemplate($fileName);
            $html = $template->render($data);

            file_put_contents($this->publicDir . $fileName . '.html', $html);
        }

        // generate post and page files
        $this->generatePostPageFiles($mustache, $data, 'page');
        $this->generatePostPageFiles($mustache, $data, 'post');

        // generate category and tag files
        $this->generateCategoryTagFiles($mustache, $data, 'category');
        $this->generateCategoryTagFiles($mustache, $data, 'tag');

        // generate archive files
        $this->generateArchiveFiles($mustache, $data);

        // generate RSS file
        $this->generateRSS($data);

        // generate sitemap.xml
        $this->generateSitemap($data);

        // copy data folder too
        $this->copy_directory('data', '../public/data');

        echo $message;
    }

    private function getData()
    {
        $data['settings'] = MetaDataWriter::getFileData($this->settingsFile);

        if (empty($data['settings']['url'])) {
            exit('Please specify Blog URL in settings first !');
        }

        $data['settings']['url'] = rtrim($data['settings']['url'], '/');

        $data['customValues'] = MetaDataWriter::getFileData($this->customValuesFile);
        $posts = MetaDataWriter::getFileData($this->postsFile);
        $data['pages'] = MetaDataWriter::getFileData($this->pagesFile);
        $data['follow'] = MetaDataWriter::getFileData($this->followFile);

        // only add published posts
        foreach ($posts as $post) {
            if ($post['status'] === 'draft' || $post['status'] === 'trashed') {
                continue;
            }

            $data['posts'][] = $post;
        }

        // convert posts markdown to html
        foreach ($data['posts'] as $key => $post) {
            $data['posts'][$key]['body'] = $this->parser->text($post['body']);
        }

        // convert pages markdown to html
        foreach ($data['pages'] as $key => $page) {
            $data['pages'][$key]['body'] = $this->parser->text($page['body']);
        }

        // sort posts by latest first
        $dates = array();
        foreach ($data['posts'] as $key => $value) {
            $dates[] = strtotime($value['dated']);
        }

        array_multisort($dates, SORT_DESC, $data['posts']);

        // categories
        $categories = array();
        foreach ($data['posts'] as $post) {
            $categories[] = $post['category'];
        }

        $categories = array_unique($categories);
        sort($categories);
        $data['categories'] = $categories;
        //pretty_print($data);

        // see whether to show full posts body or just titles
        if (empty($data['settings']['only_titles'])) {
            $showbody = true;
        } else {
            $showbody = false;
        }

        foreach ($data['posts'] as $key => $post) {
            $data['posts'][$key]['showbody'] = $showbody;
        }


        // for latest posts - show 10 max
        $data['latestPosts'] = array_slice($data['posts'], 0, 10);

        // total posts to show on homepage
        //$countHomePosts = $data['settings']['number_posts'] ?: 10;
        $data['homePosts'] = array_slice($data['posts'], 0, 1);

        // generate tags cloud
        $tagsCloud = '';
        foreach ($data['posts'] as $post) {
            foreach ($post['tags'] as $tag) {
                $tagsCloud .= $tag . ',';
            }
        }

        // sort tags
        $tagsCloud = explode(',', $tagsCloud);
        natcasesort($tagsCloud);
        $tagsCloud = implode(',', $tagsCloud);

        // Store frequency of words in an array
        $tagFreq = array();

        // Get individual words and build a frequency table
        foreach (str_word_count($tagsCloud, 1) as $word) {
            // For each word found in the frequency table, increment its value by one
            array_key_exists($word, $tagFreq) ? $tagFreq[$word] ++ : $tagFreq[$word] = 0;
        }

        $data['tagsCloud'] = $this->createTagCloud($tagFreq);

        // generate archives
        $data['archives'] = $this->createArchives($data['posts']);

        // write whole blog data to file
        MetaDataWriter::writeData($this->metaFile, $data);

        return $data;
    }

    private function generatePostPageFiles($mustache, $data, $type)
    {
        if (!file_exists($this->publicDir . $type) && !mkdir($this->publicDir . $type)) {
            echo "Error: could not make $type directly in public folder";
            exit;
        }

        $pagesDir = $this->publicDir . $type . '/';

        foreach ($data[$type . 's'] as &$item) {
            $data[$type] = $item;

            $template = $mustache->loadTemplate($type);
            $html = $template->render($data);

            file_put_contents($pagesDir . $item['slug'] . '.html', $html);
        }

        return true;
    }

    private function generateCategoryTagFiles($mustache, $data, $type)
    {
        if (!file_exists($this->publicDir . $type) && !mkdir($this->publicDir . $type)) {
            echo "Error: could not make $type directly in public folder";
            exit;
        }

        if ($type === 'category') {

            $items = $data['categories'];

            foreach ($items as $item) {
                $itemRootDir = $this->publicDir . $type . '/';

                $itemData = array();
                foreach ($data['posts'] as $post) {
                    if ($post[$type] === $item) {
                        $itemData[] = $post;
                    }
                }

                $data['categoryPosts'] = $itemData;

                $template = $mustache->loadTemplate($type);
                $html = $template->render($data);

                $fileName = getSlugName($item);
                file_put_contents($itemRootDir . "/$fileName.html", $html);
            }
        } else {
            $items = array();

            foreach ($data['posts'] as $post) {
                $items[] = $post['tags'];
            }

            $items = arrayFlatten($items);
            $items = array_unique($items);

            foreach ($items as $item) {
                $itemRootDir = $this->publicDir . $type . '/';

                $itemData = array();
                foreach ($data['posts'] as $post) {
                    foreach ($post['tags'] as $tag) {
                        if ($tag === $item) {
                            $itemData[] = $post;
                        }
                    }
                }

                $data['tagPosts'] = $itemData;

                $template = $mustache->loadTemplate($type);
                $html = $template->render($data);

                $fileName = getSlugName($item);
                file_put_contents($itemRootDir . "/$fileName.html", $html);
            }
        }
    }

    function copy_directory($source, $destination)
    {
        if (is_dir($source)) {

            @mkdir($destination);
            $directory = dir($source);

            while (false !== ($readdirectory = $directory->read())) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }

                $PathDir = $source . '/' . $readdirectory;

                if (is_dir($PathDir)) {
                    $this->copy_directory($PathDir, $destination . '/' . $readdirectory);
                    continue;
                }

                copy($PathDir, $destination . '/' . $readdirectory);
            }

            $directory->close();

        } else {
            copy($source, $destination);
        }
    }

    private function createTagCloud($data = array(), $minFontSize = 12, $maxFontSize = 30)
    {
        $minimumCount = min(array_values($data));
        $maximumCount = max(array_values($data));
        $spread = $maximumCount - $minimumCount;
        $cloudTags = array();

        $spread == 0 && $spread = 1;

        $settings = MetaDataWriter::getFileData($this->settingsFile);
        $base = $settings['url'];
        $base = rtrim($base, '/');

        foreach ($data as $tag => $count) {
            $size = $minFontSize + ($count - $minimumCount)
               * ($maxFontSize - $minFontSize) / $spread;
            $cloudTags[] = '<a style="font-size: ' . floor($size) . 'px'
               . '" class="tag_cloud" href="' . $base . '/tag/' . strtolower($tag) . '.html'
               . '" title="\'' . $tag . '\' returned a count of ' . $count . '">'
               . htmlspecialchars(stripslashes($tag)) . '</a>';
        }

        return implode("\n", $cloudTags) . "\n";
    }

    private function createArchives($posts)
    {
        $archives = '<ul class="archives list-group">';
        $datesSorted = array();

        foreach ($posts as $post) {
            $key = date('yyyy-mm-dd', strtotime($post['dated']));
            $datesSorted[$key] = date('F Y', strtotime($post['dated']));
        }

        $datesSorted = array_unique($datesSorted);
        //krsort($datesSorted);

        usort(
           $datesSorted,
           function ($a, $b) {
               return strtotime($a) < strtotime($b);
           }
        );

        $settings = MetaDataWriter::getFileData($this->settingsFile);
        $base = $settings['url'];
        $base = rtrim($base, '/');

        foreach ($datesSorted as $date) {

            /*
            $postCount = 0;
            foreach ($posts as $postItem) {
                if ($date === date('F Y', strtotime($postItem['dated']))) {
                    $postCount ++;
                }
            }
            */

            $archives .= '<li class="list-group-item archive_link"><a href="' . $base . '/archive/' . getSlugName(
                  $date
               ) . '">' . $date . '</a></li>';
        }

        $archives .= '</ul>';

        return $archives;
    }

    private function generateArchiveFiles($mustache, $data)
    {
        if (!file_exists($this->publicDir . 'archive') && !mkdir($this->publicDir . 'archive')) {
            echo "Error: could not make archives directly in public folder";
            exit;
        }

        $archivesDir = $this->publicDir . 'archive/';

        foreach ($data['posts'] as $post) {
            $archiveName = getSlugName(date('F Y', strtotime($post['dated'])));

            if (!file_exists($archivesDir . $archiveName) && !mkdir($archivesDir . $archiveName)) {
                echo "Error: could not make $archiveName directly in public folder";
                exit;
            }

            $archivesData = array();
            foreach ($data['posts'] as $postItem) {
                if ($archiveName === getSlugName(date('F Y', strtotime($postItem['dated'])))) {
                    $archivesData[] = $postItem;
                }

                $data['archivePosts'] = $archivesData;

                $template = $mustache->loadTemplate('archive');
                $html = $template->render($data);

                file_put_contents($archivesDir . $archiveName . "/index.html", $html);
            }
        }
    }

    private function generateRSS($data)
    {
        $newline = PHP_EOL;
        $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>' . $newline;
        $rssfeed .= '<rss version="2.0">' . $newline;
        $rssfeed .= '<channel>' . $newline;
        $rssfeed .= '<title>' . $data['settings']['title'] . '</title>' . $newline;
        $rssfeed .= '<link>' . $data['settings']['url'] . '</link>' . $newline;
        $rssfeed .= '<description>' . $data['settings']['tagline'] . '</description>' . $newline;
        $rssfeed .= '<language>en-us</language>' . $newline;

        foreach ($data['posts'] as $post) {
            $rssfeed .= '<item>' . $newline;
            $rssfeed .= '<title>' . $post['title'] . '</title>' . $newline;
            $rssfeed .= '<description><![CDATA[' . $post['body'] . ']]></description>' . $newline;
            $rssfeed .= '<link>' . $data['settings']['url'] . '/post/' . getSlugName(
                  $post['title']
               ) . '.html</link>' . $newline;
            $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($post['dated'])) . '</pubDate>' . $newline;
            $rssfeed .= '</item>' . $newline;
        }

        $rssfeed .= '</channel>' . $newline;
        $rssfeed .= '</rss>' . $newline;

        file_put_contents($this->publicDir . 'rss.xml', $rssfeed) or die('error writing rss file!');
    }

    private function generateSitemap($data)
    {
        $newline = PHP_EOL;

        $sitemap = <<< SITEMAP
<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">$newline
SITEMAP;

        // for posts
        foreach ($data['posts'] as $post) {
            $postURL = $data['settings']['url'];
            $postURL = rtrim($postURL, '/');
            $postURL .= '/post/' . getSlugName($post['title']) . '.html';

            $datetime = new DateTime($post['dated']);
            $lastmod = $datetime->format('Y-m-d\TH:i:sP');

            $sitemap .= '<url>' . $newline;
            $sitemap .= '<loc>' . $postURL . '/</loc>' . $newline;
            $sitemap .= '<lastmod>' . $lastmod . '</lastmod>' . $newline;
            $sitemap .= '<changefreq>daily</changefreq>' . $newline;
            $sitemap .= '<priority>1.00</priority>' . $newline;
            $sitemap .= '</url>' . $newline;
        }

        // for pages
        foreach ($data['pages'] as $page) {
            $pageURL = $data['settings']['url'];
            $pageURL = rtrim($pageURL, '/');
            $pageURL .= '/page/' . getSlugName($page['title']) . '.html';

            $sitemap .= '<url>' . $newline;
            $sitemap .= '<loc>' . $pageURL . '/</loc>' . $newline;
            $sitemap .= '<changefreq>weekly</changefreq>' . $newline;
            $sitemap .= '<priority>1.00</priority>' . $newline;
            $sitemap .= '</url>' . $newline;
        }

        $sitemap .= '</urlset>';

        file_put_contents($this->publicDir . 'sitemap.xml', $sitemap) or die('error writing sitemap file!');
    }
    
    function rrmdir($dir) { 
       if (is_dir($dir)) { 
         $objects = scandir($dir); 
         foreach ($objects as $object) { 
           if ($object != "." && $object != "..") { 
             if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
           } 
         } 
         reset($objects); 
         rmdir($dir); 
       } 
    }     

}