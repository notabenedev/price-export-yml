<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\PriceExportYml\Site",
    "middleware" => ["web"],
    "as" => "price.yml",
    "prefix" => config("price-export-yml.siteUrlName"),
], function () {
    Route::get("", "PriceExportYmlController@index")->name("index");
});