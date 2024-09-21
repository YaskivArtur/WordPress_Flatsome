<?php

namespace Duplicator\Utils\Help;

use DUP_LITE_Plugin_Upgrade;
use DUP_Log;
use DUP_Settings;
use Duplicator\Controllers\HelpPageController;
use Duplicator\Libs\Snap\SnapIO;
use Duplicator\Libs\Snap\SnapJson;
use Duplicator\Core\Controllers\ControllersManager;
use Duplicator\Utils\ExpireOptions;

/*
 * Dynamic Help from site documentation
 */
class Help
{
    /** @var string The doc article endpoint */
    const ARTICLE_ENDPOINT = 'https://www.duplicator.com/wp-json/wp/v2/ht-kb';

    /** @var string The doc categories endpoint */
    const CATEGORY_ENDPOINT = 'https://www.duplicator.com/wp-json/wp/v2/ht-kb-category';

    /** @var string The doc tags endpoint */
    const TAGS_ENDPOINT = 'https://www.duplicator.com/wp-json/wp/v2/ht-kb-tag';

    /** @var int Maximum number of articles to load */
    const MAX_ARTICLES = 500;

    /** @var int Maximum number of categories to load */
    const MAX_CATEGORY = 20;

    /** @var int Maximum number of tags to load */
    const MAX_TAGS = 100;

    /** @var int Per page limit */
    const PER_PAGE = 100;

    /** @var string Cron hook */
    const DOCS_EXPIRE_OPT_KEY = 'duplicator_help_docs_expire';

    /** @var Article[] The articles */
    private $articles = [];

    /** @var Category[] The categories */
    private $categories = [];

    /** @var array<int, string> The tags ID => slug */
    private $tags = [];

    /** @var self The instance */
    private static $instance = null;

    /**
     * Init
     *
     * @return void
     */
    private function __construct()
    {
        // Update data from API if cache is expired or does not exist
        if (
            !ExpireOptions::getUpdate(self::DOCS_EXPIRE_OPT_KEY, true, WEEK_IN_SECONDS) ||
            !$this->loadData()
        ) {
            $this->updateData();
        }
    }

    /**
     * Get the instance
     *
     * @return self The instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get the help page URL with tag
     *
     * @return string The URL with tag
     */
    public static function getHelpPageUrl()
    {
        return HelpPageController::getHelpLink() . '&tag=' . self::getCurrentPageTag();
    }

    /**
     * Get articles by category
     *
     * @param int $categoryId The category ID
     *
     * @return Article[] The articles
     */
    public function getArticlesByCategory($categoryId)
    {
        return array_filter($this->articles, function (Article $article) use ($categoryId) {
            return in_array($categoryId, $article->getCategories());
        });
    }

    /**
     * Get articles by tag
     *
     * @param string $tag The tag
     *
     * @return Article[] The articles
     */
    public function getArticlesByTag($tag)
    {
        if ($tag === '') {
            return [];
        }

        return array_filter($this->articles, function (Article $article) use ($tag) {
            return in_array($tag, $article->getTags());
        });
    }

    /**
     * Get top level categories.
     * E.g. categories without parents & with children or articles
     *
     * @return Category[] The categories
     */
    public function getTopLevelCategories()
    {
        return array_filter($this->categories, function (Category $category) {
            return $category->getParent() === null && (count($category->getChildren()) > 0 || $category->getArticleCount() > 0);
        });
    }

    /**
     * Load data from API
     *
     * @return array{articles: mixed[], categories: mixed[], tags: mixed[]}|array<mixed> The data
     */
    private function getDataFromApi()
    {
        $categories = $this->fetchDataFromEndpoint(
            self::CATEGORY_ENDPOINT,
            self::MAX_CATEGORY,
            [
                'id',
                'name',
                'count',
                'parent',
            ]
        );

        $articles = $this->fetchDataFromEndpoint(
            self::ARTICLE_ENDPOINT,
            self::MAX_ARTICLES,
            [
                'id',
                'title',
                'link',
                'ht-kb-category',
                'ht-kb-tag',
            ]
        );

        $tags = $this->fetchDataFromEndpoint(
            self::TAGS_ENDPOINT,
            self::MAX_TAGS,
            [
                'id',
                'slug',
            ]
        );

        if ($categories === [] || $articles === [] || $tags === []) {
            DUP_Log::Trace('Failed to load from API. No data.');
            return [];
        }

        return [
            'articles'   => $articles,
            'categories' => $categories,
            'tags'       => $tags,
        ];
    }

    /**
     * Load from API
     *
     * @param string   $endpoint The endpoint
     * @param int      $limit    Maximum number of items to load
     * @param string[] $fields   The fields to load
     *
     * @return array<mixed> The data
     */
    private function fetchDataFromEndpoint($endpoint, $limit, $fields = [])
    {
        $result      = [];
        $endpointUrl = $endpoint . '?per_page=' . self::PER_PAGE;
        if (count($fields) > 0) {
            $endpointUrl .= '&_fields[]=' . implode('&_fields[]=', $fields);
        }

        $maxPages = ceil($limit / self::PER_PAGE);
        for ($i = 1; $i <= $maxPages; $i++) {
            $endpointUrl .= '&page=' . $i;
            $response     = wp_remote_get(
                $endpointUrl,
                ['timeout' => 15]
            );
            if (is_wp_error($response)) {
                DUP_Log::Trace("Failed to load from API: {$endpointUrl}");
                DUP_Log::Trace($response->get_error_message());
                return [];
            }

            $code = wp_remote_retrieve_response_code($response);
            if ($code !== 200) {
                DUP_Log::Trace("Failed to load from API: {$endpointUrl}, code: {$code}");
                return [];
            }

            $body = wp_remote_retrieve_body($response);
            if (($data = json_decode($body, true)) === null) {
                DUP_Log::Trace("Failed to decode response: {$body}");
                return [];
            }

            $result     = array_merge($result, $data);
            $totalPages = wp_remote_retrieve_header($response, 'x-wp-totalpages');
            if ($totalPages === '' || $i >= (int) $totalPages) {
                break;
            }
        }

        $result = array_combine(array_column($result, 'id'), $result);
        return $result;
    }

