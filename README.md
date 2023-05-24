# Convert laravel view to arabic html (pdf) using ArPHP and dompdf with arabic text

To handle Arabic text issue in a more clean way with no need to hack dompdf script at all.

This package makes it easy to convert view blade to pdf using [laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) and [Ar-PHP](https://github.com/khaled-alshamaa/ar-php).

قمنا بكتابة هذا الباكج لدعم اللغة العربية في مكتبة [laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) من خلال استخدام [Ar-PHP](https://github.com/khaled-alshamaa/ar-php)
سيقوم الباكج بتنزيل المكتبات المطلوبة في حال كانت غير موجودة دون الحاجة لتنزيلها كلاً على حده.

يوفر هذا الباكج حالياً دالة واحدة فقط وهي toArabicHTML() بالاضافة إلى انه يمكنك استخدام المكتبات الأصلية في تحويل أي محتوى إلى ملف pdf .

## Contents

- [Installation](#installation)
  - [Package Installation](#package-installation)
- [Usage](#usage)

## Requirements

- DOM extension
- MBString extension
- php-font-lib
- php-svg-lib

Note that some required dependencies may have further dependencies
(notably php-svg-lib requires sabberworm/php-css-parser).

### Recommendations

- OPcache (OPcache, XCache, APC, etc.): improves performance
- GD (for image processing)
- IMagick or GMagick extension: improves image processing performance

## Installation

### Package Installation

Install the package using composer:

```bash
composer require ab-alselwi/laravel-arabic-html
```

### Configuration

فقط هنا تحتاج للقيام باعداد المكتباات المرتبطة .

The defaults configuration settings are set in `config/dompdf.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:

```shell
    php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

ينصح بتحديد مجلد الخطوط في المسار storage\fonts .

You need to setup font_dir in config\dompdf by copy fonts folder to storage\fonts or any folder with read and write permissions.
You should be carful when write css , and font . you can't use font-weight:number like font-weight:600 ; just use font-weight:bold.

بعض التنسيقات قد لا يتم دعمها في dompdf مثل font-weight مع القيم الرقمية بالاضافة للعديد من التنسيقات التي تعتمد على flex box , ولعرض رسالة خطأ في حال وجود أي تنسيقات غير مدعومة يمكنك تغيير اعدادات عرض الخطاء كما يلي :

'show_warnings' => true , // Throw an Exception on warnings from dompdf

for more detials about dompdf settings :

- https://github.com/barryvdh/laravel-dompdf
- https://github.com/dompdf/dompdf

## Usage

To handle Arabic text issue in a more clean way with no need to hack dompdf script at all.We use View macro, so use view('your_blade_view_name')->toArabicHTML() to support any content in Arabic . https://github.com/dompdf/dompdf/issues/712#issuecomment-650592099

### function parameters :

- $line_length :int ,optional, default 100. max length in line.
- $hindo :bool, optional, default false. convert numbers in text to hindo.
- $forcertl :bool, optional, default false. force right to left document direction.

تسختدم الدالة دالة utf8Glyphs الموجودة في مكتبة Ar-PHP لذا يمكنك أن تقوم بتخصيص بارامترات هذه الدالة حيث :

- 1 - $line_length :
  اختياري
  رقم - في حال وجود نصوص طويلة قد يتطلب منك تحديد كم حرفاً في السطر الواحد قمنا بتعديل القيمة الافتراضية من 50 الى 100 حرف .
- 2- $hindo :
  اختياري
  قيمة منطقية bool في حال رغبت في تحويل الأرقام الموجودة في المستند إلى أرقام هندية قمنا بتعديل القيمة الافتراضية إلى false حيث ستبقى الأرقام على حالها بألارقام العربية والتي تكتب في اللغة الانجليزية حالياُ
- 3- $forcertl :
  اختياري
  قيمة منطقية bool تتوافق القيمة الافتراضية مع الدالة الأصلية false حيث تشير إلى فرض تحويل اتجاه المستند من اليمين إلى اليسار

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

هذا هو شكل ال view
invoice.blade.php

```php
<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Arabic Invoice </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            font-family: DejaVu Sans !important;
        }

        body {
            font-size: 16px;
            font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
            padding: 10px;
            margin: 10px;

            color: #777;
        }


        body {
            color: #777;
            text-align: right;
        }

        body h1 {

            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {

            margin-top: 10px;
            margin-bottom: 20px;
            color: #555;
        }

        body a {
            color: #06f;
        }

        @page {
            size: a4;
            margin: 0;
            padding: 0;
        }

        .invoice-box table {
            direction: ltr;
            width: 100%;
            text-align: right;
            border: 1px solid;
            font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
        }


        .row {
            display: block;
            padding-left: 24;
            padding-right: 24;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        .column {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }
    </style>
</head>

<body>

    <div class="row">
        <div class="column">
            <p class="text-darky">بيانات باللغة العربية </p>
        </div>
    </div>
    <h1>ان هذا عبارة عن قالب عادي </h1>
    <h3>ومن هنا قمنا بعرض قالب اعتيادي فقط كمثال توضيحي .</h3>

    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">

                            </td>

                            <td>
                                فاتورة #: 123<br />
                                الانشاء : يناير 1, 2015<br />
                                تاريخ : فبراير 1, 2015
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>


            <tr class="heading">
                <td>العنصر </td>

                <td>السعر </td>
            </tr>

            <tr class="item">
                <td>تصميم موقع </td>

                <td>$300.00</td>
            </tr>

            <tr class="item">
                <td>استضافة (3 أشهر )</td>

                <td>$75.00</td>
            </tr>

            <tr class="item ">
                <td>نطاق (1 عام )</td>

                <td>$10.00</td>
            </tr>

            <tr class="total last">
                <td>الإجمالي : </td>

                <td>$385.00 </td>
            </tr>
        </table>
        <div>
            <p lang="ar">
                انما نحن هنا نقوم بكتابة نصوص عربية فلما لا يكون هناك اخرى مما قد يحتم علينا أن نكتب أكثر.<br>
                وعليه فإن السطر هذا يعتبر سطر جديد
            </p>
        </div>
    </div>
</body>

</html>
```
