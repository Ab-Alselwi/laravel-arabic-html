<?php

namespace Ab\ArabicHTML;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\View\View;


class ServiceProvider extends IlluminateServiceProvider 
{

     /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::macro('toArabicHTML', function () {
            $html=$this->toHtml();
            $Arabic = new \ArPHP\I18N\Arabic();
            $p = $Arabic->arIdentify($html);

            for ($i = count($p)-1; $i >= 0; $i-=2) {
                $utf8ar = $Arabic->utf8Glyphs(substr($html, $p[$i-1], $p[$i] - $p[$i-1]));
                $html   = substr_replace($html, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
            }

            return $html;
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        

    }

    
}
