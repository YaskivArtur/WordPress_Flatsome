<?php

namespace Duplicator\Utils\Help;

class Category
{
    /** @var int The ID */
    private $id = -1;

    /** @var string The name */
    private $name = '';

    /** @var int Number of articles */
    private $articleCount = 0;

    /** @var Category|null The parent */
    private $parent = null;

    /** @var Category[] The children */
    private $children = [];


    /**
     * Constructor
     *
     * @param int    $id           The ID
     * @param string $name         The name
     * @param int    $articleCount Number of articles
     */
    public function __construct($id, $name, $articleCount)
    {
        $this->id           = $id;
        $this->name         = $name;
        $this->articleCount = $articleCount;
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

    /**
     * Get the Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the Article Count
     *
     * @return int
     */
    public function getArticleCount()
    {
        return $this->articleCount;
    }

    /**
     * Get the Children
     *
     * @return Category[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add a child
     *
     * @param Category $child The child
     *
     * @return void
     */
    public function addChild(Category $child)
    {
        if (isset($this->children[$child->getId()])) {
            return;
        }

        $this->children[$child->getId()] = $child;
    }

    /**
     * Get the Parent
     *
     * @return Category|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the Parent
     *
     * @param Category $parent The parent
     *
     * @return void
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
    }
}
