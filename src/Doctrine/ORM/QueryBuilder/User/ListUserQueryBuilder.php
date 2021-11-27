<?php

declare(strict_types=1);

namespace App\Doctrine\ORM\QueryBuilder\User;

use App\Repository\User\UserRepository;
use App\View\User\ListSearchRequestData;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

class ListUserQueryBuilder
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createQueryBuilder(ListSearchRequestData $data): QueryBuilder
    {
        $queryBuilder = $this
            ->userRepository
            ->createQueryBuilder('user');

        $this->applySearch($queryBuilder, $data);
        $this->applySort($queryBuilder, $data);

        return $queryBuilder;
    }

    private function applySearch(
        QueryBuilder $queryBuilder,
        ListSearchRequestData $data
    ): void {
        if (null === $data->search) {
            return;
        }

        $queryBuilder
            ->andWhere(
                $queryBuilder
                    ->expr()
                    ->orX(
                        $queryBuilder
                            ->expr()
                            ->like('user.firstName', ':search'),
                        $queryBuilder
                            ->expr()
                            ->like('user.lastName', ':search'),
                        $queryBuilder
                            ->expr()
                            ->like('user.username', ':search'),
                        $queryBuilder
                            ->expr()
                            ->like('user.email', ':search')
                    )
            )
            ->setParameter('search', '%'.$data->search.'%');
    }

    private function applySort(
        QueryBuilder $queryBuilder,
        ListSearchRequestData $data
    ): void {

        $i = 0;
        foreach ($data->sortBy as $sortBy) {
            switch ($sortBy) {
                case ListSearchRequestData::BY_NAME_SORT_FIELD:
                    $queryBuilder
                        ->addOrderBy('user.firstName', $data->sortOrder[$i])
                        ->addOrderBy('user.lastName', $data->sortOrder[$i]);

                    break;
                case ListSearchRequestData::BY_USER_NAME_SORT_FIELD:
                case ListSearchRequestData::BY_EMAIL_SORT_FIELD:
                case ListSearchRequestData::BY_LAST_LOGIN_SORT_FIELD:
                case ListSearchRequestData::BY_CREATED_AT_SORT_FIELD:
                    $queryBuilder->addOrderBy(
                        sprintf(
                            'user.%s',
                            $sortBy
                        ),
                        $data->sortOrder[$i]
                    );

                    break;
                default:
                    throw new RuntimeException(
                        sprintf(
                            'The given sort by field "%s" is unknown',
                            $data->sortBy
                        )
                    );
            }

            ++$i;
        }
    }
}