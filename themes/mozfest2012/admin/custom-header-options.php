<?php

function mf2012_header_button_defaults($label=null, $target=null) {
	return (object) array(
		'label' => empty($label) ? '' : $label,
		'target' => empty($target) ? 'https://donate.mozilla.org/page/contribute/mozfest2012-registration' : $target,
	);
}

function mf2012_custom_header_options() {
	$defaults = mf2012_header_button_defaults();
?>
<h3>That big blue button</h3>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><?php _e('Label (leave empty to remove the button):'); ?></th>
			<td><p><input type="text" name="mf2012_header_button_label" value="<?php echo esc_attr(get_theme_mod('mf2012_header_button_label', $defaults->label)); ?>"></p></td>
		</tr>
		<tr valign="top" class="hide-if-no-js">
			<th scope="row"><?php _e('Target:'); ?></th>
			<td><p><input type="text" name="mf2012_header_button_target" value="<?php echo esc_attr(get_theme_mod('mf2012_header_button_target', $defaults->target)); ?>"></p></td>
		</tr>
	</tbody>
</table>
<?php
}

add_action('custom_header_options', 'mf2012_custom_header_options');

function mf2012_save_custom_header_options() {
	if (isset($_POST['mf2012_header_button_label']) && isset($_POST['mf2012_header_button_target'])) {
		// validate the request itself by verifying the _wpnonce-custom-header-options nonce
		// (note: this nonce was present in the normal Custom Header form already, so we didn't have to add our own)
		check_admin_referer('custom-header-options', '_wpnonce-custom-header-options');

		// be sure the user has permission to save theme options (i.e., is an administrator)
		if (current_user_can('manage_options')) {
			$defaults = mf2012_header_button_defaults(
				@$_POST['mf2012_header_button_label'],
				@$_POST['mf2012_header_button_target']
			);

			set_theme_mod('mf2012_header_button_label', $defaults->label);
			set_theme_mod('mf2012_header_button_target', $defaults->target);
		}
	}
	return;
}

add_action('admin_head', 'mf2012_save_custom_header_options');
