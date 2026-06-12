 <?php
/**
 * Designed for v2.1.0+ (depends on InteractsWithPlugins trait and NOTIFY_HTML_HEAD_END notifier added in v2.1.0 )
 */

use Zencart\PageLoader\PageLoader;
use Zencart\Traits\InteractsWithPlugins;
use Zencart\Traits\NotifierManager;
use Zencart\Traits\ObserverManager;

class zcObserverPostAuto
{
    use InteractsWithPlugins;
    use NotifierManager;
    use ObserverManager;

    public function __construct()
    {
        $this->attach($this, ['NOTIFY_HTML_HEAD_END']);

        /**
         * Determine this zc_plugin's paths: $this->zcPluginCatalogPath is used to load more template assets
         */
        $this->detectZcPluginDetails(DIR_FS_CATALOG . 'zc_plugins/PostAuto/v2.0.5/catalog');
    }

    /**
     * Catalog: Runs at the end of the active template's html_header.php (just before the </head> tag)
     * We can load additional plugin assets here.
     */
    public function notify_html_head_end(&$class, $eventID, string $current_page_base): void
    {
        if (in_array($current_page_base, ['address_book_process', 'checkout_shipping_address', 'login'])) {
            // load a plugin-specific stylesheet, first from plugin dir, then from store's template:
            $this->linkCatalogStylesheet('stylesheet_jquery.powertip.min.css', $current_page_base);

            // load a JS/PHP file from the plugin's jscript directory:
            $pageLoader = PageLoader::getInstance();
            $filename = 'jscript_postcode.php';
            if (file_exists($file = $pageLoader->getTemplatePluginDir($filename, 'jscript', $this->zcPluginDirName) . $filename)) {
                include($file);
            }
        }
    }
}
