<?php

namespace SearchSpringDev\FeedLogger\Api\Data;

interface ProductInfoResponseInterface
{
    /**
     * @return int[]
     */
    public function getProductIds(): array;

    /**
     * @param int[] $productIds
     * @return $this
     */
    public function setProductIds(array $productIds);

    /**
     * @return mixed[]
     */
    public function getProductInfo(): array;

    /**
     * @param mixed[] $productInfo
     * @return $this
     */
    public function setProductInfo(array $productInfo);

    /**
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * @param string|null $message
     * @return $this
     */
    public function setMessage(?string $message);
}
