<?php

namespace SearchSpringDev\FeedLogger\Model\Api;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Psr\Log\LoggerInterface;
use SearchSpring\Feed\Api\Data\FeedSpecificationInterface;
use SearchSpring\Feed\Api\Data\TaskInterface;
use SearchSpring\Feed\Api\TaskRepositoryInterface;
use SearchSpring\Feed\Model\Feed\Collection\ProcessorPool;
use SearchSpring\Feed\Model\Feed\CollectionProviderInterface;
use SearchSpring\Feed\Model\Feed\DataProviderPool;
use SearchSpring\Feed\Model\Feed\SpecificationBuilderInterface;
use Magento\Framework\Serialize\SerializerInterface;
use SearchSpringDev\FeedLogger\Api\Data\ProductInfoResponseInterface;
use SearchSpringDev\FeedLogger\Api\ProductInfoInterface;
use SearchSpringDev\FeedLogger\Api\Data\ProductInfoResponseInterfaceFactory;

class ProductInfo implements ProductInfoInterface
{
    /**
     * @var CollectionProviderInterface
     */
    private $collectionProvider;
    /**
     * @var SpecificationBuilderInterface
     */
    private $specificationBuilder;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ProductInfoResponseInterfaceFactory
     */
    private $responseFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var ProcessorPool
     */
    private $afterLoadProcessorPool;
    /**
     * @var DataProviderPool
     */
    private $dataProviderPool;

