<?php
/**
* File: Scan.php
*
* Setup the Scan.
*
* @since      2.0.0
* @package    BoldGrid\Connect\Rest\Security
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace BoldGrid\Connect\Rest\Health;

/**
* Class: Scan
*
* Setup the Router.
*
* @since 2.0.0
*/
class Scan {

	/**
	 * Scan the site for potential wanrings
	 */
	public function getWarnings() {
		$warnings = [];
		foreach ( $this->getTests() as $method => $info ) {
			if ( $this->$method() ) {
				$warnings[] = $info;
			}
		}

		return $warnings;
	}

	protected function getTests() {
		return [
			'noSSL' => [
				'label' => __( 'Implement an SSL Certificate', 'boldgrid-connect' ),
				'description' => __( 'ISSL certificates help protect the integrity of the data in transit between the host (web server or firewall) and the client (web browser)', 'boldgrid-connect' ),
			],
			'PHPVersionCheck' => [
				'label' => __( 'Upgrade PHP to a supported version', 'boldgrid-connect' ),
				'description' => __( 'The PHP version you are using no longer receives security support and could be exposed to unpatched security vulnerabilities.', 'boldgrid-connect' ),
			],
			'wpSaltExistenceChecker' => [
				'label' => __( 'Missing WordPress Salt & Security Keys', 'boldgrid-connect' ),
				'description' => __( 'Consider using WordPress Salt & Security Keys to add an extra layer of protection to the session cookies and credentials.', 'boldgrid-connect' ),
			],
			'wpSaltAgeDiscriminator' => [
				'label' => __( 'WordPress Salt & Security Keys should be updated', 'boldgrid-connect' ),
				'description' => __( 'Updating WordPress Salt & Security Keys after a compromise and on a regular basis, at least once a year, reduces the risks of session hijacking.', 'boldgrid-connect' ),
			],
			'adminBadUsername' => [
				'label' => __( 'Admin/Administrator username still exists', 'boldgrid-connect' ),
				'description' => __( 'Using a unique username and removing the default admin/administrator account make it more difficult for attackers to brute force your WordPress.', 'boldgrid-connect' ),
			],
			'lonelySuperAdmin' => [
				'label' => __( 'Use super admin account only when needed', 'boldgrid-connect' ),
				'description' => __( 'Create an Editor account instead of always using the super-admin to reduce the damage in case of session hijacking.', 'boldgrid-connect' ),
			],
			'forgottenExtension' => [
				'label' => __( 'Remove unwanted/unused extensions', 'boldgrid-connect' ),
				'description' => __( 'Keeping unwanted themes and plugins increases the chance of a compromise, even if they are disabled.', 'boldgrid-connect' ),
			],
			'tooMuchPlugins' => [
				'label' => __( 'Decrease the number of plugins', 'boldgrid-connect' ),
				'description' => __( 'The greater the number of plugins installed, the greater the risk of infection and performance issues.', 'boldgrid-connect' ),
			],
			'fileEditStillEnabled' => [
				'label' => __( 'Disable file editing', 'boldgrid-connect' ),
				'description' => __( 'Using "DISALLOW_FILE_EDIT" helps prevent an attacker from changing your files through WordPress backend.', 'boldgrid-connect' ),
			],
			'wpDebugOnline' => [
				'label' => __( 'Disable WordPress debug mode', 'boldgrid-connect' ),
				'description' => __( 'When "WP_DEBUG" is set to true, it will cause all PHP errors, notices and warnings to be displayed which can expose sensitive information.', 'boldgrid-connect' ),
			],
		];
	}
}
