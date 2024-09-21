<?php

/**
 * Impost installer page controller
 *
 * @package   Duplicator
 * @copyright (c) 2022, Snap Creek LLC
 */

namespace Duplicator\Controllers;

use Duplicator\Core\Controllers\ControllersManager;
use Duplicator\Core\Views\TplMng;
use Duplicator\Libs\Snap\SnapUtil;

class HelpPageController
{
    const HELP_SLUG = 'duplicator-dynamic-help';

    /**
     * Class constructor
     *
     * @return void
     */
    public static function init()
    {
        if (!ControllersManager::isCurrentPage(self::HELP_SLUG) || !is_admin()) {
            return;
        }

        $tag = SnapUtil::sanitizeInput(INPUT_GET, 'tag', '');
        TplMng::getInstance()->render(
            "parts/help/main",
            [
                'tag' => $tag,
            ]
        );
        die;
    }

    /**
     * Returns link to the help page
     *
     * @return string
     */
    public static function getHelpLink()
    {
        return ControllersManager::getMenuLink(self::HELP_SLUG);
    }
}
