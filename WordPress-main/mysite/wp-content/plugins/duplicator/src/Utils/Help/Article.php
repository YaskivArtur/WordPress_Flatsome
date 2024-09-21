<?php

namespace Duplicator\Utils\Help;

class Article
{
    /** @var int The ID */
    private $id = -1;

    /** @var string The title */
    private $title = '';

    /** @var string Link to the article */
    private $link = '';

    /** @var int[] Categoriy IDs */
    private $categories = [];

    /** @var string[] The tags */
    private $tags = [];

    /**
     * Constructor
     *
     * @param int      $id         The ID
     * @param string   $title      The title
     * @param string   $link       Link to the article
     * @param int[]    $categories Categories
     * @param string[] $tags       Tags
     */
    public function __construct($id, $title, $link, $categories, $tags = array())
    {
        $this->id         = $id;
        $this->title      = $title;
        $this->link       = $link;
        $this->categories = $categories;
        $this->tags       = $tags;
    }

    /**
     * Get the Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the Link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Get the Categories
     *
     * @return int[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get the Tags
     *
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get the ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
