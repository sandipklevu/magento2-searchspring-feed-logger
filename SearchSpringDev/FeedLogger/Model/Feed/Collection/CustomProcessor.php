<?php

declare(strict_types=1);

namespace SearchSpringDev\FeedLogger\Model\Feed\Collection;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Psr\Log\LoggerInterface;
use SearchSpring\Feed\Api\Data\FeedSpecificationInterface;
use SearchSpring\Feed\Model\Feed\Collection\ProcessCollectionInterface;

class CustomProcessor implements ProcessCollectionInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param Collection $collection
     * @param FeedSpecificationInterface $feedSpecification
     *
     * @return Collection
     */
    public function processAfterLoad(
        Collection $collection,
        FeedSpecificationInterface $feedSpecification
    ): Collection {

        $query = $collection->getSelect()->__toString();
        $this->logger->info(
            'processAfterLoad',
            [
                'method' => __METHOD__,
                'query' => $query,
                'timestamp' => date('YmdH:i:s'),
                'includeOutOfStock' => $feedSpecification->getIncludeOutOfStock(),
                'preSignedUrl' => $feedSpecification->getPreSignedUrl(),
            ]
        );

        return $collection;
    }

    /**
     * @param Collection $collection
     * @param FeedSpecificationInterface $feedSpecification
     *
     * @return Collection
     */
    public function processAfterFetchItems(
        Collection $collection,
        FeedSpecificationInterface $feedSpecification
    ): Collection {

        $query = $collection->getSelect()->__toString();
        $this->logger->info(
            'processAfterFetchItems',
            [
                'method' => __METHOD__,
                'query' => $query,
                'timestamp' => date('YmdH:i:s'),
                'includeOutOfStock' => $feedSpecification->getIncludeOutOfStock(),
                'preSignedUrl' => $feedSpecification->getPreSignedUrl(),
            ]
        );

        return $collection;
    }
}
