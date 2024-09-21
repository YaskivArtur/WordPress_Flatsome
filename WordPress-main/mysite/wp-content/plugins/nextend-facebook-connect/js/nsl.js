/**
 * Used when Cross-Origin-Opener-Policy blocked the access to the opener. We can't have a reference of the opened windows, so we should attempt to refresh only the windows that has opened popups.
 */
window._nslHasOpenedPopup = false;
window._nslWebViewNoticeElement = null;

window.NSLPopup = function (url, title, w, h) {
    const userAgent = navigator.userAgent,
        mobile = function () {
            return /\b(iPhone|iP[ao]d)/.test(userAgent) ||
                /\b(iP[ao]d)/.test(userAgent) ||
                /Android/i.test(userAgent) ||
                /Mobile/i.test(userAgent);
        },
        screenX = window.screenX !== undefined ? window.screenX : window.screenLeft,
        screenY = window.screenY !== undefined ? window.screenY : window.screenTop,
        outerWidth = window.outerWidth !== undefined ? window.outerWidth : document.documentElement.clientWidth,
        outerHeight = window.outerHeight !== undefined ? window.outerHeight : document.documentElement.clientHeight - 22,
        targetWidth = mobile() ? null : w,
        targetHeight = mobile() ? null : h,
        left = parseInt(screenX + (outerWidth - targetWidth) / 2, 10),
        right = parseInt(screenY + (outerHeight - targetHeight) / 2.5, 10),
        features = [];
    if (targetWidth !== null) {
        features.push('width=' + targetWidth);
    }
    if (targetHeight !== null) {
        features.push('height=' + targetHeight);
    }
    features.push('left=' + left);
    features.push('top=' + right);
    features.push('scrollbars=1');

    const newWindow = window.open(url, title, features.join(','));

    if (window.focus) {
        newWindow.focus();
    }

    window._nslHasOpenedPopup = true;

    return newWindow;
};

let isWebView = null;

function checkWebView() {
    if (isWebView === null) {
        function _detectOS(ua) {
            if (/Android/.test(ua)) {
                return "Android";
            } else if (/iPhone|iPad|iPod/.test(ua)) {
                return "iOS";
            } else if (/Windows/.test(ua)) {
                return "Windows";
            } else if (/Mac OS X/.test(ua)) {
                return "Mac";
            } else if (/CrOS/.test(ua)) {
                return "Chrome OS";
            } else if (/Firefox/.test(ua)) {
                return "Firefox OS";
            }
            return "";
        }

        function _detectBrowser(ua) {
            let android = /Android/.test(ua);

            if (/Opera Mini/.test(ua) || / OPR/.test(ua) || / OPT/.test(ua)) {
                return "Opera";
            } else if (/CriOS/.test(ua)) {
                return "Chrome for iOS";
            } else if (/Edge/.test(ua)) {
                return "Edge";
            } else if (android && /Silk\//.test(ua)) {
                return "Silk";
            } else if (/Chrome/.test(ua)) {
                return "Chrome";
            } else if (/Firefox/.test(ua)) {
                return "Firefox";
            } else if (android) {
                return "AOSP";
            } else if (/MSIE|Trident/.test(ua)) {
                return "IE";
            } else if (/Safari\//.test(ua)) {
                return "Safari";
            } else if (/AppleWebKit/.test(ua)) {
                return "WebKit";
            }
            return "";
        }

        function _detectBrowserVersion(ua, browser) {
            if (browser === "Opera") {
                return /Opera Mini/.test(ua) ? _getVersion(ua, "Opera Mini/") :
                    / OPR/.test(ua) ? _getVersion(ua, " OPR/") :
                        _getVersion(ua, " OPT/");
            } else if (browser === "Chrome for iOS") {
                return _getVersion(ua, "CriOS/");
            } else if (browser === "Edge") {
                return _getVersion(ua, "Edge/");
            } else if (browser === "Chrome") {
                return _getVersion(ua, "Chrome/");
            } else if (browser === "Firefox") {
                return _getVersion(ua, "Firefox/");
            } else if (browser === "Silk") {
                return _getVersion(ua, "Silk/");
            } else if (browser === "AOSP") {
                return _getVersion(ua, "Version/");
            } else if (browser === "IE") {
                return /IEMobile/.test(ua) ? _getVersion(ua, "IEMobile/") :
                    /MSIE/.test(ua) ? _getVersion(ua, "MSIE ")
                        :
                        _getVersion(ua, "rv:");
            } else if (browser === "Safari") {
                return _getVersion(ua, "Version/");
            } else if (browser === "WebKit") {
                return _getVersion(ua, "WebKit/");
            }
            return "0.0.0";
        }

        function _getVersion(ua, token) {
            try {
                return _normalizeSemverString(ua.split(token)[1].trim().split(/[^\w\.]/)[0]);
            } catch (o_O) {
            }
            return "0.0.0";
        }

        function _normalizeSemverString(version) {
            const ary = version.split(/[\._]/);
            return (parseInt(ary[0], 10) || 0) + "." +
                (parseInt(ary[1], 10) || 0) + "." +
                (parseInt(ary[2], 10) || 0);
        }

        function _isWebView(ua, os, browser, version, options) {
            switch (os + browser) {
                case "iOSSafari":
                    return false;
                case "iOSWebKit":
                    return _isWebView_iOS(options);
                case "AndroidAOSP":
                    return false;
                case "AndroidChrome":
                    return parseFloat(version) >= 42 ? /; wv/.test(ua) : /\d{2}\.0\.0/.test(version) ? true : _isWebView_Android(options);
            }
            return false;
        }

        function _isWebView_iOS(options) {
            const document = (window["document"] || {});

            if ("WEB_VIEW" in options) {
                return options["WEB_VIEW"];
            }
            return !("fullscreenEnabled" in document || "webkitFullscreenEnabled" in document || false);
        }

        function _isWebView_Android(options) {
            if ("WEB_VIEW" in options) {
                return options["WEB_VIEW"];
            }
            return !("requestFileSystem" in window || "webkitRequestFileSystem" in window || false);
        }

        const options = {},
            nav = window.navigator || {},
            ua = nav.userAgent || "",
            os = _detectOS(ua),
            browser = _detectBrowser(ua),
            browserVersion = _detectBrowserVersion(ua, browser);

        isWebView = _isWebView(ua, os, browser, browserVersion, options);
    }

    return isWebView;
}

