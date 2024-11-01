<?php
/*
Plugin Name: Smart Tribune AddSnippet
Plugin URI: http://www.smart-tribune.com/
Description: A plugin that make even easier the integration of the <a href="http://www.smart-tribune.com/">Smart Tribune</a> User Feedback tool.
Version: 1.0
Author: Samy Lastmann
Author URI: http://www.smart-tribune.com/

=== VERSION HISTORY ===
  10.10.12 - v1.0 - Initial version
 
=== LEGAL INFORMATION ===
  Copyright (C) 2012 Smart Tribune <contact@smart-tribune.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


register_activation_hook(__FILE__,'stPlugin_activated');
function stPlugin_activated(){
	if(!get_option('stSnippet')) {
	}
}

$st_domain = 'stWPPlugin';
load_plugin_textdomain($st_domain, 'wp-content/plugins/smarttribune-addsnippet');
add_action('init', 'st_init');
function st_init() {
	if(function_exists('current_user_can') && current_user_can('manage_options'))
		add_action('admin_menu', 'st_add_settings_page');
}

add_action('wp_footer', 'st_insert');
function st_insert() {
	if(get_option('stSnippet')) {
		echo get_option('stSnippet');
	}
}

add_action('admin_notices', 'st_admin_notice');
function st_admin_notice() {
	if(!get_option('stSnippet')) echo('<div class="error"><p><strong>'.sprintf(__('Smart Tribune plugin is disabled for now. Please go to "General Settings" then "Smart Tribune AddSnippet" section and paste your Smart Tribune snippet to enable it.' ), admin_url('options-general.php?page=smarttribune-addsnippet')).'</strong></p></div>');
}

add_filter('plugin_action_links', 'st_plugin_actions', 10, 2);
function st_plugin_actions($links, $file) {
	static $this_plugin;
	if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if($file == $this_plugin && function_exists('admin_url')) {
		$settings_link = '<a href="'.admin_url('options-general.php?page=smarttribune-addsnippet').'">'.__('Settings', $st_domain).'</a>';
		array_unshift($links, $settings_link);
	}
	return($links);
}

function st_add_settings_page() {
	function st_settings_page() {
		global $st_domain; ?>
		<div class="wrap">
			<?php screen_icon() ?>
			<h2><?php _e('Smart Tribune AddSnippet', $st_domain) ?></h2>
			<form method="post" action="options.php">
				<?php wp_nonce_field('update-options') ?>
				<p><label for="stSnippet"><?php _e('Paste your Smart Tribune snippet here', $st_domain) ?></label><br />
				<textarea cols="120" rows="12" name="stSnippet" id="stSnippet"><?php echo(get_option('stSnippet')) ?></textarea>
				</p>
				<p>You can retrieve your Smart Tribune snippet from "Button setting" section in your <a href="https://www.smart-tribune.com/admin/visuals/button" target="_blank" title="Visit Smart Tribune administrative interface">administrative interface</a>.<br>Don't forget to add your website domain to <a href="https://www.smart-tribune.com/admin/admin/web-sites" target="_blank" title="Allowed website list">Allowed website</a> list !</p>
				<p class="submit" style="padding:0">
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="stSnippet" />
				<input type="submit" name="stSubmit" id="stSubmit" value="<?php _e('Save snippet', $st_domain) ?>" class="button-primary" /> 
				</p>
			</form>
		</div>
  <?php }
	add_submenu_page('options-general.php', __('Smart Tribune AddSnippet', $st_domain), __('Smart Tribune AddSnippet', $st_domain), 'manage_options', 'smarttribune-addsnippet', 'st_settings_page');
}
?>