    /**
     * @param CollectionProviderInterface $collectionProvider
     * @param SpecificationBuilderInterface $specificationBuilder
     * @param SerializerInterface $serializer
     * @param ProductInfoResponseInterfaceFactory $responseFactory
     * @param LoggerInterface $logger
     * @param TaskRepositoryInterface $taskRepository
     * @param SortOrderBuilder $sortOrderBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProcessorPool $afterLoadProcessorPool
     * @param DataProviderPool $dataProviderPool
     */
    public function __construct(
        CollectionProviderInterface         $collectionProvider,
        SpecificationBuilderInterface       $specificationBuilder,
        SerializerInterface                 $serializer,
        ProductInfoResponseInterfaceFactory $responseFactory,
        LoggerInterface                     $logger,
        TaskRepositoryInterface             $taskRepository,
        SortOrderBuilder                    $sortOrderBuilder,
        SearchCriteriaBuilder               $searchCriteriaBuilder,
        ProcessorPool                       $afterLoadProcessorPool,
        DataProviderPool                    $dataProviderPool
    )
    {

        $this->collectionProvider = $collectionProvider;
        $this->specificationBuilder = $specificationBuilder;
        $this->serializer = $serializer;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
        $this->taskRepository = $taskRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->afterLoadProcessorPool = $afterLoadProcessorPool;
        $this->dataProviderPool = $dataProviderPool;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return ProductInfoResponseInterface
     */
    public function getInfo(
        int $productId,
        int $storeId = 1
    ): ProductInfoResponseInterface
    {
        /** @var ProductInfoResponseInterface $response */
        $response = $this->responseFactory->create();

        try {
            $productIds = $this->getParentOrChildIds($productId);
            $response->setProductIds($productIds);

            $payload = $this->getTaskPayload();

            if (!$payload) {
                return $response
                    ->setProductInfo([])
                    ->setMessage(sprintf('No payload found for store ID %d', $storeId));
            }

            if (is_string($payload)) {
                $payload = $this->serializer->unserialize($payload);
            }
            if (!is_array($payload)) {
                return $response
                    ->setProductInfo([])
                    ->setMessage(sprintf('No payload found for store ID %d', $storeId));
            }

            $feedSpecification = $this->specificationBuilder->build($payload);

            $collection = $this->collectionProvider->getCollection($feedSpecification);
            $collection->addFieldToFilter('entity_id', ['in' => $productIds]);
            $collection->load();
            $this->processAfterLoad($collection, $feedSpecification);
            $itemsData = $this->getItemsData($collection->getItems(), $feedSpecification);

            $this->logger->info(
                'ProductInfoAPI started fetching product info',
                [
                    'method' => __METHOD__,
                    'product_ids' => $productIds,
                    'store_id' => $storeId
                ]
            );
            if (!$collection->getSize()) {
                return $response
                    ->setProductInfo([])
                    ->setMessage('No products found for the given product IDs');
            }

            $this->resetDataProvidersAfterFetchItems($feedSpecification);
            $collection->clear();
            $this->processAfterLoad($collection, $feedSpecification);

            $this->logger->debug(
                'ProductInfoAPI: ItemsData and Query',
                [
                    'method' => __METHOD__,
                    'query' => $collection->getSelect()->__toString(),
                    'items_data' => $itemsData
                ]
            );

            $response->setProductInfo($itemsData);
        } catch (\Exception $e) {
            $this->logger->error(
                'ProductInfoAPI: Failed to fetch product info',
                [
                    'method' => __METHOD__,
                    'product_ids' => $productIds,
                    'store_id' => $storeId,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            return $response
                ->setProductInfo([])
                ->setMessage($e->getMessage());
        }
        $this->logger->info(
            'ProductInfoAPI completed fetching product info',
            [
                'method' => __METHOD__,
                'product_ids' => $productIds,
                'store_id' => $storeId
            ]
        );
        return $response;
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    private function getParentOrChildIds(int $productId): array
    {
        $ids = [$productId];

        try {
            $childIds = [];
            $parentIds = [];
            $groupedParentIds = [];
            $groupedChildrenIds = [];

            $childIds = array_merge(...
                array_values($childIds
                    ?: []));
            $ids = array_merge(
                $ids,
                $parentIds
                    ?: [],
                $groupedParentIds
                    ?: [],
                $childIds
                    ?: [],
                $groupedChildrenIds
                    ?: []
            );
        } catch (\RuntimeException $e) {
            $this->logger->error(
                'ProductInfoAPI: Failed to resolve parent/child IDs',
                [
                    'method' => __METHOD__,
                    'productId' => $productId,
                    'message' => $e->getMessage(),
                ]
            );
        }

        return array_unique(array_filter($ids));
    }


    private function resetDataProvidersAfterFetchItems(FeedSpecificationInterface $feedSpecification): void
    {
        $dataProviders = $this->getDataProviders($feedSpecification);
        foreach ($dataProviders as $dataProvider) {
            $dataProvider->resetAfterFetchItems();
        }
    }

    private function getTaskPayload(): ?array
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField('entity_id')
            ->setDirection('DESC')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->setPageSize(1)
            ->setCurrentPage(1)
            ->setSortOrders([$sortOrder])
            ->create();

        $searchResults = $this->taskRepository->getList($searchCriteria);

        $items = $searchResults->getItems();
        if (!$items) {
            return null;
        }

        /** @var TaskInterface $task */
        $task = reset($items);

        return $task->getPayload();
    }

    private function processAfterLoad(Collection $collection, FeedSpecificationInterface $feedSpecification): void
    {
        foreach ($this->afterLoadProcessorPool->getAll() as $processor) {
            $processor->processAfterLoad($collection, $feedSpecification);
        }
    }

    /**
     * @param Collection $collection
     * @param FeedSpecificationInterface $feedSpecification
     */
    private function processAfterFetchItems(Collection $collection, FeedSpecificationInterface $feedSpecification): void
    {
        foreach ($this->afterLoadProcessorPool->getAll() as $processor) {
            $processor->processAfterFetchItems($collection, $feedSpecification);
        }
    }

    private function getItemsData(array $items, FeedSpecificationInterface $feedSpecification): array
    {
        if (empty($items)) {
            return [];
        }

        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'entity_id' => $item->getEntityId(),
                'product_model' => $item
            ];
        }


        $dataProviders = $this->getDataProviders($feedSpecification);
        foreach ($dataProviders as $dataProvider) {
            $data = $dataProvider->getData($data, $feedSpecification);
        }


        return $data;
    }

    private function getDataProviders(FeedSpecificationInterface $feedSpecification): array
    {
        $ignoredFields = $feedSpecification->getIgnoreFields();
        return $this->dataProviderPool->get($ignoredFields);
    }
}
