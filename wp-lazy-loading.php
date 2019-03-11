<?php
/**
 * Plugin Name: WP Simple Lazy Loading
 * Description: A Simple Lazy Loading Functionality Plugin for WordPress
 * Author: Niklas Dahlqvist
 * Author URI: https://www.niklasdahlqvist.com
 * Version: 1.0
 * License: GPL2+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
* Ensure class doesn't already exist
*/
if (!class_exists('WPSimpleLazyLoading')) {
    class WPSimpleLazyLoading
    {
        public function __construct()
        {
            //Actions
            add_action('wp_enqueue_scripts', [$this, 'loadLazyLoadScripts']);

            //Filters
            add_filter('the_content', [$this, 'filterTheContent'], 20);
            add_filter('post_thumbnail_html', [$this, 'filterTheContent'], 11);
        }

        public function loadLazyLoadScripts()
        {
            // If you Need Polyfill uncomment the line below
            //wp_enqueue_script('IntersectionObserver', 'https://polyfill.io/v2/polyfill.min.js?features=IntersectionObserver', [], null, true);
            wp_enqueue_script('lozad', 'https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js', [], null, true);
            wp_add_inline_script('lozad', '
                lozad(".lazy-load", { 
                    rootMargin: "300px 0px", 
                    loaded: function (el) {
                        el.classList.add("is-loaded");
                    }
                }).observe();
            ');
        }

        public function filterTheContent($content)
        {
            //-- Source URL
            preg_match('/src="([^"]*)"/i', $content, $matches);
            $src = $matches['0'];

            //-- ALT Text
            preg_match('/alt="([^"]*)"/i', $content, $matches);
            $alt = $matches['0'];

            //-- Change src to data attributes.
            $content = preg_replace('/<img(.*?)(src="[^"]*")([^>]*?)>/i', '<img$1data-$2$3>', $content);

            //-- Change srcset to data attributes.
            $content = preg_replace('/<img(.*?)(srcset=)(.*?)>/i', '<img$1data-$2$3>', $content);

            //-- Add .lazy-load class to each image that already has a class.
            $content = preg_replace('/<img(.*?)class=\"(.*?)\"(.*?)>/i', '<img$1class="$2 lazy-load"$3>', $content);

            //-- Add .lazy-load class to each image that doesn't already have a class.
            $content = preg_replace('/<img((?:(?!class=).)*?)>/i', '<img class="lazy-load"$1>', $content);

            //-- Add NoScript Tag
            $content .= '<noscript><img ' . $src . ' ' . $alt . '></noscript>';

            return $content;
        }
    } //End Class

    /**
     * Instantiate this class to ensure the action and filter hooks are hooked.
     * This instantiation can only be done once (see it's __construct() to understand why.)
     */
    new WPSimpleLazyLoading();
} // End if class exists statement
