<?php

namespace Ab\ArabicHTML;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::macro('toArabicHTML', function ($line_length = 100, $hindo = false, $forcertl = false) {
            return ServiceProvider::convertToArabic($this->toHtml(), $line_length, $hindo, $forcertl);
        });
    }

    /**
     * Convert arabic text in HTML  to utf8Glyphs.
     * @param string $html
     * @param int $line_length
     * @param bool $hindo
     * @param bool $forcertl
     */
    public static function convertToArabic($html, int $line_length = 100, bool $hindo = false, $forcertl = false): string
    {
        $Arabic = new \ArPHP\I18N\Arabic();
        $p = $Arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $Arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]), $line_length, $hindo, $forcertl);
            $html   = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        return $html;
    }
}
