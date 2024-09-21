<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-12-09';

$provider = $this->getProvider();
?>

<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Google", "Client ID", "Client secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Google App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://console.developers.google.com/apis/" target="_blank">https://console.developers.google.com/apis/</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Google'); ?></li>
            <li><?php printf(__('If you don\'t have a project yet, you\'ll need to create one. You can do this by clicking on the blue "<b>%1$s</b>" text on the right side!  ( If you already have a project, then in the top bar click on the name of your project instead, which will bring up a modal and click <b>"%2$s"</b>. )', 'nextend-facebook-connect'), 'Create Project', 'New Project'); ?></li>
            <li><?php printf(__('Name your project and then click on the "<b>%1$s</b>" button again!', 'nextend-facebook-connect'), 'Create'); ?></li>
            <li><?php _e('Once you have a project, you\'ll end up in the dashboard. ( If earlier you have already had a Project, then make sure you select the created project in the top bar! )', 'nextend-facebook-connect'); ?></li>
            <li><?php printf(__('Click the “<b>%1$s</b>” button on the left hand side.', 'nextend-facebook-connect'), 'OAuth consent screen'); ?></li>
            <li><?php printf(__('Choose a <b>%1$s</b> according to your needs and press "<b>%2$s</b>". If you want to enable the social login with %3$s for any users with a %3$s account, then pick the "%4$s" option!', 'nextend-facebook-connect'), 'User Type', 'Create', 'Google', 'External'); ?>
                <ul>
                    <li><?php printf(__('<b>Note:</b> We don\'t use sensitive or restricted scopes either. But if you will use this App for other purposes too, then you may need to go through an %1$s!', 'nextend-facebook-connect'), '<a href="https://support.google.com/cloud/answer/9110914" target="_blank">Independent security review</a>'); ?></li>
                </ul>
            </li>
            <li><?php printf(__('Enter a name for your App to the "<b>%1$s</b>" field, which will appear as the name of the app asking for consent.', 'nextend-facebook-connect'), 'App name'); ?></li>
            <li><?php printf(__('For the "<b>%1$s</b>" field, select an email address that users can use to contact you with questions about their consent.', 'nextend-facebook-connect'), 'User support email'); ?></li>
            <li><?php printf(__('Fill the %1$s field with the url of your homepage, probably: %2$s', 'nextend-facebook-connect'), '"<b>Application home page</b>"', '<b>' . str_replace(parse_url(site_url(), PHP_URL_PATH), "", site_url()) . '</b>'); ?></li>
            <li><?php printf(__('Enter your privacy policy and terms of service links into the %1$s and %2$s fields.', 'nextend-facebook-connect'), '"<b>Application privacy policy link</b>"', '"<b>Application terms of service link</b>"'); ?></li>
            <li><?php printf(__('Under the "<b>%1$s</b>" section press the "<b>%2$s</b>" button and  enter your domain name, probably: <b>%3$s</b> without subdomains!', 'nextend-facebook-connect'), 'Authorized domains', 'Add Domain', str_replace('www.', '', $_SERVER['HTTP_HOST'])); ?></li>
            <li><?php printf(__('At the "<b>%1$s</b>" section, enter an email address that %2$s can use to notify you about any changes to your project.', 'nextend-facebook-connect'), 'Developer contact information', 'Google'); ?></li>
            <li><?php printf(__('Press "<b>%1$s</b>" then press it again on the "%2$s", "%3$s" pages, too!', 'nextend-facebook-connect'), 'Save and Continue', 'Scopes', 'Test users'); ?></li>
            <li><?php printf(__('On the left side, click on the "<b>%1$s</b>" menu point, then click the "<b>%2$s</b>" button in the top bar.', 'nextend-facebook-connect'), 'Credentials', '+ Create Credentials') ?></li>
            <li><?php printf(__('Choose the "<b>%1$s</b>" option.', 'nextend-facebook-connect'), 'OAuth client ID'); ?></li>
            <li><?php printf(__('Select the "<b>%1$s</b>" under Application type.', 'nextend-facebook-connect'), 'Web application'); ?></li>
            <li><?php printf(__('Enter a "<b>%1$s</b>" for your OAuth client ID.', 'nextend-facebook-connect'), 'Name'); ?></li>
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
            <li><?php printf(__('Click on the "<b>%1$s</b>" button', 'nextend-facebook-connect'), 'Create'); ?></li>
            <li><?php printf(__('A modal should pop up with your credentials. If that doesn\'t happen, go to the %1$s in the left hand menu and select your app by clicking on its name and you\'ll be able to copy-paste the "<b>%2$s</b>" and "<b>%3$s</b>" from there.', 'nextend-facebook-connect'), 'Credentials', 'Client ID', 'Client Secret'); ?></li>
            <li><?php printf(__('Currently your App is in %1$s mode, so only limited number of people can use it. To allow this App for any user with a Google Account, click on the "%2$s" option on the left side, then click the "%3$s" button under the "%4$s" section, and press the "%5$s" button. ', 'nextend-facebook-connect'), 'Testing', '<b>OAuth consent screen</b>', '<b>PUBLISH APP</b>', '<b>Publishing status</b>', '<b>Confirm</b>'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Google App'); ?></a>
    </div>
</div>