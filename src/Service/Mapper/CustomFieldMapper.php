<?php

namespace App\Service\Mapper;

class CustomFieldMapper
{
    public function mapToCustomFieldValues(array $customFieldValues): array
    {
        $res = $tags = [];
        foreach ($customFieldValues as $cfv)
            $this->processCustomFieldValue($cfv, $res, $tags);
        $res['tags'] = implode(', ', array_values($tags));;
        return $res;
    }

    private function processCustomFieldValue(array $cfv, array &$res, array &$tags): void {
        if(!isset($res['name'])) $res['name'] = $cfv['itemName'];
        if(!in_array($cfv['tagName'], $tags)) $tags[] = $cfv['tagName'];
        $res[$cfv['name']] = $cfv['value'];
    }
}