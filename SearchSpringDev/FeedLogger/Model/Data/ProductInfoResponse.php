<?php

namespace SearchSpringDev\FeedLogger\Model\Data;

use SearchSpringDev\FeedLogger\Api\Data\ProductInfoResponseInterface;
use Magento\Framework\DataObject;

class ProductInfoResponse extends DataObject implements ProductInfoResponseInterface
{
    private const PRODUCT_IDS = 'product_ids';
    private const PRODUCT_INFO = 'product_info';
    private const MESSAGE = 'message';

    public function getProductIds(): array
    {
        return (array)$this->getData(self::PRODUCT_IDS);
    }

    public function setProductIds(array $productIds)
    {
        return $this->setData(self::PRODUCT_IDS, $productIds);
    }

    public function getProductInfo(): array
    {
        return (array)$this->getData(self::PRODUCT_INFO);
    }

    public function setProductInfo(array $productInfo)
    {
        return $this->setData(self::PRODUCT_INFO, $productInfo);
    }

    public function getMessage(): ?string
    {
        return $this->getData(self::MESSAGE);
    }

    public function setMessage(?string $message)
    {
        return $this->setData(self::MESSAGE, $message);
    }
}
