<?php
/**
 * Unified WPMU DEV (Hustle Pro & Forminator Pro) Integration Engine for Xophz COMPASS.
 *
 * Centralizes provider registration, form hooks, submission routing, and historical
 * lead syncing for Bomb Bag News Drip and Questbook CRM.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Integrations {

	/**
	 * Singleton instance.
	 *
	 * @var Xophz_Compass_Integrations|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Xophz_Compass_Integrations
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	public function init_hooks() {
		// Hustle Pro integration hooks
		add_action( 'hustle_before_load_providers', array( $this, 'register_hustle_providers' ) );
		add_action( 'hustle_providers_loaded', array( $this, 'register_hustle_providers' ) );

		// Forminator Pro integration hooks
		add_action( 'forminator_before_load_addons', array( $this, 'register_forminator_addons' ) );
		add_action( 'forminator_addons_loaded', array( $this, 'register_forminator_addons' ) );

		// Fallback hooks to guarantee registration if loaded after plugin hooks fired
		add_action( 'init', array( $this, 'register_all_providers' ), 1 );
		add_action( 'admin_init', array( $this, 'register_all_providers' ), 1 );

		// Universal submission capture hooks
		add_action( 'hustle_form_submit_before_set_fields', array( $this, 'handle_hustle_submission' ), 10, 3 );
		add_action( 'hustle_after_optin', array( $this, 'handle_hustle_optin' ), 10, 3 );
		add_action( 'forminator_custom_form_submit_before_set_fields', array( $this, 'handle_forminator_submission' ), 10, 3 );

		// REST API endpoints
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register all providers.
	 */
	public function register_all_providers() {
		$this->register_hustle_providers();
		$this->register_forminator_addons();
	}

	/**
	 * Register Hustle Pro Providers (Bomb Bag & Questbook CRM).
	 */
	public function register_hustle_providers() {
		if ( ! class_exists( 'Hustle_Providers' ) ) {
			return;
		}

		$this->ensure_hustle_dependencies();

		if ( class_exists( 'Hustle_Bomb_Bag' ) ) {
			Hustle_Providers::get_instance()->register( 'Hustle_Bomb_Bag' );
			if ( ! Hustle_Provider_Utils::is_provider_active( 'bomb_bag' ) ) {
				Hustle_Providers::get_instance()->activate_addon( 'bomb_bag' );
			}
		}

		if ( class_exists( 'Hustle_Questbook' ) ) {
			Hustle_Providers::get_instance()->register( 'Hustle_Questbook' );
			if ( ! Hustle_Provider_Utils::is_provider_active( 'questbook' ) ) {
				Hustle_Providers::get_instance()->activate_addon( 'questbook' );
			}
		}
	}

	/**
	 * Register Forminator Pro Addons (Bomb Bag & Questbook CRM).
	 */
	public function register_forminator_addons() {
		if ( ! class_exists( 'Forminator_Integration_Loader' ) ) {
			return;
		}

		$this->ensure_forminator_dependencies();

		if ( class_exists( 'Forminator_Integration_Bomb_Bag' ) ) {
			$loader = Forminator_Integration_Loader::get_instance();
			$loader->register( 'Forminator_Integration_Bomb_Bag' );
			$is_active = method_exists( $loader, 'is_addon_active' ) ? $loader->is_addon_active( 'bomb_bag' ) : ( function_exists( 'forminator_addon_is_active' ) && forminator_addon_is_active( 'bomb_bag' ) );
			if ( ! $is_active && method_exists( $loader, 'activate_addon' ) ) {
				$loader->activate_addon( 'bomb_bag' );
			}
		}

		if ( class_exists( 'Forminator_Integration_Questbook' ) ) {
			$loader = Forminator_Integration_Loader::get_instance();
			$loader->register( 'Forminator_Integration_Questbook' );
			$is_active = method_exists( $loader, 'is_addon_active' ) ? $loader->is_addon_active( 'questbook' ) : ( function_exists( 'forminator_addon_is_active' ) && forminator_addon_is_active( 'questbook' ) );
			if ( ! $is_active && method_exists( $loader, 'activate_addon' ) ) {
				$loader->activate_addon( 'questbook' );
			}
		}
	}

	/**
	 * Ensure Hustle abstract classes and custom providers are loaded.
	 */
	private function ensure_hustle_dependencies() {
		if ( defined( 'HUSTLE_DIR' ) ) {
			$hustle_inc = HUSTLE_DIR . 'inc/';
			if ( file_exists( $hustle_inc . 'provider/class-hustle-provider-abstract.php' ) ) {
				require_once $hustle_inc . 'provider/class-hustle-provider-abstract.php';
			}
			if ( file_exists( $hustle_inc . 'provider/class-hustle-provider-form-settings-abstract.php' ) ) {
				require_once $hustle_inc . 'provider/class-hustle-provider-form-settings-abstract.php';
			}
			if ( file_exists( $hustle_inc . 'provider/class-hustle-provider-form-hooks-abstract.php' ) ) {
				require_once $hustle_inc . 'provider/class-hustle-provider-form-hooks-abstract.php';
			}
		} elseif ( defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/hustle/inc/provider/class-hustle-provider-abstract.php' ) ) {
			require_once WP_PLUGIN_DIR . '/hustle/inc/provider/class-hustle-provider-abstract.php';
			require_once WP_PLUGIN_DIR . '/hustle/inc/provider/class-hustle-provider-form-settings-abstract.php';
			require_once WP_PLUGIN_DIR . '/hustle/inc/provider/class-hustle-provider-form-hooks-abstract.php';
		}

		$hustle_providers_file = dirname( __FILE__ ) . '/integrations/class-compass-hustle-providers.php';
		if ( file_exists( $hustle_providers_file ) ) {
			require_once $hustle_providers_file;
		}
	}

	/**
	 * Ensure Forminator abstract classes and custom integrations are loaded.
	 */
	private function ensure_forminator_dependencies() {
		if ( function_exists( 'forminator_plugin_dir' ) ) {
			$forminator_lib = forminator_plugin_dir() . 'library/';
			if ( file_exists( $forminator_lib . 'addon/class-integration.php' ) ) {
				require_once $forminator_lib . 'addon/class-integration.php';
			}
			if ( file_exists( $forminator_lib . 'addon/class-integration-settings.php' ) ) {
				require_once $forminator_lib . 'addon/class-integration-settings.php';
			}
			if ( file_exists( $forminator_lib . 'addon/class-integration-form-settings.php' ) ) {
				require_once $forminator_lib . 'addon/class-integration-form-settings.php';
			}
			if ( file_exists( $forminator_lib . 'addon/class-integration-hooks.php' ) ) {
				require_once $forminator_lib . 'addon/class-integration-hooks.php';
			}
			if ( file_exists( $forminator_lib . 'addon/class-integration-form-hooks.php' ) ) {
				require_once $forminator_lib . 'addon/class-integration-form-hooks.php';
			}
		} elseif ( defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/forminator/library/addon/class-integration.php' ) ) {
			require_once WP_PLUGIN_DIR . '/forminator/library/addon/class-integration.php';
			require_once WP_PLUGIN_DIR . '/forminator/library/addon/class-integration-settings.php';
			require_once WP_PLUGIN_DIR . '/forminator/library/addon/class-integration-form-settings.php';
			require_once WP_PLUGIN_DIR . '/forminator/library/addon/class-integration-hooks.php';
			require_once WP_PLUGIN_DIR . '/forminator/library/addon/class-integration-form-hooks.php';
		}

		$forminator_addons_file = dirname( __FILE__ ) . '/integrations/class-compass-forminator-addons.php';
		if ( file_exists( $forminator_addons_file ) ) {
			require_once $forminator_addons_file;
		}
	}

	/**
	 * Handle Hustle form submissions (Popups, Slide-ins, Embeds).
	 *
	 * @param object $entry
	 * @param int $module_id
	 * @param array $field_data_array
	 */
	public function handle_hustle_submission( $entry, $module_id, $field_data_array ) {
		$email      = '';
		$first_name = '';
		$last_name  = '';
		$phone      = '';
		$message    = '';

		foreach ( $field_data_array as $field ) {
			$name  = isset( $field['name'] ) ? strtolower( $field['name'] ) : '';
			$value = isset( $field['value'] ) ? $field['value'] : '';

			if ( empty( $value ) ) {
				continue;
			}

			if ( strpos( $name, 'email' ) !== false ) {
				$email = sanitize_email( $value );
			} elseif ( strpos( $name, 'first_name' ) !== false || strpos( $name, 'fname' ) !== false ) {
				$first_name = sanitize_text_field( $value );
			} elseif ( strpos( $name, 'last_name' ) !== false || strpos( $name, 'lname' ) !== false ) {
				$last_name = sanitize_text_field( $value );
			} elseif ( strpos( $name, 'name' ) !== false ) {
				if ( is_array( $value ) ) {
					$first_name = isset( $value['first-name'] ) ? sanitize_text_field( $value['first-name'] ) : '';
					$last_name  = isset( $value['last-name'] ) ? sanitize_text_field( $value['last-name'] ) : '';
				} else {
					$parts = explode( ' ', sanitize_text_field( $value ), 2 );
					$first_name = $parts[0];
					$last_name  = isset( $parts[1] ) ? $parts[1] : '';
				}
			} elseif ( strpos( $name, 'phone' ) !== false ) {
				$phone = sanitize_text_field( $value );
			} elseif ( strpos( $name, 'message' ) !== false || strpos( $name, 'comment' ) !== false ) {
				$message = sanitize_textarea_field( $value );
			}
		}

		if ( empty( $email ) || ! is_email( $email ) ) {
			return;
		}

		// Resolve Module Name
		$module_title = 'Hustle Module #' . absint( $module_id );
		if ( class_exists( 'Hustle_Module_Model' ) ) {
			$mod = Hustle_Module_Model::get_instance( $module_id );
			if ( $mod && ! empty( $mod->module_name ) ) {
				$module_title = sanitize_text_field( $mod->module_name );
			}
		}

		$this->save_lead_to_bomb_bag( $email, $first_name, $last_name, $phone, 'Hustle: ' . $module_title, 'hustle', $module_id );
		$this->save_lead_to_questbook( $email, $first_name, $last_name, $phone, 'Hustle: ' . $module_title, $message, $module_id );
	}

	/**
	 * Handle Hustle Opt-In action hook.
	 *
	 * @param int $module_id
	 * @param string $email
	 * @param string $name
	 */
	public function handle_hustle_optin( $module_id, $email, $name ) {
		if ( empty( $email ) || ! is_email( $email ) ) {
			return;
		}

		$parts = explode( ' ', sanitize_text_field( $name ), 2 );
		$first_name = $parts[0] ?? '';
		$last_name  = $parts[1] ?? '';

		$this->save_lead_to_bomb_bag( $email, $first_name, $last_name, '', 'Hustle Opt-in #' . absint( $module_id ), 'hustle', $module_id );
		$this->save_lead_to_questbook( $email, $first_name, $last_name, '', 'Hustle Opt-in #' . absint( $module_id ), '', $module_id );
	}

	/**
	 * Handle Forminator form submissions.
	 *
	 * @param Forminator_Form_Entry_Model $entry
	 * @param int $form_id
	 * @param array $field_data_array
	 */
	public function handle_forminator_submission( $entry, $form_id, $field_data_array ) {
		$email      = '';
		$first_name = '';
		$last_name  = '';
		$phone      = '';
		$company    = '';
		$message    = '';
		$form_title = 'Forminator #' . absint( $form_id );

		if ( class_exists( 'Forminator_API' ) ) {
			$form_model = Forminator_API::get_form( $form_id );
			if ( $form_model && isset( $form_model->name ) ) {
				$form_title = sanitize_text_field( $form_model->name );
			}
		}

		foreach ( $field_data_array as $field ) {
			$name  = isset( $field['name'] ) ? strtolower( $field['name'] ) : '';
			$value = isset( $field['value'] ) ? $field['value'] : '';

			if ( empty( $value ) ) {
				continue;
			}

			if ( strpos( $name, 'email' ) === 0 ) {
				$email = sanitize_email( $value );
			} elseif ( strpos( $name, 'name' ) === 0 ) {
				if ( is_array( $value ) ) {
					$first_name = isset( $value['first-name'] ) ? sanitize_text_field( $value['first-name'] ) : '';
					$last_name  = isset( $value['last-name'] ) ? sanitize_text_field( $value['last-name'] ) : '';
				} else {
					$parts = explode( ' ', sanitize_text_field( $value ), 2 );
					$first_name = $parts[0];
					$last_name  = isset( $parts[1] ) ? $parts[1] : '';
				}
			} elseif ( strpos( $name, 'phone' ) === 0 ) {
				$phone = sanitize_text_field( $value );
			} elseif ( strpos( $name, 'text' ) === 0 && empty( $company ) ) {
				$company = sanitize_text_field( $value );
			} elseif ( strpos( $name, 'textarea' ) === 0 ) {
				$message = sanitize_textarea_field( $value );
			}
		}

		if ( empty( $email ) || ! is_email( $email ) ) {
			return;
		}

		$this->save_lead_to_bomb_bag( $email, $first_name, $last_name, $phone, 'Forminator: ' . $form_title, 'forminator', $form_id );
		$this->save_lead_to_questbook( $email, $first_name, $last_name, $phone, 'Forminator: ' . $form_title, $message, $form_id, $company );
	}

	/**
	 * Save lead to Bomb Bag subscribers, lists, and tags.
	 */
	public function save_lead_to_bomb_bag( $email, $first_name, $last_name, $phone, $source_name, $source_type = 'hustle', $module_id = 0 ) {
		global $wpdb;
		$sub_table             = $wpdb->prefix . 'bomb_bag_subscribers';
		$lists_table           = $wpdb->prefix . 'bomb_bag_lists';
		$list_sub_table        = $wpdb->prefix . 'bomb_bag_list_subscribers';
		$tags_table            = $wpdb->prefix . 'bomb_bag_tags';
		$subscriber_tags_table = $wpdb->prefix . 'bomb_bag_subscriber_tags';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$sub_table'" ) !== $sub_table ) {
			return;
		}

		// Find or create subscriber
		$subscriber = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $sub_table WHERE email = %s", $email ) );
		$subscriber_id = 0;

		if ( $subscriber ) {
			$subscriber_id = absint( $subscriber->id );
			$update = array();
			if ( ! empty( $first_name ) ) $update['first_name'] = $first_name;
			if ( ! empty( $last_name ) )  $update['last_name']  = $last_name;
			if ( ! empty( $update ) ) {
				$wpdb->update( $sub_table, $update, array( 'id' => $subscriber_id ) );
			}
		} else {
			$wpdb->insert( $sub_table, array(
				'email'         => $email,
				'first_name'    => $first_name,
				'last_name'     => $last_name,
				'status'        => 'active',
				'source'        => $source_type,
				'score'         => 10,
				'lead_status'   => 'warm',
				'subscribed_at' => current_time( 'mysql' ),
				'created_at'    => current_time( 'mysql' ),
			) );
			$subscriber_id = absint( $wpdb->insert_id );
		}

		if ( ! $subscriber_id ) {
			return;
		}

		// Find or create auto list
		$list_id = 0;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$lists_table'" ) === $lists_table ) {
			$auto_list_name = $source_name;
			$auto_list_slug = sanitize_title( $source_name );

			$existing_list = $wpdb->get_row( $wpdb->prepare(
				"SELECT id FROM $lists_table WHERE slug = %s OR name = %s LIMIT 1",
				$auto_list_slug, $auto_list_name
			) );

			if ( $existing_list ) {
				$list_id = absint( $existing_list->id );
			} else {
				$wpdb->insert( $lists_table, array(
					'name'        => $auto_list_name,
					'slug'        => $auto_list_slug,
					'description' => 'Automated list for submissions received from ' . $source_name,
					'created_at'  => current_time( 'mysql' ),
					'updated_at'  => current_time( 'mysql' ),
				) );
				$list_id = absint( $wpdb->insert_id );
			}

			// Assign to list
			if ( $list_id > 0 && $wpdb->get_var( "SHOW TABLES LIKE '$list_sub_table'" ) === $list_sub_table ) {
				$exists = $wpdb->get_var( $wpdb->prepare(
					"SELECT id FROM $list_sub_table WHERE subscriber_id = %d AND list_id = %d",
					$subscriber_id, $list_id
				) );
				if ( ! $exists ) {
					$wpdb->insert( $list_sub_table, array(
						'subscriber_id' => $subscriber_id,
						'list_id'       => $list_id,
						'status'        => 'subscribed',
						'subscribed_at' => current_time( 'mysql' ),
						'created_at'    => current_time( 'mysql' ),
					) );
				}
			}
		}

		// Auto tag
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$tags_table'" ) === $tags_table ) {
			$tag_slug = sanitize_title( $source_type . '-' . $module_id );
			$tag_name = $source_name;
			$existing_tag = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $tags_table WHERE slug = %s OR name = %s LIMIT 1", $tag_slug, $tag_name ) );
			$tag_id = $existing_tag ? absint( $existing_tag->id ) : 0;

			if ( ! $tag_id ) {
				$wpdb->insert( $tags_table, array(
					'name'       => $tag_name,
					'slug'       => $tag_slug,
					'color'      => '#62c9ff',
					'created_at' => current_time( 'mysql' ),
				) );
				$tag_id = absint( $wpdb->insert_id );
			}

			if ( $tag_id > 0 && $wpdb->get_var( "SHOW TABLES LIKE '$subscriber_tags_table'" ) === $subscriber_tags_table ) {
				$tag_map_exists = $wpdb->get_var( $wpdb->prepare(
					"SELECT id FROM $subscriber_tags_table WHERE subscriber_id = %d AND tag_id = %d",
					$subscriber_id, $tag_id
				) );
				if ( ! $tag_map_exists ) {
					$wpdb->insert( $subscriber_tags_table, array(
						'subscriber_id' => $subscriber_id,
						'tag_id'        => $tag_id,
						'created_at'    => current_time( 'mysql' ),
					) );
				}
			}
		}

		do_action( 'bomb_bag_subscriber_created', $subscriber_id, $list_id );
	}

	/**
	 * Save lead to Questbook CRM contacts, deals, and communication logs.
	 */
	public function save_lead_to_questbook( $email, $first_name, $last_name, $phone, $source_name, $message = '', $module_id = 0, $company = '' ) {
		global $wpdb;
		$contacts_table = $wpdb->prefix . 'xophz_qb_contacts';
		$logs_table     = $wpdb->prefix . 'xophz_qb_logs';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$contacts_table'" ) !== $contacts_table ) {
			return;
		}

		$existing = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM $contacts_table WHERE email = %s", $email ) );
		$contact_id = 0;

		if ( $existing ) {
			$contact_id = absint( $existing->id );
			$update = array();
			if ( ! empty( $first_name ) ) $update['first_name'] = $first_name;
			if ( ! empty( $last_name ) )  $update['last_name']  = $last_name;
			if ( ! empty( $phone ) )      $update['phone']      = $phone;
			if ( ! empty( $company ) )    $update['company']    = $company;
			if ( ! empty( $update ) ) {
				$wpdb->update( $contacts_table, $update, array( 'id' => $contact_id ) );
			}
		} else {
			$wpdb->insert( $contacts_table, array(
				'first_name'  => $first_name ?: 'New',
				'last_name'   => $last_name  ?: 'Lead',
				'email'       => $email,
				'phone'       => $phone,
				'company'     => $company,
				'source'      => $source_name,
				'lead_status' => 'New Lead',
				'created_at'  => current_time( 'mysql' ),
				'updated_at'  => current_time( 'mysql' ),
			) );
			$contact_id = absint( $wpdb->insert_id );
		}

		if ( $contact_id > 0 && $wpdb->get_var( "SHOW TABLES LIKE '$logs_table'" ) === $logs_table ) {
			$payload = "Inbound Submission from: " . $source_name;
			if ( ! empty( $message ) ) {
				$payload .= "\n\nMessage:\n" . $message;
			}

			$wpdb->insert( $logs_table, array(
				'contact_id'      => $contact_id,
				'log_type'        => 'webform',
				'direction'       => 'inbound',
				'message_payload' => $payload,
				'is_read'         => 'no',
				'created_at'      => current_time( 'mysql' ),
			) );
		}

		do_action( 'questbook_lead_captured', $contact_id, $source_name );
	}

	/**
	 * Register REST API routes.
	 */
	public function register_rest_routes() {
		register_rest_route( 'xophz-compass/v1', '/integrations/sync-hustle', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'rest_sync_hustle_entries' ),
			'permission_callback' => array( $this, 'permissions_check' ),
		) );

		register_rest_route( 'xophz-compass/v1', '/integrations/sync-forminator', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'rest_sync_forminator_entries' ),
			'permission_callback' => array( $this, 'permissions_check' ),
		) );

		register_rest_route( 'xophz-compass/v1', '/integrations/status', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'rest_get_status' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * Permission check.
	 */
	public function permissions_check( WP_REST_Request $request ) {
		return current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' );
	}

	/**
	 * Sync historical Hustle module entries into Bomb Bag and Questbook CRM.
	 */
	public function rest_sync_hustle_entries( WP_REST_Request $request ) {
		global $wpdb;
		$entries_table = $wpdb->prefix . 'hustle_entries';
		$meta_table    = $wpdb->prefix . 'hustle_entries_meta';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$entries_table'" ) !== $entries_table ) {
			return new WP_REST_Response( array(
				'success' => false,
				'message' => 'Hustle entries table not found.',
				'count'   => 0,
			), 404 );
		}

		$entries = $wpdb->get_results( "SELECT entry_id, module_id, created_date_sql FROM $entries_table ORDER BY entry_id ASC" );
		$synced_count = 0;

		foreach ( $entries as $entry ) {
			$metas = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $meta_table WHERE entry_id = %d", $entry->entry_id ) );
			$email = '';
			$first_name = '';
			$last_name = '';
			$phone = '';

			foreach ( $metas as $m ) {
				$k = strtolower( $m->meta_key );
				$v = maybe_unserialize( $m->meta_value );
				if ( is_string( $v ) && is_email( $v ) ) {
					$email = sanitize_email( $v );
				} elseif ( strpos( $k, 'first_name' ) !== false || strpos( $k, 'fname' ) !== false ) {
					$first_name = sanitize_text_field( $v );
				} elseif ( strpos( $k, 'last_name' ) !== false || strpos( $k, 'lname' ) !== false ) {
					$last_name = sanitize_text_field( $v );
				} elseif ( strpos( $k, 'name' ) !== false && empty( $first_name ) ) {
					if ( is_array( $v ) ) {
						$first_name = $v['first-name'] ?? '';
						$last_name  = $v['last-name'] ?? '';
					} else {
						$parts = explode( ' ', sanitize_text_field( $v ), 2 );
						$first_name = $parts[0];
						$last_name  = $parts[1] ?? '';
					}
				} elseif ( strpos( $k, 'phone' ) !== false ) {
					$phone = sanitize_text_field( $v );
				}
			}

			if ( ! empty( $email ) && is_email( $email ) ) {
				$module_name = 'Hustle Module #' . absint( $entry->module_id );
				if ( class_exists( 'Hustle_Module_Model' ) ) {
					$mod = Hustle_Module_Model::get_instance( $entry->module_id );
					if ( $mod && ! empty( $mod->module_name ) ) {
						$module_name = sanitize_text_field( $mod->module_name );
					}
				}

				$this->save_lead_to_bomb_bag( $email, $first_name, $last_name, $phone, 'Hustle: ' . $module_name, 'hustle', $entry->module_id );
				$this->save_lead_to_questbook( $email, $first_name, $last_name, $phone, 'Hustle: ' . $module_name, 'Imported from Hustle entry #' . $entry->entry_id, $entry->module_id );
				$synced_count++;
			}
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => sprintf( 'Successfully synced %d Hustle entries into Bomb Bag and Questbook CRM.', $synced_count ),
			'count'   => $synced_count,
		) );
	}

	/**
	 * Sync historical Forminator form entries into Bomb Bag and Questbook CRM.
	 */
	public function rest_sync_forminator_entries( WP_REST_Request $request ) {
		global $wpdb;
		$entries_table = $wpdb->prefix . 'frmt_form_entry';
		$meta_table    = $wpdb->prefix . 'frmt_form_entry_meta';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$entries_table'" ) !== $entries_table ) {
			return new WP_REST_Response( array(
				'success' => false,
				'message' => 'Forminator entries table not found.',
				'count'   => 0,
			), 404 );
		}

		$entries = $wpdb->get_results( "SELECT entry_id, form_id, date_created_sql FROM $entries_table ORDER BY entry_id ASC" );
		$synced_count = 0;

		foreach ( $entries as $entry ) {
			$metas = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $meta_table WHERE entry_id = %d", $entry->entry_id ) );
			$email = '';
			$first_name = '';
			$last_name = '';
			$phone = '';

			foreach ( $metas as $m ) {
				$k = strtolower( $m->meta_key );
				$v = maybe_unserialize( $m->meta_value );
				if ( is_string( $v ) && is_email( $v ) ) {
					$email = sanitize_email( $v );
				} elseif ( strpos( $k, 'name' ) !== false && empty( $first_name ) ) {
					if ( is_array( $v ) ) {
						$first_name = $v['first-name'] ?? '';
						$last_name  = $v['last-name'] ?? '';
					} else {
						$parts = explode( ' ', sanitize_text_field( $v ), 2 );
						$first_name = $parts[0];
						$last_name  = $parts[1] ?? '';
					}
				} elseif ( strpos( $k, 'phone' ) !== false ) {
					$phone = sanitize_text_field( $v );
				}
			}

			if ( ! empty( $email ) && is_email( $email ) ) {
				$form_title = 'Form #' . absint( $entry->form_id );
				if ( class_exists( 'Forminator_API' ) ) {
					$form_model = Forminator_API::get_form( $entry->form_id );
					if ( $form_model && isset( $form_model->name ) ) {
						$form_title = sanitize_text_field( $form_model->name );
					}
				}

				$this->save_lead_to_bomb_bag( $email, $first_name, $last_name, $phone, 'Forminator: ' . $form_title, 'forminator', $entry->form_id );
				$this->save_lead_to_questbook( $email, $first_name, $last_name, $phone, 'Forminator: ' . $form_title, 'Imported from Forminator entry #' . $entry->entry_id, $entry->form_id );
				$synced_count++;
			}
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => sprintf( 'Successfully synced %d Forminator entries into Bomb Bag and Questbook CRM.', $synced_count ),
			'count'   => $synced_count,
		) );
	}

	/**
	 * Get Integration Status.
	 */
	public function rest_get_status( WP_REST_Request $request ) {
		$hustle_active     = class_exists( 'Hustle_Providers' );
		$forminator_active = class_exists( 'Forminator_Integration_Loader' );

		return rest_ensure_response( array(
			'hustle'     => array(
				'active'     => $hustle_active,
				'bomb_bag'   => true,
				'questbook'  => true,
			),
			'forminator' => array(
				'active'     => $forminator_active,
				'bomb_bag'   => true,
				'questbook'  => true,
			),
		) );
	}
}
