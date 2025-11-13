<?php

namespace SearchSpringDev\FeedLogger\Plugin\Feed;

use Psr\Log\LoggerInterface;
use SearchSpring\Feed\Model\Feed\StorageInterface;

class StorageAddDataLoggerPlugin
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function afterAddData(
        StorageInterface $subject,
        $result,
        array $data,
        int $id
    ): void {
        try {
            $this->logger->info('[SearchSpring Feed] addData called', [
                'id' => $id,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            // avoid breaking feed generation due to logging error
        }
    }
}
