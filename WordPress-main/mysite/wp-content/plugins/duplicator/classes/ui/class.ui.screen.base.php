<?php

use Duplicator\Libs\Snap\SnapUtil;

defined('ABSPATH') || defined('DUPXABSPATH') || exit;
/**
 * The base class for all screen.php files.  This class is used to control items that are common
 * among all screens, namely the Help tab and Screen Options drop down items.  When creating a
 * screen object please extent this class.
 *
 * Standard: PSR-2
 *
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package    Duplicator
 * @subpackage classes/ui
 * @copyright  (c) 2017, Snapcreek LLC
 */
// Exit if accessed directly
if (!defined('DUPLICATOR_VERSION')) {
    exit;
}

class DUP_UI_Screen
{
    /**
     * Used as a placeholder for the current screen object
     */
    public $screen;

    /**
     *  Init this object when created
     */
    public function __construct()
    {
    }

    public static function getCustomCss()
    {
        $screen = get_current_screen();
        if (
            !in_array($screen->id, array(
            'toplevel_page_duplicator',
            'duplicator_page_duplicator-tools',
            'duplicator_page_duplicator-settings',
            'duplicator_page_duplicator-gopro'))
        ) {
            return;
        }

        $colorScheme        = self::getCurrentColorScheme();
        $primaryButtonColor = self::getPrimaryButtonColorByScheme();
        if ($colorScheme !== false) { ?>
            <style>
                .link-style {
                    color: <?php echo $colorScheme->colors[2]; ?>;
                }

                .link-style:hover {
                    color: <?php echo $colorScheme->colors[3]; ?>;
                }
                
                
                .dup-radio-button-group-wrapper input[type="radio"] + label {
                    color: <?php echo $primaryButtonColor; ?>;
                }

                .dup-radio-button-group-wrapper input[type="radio"] + label:hover,
                .dup-radio-button-group-wrapper input[type="radio"]:focus + label, 
                .dup-radio-button-group-wrapper input[type="radio"]:checked + label {
                    background: <?php echo $primaryButtonColor; ?>;
                    border-color: <?php echo $primaryButtonColor; ?>;
                }
            </style>
            <?php
        }
    }

        /**
     * Unfortunately not all color schemes take the same color as the buttons so you need to make a custom switch/
     *
     * @return string
     */
    public static function getPrimaryButtonColorByScheme()
    {
        $colorScheme = self::getCurrentColorScheme();
        $name        = strtolower($colorScheme->name);
        switch ($name) {
            case 'blue':
                return '#e3af55';
            case 'light':
            case 'midnight':
                return $colorScheme->colors[3];
            case 'ocean':
            case 'ectoplasm':
            case 'coffee':
            case 'sunrise':
            case 'default':
            default:
                return $colorScheme->colors[2];
        }
    }


    public static function getCurrentColorScheme()
    {
        global $_wp_admin_css_colors;
        if (!isset($_wp_admin_css_colors) || !is_array($_wp_admin_css_colors)) {
            return false;
        }

        $colorScheme = get_user_option('admin_color');

        if (isset($_wp_admin_css_colors[$colorScheme])) {
            return $_wp_admin_css_colors[$colorScheme];
        } else {
             return $_wp_admin_css_colors[SnapUtil::arrayKeyFirst($_wp_admin_css_colors)];
        }
    }
}
