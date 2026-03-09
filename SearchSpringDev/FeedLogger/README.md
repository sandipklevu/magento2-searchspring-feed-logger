# SearchSpringDev_FeedLogger module

This module has two main functionalities:

## 1: Product detail info via rest api

Once this utility module is installed and configured, one can retrieve product information via the 
following REST API endpoint:

API endpoint `https://www.domain.com/rest/V1/searchspring/productinfo?productId=1010&storeId=1` it uses the same ACL 
as the `SearchSpring_Feed::general` so you can use the same token for testing purpose.

Replace `www.domain.com` with your Magento domain 
and `1010` with the actual product ID you want to retrieve information for, and `1` with the store ID.

Refer the response
e.g. for product ID `1000` which is not exist in the system, the response will be as follows:
```
{
    "product_ids": [
        1000
    ],
    "product_info": [],
    "message": "No products found for the given product IDs"
} 
```

Now, in this case, refer the log file which is generated in `<magento-root>/var/log/searchspringdev_logger.log` for the SQL query and product info related data.
Logs will show only for the given product ID, so you can easily find the relevant logs for the API request.

e.g. if product is existing and Magento is able to retrieve the product information, the response will be as follows:
```
{
    "product_ids": [
        1
    ],
    "product_info": ["{\"entity_id\":\"1\",\"product_model\":{},\"attribute_set_id\":\"4\",\"type_id\":\"simple\",\"sku\":\"CottonShirt-101\",\"has_options\":\"0\",\"required_options\":\"0\",\"created_at\":\"2025-03-04 06:44:40\",\"updated_at\":\"2026-01-16 09:56:28\",\"row_id\":\"1\",\"created_in\":\"1\",\"updated_in\":\"2147483647\",\"status\":\"Enabled\",\"cat_index_position\":\"-1\",\"is_salable\":null,\"price\":\"1123.030000\",\"tax_class_id\":\"Taxable Goods\",\"final_price\":456.37,\"minimal_price\":\"456.370000\",\"min_price\":\"456.370000\",\"max_price\":456.37,\"tier_price\":null,\"catalog_rule_price\":null,\"manufacturer\":false,\"color\":false,\"visibility\":\"Catalog, Search\",\"flavour\":false,\"boolfield\":\"No\",\"text_swatch\":false,\"special_price\":\"456.370000\",\"cost\":\"123.460000\",\"weight\":\"123.000000\",\"sell_price\":null,\"name\":\"CottonShirt-101 | Tier \",\"meta_title\":\"Cotton Shirt\",\"meta_description\":\"Cotton Shirt\",\"image\":\"\\/c\\/h\\/child-simple-purple.jpg\",\"small_image\":\"\\/c\\/h\\/child-simple-purple.jpg\",\"thumbnail\":\"\\/c\\/h\\/child-simple-purple.jpg\",\"page_layout\":\"Product -- Full Width\",\"options_container\":\"Block after Info Column\",\"url_key\":\"cotton-shirt\",\"msrp_display_actual_price_type\":\"Use config\",\"gift_message_available\":\"Use config\",\"gift_wrapping_available\":\"Use config\",\"is_returnable\":false,\"swatch_image\":\"\\/c\\/h\\/child-simple-purple.jpg\",\"special_from_date\":\"2025-03-05 01:47:17\",\"pre_purchased_date_and_time\":null,\"description\":\"<ol>\\r\\n<li>CottonShirt-101<\\/li>\\r\\n<li>CottonShirt-101<\\/li>\\r\\n<li>CottonShirt-101<\\/li>\\r\\n<\\/ol>\\r\\n<table style=\\\"border-collapse: collapse; width: 100%;\\\" border=\\\"1\\\"><colgroup><col style=\\\"width: 49.9385%;\\\"><col style=\\\"width: 49.9385%;\\\"><\\/colgroup>\\r\\n<tbody>\\r\\n<tr>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<\\/tr>\\r\\n<tr>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<\\/tr>\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"short_description\":\"<ol>\\r\\n<li>CottonShirt-101<\\/li>\\r\\n<li>CottonShirt-101<\\/li>\\r\\n<li>CottonShirt-101<\\/li>\\r\\n<\\/ol>\\r\\n<table style=\\\"border-collapse: collapse; width: 100%;\\\" border=\\\"1\\\"><colgroup><col style=\\\"width: 49.9385%;\\\"><col style=\\\"width: 49.9385%;\\\"><\\/colgroup>\\r\\n<tbody>\\r\\n<tr>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<\\/tr>\\r\\n<tr>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<td>CottonShirt-101<\\/td>\\r\\n<\\/tr>\\r\\n<\\/tbody>\\r\\n<\\/table>\",\"meta_keyword\":\"Cotton Shirt\",\"multi_select\":false,\"store_id\":1,\"regular_price\":1123.03,\"tier_pricing\":\"[]\",\"in_stock\":1,\"stock_qty\":0,\"is_stock_managed\":0,\"saleable\":false,\"url\":\"http:\\/\\/searchspring-ee247-p4.loc\\/cotton-shirt.html\",\"categories\":[\"Category\",\"Apparel V\êtements\",\"Books\",\"Action Figures \ش\خ\ص\ي\ا\ت \ا\ل\ع\م\ل\",\"Spiritual\"],\"category_ids\":[2,3,4,5,7],\"category_hierarchy\":[\"Category\",\"Category\",\"Category>Apparel V\êtements\",\"Category\",\"Category>Books\",\"Category\",\"Category>Action Figures \ش\خ\ص\ي\ا\ت \ا\ل\ع\م\ل\",\"Category\",\"Category>Books\",\"Category>Books>Fictional\",\"Category>Books>Fictional>Spiritual\"],\"menu_hierarchy\":[\"Category\",\"Category\",\"Category>Apparel V\êtements\",\"Category\",\"Category>Books\",\"Category\",\"Category>Action Figures \ش\خ\ص\ي\ا\ت \ا\ل\ع\م\ل\",\"Category\",\"Category>Books\",\"Category>Books>Fictional\",\"Category>Books>Fictional>Spiritual\"],\"url_hierarchy\":[\"Category[http:\\/\\/searchspring-ee247-p4.loc\\/catalog\\/category\\/view\\/s\\/category\\/id\\/2\\/]\",\"Category[http:\\/\\/searchspring-ee247-p4.loc\\/catalog\\/category\\/view\\/s\\/category\\/id\\/2\\/]\",\"Category>Apparel V\êtements[http:\\/\\/searchspring-ee247-p4.loc\\/apparel-vetements.html]\",\"Category[http:\\/\\/searchspring-ee247-p4.loc\\/catalog\\/category\\/view\\/s\\/category\\/id\\/2\\/]\",\"Category>Books[http:\\/\\/searchspring-ee247-p4.loc\\/books.html]\",\"Category[http:\\/\\/searchspring-ee247-p4.loc\\/catalog\\/category\\/view\\/s\\/category\\/id\\/2\\/]\",\"Category>Action Figures \ش\خ\ص\ي\ا\ت \ا\ل\ع\م\ل[http:\\/\\/searchspring-ee247-p4.loc\\/action-figures.html]\",\"Category[http:\\/\\/searchspring-ee247-p4.loc\\/catalog\\/category\\/view\\/s\\/category\\/id\\/2\\/]\",\"Category>Books[http:\\/\\/searchspring-ee247-p4.loc\\/books.html]\",\"Category>Books>Fictional[http:\\/\\/searchspring-ee247-p4.loc\\/books\\/fictional.html]\",\"Category>Books>Fictional>Spiritual[http:\\/\\/searchspring-ee247-p4.loc\\/books\\/fictional\\/spritiual.html]\"],\"cached_thumbnail\":\"http:\\/\\/searchspring-ee247-p4.loc\\/static\\/version1772975533\\/webapi_rest\\/_view\\/en_US\\/Magento_Catalog\\/images\\/product\\/placeholder\\/.jpg\",\"media_gallery_json\":\"[]\"}"
    ]
}
```

