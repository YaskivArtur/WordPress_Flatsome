<?php

/**
 * @package Duplicator
 */

use Duplicator\Utils\Help\Help;
use Duplicator\Libs\Snap\SnapJson;

defined("ABSPATH") || exit;

/**
 * Variables
 *
 * @var \Duplicator\Core\Views\TplMng  $tplMng
 * @var array<string, mixed> $tplData
 */
$helpPageUrl = SnapJson::jsonEncode(Help::getHelpPageUrl());
require_once(DUPLICATOR_PLUGIN_PATH . '/assets/js/javascript.php');
?>
<script>
    jQuery(document).ready(function ($) {
        $('.duplicator-help-open').click(function () { 
            if (Duplicator.Help.isDataLoaded()) {
                Duplicator.Help.Display();
            } else {
                Duplicator.Help.Load(<?php echo $helpPageUrl; ?>);
            }
        });
    });
</script>
<div id="dup-meta-screen"></div>
<div class="dup-header">
    <img src="<?php echo DUPLICATOR_PLUGIN_URL . 'assets/img/duplicator-header-logo.svg'; ?>" alt="Duplicator Logo" >
    <button class="duplicator-help-open">
        <i class="fa-regular fa-question-circle"></i>
    </button>
</div>
