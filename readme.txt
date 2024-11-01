=== WP Existing Tags ===
Tags: tags, post
Requires at least: 2.5
Tested up to: 2.5.1
Stable tag: trunk

This simple jQuery-based plugin adds a list of existing (already used) tags with posts count for each tag into "Tags" box on new/edit post form.

== Description ==

This simple jQuery-based plugin adds a list of existing (already used) tags with posts count for each tag into "Tags" box on new/edit post form. No more hard time remembering your hundreds of tags - simply click the one you need to add it to the post. Clicked tags are removed from tags list after they inserted into a post - so no worries about inserting one tag several times. No tuning and customizing needed - it just works as it is. Also, you now can hover a tag with mouse pointer to get a short list of five last posts tagged by it.

= Changelog =

*v.1.14* - Made some changes in paths, cause automatic plugin update feature seems to move updated plugin from `/wp-content/plugins/` to `/wp-content/plugins/wp-existing-tags/`. Now installation require you to put `wp-extags.php` into `/wp-content/plugins/wp-existing-tags/` directory or manually edit paths after update to point to your custom directory.

*v.1.12* - Fix: in_array() wrong datatype warning occured when editing post with no tags on it.

*v.1.11* - Made some minor tweaks: clicked tags are removed from tags list after they inserted into a post; when editing, tags already assigned to the post do not appear in the list.

*v.1.1* - Now prints number of posts for each tag. Also, you now can hover a tag with mouse pointer to get a short list of five last posts tagged by it.

*v.1.0* - Initial version, basic functionality.

== Installation ==

1. Upload `wp-extags.php` to the `/wp-content/plugins/wp-existing-tags/` directory.
2. *You may want to change file paths* in lines 35 and 131, if you actually put `wp-extags.php` to the plugins root not `/wp-content/plugins/wp-existing-tags/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. It's already working, check it out!

== Frequently Asked Questions ==

None so far.

== Screenshots ==

1. This is how existing tags list looks in your new/edit post form.