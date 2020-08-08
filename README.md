# Laravel Settings

This package allows you to save the configuration in a more persistent way. Use the database to save your settings, you can save values in json format. You can also override the Laravel configuration.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require wimil/settings
```

### 2. Register (for Laravel < 5.5)

Register the service provider in `config/app.php`

```php
Wimil\Settings\Provider::class,
```

Add alias if you want to use the facade.

```php
'Settings' => Wimil\Settings\Facade::class,
```

### 3. Publish

Publish config file.

```bash
php artisan vendor:publish --provider="Wimil\Settings\Provider"
```


### 4. Configure

You can change the options of your app from `config/settings.php` file

## Usage

You can either use the helper method like `settings('foo')` or the facade `Settings::get('foo')`

### Facade

```php
Settings::get('foo');
Settings::set('foo', 'bar');
$settings = Settings::all();
```

### Helper

```php
settings('foo');
settings('foo', 'bar');
$settings = settings();
```

### Using your model

```php
use Wimil\Settings\Model\Setting as BaseSetting;
class Setting extends BaseSetting {

}
```
