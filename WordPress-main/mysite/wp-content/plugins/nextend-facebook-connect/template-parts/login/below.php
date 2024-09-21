<script type="text/javascript">
    window._nslDOMReady(function () {
        var container = document.getElementById('nsl-custom-login-form-main'),
            form = document.querySelector('#loginform,#registerform,#front-login-form,#setupform');

        if (!form) {
            form = container.closest('form');
            if (!form) {
                form = container.parentNode;
            }
        }

        var innerContainer = container.querySelector('.nsl-container');
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-login-layout-below');
            innerContainer.style.display = 'block';
        }

        var jetpackSSO = document.getElementById('jetpack-sso-wrap');
        if (jetpackSSO) {
            form = jetpackSSO;
        } else {
            if (form.parentNode.classList.contains('tml')) {
                form = form.parentNode;
            }
        }

        form.appendChild(container);

    });
</script>
<style type="text/css">
    #nsl-custom-login-form-main .nsl-container {
        display: none;
    }

    #nsl-custom-login-form-main .nsl-container-login-layout-below {
        clear: both;
        padding: 20px 0 0;
    }

    .login form {
        padding-bottom: 20px;
    }

    #nsl-custom-login-form-jetpack-sso .nsl-container-login-layout-below {
        clear: both;
        padding: 0 0 20px;
    }
</style>