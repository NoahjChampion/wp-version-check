<?php
/**
 * DBisso_Version_Check
 *
 * PHP version checking class.
 *
 * @version 1.0.3
*/
if ( ! class_exists( 'DBisso_Version_Check' ) ) {
	class DBisso_Version_Check {
		private static $min_php_version;
		private static $plugin_file;

		/**
		 * Kicks off the checking process.
		 *
		 * Usually called in the root file of the plugin to prevent it bootstrapping with the
		 * correct PHP version.
		 *
		 * @param  string $min_php_version  Minimun usable PHP version for this plugin.
		 * @param  string $plugin_file      Root plugin file.
		 * @param  string $plugin_init_file The plugin's actual initialisation code.
		 */
		function bootstrap( $min_php_version, $plugin_file, $plugin_init_file ) {
			self::$min_php_version = $min_php_version;
			self::$plugin_file = $plugin_file;

			add_action( 'activate_plugin', array( __CLASS__, 'activate' ), 10, 2 );

			if ( self::is_bad_version() ) {
				// We are logged in then we can safely display a message.
				if ( is_admin() ) {
					echo "<div class='error'><p>" . self::get_message() . '</p></div>';
				}

				// Also log it.
				error_log( self::get_message() );
			} else {
				include_once $plugin_init_file;
			}
		}

		/**
		 * Action callback for plugin activation.
		 * @param  string  $plugin  Plugin file.
		 * @param  boolean $network Is this a network activation.
		 */
		function activate( $plugin, $network ) {
			if ( plugin_basename( self::$plugin_file ) === plugin_basename( $plugin ) && self::is_bad_version() ) {
				exit( self::get_message() );
			}
		}

		/**
		 * Checks whether the current PHP version is insufficient.
		 * @return boolean true if PHP version is too low.
		 */
		function is_bad_version() {
			return version_compare( PHP_VERSION, self::$min_php_version, '<' );
		}

		/**
		 * Generates a meaningful error message.
		 * @return string The error message
		 */
		function get_message() {
			return sprintf(
				'The plugin <code>%s</code> requires at least PHP %s. You are currently running PHP %s.',
				dirname( plugin_basename( self::$plugin_file ) ),
				self::$min_php_version,
				PHP_VERSION
			);
		}
	}
}