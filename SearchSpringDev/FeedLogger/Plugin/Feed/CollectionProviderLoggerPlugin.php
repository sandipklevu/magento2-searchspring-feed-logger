<?php

namespace SearchSpringDev\FeedLogger\Plugin\Feed;

use Magento\Catalog\Model\ResourceModel\Product\Collection as MagentoCollection;
use Psr\Log\LoggerInterface;
use SearchSpring\Feed\Model\Feed\CollectionProviderInterface;
use SearchSpring\Feed\Api\Data\FeedSpecificationInterface;

class CollectionProviderLoggerPlugin
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function aroundGetCollection(
        CollectionProviderInterface $subject,
        callable $proceed,
        FeedSpecificationInterface $specification
    ): MagentoCollection {
        $start = microtime(true);

        /** @var MagentoCollection $result */
        $result = $proceed($specification);

        try {
            $this->logger->info('[SearchSpring Feed] Final collection after modifiers',
                [
                    'store_id' => $specification->getStoreCode() ?? null,
                    'collection_class' => get_class($result),
                    'collection_size' => $result->getSize(),
                    'execution_time' => round(microtime(true) - $start, 4) . 's',
                    'sql' => $result->getSelect()->__toString(),
                    'caller' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]
                ]
            );
        } catch (\Throwable $e) {
            $this->logger->error($e->getTraceAsString());
        }

        return $result;
    }
}
