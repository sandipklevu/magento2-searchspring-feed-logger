<?php

namespace SearchSpringDev\FeedLogger\Api;

use Magento\Framework\Exception\LocalizedException;

interface GetFeedLoggerInterface
{
    /**
     * @param bool $compressOutput
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function get(bool $compressOutput = false) : string;

    /**
     * @return bool
     *
     * @throws LocalizedException
     */
    public function clear() : bool;
}
