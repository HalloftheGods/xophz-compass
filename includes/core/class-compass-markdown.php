<?php
/**
 * Compass Markdown Transformer & Document Engine.
 * Converts WordPress posts and HTML documents into clean, LLM-ready Markdown with YAML frontmatter.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Markdown {

	/**
	 * Convert a WP_Post object into structured Markdown with YAML frontmatter.
	 *
	 * @param WP_Post $post WP_Post object.
	 * @param array   $extra_meta Optional extra frontmatter fields.
	 * @return string
	 */
	public static function from_post( WP_Post $post, array $extra_meta = array() ): string {
		$author_name = get_the_author_meta( 'display_name', $post->post_author );
		$permalink   = get_permalink( $post );
		$date        = get_the_date( 'c', $post );
		$modified    = get_the_modified_date( 'c', $post );
		$excerpt     = wp_strip_all_tags( $post->post_excerpt );

		// Collect taxonomy terms
		$categories = wp_get_post_terms( $post->ID, 'category', array( 'fields' => 'names' ) );
		$tags       = wp_get_post_terms( $post->ID, 'post_tag', array( 'fields' => 'names' ) );

		// Assemble Frontmatter
		$frontmatter = array(
			'title'     => $post->post_title,
			'url'       => $permalink,
			'author'    => $author_name,
			'published' => $date,
			'modified'  => $modified,
		);

		if ( ! empty( $excerpt ) ) {
			$frontmatter['description'] = $excerpt;
		}

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$frontmatter['categories'] = array_values( $categories );
		}

		if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
			$frontmatter['tags'] = array_values( $tags );
		}

		if ( ! empty( $extra_meta ) ) {
			$frontmatter = array_merge( $frontmatter, $extra_meta );
		}

		$output = self::render_yaml_frontmatter( $frontmatter );
		$output .= "\n\n# " . esc_html( $post->post_title ) . "\n\n";

		// Process raw or filtered content
		$content = apply_filters( 'the_content', $post->post_content );
		$output .= self::from_html( $content );

		return trim( $output ) . "\n";
	}

	/**
	 * Convert raw HTML string into clean Markdown.
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	public static function from_html( string $html ): string {
		if ( empty( $html ) ) {
			return '';
		}

		// 1. Remove scripts, styles, frames, and tracking tags
		$text = preg_replace( '@<(script|style|iframe|noscript|svg|canvas)[^>]*?>.*?</\\1>@si', '', $html );

		// 2. Normalize preformatted code blocks
		$text = preg_replace_callback( '/<pre[^>]*><code[^>]*>(.*?)<\/code><\/pre>/is', function( $matches ) {
			$code = html_entity_decode( $matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			return "\n\n```\n" . trim( $code ) . "\n```\n\n";
		}, $text );

		// 3. Inline code
		$text = preg_replace( '/<code[^>]*>(.*?)<\/code>/is', '`$1`', $text );

		// 4. Headings
		$text = preg_replace( '/<h1[^>]*>(.*?)<\/h1>/is', "\n\n# $1\n\n", $text );
		$text = preg_replace( '/<h2[^>]*>(.*?)<\/h2>/is', "\n\n## $1\n\n", $text );
		$text = preg_replace( '/<h3[^>]*>(.*?)<\/h3>/is', "\n\n### $1\n\n", $text );
		$text = preg_replace( '/<h4[^>]*>(.*?)<\/h4>/is', "\n\n#### $1\n\n", $text );
		$text = preg_replace( '/<h5[^>]*>(.*?)<\/h5>/is', "\n\n##### $1\n\n", $text );
		$text = preg_replace( '/<h6[^>]*>(.*?)<\/h6>/is', "\n\n###### $1\n\n", $text );

		// 5. Blockquotes
		$text = preg_replace_callback( '/<blockquote[^>]*>(.*?)<\/blockquote>/is', function( $m ) {
			$lines = explode( "\n", trim( wp_strip_all_tags( $m[1] ) ) );
			$quoted = array_map( function( $line ) {
				return '> ' . trim( $line );
			}, $lines );
			return "\n\n" . implode( "\n", $quoted ) . "\n\n";
		}, $text );

		// 6. Emphasis and strong
		$text = preg_replace( '/<(strong|b)[^>]*>(.*?)<\/(strong|b)>/is', '**$2**', $text );
		$text = preg_replace( '/<(em|i)[^>]*>(.*?)<\/(em|i)>/is', '*$2*', $text );

		// 7. Links [text](href)
		$text = preg_replace_callback( '/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', function( $m ) {
			$href = trim( $m[1] );
			$label = trim( wp_strip_all_tags( $m[2] ) );
			if ( empty( $label ) ) {
				$label = $href;
			}
			return '[' . $label . '](' . $href . ')';
		}, $text );

		// 8. Lists (ordered & unordered)
		$text = preg_replace( '/<li[^>]*>(.*?)<\/li>/is', "\n- $1", $text );
		$text = preg_replace( '/<\/(ul|ol)>/is', "\n", $text );

		// 9. Paragraphs and breaks
		$text = preg_replace( '/<p[^>]*>/is', "\n\n", $text );
		$text = preg_replace( '/<\/p>/is', "", $text );
		$text = preg_replace( '/<br\s*\/?>/is', "\n", $text );
		$text = preg_replace( '/<hr\s*\/?>/is', "\n\n---\n\n", $text );

		// 10. Strip remaining HTML tags
		$text = strip_tags( $text );

		// 11. Decode entities
		$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

		// 12. Normalize multiple newlines and trailing whitespace
		$text = preg_replace( "/[ \t]+/", ' ', $text );
		$text = preg_replace( "/\n{3,}/", "\n\n", $text );

		return trim( $text );
	}

	/**
	 * Render an associative array as YAML frontmatter.
	 *
	 * @param array<string, mixed> $data Key-value data.
	 * @return string
	 */
	public static function render_yaml_frontmatter( array $data ): string {
		$lines = array( '---' );
		foreach ( $data as $key => $val ) {
			if ( is_array( $val ) ) {
				$lines[] = $key . ':';
				foreach ( $val as $item ) {
					$lines[] = '  - ' . self::escape_yaml_value( (string) $item );
				}
			} elseif ( is_bool( $val ) ) {
				$lines[] = $key . ': ' . ( $val ? 'true' : 'false' );
			} elseif ( is_numeric( $val ) ) {
				$lines[] = $key . ': ' . $val;
			} else {
				$lines[] = $key . ': ' . self::escape_yaml_value( (string) $val );
			}
		}
		$lines[] = '---';
		return implode( "\n", $lines );
	}

	/**
	 * Escape YAML string value safely.
	 *
	 * @param string $str String value.
	 * @return string
	 */
	protected static function escape_yaml_value( string $str ): string {
		if ( strpos( $str, "\n" ) !== false || strpos( $str, ':' ) !== false || strpos( $str, '#' ) !== false || strpos( $str, '"' ) !== false ) {
			return '"' . str_replace( '"', '\\"', $str ) . '"';
		}
		return $str;
	}
}
