<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mycompassconsulting.com
 * @since      0.0.0
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/public
 * @author     Your Name <email@example.com>
 */
class Xophz_Compass_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 * @var      string    $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.0
	 * @param      string    $xophz_compass       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xophz_Compass_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xophz_Compass_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/xophz-compass-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xophz_Compass_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xophz_Compass_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/xophz-compass-public.js', array( 'jquery' ), $this->version, false );

	}

  public static function isBotRequest() {
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
    if (empty($user_agent)) {
      return true;
    }
    $bots = array('bot', 'crawler', 'spider', 'slurp', 'lighthouse', 'gtmetrix', 'pingdom', 'headless', 'python', 'curl', 'wget');
    foreach ($bots as $bot) {
      if (strpos($user_agent, $bot) !== false) {
        return true;
      }
    }
    return false;
  }

  public static function incrementPostViews($postID) {
    $postID = (int) $postID;
    if ( ! $postID || get_post_status($postID) !== 'publish' ) {
      return false;
    }
    if ( self::isBotRequest() ) {
      return false;
    }

    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '127.0.0.1';
    $transient_key = 'compass_vlock_' . md5($ip . '_' . $postID);
    if ( get_transient($transient_key) ) {
      return false;
    }
    set_transient($transient_key, 1, HOUR_IN_SECONDS);

    global $wpdb;
    $count_key = 'post_views_count';
    $existing = get_post_meta($postID, $count_key, true);

    if ( '' === $existing || false === $existing ) {
      add_post_meta($postID, $count_key, 1, true);
    } else {
      $wpdb->query(
        $wpdb->prepare(
          "UPDATE {$wpdb->postmeta} SET meta_value = meta_value + 1 WHERE post_id = %d AND meta_key = %s",
          $postID,
          $count_key
        )
      );
      wp_cache_delete($postID, 'post_meta');
    }
    return true;
  }

  public function setPostViews() {
    if ( ! is_single() && ! is_page() ) {
      return;
    }
    global $wp_query;
    if ( ! isset( $wp_query->post ) || ! is_object( $wp_query->post ) ) {
      return;
    }
    self::incrementPostViews( $wp_query->post->ID );
  }

  public function enable_rest_api() {
    return true;
  }

  public function inject_aeo_json_ld() {
    if ( ! get_option( 'xophz_compass_aeo_enabled', true ) ) {
        return;
    }

    $org_name = get_option( 'xophz_compass_aeo_org_name' );
    if ( empty( $org_name ) ) {
        $org_name = get_bloginfo( 'name' );
    }

    $logo_url = get_option( 'xophz_compass_aeo_logo_url' );
    if ( empty( $logo_url ) && has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }

    $publisher = array(
        '@type' => 'Organization',
        'name'  => $org_name,
    );

    if ( ! empty( $logo_url ) ) {
        $publisher['logo'] = array(
            '@type' => 'ImageObject',
            'url'   => $logo_url,
        );
    }

    $schema = array(
        '@context'  => 'https://schema.org',
        '@graph'    => array()
    );

    $schema['@graph'][] = array(
        '@type' => 'WebSite',
        '@id'   => home_url( '/#website' ),
        'url'   => home_url( '/' ),
        'name'  => get_bloginfo( 'name' ),
        'publisher' => $publisher,
    );

    if ( is_singular() ) {
        global $post;
        
        // Prevent duplicate injection if it's a specific type handled by its own plugin (like recipes)
        if ( 'ks_saved_recipe' !== $post->post_type ) {
            $article = array(
                '@type'    => 'Article',
                '@id'      => get_permalink() . '#article',
                'isPartOf' => array( '@id' => home_url( '/#website' ) ),
                'headline' => get_the_title(),
                'datePublished' => get_the_date( 'c' ),
                'dateModified'  => get_the_modified_date( 'c' ),
                'author'   => array(
                    '@type' => 'Person',
                    'name'  => get_the_author_meta( 'display_name', $post->post_author ),
                ),
                'publisher' => $publisher,
            );

            if ( has_post_thumbnail() ) {
                $article['image'] = get_the_post_thumbnail_url( $post->ID, 'full' );
            }

            $schema['@graph'][] = $article;
        }
    }

    echo '<script type="application/ld+json" class="compass-aeo-json-ld">' . wp_json_encode( $schema ) . '</script>' . "\n";
  }

  public function serve_llms_txt() {
    if ( ! get_option( 'xophz_compass_aeo_enabled', true ) ) {
        return;
    }

    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    $path = parse_url( $request_uri, PHP_URL_PATH );

    if ( '/llms.txt' === $path ) {
        header( 'Content-Type: text/plain; charset=utf-8' );
        
        $org_name = get_option( 'xophz_compass_aeo_org_name' );
        if ( empty( $org_name ) ) {
            $org_name = get_bloginfo( 'name' );
        }

        echo "# " . $org_name . " - LLM Manifest\n\n";
        echo "This file provides a structured overview of the public content available on this site, optimized for Answer Engines and AI agents.\n\n";
        
        echo "## Organization\n";
        echo "- Name: " . $org_name . "\n";
        echo "- Description: " . get_bloginfo( 'description' ) . "\n";
        echo "- URL: " . home_url( '/' ) . "\n\n";

        // Generate a list of recent public posts (or pages) as a reference point
        echo "## Recent Content\n";
        $recent_posts = get_posts( array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
        ) );

        if ( ! empty( $recent_posts ) ) {
            foreach ( $recent_posts as $post ) {
                echo "- [" . get_the_title( $post->ID ) . "](" . get_permalink( $post->ID ) . ")\n";
            }
        } else {
            echo "No recent posts found.\n";
        }
        echo "\n";

        // Integrate with Kitchen Synk if active
        if ( post_type_exists( 'ks_saved_recipe' ) ) {
            echo "## Kitchen Synk Recipes\n";
            $recipes = get_posts( array(
                'post_type'      => 'ks_saved_recipe',
                'post_status'    => 'publish',
                'posts_per_page' => 10,
            ) );
            if ( ! empty( $recipes ) ) {
                foreach ( $recipes as $recipe ) {
                    echo "- [" . get_the_title( $recipe->ID ) . "](" . get_permalink( $recipe->ID ) . ")\n";
                }
            } else {
                echo "No recent recipes found.\n";
            }
            echo "\n";
        }

        // Action hook for other plugins (like Kitchen Synk or Nook Phone) to append their own LLM data
        do_action( 'xophz_compass_llms_txt_output' );

        exit;
    }
  }

}
