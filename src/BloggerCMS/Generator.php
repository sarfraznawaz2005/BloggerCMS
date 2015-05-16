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
class Generator
{
    // data files
    private $metaFile = 'data/blog.json';
    private $postsFile = 'data/posts.json';
    private $pagesFile = 'data/pages.json';
    private $settingsFile = 'data/settings.json';
    private $customValuesFile = 'data/customvalues.json';
    private $followFile = 'data/follow.json';

    // other vars
    private $publicDir = 'public/';
    private $parser = null;
    private $generateLog = array();

    public function generateBlog()
    {
        set_time_limit(0);

        global $app;

        $this->parser = new \Parsedown();

        $data = $this->getData();

        $layout = $data['settings']['layout'] ?: 'default';
        $layoutDir = $app->view->getData('layoutsDir') . $layout . '/';

        // first copy all contents of template to public folder
        copy_directory($layoutDir, $this->publicDir);

        // now create actual html pages
        $mustache = new \Mustache_Engine(
           array(
              'loader' => new \Mustache_Loader_FilesystemLoader($layoutDir),
              'partials_loader' => new \Mustache_Loader_FilesystemLoader($layoutDir . '/partials')
           )
        );

        $mustacheFiles = glob($layoutDir . '/*.mustache');

        $excludedFiles = array(
           'category',
           'post',
           'page',
           'archive',
           'tag',
        );

        foreach ($mustacheFiles as $mustacheFile) {
            $fileName = basename($mustacheFile);
            $fileName = str_replace('.mustache', '', $fileName);

            // we will generate these later
            if (true === in_array($fileName, $excludedFiles)) {
                continue;
            }

            $template = $mustache->loadTemplate($fileName);
            $html = $template->render($data);

            file_put_contents($this->publicDir . $fileName . '.html', $html);
        }

        // delete mustache particals folders from public folder
        rrmdir($this->publicDir . 'partials/');

        // delete *.mustache from public dir
        $mustacheFiles = glob($this->publicDir . '/*.mustache');

        foreach ($mustacheFiles as $mustacheFile) {
            @unlink($mustacheFile);
        }

        // generate post files
        $this->generatePostPageFiles($mustache, $data, 'post');
        // generate page files
        $this->generatePostPageFiles($mustache, $data, 'page');

        // generate category and tag files
        $this->generateCategoryTagFiles($mustache, $data, 'category');
        $this->generateCategoryTagFiles($mustache, $data, 'tag');

        // generate archive files
        $this->generateArchiveFiles($mustache, $data);

        // generate RSS file
        $this->generateRSS($data);

        // generate sitemap.xml
        $this->generateSitemap($data);

        // copy blog data file
        copy('data/blog.json', 'public/data/blog.json');

        $message = '';
        $message .= 'Blog has been generated in <strong>public</strong> folder :)<br><br>';
        $message .= '<a id="viewGenLog" class="btn btn-primary">View Log</a><br><br>';
        $message .= '<div id="genlog">' . $this->getGenerateLog($this->generateLog) . '</div>';

        echo $message;
    }

