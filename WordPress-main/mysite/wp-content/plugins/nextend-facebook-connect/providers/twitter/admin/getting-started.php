<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2023-04-13';

$provider = $this->getProvider();
?>

<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "X", "Consumer Key", "Consumer Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'X App'); ?></h2>


        <?php
        if ($provider->isV2Api()): ?>
            <ol>
                <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/portal/projects-and-apps" target="_blank">https://developer.twitter.com/en/portal/projects-and-apps</a>'); ?></li>
                <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'X'); ?></li>
                <li><?php _e('If you don\'t have a developer account yet, please apply one by filling all the required details! This is required for the next steps!', 'nextend-facebook-connect'); ?></li>
                <li><?php printf(__('Once your developer account is complete, navigate back to <b>%s</b> if you aren\'t already there!', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/portal/projects-and-apps" target="_blank">https://developer.twitter.com/en/portal/projects-and-apps</a>'); ?>
                <li><?php printf(__('Click on "<b>%s</b>"!', 'nextend-facebook-connect'), '+ Add Project'); ?></li>
                <li><?php printf(__('Name your project, and go through the basic setup. You’ll need to select your use case, give a description then click the %s button.', 'nextend-facebook-connect'), '"<b>Next</b>"'); ?></li>
                <li><?php printf(__('Choose the %1$s option for %2$s, then press %3$s!', 'nextend-facebook-connect'), '"<b>Production</b>"', '"<b>App environment</b>"', '"<b>Next</b>"'); ?></li>
                <li><?php printf(__('Into the %1$s field, enter a name for your App, then press %2$s again!', 'nextend-facebook-connect'), '"<b>App name</b>"', '"<b>Next</b>"'); ?></li>
                <li><?php printf(__('Click on the  the %1$s button on the bottom right corner.', 'nextend-facebook-connect'), '<b>App settings</b>'); ?></li>
                <li><?php printf(__('Scroll down and click on the %1$s button at %2$s.', 'nextend-facebook-connect'), '"<b>Set up</b>"', '"<b>User authentication settings</b>"'); ?></li>

                <li><?php printf(__('Choose the %1$s option at %2$s.', 'nextend-facebook-connect'), '"<b>Read</b>"', '"<b>App permission</b>"'); ?></li>
                <li><?php printf(__('Select the %1$s option for %2$s.', 'nextend-facebook-connect'), '"<b>Web App, Automated App or Bot</b>"', '"<b>Type of App</b>"'); ?></li>
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
                <li><?php printf(__('Enter your site\'s URL to the "<b>%1$s</b>" field: <b>%2$s</b>', 'nextend-facebook-connect'), 'Website URL', site_url()); ?></li>
                <li><?php printf(__('Click on %s.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
                <li><?php printf(__('Find your %1$s and %2$s. Copy and paste the credentials to the corresponding fields at %3$s and press %4$s.', 'nextend-facebook-connect'), '"<b>Client ID</b>"', '"<b>Client Secret</b>"', '<b>"Nextend Social Login > X > Settings"</b>', '<b>"' . __('Save Changes') . '"</b>'); ?></li>
                <li><?php printf(__('Verify the settings and enable the %1$s provider!', 'nextend-facebook-connect'), 'X'); ?></li>
            </ol>

            <p><?php printf(__('%1$s: %2$s has some %3$srate limits%4$s that you need to consider before you choose a plan. For example we use the %5$s endpoint, which have:', 'nextend-facebook-connect'), '<b>' . __('WARNING', 'nextend-facebook-connect') . '</b>', 'Twitter V2 API', '<a href="https://developer.twitter.com/en/docs/twitter-api/rate-limits" target="_blank">', '</a>', '"<b>GET_2_users_me</b>"'); ?></p>
            <ol>
                <li><?php printf(__('%1$s which means a user can make a fix number of requests every 24 hours.', 'nextend-facebook-connect'), '<b>' . __('User Rate Limit:', 'nextend-facebook-connect') . '</b>'); ?></li>
                <li><?php printf(__('%1$s which means your App can serve a fix number of requests in total every 30 days. This limit is shared between all users and applied to the entire project.', 'nextend-facebook-connect'), '<b>' . __('Effective 30-day limit:', 'nextend-facebook-connect') . '</b>'); ?></li>
            </ol>
            <p><?php printf(__('Once the rate limit is reached, people won\'t be able to login with %1$s until the limit resets. If you want to have a higher limit, you might need to upgrade to the %3$sBasic plan of %2$s %4$s.', 'nextend-facebook-connect'), 'X', 'Twitter API v2', '<a href="https://developer.twitter.com/en/portal/products/basic" target="_blank">', '</a>'); ?></p>

        <?php else: ?>

            <ol>
                <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/portal/projects-and-apps" target="_blank">https://developer.twitter.com/en/portal/projects-and-apps</a>'); ?></li>
                <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'X'); ?></li>
                <li><?php _e('If you don\'t have a developer account yet, please apply one by filling all the required details! This is required for the next steps!', 'nextend-facebook-connect'); ?></li>
                <li><?php printf(__('Once your developer account is complete, navigate back to <b>%s</b> if you aren\'t already there!', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/portal/projects-and-apps" target="_blank">https://developer.twitter.com/en/portal/projects-and-apps</a>'); ?>
                <li><?php printf(__('Click on "<b>%s</b>"!', 'nextend-facebook-connect'), '+ Add Project'); ?></li>
                <li><?php printf(__('Name your project, and go through the basic setup. You’ll need to select your use case, give a description then click the %s button.', 'nextend-facebook-connect'), '"<b>Next</b>"'); ?></li>
                <li><?php printf(__('Choose the %1$s option for %2$s, then press %3$s!', 'nextend-facebook-connect'), '"<b>Production</b>"', '"<b>App environment</b>"', '"<b>Next</b>"'); ?></li>
                <li><?php printf(__('Into the %1$s field, enter a name for your App, then press %2$s again!', 'nextend-facebook-connect'), '"<b>App name</b>"', '"<b>Next</b>"'); ?></li>
                <li><?php printf(__('You’ll find your API key and secret on this page. Copy and paste the "<b>%1$s</b>" and the "<b>%2$s</b>" to the corresponding fields at %3$s and press "<b>Save Changes</b>".', 'nextend-facebook-connect'), 'API Key', 'API Key Secret', 'Nextend Social Login > X > Settings'); ?></li>
                <li><?php printf(__('Go back to your X project and on the left side, under the "<b>%s</b>" section click on the name of your App.', 'nextend-facebook-connect'), 'Projects & Apps'); ?></li>
                <li><?php printf(__('Scroll down and click on the %1$s button at %2$s.', 'nextend-facebook-connect'), '"<b>Set up</b>"', '"<b>User authentication settings</b>"'); ?></li>
                <li><?php printf(__('Choose the %1$s option at %2$s.', 'nextend-facebook-connect'), '"<b>Read</b>"', '"<b>App permission</b>"'); ?></li>
                <li><?php printf(__('If you want to get the email address as well, then don’t forget to enable the %1$s option. In this case you also need to fill the "<b>%2$s</b>" and the "<b>%3$s</b>" fields with the corresponding URLs!', 'nextend-facebook-connect'), '"<b>Request email from users</b>"', 'Terms of service', 'Privacy policy'); ?></li>
                <li><?php printf(__('Select the %1$s option for %2$s.', 'nextend-facebook-connect'), '"<b>Web App, Automated App or Bot</b>"', '"<b>Type of App</b>"'); ?></li>
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
                <li><?php printf(__('Enter your site\'s URL to the "<b>%1$s</b>" field: <b>%2$s</b>', 'nextend-facebook-connect'), 'Website URL', site_url()); ?></li>
                <li><?php printf(__('Click on %s.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
                <li><?php printf(__('Go back to %1$s then <b>verify</b> and <b>enable</b> the %2$s provider!', 'nextend-facebook-connect'), 'Nextend Social Login', 'X'); ?></li>
            </ol>

        <?php endif; ?>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'X App'); ?></a>
    </div>
</div>