    /**
     * Get the current page tag
     *
     * @return string The tag
     */
    private static function getCurrentPageTag()
    {
        if (!isset($_GET['page'])) {
            return '';
        }

        $page      = $_GET['page'];
        $tab       = isset($_GET['tab']) ? $_GET['tab'] : '';
        $innerPage = isset($_GET['inner_page']) ? $_GET['inner_page'] : '';

        switch ($page) {
            case ControllersManager::PACKAGES_SUBMENU_SLUG:
                if ($innerPage === 'new1') {
                    return 'backup_step_1';
                } elseif ($tab === 'new2') {
                    return 'backup_step_2';
                } elseif ($tab === 'new3') {
                    return 'backup_step_3';
                }

                return 'backups';
            case ControllersManager::IMPORT_SUBMENU_SLUG:
                return 'import';
            case ControllersManager::SCHEDULES_SUBMENU_SLUG:
                return 'schedules';
            case ControllersManager::STORAGE_SUBMENU_SLUG:
                return 'storages';
            case ControllersManager::TOOLS_SUBMENU_SLUG:
                if ($tab === 'templates') {
                    return 'templates';
                } elseif ($tab === 'recovery') {
                    return 'recovery';
                }

                return 'tools';
            case ControllersManager::SETTINGS_SUBMENU_SLUG:
                return 'settings';
            default:
                DUP_Log::Trace("No tag for page.");
        }

        return '';
    }

    /**
     * Get the cache path
     *
     * @return string The cache path
     */
    private static function getCacheFilePath()
    {
        $installInfo = DUP_LITE_Plugin_Upgrade::getInstallInfo();
        return DUP_Settings::getSsdirPath() . '/cache_' . md5($installInfo['time']) . '/duplicator_help_cache.json';
    }

    /**
     * Set from cache data
     *
     * @param array{articles: mixed[], categories: mixed[], tags: mixed[]} $data The data
     *
     * @return bool True if set
     */
    private function setFromArray($data)
    {
        if (!isset($data['articles']) || !isset($data['categories']) || !isset($data['tags'])) {
            DUP_Log::Trace("Invalid data.");
            return false;
        }

        foreach ($data['tags'] as $tag) {
            $this->tags[$tag['id']] = $tag['slug'];
        }

        foreach ($data['categories'] as $category) {
            $this->categories[$category['id']] = new Category(
                $category['id'],
                $category['name'],
                $category['count']
            );
        }

        foreach ($this->categories as $category) {
            if (
                ($parentId = $data['categories'][$category->getId()]['parent']) === 0 ||
                !isset($this->categories[$parentId])
            ) {
                continue;
            }

            $this->categories[$parentId]->addChild($category);
            $category->setParent($this->categories[$parentId]);
        }

        foreach ($data['articles'] as $article) {
            $this->articles[$article['id']] = new Article(
                $article['id'],
                $article['title']['rendered'],
                $article['link'],
                $article['ht-kb-category'],
                array_map(function ($tagId) {
                    return $this->tags[$tagId];
                }, $article['ht-kb-tag'])
            );
        }

        return true;
    }

    /**
     * Get data from cache
     *
     * @return bool True if loaded
     */
    private function loadData()
    {
        if (!file_exists(self::getCacheFilePath())) {
            DUP_Log::Trace("Cache file does not exist: " . self::getCacheFilePath());
            return false;
        }

        if (($contents = file_get_contents(self::getCacheFilePath())) === false) {
            DUP_Log::Trace("Failed to read cache file: " . self::getCacheFilePath());
            return false;
        }

        if (($data = json_decode($contents, true)) === null) {
            DUP_Log::Trace("Failed to decode cache file: " . self::getCacheFilePath());
            return false;
        }

        return $this->setFromArray($data);
    }

    /**
     * Save to cache
     *
     * @return bool True if saved
     */
    public function updateData()
    {
        if (($data = $this->getDataFromApi()) === []) {
            DUP_Log::Trace("Failed to load data from API.");
            return false;
        }

        $cachePath = self::getCacheFilePath();
        $cacheDir  = dirname($cachePath);
        if (!file_exists($cacheDir) && !SnapIO::mkdir($cacheDir, 0755, true)) {
            DUP_Log::Trace("Failed to create cache directory: {$cacheDir}");
            return false;
        }

        if (($encoded = SnapJson::jsonEncode($data)) === false) {
            DUP_Log::Trace("Failed to encode cache data.");
            return false;
        }

        if (file_put_contents(self::getCacheFilePath(), $encoded) === false) {
            DUP_Log::Trace("Failed to write cache file: {$cachePath}");
            return false;
        }

        return $this->setFromArray($data);
    }
}
