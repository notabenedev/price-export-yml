## Description
- экспорт категорий и цен в YML файл импорта
- кэширование в течение суток
- задать параметры экспорта мождно в конфиге

## Config

php artisan vendor:publish --provider="Notabenedev\PriceExportYml\PriceExportYmlServiceProvider" --tag=config 

## Install
     -   php artisan make:price-export-yml
                            {--controllers: export controllers}
     -   fill config if you need
