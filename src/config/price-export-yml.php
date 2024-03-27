<?php
return [
    "siteRoutes" => true,
    "siteUrlName" => "price.yml",
    // false | true - CDATA or strip tags
    "stripTags" => false,
    // string | null - field to filter data
    "groupsFilterField" => "published_at",
    // php artisan cache:clear after change cacheLifetime
    "cacheLifetime" => 86400,
    "cacheKey" => "price-export-yml",
];