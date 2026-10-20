<?php
/**
 * Designed for v2.1.0+ (depends on InteractsWithPlugins trait and NOTIFY_HTML_HEAD_END notifier added in v2.1.0 )
 */

use Zencart\Traits\InteractsWithPlugins;
use Zencart\Traits\ObserverManager;

class zcObserverPostAuto
{
    use InteractsWithPlugins;
    use ObserverManager;

    public function __construct()
    {
        $this->attach($this, ['NOTIFY_HTML_HEAD_END', 'NOTIFY_ZEN_REDIRECT']);

        /**
         * Determine this zc_plugin's paths: $this->zcPluginCatalogPath is used to load more template assets
         */
        $this->detectZcPluginDetails(__DIR__);
    }

    /**
     * Catalog: Runs at the end of the active template's html_header.php (just before the </head> tag)
     * We can load additional plugin assets here.
     */
    public function notify_html_head_end(&$class, $eventID, string $current_page_base): void
    {
        if (in_array($current_page_base, ['address_book_process', 'checkout_shipping_address', 'checkout_payment_address', 'login', 'create_account', 'checkout_one'])) {
            // load a plugin-specific stylesheet, first from plugin dir, then from store's template:
            $this->linkCatalogStylesheet('jquery.powertip.min.css', $current_page_base);

            // load a JS/PHP file from the plugin's jscript directory:
            global $pageLoader;
            if (!$pageLoader) {
                $pageLoader = PageLoader::getInstance();
            }

            if ($current_page_base === 'checkout_one') {
                $jsFilename = 'js_postcode_bill_ship.php';
            } else {
                $jsFilename = 'js_postcode.php';
            }
            $pluginDir = $pageLoader->getTemplatePluginDir($jsFilename, 'jscript', $this->zcPluginDirName);
            if ($pluginDir !== false) {
                $file = $pluginDir . $jsFilename;
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }
    }
    public function notify_zen_redirect(&$class, $eventID, array $params, bool &$stopRedirect): void
    {
        if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'ajax.php' && ($_GET['act'] ?? '') === 'AjaxPostcodeQuery') {
            $stopRedirect = true;
        }
    }
}
