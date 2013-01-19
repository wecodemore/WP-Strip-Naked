<?php
defined( 'ABSPATH' ) OR exit;

/**
 * This class triggers functions that run during activation/deactivation & uninstallation
 *
 * @author Franz Josef Kaiser
 * @package WP Strip Naked
 * @subpackage Setup
 * @license GNU GPL v2
 *
 * @link http://wordpress.stackexchange.com/a/25979/385 Based on my tutorial on WPSE
 */
class WP_Strip_Naked_Setup
{
	/**
	 * Set this to true to get the state of origin,
	 * so you don't need to always uninstall during development.
	 *
	 * @param boolean
	 */
	const STATE_OF_ORIGIN = false;


	public function __construct( $case = false )
	{
		if ( ! $case )
			wp_die( 'Busted! You should not call this class directly', 'Doing it wrong!' );

		switch( $case )
		{
			case 'activate' :
				// Setup options
				add_action( 'init', array( &$this, 'activate_cb' ) );
				break;

			case 'deactivate' :
				// Reset the options
				# add_action( 'init', array( &$this, 'deactivate_cb' ) );
				break;

			case 'uninstall' :
				// Delete the options & clean the tables
				add_action( 'init', array( &$this, 'uninstall_cb' ) );
				break;
		}
	}


	/**
	 * Set up tables, add options, etc.
	 * All preparation that only needs to be done once
	 *
	 * @since 0.3
	 *
	 * @return void
	 */
	public function on_activate()
	{
		new WP_Strip_Naked_Setup( 'activate' );
	}


	/**
	 * Deactivate
	 * @internal If the class constant is set to true, uninstall gets triggered instead
	 *
	 * @since 0.3
	 *
	 * @return void
	 */
	public function on_deactivate()
	{
		$case = self::STATE_OF_ORIGIN ? 'uninstall' : 'deactivate';

		new WP_Strip_Naked_Setup( $case );
	}


	/**
	 * Uninstall
	 *
	 * @since 0.3
	 *
	 * @return void
	 */
	public function on_uninstall()
	{
		// important: check if the file is the one that was registered with the uninstall hook (function)
		if ( __FILE__ != WP_UNINSTALL_PLUGIN )
			return;

		new WP_Strip_Naked_Setup( 'uninstall' );
	}


	/**
	 * On activate
	 * Sets the lowest page ID as front post
	 *
	 * @since 0.1 | 0.3 moved into this class
	 *
	 * @return void
	 */
	public function activate_cb()
	{
		add_option( 'strip_pages', false );
		add_option( 'strip_feed', false );

		if ( 'pages' !== get_option( 'show_on_front' ) )
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


	/**
	 * Deactivate
	 *
	 * @since 0.3
	 *
	 * @return void
	 */
	public function deactivate_cb()
	{
		// if you need to output messages in the 'admin_notices' field, do it like this:
		// $this->error( "Some message.<br />" );
		// if you need to output messages in the 'admin_notices' field AND stop further processing, do it like this:
		// $this->error( "Some message.<br />", TRUE );
	}


	/**
	 * On uninstall
	 * Removes all set options
	 *
	 * @since 0.3
	 *
	 * @return void
	 */
	public function uninstall_cb()
	{
		delete_option( 'strip_pages' );
		delete_option( 'strip_feed' );

		update_option( 'show_on_front', 'posts' );

		$post = get_posts( array(
			 'numberposts'	=> 1
			,'orderby'		=> 'ID'
			,'order'		=> 'ASC'
			,'post_type'	=> 'post'
		) );
		update_option( 'page_on_front', $post->ID );
	}


	/**
	 * trigger_error()
	 *
	 * @since 0.3
	 *
	 * @param (string) $error_msg
	 * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
	 * @param unknown_type $error_type
	 *
	 * @return void
	 */
	public function error( $error_msg, $fatal_error = false, $error_type = E_USER_ERROR )
	{
		if ( isset( $_GET['action'] ) && 'error_scrape' == $_GET['action'] )
		{
			echo "{$error_msg}\n";
			if ( $fatal_error )
				exit;
		}
		else
		{
			trigger_error( $error_msg, $error_type );
		}
	}
}