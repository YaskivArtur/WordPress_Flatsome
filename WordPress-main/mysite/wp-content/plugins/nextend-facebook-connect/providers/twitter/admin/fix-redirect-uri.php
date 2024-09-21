<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/portal/projects-and-apps" target="_blank">https://developer.twitter.com/en/portal/projects-and-apps</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'X (formerly Twitter)'); ?></li>
    <li><?php printf(__('On the left side, under the %s section click on the name of your App.', 'nextend-facebook-connect'), '"<b>Projects & Apps</b>"'); ?></li>
    <li><?php printf(__('Click on the %1$s button at %2$s.', 'nextend-facebook-connect'), '"<b>Edit</b>"', '"<b>User authentication settings</b>"'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Callback URI / Redirect URL</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php printf(__('Make sure the %1$s field contains the following URL: %2$s', 'nextend-facebook-connect'), '"<b>Website URL</b>"', '<b>' . site_url() . '</b>'); ?></li>
    <li><?php printf(__('Click on %s', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
</ol>