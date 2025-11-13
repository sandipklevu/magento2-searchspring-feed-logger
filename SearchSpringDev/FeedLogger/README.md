# SearchSpringDev_FeedLogger module

### If you want to test the file at Magento level then you can enable mocking by modifying the `env.php` is as follows:
Step: 1 changes to the `env.php`
```
    'searchspring' => [
        'feed' => [
            'debug' => true,
            'product' => [
                'api' => [
                    'mock' => true,
                ],
                'delete' => [
                    'file' => false,
                ],
            ],
        ],
    ],
    ........
```
Step: 2 Add new entry to the `searchspring_task` table with the help of below-mentioned payload

### Payload for feed specification

```
{
    "type": "feed_generation",
    "payload": {
        "thumbHeight": 250,
        "multiValuedSeparator": ">",
        "childFields": [],
        "includeChildPrices": true,
        "includeJSONConfig": true,
        "ignoreFields": [],
        "store": "default",
        "thumbWidth": 500,
        "keepAspectRatio": false,
        "hierarchySeparator": ">",
        "includeTierPricing": true,
        "includeUrlHierarchy": true,
        "imageTypes": [],
        "includeOutOfStock": true,
        "includeMenuCategories": true,
        "includeMediaGallery": false,
        "preSignedUrl": "Storage path "
    }
}
```
Step: 3 Execute the Magento CRON with group parameter 
```
bin/magento cron:run --group="searchspring_task"
```

### view the logs generated in `<magento-root>/var/log/searchspringdev_logger.log`
