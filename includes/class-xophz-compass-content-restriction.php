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
	}

	public function filter_content( $content ) {
		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$requires_login = get_post_meta( get_the_ID(), '_compass_requires_login', true );
		
		if ( $requires_login && ! is_user_logged_in() ) {
			$login_url = esc_url( wp_login_url( get_permalink() ) );
			return '
			<div class="compass-content-restriction" style="background: rgba(10, 10, 10, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(98, 201, 255, 0.2); border-radius: 12px; padding: 2rem; text-align: center; margin: 2rem 0; box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
				<h3 style="color: #62c9ff; margin-top: 0; font-family: \'Inter\', sans-serif;">Classified Intel</h3>
				<p style="color: #e0e0e0; font-size: 1.1rem; margin-bottom: 1.5rem;">You must be authenticated to access this data.</p>
				<a href="' . $login_url . '" style="background: rgba(98, 201, 255, 0.1); border: 1px solid #62c9ff; color: #62c9ff; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.2s ease;">Authenticate Now</a>
			</div>';
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
			if ( $requires_login ) {
				echo '<span class="dashicons dashicons-lock compass-members-only-icon" style="color: #62c9ff;" title="Requires Login"></span>';
			}
			// Hidden div for Quick Edit JS to extract the value
			echo '<div class="compass_requires_login_value" style="display:none;">' . ( $requires_login ? '1' : '0' ) . '</div>';
		}
	}

	public function quick_edit_box( $column_name, $post_type ) {
		if ( 'compass_members_only' !== $column_name ) {
			return;
		}
		wp_nonce_field( 'compass_members_only_nonce', 'compass_members_only_nonce_field' );
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label class="alignleft" style="margin-top: 1em;">
					<input type="checkbox" name="_compass_requires_login" value="1" class="compass-requires-login-checkbox">
					<span class="checkbox-title">Members Only (Requires Login)</span>
				</label>
			</div>
		</fieldset>
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

					var requires_login = post_row.querySelector('.compass_requires_login_value');
					if (requires_login) {
						var val = requires_login.textContent || requires_login.innerText;
						var checkbox = edit_row.querySelector('.compass-requires-login-checkbox');
						if (checkbox) {
							checkbox.checked = (val === '1');
						}
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
		} else {
			// Checkbox was unchecked
			update_post_meta( $post_id, '_compass_requires_login', false );
		}
	}
}
