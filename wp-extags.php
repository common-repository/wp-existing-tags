<?php
/*
Plugin Name: WP Existing Tags
Plugin URI: http://wordpress.org/extend/plugins/wp-existing-tags/
Description: This simple plugin adds a list of existing (already used) tags into "Tags" box on new/edit post form. No more hard time remembering your hundreds of tags - simply click the one you need to add it to the post. No tuning and customizing needed - it just works as it is.
Author: Kyr Dunenkoff
Version: 1.14
Changes:	1.0 - Initial version.
			1.1 - Now prints number of posts for each tag; hover it with mouse pointer and get a short list of five last posts tagged by it.
			1.11 - Made some minor tweaks: clicked tags are removed from tags list after they inserted into a post; when editing, tags already assigned to the post do not appear in the list.
			1.14 - Fix: in_array() wrong datatype warning occured when editing post with no tags on it.

Author URI: http://wordpress.org/extend/plugins/profile/kyr-dunenkoff
*/

/*  Copyright 2008 Kyr Dunenkoff (email: dunenkoff@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ($_GET['tag'] != '') {

	include('../../../wp-config.php'); // you may want to change that if you didn't put wp-extags.php to plugins root directory
	
	$objs = get_objects_in_term($_GET['tag'],"post_tag"); // very helpful function - get array of post_ids for tagged posts
	
	foreach ($objs as $o) {
	
		$names[$o] = get_post_field("post_title",$o); // get only title to lessen the load; $o contains post id, so you can use it too
		$names[$o] = ($names[$o]!='')?$names[$o]:"***";
	
	}
	
	krsort($names); // reverse it, we need newer posts first
	
	$names = array_slice($names,0,5); // only five last posts
	
	$names = implode("<br />",$names); // make them string
		
	header("Content-Type: text/html;charset=utf-8"); // make them utf-8; if not, can cause broken encoding on non-utf8 MySQL servers
	
	die($names); // output it and make sure this script stops

}

function wp_extags_init() {

	global $post;

	$tags = get_terms("post_tag"); // better use inner WP functions instead of template tags
	
	$terms = wp_get_object_terms($post->ID,"post_tag"); // get all tags for post
	
	foreach ($terms as $t) {
	
		$tt[] = $t->term_id;
	
	}
	
	if(!is_array($tt)) $tt = array($tt);
	
	foreach ($tags as $t) {
	
		$id = $t->term_id; // id for using in AJAX request
		$name = $t->name; // name for displaying
		
			if (in_array($id,$tt)) continue; // if tag already assigned to the post, skip it
	

		$lt .= "<span class=\"wpextag\"><a id=\"$id\" alt=\"$name\">".$name."</a> (".$t->count."), </span>"; // building a classed link without href		
		
	
	}
	
	$floatie = "<div id=\"wpextagfloatie\" style=\"position:absolute;z-index:2;padding:5px;border:1px solid black;background-color:#fff;\"></div>"; // here's a absolute positioned block for displaying short list of five post when hovering a tag
	
	$lt = rtrim($lt,", </span>")."</span>"; // removing last comma and whitespace
	
	// style makes taglinks behave like common
	echo "
	<style type=\"text/css\" media=\"all\">
	
		.wpextag a {cursor:pointer;}
	
	</style>
	";
	
	// jQuery inserts existing tags links list before new tag input box
	// 'click' function is a copy of tag_flush_to_text() except it uses link inner text for a new tag injection
	echo "
	<script>
	
		jQuery(document).ready(function() {
		
			jQuery('#jaxtag').prepend('$floatie');
			jQuery('#wpextagfloatie').css({opacity:'0.7'});			
			jQuery('#wpextagfloatie').hide();
			
			jQuery('#jaxtag').prepend('<p id=\"wpextags\">$lt</p>');
			
			jQuery('.wpextag a').click(function() {
			
				var newtags = jQuery('#tags-input').val() + ',' + jQuery(this).attr('alt');
				newtags = newtags.replace( /\s+,+\s*/g, ',' ).replace( /,+/g, ',' ).replace( /,+\s+,+/g, ',' ).replace( /,+\s*$/g, '' ).replace( /^\s*,+/g, '' );
				jQuery('#tags-input').val( newtags );
				tag_update_quickclicks();
				jQuery(this).parent().replaceWith('');
				jQuery('#newtag').val('');
				jQuery('#newtag').focus();
			
			
			});
			
			jQuery('.wpextag a').hover(
			
				function() {
				
					var response = jQuery.ajax({
					  url: '".get_option('siteurl')."/".PLUGINDIR."/wp-existing-tags/wp-extags.php"."', // you may want to change that if you didn't put wp-extags.php to plugins root directory
					  data: 'tag='+jQuery(this).attr('id'),
					  async: false, // if true, will show empty box before response
					  dataType: 'text',
					  type: 'GET'
					 }).responseText;			
	
					var pos = jQuery(this).offset();

					jQuery('#wpextagfloatie').html(response);					
					jQuery('#wpextagfloatie').css({top:pos.top-jQuery(this).height()-jQuery('#wpextagfloatie').height(),left:pos.left});
					jQuery('#wpextagfloatie').fadeIn();						
							
				},
				
				function() {
				
					jQuery('#wpextagfloatie').hide();			
				
				}
			
			
			);		
		});
		
	</script>
	";
	
	}
	
	// all set, injecting style and script into admin_head of post.php|post-new.php
	if(preg_match("/(post-new\.php|post\.php)$/", $_SERVER['PHP_SELF'])) {
	
		add_action('admin_head', 'wp_extags_init');
	
}
?>