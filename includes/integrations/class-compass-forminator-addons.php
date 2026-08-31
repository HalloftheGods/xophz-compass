<?php
/**
 * Forminator Pro Addons for Bomb Bag News Drip and Questbook CRM.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Forminator_Integration' ) ) {
	return;
}

// Bomb Bag Forminator Addon
if ( ! class_exists( 'Forminator_Integration_Bomb_Bag' ) ) {
	class Forminator_Integration_Bomb_Bag extends Forminator_Integration {
		protected static $instance = null;
		protected $_slug = 'bomb_bag';
		protected $_version = '1.0';
		protected $_min_forminator_version = '1.1';
		protected $_short_title = 'Bomb Bag';
		protected $_title = 'Bomb Bag News Drip';
		protected $_position = 1;
		protected $_image = '';
		protected $_icon = '';
		protected $_form_settings = 'Forminator_Bomb_Bag_Form_Settings';
		protected $_form_hooks = 'Forminator_Bomb_Bag_Form_Hooks';

		public function __construct() {
			$asset_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/';
			$this->_image       = $asset_url . 'xophz-compass-bomb-bag.svg';
			$this->_icon        = 'bomb_bag';
			$this->_description = esc_html__( 'Send form submissions directly into your Bomb Bag marketing lists and email journeys.', 'xophz-compass' );
			$this->_promotion   = esc_html__( 'Connected to Xophz COMPASS Bomb Bag engine.', 'xophz-compass' );
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function is_connected() {
			return true;
		}

		public function is_authorized() {
			return true;
		}

		public function is_module_connected( $module_id, $module_slug = 'form', $check_lead = false ) {
			return true;
		}

		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'setup_connect' ),
					'is_completed' => array( $this, 'is_connected' ),
				),
			);
		}

		public function setup_connect( $submitted_data, $form_id = 0 ) {
			return array(
				'html'       => '<div class="sui-box-body"><p>' . esc_html__( 'Bomb Bag is active and automatically connected to your COMPASS suite.', 'xophz-compass' ) . '</p></div>',
				'buttons'    => array(
					'submit' => array(
						'markup' => self::get_button_markup( esc_html__( 'Connected', 'xophz-compass' ), 'sui-button-primary forminator-addon-close' ),
					),
				),
				'redirect'   => false,
				'has_errors' => false,
			);
		}
	}

	class Forminator_Bomb_Bag_Form_Settings extends Forminator_Integration_Form_Settings {
		public function form_settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'setup_list' ),
					'is_completed' => array( $this, 'is_list_completed' ),
				),
			);
		}

		public function is_list_completed() {
			return true;
		}

		public function setup_list( $submitted_data ) {
			$multi_id = $this->generate_multi_id();
			if ( isset( $submitted_data['multi_id'] ) ) {
				$multi_id = $submitted_data['multi_id'];
			}

			global $wpdb;
			$lists_table = $wpdb->prefix . 'bomb_bag_lists';
			$options_html = '<option value="">' . esc_html__( '-- Select Bomb Bag List --', 'xophz-compass' ) . '</option>';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$lists_table'" ) === $lists_table ) {
				$results = $wpdb->get_results( "SELECT id, name FROM $lists_table ORDER BY name ASC" );
				if ( ! empty( $results ) ) {
					foreach ( $results as $l ) {
						$options_html .= '<option value="' . esc_attr( $l->id ) . '">' . esc_html( $l->name ) . '</option>';
					}
				}
			}

			$html = '<div class="sui-box-body">'
				. '<p>' . esc_html__( 'All form submissions are automatically routed to your Bomb Bag email marketing lists and campaigns.', 'xophz-compass' ) . '</p>'
				. '<div class="sui-form-field">'
				. '<label class="sui-label">' . esc_html__( 'Target List', 'xophz-compass' ) . '</label>'
				. '<select name="list_id" class="sui-select">' . $options_html . '</select>'
				. '</div>'
				. '</div>';

			return array(
				'html'       => $html,
				'buttons'    => array(
					'submit' => array(
						'markup' => Forminator_Integration::get_button_markup( esc_html__( 'Save', 'xophz-compass' ), 'sui-button-primary forminator-addon-finish' ),
					),
				),
				'redirect'   => false,
				'has_errors' => false,
			);
		}
	}

	class Forminator_Bomb_Bag_Form_Hooks extends Forminator_Integration_Form_Hooks {
		public function on_form_submit( $submitted_data ) {
			return true;
		}
	}
}

// Questbook Forminator Addon
if ( ! class_exists( 'Forminator_Integration_Questbook' ) ) {
	class Forminator_Integration_Questbook extends Forminator_Integration {
		protected static $instance = null;
		protected $_slug = 'questbook';
		protected $_version = '1.0';
		protected $_min_forminator_version = '1.1';
		protected $_short_title = 'Questbook CRM';
		protected $_title = 'Questbook CRM';
		protected $_position = 2;
		protected $_image = '';
		protected $_icon = '';
		protected $_form_settings = 'Forminator_Questbook_Form_Settings';
		protected $_form_hooks = 'Forminator_Questbook_Form_Hooks';

		public function __construct() {
			$asset_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/';
			$this->_image       = $asset_url . 'xophz-compass-quests.svg';
			$this->_icon        = 'questbook';
			$this->_description = esc_html__( 'Route leads from Forminator forms into your Questbook CRM pipeline, directory, and contact activity logs.', 'xophz-compass' );
			$this->_promotion   = esc_html__( 'Connected to Xophz COMPASS Questbook CRM engine.', 'xophz-compass' );
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function is_connected() {
			return true;
		}

		public function is_authorized() {
			return true;
		}

		public function is_module_connected( $module_id, $module_slug = 'form', $check_lead = false ) {
			return true;
		}

		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'setup_connect' ),
					'is_completed' => array( $this, 'is_connected' ),
				),
			);
		}

		public function setup_connect( $submitted_data, $form_id = 0 ) {
			return array(
				'html'       => '<div class="sui-box-body"><p>' . esc_html__( 'Questbook CRM is connected and automatically ready to receive form leads.', 'xophz-compass' ) . '</p></div>',
				'buttons'    => array(
					'submit' => array(
						'markup' => self::get_button_markup( esc_html__( 'Connected', 'xophz-compass' ), 'sui-button-primary forminator-addon-close' ),
					),
				),
				'redirect'   => false,
				'has_errors' => false,
			);
		}
	}

	class Forminator_Questbook_Form_Settings extends Forminator_Integration_Form_Settings {
		public function form_settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'setup_stage' ),
					'is_completed' => array( $this, 'is_stage_completed' ),
				),
			);
		}

		public function is_stage_completed() {
			return true;
		}

		public function setup_stage( $submitted_data ) {
			$html = '<div class="sui-box-body">'
				. '<p>' . esc_html__( 'Form submissions will automatically create or update contacts and log activity inside Questbook CRM.', 'xophz-compass' ) . '</p>'
				. '</div>';

			return array(
				'html'       => $html,
				'buttons'    => array(
					'submit' => array(
						'markup' => Forminator_Integration::get_button_markup( esc_html__( 'Save', 'xophz-compass' ), 'sui-button-primary forminator-addon-finish' ),
					),
				),
				'redirect'   => false,
				'has_errors' => false,
			);
		}
	}

	class Forminator_Questbook_Form_Hooks extends Forminator_Integration_Form_Hooks {
		public function on_form_submit( $submitted_data ) {
			return true;
		}
	}
}
