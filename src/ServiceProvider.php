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
            
            // Check if array is valid and has at least two elements
            if (!is_array($p) || count($p) < 2) {
                // If no Arabic text was found or the array is invalid, return the original HTML
                return $html;
            }
            
            // Make sure we have even number of elements (pairs of positions)
            if (count($p) % 2 !== 0) {
                // If odd count, remove last element to make it even
                array_pop($p);
            }
            
            // Process pairs of positions in reverse order
            for ($i = count($p) - 1; $i >= 1; $i -= 2) {
                // Validate array indices before using them
                if (!isset($p[$i]) || !isset($p[$i - 1]) || $p[$i] < $p[$i - 1]) {
                    continue; // Skip this iteration if indices are invalid
                }
                
                $utf8ar = $Arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]), $line_length, $hindo, $forcertl);
                $html   = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
                
            }

            return $html;
       
        
    }
}
