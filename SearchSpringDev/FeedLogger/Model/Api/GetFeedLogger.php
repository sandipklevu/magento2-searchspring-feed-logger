<?php

namespace SearchSpringDev\FeedLogger\Model\Api;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;
use SearchSpringDev\FeedLogger\Api\GetFeedLoggerInterface;

class GetFeedLogger implements GetFeedLoggerInterface
{
    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var File
     */
    protected $fileDriver;
    /**
     * @var LoggerInterface
     */
    protected  $logger;

    public const LOG = [
        'search_spring_dev' => 'searchspringdev_logger.log',
        'getFileInfo' => 'File SearchSpring Feed Debugger log will be retrieved from the path',
        'getFileInfoError' => 'File SearchSpring Feed Debugger not present at the location:',
        'deleteLogFileInfo' => 'File SearchSpring Feed Debugger log will be removed from the path',
        'deleteLogFileRemove' => 'File SearchSpring Feed Debugger log removed successfully from the path',
        'deleteLogFileError' => 'File SearchSpring Feed Debugger log not present at the location'
    ];

    public function __construct( DirectoryList $directoryList, File $fileDriver, LoggerInterface $logger)
    {
        $this->directoryList = $directoryList;
        $this->fileDriver = $fileDriver;
        $this->logger = $logger;
    }
    /**
     * @inheritDoc
     */
    public function get(bool $compressOutput = false): string
    {
        $result = '';

        $logPath = $this->directoryList->getPath(DirectoryList::LOG);
        $logFile = $logPath . '/'. self::LOG['search_spring_dev'];

        if ($this->fileDriver->isExists($logFile)) {
            $this->logger->info(self::LOG['getFileInfo']. $logPath);
            $result = $this->fileDriver->fileGetContents($logFile);

            if (strlen($result) > 0 and $compressOutput){
                $result = rtrim(strtr(base64_encode(gzdeflate($result, 9)), '+/', '-_'), '=');
            }
        }
        $this->logger->error(self::LOG['getFileInfoError'] . $logPath);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        $logPath = $this->directoryList->getPath(DirectoryList::LOG);
        $logFile = $logPath . '/'. self::LOG['search_spring_dev'];

        if ($this->fileDriver->isExists($logFile)) {
            $this->logger->info(self::LOG['deleteLogFileInfo']. $logPath);
            unlink($logFile);
            $this->logger->info(self::LOG['deleteLogFileRemove'] . $logPath . '/'. self::LOG['search_spring_dev']);
        }
        $this->logger->error(self::LOG['deleteLogFileError'] . $logFile);

        return true;
    }
}
