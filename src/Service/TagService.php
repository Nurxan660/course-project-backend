<?php

namespace App\Service;

use App\Repository\TagRepository;

class TagService
{
    public function __construct(private TagRepository $tagRepository)
    {
    }

    public function getTagsMap(array $tagNames): array
    {
        $tags = $this->tagRepository->findBy(['name' => $tagNames]);
        $tagsMap = [];
        foreach ($tags as $tag)
            $tagsMap[$tag->getName()] = $tag;
        return $tagsMap;
    }
}