<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Service\Mapper\TagMapper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class TagService
{
    public function __construct(private TagRepository $tagRepository,
                                private EntityManagerInterface $entityManager,
                                private TransformedFinder $finder,
                                private SearchService  $searchService,
                                private TagMapper $tagMapper)
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

    public function searchTags(string $searchTerm): array {
        $query = $this->searchService->getSearchQuery($searchTerm, ['name']);
        $results = $this->finder->findRaw($query);
        return $this->tagMapper->mapToSearchTagResponseDto($results);
    }

    public function updateTags(Item $item, array $editedTags): void
    {
        $currentTagsMap = $this->mapCurrentTags($item);
        $this->removeTags($item, array_diff($currentTagsMap, $editedTags));
        $this->addTags(array_diff($editedTags, $currentTagsMap), $item);
        $this->entityManager->flush();
    }

    public function addTags(array $tagsToAdd, Item $item): void {
        foreach ($tagsToAdd as $tagName) {
            $tag = $this->tagRepository->findOneBy(['name' => $tagName]);
            if ($tag) $item->addTag($tag);
            else $item->addTag(new Tag($tagName));
        }
    }

    public function removeTags(Item $item, array $tagsToDelete): void {
        foreach ($item->getTags() as $tag) {
            if(!$tag instanceof Tag) continue;
            if (in_array($tag->getName(), $tagsToDelete)) $item->removeTag($tag);
        }
    }

    private function mapCurrentTags(Item $item): array
    {
        return array_map(function (Tag $tag) {
            return $tag->getName();
        }, $item->getTags()->toArray());
    }
}