function isAllowedWebViewForUserAgent(provider) {
    const facebookAllowedWebViews = [
        'Instagram',
        'FBAV',
        'FBAN'
    ];
    let whitelist = [];

    if (provider && provider === 'facebook') {
        whitelist = facebookAllowedWebViews;
    }

    const nav = window.navigator || {},
        ua = nav.userAgent || "";

    if (whitelist.length && ua.match(new RegExp(whitelist.join('|')))) {
        return true;
    }

    return false;
}

function disableButtonInWebView(providerButtonElement) {
    if (providerButtonElement) {
        providerButtonElement.classList.add('nsl-disabled-provider');
        providerButtonElement.setAttribute('href', '#');

        providerButtonElement.addEventListener('pointerdown', (e) => {
            if (!window._nslWebViewNoticeElement) {
                window._nslWebViewNoticeElement = document.createElement('div');
                window._nslWebViewNoticeElement.id = "nsl-notices-fallback";
                window._nslWebViewNoticeElement.addEventListener('pointerdown', function (e) {
                    this.parentNode.removeChild(this);
                    window._nslWebViewNoticeElement = null;
                });
                const webviewNoticeHTML = '<div class="error"><p>' + scriptOptions._localizedStrings.webview_notification_text + '</p></div>';

                window._nslWebViewNoticeElement.insertAdjacentHTML("afterbegin", webviewNoticeHTML);
                document.body.appendChild(window._nslWebViewNoticeElement);
            }
        });
    }

}

