<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://console.developers.google.com/apis/" target="_blank">https://console.developers.google.com/apis/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'Google'); ?></li>
    <li><?php _e('Click on the "<b>Credentials</b>" in the left hand menu', 'nextend-facebook-connect'); ?></li>
    <li><?php printf(__('Under the "<b>OAuth 2.0 Client IDs</b>" section find your Client ID: <b>%s</b>', 'nextend-facebook-connect'), $provider->settings->get('client_id')); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Under the "<b>%1$s</b>" section click "<b>%2$s</b>" and add the following URL:', 'nextend-facebook-connect'), 'Authorised redirect URIs', 'Add URI');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Click on "<b>Save</b>"', 'nextend-facebook-connect'); ?></li>
</ol>