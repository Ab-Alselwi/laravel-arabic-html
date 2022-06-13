#  Convert laravel view to arabic html using ArPHP , to support dompdf with arabic letters

To handle  Arabic text issue in a more clean way with no need to hack dompdf script at all. 

This package makes it easy to convert view blade to  pdf  using [laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) and [Ar-PHP](https://github.com/khaled-alshamaa/ar-php). 

قمنا بكتابة هذا الباكج  لدعم اللغة العربية  في مكتبة  [laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) من خلال   استخدام  [Ar-PHP](https://github.com/khaled-alshamaa/ar-php)

يوفر هذا الباكج حالياً  دالة واحدة فقط وهي   toArabicHTML() بالاضافة إلى انه يمكنك استخدام  المكتبات  الأصلية في تحويل أي محتوى إلى ملف  pdf .

## Contents

- [Installation](#installation)
	- [Package Installation](#package-installation)
- [Usage](#usage)

## Requirements

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

فقط هنا تحتاج  للقيام  باعداد  المكتباات المرتبطة  .

The defaults configuration settings are set in `config/dompdf.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:

```shell
    php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

ينصح  بتحديد مجلد الخطوط  في المسار   storage\fonts  .

You need to setup font_dir in config\dompdf by copy fonts folder to storage\fonts or any folder with read and write permissions. 
You should be carful when write css , and font . you can't use font-weight:number like font-weight:600 ; just use font-weight:bold.

بعض  التنسيقات قد لا يتم دعمها في  dompdf مثل  font-weight مع القيم الرقمية  بالاضافة للعديد من التنسيقات  التي  تعتمد على flex box , ولعرض رسالة خطأ في حال وجود أي  تنسيقات  غير مدعومة يمكنك  تغيير  اعدادات عرض الخطاء  كما يلي : 

'show_warnings' => true ,   // Throw an Exception on warnings from dompdf

for more detials about dompdf settings : 
- https://github.com/barryvdh/laravel-dompdf 
- https://github.com/dompdf/dompdf

## Usage

To handle  Arabic text issue in a more clean way with no need to hack dompdf script at all.We use View macro, so use view('your_blade_view_name')->toArabicHTML() to support any content in Arabic .  https://github.com/dompdf/dompdf/issues/712#issuecomment-650592099

Example:

```php

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

