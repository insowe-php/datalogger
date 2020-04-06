# DataLogger

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]
-->

This package help log the data after every updated, and upload to cloud storage not database to reduce loading of the database.

## Installation

Via Composer

``` bash
$ composer require insowe/datalogger
```

Execute `php artisan vendor:publish`, choose `Provider: Insowe\DataLogger\DataLoggerServiceProvider` to publish database migration to `~/database/migrations/2020_01_22_023910_create_data_logs_table.php`.

You should put all the data types you need to enum column setting.
```
Schema::create('data_logs', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->enum('data_type', [

    // Put data types of the app here!

    ])->comment('資料類型');
```

For example:
```
Schema::create('data_logs', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->enum('data_type', [
        Model::getDataLogType(),
        Product::getDataLogType(),
        CleaningService::getDataLogType(),
    ])->comment('資料類型');
```

> This package use **queue**, make sure the queue environment is ready, or just let `QUEUE_CONNECTION=sync`.

## Usage

If an `Eloquent Model` will be logged, let it implement the interface `Insowe\DataLogger\Models\IData`.

```
class Model extends BaseModel implements IData
{
    public function getDataLogId()
    {
        return $this->id;
    }
    
    public static function getDataLogType()
    {
        return 'model';
    }
}
```


Create a `createLog` method in the controller after data has beed updated.
```
public function createOrUpdate(Request $request)
{
    if (intval($request->id) === 0) {
        $item = $this->create($request);
    }
    else {
        $item = $this->update($request);
    }
    $this->createLog($item->id, $request->user()->id);
}
```

In the method `createLog`，get the newest data and trigger the event `Updated`, the listener will add a log row to database and make a queue for upload log file to the cloud.
```
public function createLog($modelId, $userId)
{
    $item = Model::with('brand')
            ->with('type')
            ->with('age')
            ->with('minAge')
            ->with('usages')
            ->with('detail')
            ->with('accessories')
            ->where('id', $modelId)
            ->first();
    
    $item->setHidden([
        'product_quantity',
        'product_in_stock',
        'updated_at',
        'deleted_at',
    ]);
    
    event(new Updated($item, $userId));
}
```

>Notice: should hide the **machine-updated columns** like updated_at, deleted_at and other **statistics columns**.

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/insowe/datalogger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/insowe/datalogger.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/insowe/datalogger/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/insowe/datalogger
[link-downloads]: https://packagist.org/packages/insowe/datalogger
[link-travis]: https://travis-ci.org/insowe/datalogger
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/insowe
[link-contributors]: ../../contributors
