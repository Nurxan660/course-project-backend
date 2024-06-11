<?php

namespace App\Service;

use App\DTO\JiraDTO\CreateJiraUserRes;
use App\DTO\JiraDTO\CreateTickerRes;
use App\DTO\JiraDTO\HelpRequestDto;
use App\DTO\JiraDTO\UserTicketsResponse;
use App\DTO\Pojo\JiraCreateIssueBody;
use App\DTO\Pojo\JiraGetIssueBody;
use App\Entity\User;
use App\Exception\CreateTicketException;
use App\Service\Mapper\JiraMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class JiraService
{
    public function __construct(private string $jiraBaseUrl,
                                private string $jiraEmail,
                                private string $jiraApiToken,
                                private HttpClientInterface $httpClient,
                                private SerializerInterface $serializer,
                                private TranslatorInterface $translator,
                                private Security $security,
                                private EntityManagerInterface $entityManager,
                                private JiraMapper $jiraMapper)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws CreateTicketException
     */
    public function handleCreateTicket(HelpRequestDto $dto): string
    {
        $createUserDto = $this->getJiraAccountId($this->security->getUser());
        return $this->createJiraTicket($dto, $createUserDto);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws CreateTicketException
     */
    public function getJiraAccountId(User $user): CreateJiraUserRes
    {
        $accountId = $user->getJiraAccountId();
        if ($accountId) return new CreateJiraUserRes($accountId);
        return $this->handleCreateJiraUser($user);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws CreateTicketException
     */
    private function createJiraTicket(HelpRequestDto $dto, CreateJiraUserRes $createJiraUserRes): string
    {
        $response = $this->httpClient->request('POST', $this->jiraBaseUrl . '/rest/api/2/issue/', [
            'headers' => $this->prepareHeaders(),
            'body' => $this->prepareCreateTicketBody($dto, $createJiraUserRes),
        ]);
        return $this->handleCreateJiraTicketResponse($response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws CreateTicketException
     */
    private function handleCreateJiraTicketResponse(ResponseInterface $response): string
    {
        if($response->getStatusCode() !== 201) {
            throw new CreateTicketException();
        }
        return $this->translator->trans('ticket_created_response', [], 'api_success');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws CreateTicketException
     */
    private function handleCreateJiraUser(User $user): CreateJiraUserRes
    {
        $response = $this->createJiraUser();
        $dto = $this->serializer->deserialize($response->getContent(), CreateJiraUserRes::class, 'json');
        $this->saveJiraAccountId($response, $user, $dto);
        return $dto;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function createJiraUser(): ResponseInterface
    {
        return $this->httpClient->request('POST', $this->jiraBaseUrl . '/rest/api/3/user', [
            'headers' => $this->prepareHeaders(),
            'body' => $this->prepareCreateUserBody()
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws CreateTicketException
     */
    private function saveJiraAccountId(ResponseInterface $response, User $user, CreateJiraUserRes $dto): void
    {
        if($response->getStatusCode() !== Response::HTTP_CREATED) throw new CreateTicketException();
        $user->setJiraAccountId($dto->getAccountId());
        $this->entityManager->flush();
    }

    private function prepareHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode("{$this->jiraEmail}:{$this->jiraApiToken}")
        ];
    }

    private function prepareCreateUserBody(): string
    {
        return $this->serializer->serialize([
            'emailAddress' => $this->security->getUser()->getUserIdentifier(),
            'products' => ['jira-software']
        ], 'json');
    }

    private function prepareCreateTicketBody(HelpRequestDto $dto, CreateJiraUserRes $createJiraUserRes): string
    {
        return $this->serializer->serialize(['fields' => new JiraCreateIssueBody($dto->getDescription(),
            $dto->getPriority(), $dto->getCollection(),
            $createJiraUserRes->getAccountId(), $dto->getLink())], 'json');
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws CreateTicketException
     * @throws ServerExceptionInterface
     */
    public function getTickets(int $startAt): string
    {
        $response = $this->httpClient->request('POST', $this->jiraBaseUrl . '/rest/api/3/search', [
            'headers' => $this->prepareHeaders(),
            'body' => $this->prepareGetTicketsBody($startAt)
        ]);
        return $this->jiraMapper->mapKeyToLink($response->getContent(), $this->jiraBaseUrl);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws CreateTicketException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function prepareGetTicketsBody(int $startAt): string
    {
        $dto = $this->getJiraAccountId($this->security->getUser());
        $jql = "reporter = '{$dto->getAccountId()}'";
        return $this->serializer->serialize(new JiraGetIssueBody($jql, $startAt), 'json');
    }
}