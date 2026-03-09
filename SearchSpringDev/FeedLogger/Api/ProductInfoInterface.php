<?php

namespace SearchSpringDev\FeedLogger\Api;

use SearchSpringDev\FeedLogger\Api\Data\ProductInfoResponseInterface;

interface ProductInfoInterface
{
    /**
     * @param int $productId
     * @param int $storeId
     * @return ProductInfoResponseInterface
     */
    public function getInfo(
        int $productId,
        int $storeId = 1
    ): ProductInfoResponseInterface;
}
