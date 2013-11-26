<img title="WP Strip Naked Logo" src="https://github.com/franz-josef-kaiser/WP-Strip-Naked/raw/master/strip-naked-logo.png" />

# WP Strip Naked

**Version: _v0.5_**

Strips down WP to it's bare essentials. Removes everything that's not needed to be in place if you use WP as a CMS. There are no options needed. The plugin works out of the box - **just activate**!

----------------------------------------------------------------

## Functionality

The Plugin removes:

 * All Dashboard Widgets except for "Incoming Links"
 * All built in taxonomies and leaves only the "nav-menu" taxonomy
 * The built in post type "Post"
 * Changes the option "show on front" to "pages"
 * Changes the initial posts query to search for the page with the lowest ID
 * All admin bar items and leaves only the site name that can be used to access the public view
 * Admin Menu items and submenu items: Posts, Links, Comments
 * The admin menu items for Plugin & Theme Editor
 * All Settings admin menu items and replaces them with the "All Settings" Page and makes it only accessible for 'manage_options' capability
 * Removes the "capitalPdangit" filter from content, title and comment text, in case you add those filter in CPT related stuff

## How-To

You can now remove the "Feeds" and "Pages" post type with switching 0/1 on the "All Settings" page.
Plugin Settings fields are highlighted in blue.

## Languages

Not needed.

### Authors

visit [Franz Josef Kaiser at his blog](https://unserkaiser.com/) | or [at his Github Account](https://github.com/franz-josef-kaiser) | or [get social on G+](https://plus.google.com/+FranzJosefKaiser) or [on Twitter](https://twitter.com/unserkaiser).

### Screenshot

<img title="Stripped admin UI" src="https://github.com/franz-josef-kaiser/WP-Strip-Naked/raw/master/screenshot-1.jpg" />

-----

### Changelog

* _v0.5_ Removed PHP 4 compatibility. Moved single filters to a function. Fixed a small bug.
* _v0.4_ Move Capital P Dangit filters to a single function
* _v0.3.1_ Bug fixes: Textdomain string
* _v0.3_ Adds Settings, removes Feed & Pages on demand. New (De-)Activate/Uninstall Class
* _v0.2_ Removes feed
* _v0.1_ First version - Draft

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/franz-josef-kaiser/wp-strip-naked/trend.png)](https://bitdeli.com/free �Bitdeli Badge�)