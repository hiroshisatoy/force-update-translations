<?php
/**
 * WP-CLI commands for Force Update Translations.
 *
 * @package Force_Update_Translations
 */

/**
 * Force Update Translations CLI command group.
 */
class FUT_CLI_Command {

	/**
	 * Shared helper instance.
	 *
	 * @var Force_Update_Translations
	 */
	protected $plugin;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->plugin = new Force_Update_Translations_CLI_Helper();
	}

	/**
	 * Update translations for a plugin or theme.
	 *
	 * ## OPTIONS
	 *
	 * <type>
	 * : Item type. plugin or theme.
	 *
	 * <slug>
	 * : Plugin directory slug or theme stylesheet / text domain.
	 *
	 * [--branch=<branch>]
	 * : For plugins only: stable or dev. Default: stable.
	 *
	 * ## EXAMPLES
	 *
	 *     wp fut update plugin akismet --branch=stable
	 *     wp fut update plugin akismet --branch=dev
	 *     wp fut update theme twentytwentyfour
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Associative args.
	 * @return void
	 */
	public function update( $args, $assoc_args ) {
		list( $type, $slug ) = $args;
		$type = sanitize_key( $type );
		$slug = sanitize_title( $slug );

		if ( ! in_array( $type, array( 'plugin', 'theme' ), true ) ) {
			WP_CLI::error( 'Type must be plugin or theme.' );
		}

		$branch = isset( $assoc_args['branch'] ) ? sanitize_key( $assoc_args['branch'] ) : 'stable';
		if ( 'plugin' === $type && ! in_array( $branch, array( 'stable', 'dev' ), true ) ) {
			WP_CLI::error( 'Branch must be stable or dev.' );
		}

		$project = $this->build_project( $type, $slug, $branch );
		if ( is_wp_error( $project ) ) {
			WP_CLI::error( $project->get_error_message() );
		}

		$this->plugin->admin_notices = array();
		$this->plugin->get_files( array( $slug => $project ) );

		$notices = isset( $this->plugin->admin_notices[ $slug ] ) ? $this->plugin->admin_notices[ $slug ] : array();
		$had_error = false;
		foreach ( $notices as $notice ) {
			$message = wp_strip_all_tags( $notice['content'] );
			if ( 'error' === $notice['status'] ) {
				$had_error = true;
				WP_CLI::warning( $message );
			} else {
				WP_CLI::log( $message );
			}
		}

		if ( $had_error ) {
			WP_CLI::error( 'Translation update finished with errors.' );
		}

		WP_CLI::success( 'Translation update completed.' );
	}

	/**
	 * List forced translations currently protected from language-pack overwrites.
	 *
	 * ## EXAMPLES
	 *
	 *     wp fut list-forced
	 *
	 * @subcommand list-forced
	 * @return void
	 */
	public function list_forced() {
		$forced = $this->plugin->get_forced_translations();
		if ( empty( $forced ) ) {
			WP_CLI::log( 'No forced translations are protected.' );
			return;
		}

		$items = array();
		foreach ( $forced as $key => $entry ) {
			$items[] = array(
				'key'    => $key,
				'type'   => isset( $entry['type'] ) ? $entry['type'] : '',
				'slug'   => isset( $entry['slug'] ) ? $entry['slug'] : '',
				'locale' => isset( $entry['locale'] ) ? $entry['locale'] : '',
				'branch' => isset( $entry['branch'] ) ? $entry['branch'] : '',
				'updated'=> isset( $entry['updated'] ) ? gmdate( 'c', (int) $entry['updated'] ) : '',
			);
		}

		WP_CLI\Utils\format_items( 'table', $items, array( 'key', 'type', 'slug', 'locale', 'branch', 'updated' ) );
	}

	/**
	 * Clear forced-translation protection entries.
	 *
	 * ## OPTIONS
	 *
	 * [--type=<type>]
	 * : Optional type filter: plugin or theme.
	 *
	 * [--slug=<slug>]
	 * : Optional slug filter.
	 *
	 * ## EXAMPLES
	 *
	 *     wp fut clear-forced
	 *     wp fut clear-forced --type=plugin --slug=akismet
	 *
	 * @subcommand clear-forced
	 * @param array $args       Positional args.
	 * @param array $assoc_args Associative args.
	 * @return void
	 */
	public function clear_forced( $args, $assoc_args ) {
		$type = isset( $assoc_args['type'] ) ? sanitize_key( $assoc_args['type'] ) : '';
		$slug = isset( $assoc_args['slug'] ) ? sanitize_title( $assoc_args['slug'] ) : '';

		if ( '' !== $type && ! in_array( $type, array( 'plugin', 'theme' ), true ) ) {
			WP_CLI::error( 'Type must be plugin or theme.' );
		}

		$removed = $this->plugin->clear_forced_translations( $type, $slug );
		WP_CLI::success( sprintf( 'Cleared protection for %d translation(s).', $removed ) );
	}

	/**
	 * Build a project array for get_files().
	 *
	 * @param string $type   plugin|theme.
	 * @param string $slug   Slug.
	 * @param string $branch Branch for plugins.
	 * @return array|WP_Error
	 */
	protected function build_project( $type, $slug, $branch ) {
		if ( 'plugin' === $type ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugins     = get_plugins();
			$plugin_file = '';
			$plugin_data = null;
			foreach ( $plugins as $file => $data ) {
				if ( dirname( $file ) === $slug ) {
					$plugin_file = $file;
					$plugin_data = $data;
					break;
				}
			}
			if ( ! $plugin_file ) {
				return new WP_Error( 'fut_cli_plugin', sprintf( 'Plugin slug not found: %s', $slug ) );
			}

			return array(
				'type'        => 'plugin',
				'branch'      => $branch,
				'sub_project' => array(
					'slug' => $slug,
					'name' => $plugin_data['Name'],
				),
			);
		}

		$theme = wp_get_theme( $slug );
		if ( ! $theme->exists() ) {
			return new WP_Error( 'fut_cli_theme', sprintf( 'Theme not found: %s', $slug ) );
		}

		$text_domain = $theme->get( 'TextDomain' );
		if ( ! $text_domain ) {
			$text_domain = $slug;
		}

		return array(
			'type'        => 'theme',
			'sub_project' => array(
				'slug' => $text_domain,
				'name' => $theme->get( 'Name' ),
			),
		);
	}
}

/**
 * Helper that exposes Force_Update_Translations methods without re-including admin bootstraps.
 */
class Force_Update_Translations_CLI_Helper extends Force_Update_Translations {
	/**
	 * Avoid re-including plugins/themes/settings from the parent constructor.
	 */
	public function __construct() {
		// Intentionally empty: CLI uses methods only.
	}
}

WP_CLI::add_command( 'fut', 'FUT_CLI_Command' );
