<?php

namespace App\Entity\Form;

use App\Controller\ArticleController;
use App\Entity\User;

/**
 * Class ArticleSearchDto
 *
 * @package App\Entity\Form
 */
class ArticleSearchDto
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var User|null
     */
    private $author;

    /**
     * @var int
     */
    private $page = 0;

    /**
     * @var int
     */
    private $itemCount;

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return User|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        if (!$this->page) {
            return 1;
        }

        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    /**
     * @param int $itemCount
     */
    public function setItemCount(int $itemCount)
    {
        $this->itemCount = $itemCount;
        $pages = $this->getNumberOfPages();
        $this->page = min($pages, $this->page);
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return ceil($this->itemCount / ArticleController::ITEMS_PER_PAGE);
    }
}
