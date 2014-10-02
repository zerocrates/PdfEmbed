<?php
/**
 * @package PdfEmbed
 * @copyright Copyright 2014, John Flatness
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3 or any later version
 */

class PdfEmbedPlugin extends Omeka_Plugin_AbstractPlugin
{
    const OPTION_NAME = 'pdf_embed_settings';

    private static $types = array('application/pdf', 'application/x-pdf');
    private static $exts = array('pdf');
    private static $defaultSettings = array(
        'height' => 500,
        'pdf_embed_type' => 'object'
    );
    private static $settings = array();

    protected $_hooks = array('initialize',
        'config', 'config_form', 'install', 'uninstall'
    );

    public function hookInstall()
    {
        self::_setSettings(self::$defaultSettings);
    }

    public function hookUninstall()
    {
        delete_option(self::OPTION_NAME);
    }

    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');

        add_file_display_callback(
            array(
                'mimeTypes' => self::$types,
                'fileExtensions' => self::$exts
            ),
            'PdfEmbedPlugin::embedPdf',
            self::_getSettings()
        );
    }

    public function hookConfigForm()
    {
        $settings = self::_getSettings();
        include 'config-form.php';
    }

    public function hookConfig()
    {
        $settings['height'] = (int) $_POST['height'];
        $settings['pdf_embed_type'] = $_POST['pdf_embed_type'];

        self::_setSettings($settings);
    }

    public static function embedPdf($file, $options)
    {
        switch ($options['pdf_embed_type']) {
            case 'pdf_js':
                return self::embedPdfJs($file, $options);
            case 'object':
            default:
                return self::embedPdfObject($file, $options);
        }
    }

    public static function embedPdfObject($file, $options)
    {
        $height = (int) $options['height'];
        $attrs['data'] = $file->getWebPath('original');
        $attrs['type'] = 'application/pdf';
        $attrs['style'] = "width: 100%; height: {$height}px";
        $attrString = tag_attributes($attrs);

        return "<object {$attrString}></object>";
    }

    public static function embedPdfJs($file, $options)
    {
        $height = (int) $options['height'];
        $pdfJsViewer = web_path_to('pdf-embed-js/web/viewer.html');
        $hash = (($lang = get_html_lang()) == 'en-US')
              ? ''
              : '#locale=' . rawurlencode($lang);
        $attrs['src'] = $pdfJsViewer
            . '?file=' . rawurlencode($file->getWebPath('original'))
            . $hash;
        $attrs['style'] = "width: 100%; height: {$height}px";
        $attrString = tag_attributes($attrs);

        return "<iframe {$attrString}></iframe>";
    }

    public static function _getSettings()
    {
        if (!self::$settings) {
            $settings = json_decode(get_option(self::OPTION_NAME), true);
            self::$settings = $settings ? $settings : array();
        }
        return self::$settings;
    }

    public static function _setSettings($settings)
    {
        set_option(self::OPTION_NAME, json_encode($settings));
    }
}
