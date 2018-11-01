**Before using this module you must define interface links for import factories**

```php
'container' => [
        'definitions' => [
...
# import interfaces
                        \app\modules\opendata\import\roster\ImportListFactoryInterface::class => \app\modules\opendata\import\roster\ImportListFactory::class,
                        \app\modules\opendata\import\passport\ImportPassportFactoryInterface::class => \app\modules\opendata\import\passport\ImportPassportFactory::class,
                        \app\modules\opendata\import\data\ImportDataFactoryInterface::class => \app\modules\opendata\import\data\ImportDataFactory::class,
# export interfaces                        
                        \app\modules\opendata\export\roster\ExportListFactoryInterface::class => \app\modules\opendata\export\roster\ExportListFactory::class,
                        \app\modules\opendata\export\passport\ExportPassportFactoryInterface::class => \app\modules\opendata\export\passport\ExportPassportFactory::class,
                        \app\modules\opendata\export\data\ExportDataFactoryInterface::class => \app\modules\opendata\export\data\ExportDataFactory::class,
        ],
    ],
``` 