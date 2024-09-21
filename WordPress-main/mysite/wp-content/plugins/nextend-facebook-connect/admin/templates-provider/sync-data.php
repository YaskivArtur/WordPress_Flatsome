<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();

$settings = $provider->settings;

$hasSyncableProfileFields = $provider->hasSyncableProfileFields();

?>

<div class="nsl-admin-sub-content">

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" novalidate="novalidate">

        <?php wp_nonce_field('nextend-social-login'); ?>
        <input type="hidden" name="action" value="nextend-social-login"/>
        <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
        <input type="hidden" name="subview" value="sync-data"/>
        <input type="hidden" name="settings_saved" value="1"/>

        <?php if ($hasSyncableProfileFields): ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label>Sync profile</label></th>
                    <td>
                        <fieldset>
                            <label for="sync_profile_register">
                                <input name="sync_profile[register]" type="hidden" value="0"/>
                                <input name="sync_profile[register]" type="checkbox" id="sync_profile_register"
                                       value="1" <?php if ($settings->get('sync_profile/register')): ?> checked<?php endif; ?>/>
                                <?php _e('Register', 'nextend-facebook-connect'); ?>
                            </label>
                        </fieldset>
                        <fieldset>
                            <label for="sync_profile_login">
                                <input name="sync_profile[login]" type="hidden" value="0"/>
                                <input name="sync_profile[login]" type="checkbox" id="sync_profile_login"
                                       value="1" <?php if ($settings->get('sync_profile/login')): ?> checked<?php endif; ?>/>
                                <?php _e('Login', 'nextend-facebook-connect'); ?>
                            </label>
                        </fieldset>

                        <fieldset>
                            <label for="sync_profile_link">
                                <input name="sync_profile[link]" type="hidden" value="0"/>
                                <input name="sync_profile[link]" type="checkbox" id="sync_profile_link"
                                       value="1" <?php if ($settings->get('sync_profile/link')): ?> checked<?php endif; ?>/>
                                <?php _e('Link', 'nextend-facebook-connect'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <?php
        include dirname(__FILE__) . '/sync-data-pro.php';
        ?>
    </form>
</div>

