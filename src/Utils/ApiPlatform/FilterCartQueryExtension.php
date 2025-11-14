<?php

namespace App\Utils\ApiPlatform;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Cart;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class FilterCartQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->andWhere($queryBuilder, $resourceClass);
    }


    private function andWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if(Cart::class !== $resourceClass) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $request = Request::createFromGlobals();
        $phpSessid = $request->cookies->get('PHPSESSID');

        $queryBuilder->andWhere(
            sprintf("%s.sessionId ='%s'", $rootAlias,$phpSessid)
        );
    }
}
