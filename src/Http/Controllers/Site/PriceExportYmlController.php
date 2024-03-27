<?php

namespace Notabenedev\PriceExportYml\Http\Controllers\Site;

use App\Group;
use App\Http\Controllers\Controller;
use App\Price;
use Illuminate\Support\Facades\Cache;


class PriceExportYmlController extends Controller
{
    /**
     * Get yml
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key =  config('price-export-yml.cacheKey', "price-export-yml");
        $yml = Cache::remember( $key, config('price-export-yml.cacheLifetime', 0), function () {
            $file = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' ?><yml_catalog></yml_catalog>");
            $file->addAttribute('date', now());
            $shop = $file->addChild("shop");
            $shop->addChild("name",  env("APP_NAME") );
            $shop->addChild("company",  env("APP_NAME") );
            $shop->addChild("url",  env("APP_URL") );
            $currencies = $shop->addChild("currencies");
            $currency = $currencies->addChild("currency");
            $currency->addAttribute("id","RUB");
            $currency->addAttribute("rate", "1");

            $categoriesYml = $shop->addChild("categories");
            $groups = Group::query()->select("id","parent_id", "title");
            $groupsFilter = config("price-export-yml.groupsFilterField",null);
            if (! empty($groupsFilter))
                $groups->whereNotNull($groupsFilter);
            $groups->chunk(100, function ($groups) use ($categoriesYml) {
                foreach ($groups as $group) {
                    $categoryYml = $categoriesYml->addChild("category", $group->title);
                    $categoryYml->addAttribute("id", $group->id);
                    if ($group->parent_id)
                        $categoryYml->addAttribute("parentId", $group->parent_id);
                }
            });

            $offersYml = $shop->addChild("offers");
            $prices = Price::query();
            $prices->chunk(100, function ($prices) use ($offersYml) {
                foreach ($prices as $price) {
                    // first image
                    $imageRoute =  class_exists(\App\ImageFilter::class) ? 'image-filter' : 'imagecache';
                    $imageSrc = $price->image ? route($imageRoute, ['template' => 'original', 'filename' => $price->image->file_name]) : null;
                    // description
                    $description =
                        (config("price-export-yml.stripTags", true) ?
                            htmlspecialchars(strip_tags($price->description),ENT_XML1) :
                            (! empty($price->description) ? '<![CDATA[ '.htmlspecialchars($price->description, ENT_XML1).' ]]>' : '' )
                        );

                    // generate xml
                    $offerYml = $offersYml->addChild("offer");
                    $offerYml->addAttribute("id", $price->id);
                    $offerYml->addChild("name", htmlspecialchars($price->title));
                    $offerYml->addChild("url", route("site.groups.show", ["group" => $price->group->slug]).'#'.$price->slug);
                    try{
                        $priceYml = floatval($price->price);
                    } catch (\Exception $e){
                        $priceYml = 0;
                    }
                    $offerYml->addChild("price", $priceYml);
                    $offerYml->addChild("currencyId", "RUR");
                    $offerYml->addChild("categoryId", $price->group_id);
                    $offerYml->addChild("description", $description);
                    if ($imageSrc)
                        $offerYml->addChild("picture", "$imageSrc");
                }
            });
            return $file->asXML();
         });
        return response($yml, 200)->header('Content-Type', 'text/xml') ;
    }
}
