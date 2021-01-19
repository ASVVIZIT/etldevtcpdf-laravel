# Laravel 8.0 TCPDF
[![Latest Stable Version](https://poser.pugx.org/asvvizit/etldevtcpdf-laravel/1.0.0)](https://packagist.org/packages/asvvizit/etldevtcpdf-laravel/v/1.0.3) [![Total Downloads](https://poser.pugx.org/asvvizit/etldevtcpdf-laravel/downloads)](https://packagist.org/packages/asvvizit/etldevtcpdf-laravel) [![Latest Unstable Version](https://poser.pugx.org/asvvizit/etldevtcpdf-laravel/v/unstable)](https://packagist.org/packages/asvvizit/etldevtcpdf-laravel) [![License](https://poser.pugx.org/asvvizit/etldevtcpdf-laravel/license)](https://packagist.org/packages/asvvizit/etldevtcpdf-laravel)

A simple [Laravel](http://www.laravel.com) service provider with some basic configuration for including the [TCPDF library](http://www.tcpdf.org/)

#### TCPDF is not really supported in PHP 7 but there's a plan for supporting it, check [this](https://github.com/tecnickcom/tc-lib-pdf) out.

## Installation

The Laravel TCPDF service provider can be installed via [composer](http://getcomposer.org) by requiring the `asvvizit/etldevtcpdf-laravel` package in your project's `composer.json`. (The installation may take a while, because the package requires TCPDF. Sadly its .git folder is very heavy)

Laravel 8.0+ will use the auto-discovery function.

```json
{
    "require": {
        "asvvizit/etldevtcpdf-laravel": "dev-master"
    }
}
```

If you don't use auto-discovery you will need to include the service provider / facade in `config/app.php`.


```php
'providers' => [
    //...
    asvvizit\ETLDEVTCPDF\ServiceProvider::class,
]

//...

'aliases' => [
    //...
    'PDF' => asvvizit\ETLDEVTCPDF\Facades\TCPDF::class
]
```

(Please note: TCPDF cannot be used as an alias)

for lumen you should add the following lines:

```php
$app->register(asvvizit\ETLDEVTCPDF\ServiceProvider::class);
class_alias(asvvizit\ETLDEVTCPDF\Facades\TCPDF::class, 'PDF');
```

That's it! You're good to go.

Here is a little example:

```php
use PDF; // at the top of the file

  PDF::SetTitle('Hello World');
  PDF::AddPage();
  PDF::Write(0, 'Hello World');
  PDF::Output('hello_world.pdf');
```

another example for generating multiple PDF's

```php
use PDF; // at the top of the file

  for ($i = 0; $i < 5; $i++) {
    PDF::SetTitle('Hello World'.$i);
    PDF::AddPage();
    PDF::Write(0, 'Hello World'.$i);
    PDF::Output(public_path('hello_world' . $i . '.pdf'), 'F');
    PDF::reset();
  }
```

For a list of all available function take a look at the [TCPDF Documentation](http://www.tcpdf.org/doc/code/classTCPDF.html)

## Configuration 

Laravel-TCPDF comes with some basic configuration.
If you want to override the defaults, you can publish the config, like so:

    php artisan vendor:publish --provider="asvvizit\ETLDEVTCPDF\ServiceProvider"

Now access `config/tcpdf.php` to customize.

 * use_original_header is to used the original `Header()` from TCPDF.
    * Please note that `PDF::setHeaderCallback(function($pdf){})` overrides this settings.
 * use_original_footer is to used the original `Footer()` from TCPDF.
    * Please note that `PDF::setFooterCallback(function($pdf){})` overrides this settings.
 * use_fpdi is so that our internal helper will extend `TcpdfFpdi` instead of `TCPDF`.
    * Please note fpdi is not a dependency in my project so you will have to follow their install instructions [here](https://github.com/Setasign/FPDI)  

## Header/Footer helpers

I've got a pull-request asking for this so I've added the feature

now you can use `PDF::setHeaderCallback(function($pdf){})` or `PDF::setFooterCallback(function($pdf){})`
