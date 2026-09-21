<?php
/**
 * AI Bot Detector & User Agent Intelligence Engine.
 * Identifies generative AI crawlers, retrieval agents, and training scrapers.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_AI_Detector {

	/**
	 * Recognized AI Bots catalog with classification and provider metadata.
	 *
	 * @var array<string, array<string, string>>
	 */
	protected static array $bot_catalog = array(
		'perplexitybot' => array(
			'name'     => 'PerplexityBot',
			'slug'     => 'perplexitybot',
			'category' => 'search_retrieval',
			'provider' => 'Perplexity AI',
			'purpose'  => 'Live search citation and answer synthesis',
		),
		'gptbot' => array(
			'name'     => 'GPTBot',
			'slug'     => 'gptbot',
			'category' => 'search_retrieval',
			'provider' => 'OpenAI',
			'purpose'  => 'SearchGPT and ChatGPT web retrieval',
		),
		'chatgpt-user' => array(
			'name'     => 'ChatGPT-User',
			'slug'     => 'chatgpt-user',
			'category' => 'search_retrieval',
			'provider' => 'OpenAI',
			'purpose'  => 'Direct real-time browsing on user prompt',
		),
		'claudebot' => array(
			'name'     => 'ClaudeBot',
			'slug'     => 'claudebot',
			'category' => 'search_retrieval',
			'provider' => 'Anthropic',
			'purpose'  => 'Claude web search and artifact retrieval',
		),
		'claude-web' => array(
			'name'     => 'Claude-Web',
			'slug'     => 'claude-web',
			'category' => 'search_retrieval',
			'provider' => 'Anthropic',
			'purpose'  => 'Real-time user prompt retrieval',
		),
		'google-extended' => array(
			'name'     => 'Google-Extended',
			'slug'     => 'google-extended',
			'category' => 'search_retrieval',
			'provider' => 'Google',
			'purpose'  => 'Gemini and Vertex AI training and retrieval',
		),
		'applebot-extended' => array(
			'name'     => 'Applebot-Extended',
			'slug'     => 'applebot-extended',
			'category' => 'search_retrieval',
			'provider' => 'Apple',
			'purpose'  => 'Apple Intelligence search and Siri knowledge',
		),
		'amazonbot' => array(
			'name'     => 'Amazonbot',
			'slug'     => 'amazonbot',
			'category' => 'search_retrieval',
			'provider' => 'Amazon',
			'purpose'  => 'Amazon Rufus and Bedrock assistant indexing',
		),
		'bytespider' => array(
			'name'     => 'Bytespider',
			'slug'     => 'bytespider',
			'category' => 'training_scraper',
			'provider' => 'ByteDance',
			'purpose'  => 'High-volume LLM training scraping',
		),
		'ccbot' => array(
			'name'     => 'CCBot',
			'slug'     => 'ccbot',
			'category' => 'training_scraper',
			'provider' => 'Common Crawl',
			'purpose'  => 'Open web bulk dataset aggregation',
		),
		'diffbot' => array(
			'name'     => 'Diffbot',
			'slug'     => 'diffbot',
			'category' => 'training_scraper',
			'provider' => 'Diffbot',
			'purpose'  => 'Structured web scraping and entity extraction',
		),
		'cohere-ai' => array(
			'name'     => 'Cohere-ai',
			'slug'     => 'cohere-ai',
			'category' => 'training_scraper',
			'provider' => 'Cohere',
			'purpose'  => 'Enterprise LLM training dataset collection',
		),
		'meta-externalagent' => array(
			'name'     => 'Meta-ExternalAgent',
			'slug'     => 'meta-externalagent',
			'category' => 'search_retrieval',
			'provider' => 'Meta',
			'purpose'  => 'Meta AI assistant live retrieval',
		),
	);

	/**
	 * Retrieve current User-Agent string safely.
	 *
	 * @param string|null $ua Optional override.
	 * @return string
	 */
	public static function get_current_user_agent( ?string $ua = null ): string {
		if ( null !== $ua ) {
			return $ua;
		}
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
	}

	/**
	 * Detect if the request originates from a known AI bot.
	 *
	 * @param string|null $ua Optional override.
	 * @return bool
	 */
	public static function is_ai_bot( ?string $ua = null ): bool {
		return null !== self::get_bot_info( $ua );
	}

	/**
	 * Get classified bot details if user-agent matches known catalog.
	 *
	 * @param string|null $ua Optional override.
	 * @return array<string, string>|null
	 */
	public static function get_bot_info( ?string $ua = null ): ?array {
		$agent = strtolower( self::get_current_user_agent( $ua ) );
		if ( empty( $agent ) ) {
			return null;
		}

		foreach ( self::$bot_catalog as $pattern => $info ) {
			if ( strpos( $agent, $pattern ) !== false ) {
				return $info;
			}
		}

		return null;
	}

	/**
	 * Check if the request explicitly asks for Markdown.
	 *
	 * @return bool
	 */
	public static function wants_markdown(): bool {
		// 1. Check HTTP Accept header
		$accept = isset( $_SERVER['HTTP_ACCEPT'] ) ? strtolower( (string) $_SERVER['HTTP_ACCEPT'] ) : '';
		if ( strpos( $accept, 'text/markdown' ) !== false || strpos( $accept, 'text/x-markdown' ) !== false ) {
			return true;
		}

		// 2. Check query parameter format=md or format=markdown
		if ( isset( $_GET['format'] ) ) {
			$fmt = strtolower( sanitize_key( wp_unslash( $_GET['format'] ) ) );
			if ( in_array( $fmt, array( 'md', 'markdown' ), true ) ) {
				return true;
			}
		}

		// 3. Check custom header
		if ( isset( $_SERVER['HTTP_X_ACCEPT_MARKDOWN'] ) && '1' === $_SERVER['HTTP_X_ACCEPT_MARKDOWN'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Return list of all known AI bots.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function get_known_bots(): array {
		return self::$bot_catalog;
	}
}
