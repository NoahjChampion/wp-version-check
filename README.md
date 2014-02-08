## WP Version Check

Class to check for correct PHP version when bootstrapping a WordPress plugin.

### Purpose

WordPress still only requires PHP 5.2, so if you want to use PHP 5.3+ features you need to check the available version before initialising the plugin.

This class allows you to do a simple version check both when the plugin is activated and if the plugin is already active (eg. you have just pushed the whole site to a new server).

It will prevent activation if the PHP version is insufficient or show an admin warning if the plugin is activated already so you can either remove the plugin or enable the correct PHP version on the server.

### Usage

Use thusly in you plugin's main file (ie. the one with the WordPress plugin data headers).

````
if ( ! class_exists( 'DBisso_Util_VersionCheck' ) ) {
	include_once 'path_to_class/DBisso_Util_VersionCheck.php';
}

// Check for PHP version and then kick things off
DBisso_Util_VersionCheck::bootstrap( '5.3.5', __FILE__, 'plugin.php' );
````

`DBisso_Util_VersionCheck::bootstrap` takes three arguments:

* (string) Minimum PHP version.
* (string) The root plugin file. (for the activation hook)
* (string) The initialising plugin file. This is where your plugin's main code lives.