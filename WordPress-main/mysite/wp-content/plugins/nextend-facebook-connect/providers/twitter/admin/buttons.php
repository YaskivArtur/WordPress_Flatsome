<tr>
    <th scope="row"><?php _e('Button skin', 'nextend-facebook-connect'); ?></th>
    <td>
        <fieldset>
            <label>
                <input type="radio" name="skin"
                       value="x" <?php if ($settings->get('skin') == 'x') : ?> checked="checked" <?php endif; ?>>
                <span><?php _e('X', 'nextend-facebook-connect'); ?></span><br/>
                <img src="<?php echo plugins_url('images/twitter/x.png', NSL_ADMIN_PATH) ?>"/>
            </label>
            <label>
                <input type="radio" name="skin"
                       value="legacy" <?php if ($settings->get('skin') == 'legacy') : ?> checked="checked" <?php endif; ?>>
                <span><?php _e('Legacy', 'nextend-facebook-connect'); ?></span><br/>
                <img src="<?php echo plugins_url('images/twitter/legacy.png', NSL_ADMIN_PATH) ?>"/>
            </label>
        </fieldset>
    </td>
</tr>