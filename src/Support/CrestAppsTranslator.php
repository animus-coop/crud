<?php

namespace AnimusCoop\CrudGenerator\Support;

use Illuminate\Translation\Translator;
use Illuminate\Support\Arr;

class AnimusTranslator extends Translator
{
    /**
     * Add translation lines to the given locale.
     *
     * @param  array  $lines
     * @param  string  $locale
     * @param  string  $namespace
     * @return void
     */
    public function addLines(array $lines, $locale, $namespace = '*')
    {
        foreach ($lines as $key => $value) {
            list($group, $item) = explode('.', $key, 2);
            Arr::set($this->loaded, "$namespace.$group.$locale.$item", $value);
        }
    }

    /**
    * Adds a new instance of animus_translator to the IoC container,
    *
    * @return AnimusCoop\CrudGenerator\Support\AnimusTranslator
    */
    public static function getTranslator()
    {
        $translator = app('translator');

        app()->singleton('animus_translator', function ($app) use ($translator) {
            $trans = new AnimusTranslator($translator->getLoader(), $translator->getLocale());

            $trans->setFallback($translator->getFallback());

            return $trans;
        });

        return app('animus_translator');
    }
}
