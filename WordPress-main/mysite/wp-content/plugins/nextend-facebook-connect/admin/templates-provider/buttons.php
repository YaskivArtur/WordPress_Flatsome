<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();

$settings = $provider->settings;

$isPRO = apply_filters('nsl-pro', false);
?>
<div class="nsl-admin-sub-content">
    <script type="text/javascript">

        document.addEventListener("DOMContentLoaded", function () {
            window.resetButtonToDefault = function (id) {
                var defaultButtonValues = {
                    '#login_label': <?php echo wp_json_encode($settings->get('login_label', 'default')); ?>,
                    '#register_label': <?php echo wp_json_encode($settings->get('register_label', 'default')); ?>,
                    '#link_label': <?php echo wp_json_encode($settings->get('link_label', 'default')); ?>,
                    '#unlink_label': <?php echo wp_json_encode($settings->get('unlink_label', 'default')); ?>,
                    '#custom_default_button': <?php echo wp_json_encode($provider->getRawDefaultButton()); ?>,
                    '#custom_icon_button': <?php echo wp_json_encode($provider->getRawIconButton()); ?>
                };

                var inputField = document.querySelector(id),
                    codeMirror = inputField.parentNode.querySelector('.CodeMirror');

                inputField.value = defaultButtonValues[id];
                if (codeMirror) {
                    codeMirror.CodeMirror.setValue(defaultButtonValues[id]);
                }
                return false;
            };

            var defaultButton = document.getElementById('custom_default_button_enabled');
            defaultButton.addEventListener('change', function () {
                if (this.checked) {
                    document.getElementById('custom_default_button_textarea_container').style.removeProperty('display');

                    var inputField = document.getElementById('custom_default_button'),
                        codeMirror = inputField.parentNode.querySelector('.CodeMirror');
                    if (codeMirror) {
                        codeMirror.CodeMirror.refresh();
                    }
                } else {
                    document.getElementById('custom_default_button_textarea_container').style.display = 'none';
                }
            });

            var defaultIcon = document.getElementById('custom_icon_button_enabled');
            defaultIcon.addEventListener('change', function () {
                if (this.checked) {
                    document.getElementById('custom_icon_button_textarea_container').style.removeProperty('display');

                    var inputField = document.getElementById('custom_icon_button');
                    var codeMirror = inputField.parentNode.querySelector('.CodeMirror');
                    if (codeMirror) {
                        codeMirror.CodeMirror.refresh();
                    }
                } else {
                    document.getElementById('custom_icon_button_textarea_container').style.display = 'none';
                }
            });
        });

    </script>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" novalidate="novalidate">

        <?php wp_nonce_field('nextend-social-login'); ?>
        <input type="hidden" name="action" value="nextend-social-login"/>
        <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
        <input type="hidden" name="subview" value="buttons"/>

        <table class="form-table">
            <tbody>
            <?php
            $buttonsPath = $provider->getPath() . '/admin/buttons.php';
            if (file_exists($buttonsPath)) {
                include($buttonsPath);
            }
            ?>

            <tr>
                <th scope="row"><label
                            for="login_label"><?php _e('Login label', 'nextend-facebook-connect'); ?></label></th>
                <td>
                    <input name="login_label" type="text" id="login_label"
                           value="<?php echo esc_attr($settings->get('login_label')); ?>" class="regular-text">
                    <p class="description"><a href="#"
                                              onclick="return resetButtonToDefault('#login_label');"><?php _e('Reset to default', 'nextend-facebook-connect'); ?></a>
                    </p>
                </td>
            </tr>

            <?php
            $useCustomRegisterLabel = NextendSocialLogin::$settings->get('custom_register_label');
            if ($useCustomRegisterLabel): ?>
                <tr>
                    <th scope="row"><label
                                for="register_label"><?php _e('Register label', 'nextend-facebook-connect'); ?></label>
                    </th>
                    <td>
                        <input name="register_label" type="text" id="register_label"
                               value="<?php echo esc_attr($settings->get('register_label')); ?>" class="regular-text">
                        <p class="description"><a href="#"
                                                  onclick="return resetButtonToDefault('#register_label');"><?php _e('Reset to default', 'nextend-facebook-connect'); ?></a>
                        </p>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <th scope="row"><label for="link_label"><?php _e('Link label', 'nextend-facebook-connect'); ?></label>
                </th>
                <td>
                    <input name="link_label" type="text" id="link_label"
                           value="<?php echo esc_attr($settings->get('link_label')); ?>" class="regular-text">
                    <p class="description"><a href="#"
                                              onclick="return resetButtonToDefault('#link_label');"><?php _e('Reset to default', 'nextend-facebook-connect'); ?></a>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="unlink_label"><?php _e('Unlink label', 'nextend-facebook-connect'); ?></label></th>
                <td>
                    <input name="unlink_label" type="text" id="unlink_label"
                           value="<?php echo esc_attr($settings->get('unlink_label')); ?>" class="regular-text">
                    <p class="description"><a href="#"
                                              onclick="return resetButtonToDefault('#unlink_label');"><?php _e('Reset to default', 'nextend-facebook-connect'); ?></a>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="custom_default_button"><?php _e('Default button', 'nextend-facebook-connect'); ?></label>
                </th>
                <td>
                    <?php
                    $useCustom      = false;
                    $buttonTemplate = $settings->get('custom_default_button');
                    if (!empty($buttonTemplate)) {
                        $useCustom = true;
                    } else {
                        $buttonTemplate = $provider->getRawDefaultButton();
                    }
                    ?>
                    <fieldset><label for="custom_default_button_enabled">
                            <input name="custom_default_button_enabled" type="checkbox"
                                   id="custom_default_button_enabled"
                                   value="1" <?php if ($useCustom): ?> checked<?php endif; ?>>
                            <?php _e('Use custom button', 'nextend-facebook-connect'); ?></label>
                    </fieldset>
                    <div id="custom_default_button_textarea_container" <?php if (!$useCustom): ?> style="display:none;"<?php endif; ?>>
                        <textarea cols="160" rows="6" name="custom_default_button" id="custom_default_button"
                                  class="nextend-html-editor"
                                  aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo esc_textarea($buttonTemplate); ?></textarea>
                        <p class="description"><a href="#"
                                                  onclick="return resetButtonToDefault('#custom_default_button');"><?php _e('Reset to default', 'nextend-facebook-connect'); ?></a><br><br><?php printf(__('Use the %s in your custom button\'s code to make the label show up.', 'nextend-facebook-connect'), "<code>{{label}}</code>"); ?>
                        </p>
                    </div>
                </td>
            </tr>
            <?php if ($isPRO): ?>
                <tr>
                    <th scope="row"><label
                                for="custom_icon_button"><?php _e('Icon button', 'nextend-facebook-connect'); ?></label>
                    </th>
                    <td>
                        <?php
                        $useCustom      = false;
                        $buttonTemplate = $settings->get('custom_icon_button');
                        if (!empty($buttonTemplate)) {
                            $useCustom = true;
                        } else {
                            $buttonTemplate = $provider->getRawIconButton();
                        }
                        ?>
                        <fieldset><label for="custom_icon_button_enabled">
                                <input name="custom_icon_button_enabled" type="checkbox" id="custom_icon_button_enabled"
                                       value="1" <?php if ($useCustom): ?> checked<?php endif; ?>>
                                <?php _e('Use custom button', 'nextend-facebook-connect'); ?></label>
                        </fieldset>
                        <div id="custom_icon_button_textarea_container" <?php if (!$useCustom): ?> style="display:none;"<?php endif; ?>>
                        <textarea cols="160" rows="6" name="custom_icon_button" id="custom_icon_button"
                                  class="nextend-html-editor"
                                  aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo esc_textarea($buttonTemplate); ?></textarea>
                            <p class="description"><a href="#"
                                                      onclick="return resetButtonToDefault('#custom_icon_button');"><?php _e('Reset to default', 'nextend-facebook-connect'); ?></a>
                            </p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="<?php _e('Save Changes'); ?>"></p>
    </form>
</div>
