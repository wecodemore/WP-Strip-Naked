<?php
/**
 * Plugin Name: WP Strip Naked
 * Plugin URI: http://
 * Description: Strips WordPress built in stuff down to it's bare essentials
 * Version: 0.1
 * Author: Franz Josef Kaiser
 * Author URI: http://unserkaiser.com
 *
 * 
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Prevent loading this file directly - Busted!
if( ! class_exists('WP') ) 
{
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}



if ( ! class_exists( 'WP_Strip_Naked' ) )
{
	/**
	 * On activate
	 * Sets the lowest page ID as front post
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	function wpsn_on_activate()
	{
		$index_cpt = get_option( 'show_on_front' );

		if ( 'pages' !== $index_cpt )
		{
			update_option( 'show_on_front', 'pages' );

			$index_id = get_option( 'page_on_front' );
			$page = get_posts( array(
				 'numberposts'	=> 1
				,'orderby'		=> 'ID'
				,'order'		=> 'ASC'
				,'post_type'	=> 'page'
			) );
			if ( $page->ID !== $index_id )
				update_option( 'page_on_front', $page->ID );
		}
	}
	register_activation_hook( __FILE__, 'wpsn_on_activate' );

	/**
	 * INIT PLUGIN
	 * Triggers on the "plugins_loaded" hook, as "muplugins_loaded" is too early
	 */
	if ( function_exists( 'add_action' ) )
		add_action( 'plugins_loaded', array( 'WP_Strip_Naked', 'init' ) );

/**
 * Strip all of WP down to naked
 * 
 * @author Franz Josef Kaiser
 * @since 0.1
 * @version 0.1
 * @package WordPress Strip Naked
 * @subpackage 
 */
class WP_Strip_Naked
{
	/**
	 * Handler for the action 'init'. Instantiates this class.
	 * @return void
	 */
	public static function init()
	{
		$class	= __CLASS__;
		new $class;
	}


	public function __construct()
	{
		remove_filter( 'the_content', 'capital_P_dangit' );
		remove_filter( 'the_title', 'capital_P_dangit' );
		remove_filter( 'comment_text', 'capital_P_dangit' );

		add_action( 'init', array( &$this, 'taxonomies' ) );
		add_action( 'init', array( &$this, 'post_types' ) );

		add_action( 'wp_before_admin_bar_render',  array( &$this, 'admin_bar' ) );

		add_filter( 'parent_file', array( &$this, 'admin_menu' ) );

		add_action( 'wp_dashboard_setup', array( &$this, 'dashboard_widgets' ) );

		add_filter( 'admin_footer_text', '__return_false' );
		add_filter( 'update_footer', '__return_false' );

		# add_filter( 'page_template', array( &$this, 'page_template' ), 10, 1 );
		# add_filter( 'comments_popup_template', array( &$this, 'comments_popup_template' ), 10, 1 );
	}


	public function page_template( $templates )
	{
		$templates = array();
		return $templates;
	}


	public function comments_popup_template( $templates )
	{
		$templates = array();
		return $templates;
	}


	/**
	 * Removes all built in taxonomies
	 * Leaves only the "nav-menu" taxonomy
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	public function taxonomies()
	{
		global $wp_taxonomies;

		unset( $wp_taxonomies['category'] );
		unset( $wp_taxonomies['post_tag'] );
		unset( $wp_taxonomies['link_category'] );
		unset( $wp_taxonomies['post_format'] );
	}


	/**
	 * Removes the by default built in post types of "Post" & "Page"
	 * 
	 * @since 0.1
	 * 
	 * @return void 
	 */
	public function post_types()
	{
		global $wp_post_types;

		unset( $wp_post_types['post'] );
		# unset( $wp_post_types['page'] );
	}


	/**
	 * Removes all admin bar items
	 * Leaves only the site name that can be used to access the public view
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	public function admin_bar()
	{
		global $wp_admin_bar;

		if ( ! is_admin_bar_showing() )
			return;

		$wp_admin_bar->remove_menu( 'wp-logo' );
		$wp_admin_bar->remove_menu( 'comments' );
		$wp_admin_bar->remove_menu( 'my-account' );
		$wp_admin_bar->remove_menu( 'appearance' );
		$wp_admin_bar->remove_menu( 'new-content' );
		$wp_admin_bar->remove_menu( 'my-account-with-avatar' );
	}


	/**
	 * Removes all menu & submenu items for the admin menu
	 * for stuff that's built in
	 * Also removes all settings pages and replaces them
	 * with an "All Settings" page
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	public function admin_menu()
	{
		global $menu, $submenu;

		# >>>> Sub menu items
			// Add New Post
			remove_submenu_page( 'edit.php', 'post-new.php' );

			// Add New Link
			remove_submenu_page( 'link-manager.php', 'link-add.php' );
			// Link Taxonomy
			remove_submenu_page( 'link-manager.php', 'edit-tags.php?taxonomy=link_category' );

			// Add New Page
			# remove_submenu_page( 'edit.php?post_type=page', 'post-new.php?post_type=page' );

			// Theme Editor
			remove_submenu_page( 'themes.php', 'theme-editor.php' );
			// Plugins Editor
			remove_submenu_page( 'plugins.php', 'plugin-editor.php' );

			// Settings General
			remove_submenu_page( 'options-general.php', 'options-general.php' );
			// Settings 
			remove_submenu_page( 'options-general.php', 'options-writing.php' );
			// Settings 
			remove_submenu_page( 'options-general.php', 'options-reading.php' );
			// Settings 
			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
			// Settings 
			remove_submenu_page( 'options-general.php', 'options-media.php' );
			// Settings 
			remove_submenu_page( 'options-general.php', 'options-privacy.php' );
			// Settings 
			remove_submenu_page( 'options-general.php', 'options-permalink.php' );
		# <<<< Sub menu items

		# >>>> Main menu items
			// Posts
			remove_menu_page( 'edit.php' );
			// Links
			remove_menu_page( 'link-manager.php' );
			// Pages
			# remove_menu_page( 'edit.php?post_type=page' );
			// Comments
			remove_menu_page( 'edit-comments.php' );
		# <<<< Main menu items

		// Add all options page instead
		add_submenu_page(
			 'options-general.php'
			,__( 'All Options' )
			,__( 'All Settings' )
			,'manage_options'
			,'options.php'
		);
		$opt_first = reset( array_keys( $submenu[ 'options-general.php' ] ) );
		// (Re)move
		foreach ( $submenu[ 'options-general.php' ] as $i => $item ) 
		{
			if ( 'options.php' == $item[2] ) 
			{
				unset( $submenu[ 'options-general.php' ][ $i ] );
				$submenu[ 'options-general.php' ][ $opt_first - 1 ] = $item;
			}
		}
		// Sort
		sort( $submenu[ 'options-general.php' ] );
	}


	/**
	 * Removes all default meta boxes from the dashboard
	 * Leaves only "Incoming Links"
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	public function dashboard_widgets()
	{
		remove_meta_box( 'dashboard_browser_nag',		'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now',			'dashboard', 'normal' );
		remove_meta_box( 'dashboard_recent_comments',	'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins',			'dashboard', 'normal' );

		remove_meta_box( 'dashboard_quick_press',		'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_drafts',		'dashboard', 'side' );
		remove_meta_box( 'dashboard_primary',			'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary',			'dashboard', 'side' );
	}
}

} // endif;