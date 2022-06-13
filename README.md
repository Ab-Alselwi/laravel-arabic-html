#  onvert laravel view to arabic html using ArPHP , to support dompdf with arabic lettrs


This package makes it easy to convert view blade to  pdf  using [laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) and [Ar-PHP](https://github.com/khaled-alshamaa/ar-php). 

## Contents

- [Installation](#installation)
	- [Package Installation](#package-installation)
- [Usage](#usage)

## Requirements

 * PHP version 7.1 or higher
 * DOM extension
 * MBString extension
 * php-font-lib
 * php-svg-lib
 
Note that some required dependencies may have further dependencies 
(notably php-svg-lib requires sabberworm/php-css-parser).

### Recommendations

 * OPcache (OPcache, XCache, APC, etc.): improves performance
 * GD (for image processing)
 * IMagick or GMagick extension: improves image processing performance

 ## Installation

### Package Installation

Install the package using composer:
```bash
composer require ab-alselwi/laravel-arabic-html
```

### Configuration
The defaults configuration settings are set in `config/dompdf.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:
```shell
    php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```
also you need to setup font_dir in config\dompdf 

for more detials about dompdf settings : 
- https://github.com/barryvdh/laravel-dompdf 
- https://github.com/dompdf/dompdf

## Usage

we use View macro so you can use view('your_blade_view_name')->toArabicHTML()

Example:

```php
 		use Barryvdh\DomPDF\Facade\Pdf;

 		$html = view('invoice')->toArabicHTML();

        $pdf = PDF::loadHTML($html)->output();
                
        $headers = array(
            "Content-type" => "application/pdf",
        );

        // Create a stream response as a file download
        return response()->streamDownload(
            fn () => print($pdf), // add the content to the stream
            "invoice.pdf", // the name of the file/stream
            $headers
        );
  ```