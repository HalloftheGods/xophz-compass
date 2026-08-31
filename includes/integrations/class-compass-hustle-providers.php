<?php
/**
 * Hustle Pro Providers for Bomb Bag News Drip and Questbook CRM.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hustle_Provider_Abstract' ) ) {
	return;
}

// Bomb Bag Hustle Provider
if ( ! class_exists( 'Hustle_Bomb_Bag' ) ) {
	class Hustle_Bomb_Bag extends Hustle_Provider_Abstract {
		const SLUG = 'bomb_bag';
		protected static $instance = null;
		protected $slug = 'bomb_bag';
		protected $version = '1.0';
		protected $class = __CLASS__;
		protected $title = 'Bomb Bag News Drip';
		protected $is_multi_on_global = false;
		protected $form_settings = 'Hustle_Bomb_Bag_Form_Settings';
		protected $form_hooks = 'Hustle_Bomb_Bag_Form_Hooks';
		protected $completion_options = array( 'active' );

		public function __construct() {
			$asset_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/';
			$this->icon_2x   = $asset_url . 'xophz-compass-bomb-bag.svg';
			$this->logo_2x   = $asset_url . 'xophz-compass-bomb-bag.svg';
			$this->banner_1x = $asset_url . 'xophz-compass-bomb-bag.svg';
			$this->banner_2x = $asset_url . 'xophz-compass-bomb-bag.svg';
			$this->short_description = esc_html__( 'Connect your Hustle popups, slide-ins, and embeds directly to Bomb Bag subscriber lists, email drip journeys, and broadcasts.', 'xophz-compass' );
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'configure_bomb_bag' ),
					'is_completed' => array( $this, 'settings_are_completed' ),
				),
			);
		}

		public function configure_bomb_bag( $submitted_data ) {
			$is_submit = isset( $submitted_data['hustle_is_submit'] );
			if ( $is_submit ) {
				if ( ! Hustle_Provider_Utils::is_provider_active( $this->slug ) ) {
					Hustle_Providers::get_instance()->activate_addon( $this->slug );
				}
				$this->save_settings_values( array( 'active' => 1 ) );

				return array(
					'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Bomb Bag Connected', 'xophz-compass' ), __( 'You can now assign any Hustle popup, slide-in, or embed to Bomb Bag subscriber lists.', 'xophz-compass' ) ),
					'buttons'      => array(
						'close' => array(
							'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'xophz-compass' ), 'sui-button-ghost', 'close' ),
						),
					),
					'redirect'     => false,
					'has_errors'   => false,
					'notification' => array(
						'type' => 'success',
						'text' => '<strong>' . $this->get_title() . '</strong> ' . esc_html__( 'Successfully connected to COMPASS engine', 'xophz-compass' ),
					),
				);
			}

			$options = array(
				array(
					'type'  => 'hidden',
					'name'  => 'active',
					'value' => 1,
				),
			);

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Connect Bomb Bag', 'xophz-compass' ), __( 'Activate Bomb Bag to send leads straight to your COMPASS email marketing lists.', 'xophz-compass' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$buttons = array(
				'connect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Activate Bomb Bag', 'xophz-compass' ), 'sui-button-primary', 'connect', true ),
				),
			);

			return array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => false,
			);
		}
	}

	class Hustle_Bomb_Bag_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {
		protected $form_completion_options = array( 'list_id' );

		public function form_settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'first_step_callback' ),
					'is_completed' => array( $this, 'first_step_is_completed' ),
				),
			);
		}

		public function first_step_is_completed() {
			$settings = $this->get_form_settings_values();
			return ! empty( $settings['list_id'] );
		}

		public function first_step_callback( $submitted_data ) {
			$this->addon_form_settings = $this->get_form_settings_values();
			$current_data = array(
				'list_id'  => '',
				'tag_name' => '',
			);
			$current_data = $this->get_current_data( $current_data, $submitted_data );
			$is_submit    = ! empty( $submitted_data['hustle_is_submit'] );

			global $wpdb;
			$lists_table = $wpdb->prefix . 'bomb_bag_lists';
			$lists = array( '' => __( '-- Select Bomb Bag List --', 'xophz-compass' ) );
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$lists_table'" ) === $lists_table ) {
				$results = $wpdb->get_results( "SELECT id, name FROM $lists_table ORDER BY name ASC" );
				if ( ! empty( $results ) ) {
					foreach ( $results as $l ) {
						$lists[ $l->id ] = esc_html( $l->name );
					}
				}
			}

			$selected_list = ! empty( $current_data['list_id'] ) ? $current_data['list_id'] : '';

			$options = array(
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						array(
							'type'  => 'label',
							'for'   => 'list_id',
							'value' => __( 'Bomb Bag Subscriber List', 'xophz-compass' ),
						),
						array(
							'type'     => 'select',
							'id'       => 'list_id',
							'name'     => 'list_id',
							'class'    => 'sui-select',
							'value'    => $selected_list,
							'selected' => $selected_list,
							'options'  => $lists,
						),
						array(
							'type'  => 'label',
							'for'   => 'tag_name',
							'value' => __( 'Optional Tag', 'xophz-compass' ),
						),
						array(
							'type'        => 'text',
							'id'          => 'tag_name',
							'name'        => 'tag_name',
							'value'       => ! empty( $current_data['tag_name'] ) ? $current_data['tag_name'] : '',
							'placeholder' => __( 'e.g. Website Lead, VIP Waitlist', 'xophz-compass' ),
						),
					),
				),
			);

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Bomb Bag List Setup', 'xophz-compass' ), __( 'Choose which Bomb Bag list newly collected emails should be saved to.', 'xophz-compass' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Disconnect', 'xophz-compass' ), 'sui-button-ghost', 'disconnect_form', true ),
				),
				'save'       => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Save', 'xophz-compass' ), 'sui-button-primary', 'connect', true ),
				),
			);

			if ( $is_submit ) {
				$this->save_form_settings_values( $current_data );
			}

			return array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => false,
			);
		}
	}

	class Hustle_Bomb_Bag_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {
		public function on_form_submit( $submitted_data, $allow_subscribed = true ) {
			return true;
		}
	}
}

// Questbook Hustle Provider
if ( ! class_exists( 'Hustle_Questbook' ) ) {
	class Hustle_Questbook extends Hustle_Provider_Abstract {
		const SLUG = 'questbook';
		protected static $instance = null;
		protected $slug = 'questbook';
		protected $version = '1.0';
		protected $class = __CLASS__;
		protected $title = 'Questbook CRM';
		protected $is_multi_on_global = false;
		protected $form_settings = 'Hustle_Questbook_Form_Settings';
		protected $form_hooks = 'Hustle_Questbook_Form_Hooks';
		protected $completion_options = array( 'active' );

		public function __construct() {
			$asset_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/';
			$this->icon_2x   = $asset_url . 'xophz-compass-quests.svg';
			$this->logo_2x   = $asset_url . 'xophz-compass-quests.svg';
			$this->banner_1x = $asset_url . 'xophz-compass-quests.svg';
			$this->banner_2x = $asset_url . 'xophz-compass-quests.svg';
			$this->short_description = esc_html__( 'Capture opt-ins into your Questbook CRM pipeline, track customer journey history, and trigger automated sales workflows.', 'xophz-compass' );
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'configure_questbook' ),
					'is_completed' => array( $this, 'settings_are_completed' ),
				),
			);
		}

		public function configure_questbook( $submitted_data ) {
			$is_submit = isset( $submitted_data['hustle_is_submit'] );
			if ( $is_submit ) {
				if ( ! Hustle_Provider_Utils::is_provider_active( $this->slug ) ) {
					Hustle_Providers::get_instance()->activate_addon( $this->slug );
				}
				$this->save_settings_values( array( 'active' => 1 ) );

				return array(
					'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Questbook CRM Connected', 'xophz-compass' ), __( 'You can now route form submissions straight into your Questbook CRM directory and pipeline.', 'xophz-compass' ) ),
					'buttons'      => array(
						'close' => array(
							'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'xophz-compass' ), 'sui-button-ghost', 'close' ),
						),
					),
					'redirect'     => false,
					'has_errors'   => false,
					'notification' => array(
						'type' => 'success',
						'text' => '<strong>' . $this->get_title() . '</strong> ' . esc_html__( 'Successfully connected to COMPASS CRM', 'xophz-compass' ),
					),
				);
			}

			$options = array(
				array(
					'type'  => 'hidden',
					'name'  => 'active',
					'value' => 1,
				),
			);

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Connect Questbook CRM', 'xophz-compass' ), __( 'Activate Questbook CRM to automatically capture leads and log interactions.', 'xophz-compass' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$buttons = array(
				'connect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Activate Questbook CRM', 'xophz-compass' ), 'sui-button-primary', 'connect', true ),
				),
			);

			return array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => false,
			);
		}
	}

	class Hustle_Questbook_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {
		protected $form_completion_options = array( 'lead_status' );

		public function form_settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'first_step_callback' ),
					'is_completed' => array( $this, 'first_step_is_completed' ),
				),
			);
		}

		public function first_step_is_completed() {
			$settings = $this->get_form_settings_values();
			return ! empty( $settings['lead_status'] );
		}

		public function first_step_callback( $submitted_data ) {
			$this->addon_form_settings = $this->get_form_settings_values();
			$current_data = array(
				'lead_status' => 'New Lead',
				'stage'       => 'New',
			);
			$current_data = $this->get_current_data( $current_data, $submitted_data );
			$is_submit    = ! empty( $submitted_data['hustle_is_submit'] );

			$status_options = array(
				'New Lead'   => __( 'New Lead', 'xophz-compass' ),
				'Prospect'   => __( 'Prospect', 'xophz-compass' ),
				'Qualified'  => __( 'Qualified', 'xophz-compass' ),
				'Customer'   => __( 'Customer', 'xophz-compass' ),
			);

			$options = array(
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						array(
							'type'  => 'label',
							'for'   => 'lead_status',
							'value' => __( 'Default Lead Status', 'xophz-compass' ),
						),
						array(
							'type'     => 'select',
							'id'       => 'lead_status',
							'name'     => 'lead_status',
							'class'    => 'sui-select',
							'value'    => $current_data['lead_status'],
							'selected' => $current_data['lead_status'],
							'options'  => $status_options,
						),
					),
				),
			);

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Questbook CRM Setup', 'xophz-compass' ), __( 'Configure lead status when visitors submit this module.', 'xophz-compass' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Disconnect', 'xophz-compass' ), 'sui-button-ghost', 'disconnect_form', true ),
				),
				'save'       => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Save', 'xophz-compass' ), 'sui-button-primary', 'connect', true ),
				),
			);

			if ( $is_submit ) {
				$this->save_form_settings_values( $current_data );
			}

			return array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => false,
			);
		}
	}

	class Hustle_Questbook_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {
		public function on_form_submit( $submitted_data, $allow_subscribed = true ) {
			return true;
		}
	}
}
