<?php
defined('ABSPATH') || die();

$isPRO = NextendSocialLoginAdmin::isPro();

$attr = '';
if (!$isPRO) {
    $attr = ' disabled ';
}

$settings = NextendSocialLogin::$settings;

NextendSocialLoginAdmin::showProBox();
?>
    <table class="form-table">
        <tbody>

        <tr>
            <th scope="row"><?php _e('Custom Actions', 'nextend-facebook-connect'); ?></th>
            <td>
                <?php
                $customActions = $settings->get('custom_actions');
                ?>
                <textarea rows="4" cols="53" name="custom_actions" id="custom_actions"<?php echo $attr; ?>><?php echo esc_textarea($customActions); ?></textarea>
                <p class="description"><?php printf(__('%1$s Add your custom actions here. One action per line.', 'nextend-facebook-connect'), '<b>' . __("Usage:", "nextend-facebook-connect") . '</b>'); ?></p>
                <p class="description"><?php printf(__('%1$s The HTML of the social buttons will be added at the place where the action is fired.', 'nextend-facebook-connect'), '<b>' . __("Important:", "nextend-facebook-connect") . '</b>'); ?></p>
                <p class="description"><?php printf(__('If you %1$sexperience problems%2$s because of this feature, you can disable it by defining the %3$s constant.', 'nextend-facebook-connect'), '<a href="https://nextendweb.com/nextend-social-login-docs/global-settings-custom-actions/#custom-actions" target="_blank">', '</a>', '<code>NSL_DISABLE_CUSTOM_ACTIONS</code>'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row"><?php _e('Button style', 'nextend-facebook-connect'); ?></th>
            <td>
                <fieldset>
                    <label>
                        <input type="radio" name="custom_actions_button_style"
                               value="default" <?php if ($settings->get('custom_actions_button_style') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                        <img src="<?php echo plugins_url('images/buttons/default.png', NSL_ADMIN_PATH) ?>"/>
                    </label>
                    <label>
                        <input type="radio" name="custom_actions_button_style"
                               value="fullwidth" <?php if ($settings->get('custom_actions_button_style') == 'fullwidth') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Fullwidth', 'nextend-facebook-connect'); ?></span><br/>
                        <img src="<?php echo plugins_url('images/buttons/fullwidth.png', NSL_ADMIN_PATH) ?>"/>
                    </label>
                    <label>
                        <input type="radio" name="custom_actions_button_style"
                               value="icon" <?php if ($settings->get('custom_actions_button_style') == 'icon') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Icon', 'nextend-facebook-connect'); ?></span><br/>
                        <img src="<?php echo plugins_url('images/buttons/icon.png', NSL_ADMIN_PATH) ?>"/>
                    </label><br>
                </fieldset>
            </td>
        </tr>

        <tr>
            <th scope="row"><?php _e('Button layout', 'nextend-facebook-connect'); ?></th>
            <td>
                <fieldset>
                    <label>
                        <input type="radio" name="custom_actions_button_layout"
                               value="default" <?php if ($settings->get('custom_actions_button_layout') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                        <img src="<?php echo plugins_url('images/layouts/default.png', NSL_ADMIN_PATH) ?>"/>
                    </label>
                    <label>
                        <input type="radio" name="custom_actions_button_layout"
                               value="default-separator-top" <?php if ($settings->get('custom_actions_button_layout') == 'default-separator-top') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Default with top separator', 'nextend-facebook-connect'); ?></span><br/>
                        <img src="<?php echo plugins_url('images/layouts/below-separator.png', NSL_ADMIN_PATH) ?>"/>
                    </label>
                    <label>
                        <input type="radio" name="custom_actions_button_layout"
                               value="default-separator-bottom" <?php if ($settings->get('custom_actions_button_layout') == 'default-separator-bottom') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Default with bottom separator', 'nextend-facebook-connect'); ?></span><br/>
                        <img src="<?php echo plugins_url('images/layouts/above-separator.png', NSL_ADMIN_PATH) ?>"/>
                    </label><br>
                </fieldset>
            </td>
        </tr>

        <tr>
            <th scope="row"><?php _e('Button alignment', 'nextend-facebook-connect'); ?></th>
            <td>
                <fieldset>
                    <label><input type="radio" name="custom_actions_button_align"
                                  value="left" <?php if ($settings->get('custom_actions_button_align') == 'left') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Left', 'nextend-facebook-connect'); ?></span></label><br>
                    <label><input type="radio" name="custom_actions_button_align"
                                  value="center" <?php if ($settings->get('custom_actions_button_align') == 'center') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Center', 'nextend-facebook-connect'); ?></span></label><br>

                    <label><input type="radio" name="custom_actions_button_align"
                                  value="right" <?php if ($settings->get('custom_actions_button_align') == 'right') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                        <span><?php _e('Right', 'nextend-facebook-connect'); ?></span></label><br>
                </fieldset>
            </td>
        </tr>
        </tbody>
    </table>
<?php if ($isPRO): ?>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                             value="<?php _e('Save Changes'); ?>"></p>
<?php endif; ?>