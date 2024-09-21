=== Nextend Social Login and Register ===
Contributors: nextendweb
Tags: social login, facebook, google, twitter, x, linkedin, register, login, social, nextend facebook connect, social sign in
Donate link: https://www.facebook.com/nextendweb
Requires at least: 4.9
Tested up to: 6.4.3
Stable tag: 3.1.13
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

One click registration & login plugin for Facebook, Google, X (formerly Twitter) and more. Quick setup and easy configuration.

== Description ==

Nextend Social Login is a professional, easy to use and free WordPress plugin. It lets your visitors  register and login to your site using their social profiles (Facebook, Google, X (formerly Twitter), etc.) instead of forcing them to spend valuable time to fill out the default registration form. Besides that, they don't need to wait for validation emails or keep track of their username and password anymore.

>[Demo](https://try-nextend-social-login.nextendweb.com/wp-login.php)  |  [Tutorial videos](https://www.youtube.com/watch?v=buPTza2-6xc&list=PLSawiBnEUNftt3EDqnP2jIXeh6q0pZ5D8&index=1)  |  [Docs](https://nextendweb.com/nextend-social-login-docs/documentation/)  |  [Support](https://nextendweb.com/contact-us/nextend-social-login-support/)  |  [Pro Addon](https://nextendweb.com/social-login/)

[youtube https://www.youtube.com/watch?v=buPTza2-6xc]

Nextend Social Login seamlessly integrates with your existing WordPress login and registration form. Existing users can add or remove their social accounts at their WordPress profile page. A single user can attach as many social account as they want allowing them to log in with Facebook, Google or X (formerly Twitter).

#### Three popular providers: Facebook, Google and X (formerly Twitter)

Providers are the services which the visitors can use to register and log in to your site. Nextend Social Login allows your visitors to log in with their account from the most popular social networks: Facebook, Google and X (formerly Twitter).

#### Free version features

* One click registration and login via Facebook, Google and X (formerly Twitter)
* Your current users can easily connect their Facebook, Google or X (formerly Twitter) profiles with their account
* Social accounts are tied to a WordPress user account so every account can be accessed with and without social account
* You can define custom redirect URL after the registration (upon first login) using any of the social accounts.
* You can define custom redirect URL after each login with any of the enabled social accounts.
* Display Facebook, Google, X (formerly Twitter) profile picture as avatar
* Login widget and shortcodes
* Customizable designs to match your site
* Editable and translatable texts on the login buttons
* Very simple to setup and use
* Clean, user friendly UI
* Fast and helpful support

#### Additional features in the [Pro addon](https://nextendweb.com/social-login/)

* WooCommerce compatibility
* BuddyPress compatibility
* UserPro compatibility
* Ultimate Member compatibility
* Easy Digital Downloads compatibility
* Pro providers: LinkedIn, Amazon, VKontakte, WordPress.com, Yahoo, PayPal, Disqus, Apple, GitHub, Microsoft, Line, Discord, Slack, TikTok, Steam, Twitch, Kakao, Reddit, Spotify and more coming soon
* Configure whether email address should be asked on registration at each provider
* Configure whether username should be asked on registration at each provider
* Choose from icons or wide buttons
* Several login layouts
* Restrict specific user roles from using the social logins. (You can restrict different roles for each provider.)
* Assign specific user roles to the newly registered users who use any social login provider. (You can set different roles for each provider.)
* Show the name of the linked providers in the Users table

#### Usage

After you activated the plugin configure and enable the provider you want to use, then the plugin will automatically

* add the login buttons to the WordPress login page. See screenshot #1
* add the account linking buttons to the WordPress profile page. See screenshot #2

== Frequently Asked Questions ==

= Can I make my site GDPR compliant with Nextend Social Login installed? =
Sure, Nextend Social Login provides you the tools to make your site GDPR compliant. [Check out the Nextend Social Login GDPR documentation](https://nextendweb.com/nextend-social-login-docs/gdpr/) to learn more about the topic.

= 1. Where does Nextend Social Login display the social login buttons? =
The free version of Nextend Social Login displays the social login buttons automatically on the /wp-login.php's login form and all forms made using the wp_login_form action.
You can use Nextend Social Login's widget and shortcodes if you need to display the buttons anywhere. If you need to publish the login buttons in your theme, you can use the [PHP code](https://nextendweb.com/nextend-social-login-docs/theme-developer/).

= 2. How can I get the email address from the X (formerly Twitter) users? =
After you set up your APP go to the Settings tab and enter the URL of your Terms of Service and Privacy Policy page. Then hit the Update your settings button. Then go to the Permissions tab and check the "Request email addresses from users" under "Additional Permissions". [There's a documentation](https://nextendweb.com/nextend-social-login-docs/provider-twitter/#get-email) that explains the process with screenshots.

= 3. Why are random usernames generated? =
When a user tries to register with a social account, Nextend Social Login will try to generate a username from the name that comes from the provider. If this name contains special characters, then we won't be able to generate a username that is valid for WordPress, as WordPress doesn't allow special characters in usernames either.
For this reason we will need to generate a unique and random username for the registered account.

In the Pro Addon it's possible to ask an username if we can not generate a valid username, so you can avoid random usernames.

= 4. What should I do when I experience any problems? =
[Contact us](https://nextendweb.com/contact-us/nextend-social-login-support/) via email and explain the issue you have.

= 5. How can I translate the plugin? =
Find the `.pot` file at the /languages folder. From that you can start the translation process. [Drop us](https://nextendweb.com/contact-us/nextend-social-login-support/) the final `.po` and `.mo` files and we'll put them to the next releases.

= 6. I have a feature request... =
That's awesome! [Contact us](https://nextendweb.com/contact-us/nextend-social-login-support/) and let's discuss the details.

= 7. Does Nextend Social Login work with BuddyPress? =
Nextend Social Login Free version does not have BuddyPress specific settings and the login buttons will not appear there. However your users will still be able login and register at the normal WordPress login page. Then when logged in they can use every BuddyPress feature their current user role have access to.

Using the Pro Addon you can set where the login buttons should appear on the Register form and how they should look like.

== Installation ==

### Automatic installation

1. Search for Nextend Social Login through 'Plugins > Add New' interface.
2. Find the plugin box of Nextend Social Login and click on the 'Install Now' button.
3. Then activate the Nextend Social Login plugin.
4. Go to the 'Settings > Nextend' Social Connect to see the available providers.
5. Configure the provider you would like to use. (You'll find detailed instructions for each provider.)
6. Test the configuration then enable the provider.

### Manual installation

1. Download [Nextend Social Login](https://downloads.wordpress.org/plugin/nextend-facebook-connect.zip)
2. Upload Nextend Social Login through 'Plugins > Add New > Upload' interface or upload nextend-facebook-connect folder to the `/wp-content/plugins/` directory.
3. Activate the Nextend Social Login plugin through the 'Plugins' menu in WordPress.
4. Go to the 'Settings > Nextend Social Login' to see the available providers.
5. Configure the provider you would like to use. (You'll find detailed instructions for each provider.)
6. Test the configuration then enable the provider.


== Screenshots ==

1. Nextend Social Login and Register on the main WP login page
2. Nextend Social Login and Register in the profile page for account linking
3. The Providers page with the available providers and their states.
4. The Settings page of the Facebook provider.

== Changelog ==

= 3.1.13 =
* Security fix: Error message sanitization.


= 3.1.12 =
* Fix: We will now delete the user avatar if the social media account which was used to set the avatar for the user gets unlinked.
* Fix: Fixed an issue where an error was thrown whenever the download_url() function would return a WP_Error object during the unlinking process.
* Fix: Fixed an issue where a plugin/theme force update check could cause a fatal error.
* Feature: [Global Settings](https://nextendweb.com/nextend-social-login-docs/global-settings/) Added new "Unsupported WebView behaviour" option which determines what should happen to the social login buttons in WebView environments.
* Improvement: Removed custom download_url() method, instead we will use the standard WordPress version, as it now works as expected.
* Improvement: [Google](https://nextendweb.com/nextend-social-login-docs/provider-google/#configuration/) Getting Started updated.
* Improvement: [Facebook](https://nextendweb.com/nextend-social-login-docs/provider-facebook/) API call version updated from v13.0 to v19.0.

* PRO: Fix: Updated our code to comply with the fact that func_get_args() no longer reports the original value passed to a parameter, and instead returns the current value.
* PRO: Improvement: Removed no longer needed "Checkout for WooCommerce" integration, and instead added the following actions at the billing form: woocommerce_before_checkout_shipping_form, woocommerce_after_checkout_shipping_form
* PRO: New provider: [Spotify](https://nextendweb.com/nextend-social-login-docs/provider-spotify/)


= 3.1.11 =
* Fix: The default "Terms and conditions" text can now be translated with our language files.
* Improvement: [Facebook Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#configuration) Update
* Improvement: [Shortcode](https://nextendweb.com/nextend-social-login-docs/theme-developer/#shortcode) - customlabel shortcode parameter added, which can be used for specifying a custom label for the connect state of the social buttons rendered by the currently used shortcode. Also you can use the {{providerName}} placeholder to display the name of the given provider in your custom text.

* PRO: Improvement: Register form - Password template HTML has been updated according to the latest WordPress default Forget Password form structure.
* PRO: Improvement: [Custom Actions](https://nextendweb.com/nextend-social-login-docs/global-settings-custom-actions/) - Full Site Editing compatibility: the Custom Actions feature now attempts to render the buttons on any actions which are fired after the "init" action. ( Earlier, we tried only on the actions fired after the "login_head" and "wp_head" actions, so the buttons weren't rendered on the actions that were called within the Block Theme widgets. )
* PRO: Improvement: [PayPal Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-paypal/#configuration) Update
* PRO: Improvement: [PayPal](https://nextendweb.com/nextend-social-login-docs/provider-paypal/) integration updated according to the new PayPal endpoint and API changes.
* PRO: New provider: [Reddit](https://nextendweb.com/nextend-social-login-docs/provider-reddit/)


= 3.1.10 =
* Fix: Fixed a crash during login when "Support login restrictions" is enabled while WP 2FA plugin is installed.
* Fix: Fixed a PHP Warning issue related to Ultimate Member when "Support login restrictions" is enabled.
* Fix: Indeed Ultimate Affiliate Pro compatibility - registration with social login didn't award rewards to the affiliates
* Fix: Terms and conditions	text could not be translated
* Improvement: Twitter provider has been rebranded as X
* Improvement: [Facebook Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#configuration) Update

* PRO: Fix: Added the now needed Content-Type: application/x-www-form-urlencoded header to the TikTok access token endpoint request.
* PRO: Fix: Updated our code to use "Sign In with LinkedIn using OpenID Connect" instead of the old deprecated "Sign in with LinkedIn" product.
* PRO: Improvement: [LinkedIn Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-linkedin/) Update
* PRO: Improvement: Added new "edd_before_purchase_form" action for our Easy Digital Downloads integration.
* PRO: New provider: [Kakao](https://nextendweb.com/nextend-social-login-docs/provider-kakao/)


= 3.1.9 =
* Feature: Facebook provider - Added option to choose a custom profile image size.
* Fix: renderButtonsWithContainerAndTitle() method will no longer render the connect buttons for the logged in users
* Fix: renderLinkAndUnlinkButtons() method will no longer render the link and unlink buttons for the logged out users
* Improvement: Developers can now use the "nsl_autolink_error_redirect_url" filter to override the redirect URL when auto-linking fails.

* PRO: Feature: TikTok provider – [Sync data fields](https://nextendweb.com/nextend-social-login-docs/provider-tiktok/#sync_data) added.
* PRO: Improvement: TikTok provider – new OAuth2 endpoints and redirect_url introduced.
* PRO: Improvement: [TikTok Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-tiktok/#configuration) Update


= 3.1.8 =
* Fix: In some cases we opened the login popup window in an incorrect position.
* Fix: Some language files had wrong language codes in their names. For example es_LA had been renamed to es_ES and zh_ZH to zh_CN.
* Improvement: [Facebook Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#configuration) Update
* Improvement: The HTML of the "Register" submit button in our Register flow can now be overridden over template files.
* Improvement: Registration – if the provider has account select screen, then the user will be able to select another account if an error prevented the registration.
* Improvement: Auto-linking security improvements.
* Improvement: Google provider – new OAuth2 authorization and token endpoints.
* Improvement: Developers can now use the "nsl_required_capability" filter to override the default capability ( manage_options ) that is required to be able to access the backend of Nextend Social Login.
* Improvement: Japanese translation added.
* Improvement: Hebrew translation added.
* Feature: Twitter – option to choose whether we should use v1.1 or the v2.0 OAuth flow and credentials. ( The different versions have different App creation guides depending on the selected value. )

* PRO: Fix: Yahoo provider – The redirect URL suggestion will now contain the complete Yahoo endpoint URL, as new Apps now require an exact match ( earlier the URLs with query strings weren't accepted ).
* PRO: Improvement: Developers can now add extra validation logics for the "Ask E-mail/Password/Username on registration" features by using the "nsl_validate_extra_input_password_errors", "nsl_validate_extra_input_username_errors" and "nsl_validate_extra_input_username_errors" filters.
* PRO: Improvement: The HTML of the "Ask E-mail/Password/Username on registration" features can now be overridden over template files.
* PRO: Feature: Line provider – added an option to allow the [auto login](https://nextendweb.com/nextend-social-login-docs/provider-line/#allow-auto-login).
* PRO: Feature: BuddyPress registration integration – so BuddyPress can send its registration notifications upon registration with social login, too.
* PRO: Feature: Discord provider – v10 API support
* PRO: Feature: Discord provider – [Sync data fields](https://nextendweb.com/nextend-social-login-docs/provider-discord/#sync_data) added.


= 3.1.7 =
* Fix: The !important CSS rules can cause problems for AMP validators, so we will use more specific selectors instead.
* Fix: Some object cache might caused a PHP warning when the login happened with third party login forms.
* Fix: There was a JavaScript error in WordPress-like login forms that didn't fire any login specific actions
* Improvement: [Twitter Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-twitter/#configuration) Update
* Improvement: Support login restrictions - BuddyPress activation integration - in this case the actual registration is done by BuddyPress, not Nextend Social Login.
* Improvement: Developers can now define the NSL_DISABLE_IN_AMP_REQUESTS constant, to disable the social buttons and its assets in the AMP requests.
* Improvement: Developers can now use the "nsl_disabled_register_redirect_url" and "nsl_disabled_login_redirect_url" filters to override the redirects in most cases when the registration or login with social login fails for some reason
* Improvement: Introduced some public PHP functions that developers can use to get some user data outside of our normal flow
* Improvement: Avatar storing improvements
* Feature: Option to allow the profile syncing separately for the registration, login and link actions

* PRO: Fix: Apple provider - There was an encoding problem with the scope parameter of the Apple authorization URL.
* PRO: Improvement: Developers can now override the GitHub and Disqus client credentials over the "nsl_disqus_api_key" / "nsl_disqus_api_secret" and "nsl_github_client_id" / "nsl_github_client_secret" filters. ( Can be useful for multi sites and multi language sites, as the App of these providers support only a single redirect URL. )
* PRO: Improvement: [Microsoft Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-microsoft/#configuration) Update
* PRO: New provider: [Twitch](https://nextendweb.com/nextend-social-login-docs/provider-twitch/)


= 3.1.6 =
* Fix: Google provider - Google OAuth no longer supports any kind of WebViews, so the Google button will be hidden in all WebView environments.
* Fix: [User selected language](https://wordpress.org/support/article/users-your-profile-screen/#your-profile-and-personal-options) in the backend didn't load our translations in certain cases.
* Improvement: We will trigger the "wp_login_failed" action when the login with social login fails, so the login history plugins could track the failed login attempts happened with social login, too.
* Improvement: The unlinked social media identifier will be now available from the "nsl_unlink_user" action parameters.

* PRO: Fix: Microsoft provider - ErrorInsufficientPermissionsInAccessToken error when using the Personal Audience option.
* PRO: Fix: Steam provider - Error when pressing the "Got it" button on the "Fix OAuth Redirect URIs" page while the Steam provider was enabled.
* PRO: Fix: TikTok provider - Invalid request parameters error
* PRO: Improvement: TikTok provider - [Getting Started Update](https://nextendweb.com/nextend-social-login-docs/provider-tiktok/)
* PRO: Feature: TikTok provider – [button skin](https://nextendweb.com/nextend-social-login-docs/provider-tiktok/#guidelines) selector added.


= 3.1.5 =
* Fix: The Jetpack Boost plugin stopped our authentication flow
* Fix: The "WP 2FA" plugin could prevent the login with social login even if our "Support login restrictions" feature was disabled
* Fix: PHP error when other plugins tried to force WordPress to update the plugin update transients ( update_plugins ) with null parameter
* Fix: The "Page Transitions" feature of Elementor Pro opened our links in the opener window, causing a redirect in both the popup and the popup opener window
* Improvement: Introducing the "nsl_connect_button_custom_attributes", "nsl_unlink_button_custom_attributes", "nsl_link_button_custom_attributes" filters to add extra attributes on our button links
* Improvement: Avatar storing - We won't try to copy the avatar into our avatar folder if the same file is already there
* Improvement: Avatar storing - If the earlier stored avatar file doesn't exist, we will delete the associated attachment data the next time the user logs in
* Improvement: Facebook provider - Graph API version of the used endpoints have been updated from v7.0 to v13.0

* PRO: Fix: There was a JavaScript error on the WordPress default Lost Password page when separator layouts were used
* PRO: Fix: We didn't display the intended warning when the Free version was not compatible with the Pro Addon.
* PRO: Fix: Apple provider - PHP warning when verifying the provider settings for the first time
* PRO: Fix: WebView - we will no longer display the separator either, if we can not display any social buttons because of the embedded browser environment.
* PRO: Fix: TikTok provider - the registration and login didn't work as TikTok modified their endpoints, the request method and the response of their API.
* PRO: Improvement: Yahoo provider - Getting Started Update
* PRO: Improvement: PayPal provider - Getting Started Update
* PRO: Feature: BuddyPress - option to show/hide the [Social Accounts tab](https://nextendweb.com/nextend-social-login-docs/global-settings-buddypress/#social-accounts-tab)
* PRO: Feature: BuddyPress - Introducing "nsl_bp_social_accounts_tab_slug" filter to modify the slug of the BuddyPress - Social Accounts tab.
* PRO: Feature: Line - [Initial Login method](https://nextendweb.com/nextend-social-login-docs/provider-line/#initial-login-method) and [Force initial login method](https://nextendweb.com/nextend-social-login-docs/provider-line/#force-initial-login-method) settings.
* PRO: New provider: [Steam](https://nextendweb.com/nextend-social-login-docs/provider-steam/)


= 3.1.4 =
* Fix: PHP Warning on redirects, when the "Host" header is not sent by the browser ( e.g when an action happens with CLI )
* Fix: The redirect parameter of the shortcode didn't work in AJAX requests
* Fix: Provider order modification didn't work in WordPress 5.9
* Improvement: [Google Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-google/#configuration) Update
* Improvement: [Twitter Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-Twitter/#configuration) Update
* Feature: Option to attempt to [Bypass cache on redirect](https://nextendweb.com/nextend-social-login-docs/global-settings/#bypass-cache) by adding a GET parameter to the redirect URL

* PRO: New provider: [TikTok](https://nextendweb.com/nextend-social-login-docs/provider-tiktok/)


= 3.1.3 =
* Fix: Database error on clean install
* Improvement: [WPML Redirect URL compatibility](https://nextendweb.com/nextend-social-login-docs/how-to-make-nextend-social-login-compatible-with-wpml/)
* Improvement: Updated Italian translation files
* Improvement: [Twitter Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-twitter/#configuration) Update
* Improvement: Facebook provider - Facebook popup height has been increased, so everything will be visible during Authorization
* Improvement: Facebook provider - The Facebook button will no longer be visible in Android WebView, as [Facebook has deprecated the support for Facebook Login Authentication on Android WebView](https://developers.facebook.com/blog/post/2021/06/28/deprecating-support-fb-login-authentication-android-embedded-browsers/). In the WebView of the Facebook and Instagram Android Apps the buttons will still be visible, as Facebook currently allows the authentication over these Android WebViews.
* Feature: Option to control the appearance of the [Redirect Overlay](https://nextendweb.com/nextend-social-login-docs/global-settings/#redirect-overlay)

* PRO: New provider: [Slack](https://nextendweb.com/nextend-social-login-docs/provider-slack/)


= 3.1.2 =
* Improvement: If there is a slow server or a poor internet connection, the redirect after the authentication with social login might take some time. During this time we will display a loading spinner, so the visitor will know that something is about to happen.

* PRO: New provider: [Discord](https://nextendweb.com/nextend-social-login-docs/provider-discord/)
* PRO: Fix: The Microsoft provider used a wrong redirect url, when the Frontend and the Backend URL of the site were different.
* PRO: Improvement: New options for the Line provider: [Force reauthorization on each login](https://nextendweb.com/nextend-social-login-docs/provider-line/#force-reauth) and [Add LINE Official Account as a friend](https://nextendweb.com/nextend-social-login-docs/provider-line/#add-friend)
* PRO: Improvement: New options for the Microsoft provider: [Audience](https://nextendweb.com/nextend-social-login-docs/provider-microsoft/#audience) and [Authorization Prompt](https://nextendweb.com/nextend-social-login-docs/provider-microsoft/#auth-prompt)
* PRO: Feature: Integration for Easy Digital Downloads [Checkout](https://nextendweb.com/nextend-social-login-docs/global-settings-easy-digital-downloads/#edd-checkout-form) form.
* PRO: Feature: Display social buttons with layouts on [Custom Actions](https://nextendweb.com/nextend-social-login-docs/global-settings-custom-actions/)


= 3.1.1 =
* Improvement: string paths from the language files have been removed.

* PRO: Improvement: VKontakte provider – we will use the API version 5.131 for the endpoints, as API version 5.74 is deprecated.
* PRO: Feature: Easy Digital Downloads login and register form support.


= 3.1.0 =
* Fix: Display error message for logged out users, when they try to login with a social media account that's email address matches with a WordPress account email address, that has a linked provider from the same kind.
* Fix: WooRewards will be able to generate points on registration with Nextend Social Login
* Improvement: nsl_already_linked_error_message filter added to modify the error message when a WordPress account with the same email address has another social media account already linked
* Improvement: Separate autologin from registerComplete function
* Improvement: nsl_autologin_priority filter added to control the priority of the autologin after the registration with Nextend Social Login
* Improvement: [Facebook Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#configuration) Update
* Improvement: [WPML](https://nextendweb.com/nextend-social-login-docs/how-to-make-nextend-social-login-compatible-with-wpml/) compatibility

* PRO: New provider: [Microsoft](https://nextendweb.com/nextend-social-login-docs/provider-microsoft/)
* PRO: New provider: [Line](https://nextendweb.com/nextend-social-login-docs/provider-line/)
* PRO: Improvement: Optimized Light and Dark SVG for Apple
* PRO: Improvement: [Apple Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-apple/#configuration) Update
* PRO: Improvement: [PayPal Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-paypal/#configuration) Update
* PRO: Improvement: New [Facebook Sync data](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#sync_data) field: Quote ( requires user_likes permission )
* PRO: Feature: [BuddyPress](https://nextendweb.com/nextend-social-login-docs/global-settings-buddypress/) Layout options added for registration form


= 3.0.29 =
* Fix: We added clear: both; on .nsl-container to make floated elements before the buttons not to mess up the layout.
* Fix: Jetpack removed the "Register" button in our register flow when the registration was handled over the WordPress default login page.
* Improvement: The social buttons with the Default style will try to go as wide as the longest button, if there is enough space in the social button container element.
* Feature: Fullwidth style for the social buttons.
* Summer Sale offer


= 3.0.28 =
* Fix: We didn't display the disabled registration notification when the "OAuth redirect uri proxy page" feature was used.
* Fix: Google provider - Social button didn't appear in Opera Mini and iOS Opera Touch.
* Fix: WordPress couldn't download the avatars coming from the social media when the avatar URL was too long.
* Fix: Our styles were missing from pages with AMP mode of "AMP for WP".
* Fix: There was an AMP validation error as earlier we didn't load our styles into the unique style tag with the "amp-custom" attribute.
* Fix: Database - There was a database error on MySQL 8.0.17 and above, as the display width attribute has been deprecated for integer data types.
* Fix: Database - Default values of register_date, login_date and link_date have been changed from "0000-00-00 00:00:00" to NULL, since the old value could cause a database error when we tried to make database structure modifications in databases when NO_ZERO_DATE, NO_ZERO_IN_DATE values are set in sql_mode.
* Improvement: Developers can now pass false value for the [nsl_disabled_register_error_message](https://nextendweb.com/nextend-social-login-docs/backend-developer/#disabled-reg-message-override) filter for turning off our registration disabled notification.
* Improvement: Google provider - The Google button will no longer be hidden in Line App WebView, as Google allows the authentication over the WebView of this App.
* Improvement: Developers can now use the [nsl_unlink_user](https://nextendweb.com/nextend-social-login-docs/backend-developer/#unlink-user) action to run custom function when a user unlinks the social media account from the WordPress account.
* Improvement: [Twitter Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-twitter/#configuration) Update
* Improvement: [Google Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-google/#configuration) Update
* Improvement: [Facebook Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#configuration) Update
* Improvement: The context "Register form submit button label" has been added to the Register button appearing in our register flow. So it can now be translated with language files separately.
* Improvement: On the frontend we will use native JavaScript instead of jQuery.

* PRO: Fix: [Linkedin](https://nextendweb.com/nextend-social-login-docs/provider-linkedin/) provider didn't store the first name and last name, if the account didn't have profile with English as either primary language or secondary language.
* PRO: Fix: Our integration for "Checkout for WooCommerce" didn't work with their most recent versions.
* PRO: Improvement: [Apple Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-apple/#configuration) Update


= 3.0.27 =
* Fix: Ultimate Member prevents our registration when we need to ask extra information before the registration.
* Fix: post_mime_type PHP notice.
* Improvement: Italian translation files added.
* Improvement: Notice handling logic improvements.
* Improvement: [Twitter Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-twitter/#configuration) Update
* Improvement: [Facebook Getting Started](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#configuration) Update
* Improvement: [Facebook Warning](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#limitations) for App replacing
* Improvement: Google provider – using OAuth2 v2 endpoint
* Improvement: [2 new filters](https://nextendweb.com/nextend-social-login-docs/backend-developer/#disabled-login-redirect-override) for customizing the redirect url and error message when login is disabled.
* Improvement: Database – new column "social_users_id" for Primary Key
* Improvement: PHP 8.0 compatibility
* Improvement: We will override the WordPress default avatar using the “pre_get_avatar_data” filter instead of “get_avatar” filter.

* PRO: Fix: [Apple](https://nextendweb.com/nextend-social-login-docs/provider-apple/#guidelines) provider – Logo overlaps the box shadow of the light button skin
* PRO: Improvement: New [Google Sync data](https://nextendweb.com/nextend-social-login-docs/provider-google/#sync_data) fields: Genders and Locations ( requires People API )
* PRO: Removed: [Google Sync data](https://nextendweb.com/nextend-social-login-docs/provider-google/#sync_data) fields: Gender, Profile link, Taglines and Residences


= 3.0.26 =
* Fix: PHP notice by AMP plugin
* Fix: The orphan thumbnail sizes generated from the avatars will be deleted when the earlier stored avatar has been overridden by the provider.
* Improvement: Hashed filenames for avatars to avoid tracking back the user avatars over the URL by User ID.
* Improvement: Avatars are now stored in the dedicated folder called nsl_avatars. The name of the folder can be modified with the NSL_AVATARS_FOLDER constant.
* Improvement: Ultimate Member – the registration date will appear in the info popup for users registered by social login.
* Improvement: [2 new filters](https://nextendweb.com/nextend-social-login-docs/backend-developer/#auth-url-args) for developers
* Improvement: [nsl_disabled_register_error_message](https://nextendweb.com/nextend-social-login-docs/backend-developer/#disabled-reg-message-override) filter will also work when the OAuth flow is being handled over the default login page.
* Improvement: Facebook provider – Getting Started update.
* Feature: [Custom label](https://nextendweb.com/nextend-social-login-docs/global-settings/#custom-register-label) for social buttons in register forms and new [shortcode parameter](https://nextendweb.com/nextend-social-login-docs/theme-developer/#shortcode) to use the register labels.
* Black friday offer

* PRO: Improvement: WooCommerce – [Email template for registration](https://nextendweb.com/nextend-social-login-docs/global-settings-woocommerce/#email-template) setting defines the email template that the registration notification will use when the registration happens with social login. Earlier this was a hidden and built in feature of the [Registration notification sent to](https://nextendweb.com/nextend-social-login-docs/global-settings/#pro-settings) setting.


= 3.0.25 =
* Fix: WishList Member plugin prevented the strong redirects of Nextend Social Login.
* Fix: Connect button – URL encoding in the redirect parameter to keep the URL parameters after login.
* Fix: JavaScript errors on JavaScript minification with WP Hide & Security Enhancer
* Fix: Delayed login caused by image optimization plugins, like EWWW Image Optimizer.
* Fix: Social button styles will be loaded in AMP Reader template pages, too.
* Improvement: Reactivate renamed to Analyze & Repair
* Improvement: Notification at the backend, when there is at least one configured provider however it is not enabled.
* Improvement: Facebook provider – updated steps and new video guide in the Getting Started section.
* Improvement: Facebook provider – new default button color.
* Improvement: Facebook provider – we will use Graph API v7.0 for the endpoints.
* Improvement: Google provider – updated steps and new video guide in the Getting Started section.
* Improvement: Allow [redirect](https://nextendweb.com/nextend-social-login-docs/backend-developer/#disabled-reg-redirect-override) and [error message](https://nextendweb.com/nextend-social-login-docs/backend-developer/#disabled-reg-message-override) overrides when registration is disabled.
* Improvement: The Google button will no longer be hidden for Instagram, Twitter and Facebook App WebViews, as Google allows the authentication over the WebView of these Apps.
* Feature: Facebook provider – [button skin](https://nextendweb.com/nextend-social-login-docs/provider-facebook/#guidelines) selector added.

* PRO: New provider: [GitHub](https://nextendweb.com/nextend-social-login-docs/provider-github/)
* PRO: Improvement: WooCommerce Billing – Default with separator layout to display the buttons on the place where the action is fired.
* PRO: Improvement: LinkedIn provider – updated steps in Getting Started section.
* PRO: Improvement: Pro Addon PHP 7.0 version check to load Pro Addon only on compatible PHP versions.
* PRO: Improvement: MemberPress Login form – option to hide the social buttons.
* PRO: Feature: [Show linked providers](https://nextendweb.com/nextend-social-login-docs/global-settings/#linked-providers) – Option to display the name of the providers which are linked to a WordPress account.


= 3.0.24 =
* Fix: BuddyPress 6.0 compatibility fix.

= 3.0.23 =
* Fix: PHP error when BuddyPress – Activity is disabled.
* Fix: [Support login restrictions](https://nextendweb.com/nextend-social-login-docs/global-settings/) – delete persistent data when the registration was prevented by a third party plugin
* Fix: Twitter – 48×48 avatars can be stored again

* PRO: Fix: Longer Apple JWT token expiry.
* PRO: Improvement: compatibility with the forms generated by the plugin “Checkout for WooCommerce”
* PRO: Improvement: [Apple provider button skins](https://nextendweb.com/nextend-social-login-docs/provider-apple/#guidelines) to comply with [Human Interface Guidelines](https://developer.apple.com/design/human-interface-guidelines/sign-in-with-apple/overview/buttons/)

= 3.0.22 =
* Fix: Updated language files

* PRO: Fix: Plugin could not be activated because it triggered a fatal error. - Fix for the problem: Deactivate and delete "Nextend Social Login Pro Addon" plugin with version 3.0.21, then activate the version 3.0.22.

= 3.0.21 =
* Compatibility: PHP 7 or greater is required for the new version!.
* Fix: Icon style - Icons will be wrapped into multiple lines when there is no more room for them.
* Fix: Social buttons will no longer be distorted when the page is translated with Google translator.
* Fix: WPLMS theme - social button style and duplicated social buttons.
* Fix: WP Rocket - compatibility with combine JavaScript feature.
* Fix: Popup target window when the social buttons appear in certain modals.
* Fix: Ultimate Member avatars with social registration.
* Fix: Avatar will be synchronized again, if the attachment was already set, but the file doesn't exist.
* Improvement: Database - Register, Link and Login date will be stored in database.
* Improvement: Improvement: Google - Light skin will be the default button skin.
* Improvement:  Pages which are being used by other plugins will be filtered out from [Page for register flow and OAuth redirect uri proxy page](https://nextendweb.com/nextend-social-login-docs/global-settings/)
* Improvement: The Getting Started sections are updated with new steps.
* Improvement: New registrations happening with social login will also be displayed in the BuddyPress - Activity log.
* Improvement: Shortcode [provider](https://nextendweb.com/nextend-social-login-docs/theme-developer/#shortcode) parameter will also define the visibility of the link and unlink buttons.
* Feature: Option to disable the Google account select prompt on each login.
* For developers: The provider instance can now be accessed over "nsl_registration_form_start" and "nsl_registration_form_end" actions

* PRO: New provider: [Apple](https://nextendweb.com/nextend-social-login-docs/provider-apple/)
* PRO: Fix: Plugin update error - WordPress cached the wrong update url.
* PRO: Fix: Social button layouts in Theme My Login forms.
* PRO: Fix: Ultimate Member and [Support login restrictions](https://nextendweb.com/nextend-social-login-docs/login-restriction/) - Users will be redirected to the Ultimate Member login page after the registration.
* PRO: Improvement: Yahoo new endpoint and app creation guide. New and deprecated [Sync data](https://nextendweb.com/nextend-social-login-docs/provider-yahoo/#sync_data) fields.
* PRO: Improvement: WooCommerce automatically generated password feature support when [Registration notification sent to](https://nextendweb.com/nextend-social-login-docs/global-settings/#pro-settings) is set to User or User and Admin.

= 3.0.20 =
* Fix: Ultimate Member Auto Approve + Support Login Restriction - Avatars will be synchronized.
* Fix: Error message didn't show up when an "OAuth redirect uri proxy page" was selected.
* Feature: Shortcode - [Grid style](https://nextendweb.com/nextend-social-login-docs/theme-developer/#shortcode)
* Feature: German translation files added.
* Improvement: redirect_to URL parameter will be stronger than current page url
* Improvement: [nsl_registration_user_data](https://nextendweb.com/nextend-social-login-docs/backend-developer/) filter can now be also used
  for [preventing the registration](https://nextendweb.com/nextend-social-login-docs/backend-developer/#prevent-registration).

* PRO: Improvement: PayPal updated endpoints. New Sync Data field: PayPal account ID (payer ID)
* PRO: Removed: [PayPal Sync Data](https://nextendweb.com/nextend-social-login-docs/provider-paypal/#sync_data) fields: Date of birth, Age range, Phone, Account type, Account creation date, Time zone, Locale, Language.

= 3.0.19 =
* Fix: Shortcode - align parameter notice
* Fix: Social buttons didn't show up properly when the action where we check jQuery was called multiple times.
* Improvement: Google Select account modal before the login.

* PRO: Fix: Jetpack - display our social buttons on custom Jetpack comment form
* PRO: Feature: BuddyPress - option to disable the social buttons on the action: bp_sidebar_login_form
* PRO: Improvement: LinkedIn v2 REST API update. Getting Started section updated with the new App creation steps.
* PRO: Removed: [LinkedIn Sync data](https://nextendweb.com/nextend-social-login-docs/provider-linkedin/#sync_data)

= 3.0.18 =
* Fix:  _nsl is not defined error
* Fix:  The shortcode of [Page for register flow](https://nextendweb.com/nextend-social-login-docs/global-settings/) will be rendered into the correct position.
* Fix: Google - G+ logo is replaced with simple G logo.

* PRO: Fix: [Target window](https://nextendweb.com/nextend-social-login-docs/global-settings/#pro-settings) will open the auth window of the provider in the selected way again.
* PRO: Fix: Update notice when the Free and Pro Addon are not compatible.
* PRO: Feature: Social buttons for BuddyPress - Login widget
* PRO: Feature: Option to disable the WordPress Toolbar on the front-end for some roles.
* PRO: New provider: [Yahoo](https://nextendweb.com/nextend-social-login-docs/provider-yahoo/)
* PRO: Note: We had plans to implement the [Instagram](https://nextendweb.com/nextend-social-login-docs/provider-instagram/) provider. Unfortunately we need to change our mind, since the Instagram API will become deprecated soon!

= 3.0.17 =
* Fix: Activation fix on certain sub-domains.

= 3.0.16 =
* Fix: NSL Avatars used to override the specified BuddyPress avatars.
* Fix: 500 error when the Extended Profiles setting is disabled in BuddyPress.
* Fix: By default, users won’t be redirected to the homepage after unlinking their accounts, instead will be redirected back to the page, where the unlink action has happened.
* Fix: Nextend Social Login will now wait for jQuery before positioning the social buttons.
* Fix: Getting Started section of some providers are updated with the new App creation steps.
* Feature: Russian translation added.
* Feature: [Display avatars in “All media items”](https://nextendweb.com/nextend-social-login-docs/global-settings/) – Images can now load faster in Media Library – Grid view, when this option is enabled.
* Feature: Social button alignment option for WordPress forms, shortcode and widget.
* Feature: [Membership](https://nextendweb.com/nextend-social-login-docs/global-settings/) – is now available in the FREE version and provides support for WordPress default membership as well.
* Feature: new hook allows overriding the username and email before registration - [nsl_registration_user_data](https://nextendweb.com/nextend-social-login-docs/backend-developer/)
* Facebook – Graph API v3.2 - old API-s may require [API Call version upgrade](https://nextendweb.com/nextend-social-login-docs/facebook-upgrade-api-call/)!
* Old Nextend Facebook/Twitter/Google Connect compatibility has been removed.
* Social Buttons use flex-box layout now.


* PRO: Fix: Internet Explorer – Pro Addon activation.
* PRO: Fix: Facebook provider – Sync data: Gender, Profile link, Age range can be retrieved again.
* PRO: Feature: Social button alignment option for WooCommerce, Comment, BuddyPress, MemberPress, UserPro, Ultimate Member forms.
* PRO: Feature: [Unlink](https://nextendweb.com/nextend-social-login-docs/global-settings/) option to disable unlink buttons.
* PRO: Feature: PayPal – Option to [disable the email scope](https://nextendweb.com/nextend-social-login-docs/provider-paypal/#settings).
* PRO: Removed: Facebook provider – Sync data fields: Currency, TimeZone, Locale became deprecated.
* PRO: Improvement: Google+ API will shut down soon, so [Google Sync data](https://nextendweb.com/nextend-social-login-docs/provider-google/#sync_data) will use Google People API instead.

= 3.0.14 =
* Fix: Conflict with Login with Ajax reset password.
* Fix: BuddyPress related themes, that render the avatar with the bp_displayed_user_avatar() will be able to get the avatar of the user.
* Fix: New email and profile Google scopes, since old ones became deprecated.
* Fix: WooCommerce User Email Verification plugin prevented users with NSL from logging in.
* Fix: registerComplete function is hooked later to let other plugins send their email notifications.
* Old Nextend Twitter/Google Connect - backwards compatibility notice added. In the 3.0.15 release the backward compatibility will be removed.

* PRO: Fix: Ultimate Member - missing avatar when Support login restriction is disabled.
* PRO: Fix: Authorized domain notification when the page was authorized on non www but was visited on www or vice versa.
* PRO: New provider: [WordPress.com](https://nextendweb.com/nextend-social-login-docs/provider-wordpress-com/)
* PRO: New provider: [Disqus](https://nextendweb.com/nextend-social-login-docs/provider-disqus/)

= 3.0.13 =
* Fix: Twitter Getting Started and Settings page updated according to the new Twitter App creation.
* Fix: Won't stuck on a blank page anymore when the login and registration is blocked by WP Cerber.
* Fix: Infinite redirect loop when home page was selected as OAuth redirect uri proxy page.
* Fix: Safari will no longer close the page automatically after logging in with NSL.
* Feature: Login restriction - Some plugins are now able to prevent the login of NSL when admin approval or email verification is necessary!
* Feature: Google button skins.
* Feature: Portuguese (Brazilian) translation added.

* PRO: Fix: USM Premium prevented the authorization of NSL Pro Addon.
* PRO: Fix: WooCommerce default button layout fix for Billing.
* PRO: Fix: Separator duplication by some themes.

= 3.0.12 =
* Fix: Further changes to prevent some issues with Theme My Login.
* Fix: 'profile_update' WordPress hook won't be triggered anymore upon a registration process.
* Fix: Chrome and Android Facebook login issue via Facebook App.
* Feature: Debug menu and option to test the connection of each provider.
* Feature: Twitter - Selecting profile image size is an option now.
* Feature: Blacklisted redirects
* Feature: Nextend Social Login newsletters subscription!

* PRO: Fix: Google Sync data - Error message for Google+ API when it is not enabled.
* PRO: Feature: PayPal provider and PayPal Sync data!
* PRO: Feature: Social Buttons for MemberPress - Memberships form.
* PRO: Feature: Social Buttons for Ultimate Member forms.

= 3.0.11 =
* Fix: Twitter - 32bit and Windows servers are lost the id precision
* Feature: Jetpack SSO login form extension
* Feature: Prevent external redirect
* Feature: Added Debug menu and Provider connection test
* Theme My Login version 7 breaks Nextend Social Login, so notice displays with details

* PRO: Feature: Sync Google fields
* PRO: Feature: Sync Twitter fields

= 3.0.10 =
* Fix: display_post_states is static now

= 3.0.9 =
* Fix: Parse error for alternate login page

= 3.0.8 =
* Feature: A page can be selected which handles the extra fields for Register flow.
* Feature: A page can be selected which handles the OAuth flow.
* Feature: Spanish (Latin America) translation added.
* Feature: GDPR - add custom Terms and conditions on register.
* Feature: GDPR - retrieved fields can now be exported with the Export Personal Data tool of WordPress.
* Fix: Jetpack - Secure Sign On
* Fix: Dokan - redirection

* PRO: Feature: Authorized domain name check and notice for changed domain name.
* PRO: Feature: Option to change the button layouts for WooCommerce login/register/billing forms.
* PRO: Feature: Sync LinkedId fields

= 3.0.7 =
* Feature: AJAX compatibility
* Feature: Default Redirect URL
* Feature: Twitter screen name as username
* Fix: SocialRabbit compatibility

* PRO: New provider: [VKontakte - vk.com](https://nextendweb.com/nextend-social-login-docs/provider-vkontakte/)
* PRO: New provider: [Amazon](https://nextendweb.com/nextend-social-login-docs/provider-amazon/)
* PRO: Feature: [UserPro Login and Register support.](https://nextendweb.com/nextend-social-login-docs/global-settings-userpro/)

= 3.0.6 =
* Avatars are stored in your media library as Facebook blocked the url access
* Code improvements
* PHP and WordPress version check
* Improved template-parts
* Fix: Login and redirect cleanup
* Fix: Socialize theme

* PRO: Sync Facebook fields
* PRO: Force to ask password and username when enabled
* PRO: MemberPress integration

= 3.0.5 =
* Session cookie name changed to properly work on Pantheon hosting. It can be changed with Can be changed with nsl_session_name filter and NSL_SESSION_NAME constant.
* Fix for Hide my WP plugin @see https://codecanyon.net/item/hide-my-wp-amazing-security-plugin-for-wordpress/4177158

= 3.0.4 =
* Remove whitespaces from username
* Provider test process renamed to "Verify Settings"
* NextendSocialLogin::renderLinkAndUnlinkButtons($heading = '', $link = true, $unlink = true) allows to display link and unlink buttons
* Link and unlink shortcode added: [nextend_social_login login="0" link="1" unlink="1" heading="Connect Social Accounts"]
* [Theme My Login](https://wordpress.org/plugins/theme-my-login/) plugin compatibility fixes.
* Embedded login form settings for wp_login_form
* Prevent account linking if it is already linked
* BuddyPress register form support and profile link and unlink buttons
* iThemes Security - Filter Long URL removed as it prevents provider to return oauth params.
* All In One WP Security - Fixed Verify Settings in providers
* Instruction when redirect Uri changes
* Added new shortcode parameter: trackerdata.

= 3.0.3 =
* Added fallback username prefix
* Fixed avatar for Google, Twitter and LinkedIn providers
* Fixed avatars on retina screen
* Optimized registration process
* Fixed Shopkeeper theme conflict
* WP HTTP api replaced the native cURL
* Twitter provider client optimization, removed force_login param, [added email permission](https://nextendweb.com/nextend-social-login-docs/provider-twitter/#get-email)
* Removed mb_strlen, so "PHP Multibyte String" not required anymore
* Fixed rare case when the redirect to last state url was missing
* Added [WebView support](https://nextendweb.com/nextend-social-login-docs/can-use-nextend-social-login-webview/) (Google buttons are hidden in WebView as Google does not allow to use)
* Fixed rare case when user can stuck in legacy mode while importing provider.

= 3.0.2 =
* Fixed upgrade script

= 3.0.1 =
* Nextend Facebook Connect renamed to Nextend Social Login and contains Google and Twitter providers too.
* Brand new UI
* Popup login
* Pro Addon

= 2.1 =
* New providers: Twitter and Google
* Major UI redesign
* API testing before a provider is enabled to eliminate possible configuration issues

= 2.0.2 =
* Fix: Fatal error: Call to undefined method Facebook\Facebook::getAccessToken()

= 2.0.1 =
* Fix: Redirect uri mismatch in spacial server environment

= 2.0.0 =
* The latest Facebook PHP API used: https://github.com/facebook/php-graph-sdk
* Facebook SDK for PHP requires PHP 5.4 or greater.
* Fix: Facebook 2.2 API does not work anymore