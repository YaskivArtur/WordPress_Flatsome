<tr>
    <th scope="row"><?php _e('Button skin', 'nextend-facebook-connect'); ?></th>
    <td>
        <fieldset>
            <label>
                <input type="radio" name="skin"
                       value="dark" <?php if ($settings->get('skin') == 'dark') : ?> checked="checked" <?php endif; ?>>
                <span><?php _e('Dark', 'nextend-facebook-connect'); ?></span><br/>
                <img src="<?php echo plugins_url('images/facebook/dark.png', NSL_ADMIN_PATH) ?>"/>
            </label>
            <label>
                <input type="radio" name="skin"
                       value="light" <?php if ($settings->get('skin') == 'light') : ?> checked="checked" <?php endif; ?>>
                <span><?php _e('Light', 'nextend-facebook-connect'); ?></span><br/>
                <img src="<?php echo plugins_url('images/facebook/light.png', NSL_ADMIN_PATH) ?>"/>
            </label>
            <label>
                <input type="radio" name="skin"
                       value="black" <?php if ($settings->get('skin') == 'black') : ?> checked="checked" <?php endif; ?>>
                <span><?php _e('Black', 'nextend-facebook-connect'); ?></span><br/>
                <img src="<?php echo plugins_url('images/facebook/black.png', NSL_ADMIN_PATH) ?>"/>
            </label>
            <label>
                <input type="radio" name="skin"
                       value="white" <?php if ($settings->get('skin') == 'white') : ?> checked="checked" <?php endif; ?>>
                <span><?php _e('White', 'nextend-facebook-connect'); ?></span><br/>
                <img src="<?php echo plugins_url('images/facebook/white.png', NSL_ADMIN_PATH) ?>"/>
            </label>

        </fieldset>
    </td>
</tr>