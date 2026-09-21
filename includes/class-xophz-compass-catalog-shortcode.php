<?php
/**
 * Compass Plugin Suite Catalog Shortcode & Modal Controller
 *
 * Provides shortcodes [compass_catalog], [compass_suite], and [compass_plugins]
 * to render sovereign plugin info cards with category filtering, live search,
 * interactive modal details, demo links, and checkout license links.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xophz_Compass_Catalog_Shortcode {

	/**
	 * Asset enqueued flag to prevent multiple scripts/styles on the same page.
	 *
	 * @var bool
	 */
	private static $assets_rendered = false;

	/**
	 * Initialize shortcodes and hooks.
	 */
	public static function init(): void {
		add_shortcode( 'compass_catalog', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'compass_suite', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'compass_plugins', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Retrieve master catalog of Compass companion plugins.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_catalog(): array {
		$catalog = array(
			// =========================================================================
			// TRUE NORTH / CORE OS & GOVERNANCE
			// =========================================================================
			array(
				'key'           => 'compass-core',
				'name'          => 'My Compass Engine',
				'codename'      => 'xophz-compass',
				'category'      => 'True North',
				'group'         => 'OS',
				'desc'          => 'The central framework powering all Xophz COMPASS extensions, routing, component registries, and atomic design system.',
				'tag'           => 'Core OS · Sovereign',
				'color'         => '#62c9ff',
				'gradient'      => 'linear-gradient(135deg, #62c9ff 0%, #0284c7 100%)',
				'price'         => '$199/yr',
				'marketEqv'     => 'Salesforce Platform',
				'version'       => 'v26.9.21',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'compass',
			),
			array(
				'key'           => 'youmeos',
				'name'          => 'You Me OS',
				'codename'      => 'xophz-compass-event-horizon',
				'category'      => 'True North',
				'group'         => 'OS',
				'desc'          => 'Spatial web operating system featuring dynamic window management, 3D microverse canvases, and live media connectivity.',
				'tag'           => 'Spatial OS · Portal',
				'color'         => '#6366f1',
				'gradient'      => 'linear-gradient(135deg, #6366f1 0%, #312e81 100%)',
				'price'         => '$199/yr',
				'marketEqv'     => 'Custom Spatial Portal SaaS',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-event-horizon',
				'showcaseUrl'   => 'https://youmeos.com/',
				'showcaseLabel' => 'Spatial OS',
				'icon'          => 'layers',
			),
			array(
				'key'           => 'xp-engine',
				'name'          => 'For the XP',
				'codename'      => 'xophz-compass-xp',
				'category'      => 'True North',
				'group'         => 'LXP',
				'desc'          => 'Turnkey gamification engine introducing XP, levels, achievement badges, and reward mechanics across the entire webwork.',
				'tag'           => 'Gamification · Live',
				'color'         => '#f59e0b',
				'gradient'      => 'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'GamiPress Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-xp',
				'showcaseUrl'   => 'https://forthexp.com/',
				'showcaseLabel' => 'Live Platform',
				'icon'          => 'sparkles',
			),
			array(
				'key'           => 'quests',
				'name'          => 'Questbook CRM',
				'codename'      => 'xophz-compass-quests',
				'category'      => 'True North',
				'group'         => 'CRM',
				'desc'          => 'Gamified customer relationship manager, interactive quest milestone tracker, and user activity lifecycle journal.',
				'tag'           => 'CRM · Gamified',
				'color'         => '#f97316',
				'gradient'      => 'linear-gradient(135deg, #fb923c 0%, #7c2d12 100%)',
				'price'         => '$149/yr',
				'marketEqv'     => 'HubSpot CRM Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-quests',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'flag',
			),
			array(
				'key'           => 'gale-boomerang',
				'name'          => 'Magic Boomerang',
				'codename'      => 'xophz-compass-gale-boomerang',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Traffic resonance radar catching wind of returning visitor origins and executing smart dynamic redirects.',
				'tag'           => 'Traffic Radar · Live',
				'color'         => '#3b82f6',
				'gradient'      => 'linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%)',
				'price'         => '$129/yr',
				'marketEqv'     => 'Pendo / Mixpanel',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-gale-boomerang',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'refresh',
			),
			array(
				'key'           => 'enchanted-mirror',
				'name'          => 'Enchanted Mirror',
				'codename'      => 'xophz-compass-enchanted-mirror',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Audience comparison and behavioral telemetry tool peering into how visitor engagement compares with competitor benchmarks.',
				'tag'           => 'Analytics · Live',
				'color'         => '#8b5cf6',
				'gradient'      => 'linear-gradient(135deg, #8b5cf6 0%, #3730a3 100%)',
				'price'         => '$89/yr',
				'marketEqv'     => 'Hotjar Plus',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-enchanted-mirror',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'shield',
			),
			array(
				'key'           => 'golden-keys',
				'name'          => 'Golden Keywords',
				'codename'      => 'xophz-compass-golden-keys',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Discover high-converting search keywords, unlock organic reach, and secure OAuth2 single sign-on API gateway routing.',
				'tag'           => 'Discovery · Live',
				'color'         => '#d97706',
				'gradient'      => 'linear-gradient(135deg, #f59e0b 0%, #78350f 100%)',
				'price'         => '$149/yr',
				'marketEqv'     => 'Auth0 Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-golden-keys',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'key',
			),
			array(
				'key'           => 'hookshot',
				'name'          => 'Magic Hookshot',
				'codename'      => 'xophz-compass-hookshot',
				'category'      => 'True North',
				'group'         => 'ITSM',
				'desc'          => 'Incoming and outgoing webhook management for the COMPASS ecosystem with automated retry queues.',
				'tag'           => 'Webhooks · Active',
				'color'         => '#ec4899',
				'gradient'      => 'linear-gradient(135deg, #f43f5e 0%, #881337 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Zapier Webhooks',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-hookshot',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'zap',
			),
			array(
				'key'           => 'pegasus-boots',
				'name'          => 'Pegasus Boots',
				'codename'      => 'xophz-compass-pegasus-boots',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Asset minification, critical CSS extraction, and pagespeed acceleration engineered for Core Web Vitals.',
				'tag'           => 'Speed · Live',
				'color'         => '#0284c7',
				'gradient'      => 'linear-gradient(135deg, #38bdf8 0%, #0369a1 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'NitroPack Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-pegasus-boots',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'gauge',
			),

			// =========================================================================
			// COMMAND DECK / COMMERCE, ECOSYSTEM & THEMES
			// =========================================================================
			array(
				'key'           => 'card-vault',
				'name'          => 'Card Vault',
				'codename'      => 'xophz-compass-card-vault',
				'category'      => 'Command Deck',
				'group'         => 'Commerce',
				'desc'          => 'Offline-first trade desk, optical AI grading assessment, consignment accounting, and WooCommerce product synchronization.',
				'tag'           => 'Commerce · Active',
				'color'         => '#d97706',
				'gradient'      => 'linear-gradient(135deg, #f59e0b 0%, #78350f 100%)',
				'price'         => '$99/yr',
				'marketEqv'     => 'Card Dealer Pro / BinderPOS',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-card-vault',
				'showcaseUrl'   => 'https://cardvault.worldwidewebwork.com/',
				'showcaseLabel' => 'Live Showcase',
				'icon'          => 'shield',
			),
			array(
				'key'           => 'bazaar',
				'name'          => 'Bazaar Warehouse',
				'codename'      => 'xophz-compass-bazaar',
				'category'      => 'Command Deck',
				'group'         => 'POS',
				'desc'          => 'Manage inventory stock levels, order fulfillment, point of sale transactions, and financial analytics in real time.',
				'tag'           => 'E-Commerce · POS',
				'color'         => '#10b981',
				'gradient'      => 'linear-gradient(135deg, #34d399 0%, #064e3b 100%)',
				'price'         => '$149/yr',
				'marketEqv'     => 'Shopify Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bazaar',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'bag',
			),
			array(
				'key'           => 'fresh-mints',
				'name'          => 'Fresh Mints',
				'codename'      => 'xophz-compass-fresh-mints',
				'category'      => 'Command Deck',
				'group'         => 'Intelligence',
				'desc'          => 'Turnkey lead discovery, license registry audit, skip-tracing intelligence, and practice website launcher platform.',
				'tag'           => 'Outreach · Active',
				'color'         => '#10b981',
				'gradient'      => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'LeadIQ Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-fresh-mints',
				'showcaseUrl'   => 'https://freshmints.worldwidewebwork.com/',
				'showcaseLabel' => 'Live Platform',
				'icon'          => 'sparkles',
			),
			array(
				'key'           => 'magic-hat',
				'name'          => 'Magic Hat Theme',
				'codename'      => 'xophz-magic-hat',
				'category'      => 'Command Deck',
				'group'         => 'CMS',
				'desc'          => 'Ultra-minimal semantic parent theme for Project Compass and YouMeOS with 24-hour circadian rhythm lighting.',
				'tag'           => 'Design System · Theme',
				'color'         => '#a855f7',
				'gradient'      => 'linear-gradient(135deg, #a855f7 0%, #581c87 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Astra / Genesis Pro',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-magic-hat',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'wand',
			),
			array(
				'key'           => 'diego-lawfirm',
				'name'          => 'Lawfirm Manager',
				'codename'      => 'xophz-compass-diego-lawfirm',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Legal practice management and case tracking platform integrated with Xophz COMPASS connectors.',
				'tag'           => 'Practice Ops · Live',
				'color'         => '#3b82f6',
				'gradient'      => 'linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Clio Legal',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-diego-lawfirm',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'book',
			),
			array(
				'key'           => 'yellow-links',
				'name'          => 'Yellow Links Hub',
				'codename'      => 'xophz-compass-yellow-links',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Unified sovereign link aggregator with automatic WordPress authentication detection and personalized profile hubs.',
				'tag'           => 'Link Hub · Live',
				'color'         => '#eab308',
				'gradient'      => 'linear-gradient(135deg, #eab308 0%, #854d0e 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Linktree Enterprise',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-yellow-links',
				'showcaseUrl'   => 'https://yellowlinks.worldwidewebwork.com/',
				'showcaseLabel' => 'Live Directory',
				'icon'          => 'link',
			),
			array(
				'key'           => 'glowitheflow',
				'name'          => 'Glowitheflow Network',
				'codename'      => 'xophz-compass-glowitheflow',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Flow economy engine, creator tributaries, cross-promotional click credit ledger, and viral sharing mechanics.',
				'tag'           => 'Creator Economy · Active',
				'color'         => '#0284c7',
				'gradient'      => 'linear-gradient(135deg, #38bdf8 0%, #0c4a6e 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Linktree Pro',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-glowitheflow',
				'showcaseUrl'   => 'https://glowitheflow.com/',
				'showcaseLabel' => 'Live Network',
				'icon'          => 'waves',
			),
			array(
				'key'           => 'nook-phone',
				'name'          => 'Nook OS Phone',
				'codename'      => 'xophz-nook-phone',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Standalone spatial phone interface, app dashboard, and communication gateway for YouMeOS users.',
				'tag'           => 'Spatial OS · App',
				'color'         => '#10b981',
				'gradient'      => 'linear-gradient(135deg, #34d399 0%, #065f46 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Nook OS Spatial',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-nook-phone',
				'showcaseUrl'   => 'https://nookphone.app/',
				'showcaseLabel' => 'Live App',
				'icon'          => 'sparkles',
			),
			array(
				'key'           => 'kitchen-synk',
				'name'          => 'Kitchen Synk',
				'codename'      => 'xophz-kitchen-synk',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Standalone WordPress backend, admin bar command center, and router for the Kitchen Synk web app.',
				'tag'           => 'Admin Suite · Active',
				'color'         => '#64748b',
				'gradient'      => 'linear-gradient(135deg, #64748b 0%, #1e293b 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Admin Bar Manager',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-kitchen-synk',
				'showcaseUrl'   => 'https://kitchensynk.app/',
				'showcaseLabel' => 'Live App',
				'icon'          => 'refresh',
			),
			array(
				'key'           => 'alphabet-soup',
				'name'          => 'Alphabet Soup',
				'codename'      => 'xophz-compass-alphabet-soup',
				'category'      => 'Command Deck',
				'group'         => 'CMS',
				'desc'          => 'Headless-ready content management engine to quickly add, edit, and orchestrate posts and newsroom taxonomy.',
				'tag'           => 'CMS · Active',
				'color'         => '#06b6d4',
				'gradient'      => 'linear-gradient(135deg, #06b6d4 0%, #0e7490 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Contentful CMS',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-alphabet-soup',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'code',
			),

			// =========================================================================
			// TRAJECTORY / AUTOMATION, MARKETING & PIPELINES
			// =========================================================================
			array(
				'key'           => 'bomb-bag',
				'name'          => 'Bomb Bag News Drip',
				'codename'      => 'xophz-compass-bomb-bag',
				'category'      => 'Trajectory',
				'group'         => 'MA',
				'desc'          => 'Send email blasts, schedule newsletter drip sequences, broadcast notifications, and deliver priority alerts.',
				'tag'           => 'Email Drip · Active',
				'color'         => '#ec4899',
				'gradient'      => 'linear-gradient(135deg, #ec4899 0%, #831843 100%)',
				'price'         => '$99/yr',
				'marketEqv'     => 'Mailchimp Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bomb-bag',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'flame',
			),
			array(
				'key'           => 'magic-formula',
				'name'          => 'Magic Formula',
				'codename'      => 'xophz-compass-magic-formula',
				'category'      => 'Trajectory',
				'group'         => 'CRM',
				'desc'          => 'High-speed proxy from YouMeOS/COMPASS into the Forminator forms backend with custom validation pipelines.',
				'tag'           => 'Forms · Active',
				'color'         => '#10b981',
				'gradient'      => 'linear-gradient(135deg, #10b981 0%, #064e3b 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Typeform Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-magic-formula',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'zap',
			),
			array(
				'key'           => 'silver-arrow',
				'name'          => 'A/B Silver Arrow',
				'codename'      => 'xophz-compass-silver-arrow',
				'category'      => 'Trajectory',
				'group'         => 'MA',
				'desc'          => 'The silver bullet to A/B split testing, conversion rate optimization, and high-speed headline experimentation.',
				'tag'           => 'Split Testing · Live',
				'color'         => '#38bdf8',
				'gradient'      => 'linear-gradient(135deg, #38bdf8 0%, #0369a1 100%)',
				'price'         => '$99/yr',
				'marketEqv'     => 'VWO / Optimizely',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-silver-arrow',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'target',
			),
			array(
				'key'           => 'lead-magnet',
				'name'          => 'Lead Magnet',
				'codename'      => 'xophz-compass-lead-magnet',
				'category'      => 'Trajectory',
				'group'         => 'MA',
				'desc'          => 'Magnetic form builders, subscription capture gates, lead qualification, and automated follow-up dispatch.',
				'tag'           => 'Lead Gen · Active',
				'color'         => '#ef4444',
				'gradient'      => 'linear-gradient(135deg, #ef4444 0%, #991b1b 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'HubSpot Starter',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-lead-magnet',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'magnet',
			),
			array(
				'key'           => 'bulletin-board',
				'name'          => 'Bulletin Board',
				'codename'      => 'xophz-compass-bulletin-board',
				'category'      => 'Trajectory',
				'group'         => 'Community',
				'desc'          => 'Enterprise community forum software, topic dispatcher, and decentralized discussion observer.',
				'tag'           => 'Community · Live',
				'color'         => '#14b8a6',
				'gradient'      => 'linear-gradient(135deg, #2dd4bf 0%, #0f766e 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Discourse Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bulletin-board',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'flag',
			),

			// =========================================================================
			// CASTLE WALLS / SECURITY, ITSM & INFRASTRUCTURE
			// =========================================================================
			array(
				'key'           => 'mirror-shield',
				'name'          => 'Magic Shield',
				'codename'      => 'xophz-compass-mirror-shield',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Stand protected and reflect malicious requests back to attackers with automated IP ban lists, bot rate limits, and honeypots.',
				'tag'           => 'Firewall · Active',
				'color'         => '#64748b',
				'gradient'      => 'linear-gradient(135deg, #94a3b8 0%, #1e293b 100%)',
				'price'         => '$69/yr',
				'marketEqv'     => 'Cloudflare WAF',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-mirror-shield',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'shield',
			),
			array(
				'key'           => 'lit-lamp',
				'name'          => 'Magic Lamp',
				'codename'      => 'xophz-compass-lit-lamp',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Shed light on server health with continuous background telemetry and transparent public/private status reporting.',
				'tag'           => 'Status Page · Live',
				'color'         => '#f59e0b',
				'gradient'      => 'linear-gradient(135deg, #fbbf24 0%, #78350f 100%)',
				'price'         => '$49/yr',
				'marketEqv'     => 'StatusPage Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-lit-lamp',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'sparkles',
			),
			array(
				'key'           => 'thors-hammer',
				'name'          => 'Magic Hammer',
				'codename'      => 'xophz-compass-thors-hammer',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Smite repeated offenders by banning malicious accounts, IP subnets, and automated brute-force scripts with one command.',
				'tag'           => 'Dev Enforcement · Live',
				'color'         => '#ef4444',
				'gradient'      => 'linear-gradient(135deg, #f87171 0%, #7f1d1d 100%)',
				'price'         => '$49/yr',
				'marketEqv'     => 'WP-CLI Pro Tools',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-thors-hammer',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'zap',
			),
			array(
				'key'           => 'phantom-zone',
				'name'          => 'Phantom Zone',
				'codename'      => 'xophz-compass-phantom-zone',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Isolate and handle 404 dead-ends, forbidden access routes, and 500 server crashes gracefully with custom traps.',
				'tag'           => 'Staging & Traps · Live',
				'color'         => '#6b7280',
				'gradient'      => 'linear-gradient(135deg, #9ca3af 0%, #111827 100%)',
				'price'         => '$49/yr',
				'marketEqv'     => 'WP Stagecoach',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-phantom-zone',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'moon',
			),
			array(
				'key'           => 'moving-castle',
				'name'          => 'Moving Castle Sync',
				'codename'      => 'xophz-compass-moving-castle',
				'category'      => 'Castle Walls',
				'group'         => 'BI',
				'desc'          => 'Network multi-tenancy, cross-site synchronization, and database replication for distributed COMPASS clusters.',
				'tag'           => 'Multi-Tenancy · Active',
				'color'         => '#0ea5e9',
				'gradient'      => 'linear-gradient(135deg, #38bdf8 0%, #0369a1 100%)',
				'price'         => '$89/yr',
				'marketEqv'     => 'Multisite Sync Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-moving-castle',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'castle',
			),

			// =========================================================================
			// WIZARD'S TOWER / DEVELOPER UTILITIES & PROTOCOLS
			// =========================================================================
			array(
				'key'           => 'bugnet',
				'name'          => 'Bug Catching Net',
				'codename'      => 'xophz-compass-bugnet',
				'category'      => "Wizard's Tower",
				'group'         => 'ITSM',
				'desc'          => 'Catch client and server-side runtime errors instantly, quarantine broken sessions, and export stack traces without vendor trackers.',
				'tag'           => 'Error Telemetry · Live',
				'color'         => '#ef4444',
				'gradient'      => 'linear-gradient(135deg, #ef4444 0%, #7f1d1d 100%)',
				'price'         => '$79/yr',
				'marketEqv'     => 'Sentry Enterprise',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bugnet',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'bug',
			),
			array(
				'key'           => 'polos',
				'name'          => 'Xophz POLOS',
				'codename'      => 'xophz-compass-polos',
				'category'      => "Wizard's Tower",
				'group'         => 'Governance',
				'desc'          => 'Compliance, audit logging, and regulatory policy governance for sovereign WordPress platforms.',
				'tag'           => 'Governance · Active',
				'color'         => '#38bdf8',
				'gradient'      => 'linear-gradient(135deg, #38bdf8 0%, #0284c7 100%)',
				'price'         => '$99/yr',
				'marketEqv'     => 'OneTrust Compliance',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-polos',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'shield',
			),
			array(
				'key'           => 'magic-wand',
				'name'          => 'Magic Wand CLI',
				'codename'      => 'xophz-compass-magic-wand',
				'category'      => "Wizard's Tower",
				'group'         => 'Developer',
				'desc'          => 'Automate routine maintenance, trigger remote API actions, scaffold components, and orchestrate headless tasks with zero UI overhead.',
				'tag'           => 'Developer CLI · Active',
				'color'         => '#8b5cf6',
				'gradient'      => 'linear-gradient(135deg, #8b5cf6 0%, #4c1d95 100%)',
				'price'         => '$69/yr',
				'marketEqv'     => 'WP-CLI Automation',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-magic-wand',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'wand',
			),
			array(
				'key'           => 'enchiridion',
				'name'          => 'Enchiridion Codex',
				'codename'      => 'xophz-compass-enchiridion',
				'category'      => "Wizard's Tower",
				'group'         => 'Docs',
				'desc'          => 'Living architectural manual, API reference, and knowledge base codex co-located within the COMPASS dashboard.',
				'tag'           => 'Codex · Live',
				'color'         => '#059669',
				'gradient'      => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
				'price'         => '$59/yr',
				'marketEqv'     => 'GitBook Enterprise',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-enchiridion',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'icon'          => 'book',
			),
		);

		return apply_filters( 'compass_catalog_plugins', $catalog );
	}

	/**
	 * Render shortcode output.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string HTML markup.
	 */
	public static function render_shortcode( $atts ): string {
		$args = shortcode_atts(
			array(
				'category'     => 'all',
				'checkout_url' => 'https://xophz.com/my-compass',
				'columns'      => '3',
				'limit'        => 0,
				'show_search'  => 'true',
				'show_tabs'    => 'true',
			),
			$atts,
			'compass_catalog'
		);

		$all_plugins = self::get_catalog();

		// Optional category filter via attribute
		if ( ! empty( $args['category'] ) && 'all' !== strtolower( $args['category'] ) ) {
			$filtered = array();
			foreach ( $all_plugins as $plug ) {
				if ( strcasecmp( $plug['category'], $args['category'] ) === 0 ) {
					$filtered[] = $plug;
				}
			}
			$plugins = $filtered;
		} else {
			$plugins = $all_plugins;
		}

		if ( ! empty( $args['limit'] ) && (int) $args['limit'] > 0 ) {
			$plugins = array_slice( $plugins, 0, (int) $args['limit'] );
		}

		$categories = array(
			'All',
			'True North',
			'Command Deck',
			'Trajectory',
			'Castle Walls',
			"Wizard's Tower",
		);

		$checkout_base = esc_url( $args['checkout_url'] );

		ob_start();
		self::render_styles_and_scripts_once();
		?>
		<div class="compass-catalog-wrap" data-checkout-base="<?php echo esc_attr( $checkout_base ); ?>">
			<?php if ( 'true' === $args['show_search'] || 'true' === $args['show_tabs'] ) : ?>
				<div class="compass-catalog-toolbar">
					<?php if ( 'true' === $args['show_tabs'] ) : ?>
						<div class="compass-catalog-tabs" role="tablist">
							<?php foreach ( $categories as $idx => $cat ) : ?>
								<button
									type="button"
									class="compass-tab-btn <?php echo 0 === $idx ? 'active' : ''; ?>"
									data-category="<?php echo esc_attr( $cat ); ?>"
									role="tab"
									aria-selected="<?php echo 0 === $idx ? 'true' : 'false'; ?>"
								>
									<?php echo esc_html( $cat ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( 'true' === $args['show_search'] ) : ?>
						<div class="compass-catalog-search">
							<svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="11" cy="11" r="8"></circle>
								<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							</svg>
							<input
								type="text"
								class="compass-search-input"
								placeholder="Search sovereign plugins, features, or SaaS replacements..."
								aria-label="Search sovereign plugins"
							/>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="compass-catalog-count-bar">
				<span class="compass-catalog-counter">Showing <?php echo count( $plugins ); ?> sovereign platforms</span>
				<span class="compass-catalog-sovereign-badge">100% Owned · Zero Per-Seat SaaS</span>
			</div>

			<div class="compass-catalog-grid" data-columns="<?php echo esc_attr( $args['columns'] ); ?>">
				<?php foreach ( $plugins as $p ) : ?>
					<?php
					$color      = ! empty( $p['color'] ) ? $p['color'] : '#62c9ff';
					$has_demo   = ! empty( $p['showcaseUrl'] );
					$demo_url   = $has_demo ? esc_url( $p['showcaseUrl'] ) : '';
					$demo_lbl   = ! empty( $p['showcaseLabel'] ) ? $p['showcaseLabel'] : 'Live Demo';
					$buy_url    = add_query_arg(
						array(
							'plugin'    => $p['key'],
							'purchased' => 'true',
						),
						$checkout_base
					);
					$eqv_label  = ! empty( $p['marketEqv'] ) ? 'Eqv: ' . $p['marketEqv'] : '';
					?>
					<div
						class="compass-plug-card"
						id="plug-card-<?php echo esc_attr( $p['key'] ); ?>"
						data-key="<?php echo esc_attr( $p['key'] ); ?>"
						data-name="<?php echo esc_attr( $p['name'] ); ?>"
						data-codename="<?php echo esc_attr( $p['codename'] ); ?>"
						data-category="<?php echo esc_attr( $p['category'] ); ?>"
						data-group="<?php echo esc_attr( $p['group'] ?? '' ); ?>"
						data-desc="<?php echo esc_attr( $p['desc'] ); ?>"
						data-price="<?php echo esc_attr( $p['price'] ); ?>"
						data-eqv="<?php echo esc_attr( $p['marketEqv'] ?? '' ); ?>"
						data-version="<?php echo esc_attr( $p['version'] ?? 'v26.9.5' ); ?>"
						data-color="<?php echo esc_attr( $color ); ?>"
						data-demo="<?php echo esc_attr( $demo_url ); ?>"
						data-demo-label="<?php echo esc_attr( $demo_lbl ); ?>"
						data-buy="<?php echo esc_url( $buy_url ); ?>"
						data-repo="<?php echo esc_url( $p['repoUrl'] ?? '' ); ?>"
						style="--plug-brand: <?php echo esc_attr( $color ); ?>;"
					>
						<div class="compass-plug-card__header">
							<div class="compass-plug-card__tags">
								<span class="compass-tag-badge compass-tag-category"><?php echo esc_html( $p['category'] ); ?></span>
								<?php if ( ! empty( $p['group'] ) ) : ?>
									<span class="compass-tag-badge compass-tag-group"><?php echo esc_html( $p['group'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $eqv_label ) ) : ?>
									<span class="compass-tag-badge compass-tag-eqv"><?php echo esc_html( $eqv_label ); ?></span>
								<?php endif; ?>
							</div>
							<span class="compass-plug-price"><?php echo esc_html( $p['price'] ); ?></span>
						</div>

						<div class="compass-plug-card__body">
							<div class="compass-plug-avatar" style="background: <?php echo esc_attr( $p['gradient'] ?? 'rgba(98,201,255,0.1)' ); ?>;">
								<span class="compass-avatar-glyph"><?php echo esc_html( strtoupper( substr( $p['name'], 0, 1 ) ) ); ?></span>
							</div>

							<div class="compass-plug-info">
								<h3 class="compass-plug-title"><?php echo esc_html( $p['name'] ); ?></h3>
								<div class="compass-plug-codename">
									<code><?php echo esc_html( $p['codename'] ); ?></code>
									<span class="compass-plug-ver"><?php echo esc_html( $p['version'] ?? 'v26.9.5' ); ?></span>
								</div>
								<p class="compass-plug-desc"><?php echo esc_html( $p['desc'] ); ?></p>
							</div>
						</div>

						<div class="compass-plug-card__footer">
							<div class="compass-plug-actions">
								<?php if ( $has_demo ) : ?>
									<a
										href="<?php echo esc_url( $demo_url ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										class="compass-action-btn compass-btn-demo"
										title="Launch live demonstration"
									>
										<span><?php echo esc_html( $demo_lbl ); ?></span>
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
									</a>
								<?php endif; ?>

								<button
									type="button"
									class="compass-action-btn compass-btn-details js-open-modal"
									data-key="<?php echo esc_attr( $p['key'] ); ?>"
								>
									Details
								</button>

								<a
									href="<?php echo esc_url( $buy_url ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="compass-action-btn compass-btn-buy"
								>
									Get Access
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Dynamic Modal Container -->
			<div class="compass-modal-backdrop" id="compass-catalog-modal" aria-hidden="true" role="dialog" aria-modal="true">
				<div class="compass-modal-dialog">
					<button type="button" class="compass-modal-close" aria-label="Close dialog">&times;</button>

					<div class="compass-modal-header">
						<div class="compass-modal-avatar" id="modal-avatar"></div>
						<div class="compass-modal-meta">
							<div class="compass-modal-badges">
								<span class="compass-tag-badge" id="modal-category"></span>
								<span class="compass-tag-badge compass-tag-eqv" id="modal-eqv"></span>
								<span class="compass-tag-badge compass-tag-group" id="modal-version"></span>
							</div>
							<h2 class="compass-modal-title" id="modal-title"></h2>
							<code class="compass-modal-codename" id="modal-codename"></code>
						</div>
					</div>

					<div class="compass-modal-body">
						<div class="compass-modal-section">
							<h4>Platform Overview</h4>
							<p id="modal-desc" class="compass-modal-lead"></p>
						</div>

						<div class="compass-modal-section compass-modal-callout">
							<h4>Sovereign Asset vs SaaS Sprawl</h4>
							<p>
								Unlike commodity SaaS tools that charge $50 to $100 per seat each month and hold your data behind rate-limited APIs, this companion platform runs directly on your own headless WordPress BaaS infrastructure. Zero external seat charges, 100% data ownership, and instant local execution.
							</p>
						</div>

						<div class="compass-modal-section">
							<h4>Architecture &amp; Specifications</h4>
							<ul class="compass-modal-specs">
								<li><strong>Kernel:</strong> Headless WordPress BaaS (PHP 8+, MariaDB, REST API)</li>
								<li><strong>Protocol:</strong> Chemical X Molecular Standard (500-line ceilings, zero entropy)</li>
								<li><strong>Licensing:</strong> <span id="modal-price" class="font-bold text-accent"></span> (unlimited internal seats)</li>
							</ul>
						</div>
					</div>

					<div class="compass-modal-footer">
						<a href="#" id="modal-btn-demo" class="compass-action-btn compass-btn-demo" target="_blank" rel="noopener noreferrer">
							<span>Launch Live App</span>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
						</a>
						<a href="#" id="modal-btn-buy" class="compass-action-btn compass-btn-buy" target="_blank" rel="noopener noreferrer">
							Get Sovereign License →
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Output scoped styles and JavaScript once per page.
	 */
	private static function render_styles_and_scripts_once(): void {
		if ( self::$assets_rendered ) {
			return;
		}
		self::$assets_rendered = true;
		?>
		<style id="compass-catalog-inline-css">
			.compass-catalog-wrap {
				width: 100%;
				max-width: 1200px;
				margin: 0 auto;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
				color: #e2e8f0;
				box-sizing: border-box;
			}
			.compass-catalog-wrap * {
				box-sizing: border-box;
			}

			/* Toolbar & Filtering */
			.compass-catalog-toolbar {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				justify-content: space-between;
				gap: 1rem;
				margin-bottom: 1.25rem;
			}
			.compass-catalog-tabs {
				display: flex;
				flex-wrap: wrap;
				gap: 0.5rem;
			}
			.compass-tab-btn {
				background: rgba(255, 255, 255, 0.05);
				color: #94a3b8;
				border: 1px solid rgba(255, 255, 255, 0.1);
				border-radius: 9999px;
				padding: 0.4rem 0.9rem;
				font-size: 0.8rem;
				font-weight: 600;
				cursor: pointer;
				transition: all 0.2s ease;
			}
			.compass-tab-btn:hover {
				color: #ffffff;
				background: rgba(255, 255, 255, 0.1);
				border-color: rgba(98, 201, 255, 0.4);
			}
			.compass-tab-btn.active {
				color: #070a13;
				background: #62c9ff;
				border-color: #62c9ff;
				box-shadow: 0 0 15px rgba(98, 201, 255, 0.35);
			}

			/* Search input */
			.compass-catalog-search {
				position: relative;
				flex: 1 1 280px;
				max-width: 400px;
			}
			.compass-catalog-search .search-icon {
				position: absolute;
				left: 0.75rem;
				top: 50%;
				transform: translateY(-50%);
				color: #64748b;
				pointer-events: none;
			}
			.compass-search-input {
				width: 100%;
				background: rgba(15, 23, 42, 0.6);
				border: 1px solid rgba(255, 255, 255, 0.12);
				border-radius: 9999px;
				padding: 0.45rem 1rem 0.45rem 2.25rem;
				font-size: 0.85rem;
				color: #f8fafc;
				outline: none;
				transition: border-color 0.2s ease;
			}
			.compass-search-input:focus {
				border-color: #62c9ff;
				box-shadow: 0 0 12px rgba(98, 201, 255, 0.25);
			}

			/* Counter bar */
			.compass-catalog-count-bar {
				display: flex;
				align-items: center;
				justify-content: space-between;
				font-size: 0.75rem;
				font-family: monospace;
				color: #64748b;
				margin-bottom: 1.25rem;
				padding-bottom: 0.5rem;
				border-bottom: 1px solid rgba(255, 255, 255, 0.06);
			}
			.compass-catalog-sovereign-badge {
				color: #10b981;
				font-weight: 600;
			}

			/* Grid */
			.compass-catalog-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
				gap: 1.25rem;
			}

			/* Cards */
			.compass-plug-card {
				background: rgba(15, 23, 42, 0.65);
				border: 1px solid rgba(255, 255, 255, 0.08);
				border-radius: 14px;
				padding: 1.25rem;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
				position: relative;
			}
			.compass-plug-card:hover {
				transform: translateY(-2px);
				border-color: var(--plug-brand, rgba(98, 201, 255, 0.5));
				box-shadow: 0 10px 30px rgba(0, 0, 0, 0.45);
			}
			.compass-plug-card.is-hidden {
				display: none !important;
			}

			.compass-plug-card__header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 0.5rem;
				margin-bottom: 0.85rem;
			}
			.compass-plug-card__tags {
				display: flex;
				flex-wrap: wrap;
				gap: 0.35rem;
				align-items: center;
			}
			.compass-tag-badge {
				display: inline-block;
				font-size: 0.65rem;
				font-family: monospace;
				font-weight: 700;
				padding: 0.15rem 0.45rem;
				border-radius: 4px;
				background: rgba(255, 255, 255, 0.06);
				color: #94a3b8;
			}
			.compass-tag-category {
				background: rgba(98, 201, 255, 0.12);
				color: #62c9ff;
				border: 1px solid rgba(98, 201, 255, 0.25);
			}
			.compass-tag-eqv {
				background: rgba(245, 158, 11, 0.1);
				color: #f59e0b;
				border: 1px solid rgba(245, 158, 11, 0.25);
			}
			.compass-plug-price {
				font-family: monospace;
				font-weight: 700;
				font-size: 0.85rem;
				color: #10b981;
			}

			/* Card Body */
			.compass-plug-card__body {
				display: flex;
				align-items: flex-start;
				gap: 1rem;
				margin-bottom: 1rem;
			}
			.compass-plug-avatar {
				width: 44px;
				height: 44px;
				border-radius: 10px;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
			}
			.compass-avatar-glyph {
				font-size: 1.25rem;
				font-weight: 900;
				color: #ffffff;
				font-family: monospace;
			}
			.compass-plug-info {
				flex: 1;
				min-width: 0;
			}
			.compass-plug-title {
				font-size: 1.05rem;
				font-weight: 700;
				color: #f8fafc;
				margin: 0 0 0.2rem 0;
				line-height: 1.3;
			}
			.compass-plug-codename {
				display: flex;
				align-items: center;
				gap: 0.5rem;
				font-size: 0.7rem;
				margin-bottom: 0.45rem;
			}
			.compass-plug-codename code {
				color: #64748b;
				background: transparent;
				padding: 0;
			}
			.compass-plug-ver {
				color: #475569;
				font-family: monospace;
			}
			.compass-plug-desc {
				font-size: 0.82rem;
				line-height: 1.45;
				color: #94a3b8;
				margin: 0;
			}

			/* Card Footer Actions */
			.compass-plug-card__footer {
				padding-top: 0.85rem;
				border-top: 1px solid rgba(255, 255, 255, 0.06);
			}
			.compass-plug-actions {
				display: flex;
				align-items: center;
				justify-content: flex-end;
				gap: 0.5rem;
			}
			.compass-action-btn {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				gap: 0.3rem;
				font-size: 0.75rem;
				font-weight: 600;
				padding: 0.35rem 0.75rem;
				border-radius: 6px;
				text-decoration: none;
				cursor: pointer;
				border: none;
				transition: all 0.2s ease;
			}
			.compass-btn-demo {
				background: rgba(16, 185, 129, 0.12);
				color: #34d399;
				border: 1px solid rgba(16, 185, 129, 0.3);
			}
			.compass-btn-demo:hover {
				background: #10b981;
				color: #070a13;
			}
			.compass-btn-details {
				background: rgba(255, 255, 255, 0.08);
				color: #e2e8f0;
			}
			.compass-btn-details:hover {
				background: rgba(255, 255, 255, 0.18);
				color: #ffffff;
			}
			.compass-btn-buy {
				background: #62c9ff;
				color: #070a13;
				font-weight: 700;
			}
			.compass-btn-buy:hover {
				background: #38bdf8;
				box-shadow: 0 0 12px rgba(98, 201, 255, 0.4);
			}

			/* Modal Styles */
			.compass-modal-backdrop {
				position: fixed;
				inset: 0;
				background: rgba(3, 7, 18, 0.82);
				backdrop-filter: blur(8px);
				display: none;
				align-items: center;
				justify-content: center;
				z-index: 99999;
				padding: 1rem;
			}
			.compass-modal-backdrop.is-open {
				display: flex;
			}
			.compass-modal-dialog {
				background: #0f172a;
				border: 1px solid rgba(98, 201, 255, 0.3);
				border-radius: 16px;
				width: 100%;
				max-width: 580px;
				padding: 1.75rem;
				box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
				position: relative;
				max-height: 90vh;
				overflow-y: auto;
			}
			.compass-modal-close {
				position: absolute;
				top: 1rem;
				right: 1rem;
				background: transparent;
				border: none;
				font-size: 1.75rem;
				color: #64748b;
				cursor: pointer;
				line-height: 1;
			}
			.compass-modal-close:hover {
				color: #ffffff;
			}
			.compass-modal-header {
				display: flex;
				align-items: center;
				gap: 1.25rem;
				margin-bottom: 1.25rem;
				padding-bottom: 1rem;
				border-bottom: 1px solid rgba(255, 255, 255, 0.08);
			}
			.compass-modal-avatar {
				width: 60px;
				height: 60px;
				border-radius: 14px;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
			}
			.compass-modal-avatar .compass-avatar-glyph {
				font-size: 1.75rem;
			}
			.compass-modal-meta {
				flex: 1;
				min-width: 0;
			}
			.compass-modal-badges {
				display: flex;
				flex-wrap: wrap;
				gap: 0.35rem;
				margin-bottom: 0.35rem;
			}
			.compass-modal-title {
				font-size: 1.35rem;
				font-weight: 800;
				color: #f8fafc;
				margin: 0 0 0.25rem 0;
			}
			.compass-modal-codename {
				font-size: 0.75rem;
				color: #62c9ff;
			}
			.compass-modal-section {
				margin-bottom: 1.25rem;
			}
			.compass-modal-section h4 {
				font-size: 0.85rem;
				text-transform: uppercase;
				letter-spacing: 0.08em;
				color: #94a3b8;
				margin: 0 0 0.4rem 0;
			}
			.compass-modal-lead {
				font-size: 0.95rem;
				line-height: 1.5;
				color: #cbd5e1;
				margin: 0;
			}
			.compass-modal-callout {
				background: rgba(98, 201, 255, 0.05);
				border-left: 3px solid #62c9ff;
				padding: 0.85rem 1rem;
				border-radius: 0 8px 8px 0;
			}
			.compass-modal-callout p {
				font-size: 0.85rem;
				line-height: 1.5;
				color: #94a3b8;
				margin: 0;
			}
			.compass-modal-specs {
				margin: 0;
				padding-left: 1.25rem;
				font-size: 0.85rem;
				line-height: 1.6;
				color: #94a3b8;
			}
			.compass-modal-specs strong {
				color: #f1f5f9;
			}
			.compass-modal-footer {
				display: flex;
				align-items: center;
				justify-content: flex-end;
				gap: 0.75rem;
				padding-top: 1rem;
				border-top: 1px solid rgba(255, 255, 255, 0.08);
			}
			.compass-modal-footer .compass-action-btn {
				padding: 0.55rem 1.1rem;
				font-size: 0.85rem;
			}
		</style>

		<script id="compass-catalog-inline-js">
			document.addEventListener('DOMContentLoaded', function() {
				var catalogWraps = document.querySelectorAll('.compass-catalog-wrap');
				catalogWraps.forEach(function(wrap) {
					var tabBtns = wrap.querySelectorAll('.compass-tab-btn');
					var searchInput = wrap.querySelector('.compass-search-input');
					var cards = wrap.querySelectorAll('.compass-plug-card');
					var counter = wrap.querySelector('.compass-catalog-counter');
					var modal = wrap.querySelector('.compass-modal-backdrop');
					var modalClose = wrap.querySelector('.compass-modal-close');

					var currentCategory = 'All';
					var currentQuery = '';

					function filterCards() {
						var visibleCount = 0;
						var q = currentQuery.toLowerCase().trim();

						cards.forEach(function(card) {
							var cat = card.getAttribute('data-category') || '';
							var name = (card.getAttribute('data-name') || '').toLowerCase();
							var code = (card.getAttribute('data-codename') || '').toLowerCase();
							var desc = (card.getAttribute('data-desc') || '').toLowerCase();
							var eqv = (card.getAttribute('data-eqv') || '').toLowerCase();

							var matchesCat = (currentCategory === 'All') || (cat.toLowerCase() === currentCategory.toLowerCase());
							var matchesQuery = !q || name.indexOf(q) !== -1 || code.indexOf(q) !== -1 || desc.indexOf(q) !== -1 || eqv.indexOf(q) !== -1;

							if (matchesCat && matchesQuery) {
								card.classList.remove('is-hidden');
								visibleCount++;
							} else {
								card.classList.add('is-hidden');
							}
						});

						if (counter) {
							counter.textContent = 'Showing ' + visibleCount + ' sovereign platform' + (visibleCount === 1 ? '' : 's');
						}
					}

					// Tabs
					tabBtns.forEach(function(btn) {
						btn.addEventListener('click', function() {
							tabBtns.forEach(function(b) {
								b.classList.remove('active');
								b.setAttribute('aria-selected', 'false');
							});
							btn.classList.add('active');
							btn.setAttribute('aria-selected', 'true');
							currentCategory = btn.getAttribute('data-category') || 'All';
							filterCards();
						});
					});

					// Search
					if (searchInput) {
						searchInput.addEventListener('input', function(e) {
							currentQuery = e.target.value;
							filterCards();
						});
					}

					// Modal
					function openModalWithCard(card) {
						if (!modal) return;
						var name = card.getAttribute('data-name');
						var code = card.getAttribute('data-codename');
						var cat = card.getAttribute('data-category');
						var desc = card.getAttribute('data-desc');
						var price = card.getAttribute('data-price');
						var eqv = card.getAttribute('data-eqv');
						var ver = card.getAttribute('data-version');
						var demo = card.getAttribute('data-demo');
						var demoLbl = card.getAttribute('data-demo-label') || 'Live App';
						var buy = card.getAttribute('data-buy');
						var color = card.getAttribute('data-color') || '#62c9ff';

						wrap.querySelector('#modal-title').textContent = name;
						wrap.querySelector('#modal-codename').textContent = code;
						wrap.querySelector('#modal-category').textContent = cat;
						wrap.querySelector('#modal-version').textContent = ver;
						wrap.querySelector('#modal-desc').textContent = desc;
						wrap.querySelector('#modal-price').textContent = price;

						var eqvEl = wrap.querySelector('#modal-eqv');
						if (eqv) {
							eqvEl.textContent = 'Eqv: ' + eqv;
							eqvEl.style.display = 'inline-block';
						} else {
							eqvEl.style.display = 'none';
						}

						var avatarEl = wrap.querySelector('#modal-avatar');
						avatarEl.style.background = color;
						avatarEl.innerHTML = '<span class="compass-avatar-glyph">' + (name.charAt(0) || 'C') + '</span>';

						var demoBtn = wrap.querySelector('#modal-btn-demo');
						if (demo) {
							demoBtn.href = demo;
							demoBtn.querySelector('span').textContent = demoLbl;
							demoBtn.style.display = 'inline-flex';
						} else {
							demoBtn.style.display = 'none';
						}

						var buyBtn = wrap.querySelector('#modal-btn-buy');
						buyBtn.href = buy;

						modal.classList.add('is-open');
						modal.setAttribute('aria-hidden', 'false');
					}

					function closeModal() {
						if (!modal) return;
						modal.classList.remove('is-open');
						modal.setAttribute('aria-hidden', 'true');
					}

					wrap.querySelectorAll('.js-open-modal').forEach(function(btn) {
						btn.addEventListener('click', function(e) {
							e.stopPropagation();
							var key = btn.getAttribute('data-key');
							var card = wrap.querySelector('#plug-card-' + key);
							if (card) {
								openModalWithCard(card);
							}
						});
					});

					// Card click also opens modal if not clicking demo or buy button
					cards.forEach(function(card) {
						card.addEventListener('click', function(e) {
							if (e.target.closest('.compass-action-btn')) {
								return;
							}
							openModalWithCard(card);
						});
					});

					if (modalClose) {
						modalClose.addEventListener('click', closeModal);
					}

					if (modal) {
						modal.addEventListener('click', function(e) {
							if (e.target === modal) {
								closeModal();
							}
						});
					}

					document.addEventListener('keydown', function(e) {
						if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
							closeModal();
						}
					});
				});
			});
		</script>
		<?php
	}
}

// Auto-initialize when file is included
Xophz_Compass_Catalog_Shortcode::init();
