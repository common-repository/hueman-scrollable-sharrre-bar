<?php
/*
   Plugin Name: Hueman Scrollable Sharrre Bar
   Plugin URI: http://wordpress.org/extend/plugins/hueman-scrollable-sharrre-bar/
   Version: 1.0
   Author: Erik Frye
   Author URI: http://sideoffryes.com/hueman-scrollable-sharrre-bar/
   Description: Adds scrolling functionality to the Sharrre bar built into the Hueman template
   Text Domain: hueman-scrollable-sharrre-bar
   License: GPLv2 or later.
  */

/*
    "Hueman Scrollable Sharrre Bar" Copyright (C) 2014 Erik Frye  (email : erikfrye+shareplugin@gmail.com)

    Hueman Scrollable Sharrre Bar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Hueman Scrollable Sharrre Bar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/


if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');


	  
function huemanScrollableSharrreBar() {
	$options = get_option( 'HSSB_options' );
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		var shareContainer = jQuery(".sharrre-container"),
		header = jQuery('#header'),
		postEntry = jQuery('.entry'),
		$window = jQuery(window),
		distanceFromTop = <?php isset( $options['top_spacing'] ) ? print esc_attr( $options['top_spacing']) : print "50" ?>,
		startSharePosition = shareContainer.offset(),
		contentBottom = postEntry.offset().top + postEntry.outerHeight(),
		options
		topOfTemplate = header.offset().top;
		getTopSpacing();
		console.log(shareContainer);
		var shareScroll = function(){
				if($window.width() > <?php isset( $options['min_width'] ) ? print esc_attr( $options['min_width']) : print "719" ?>){        
						var scrollTop = $window.scrollTop() + topOfTemplate,
						stopLocation = contentBottom - (shareContainer.outerHeight() + topSpacing);
						if(scrollTop > stopLocation){
								shareContainer.offset({top: contentBottom - shareContainer.outerHeight(),left: startSharePosition.left});
						}
						else if(scrollTop >= postEntry.offset().top-topSpacing){
								shareContainer.offset({top: scrollTop + topSpacing, left: startSharePosition.left});
						}else if(scrollTop < startSharePosition.top+(topSpacing-1)){
								shareContainer.offset({top: startSharePosition.top,left:startSharePosition.left});
						}
				}
		},

		shareMove = function(){
				startSharePosition = shareContainer.offset();
				contentBottom = postEntry.offset().top + postEntry.outerHeight();
				topOfTemplate = header.offset().top;
				getTopSpacing();
		};

		/* As new images load the page content body gets longer. The bottom of the content area needs to be adjusted in case images are still loading. */
		setTimeout(function() {
				contentBottom = postEntry.offset().top + postEntry.outerHeight();
		}, 2000);

		if (window.addEventListener) {
				window.addEventListener('scroll', shareScroll, false);
				window.addEventListener('resize', shareMove, false);
		} else if (window.attachEvent) {
				window.attachEvent('onscroll', shareScroll);
				window.attachEvent('onresize', shareMove);
		}

		function getTopSpacing(){
				if($window.width() > 1024)
						topSpacing = distanceFromTop + jQuery('.nav-wrap').outerHeight();
				else
						topSpacing = distanceFromTop;
		}
		
	});
</script>
<?php
}

if (!is_admin()) {
	wp_enqueue_script('jquery');
	add_action('wp_footer', 'huemanScrollableSharrreBar');
}

if (is_admin()) {
include_once(dirname(__FILE__) .'/options.php');
$HSSB_settings_page = new HSSB_SettingsPage();
}

// Add settings link on plugin page
function HSSB_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=hueman-scrollable-sharrre-bar">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'HSSB_settings_link' );

?>