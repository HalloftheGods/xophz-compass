<?php

/**
 * Handles the "Members Only" content restriction functionality.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

class Xophz_Compass_Content_Restriction {

	public function register_hooks( $loader ) {
		$loader->add_action( 'init', $this, 'register_meta_field' );
		$loader->add_filter( 'the_content', $this, 'filter_content', 99 );

		// Quick Edit Integration
		$loader->add_action( 'quick_edit_custom_box', $this, 'quick_edit_box', 10, 2 );
		$loader->add_action( 'save_post', $this, 'save_post' );
		$loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_admin_scripts' );

		// Columns for Quick Edit JS to read
		$loader->add_filter( 'manage_posts_columns', $this, 'add_custom_column' );
		$loader->add_filter( 'manage_pages_columns', $this, 'add_custom_column' );
		$loader->add_action( 'manage_posts_custom_column', $this, 'custom_column_content', 10, 2 );
		$loader->add_action( 'manage_pages_custom_column', $this, 'custom_column_content', 10, 2 );

		// Standard Editor Meta Box Integration
		$loader->add_action( 'add_meta_boxes', $this, 'add_meta_box' );
	}

	public function register_meta_field() {
		$args = array(
			'type'         => 'boolean',
			'description'  => 'Requires Login to View (Members Only)',
			'single'       => true,
			'show_in_rest' => true,
			'default'      => false,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		);
		register_meta( 'post', '_compass_requires_login', $args );

		$args_tiers = array(
			'type'         => 'array',
			'description'  => 'Allowed User Tiers',
			'single'       => true,
			'show_in_rest' => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' )
				)
			),
			'default'      => array(),
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		);
		register_meta( 'post', '_compass_allowed_tiers', $args_tiers );
	}

	public function filter_content( $content ) {
		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$requires_login = get_post_meta( get_the_ID(), '_compass_requires_login', true );
		
		if ( $requires_login ) {
			$user_has_access = false;

			if ( is_user_logged_in() ) {
				$allowed_tiers = get_post_meta( get_the_ID(), '_compass_allowed_tiers', true );
				if ( empty( $allowed_tiers ) || ! is_array( $allowed_tiers ) ) {
					// No specific tiers set, any logged in user can view
					$user_has_access = true;
				} else {
					$user = wp_get_current_user();
					$user_roles = (array) $user->roles;
					// Check if user has at least one of the allowed roles, or is super admin / admin
					if ( in_array( 'administrator', $user_roles ) || is_super_admin( $user->ID ) ) {
						$user_has_access = true;
					} else {
						foreach ( $allowed_tiers as $tier ) {
							if ( in_array( $tier, $user_roles ) ) {
								$user_has_access = true;
								break;
							}
						}
					}
				}
			}

			if ( ! $user_has_access ) {
				$login_args = array(
					'echo'     => false,
					'redirect' => get_permalink(),
				);
				$login_form = wp_login_form( $login_args );

				$message = ! is_user_logged_in() 
					? 'You must be authenticated to access this data.' 
					: 'Your clearance level is insufficient to access this data.';
					
				$form_html = ! is_user_logged_in() ? $login_form : '';

				return '
				<style>
					.compass-content-restriction form {
						text-align: left;
						max-width: 400px;
						margin: 0 auto;
						background: rgba(0, 0, 0, 0.2);
						padding: 1.5rem;
						border-radius: 8px;
						border: 1px solid rgba(98, 201, 255, 0.1);
					}
					.compass-content-restriction form p {
						margin-bottom: 1rem;
					}
					.compass-content-restriction form label {
						display: block;
						color: #e0e0e0;
						margin-bottom: 0.5rem;
						font-family: \'Inter\', sans-serif;
						font-size: 0.9rem;
					}
					.compass-content-restriction form input[type="text"],
					.compass-content-restriction form input[type="password"] {
						width: 100%;
						padding: 0.75rem;
						background: rgba(255, 255, 255, 0.05);
						border: 1px solid rgba(98, 201, 255, 0.3);
						border-radius: 4px;
						color: #fff;
						font-family: \'Inter\', sans-serif;
						box-sizing: border-box;
					}
					.compass-content-restriction form input[type="text"]:focus,
					.compass-content-restriction form input[type="password"]:focus {
						outline: none;
						border-color: #62c9ff;
						box-shadow: 0 0 8px rgba(98, 201, 255, 0.5);
					}
					.compass-content-restriction form input[type="submit"] {
						background: rgba(98, 201, 255, 0.1);
						border: 1px solid #62c9ff;
						color: #62c9ff;
						padding: 0.75rem 1.5rem;
						border-radius: 6px;
						text-decoration: none;
						font-weight: 600;
						display: inline-block;
						transition: all 0.2s ease;
						cursor: pointer;
						width: 100%;
						margin-top: 0.5rem;
						font-family: \'Inter\', sans-serif;
					}
					.compass-content-restriction form input[type="submit"]:hover {
						background: rgba(98, 201, 255, 0.2);
						box-shadow: 0 0 12px rgba(98, 201, 255, 0.4);
					}
					.compass-content-restriction .login-remember {
						display: flex;
						align-items: center;
						gap: 0.5rem;
					}
					.compass-content-restriction .login-remember label {
						margin-bottom: 0;
						display: inline;
					}
				</style>
				<div class="compass-content-restriction" style="background: rgba(10, 10, 10, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(98, 201, 255, 0.2); border-radius: 12px; padding: 2rem; text-align: center; margin: 2rem 0; box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
					<h3 style="color: #62c9ff; margin-top: 0; font-family: \'Inter\', sans-serif;">Classified Intel</h3>
					<p style="color: #e0e0e0; font-size: 1.1rem; margin-bottom: 1.5rem;">' . esc_html( $message ) . '</p>
					' . $form_html . '
				</div>';
			}
		}

		return $content;
	}

	public function add_custom_column( $columns ) {
		$columns['compass_members_only'] = 'Members Only';
		return $columns;
	}

	public function custom_column_content( $column_name, $post_id ) {
		if ( 'compass_members_only' === $column_name ) {
			$requires_login = get_post_meta( $post_id, '_compass_requires_login', true );
			$allowed_tiers  = get_post_meta( $post_id, '_compass_allowed_tiers', true );
			if ( ! is_array( $allowed_tiers ) ) {
				$allowed_tiers = array();
			}

			if ( $requires_login ) {
				echo '<span class="dashicons dashicons-lock compass-members-only-icon" style="color: #62c9ff;" title="Requires Login"></span>';
			}
			// Hidden div for Quick Edit JS to extract the value
			echo '<div class="compass_requires_login_value" style="display:none;">' . ( $requires_login ? '1' : '0' ) . '</div>';
			echo '<div class="compass_allowed_tiers_value" style="display:none;">' . esc_html( wp_json_encode( $allowed_tiers ) ) . '</div>';
		}
	}

	public function quick_edit_box( $column_name, $post_type ) {
		if ( 'compass_members_only' !== $column_name ) {
			return;
		}
		wp_nonce_field( 'compass_members_only_nonce', 'compass_members_only_nonce_field' );
		
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();
		?>
		<fieldset class="inline-edit-col-right" style="display:none;" id="compass-members-only-quick-edit-template">
			<em class="inline-edit-or compass-inline-edit-or alignleft" style="margin: 0.2em 0.5em 0;">
				&ndash;OR&ndash;
			</em>
			<label class="alignleft compass-requires-login-label">
				<input type="checkbox" name="_compass_requires_login" value="1" class="compass-requires-login-checkbox">
				<span class="checkbox-title">Users Only</span>
			</label>
			<label class="alignleft compass-allowed-tiers-label" style="display:none; margin-left: 0.5em; margin-top: -0.2em;">
				<select name="_compass_allowed_tiers[]" multiple="multiple" class="compass-allowed-tiers-select" style="max-width: 150px; height: 4em; vertical-align: top;">
					<?php foreach ( $roles as $role_slug => $role_name ) : ?>
						<option value="<?php echo esc_attr( $role_slug ); ?>"><?php echo esc_html( $role_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</fieldset>
		<?php
	}

	public function add_meta_box() {
		add_meta_box(
			'compass_content_restriction_meta_box',
			'Access Control',
			array( $this, 'render_meta_box' ),
			array( 'post', 'page' ),
			'side',
			'high'
		);
	}

	public function render_meta_box( $post ) {
		wp_nonce_field( 'compass_members_only_nonce', 'compass_members_only_nonce_field' );
		$requires_login = get_post_meta( $post->ID, '_compass_requires_login', true );
		$allowed_tiers  = get_post_meta( $post->ID, '_compass_allowed_tiers', true );
		if ( ! is_array( $allowed_tiers ) ) {
			$allowed_tiers = array();
		}
		
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();
		?>
		<div class="compass-access-control-box" style="padding: 10px 0;">
			<label style="display: flex; align-items: center; gap: 8px; margin-bottom: 15px;">
				<input type="checkbox" name="_compass_requires_login" id="compass_requires_login_main" value="1" <?php checked( $requires_login, true ); ?>>
				<strong>Users Only (Requires Login)</strong>
			</label>
			
			<div id="compass_allowed_tiers_container" style="<?php echo $requires_login ? 'display:block;' : 'display:none;'; ?>">
				<p style="margin-bottom: 5px;"><strong>Allowed Tiers:</strong><br>
				<span class="description" style="font-size: 11px;">Leave empty to allow all authenticated users.<br>Hold CTRL/CMD to select multiple.</span></p>
				<select name="_compass_allowed_tiers[]" multiple="multiple" style="width: 100%; height: 120px;">
					<?php foreach ( $roles as $role_slug => $role_name ) : ?>
						<option value="<?php echo esc_attr( $role_slug ); ?>" <?php selected( in_array( $role_slug, $allowed_tiers ) ); ?>><?php echo esc_html( $role_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			
			<script>
				document.getElementById('compass_requires_login_main').addEventListener('change', function() {
					document.getElementById('compass_allowed_tiers_container').style.display = this.checked ? 'block' : 'none';
				});
			</script>

			<p class="description" style="margin-top: 15px; color: #666;">
				If checked, unauthorized users will see a "Classified Intel" gateway instead of the post content.
			</p>
		</div>
		<?php
	}

	public function enqueue_admin_scripts( $hook ) {
		if ( 'edit.php' !== $hook ) {
			return;
		}

		$js = "
		document.addEventListener('DOMContentLoaded', function() {
			var wp_inline_edit = inlineEditPost.edit;
			inlineEditPost.edit = function(id) {
				wp_inline_edit.apply(this, arguments);

				var post_id = 0;
				if (typeof(id) == 'object') {
					post_id = parseInt(this.getId(id));
				} else {
					post_id = parseInt(id);
				}

				if (post_id > 0) {
					var edit_row = document.getElementById('edit-' + post_id);
					var post_row = document.getElementById('post-' + post_id);

					// Move elements to be inline with Password and Private
					var privateLabel = edit_row.querySelector('.inline-edit-private');
					var template = edit_row.querySelector('#compass-members-only-quick-edit-template');
					if (privateLabel && template) {
						var orSpan = template.querySelector('.compass-inline-edit-or');
						var ourLabel = template.querySelector('.compass-requires-login-label');
						var tiersLabel = template.querySelector('.compass-allowed-tiers-label');
						
						if (orSpan && ourLabel && tiersLabel) {
							// Insert after privateLabel in reverse order
							privateLabel.parentNode.insertBefore(tiersLabel, privateLabel.nextSibling);
							privateLabel.parentNode.insertBefore(ourLabel, tiersLabel);
							privateLabel.parentNode.insertBefore(orSpan, ourLabel);
						}
					}

					var requires_login = post_row.querySelector('.compass_requires_login_value');
					var checkbox = edit_row.querySelector('.compass-requires-login-checkbox');
					var tiersLabel = edit_row.querySelector('.compass-allowed-tiers-label');
					var select = edit_row.querySelector('.compass-allowed-tiers-select');

					if (requires_login && checkbox) {
						var val = requires_login.textContent || requires_login.innerText;
						checkbox.checked = (val === '1');
						if (tiersLabel) {
							tiersLabel.style.display = checkbox.checked ? 'inline-block' : 'none';
						}
						
						// Toggle select visibility on checkbox change
						checkbox.addEventListener('change', function() {
							if (tiersLabel) {
								tiersLabel.style.display = this.checked ? 'inline-block' : 'none';
							}
						});
					}

					var allowed_tiers = post_row.querySelector('.compass_allowed_tiers_value');
					if (allowed_tiers && select) {
						try {
							var tiers = JSON.parse(allowed_tiers.textContent || allowed_tiers.innerText);
							Array.from(select.options).forEach(function(opt) {
								opt.selected = tiers.indexOf(opt.value) !== -1;
							});
						} catch(e) {}
					}
				}
			};
		});
		";

		wp_add_inline_script( 'inline-edit-post', $js );
	}

	public function save_post( $post_id ) {
		// Prevent autosaves
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Security checks
		if ( ! isset( $_POST['compass_members_only_nonce_field'] ) || ! wp_verify_nonce( $_POST['compass_members_only_nonce_field'], 'compass_members_only_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save the meta data
		if ( isset( $_POST['_compass_requires_login'] ) ) {
			update_post_meta( $post_id, '_compass_requires_login', true );
			
			if ( isset( $_POST['_compass_allowed_tiers'] ) && is_array( $_POST['_compass_allowed_tiers'] ) ) {
				$tiers = array_map( 'sanitize_text_field', $_POST['_compass_allowed_tiers'] );
				update_post_meta( $post_id, '_compass_allowed_tiers', $tiers );
			} else {
				update_post_meta( $post_id, '_compass_allowed_tiers', array() );
			}
		} else {
			// Checkbox was unchecked
			update_post_meta( $post_id, '_compass_requires_login', false );
			update_post_meta( $post_id, '_compass_allowed_tiers', array() );
		}
	}
}