    protected function getData()
    {
        $data['settings'] = MetaDataWriter::getFileData($this->settingsFile);

        if (empty($data['settings']['url'])) {
            exit('Please specify Blog URL in settings first !');
        }

        $data['settings']['url'] = rtrim($data['settings']['url'], '/');

        $data['customValues'] = MetaDataWriter::getFileData($this->customValuesFile);
        $data['pages'] = MetaDataWriter::getFileData($this->pagesFile);
        $data['follow'] = MetaDataWriter::getFileData($this->followFile);
        $posts = MetaDataWriter::getFileData($this->postsFile);

        // we want to create pages for only "published" status posts
        foreach ($posts as $post) {
            if ($post['status'] === 'draft' || $post['status'] === 'trashed') {
                continue;
            }

            $data['posts'][] = $post;
        }

        $dates = array();
        $addedCategories = array();
        $categories = array();
        $tagsCloud = array();

        foreach ($data['posts'] as $key => $post) {
            // convert posts markdown to html
            $data['posts'][$key]['body'] = $this->parser->text($post['body']);
            // see whether to show full posts body or just titles
            $data['posts'][$key]['showbody'] = '1';

            $dates[] = strtotime($post['dated']);

            // categories
            if (false === in_array($post['category'], $addedCategories)) {
                $categories[] = array('category' => $post['category'], 'categoryslug' => $post['categoryslug']);
                $addedCategories[] = $post['category'];
            }

            // tags
            $tagsCloud[] = $post['tags'];
        }

        // sort posts by latest first
        array_multisort($dates, SORT_DESC, $data['posts']);

        // for latest posts - show 5 max
        $data['latestPosts'] = array_slice($data['posts'], 0, 5);

        // total posts to show on homepage
        //$countHomePosts = $data['settings']['number_posts'] ?: 10;
        $data['homePosts'] = array_slice($data['posts'], 0, 1);

        // sort categories
        sort($categories);
        $data['categories'] = $categories;

        // generate tags cloud
        $tagsCloud = arrayFlatten($tagsCloud);
        natcasesort($tagsCloud);
        $tagsCloud = implode(',', $tagsCloud);

        // Store frequency of words in an array
        $tagFreq = array();

        // Get individual words and build a frequency table
        foreach (str_word_count($tagsCloud, 1) as $word) {
            // For each word found in the frequency table, increment its value by one
            array_key_exists($word, $tagFreq) ? $tagFreq[$word] ++ : $tagFreq[$word] = 0;
        }

        $data['tagsCloud'] = $this->generateTagCloud($tagFreq);

        // convert pages markdown to html
        foreach ($data['pages'] as $key => $page) {
            $data['pages'][$key]['body'] = $this->parser->text($page['body']);
        }

        // generate archives
        $data['archives'] = $this->generateArchives($data['posts']);

        // write whole blog data to file
        if (!file_exists($this->publicDir . 'data')) {
            mkdir($this->publicDir . 'data');
        }

        MetaDataWriter::writeData($this->metaFile, $data);

        return $data;
    }

    protected function generatePostPageFiles($mustache, $data, $type)
    {
        if (!file_exists($this->publicDir . $type) && !mkdir($this->publicDir . $type)) {
            echo "Error: could not make $type directory in public folder";
            exit;
        }

        foreach ($data[$type . 's'] as $key => $item) {
            $data[$type] = $item;

            $template = $mustache->loadTemplate($type);
            $html = $template->render($data);

            // create new folder for this page/post
            $folderPath = $this->publicDir . $type . '/' . $item['slug'];

            if (!file_exists($folderPath) && !mkdir($folderPath)) {
                echo "Error: could not make $folderPath directory";
                exit;
            }

            if (file_put_contents($folderPath . '/index.html', $html)) {
                // add to generate log
                $this->generateLog[$type . 's'][] = $folderPath . '/index.html';
            }

        }

        return true;
    }

    protected function generateCategoryTagFiles($mustache, $data, $type)
    {
        if (!file_exists($this->publicDir . $type) && !mkdir($this->publicDir . $type)) {
            echo "Error: could not make $type directory in public folder";
            exit;
        }

        if ($type === 'category') {
            $items = $data['categories'];

            foreach ($items as $item) {
                $itemRootDir = $this->publicDir . $type . '/';

                $itemData = array();
                foreach ($data['posts'] as $post) {
                    if ($post[$type] === $item['category']) {
                        $itemData[] = $post;
                    }
                }

                $data['categoryPosts'] = $itemData;

                $template = $mustache->loadTemplate($type);
                $html = $template->render($data);

                $fileName = getSlugName($item['category']);

                $folderPath = $itemRootDir . $fileName;

                if (!file_exists($folderPath) && !mkdir($folderPath)) {
                    echo "Error: could not make $folderPath directory";
                    exit;
                }

                if (file_put_contents($folderPath . "/index.html", $html)) {
                    // add to generate log
                    $this->generateLog['categories'][] = $folderPath . "/index.html";
                }
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

                $folderPath = $itemRootDir . $fileName;

                if (!file_exists($folderPath) && !mkdir($folderPath)) {
                    echo "Error: could not make $folderPath directory";
                    exit;
                }

                if (file_put_contents($folderPath . "/index.html", $html)) {
                    // add to generate log
                    $this->generateLog['tags'][] = $folderPath . "/index.html";
                }
            }
        }
    }

