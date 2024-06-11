<?php

namespace App\DTO\Pojo;

use Symfony\Component\Serializer\Attribute\SerializedName;

class JiraCreateIssueBody
{
    private array $project;
    private string $summary;
    private string $description;
    #[SerializedName("issuetype")]
    private array $issueType;
    private array $priority;
    #[SerializedName("customfield_10045")]
    private ?string $customField10045;
    #[SerializedName("customfield_10044")]
    private string $customField10044;
    private array $reporter;

    public function __construct(
        string $description,
        string $priority,
        ?string $collection,
        string $reporterAccountId,
        string $link
    ) {
        $this->project = ['key' => 'HDC'];
        $this->summary = 'New issue';
        $this->description = $description;
        $this->issueType = ['name' => 'Task'];
        $this->priority = ['name' => $priority];
        $this->customField10045 = $collection;
        $this->reporter = ['accountId' => $reporterAccountId];
        $this->customField10044 = $link;
    }

    public function getProject(): array
    {
        return $this->project;
    }

    public function setProject(array $project): void
    {
        $this->project = $project;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getIssueType(): array
    {
        return $this->issueType;
    }

    public function setIssueType(array $issueType): void
    {
        $this->issueType = $issueType;
    }

    public function getPriority(): array
    {
        return $this->priority;
    }

    public function setPriority(array $priority): void
    {
        $this->priority = $priority;
    }

    public function getCustomField10045(): ?string
    {
        return $this->customField10045;
    }

    public function setCustomField10045(?string $customField10045): void
    {
        $this->customField10045 = $customField10045;
    }

    public function getReporter(): array
    {
        return $this->reporter;
    }

    public function setReporter(array $reporter): void
    {
        $this->reporter = $reporter;
    }

    public function getCustomField10044(): string
    {
        return $this->customField10044;
    }

    public function setCustomField10044(string $customField10044): void
    {
        $this->customField10044 = $customField10044;
    }
}