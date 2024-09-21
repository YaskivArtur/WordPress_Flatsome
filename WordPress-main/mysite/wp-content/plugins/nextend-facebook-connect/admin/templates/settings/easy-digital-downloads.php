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
        <th scope="row"><?php _e('Login form', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label><input type="radio" name="edd_login"
                              value="" <?php if ($settings->get('edd_login') == '') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('No Connect button in Login form', 'nextend-facebook-connect'); ?></span></label><br>
                <label><input type="radio" name="edd_login"
                              value="before" <?php if ($settings->get('edd_login') == 'before') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_login_fields_before</code></label><br>
                <label><input type="radio" name="edd_login"
                              value="after" <?php if ($settings->get('edd_login') == 'after') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_login_fields_after</code></label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Login form button style', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label>
                    <input type="radio" name="edd_login_form_button_style"
                           value="default" <?php if ($settings->get('edd_login_form_button_style') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/default.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_login_form_button_style"
                           value="fullwidth" <?php if ($settings->get('edd_login_form_button_style') == 'fullwidth') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Fullwidth', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/fullwidth.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_login_form_button_style"
                           value="icon" <?php if ($settings->get('edd_login_form_button_style') == 'icon') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Icon', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/icon.png', NSL_ADMIN_PATH) ?>"/>
                </label><br>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Login layout', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label>
                    <input type="radio" name="edd_login_form_layout"
                           value="default" <?php if ($settings->get('edd_login_form_layout') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/default.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_login_form_layout"
                           value="below" <?php if ($settings->get('edd_login_form_layout') == 'below') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Below', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/below.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_login_form_layout"
                           value="below-separator" <?php if ($settings->get('edd_login_form_layout') == 'below-separator') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Below with separator', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/below-separator.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_login_form_layout"
                           value="above" <?php if ($settings->get('edd_login_form_layout') == 'above') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Above', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/above.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_login_form_layout"
                           value="above-separator" <?php if ($settings->get('edd_login_form_layout') == 'above-separator') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Above with separator', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/above-separator.png', NSL_ADMIN_PATH) ?>"/>
                </label><br>
            </fieldset>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Register form', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label><input type="radio" name="edd_register"
                              value="" <?php if ($settings->get('edd_register') == '') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('No Connect button in Register form', 'nextend-facebook-connect'); ?></span></label><br>
                <label><input type="radio" name="edd_register"
                              value="top" <?php if ($settings->get('edd_register') == 'top') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_register_form_fields_top</code></label><br>
                <label><input type="radio" name="edd_register"
                              value="before" <?php if ($settings->get('edd_register') == 'before') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_register_form_fields_before</code></label><br>
                <label><input type="radio" name="edd_register"
                              value="before_submit" <?php if ($settings->get('edd_register') == 'before_submit') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_register_form_fields_before_submit</code></label><br>
                <label><input type="radio" name="edd_register"
                              value="after" <?php if ($settings->get('edd_register') == 'after') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_register_form_fields_after</code></label>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Register form button style', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label>
                    <input type="radio" name="edd_register_form_button_style"
                           value="default" <?php if ($settings->get('edd_register_form_button_style') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/default.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_register_form_button_style"
                           value="fullwidth" <?php if ($settings->get('edd_register_form_button_style') == 'fullwidth') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Fullwidth', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/fullwidth.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_register_form_button_style"
                           value="icon" <?php if ($settings->get('edd_register_form_button_style') == 'icon') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Icon', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/icon.png', NSL_ADMIN_PATH) ?>"/>
                </label><br>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Register layout', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label>
                    <input type="radio" name="edd_register_form_layout"
                           value="default" <?php if ($settings->get('edd_register_form_layout') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/default.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_register_form_layout"
                           value="below" <?php if ($settings->get('edd_register_form_layout') == 'below') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Below', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/below.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_register_form_layout"
                           value="below-separator" <?php if ($settings->get('edd_register_form_layout') == 'below-separator') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Below with separator', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/below-separator.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_register_form_layout"
                           value="above" <?php if ($settings->get('edd_register_form_layout') == 'above') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Above', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/above.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_register_form_layout"
                           value="above-separator" <?php if ($settings->get('edd_register_form_layout') == 'above-separator') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Above with separator', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/above-separator.png', NSL_ADMIN_PATH) ?>"/>
                </label><br>
            </fieldset>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Checkout form', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label><input type="radio" name="edd_checkout"
                              value="" <?php if ($settings->get('edd_checkout') == '') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('No Connect button in Checkout form', 'nextend-facebook-connect'); ?></span></label><br>
                <label><input type="radio" name="edd_checkout"
                              value="items_before" <?php if ($settings->get('edd_checkout') == 'items_before') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_cart_items_before</code></label><br>
                <label><input type="radio" name="edd_checkout"
                              value="before_purchase_form" <?php if ($settings->get('edd_checkout') == 'before_purchase_form') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_before_purchase_form</code></label><br>
                <label><input type="radio" name="edd_checkout"
                              value="form_top" <?php if ($settings->get('edd_checkout') == 'form_top') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_checkout_form_top</code></label><br>
                <label><input type="radio" name="edd_checkout"
                              value="before_email" <?php if ($settings->get('edd_checkout') == 'before_email') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_purchase_form_before_email</code></label><br>
                <label><input type="radio" name="edd_checkout"
                              value="before_submit" <?php if ($settings->get('edd_checkout') == 'before_submit') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_purchase_form_before_submit</code></label><br>
                <label><input type="radio" name="edd_checkout"
                              value="form_after" <?php if ($settings->get('edd_checkout') == 'form_after') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Connect button on', 'nextend-facebook-connect'); ?></span>
                    <code><?php _e('Action:'); ?>
                        edd_purchase_form_after_submit</code></label><br>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Checkout form button style', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label>
                    <input type="radio" name="edd_checkout_form_button_style"
                           value="default" <?php if ($settings->get('edd_checkout_form_button_style') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/default.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_checkout_form_button_style"
                           value="fullwidth" <?php if ($settings->get('edd_checkout_form_button_style') == 'fullwidth') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Fullwidth', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/fullwidth.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_checkout_form_button_style"
                           value="icon" <?php if ($settings->get('edd_checkout_form_button_style') == 'icon') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Icon', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/buttons/icon.png', NSL_ADMIN_PATH) ?>"/>
                </label><br>
            </fieldset>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Checkout layout', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label>
                    <input type="radio" name="edd_checkout_form_layout"
                           value="default" <?php if ($settings->get('edd_checkout_form_layout') == 'default') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Default', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/default.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_checkout_form_layout"
                           value="below" <?php if ($settings->get('edd_checkout_form_layout') == 'below') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Below', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/below.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_checkout_form_layout"
                           value="below-separator" <?php if ($settings->get('edd_checkout_form_layout') == 'below-separator') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Below with separator', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/below-separator.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_checkout_form_layout"
                           value="above" <?php if ($settings->get('edd_checkout_form_layout') == 'above') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Above', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/above.png', NSL_ADMIN_PATH) ?>"/>
                </label>
                <label>
                    <input type="radio" name="edd_checkout_form_layout"
                           value="above-separator" <?php if ($settings->get('edd_checkout_form_layout') == 'above-separator') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Above with separator', 'nextend-facebook-connect'); ?></span><br/>
                    <img src="<?php echo plugins_url('images/layouts/above-separator.png', NSL_ADMIN_PATH) ?>"/>
                </label><br>
            </fieldset>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Button alignment', 'nextend-facebook-connect'); ?></th>
        <td>
            <fieldset>
                <label><input type="radio" name="edd_form_button_align"
                              value="left" <?php if ($settings->get('edd_form_button_align') == 'left') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Left', 'nextend-facebook-connect'); ?></span></label><br>
                <label><input type="radio" name="edd_form_button_align"
                              value="center" <?php if ($settings->get('edd_form_button_align') == 'center') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
                    <span><?php _e('Center', 'nextend-facebook-connect'); ?></span></label><br>

                <label><input type="radio" name="edd_form_button_align"
                              value="right" <?php if ($settings->get('edd_form_button_align') == 'right') : ?> checked="checked" <?php endif; ?><?php echo $attr; ?>>
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