## 2: Full debugger if products are not getting generated to feed to SearchSpring

### If you want to test the file at Magento level then you can enable mocking by modifying the `env.php` is as follows:
Step: 1 changes to the `env.php` , only for testing/debug purpose. You can remove post verification.
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
    ...
    ...
    ...
    
```
Step: 2 Run the following MySQL command mark last task as pending to execute the CRON for testing purpose:
```
UPDATE `searchspring_task` SET `status` = 'pending' ORDER BY `entity_id` DESC LIMIT 1;
```

Step: 3 Execute the Magento CRON with group parameter  and wait for the completion of cron execution:
```
bin/magento cron:run --group="searchspring_task"
```

Step: 4 check the task status in `searchspring_task` table, it should be `success` after successful execution of the CRON.

``` 
SELECT * FROM `searchspring_task` ORDER BY `entity_id` DESC LIMIT 1;
```

Step: 5 Feed will be generated in the `<magento-root>/var/searchspring/` directory.

Step: 6 Please share the below two files in compressed format which will help to analyze the issue:
 - There will be two log files generated in `<magento-root>/var/log/` directory:
   1. `searchspring_feed.log` - Main module log file
   2. `searchspringdev_logger.log` - SQL queries and product info related data for all the data. 
   This file may have large data depending on catalog size.

You can refer the SQL queries and product info related data in `searchspringdev_logger.log` file. 
This file may have large data, so you can compress it before sharing with the SearchSpring team for further analysis. 

### Refer the log file generated in `<magento-root>/var/log/searchspringdev_logger.log`.



