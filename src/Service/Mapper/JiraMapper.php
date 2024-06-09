<?php

namespace App\Service\Mapper;

class JiraMapper
{
    public function mapKeyToLink(string $jsonResponse, string $baseUrl): string
    {
        $data = json_decode($jsonResponse, true);
        foreach ($data['issues'] as &$issue) {
            $issue['link'] = $baseUrl . '/browse/' . $issue['key'];
        }
        return json_encode($data);
    }
}