<script type="text/javascript">
    window._nslDOMReady(function () {
        var container = document.getElementById('<?php echo $containerID; ?>'),
            form = container.closest('form');

        var innerContainer = container.querySelector('.nsl-container');
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-embedded-login-layout-below');
            innerContainer.style.display = 'block';
        }

        form.appendChild(container);
    });
</script>
<?php
$style = '
    {{containerID}} .nsl-container {
        display: none;
    }

    {{containerID}} .nsl-container-embedded-login-layout-below {
        clear: both;
        padding: 20px 0 0;
    }

    .login form {
        padding-bottom: 20px;
    }';
?>
<style type="text/css">
    <?php echo str_replace('{{containerID}}','#' . $containerID, $style); ?>
</style>
<?php
$style = '
    {{containerID}} .nsl-container {
        display: block;
    }';
?>