    protected function generateTagCloud($data = array(), $minFontSize = 12, $maxFontSize = 30)
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
               . '" class="tag_cloud" href="' . $base . '/tag/' . strtolower($tag)
               . '" title="' . ++ $count . ' total posts" data-original-title="' . ++ $count . ' total posts" data-toggle="tooltip">'
               . htmlspecialchars(stripslashes($tag)) . '</a>';
        }

        return implode("\n", $cloudTags) . "\n";
    }

    protected function generateArchives($posts)
    {
        $archives = '<ul class="archives list-group">';
        $datesSorted = array();

        foreach ($posts as $post) {
            if (!$post['title']) {
                continue;
            }

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

            // show count of posts in each category
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

    protected function generateArchiveFiles($mustache, $data)
    {
        if (!file_exists($this->publicDir . 'archive') && !mkdir($this->publicDir . 'archive')) {
            echo "Error: could not make archives directory in public folder";
            exit;
        }

        $archivesDir = $this->publicDir . 'archive/';

        $archiveNames = array();
        foreach ($data['posts'] as $postItem) {
            $archiveNames[] = getSlugName(date('F Y', strtotime($postItem['dated'])));
        }

        foreach ($archiveNames as $archiveName) {
            $archiveNames[] = getSlugName(date('F Y', strtotime($postItem['dated'])));

            $archivesData = array();
            foreach ($data['posts'] as $postItem) {
                if (!trim($postItem['dated'])) {
                    continue;
                }

                $postArchiveName = getSlugName(date('F Y', strtotime($postItem['dated'])));

                if (!file_exists($archivesDir . $archiveName) && !mkdir($archivesDir . $archiveName)) {
                    echo "Error: could not make $archiveName directory in public folder";
                    exit;
                }

                if ($archiveName === $postArchiveName) {
                    $archivesData[] = $postItem;

                    $data['archivePosts'] = $archivesData;
                }

            }

            $template = $mustache->loadTemplate('archive');
            $html = $template->render($data);

            if (file_put_contents($archivesDir . $archiveName . "/index.html", $html)) {
                // add to generate log
                $this->generateLog['arhives'][] = $archivesDir . $archiveName . "/index.html";
            }
        }

    }

    protected function generateRSS($data)
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
               ) . '</link>' . $newline;
            $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($post['dated'])) . '</pubDate>' . $newline;
            $rssfeed .= '</item>' . $newline;
        }

        $rssfeed .= '</channel>' . $newline;
        $rssfeed .= '</rss>' . $newline;

        file_put_contents($this->publicDir . 'rss.xml', $rssfeed) or die('error writing rss file!');
    }

    protected function generateSitemap($data)
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
            $postURL .= '/post/' . getSlugName($post['title']);

            $datetime = new \DateTime($post['dated']);
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
            $pageURL .= '/page/' . getSlugName($page['title']);

            $sitemap .= '<url>' . $newline;
            $sitemap .= '<loc>' . $pageURL . '/</loc>' . $newline;
            $sitemap .= '<changefreq>weekly</changefreq>' . $newline;
            $sitemap .= '<priority>1.00</priority>' . $newline;
            $sitemap .= '</url>' . $newline;
        }

        $sitemap .= '</urlset>';

        file_put_contents($this->publicDir . 'sitemap.xml', $sitemap) or die('error writing sitemap file!');
    }

    protected function getGenerateLog(array $generateLog)
    {
        $output = '';

        $pages = $generateLog['pages'];
        $pages = array_unique($pages);

        $posts = $generateLog['posts'];
        $posts = array_unique($posts);

        $categories = $generateLog['categories'];
        $categories = array_unique($categories);

        $tags = $generateLog['tags'];
        $tags = array_unique($tags);

        $arhives = $generateLog['arhives'];
        $arhives = array_unique($arhives);

        $output .= '<strong>Posts:</strong><br>' . implode('<br>', $posts) . '<hr>';
        $output .= '<strong>Pages:</strong><br>' . implode('<br>', $pages) . '<hr>';
        $output .= '<strong>Categories:</strong><br>' . implode('<br>', $categories) . '<hr>';
        $output .= '<strong>Tags:</strong><br>' . implode('<br>', $tags) . '<hr>';
        $output .= '<strong>Archives:</strong><br>' . implode('<br>', $arhives);

        return $output;
    }
}