window._nslDOMReady(function () {

    window.nslRedirect = function (url) {
        if (scriptOptions._redirectOverlay) {
            const overlay = document.createElement('div');
            overlay.id = "nsl-redirect-overlay";
            let overlayHTML = '';
            const overlayContainer = "<div id='nsl-redirect-overlay-container'>",
                overlayContainerClose = "</div>",
                overlaySpinner = "<div id='nsl-redirect-overlay-spinner'></div>",
                overlayTitle = "<p id='nsl-redirect-overlay-title'>" + scriptOptions._localizedStrings.redirect_overlay_title + "</p>",
                overlayText = "<p id='nsl-redirect-overlay-text'>" + scriptOptions._localizedStrings.redirect_overlay_text + "</p>";

            switch (scriptOptions._redirectOverlay) {
                case "overlay-only":
                    break;
                case "overlay-with-spinner":
                    overlayHTML = overlayContainer + overlaySpinner + overlayContainerClose;
                    break;
                default:
                    overlayHTML = overlayContainer + overlaySpinner + overlayTitle + overlayText + overlayContainerClose;
                    break;
            }

            overlay.insertAdjacentHTML("afterbegin", overlayHTML);
            document.body.appendChild(overlay);
        }

        window.location = url;
    };

    let targetWindow = scriptOptions._targetWindow || 'prefer-popup',
        lastPopup = false;


    const buttonLinks = document.querySelectorAll(' a[data-plugin="nsl"][data-action="connect"], a[data-plugin="nsl"][data-action="link"]');
    buttonLinks.forEach(function (buttonLink) {
        buttonLink.addEventListener('click', function (e) {
            if (lastPopup && !lastPopup.closed) {
                e.preventDefault();
                lastPopup.focus();
            } else {

                let href = this.href,
                    success = false;
                if (href.indexOf('?') !== -1) {
                    href += '&';
                } else {
                    href += '?';
                }

                const redirectTo = this.dataset.redirect;
                if (redirectTo === 'current') {
                    href += 'redirect=' + encodeURIComponent(window.location.href) + '&';
                } else if (redirectTo && redirectTo !== '') {
                    href += 'redirect=' + encodeURIComponent(redirectTo) + '&';
                }

                if (targetWindow !== 'prefer-same-window' && checkWebView()) {
                    targetWindow = 'prefer-same-window';
                }

                if (targetWindow === 'prefer-popup') {
                    lastPopup = NSLPopup(href + 'display=popup', 'nsl-social-connect', this.dataset.popupwidth, this.dataset.popupheight);
                    if (lastPopup) {
                        success = true;
                        e.preventDefault();
                    }
                } else if (targetWindow === 'prefer-new-tab') {
                    const newTab = window.open(href + 'display=popup', '_blank');
                    if (newTab) {
                        if (window.focus) {
                            newTab.focus();
                        }
                        success = true;
                        window._nslHasOpenedPopup = true;
                        e.preventDefault();
                    }
                }

                if (!success) {
                    window.location = href;
                    e.preventDefault();
                }
            }
        });
    });

    let buttonCountChanged = false;

    const googleLoginButtons = document.querySelectorAll(' a[data-plugin="nsl"][data-provider="google"]');
    if (googleLoginButtons.length && checkWebView()) {
        googleLoginButtons.forEach(function (googleLoginButton) {
            if (scriptOptions._unsupportedWebviewBehavior === 'disable-button') {
                disableButtonInWebView(googleLoginButton);
            } else {
                googleLoginButton.remove();
                buttonCountChanged = true;
            }
        });
    }

    const facebookLoginButtons = document.querySelectorAll(' a[data-plugin="nsl"][data-provider="facebook"]');
    if (facebookLoginButtons.length && checkWebView() && /Android/.test(window.navigator.userAgent) && !isAllowedWebViewForUserAgent('facebook')) {
        facebookLoginButtons.forEach(function (facebookLoginButton) {
            if (scriptOptions._unsupportedWebviewBehavior === 'disable-button') {
                disableButtonInWebView(facebookLoginButton);
            } else {
                facebookLoginButton.remove();
                buttonCountChanged = true;
            }
        });
    }

    const separators = document.querySelectorAll('div.nsl-separator');
    if (buttonCountChanged && separators.length) {
        separators.forEach(function (separator) {
            const separatorParentNode = separator.parentNode;
            if (separatorParentNode) {
                const separatorButtonContainer = separatorParentNode.querySelector('div.nsl-container-buttons');
                if (separatorButtonContainer && !separatorButtonContainer.hasChildNodes()) {
                    separator.remove();
                }
            }
        })
    }
});

/**
 * Cross-Origin-Opener-Policy blocked the access to the opener
 */
if (typeof BroadcastChannel === "function") {
    const _nslLoginBroadCastChannel = new BroadcastChannel('nsl_login_broadcast_channel');
    _nslLoginBroadCastChannel.onmessage = (event) => {
        if (window?._nslHasOpenedPopup && event.data?.action === 'redirect') {
            window._nslHasOpenedPopup = false;

            const url = event.data?.href;
            _nslLoginBroadCastChannel.close();
            if (typeof window.nslRedirect === 'function') {
                window.nslRedirect(url);
            } else {
                window.opener.location = url;
            }
        }
    };
}