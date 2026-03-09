<?php

declare(strict_types=1);

namespace SearchSpringDev\FeedLogger\Model\Feed\DataProvider;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Psr\Log\LoggerInterface;
use SearchSpring\Feed\Api\Data\FeedSpecificationInterface;
use SearchSpring\Feed\Model\Feed\DataProviderInterface;

class CustomProvider implements DataProviderInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param LoggerInterface $logger
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        LoggerInterface $logger,
        MetadataPool    $metadataPool
    )
    {
        $this->logger = $logger;
        $this->metadataPool = $metadataPool;
    }

    /**
     * @param array $products
     * @param FeedSpecificationInterface $feedSpecification
     *
     * @return array
     * @throws \Exception
     */
    public function getData(array $products, FeedSpecificationInterface $feedSpecification): array
    {
        $this->logger->info(
            'START_PRODUCT_DATA_BEFORE_LOOP',
            [
                'method' => __METHOD__,
                'timestamp' => date('YmdH:i:s'),
                'includeOutOfStock' => $feedSpecification->getIncludeOutOfStock(),
                'preSignedUrl' => $feedSpecification->getPreSignedUrl(),
            ]
        );

        foreach ($products as $productKeyIndex => &$product) {
            $productModel = $product['product_model'] ?? null;
            $this->logger->info('ProductsDataStart', ['product' => $product]);
            if (!$productModel) {
                $this->logger->info('ProductIdWhenModelNotAvailable: ' . $productKeyIndex, ['method' => __METHOD__,]);
                $this->logger->info('ProductsDataModelNotFound', ['product' => $product, 'method' => __METHOD__,]);
                continue;
            }
            $id = $productModel->getData($this->getLinkField());
            $this->logger->info('ProductIdAvailable: ' . $id,
                [
                    'method' => __METHOD__,
                ]
            );
            $this->logger->info('ProductsDataEnd', ['product' => $product]);
        }
        $this->logger->info(
            'END_PRODUCT_DATA_AFTER_LOOP',
            [
                'method' => __METHOD__,
                'timestamp' => date('YmdH:i:s'),
                'includeOutOfStock' => $feedSpecification->getIncludeOutOfStock(),
                'preSignedUrl' => $feedSpecification->getPreSignedUrl(),
            ]
        );

        return $products;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        //do nothing
    }

    /**
     * @return void
     */
    public function resetAfterFetchItems(): void
    {
        //do nothing
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getLinkField(): string
    {
        return $this->metadataPool->getMetadata(ProductInterface::class)->getLinkField();
    }
}
