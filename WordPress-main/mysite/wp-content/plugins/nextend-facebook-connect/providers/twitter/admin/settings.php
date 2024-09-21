<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();

$settings = $provider->settings;
?>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('input[type=radio][name=api_version]').change(function () {
                if (this.value === '2') {
                    $('.twitter-v1-specific-field').css('display', 'none');
                    $('.twitter-v2-specific-field').css('display', '');
                } else {
                    $('.twitter-v1-specific-field').css('display', '');
                    $('.twitter-v2-specific-field').css('display', 'none');
                }
            });
        });
    })(jQuery);
</script>

<div class="nsl-admin-sub-content">
    <?php
    $this->renderSettingsHeader();
    ?>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" novalidate="novalidate">

        <?php wp_nonce_field('nextend-social-login'); ?>
        <input type="hidden" name="action" value="nextend-social-login"/>
        <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
        <input type="hidden" name="subview" value="settings"/>
        <input type="hidden" name="settings_saved" value="1"/>
        <input type="hidden" name="tested" id="tested" value="<?php echo esc_attr($settings->get('tested')); ?>"/>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php _e('API version', 'nextend-facebook-connect'); ?></th>
                <td>
                    <fieldset>
                        <label><input type="radio" name="api_version"
                                      value="1.1" <?php if ($settings->get('api_version') == '1.1') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('v1.1', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="api_version"
                                      value="2" <?php if ($settings->get('api_version') == '2') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('v2', 'nextend-facebook-connect'); ?></span></label><br>
                    </fieldset>
                </td>
            </tr>

            <?php
            $isV2Api    = false;
            $apiVersion = $settings->get('api_version');
            if ($apiVersion === '2') {
                $isV2Api = true;
            }
            ?>

            <tr class="twitter-v1-specific-field"<?php if ($isV2Api): ?> style="display:none;"<?php endif; ?>>
                <th scope="row"><label
                            for="consumer_key"><?php _e('API Key', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td>
                    <input name="consumer_key" type="text" id="consumer_key"
                           value="<?php echo esc_attr($settings->get('consumer_key')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-consumer_key"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'API Key', $this->getUrl()); ?></p>
                </td>
            </tr>
            <tr class="twitter-v1-specific-field"<?php if ($isV2Api): ?> style="display:none;"<?php endif; ?>>
                <th scope="row"><label
                            for="consumer_secret"><?php _e('API Key Secret', 'nextend-facebook-connect'); ?></label>
                </th>
                <td><input name="consumer_secret" type="text" id="consumer_secret"
                           value="<?php echo esc_attr($settings->get('consumer_secret')); ?>" class="regular-text"
                           style="width:40em;">
                </td>
            </tr>

            <tr class="twitter-v2-specific-field"<?php if (!$isV2Api): ?> style="display:none;"<?php endif; ?>>
                <th scope="row"><label
                            for="client_id"><?php _e('Client ID', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td>
                    <input name="client_id" type="text" id="client_id"
                           value="<?php echo esc_attr($settings->get('client_id')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Client ID', $this->getUrl()); ?></p>
                </td>
            </tr>
            <tr class="twitter-v2-specific-field"<?php if (!$isV2Api): ?> style="display:none;"<?php endif; ?>>
                <th scope="row"><label
                            for="client_secret"><?php _e('Client Secret', 'nextend-facebook-connect'); ?></label>
                </th>
                <td><input name="client_secret" type="text" id="client_secret"
                           value="<?php echo esc_attr($settings->get('client_secret')); ?>" class="regular-text"
                           style="width:40em;">
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="<?php _e('Save Changes'); ?>"></p>

        <?php
        $this->renderOtherSettings();
        ?>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php _e('Profile image size', 'nextend-facebook-connect'); ?></th>
                <td>
                    <fieldset>
                        <label><input type="radio" name="profile_image_size"
                                      value="mini" <?php if ($settings->get('profile_image_size') == 'mini') : ?> checked="checked" <?php endif; ?>>
                            <span>24x24</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="normal" <?php if ($settings->get('profile_image_size') == 'normal') : ?> checked="checked" <?php endif; ?>>
                            <span>48x48</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="bigger" <?php if ($settings->get('profile_image_size') == 'bigger') : ?> checked="checked" <?php endif; ?>>
                            <span>73x73</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="original" <?php if ($settings->get('profile_image_size') == 'original') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Original', 'nextend-facebook-connect'); ?></span></label><br>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>

        <?php
        $this->renderProSettings();
        ?>
    </form>
</div>
