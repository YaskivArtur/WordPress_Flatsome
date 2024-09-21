<?php

defined('ABSPATH') || defined('DUPXABSPATH') || exit;

?>

<!-- ==============================
OPTIONS DATA -->
<div class="dup-box">
    <div class="dup-box-title">
        <i class="fa fa-th-list"></i>
        <?php esc_html_e("Data Cleanup", 'duplicator'); ?>
        <div class="dup-box-arrow"></div>
    </div>
    <div class="dup-box-panel" id="dup-settings-diag-opts-panel" style="<?php echo esc_html($ui_css_opts_panel); ?>">
        <table class="dup-reset-opts">
            <tr style="vertical-align:text-top">
                <td>
                    <button id="dup-remove-installer-files-btn" type="button" class="button button-small dup-fixed-btn" onclick="Duplicator.Tools.deleteInstallerFiles();">
                        <?php esc_html_e("Remove Installation Files", 'duplicator'); ?>
                    </button>
                </td>
                <td>
                    <?php esc_html_e("Removes all reserved installer files.", 'duplicator'); ?>
                    <a href="javascript:void(0)" onclick="jQuery('#dup-tools-delete-moreinfo').toggle()">[<?php esc_html_e("more info", 'duplicator'); ?>]</a><br/>

                    <div id="dup-tools-delete-moreinfo">
                        <?php
                            esc_html_e("Clicking on the 'Remove Installation Files' button will attempt to remove the installer files used by Duplicator.  These files should not "
                            . "be left on production systems for security reasons. Below are the files that should be removed.", 'duplicator');
                            echo "<br/><br/>";

                            $installer_files = array_keys($installer_files);
                            array_push($installer_files, '[HASH]_archive.zip/daf');
                            echo '<i>' . implode('<br/>', $installer_files) . '</i>';
                            echo "<br/><br/>";
                            ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="button" class="button button-small dup-fixed-btn" onclick="Duplicator.Tools.ConfirmClearBuildCache()">
                        <?php esc_html_e("Clear Build Cache", 'duplicator'); ?>
                    </button>
                </td>
                <td><?php esc_html_e("Removes all build data from:", 'duplicator'); ?> [<?php echo DUP_Settings::getSsdirTmpPath() ?>].</td>
            </tr>
        </table>

    </div>
</div>
<br/>

<!-- ==========================================
THICK-BOX DIALOGS: -->
<?php
    $confirmClearBuildCache             = new DUP_UI_Dialog();
    $confirmClearBuildCache->title      = __('Clear Build Cache?', 'duplicator');
    $confirmClearBuildCache->message    = __('This process will remove all build cache files.  Be sure no packages are currently building or else they will be cancelled.', 'duplicator');
    $confirmClearBuildCache->jscallback = 'Duplicator.Tools.ClearBuildCache()';
    $confirmClearBuildCache->initConfirm();
?>

<script>
jQuery(document).ready(function($)
{
    Duplicator.Tools.ConfirmClearBuildCache = function ()
    {
         <?php $confirmClearBuildCache->showConfirm(); ?>
    }

    Duplicator.Tools.ClearBuildCache = function ()
    {
        window.location = '?page=duplicator-tools&tab=diagnostics&action=tmp-cache&_wpnonce=<?php echo esc_js($nonce); ?>';
    }
});


Duplicator.Tools.deleteInstallerFiles = function()
{
    <?php
    $url = DUP_CTRL_Tools::getCleanFilesAcrtionUrl();
    echo "window.location = '{$url}';";
    ?>
}
</script>
