<?php

declare(strict_types=1);

namespace App\Handler\User\Data;

use App\Doctrine\ORM\QueryBuilder\User\ListUserQueryBuilder;
use App\Enum\User\Channel;
use App\Form\User\ListSearchType;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\Message;
use App\View\Pagination\PaginationViewFactory;
use App\View\User\ListSearchRequestData;
use App\ViewTransformer\User\UserViewTransformer;
use RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;

class GetUsersQueryHandler implements HandlerInterface
{
    private FormFactoryInterface $formFactory;
    private ListUserQueryBuilder $listUserQueryBuilder;
    private PaginationViewFactory $paginationViewFactory;
    private UserViewTransformer $userViewTransformer;
    private GetUsersQueryResultMessageHandler $getUsersQueryResultMessageHandler;

    public function __construct(
        FormFactoryInterface $formFactory,
        ListUserQueryBuilder $listUserQueryBuilder,
        PaginationViewFactory $paginationViewFactory,
        UserViewTransformer $userViewTransformer,
        GetUsersQueryResultMessageHandler $getUsersQueryResultMessageHandler
    ) {
        $this->formFactory = $formFactory;
        $this->listUserQueryBuilder = $listUserQueryBuilder;
        $this->paginationViewFactory = $paginationViewFactory;
        $this->userViewTransformer = $userViewTransformer;
        $this->getUsersQueryResultMessageHandler = $getUsersQueryResultMessageHandler;
    }

    public function supports(Message $message): bool
    {
        return Channel::GET_USERS === $message->getChannel();
    }

    public function __invoke(Message $message): void
    {
        $data = new ListSearchRequestData();

        $form = $this
            ->formFactory
            ->create(
                ListSearchType::class,
                $data
            );

        $formData = $this->getFormData($message);
        $form->submit($formData, false);

        if (!$form->isValid()) {
            // TODO: Send events
            throw new RuntimeException("Invalid form!");
        }

        $queryBuilder = $this
            ->listUserQueryBuilder
            ->createQueryBuilder($data);

        $paginationView = $this
            ->paginationViewFactory
            ->fromQueryBuilderWithCallback(
                $queryBuilder,
                $this->userViewTransformer,
                $data->page,
                $data->limit
            );

        $this
            ->getUsersQueryResultMessageHandler
            ->__invoke(
                $message,
                $paginationView
            );
    }

    private function getFormData(Message $message): array
    {
        $formFata = [];
        $payload = $message->getPayload();
        $sortBy = $payload->sortBy ?? null;
        $sortOrder = $payload->sortOrder ?? null;

        if (null !== $sortBy) {
            $formFata['sortBy'] = json_decode($sortBy);
        }

        if (null !== $sortOrder) {
            $formFata['sortOrder'] = json_decode($sortOrder);
        }

        $formFata['page'] = $payload->page ?? null;
        $formFata['limit'] = $payload->limit ?? null;

        return $formFata;
    }
}