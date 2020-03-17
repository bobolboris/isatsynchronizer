<?php

namespace App\Other\Fillers;

use App\Entity\Back\Category as CategoryBack;
use App\Entity\Front\Category as CategoryFront;

class CategoryFiller
{
    public static function backToFront(CategoryBack $categoryBack, CategoryFront $categoryFront, int $parentId)
    {
        $categoryFront->setImage('');
        $categoryFront->setParentId($parentId);
        $categoryFront->setTop(0);
        $categoryFront->setColumn(1);
        $categoryFront->setSortOrder(0);
        $categoryFront->setStatus($categoryBack->getEnabled());

        return $categoryFront;
    }
}