<?php

namespace App\Form\Handler;

use App\Entity\Order;

use App\Utils\Manager\OrderManager;
use Spiriit\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

readonly class OrderFormHandler{

    public function __construct(
        private OrderManager $orderManager,
        private PaginatorInterface $paginator,
        private FilterBuilderUpdater $filterBuilderUpdater)
    {

    }

    public function processOrderFiltersForm(Request $request, FormInterface $filterForm){
        $queryBuilder = $this->orderManager->getRepository()
            ->createQueryBuilder('o')
            ->leftJoin('o.owner','u')
            ->where('o.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false);

        if($filterForm->isSubmitted() && $filterForm->isValid()){
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }

        return $this->paginator->paginate($queryBuilder,$request->query->getInt('page',1));
    }

    public function processEditForm(Order $order){
        $this->orderManager->recalculateOrderTotalPrice($order);
        $this->orderManager->save($order);
        return $order;
    }

}
