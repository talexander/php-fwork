<?php

namespace App {

    class I18n {

        /**
         * @var  string   target language: en-us, es-es, zh-cn, etc
         */
        public static $lang = 'en-us';

        /**
         * @var  string  source language: en-us, es-es, zh-cn, etc
         */
        public static $source = 'en-us';

        /**
         * @var  array  cache of loaded languages
         */
        protected static $_cache = array();

        public static function lang($lang = NULL) {
            if ($lang) {
                // Normalize the language
                I18n::$lang = strtolower(str_replace(array(' ', '_'), '-', $lang));
            }

            return I18n::$lang;
        }

        /**
         * Returns translation of a string. If no translation exists, the original
         * string will be returned. No parameters are replaced.
         *
         *     $hello = I18n::get('Hello friends, my name is :name');
         *
         * @param   string  $string text to translate
         * @param   string  $lang   target language
         * @return  string
         */
        public static function get($string, $lang = NULL) {
            if ( ! $lang) {
                // Use the global target language
                $lang = I18n::$lang;
            }

            // Load the translation table for this language
            $table = I18n::load($lang);

            // Return the translated string if it exists
            return isset($table[$string]) ? $table[$string] : $string;
        }

        /**
         * Returns the translation table for a given language.
         *
         *     // Get all defined Spanish messages
         *     $messages = I18n::load('es-es');
         *
         * @param   string  $lang   language to load
         * @return  array
         */
        public static function load($lang) {
            if (isset(I18n::$_cache[$lang])) {
                return I18n::$_cache[$lang];
            }

            return array();
            // @TODO: implement

            // New translation table
            $table = array();

            // Split the language: language, region, locale, etc
            $parts = explode('-', $lang);

            do {
                // Create a path for this set of parts
                $path = implode(DIRECTORY_SEPARATOR, $parts);
                // @TODO: переписать
                if ($files = Kohana::find_file('i18n', $path, NULL, TRUE)) {
                    $t = array();
                    foreach ($files as $file) {
                        // Merge the language strings into the sub table
                        // @TODO: переписать
                        $t = array_merge($t, Kohana::load($file));
                    }

                    // Append the sub table, preventing less specific language
                    // files from overloading more specific files
                    $table += $t;
                }

                // Remove the last part
                array_pop($parts);
            }
            while ($parts);

            // Cache the translation table locally
            return I18n::$_cache[$lang] = $table;
        }

    } // End I18n
}
/**
 * [strtr](http://php.net/strtr) is used for replacing parameters.
 *    __('Welcome back, :user', array(':user' => $username));
 *
 * [!!] The target language is defined by [I18n::$lang].
 * @uses    I18n::get
 * @param   string  $string text to translate
 * @param   array   $values values to replace in the translated text
 * @param   string  $lang   source language
 * @return  string
 */

function __($string, array $values = NULL, $lang = 'en-us') {
    if ($lang !== \App\I18n::$lang) {
        // The message and target languages are different
        // Get the translation for this message
        $string = \App\I18n::get($string);
    }

    return empty($values) ? $string : strtr($string, $values);
}