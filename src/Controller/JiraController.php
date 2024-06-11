<?php

namespace App\Controller;

use App\DTO\JiraDTO\HelpRequestDto;
use App\Exception\CreateTicketException;
use App\Exception\ValidationException;
use App\Service\JiraService;
use App\Service\Mapper\JiraMapper;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/api/jira', name: 'jira_api_')]
class JiraController extends AbstractController
{
    public function __construct(private JiraService $jiraService,
                                private SerializerInterface $serializer,
                                private ValidatorService  $validatorService)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws CreateTicketException
     * @throws ServerExceptionInterface
     * @throws ValidationException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/ticket', name: 'create_ticket', methods: ['POST'])]
    public function createTicket(Request $request): JsonResponse {
        $dto = $this->serializer->deserialize($request->getContent(), HelpRequestDto::class, 'json');
        $this->validatorService->validate($dto);
        $jsonRes = $this->jiraService->handleCreateTicket($dto);
        return new JsonResponse(["message" => $jsonRes], Response::HTTP_OK);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws CreateTicketException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/tickets', name: 'get_tickets', methods: ['GET'])]
    public function getTickets(Request $request): JsonResponse {
        $page = $request->query->getInt('page');
        $res = $this->jiraService->getTickets($page);
        return new JsonResponse($res, Response::HTTP_OK, [], true);
    }
}