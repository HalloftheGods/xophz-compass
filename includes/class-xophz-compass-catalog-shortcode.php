<?php
/**
 * Compass Plugin Suite Catalog Shortcode & Modal Controller
 *
 * Provides shortcodes [compass_catalog], [compass_suite], and [compass_plugins]
 * to render sovereign plugin info cards with circular side logos, category
 * filtering, live instant search, and high-fidelity modal details matching
 * the xophz.com/my-compass experience.
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
	 * Retrieve master catalog of Compass companion plugins matching xophz.com/my-compass.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function get_catalog(): array {
		return array(
			// =========================================================================
			// COMMAND DECK
			// =========================================================================
			array(
				'key'           => 'compass-core',
				'name'          => 'My Compass Engine',
				'codename'      => 'xophz-compass',
				'category'      => 'Command Deck',
				'group'         => 'OS',
				'desc'          => 'The central framework powering all Xophz COMPASS extensions, routing, component registries, and atomic design system.',
				'tag'           => 'Core OS · Sovereign',
				'color'         => '#8b5cf6',
				'gradient'      => 'linear-gradient(135deg, #8b5cf6 0%, #4c1d95 100%)',
				'price'         => '$199/yr',
				'priceNumber'   => 199,
				'marketEqv'     => 'Salesforce Platform',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass',
				'githubRepo'    => 'xophz-compass',
				'logoUrl'       => '/icons/plugins/xophz-compass.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'isCore'        => true,
			),
			array(
				'key'           => 'youmeos',
				'name'          => 'You Me OS',
				'codename'      => 'xophz-compass-event-horizon',
				'category'      => 'Command Deck',
				'group'         => 'OS',
				'desc'          => 'Bring transparency to user-to-media connectivity by observing a 3D interactive heat map from micro to macro.',
				'tag'           => 'Spatial OS · Portal',
				'color'         => '#6366f1',
				'gradient'      => 'linear-gradient(135deg, #6366f1 0%, #312e81 100%)',
				'price'         => '$199/yr',
				'priceNumber'   => 199,
				'marketEqv'     => 'Custom Portal SaaS',
				'version'       => 'v26.9.6-131',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-event-horizon',
				'githubRepo'    => 'xophz-compass-event-horizon',
				'logoUrl'       => '/icons/plugins/xophz-compass-event-horizon.svg',
				'showcaseUrl'   => 'https://youmeos.com/',
				'showcaseLabel' => 'Spatial OS',
				'isCore'        => true,
				'saasOffer'     => array(
					'headline' => 'Spatial Operating System & Interactive Microverse Portal',
					'badge'    => 'Spatial OS',
					'url'      => 'https://youmeos.com/',
				),
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Genesis Pro / Astra',
				'version'       => 'v26.9.6-674',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-magic-hat',
				'githubRepo'    => 'xophz-magic-hat',
				'logoUrl'       => '/icons/plugins/xophz-magic-hat.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'isCore'        => true,
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Contentful CMS',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-alphabet-soup',
				'githubRepo'    => 'xophz-compass-alphabet-soup',
				'logoUrl'       => '/icons/plugins/xophz-compass-alphabet-soup.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
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
				'priceNumber'   => 149,
				'marketEqv'     => 'Shopify Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bazaar',
				'githubRepo'    => 'xophz-compass-bazaar',
				'logoUrl'       => '/icons/plugins/xophz-compass-bazaar.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'card-vault',
				'name'          => 'Card Vault',
				'codename'      => 'xophz-compass-card-vault',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Offline-first trade desk, POS, optical grading, consignment accounting, and WooCommerce product synchronization.',
				'tag'           => 'Commerce · Active',
				'color'         => '#d97706',
				'gradient'      => 'linear-gradient(135deg, #f59e0b 0%, #78350f 100%)',
				'price'         => '$99/yr',
				'priceNumber'   => 99,
				'marketEqv'     => 'Card Dealer Pro / BinderPOS',
				'version'       => 'v26.9.6-234',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-card-vault',
				'githubRepo'    => 'xophz-compass-card-vault',
				'logoUrl'       => '/icons/plugins/xophz-compass-card-vault.svg',
				'showcaseUrl'   => 'https://cardvault.worldwidewebwork.com/',
				'showcaseLabel' => 'Live Showcase',
				'saasOffer'     => array(
					'headline' => 'Online Catalog Solution for Card Shops & Card Shows',
					'badge'    => 'Hosted SaaS',
					'url'      => 'https://cardvault.worldwidewebwork.com/',
				),
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Clio Legal',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-diego-lawfirm',
				'githubRepo'    => 'xophz-compass-diego-lawfirm',
				'logoUrl'       => '/icons/plugins/xophz-compass-diego-lawfirm.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
				'isPrivate'     => true,
			),
			array(
				'key'           => 'fresh-mints',
				'name'          => 'Fresh Mints',
				'codename'      => 'xophz-compass-fresh-mints',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Turnkey lead discovery, license registry audit, skip-tracing, and practice website launcher platform.',
				'tag'           => 'Outreach · Active',
				'color'         => '#10b981',
				'gradient'      => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'LeadIQ Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-fresh-mints',
				'githubRepo'    => 'xophz-compass-fresh-mints',
				'logoUrl'       => '/icons/plugins/xophz-compass-fresh-mints.svg',
				'showcaseUrl'   => 'https://freshmints.worldwidewebwork.com/',
				'showcaseLabel' => 'Live Platform',
				'saasOffer'     => array(
					'headline' => 'Turnkey Lead Discovery & Skip-Tracing Intelligence',
					'badge'    => 'Live Platform',
					'url'      => 'https://freshmints.worldwidewebwork.com/',
				),
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Linktree Pro',
				'version'       => 'v26.9.6',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-glowitheflow',
				'githubRepo'    => 'xophz-compass-glowitheflow',
				'logoUrl'       => '/icons/plugins/xophz-compass-glowitheflow.svg',
				'showcaseUrl'   => 'https://glowitheflow.com/',
				'showcaseLabel' => 'Live Network',
				'saasOffer'     => array(
					'headline' => 'Flow Economy Engine & Creator Tributaries Network',
					'badge'    => 'Live Network',
					'url'      => 'https://glowitheflow.com/',
				),
			),
			array(
				'key'           => 'produce',
				'name'          => 'Local Produce Market',
				'codename'      => 'xophz-compass-produce',
				'category'      => 'Command Deck',
				'group'         => 'Economics',
				'desc'          => 'Universal EDVEX Data Royalty Engine & Farmer\'s Market for sovereign COMPASS and YouMeOS digital assets.',
				'tag'           => 'Royalties · Active',
				'color'         => '#84cc16',
				'gradient'      => 'linear-gradient(135deg, #a3e635 0%, #3f6212 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'Gumroad Digital',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-produce',
				'githubRepo'    => 'xophz-compass-produce',
				'logoUrl'       => '/icons/plugins/xophz-compass-produce.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Linktree Enterprise',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-yellow-links',
				'githubRepo'    => 'xophz-compass-yellow-links',
				'logoUrl'       => '/icons/plugins/xophz-compass-yellow-links.svg',
				'showcaseUrl'   => 'https://yellowlinks.worldwidewebwork.com/',
				'showcaseLabel' => 'Live Directory',
				'saasOffer'     => array(
					'headline' => 'Unified Sovereign Link Aggregator & Municipal Directory',
					'badge'    => 'Live Directory',
					'url'      => 'https://yellowlinks.worldwidewebwork.com/',
				),
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Admin Bar Manager',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-kitchen-synk',
				'githubRepo'    => 'xophz-kitchen-synk',
				'logoUrl'       => '/icons/plugins/xophz-kitchen-synk.svg',
				'showcaseUrl'   => 'https://kitchensynk.app/',
				'showcaseLabel' => 'Live App',
				'saasOffer'     => array(
					'headline' => 'Universal Cross-App Data Synchronization & State Replication',
					'badge'    => 'Live App',
					'url'      => 'https://kitchensynk.app/',
				),
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Nook OS Spatial',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-nook-phone',
				'githubRepo'    => 'xophz-nook-phone',
				'logoUrl'       => '/icons/plugins/xophz-nook-phone.svg',
				'showcaseUrl'   => 'https://nookphone.app/',
				'showcaseLabel' => 'Live App',
				'isPrivate'     => true,
				'saasOffer'     => array(
					'headline' => 'Standalone Spatial Phone Interface & App Dashboard',
					'badge'    => 'Live App',
					'url'      => 'https://nookphone.app/',
				),
			),
			array(
				'key'           => 'thoth-reader',
				'name'          => 'Thoth Reader',
				'codename'      => 'xophz-thoth-reader-wp',
				'category'      => 'Command Deck',
				'group'         => 'Ecosystem',
				'desc'          => 'Standalone backend, RSS router, and high-speed content feed reader for spatial knowledge workers.',
				'tag'           => 'Reader · Active',
				'color'         => '#f59e0b',
				'gradient'      => 'linear-gradient(135deg, #f59e0b 0%, #9a3412 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'Feedly Pro',
				'version'       => 'v26.7.30',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-thoth-reader-wp',
				'githubRepo'    => 'xophz-thoth-reader-wp',
				'logoUrl'       => '/icons/plugins/xophz-thoth-reader-wp.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'dodo-air',
				'name'          => 'Dodo Air',
				'codename'      => 'super-nerd-bros-dodo-air',
				'category'      => 'Command Deck',
				'group'         => 'Router',
				'desc'          => 'Real-time air traffic control, flight tracker, and passenger manifest gateway bridge for Dodo Airlines operations.',
				'tag'           => 'Transport · Active',
				'color'         => '#0ea5e9',
				'gradient'      => 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'FlightAware / Turnip Exchange',
				'version'       => 'v26.9.11',
				'repoUrl'       => 'https://github.com/SuperNerdBros/wp-dodo-air',
				'githubRepo'    => 'super-nerd-bros-dodo-air',
				'logoUrl'       => '/icons/plugins/xophz-compass-dodo-air.png',
				'showcaseUrl'   => 'https://dodoair.forthexp.com/',
				'showcaseLabel' => 'Flight Tracker',
				'saasOffer'     => array(
					'headline' => 'Live Passenger Router & Flight Operations Gateway',
					'badge'    => 'Live App',
					'url'      => 'https://dodoair.forthexp.com/',
				),
			),

			// =========================================================================
			// TRUE NORTH
			// =========================================================================
			array(
				'key'           => 'xp-engine',
				'name'          => 'For the XP',
				'codename'      => 'xophz-compass-xp',
				'category'      => 'True North',
				'group'         => 'LXP',
				'desc'          => 'Gamification engine introducing XP, levels, achievement badges, and reward mechanics across the entire webwork.',
				'tag'           => 'Gamification · Live',
				'color'         => '#f59e0b',
				'gradient'      => 'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'GamiPress Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-xp',
				'githubRepo'    => 'xophz-compass-xp',
				'logoUrl'       => '/icons/plugins/xophz-compass-xp.svg',
				'showcaseUrl'   => 'https://forthexp.com/',
				'showcaseLabel' => 'Live Platform',
				'isCore'        => true,
				'saasOffer'     => array(
					'headline' => 'Turnkey Gamification & Experience Engine',
					'badge'    => 'Live Platform',
					'url'      => 'https://forthexp.com/',
				),
			),
			array(
				'key'           => 'quests',
				'name'          => 'Questbook CRM',
				'codename'      => 'xophz-compass-quests',
				'category'      => 'True North',
				'group'         => 'CRM',
				'desc'          => 'An all-in-one gamified customer relationship manager, interactive quest milestone tracker, and user activity log.',
				'tag'           => 'CRM · Gamified',
				'color'         => '#f97316',
				'gradient'      => 'linear-gradient(135deg, #fb923c 0%, #7c2d12 100%)',
				'price'         => '$149/yr',
				'priceNumber'   => 149,
				'marketEqv'     => 'HubSpot CRM Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-quests',
				'githubRepo'    => 'xophz-compass-quests',
				'logoUrl'       => '/icons/plugins/xophz-compass-quests.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'enchanted-mirror',
				'name'          => 'Enchanted Mirror',
				'codename'      => 'xophz-compass-enchanted-mirror',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Audience comparison and behavioral telemetry tool peering into how your visitors compare with competitor benchmarks.',
				'tag'           => 'Analytics · Live',
				'color'         => '#8b5cf6',
				'gradient'      => 'linear-gradient(135deg, #8b5cf6 0%, #3730a3 100%)',
				'price'         => '$89/yr',
				'priceNumber'   => 89,
				'marketEqv'     => 'Hotjar Plus',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-enchanted-mirror',
				'githubRepo'    => 'xophz-compass-enchanted-mirror',
				'logoUrl'       => '/icons/plugins/xophz-compass-enchanted-mirror.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'gale-boomerang',
				'name'          => 'Magic Boomerang',
				'codename'      => 'xophz-compass-gale-boomerang',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Traffic resonance radar catching wind of where returning visitors originate and routing smart dynamic redirects.',
				'tag'           => 'Traffic Radar · Live',
				'color'         => '#3b82f6',
				'gradient'      => 'linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%)',
				'price'         => '$129/yr',
				'priceNumber'   => 129,
				'marketEqv'     => 'Pendo / Mixpanel',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-gale-boomerang',
				'githubRepo'    => 'xophz-compass-gale-boomerang',
				'logoUrl'       => '/icons/plugins/xophz-compass-gale-boomerang.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
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
				'priceNumber'   => 149,
				'marketEqv'     => 'Auth0 Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-golden-keys',
				'githubRepo'    => 'xophz-compass-golden-keys',
				'logoUrl'       => '/icons/plugins/xophz-compass-golden-keys.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
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
				'priceNumber'   => 79,
				'marketEqv'     => 'Zapier Webhooks',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-hookshot',
				'githubRepo'    => 'xophz-compass-hookshot',
				'logoUrl'       => '/icons/plugins/xophz-compass-hookshot.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'pegasus-boots',
				'name'          => 'Pegasus Boots',
				'codename'      => 'xophz-compass-pegasus-boots',
				'category'      => 'True North',
				'group'         => 'MA',
				'desc'          => 'Dash to top search rankings with asset minification, critical CSS extraction, and pagespeed acceleration.',
				'tag'           => 'SEO Acceleration · Live',
				'color'         => '#38bdf8',
				'gradient'      => 'linear-gradient(135deg, #60a5fa 0%, #1e3a8a 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'NitroPack Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-pegasus-boots',
				'githubRepo'    => 'xophz-compass-pegasus-boots',
				'logoUrl'       => '/icons/plugins/xophz-compass-pegasus-boots.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),

			// =========================================================================
			// TRAJECTORY
			// =========================================================================
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
				'priceNumber'   => 99,
				'marketEqv'     => 'VWO / Optimizely',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-silver-arrow',
				'githubRepo'    => 'xophz-compass-silver-arrow',
				'logoUrl'       => '/icons/plugins/xophz-compass-silver-arrow.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'lead-magnet',
				'name'          => 'Lead Magnet',
				'codename'      => 'xophz-compass-lead-magnet',
				'category'      => 'Trajectory',
				'group'         => 'MA',
				'desc'          => 'Pull qualified leads in with super-powered magnetic form builders, subscription capture, and automated follow-ups.',
				'tag'           => 'Lead Gen · Active',
				'color'         => '#ef4444',
				'gradient'      => 'linear-gradient(135deg, #ef4444 0%, #991b1b 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'HubSpot Starter',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-lead-magnet',
				'githubRepo'    => 'xophz-compass-lead-magnet',
				'logoUrl'       => '/icons/plugins/xophz-compass-lead-magnet.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'bulletin-board',
				'name'          => 'Bulletin Board',
				'codename'      => 'xophz-compass-bulletin-board',
				'category'      => 'Trajectory',
				'group'         => 'Community',
				'desc'          => 'Enterprise-grade community forum software, topic dispatcher, and decentralized discussion observer.',
				'tag'           => 'Community · Live',
				'color'         => '#14b8a6',
				'gradient'      => 'linear-gradient(135deg, #2dd4bf 0%, #0f766e 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'Discourse Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bulletin-board',
				'githubRepo'    => 'xophz-compass-bulletin-board',
				'logoUrl'       => '/icons/plugins/xophz-compass-bulletin-board.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'pixie-dust',
				'name'          => 'Magic Pixie Dust',
				'codename'      => 'xophz-compass-pixie-dust',
				'category'      => 'Trajectory',
				'group'         => 'MA',
				'desc'          => 'Manage pixels, micro-animations, particle canvas FX, and invisible email open tracking telemetry.',
				'tag'           => 'FX & Telemetry · Live',
				'color'         => '#a855f7',
				'gradient'      => 'linear-gradient(135deg, #a855f7 0%, #581c87 100%)',
				'price'         => '$49/yr',
				'priceNumber'   => 49,
				'marketEqv'     => 'Mailtrack Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-pixie-dust',
				'githubRepo'    => 'xophz-compass-pixie-dust',
				'logoUrl'       => '/icons/plugins/xophz-compass-pixie-dust.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
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
				'priceNumber'   => 99,
				'marketEqv'     => 'Mailchimp Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bomb-bag',
				'githubRepo'    => 'xophz-compass-bomb-bag',
				'logoUrl'       => '/icons/plugins/xophz-compass-bomb-bag.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),

			// =========================================================================
			// CASTLE WALLS
			// =========================================================================
			array(
				'key'           => 'mirror-shield',
				'name'          => 'Magic Shield',
				'codename'      => 'xophz-compass-mirror-shield',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Stand protected and reflect malicious attacks back to attackers with automated IP ban lists and bot rate limits.',
				'tag'           => 'Firewall · Active',
				'color'         => '#64748b',
				'gradient'      => 'linear-gradient(135deg, #94a3b8 0%, #1e293b 100%)',
				'price'         => '$69/yr',
				'priceNumber'   => 69,
				'marketEqv'     => 'Cloudflare WAF',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-mirror-shield',
				'githubRepo'    => 'xophz-compass-mirror-shield',
				'logoUrl'       => '/icons/plugins/xophz-compass-mirror-shield.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'lit-lamp',
				'name'          => 'Magic Lamp',
				'codename'      => 'xophz-compass-lit-lamp',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Shed light on the dungeons of your server with continuous health checks and transparent status reporting.',
				'tag'           => 'Status Page · Live',
				'color'         => '#f59e0b',
				'gradient'      => 'linear-gradient(135deg, #fbbf24 0%, #78350f 100%)',
				'price'         => '$49/yr',
				'priceNumber'   => 49,
				'marketEqv'     => 'StatusPage Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-lit-lamp',
				'githubRepo'    => 'xophz-compass-lit-lamp',
				'logoUrl'       => '/icons/plugins/xophz-compass-lit-lamp.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'thors-hammer',
				'name'          => 'Magic Hammer',
				'codename'      => 'xophz-compass-thors-hammer',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Smite repeated offenders by banning malicious accounts, IP subnets, and automated brute-force scripts.',
				'tag'           => 'Dev Enforcement · Live',
				'color'         => '#ef4444',
				'gradient'      => 'linear-gradient(135deg, #f87171 0%, #7f1d1d 100%)',
				'price'         => '$49/yr',
				'priceNumber'   => 49,
				'marketEqv'     => 'WP-CLI Pro Tools',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-thors-hammer',
				'githubRepo'    => 'xophz-compass-thors-hammer',
				'logoUrl'       => '/icons/plugins/xophz-compass-thors-hammer.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'phantom-zone',
				'name'          => 'Phantom Zone',
				'codename'      => 'xophz-compass-phantom-zone',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Isolate and handle 404 dead-ends, forbidden access routes, and 500 server crashes gracefully.',
				'tag'           => 'Staging & Traps · Live',
				'color'         => '#6b7280',
				'gradient'      => 'linear-gradient(135deg, #9ca3af 0%, #111827 100%)',
				'price'         => '$49/yr',
				'priceNumber'   => 49,
				'marketEqv'     => 'WP Stagecoach',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-phantom-zone',
				'githubRepo'    => 'xophz-compass-phantom-zone',
				'logoUrl'       => '/icons/plugins/xophz-compass-phantom-zone.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'moving-castle',
				'name'          => 'Moving Castle Sync',
				'codename'      => 'xophz-compass-moving-castle',
				'category'      => 'Castle Walls',
				'group'         => 'BI',
				'desc'          => 'Open your door to new markets and domains without downtime via snapshot sync and cross-site migration.',
				'tag'           => 'Migration · Active',
				'color'         => '#6366f1',
				'gradient'      => 'linear-gradient(135deg, #818cf8 0%, #3730a3 100%)',
				'price'         => '$69/yr',
				'priceNumber'   => 69,
				'marketEqv'     => 'WP Migrate Pro',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-moving-castle',
				'githubRepo'    => 'xophz-compass-moving-castle',
				'logoUrl'       => '/icons/plugins/xophz-compass-moving-castle.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'treasure-map',
				'name'          => 'Treasure Map',
				'codename'      => 'xophz-compass-treasure-map',
				'category'      => 'Castle Walls',
				'group'         => 'BI',
				'desc'          => 'The path to rewards: executive dashboarding and KPI visualization connecting participation to the XP engine.',
				'tag'           => 'KPI Dashboards · Active',
				'color'         => '#eab308',
				'gradient'      => 'linear-gradient(135deg, #facc15 0%, #713f12 100%)',
				'price'         => '$99/yr',
				'priceNumber'   => 99,
				'marketEqv'     => 'Databox Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-treasure-map',
				'githubRepo'    => 'xophz-compass-treasure-map',
				'logoUrl'       => '/icons/plugins/xophz-compass-treasure-map.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'treasure-trove',
				'name'          => 'Treasure Trove',
				'codename'      => 'xophz-compass-treasure-trove',
				'category'      => 'Castle Walls',
				'group'         => 'BI',
				'desc'          => 'The Sovereign Data Vault: Securely encrypt, store, and manage user preferences and digital asset inventories.',
				'tag'           => 'Data Vault · Live',
				'color'         => '#14b8a6',
				'gradient'      => 'linear-gradient(135deg, #2dd4bf 0%, #115e59 100%)',
				'price'         => '$149/yr',
				'priceNumber'   => 149,
				'marketEqv'     => 'WooCommerce Ext.',
				'version'       => 'v26.9.4',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-treasure-trove',
				'githubRepo'    => 'xophz-compass-treasure-trove',
				'logoUrl'       => '/icons/plugins/xophz-compass-treasure-trove.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'phone',
				'name'          => 'Phone Gateway',
				'codename'      => 'xophz-compass-phone',
				'category'      => 'Castle Walls',
				'group'         => 'ITSM',
				'desc'          => 'Mobile telephony bridge, SMS dispatch, and two-factor authentication gateway for sovereign identities.',
				'tag'           => 'Telephony · Active',
				'color'         => '#0ea5e9',
				'gradient'      => 'linear-gradient(135deg, #38bdf8 0%, #0369a1 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'Twilio Gateway',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-phone',
				'githubRepo'    => 'xophz-compass-phone',
				'logoUrl'       => '/icons/plugins/xophz-compass-phone.svg',
				'showcaseUrl'   => 'https://phone.mycompassconsulting.com/',
				'showcaseLabel' => 'Live Gateway',
				'saasOffer'     => array(
					'headline' => 'Mobile Telephony Bridge & 2FA SMS Gateway',
					'badge'    => 'Live Gateway',
					'url'      => 'https://phone.mycompassconsulting.com/',
				),
			),

			// =========================================================================
			// WIZARD'S TOWER
			// =========================================================================
			array(
				'key'           => 'enchiridion',
				'name'          => 'Enchiridion Library',
				'codename'      => 'xophz-compass-enchiridion',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'ITSM',
				'desc'          => 'Interactive documentation, living spellbook, and structured wiki for modern software applications.',
				'tag'           => 'Documentation · Live',
				'color'         => '#06b6d4',
				'gradient'      => 'linear-gradient(135deg, #06b6d4 0%, #0e7490 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'Confluence Team',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-enchiridion',
				'githubRepo'    => 'xophz-compass-enchiridion',
				'logoUrl'       => '/icons/plugins/xophz-compass-enchiridion.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'bugnet',
				'name'          => 'Bug-Catching Net',
				'codename'      => 'xophz-compass-bugnet',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'ITSM',
				'desc'          => 'Comprehensive issue tracking, error capturing, client telemetry, and beta diagnostics console.',
				'tag'           => 'Diagnostics · Active',
				'color'         => '#10b981',
				'gradient'      => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
				'price'         => '$99/yr',
				'priceNumber'   => 99,
				'marketEqv'     => 'Jira Service Mgmt',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-bugnet',
				'githubRepo'    => 'xophz-compass-bugnet',
				'logoUrl'       => '/icons/plugins/xophz-compass-bugnet.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'midnight-nerd',
				'name'          => 'Midnight Nerd Support',
				'codename'      => 'xophz-compass-midnight-nerd',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'ITSM',
				'desc'          => '24/7 ITSM helpdesk and sovereign support ticketing system connecting users to senior technical architects.',
				'tag'           => 'Helpdesk · Live',
				'color'         => '#8d105e',
				'gradient'      => 'linear-gradient(135deg, #a21caf 0%, #4c0519 100%)',
				'price'         => '$99/yr',
				'priceNumber'   => 99,
				'marketEqv'     => 'Zendesk Suite',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-midnight-nerd',
				'githubRepo'    => 'xophz-compass-midnight-nerd',
				'logoUrl'       => '/icons/plugins/xophz-compass-midnight-nerd.svg',
				'showcaseUrl'   => 'https://www.youmeos.com/u/?sparks=midnight-nerd&fullspark=true&name=Midnight+Nerd&icon=fal+fa-dice-d20&color=%238d105e',
				'showcaseLabel' => 'Launch Spark',
				'saasOffer'     => array(
					'headline' => '24/7 ITSM Helpdesk & Architect Support Spark',
					'badge'    => 'Live Spark',
					'url'      => 'https://www.youmeos.com/u/?sparks=midnight-nerd&fullspark=true&name=Midnight+Nerd&icon=fal+fa-dice-d20&color=%238d105e',
				),
			),
			array(
				'key'           => 'magic-cloak',
				'name'          => 'Magic Cloak Privacy',
				'codename'      => 'xophz-compass-magic-cloak',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'ITSM',
				'desc'          => 'Privacy masking, data anonymization, stealth mode, and security shielding for sovereign user identities.',
				'tag'           => 'Privacy · Live',
				'color'         => '#8b5cf6',
				'gradient'      => 'linear-gradient(135deg, #6366f1 0%, #312e81 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'MemberPress Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-magic-cloak',
				'githubRepo'    => 'xophz-compass-magic-cloak',
				'logoUrl'       => '/icons/plugins/xophz-compass-magic-cloak.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'magic-formula',
				'name'          => 'Magic Formulas',
				'codename'      => 'xophz-compass-magic-formula',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'CRM',
				'desc'          => 'Dynamic expression evaluator, custom shortcode logic, and reactive formula connector from YouMeOS to PHP forms.',
				'tag'           => 'Logic Engine · Live',
				'color'         => '#14b8a6',
				'gradient'      => 'linear-gradient(135deg, #14b8a6 0%, #134e4a 100%)',
				'price'         => '$79/yr',
				'priceNumber'   => 79,
				'marketEqv'     => 'Elementor Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-magic-formula',
				'githubRepo'    => 'xophz-compass-magic-formula',
				'logoUrl'       => '/icons/plugins/xophz-compass-magic-formula.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'magic-wand',
				'name'          => 'Magic Wand Builder',
				'codename'      => 'xophz-compass-magic-wand',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'CMS',
				'desc'          => 'Conjure stunning pages and quantum block patterns with this magical point-and-click theme builder.',
				'tag'           => 'Page Builder · Live',
				'color'         => '#a855f7',
				'gradient'      => 'linear-gradient(135deg, #c084fc 0%, #581c87 100%)',
				'price'         => '$69/yr',
				'priceNumber'   => 69,
				'marketEqv'     => 'Yoast SEO Premium',
				'version'       => 'v26.9.6-454',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-magic-wand',
				'githubRepo'    => 'xophz-compass-magic-wand',
				'logoUrl'       => '/icons/plugins/xophz-compass-magic-wand.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
			array(
				'key'           => 'titans-mitt',
				'name'          => 'Titan\'s Gloves',
				'codename'      => 'xophz-compass-titans-mitt',
				'category'      => 'Wizard\'s Tower',
				'group'         => 'ITSM',
				'desc'          => 'Heavy-duty data ingestion engine, cloud S3 syncing, high-volume file processing, and massive-scale imports.',
				'tag'           => 'Ingestion · Active',
				'color'         => '#64748b',
				'gradient'      => 'linear-gradient(135deg, #64748b 0%, #1e293b 100%)',
				'price'         => '$69/yr',
				'priceNumber'   => 69,
				'marketEqv'     => 'WP All Import Pro',
				'version'       => 'v26.9.5',
				'repoUrl'       => 'https://github.com/HalloftheGods/xophz-compass-titans-mitt',
				'githubRepo'    => 'xophz-compass-titans-mitt',
				'logoUrl'       => '/icons/plugins/xophz-compass-titans-mitt.svg',
				'showcaseUrl'   => '',
				'showcaseLabel' => '',
			),
		);
	}

	/**
	 * Resolve public URL for a plugin's artwork logo.
	 *
	 * @param array<string, mixed> $plugin Plugin data.
	 * @return string
	 */
	public static function get_logo_url( array $plugin ): string {
		$logo = $plugin['logoUrl'] ?? '';
		if ( empty( $logo ) ) {
			return '';
		}

		$filename = basename( $logo );

		// 1. Check local assets/icons/plugins
		$local_sub = dirname( __DIR__ ) . '/assets/icons/plugins/' . $filename;
		if ( file_exists( $local_sub ) ) {
			return plugins_url( 'assets/icons/plugins/' . $filename, dirname( __DIR__ ) . '/xophz-compass.php' );
		}

		// 2. Check local assets root
		$local_root = dirname( __DIR__ ) . '/assets/' . $filename;
		if ( file_exists( $local_root ) ) {
			return plugins_url( 'assets/' . $filename, dirname( __DIR__ ) . '/xophz-compass.php' );
		}

		// 3. Fallback to upstream xophz.com static asset
		return 'https://xophz.com' . $logo;
	}

	/**
	 * Render the [compass_catalog] shortcode.
	 *
	 * @param array<string, string> $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_shortcode( $atts ): string {
		$args = shortcode_atts(
			array(
				'category'     => '',
				'limit'        => '-1',
				'columns'      => '2',
				'checkout_url' => 'https://xophz.com/my-compass?plugin={key}&purchased=true',
				'show_search'  => 'true',
				'show_tabs'    => 'true',
			),
			$atts,
			'compass_catalog'
		);

		$all_plugins = self::get_catalog();

		// Optional category filter
		$filter_category = sanitize_text_field( $args['category'] );
		$limit           = intval( $args['limit'] );
		$columns         = in_array( $args['columns'], array( '1', '2', '3', '4' ), true ) ? $args['columns'] : '2';
		$show_search     = filter_var( $args['show_search'], FILTER_VALIDATE_BOOLEAN );
		$show_tabs       = filter_var( $args['show_tabs'], FILTER_VALIDATE_BOOLEAN );
		$checkout_url    = esc_url( $args['checkout_url'] );

		$plugins = array();
		foreach ( $all_plugins as $p ) {
			if ( ! empty( $filter_category ) && strcasecmp( $p['category'], $filter_category ) !== 0 ) {
				continue;
			}
			$plugins[] = $p;
		}

		if ( $limit > 0 && count( $plugins ) > $limit ) {
			$plugins = array_slice( $plugins, 0, $limit );
		}

		$instance_id = 'xo-catalog-' . wp_rand( 1000, 9999 );

		// Categories for filter tabs
		$categories = array( 'All', 'Command Deck', 'True North', 'Trajectory', 'Castle Walls', 'Wizard\'s Tower' );

		ob_start();

		self::render_styles_and_scripts();
		?>
		<div id="<?php echo esc_attr( $instance_id ); ?>" class="compass-catalog-root" data-checkout-pattern="<?php echo esc_attr( $checkout_url ); ?>">

			<?php if ( $show_search || $show_tabs ) : ?>
				<div class="compass-catalog-toolbar">
					<div class="compass-toolbar-header">
						<div class="compass-toolbar-title-wrap">
							<span class="compass-toolbar-icon">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
							</span>
							<h3 class="compass-toolbar-title">Plugin Catalog</h3>
						</div>

						<?php if ( $show_search ) : ?>
							<div class="compass-search-wrap">
								<svg class="compass-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
								<input
									type="text"
									class="compass-search-input"
									placeholder="Search 40+ plugins, replacements..."
									aria-label="Search plugins"
								/>
								<button type="button" class="compass-search-clear" style="display: none;" title="Clear search">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
								</button>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $show_tabs ) : ?>
						<div class="compass-filter-pills" role="tablist">
							<?php foreach ( $categories as $cat ) : ?>
								<button
									type="button"
									class="compass-pill-btn <?php echo ( $cat === 'All' ) ? 'is-active' : ''; ?>"
									data-category="<?php echo esc_attr( $cat ); ?>"
									role="tab"
								>
									<?php echo esc_html( $cat ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="compass-count-row">
						<span class="compass-count-label">
							Showing <span class="compass-visible-count"><?php echo count( $plugins ); ?></span> plugins
						</span>
					</div>
				</div>
			<?php endif; ?>

			<!-- Plugin Cards Grid -->
			<div class="compass-plugins-grid compass-grid-cols-<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $plugins as $plugin ) :
					$key       = esc_attr( $plugin['key'] );
					$color     = esc_attr( $plugin['color'] );
					$gradient  = esc_attr( $plugin['gradient'] );
					$logo_url  = esc_url( self::get_logo_url( $plugin ) );
					$filename  = esc_attr( basename( $plugin['logoUrl'] ?? '' ) );
					$has_demo  = ! empty( $plugin['showcaseUrl'] );
					$demo_url  = $has_demo ? esc_url( $plugin['showcaseUrl'] ) : '';
					$demo_lbl  = ! empty( $plugin['showcaseLabel'] ) ? esc_html( $plugin['showcaseLabel'] ) : 'Live App';
					$saas_bdg  = ! empty( $plugin['saasOffer']['badge'] ) ? esc_html( $plugin['saasOffer']['badge'] ) : ( $has_demo ? 'Live App' : '' );
					$eqv       = ! empty( $plugin['marketEqv'] ) ? esc_html( $plugin['marketEqv'] ) : '';
					$ver       = ! empty( $plugin['version'] ) ? esc_html( $plugin['version'] ) : 'v26.9.5';
					$json_data = esc_attr( wp_json_encode( $plugin ) );
				?>
					<div
						id="plugin-<?php echo $key; ?>"
						class="compass-plug-card"
						style="--plug-color: <?php echo $color; ?>;"
						data-key="<?php echo $key; ?>"
						data-category="<?php echo esc_attr( $plugin['category'] ); ?>"
						data-search="<?php echo esc_attr( strtolower( $plugin['name'] . ' ' . $plugin['codename'] . ' ' . $plugin['desc'] . ' ' . ( $plugin['marketEqv'] ?? '' ) ) ); ?>"
						data-plugin="<?php echo $json_data; ?>"
					>
						<!-- Side Logo Image: Placed directly on the left with negative margin, no container box -->
						<?php if ( ! empty( $logo_url ) ) : ?>
							<img
								src="<?php echo $logo_url; ?>"
								alt="<?php echo esc_attr( $plugin['name'] ); ?>"
								class="compass-side-logo"
								data-filename="<?php echo $filename; ?>"
								loading="lazy"
								onerror="if(!this.dataset.tried){this.dataset.tried='1';this.src='https://xophz.com/icons/plugins/'+this.dataset.filename;}else{this.style.display='none';this.nextElementSibling.style.display='flex';}"
							/>
						<?php endif; ?>

						<!-- Fallback Glow Disk if Image Missing -->
						<div class="compass-fallback-disk" style="display: <?php echo empty( $logo_url ) ? 'flex' : 'none'; ?>; background: <?php echo $gradient; ?>;">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polygon points="12 2 19 21 12 17 5 21 12 2"></polygon>
							</svg>
						</div>

						<!-- Details Column -->
						<div class="compass-details-col">
							<div class="compass-details-top">
								<!-- Badges Row -->
								<div class="compass-badges-row">
									<span class="compass-badge compass-badge-cat"><?php echo esc_html( $plugin['category'] ); ?></span>
									<?php if ( ! empty( $plugin['group'] ) ) : ?>
										<span class="compass-badge compass-badge-grp"><?php echo esc_html( $plugin['group'] ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $saas_bdg ) ) : ?>
										<span class="compass-badge compass-badge-live"><?php echo $saas_bdg; ?></span>
									<?php endif; ?>
									<span class="compass-badge compass-badge-price"><?php echo esc_html( $plugin['price'] ); ?></span>
								</div>

								<!-- Title -->
								<h4 class="compass-title"><?php echo esc_html( $plugin['name'] ); ?></h4>

								<!-- Description -->
								<p class="compass-desc"><?php echo esc_html( $plugin['desc'] ); ?></p>
							</div>

							<!-- Bottom Bar -->
							<div class="compass-bottom-bar">
								<div class="compass-meta-specs">
									<span class="compass-meta-ver"><?php echo $ver; ?></span>
									<?php if ( ! empty( $eqv ) ) : ?>
										<span class="compass-meta-dot">·</span>
										<span class="compass-meta-eqv">Eqv: <?php echo $eqv; ?></span>
									<?php endif; ?>
									<span class="compass-meta-dot">·</span>
									<span class="compass-meta-pub">Hall of the Gods, Inc.</span>
								</div>

								<div class="compass-card-actions">
									<?php if ( $has_demo ) : ?>
										<a
											href="<?php echo $demo_url; ?>"
											target="_blank"
											rel="noopener noreferrer"
											class="compass-btn-demo"
											title="Launch Live App"
										>
											<?php echo $demo_lbl; ?>
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
										</a>
									<?php endif; ?>
									<button type="button" class="compass-btn-details js-open-modal" title="View details">
										More Details
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
									</button>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Empty State -->
			<div class="compass-empty-state" style="display: none;">
				<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
				<p>No plugins match this category or search filter.</p>
			</div>

			<!-- Reusable Modal (Screenshot 2 Spec) -->
			<div class="compass-modal-overlay" aria-hidden="true" role="dialog" aria-modal="true">
				<div class="compass-modal-box">
					<!-- Ambient Halos -->
					<div class="compass-ambient-halo compass-halo-top"></div>
					<div class="compass-ambient-halo compass-halo-bottom"></div>

					<!-- Close Button -->
					<button type="button" class="compass-modal-close" aria-label="Close dialog">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
					</button>

					<!-- Modal Header -->
					<div class="compass-modal-header">
						<div class="compass-modal-artwork-wrap">
							<img src="" alt="" class="compass-modal-artwork" />
						</div>

						<div class="compass-modal-header-info">
							<div class="compass-modal-badges">
								<span class="compass-badge compass-badge-cat js-m-cat"></span>
								<span class="compass-badge compass-badge-grp js-m-grp"></span>
								<span class="compass-badge compass-badge-license js-m-price"></span>
								<span class="compass-modal-tag js-m-tag"></span>
							</div>
							<h3 class="compass-modal-title js-m-title"></h3>
							<p class="compass-modal-codename js-m-codename"></p>
						</div>
					</div>

					<!-- Modal Body -->
					<div class="compass-modal-body">
						<!-- Description Section -->
						<div class="compass-modal-section">
							<h5 class="compass-section-heading">DESCRIPTION</h5>
							<p class="compass-modal-desc js-m-desc"></p>
						</div>

						<!-- Turnkey Cloud SaaS Showcase Banner (if available) -->
						<div class="compass-saas-banner js-m-saas-box" style="display: none;">
							<div class="compass-saas-header">
								<div class="compass-saas-left">
									<div class="compass-saas-icon">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>
									</div>
									<div>
										<span class="compass-saas-badge js-m-saas-badge">Turnkey Cloud SaaS</span>
										<h5 class="compass-saas-headline js-m-saas-headline">Hosted Cloud Solution</h5>
									</div>
								</div>
								<a href="#" target="_blank" rel="noopener noreferrer" class="compass-saas-link js-m-saas-btn">
									<span>Launch Showcase</span>
									<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
								</a>
							</div>
							<p class="compass-saas-audience js-m-saas-audience"></p>
							<div class="compass-saas-footer">
								<span class="compass-saas-selfhost">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
									Also available as self-hosted plugin with sovereign site license
								</span>
								<span class="compass-saas-price js-m-saas-price"></span>
							</div>
						</div>

						<!-- Specs Grid (4 Columns) -->
						<div class="compass-specs-grid">
							<div class="compass-spec-item">
								<span class="compass-spec-label">RELEASE</span>
								<span class="compass-spec-value js-m-ver">v26.9.5</span>
							</div>
							<div class="compass-spec-item">
								<span class="compass-spec-label">ACCESS</span>
								<span class="compass-spec-value js-m-access">Public Repository</span>
							</div>
							<div class="compass-spec-item">
								<span class="compass-spec-label">PACKAGE SIZE</span>
								<span class="compass-spec-value js-m-size">0.04 MB</span>
							</div>
							<div class="compass-spec-item">
								<span class="compass-spec-label">PUBLISHER</span>
								<span class="compass-spec-value">Hall of the Gods Inc.</span>
							</div>
						</div>

						<!-- SHA-256 Checksum Row -->
						<div class="compass-checksum-row">
							<div class="compass-checksum-left">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
								<span class="compass-checksum-title">SHA-256 Checksum</span>
							</div>
							<button type="button" class="compass-checksum-copy js-copy-checksum">
								<svg class="js-copy-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
								<span class="js-checksum-btn-text">Copy Checksum</span>
							</button>
						</div>
					</div>

					<!-- Modal Action Buttons Footer -->
					<div class="compass-modal-footer">
						<a href="#" target="_blank" rel="noopener noreferrer" class="compass-btn-view-source js-m-source">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
							<span>View Source</span>
						</a>

						<div class="compass-modal-actions-right">
							<a href="#" target="_blank" rel="noopener noreferrer" class="compass-btn-buy-license js-m-buy">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
								<span class="js-m-buy-text">Buy Site License · $79/yr</span>
							</a>

							<a href="#" target="_blank" rel="noopener noreferrer" class="compass-btn-modal-demo js-m-demo-btn" style="display: none;">
								<span class="js-m-demo-btn-text">Launch Live App</span>
								<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
							</a>
						</div>
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
	private static function render_styles_and_scripts(): void {
		if ( self::$assets_rendered ) {
			return;
		}
		self::$assets_rendered = true;
		?>
		<style id="xophz-compass-catalog-css">
			/* =========================================================================
			   COMPASS CATALOG ROOT & LIGHT/DARK ADAPTIVE THEME
			   ========================================================================= */
			.compass-catalog-root {
				--xo-font: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
				--xo-font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
				--xo-primary: #8b5cf6;
				--xo-primary-glow: rgba(139, 92, 246, 0.35);
				font-family: var(--xo-font);
				box-sizing: border-box;
				position: relative;
				width: 100%;
				margin: 1.5rem 0;
			}
			.compass-catalog-root *,
			.compass-catalog-root *::before,
			.compass-catalog-root *::after {
				box-sizing: border-box;
			}

			/* =========================================================================
			   TOOLBAR: SEARCH & CATEGORY FILTER PILLS
			   ========================================================================= */
			.compass-catalog-toolbar {
				display: flex;
				flex-direction: column;
				gap: 1rem;
				margin-bottom: 1.5rem;
				padding-top: 0.5rem;
				border-top: 1px solid rgba(0, 0, 0, 0.08);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-catalog-toolbar {
				border-top-color: rgba(255, 255, 255, 0.08);
			}
			.compass-toolbar-header {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				justify-content: space-between;
				gap: 1rem;
			}
			.compass-toolbar-title-wrap {
				display: flex;
				align-items: center;
				gap: 0.5rem;
			}
			.compass-toolbar-icon {
				color: #8b5cf6;
				display: flex;
				align-items: center;
			}
			.compass-toolbar-title {
				font-size: 1.25rem;
				font-weight: 700;
				margin: 0;
				color: #0f172a;
				letter-spacing: -0.01em;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-toolbar-title {
				color: #f8fafc;
			}

			/* Search Input */
			.compass-search-wrap {
				position: relative;
				display: flex;
				align-items: center;
				min-width: 260px;
				max-width: 380px;
				flex: 1;
			}
			.compass-search-icon {
				position: absolute;
				left: 0.85rem;
				color: #94a3b8;
				pointer-events: none;
			}
			.compass-search-input {
				width: 100%;
				padding: 0.55rem 2rem 0.55rem 2.4rem;
				font-size: 0.82rem;
				border-radius: 9999px;
				border: 1px solid rgba(228, 228, 231, 0.9);
				background: #ffffff;
				color: #0f172a;
				outline: none;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-search-input {
				background: rgba(255, 255, 255, 0.05);
				border-color: rgba(255, 255, 255, 0.1);
				color: #f8fafc;
			}
			.compass-search-input:focus {
				border-color: #8b5cf6;
				box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
			}
			.compass-search-clear {
				position: absolute;
				right: 0.75rem;
				background: transparent;
				border: none;
				color: #94a3b8;
				cursor: pointer;
				display: flex;
				align-items: center;
				padding: 0;
			}
			.compass-search-clear:hover {
				color: #0f172a;
			}

			/* Filter Pills */
			.compass-filter-pills {
				display: flex;
				flex-wrap: wrap;
				gap: 0.4rem;
			}
			.compass-pill-btn {
				padding: 0.35rem 0.8rem;
				font-size: 0.75rem;
				font-weight: 500;
				border-radius: 9999px;
				border: 1px solid rgba(0, 0, 0, 0.08);
				background: transparent;
				color: #64748b;
				cursor: pointer;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-pill-btn {
				border-color: rgba(255, 255, 255, 0.1);
				color: #94a3b8;
			}
			.compass-pill-btn:hover {
				background: rgba(0, 0, 0, 0.04);
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-pill-btn:hover {
				background: rgba(255, 255, 255, 0.06);
				color: #f8fafc;
			}
			.compass-pill-btn.is-active {
				background: #8b5cf6;
				border-color: #8b5cf6;
				color: #ffffff;
				box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
			}

			.compass-count-row {
				display: flex;
				align-items: center;
			}
			.compass-count-label {
				font-size: 0.65rem;
				font-weight: 700;
				letter-spacing: 0.1em;
				text-transform: uppercase;
				color: #94a3b8;
			}

			/* =========================================================================
			   CARDS GRID & COLUMNS (SCREENSHOT 1 SPEC)
			   ========================================================================= */
			.compass-plugins-grid {
				display: grid;
				gap: 1.25rem;
			}
			.compass-grid-cols-1 { grid-template-columns: 1fr; }
			.compass-grid-cols-2 { grid-template-columns: repeat(auto-fill, minmax(460px, 1fr)); }
			.compass-grid-cols-3 { grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); }
			.compass-grid-cols-4 { grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); }

			@media (max-width: 640px) {
				.compass-plugins-grid {
					grid-template-columns: 1fr !important;
				}
			}

			/* =========================================================================
			   CARD COMPONENT: SIDE LOGO WITHOUT CONTAINER (SCREENSHOT 1 SPEC)
			   ========================================================================= */
			.compass-plug-card {
				position: relative;
				overflow: hidden;
				border-radius: 1rem;
				background: #ffffff;
				border: 1px solid rgba(228, 228, 231, 0.85);
				box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
				backdrop-filter: blur(16px);
				-webkit-backdrop-filter: blur(16px);
				padding: 1rem 1.25rem;
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 0.75rem;
				min-height: 160px;
				cursor: pointer;
				transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-plug-card {
				background: rgba(255, 255, 255, 0.03);
				border-color: rgba(255, 255, 255, 0.07);
				box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
			}
			.compass-plug-card:hover {
				border-color: var(--plug-color);
				box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 0 20px rgba(139, 92, 246, 0.15);
				transform: translateY(-2px);
			}

			/* SIDE LOGO: Raw circular illustration floating on the left with negative margin */
			.compass-side-logo {
				flex-shrink: 0;
				margin-left: -2.75rem;
				width: 140px;
				height: 140px;
				object-fit: contain;
				filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.35));
				pointer-events: none;
				transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
				user-select: none;
			}
			@media (max-width: 640px) {
				.compass-side-logo {
					margin-left: -2rem;
					width: 115px;
					height: 115px;
				}
			}
			.compass-plug-card:hover .compass-side-logo {
				transform: scale(1.06);
			}

			/* Fallback Disk */
			.compass-fallback-disk {
				flex-shrink: 0;
				margin-left: -2.75rem;
				width: 140px;
				height: 140px;
				border-radius: 50%;
				align-items: center;
				justify-content: center;
				box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
				transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
			}
			@media (max-width: 640px) {
				.compass-fallback-disk {
					margin-left: -2rem;
					width: 115px;
					height: 115px;
				}
			}
			.compass-plug-card:hover .compass-fallback-disk {
				transform: scale(1.06);
			}

			/* Details Column */
			.compass-details-col {
				flex: 1;
				min-width: 0;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				padding: 0.15rem 0 0.15rem 0.5rem;
				z-index: 2;
			}

			/* Badges Row */
			.compass-badges-row {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				gap: 0.35rem;
				margin-bottom: 0.35rem;
			}
			.compass-badge {
				display: inline-flex;
				align-items: center;
				padding: 0.15rem 0.45rem;
				border-radius: 4px;
				font-size: 0.65rem;
				font-weight: 600;
				line-height: 1.2;
			}
			.compass-badge-cat {
				background: rgba(139, 92, 246, 0.1);
				color: #8b5cf6;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-badge-cat {
				background: rgba(139, 92, 246, 0.15);
				color: #a78bfa;
			}
			.compass-badge-grp {
				font-family: var(--xo-font-mono);
				font-size: 0.58rem;
				background: rgba(0, 0, 0, 0.05);
				color: #64748b;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-badge-grp {
				background: rgba(255, 255, 255, 0.05);
				color: #94a3b8;
			}
			.compass-badge-live {
				font-family: var(--xo-font-mono);
				background: rgba(16, 185, 129, 0.1);
				color: #10b981;
			}
			.compass-badge-price {
				font-family: var(--xo-font-mono);
				color: #6366f1;
				border: 1px solid rgba(99, 102, 241, 0.35);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-badge-price {
				color: #818cf8;
				border-color: rgba(129, 140, 248, 0.35);
			}

			/* Title & Description */
			.compass-title {
				font-size: 1.15rem;
				font-weight: 700;
				color: #0f172a;
				margin: 0 0 0.25rem 0;
				line-height: 1.3;
				letter-spacing: -0.01em;
				transition: color 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-title {
				color: #f8fafc;
			}
			.compass-plug-card:hover .compass-title {
				color: var(--plug-color);
			}

			.compass-desc {
				font-size: 0.78rem;
				line-height: 1.45;
				color: #64748b;
				margin: 0;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				overflow: hidden;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-desc {
				color: #94a3b8;
			}

			/* Bottom Bar */
			.compass-bottom-bar {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				justify-content: space-between;
				gap: 0.5rem;
				margin-top: 0.75rem;
				padding-top: 0.65rem;
				border-top: 1px solid rgba(0, 0, 0, 0.05);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-bottom-bar {
				border-top-color: rgba(255, 255, 255, 0.05);
			}

			.compass-meta-specs {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				gap: 0.35rem;
				font-size: 0.65rem;
				font-family: var(--xo-font-mono);
				color: #94a3b8;
			}
			.compass-meta-ver {
				font-weight: 700;
				color: #475569;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-meta-ver {
				color: #cbd5e1;
			}
			.compass-meta-dot {
				color: #cbd5e1;
			}
			.compass-meta-eqv,
			.compass-meta-pub {
				color: #64748b;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-meta-eqv,
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-meta-pub {
				color: #94a3b8;
			}

			/* Actions */
			.compass-card-actions {
				display: flex;
				align-items: center;
				gap: 0.4rem;
			}
			.compass-btn-demo {
				display: inline-flex;
				align-items: center;
				gap: 0.3rem;
				padding: 0.3rem 0.65rem;
				font-size: 0.72rem;
				font-weight: 600;
				border-radius: 6px;
				border: 1px solid rgba(0, 0, 0, 0.1);
				background: transparent;
				color: #475569;
				text-decoration: none;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-btn-demo {
				border-color: rgba(255, 255, 255, 0.12);
				color: #cbd5e1;
			}
			.compass-btn-demo:hover {
				background: rgba(0, 0, 0, 0.05);
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-btn-demo:hover {
				background: rgba(255, 255, 255, 0.08);
				color: #ffffff;
			}

			.compass-btn-details {
				display: inline-flex;
				align-items: center;
				gap: 0.3rem;
				padding: 0.3rem 0.65rem;
				font-size: 0.72rem;
				font-weight: 600;
				border-radius: 6px;
				border: none;
				background: rgba(139, 92, 246, 0.1);
				color: #8b5cf6;
				cursor: pointer;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-btn-details {
				background: rgba(139, 92, 246, 0.15);
				color: #a78bfa;
			}
			.compass-btn-details:hover {
				background: #8b5cf6;
				color: #ffffff;
			}

			/* Empty State */
			.compass-empty-state {
				text-align: center;
				padding: 4rem 1rem;
				color: #94a3b8;
			}
			.compass-empty-state svg {
				margin-bottom: 0.75rem;
				opacity: 0.5;
			}

			/* =========================================================================
			   MODAL DIALOG (SCREENSHOT 2 SPEC)
			   ========================================================================= */
			.compass-modal-overlay {
				position: fixed;
				inset: 0;
				z-index: 999999;
				display: none;
				align-items: center;
				justify-content: center;
				padding: 1rem;
				background: rgba(0, 0, 0, 0.7);
				backdrop-filter: blur(16px);
				-webkit-backdrop-filter: blur(16px);
				overflow-y: auto;
			}
			.compass-modal-overlay.is-open {
				display: flex;
			}
			.compass-modal-box {
				position: relative;
				width: 100%;
				max-width: 680px;
				margin: auto;
				border-radius: 1.5rem;
				background: #ffffff;
				border: 1px solid rgba(228, 228, 231, 0.9);
				box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
				padding: 1.75rem 2rem;
				overflow: hidden;
				backdrop-filter: blur(24px);
				-webkit-backdrop-filter: blur(24px);
				animation: compassModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-box {
				background: #18181b;
				border-color: rgba(255, 255, 255, 0.15);
				box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
			}
			@keyframes compassModalPop {
				from {
					opacity: 0;
					transform: scale(0.96) translateY(8px);
				}
				to {
					opacity: 1;
					transform: scale(1) translateY(0);
				}
			}

			/* Ambient Halos */
			.compass-ambient-halo {
				position: absolute;
				width: 280px;
				height: 280px;
				border-radius: 50%;
				filter: blur(80px);
				pointer-events: none;
				opacity: 0.18;
				background-color: var(--plug-color, #8b5cf6);
			}
			.compass-halo-top {
				top: -60px;
				right: -60px;
			}
			.compass-halo-bottom {
				bottom: -60px;
				left: -60px;
			}

			/* Close Button */
			.compass-modal-close {
				position: absolute;
				top: 1.25rem;
				right: 1.25rem;
				width: 2.25rem;
				height: 2.25rem;
				border-radius: 50%;
				border: 1px solid rgba(0, 0, 0, 0.08);
				background: rgba(0, 0, 0, 0.04);
				color: #64748b;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				z-index: 20;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-close {
				border-color: rgba(255, 255, 255, 0.1);
				background: rgba(255, 255, 255, 0.05);
				color: #94a3b8;
			}
			.compass-modal-close:hover {
				background: rgba(0, 0, 0, 0.08);
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-close:hover {
				background: rgba(255, 255, 255, 0.12);
				color: #f8fafc;
			}

			/* Modal Header */
			.compass-modal-header {
				position: relative;
				z-index: 10;
				display: flex;
				align-items: center;
				gap: 1.25rem;
				padding-bottom: 1.25rem;
				border-bottom: 1px solid rgba(0, 0, 0, 0.08);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-header {
				border-bottom-color: rgba(255, 255, 255, 0.1);
			}
			.compass-modal-artwork-wrap {
				width: 88px;
				height: 88px;
				flex-shrink: 0;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.compass-modal-artwork {
				width: 100%;
				height: 100%;
				object-fit: contain;
				filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.35));
			}

			.compass-modal-header-info {
				flex: 1;
				min-width: 0;
			}
			.compass-modal-badges {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				gap: 0.4rem;
				margin-bottom: 0.4rem;
			}
			.compass-badge-license {
				font-family: var(--xo-font-mono);
				background: rgba(2, 132, 199, 0.1);
				border: 1px solid rgba(2, 132, 199, 0.25);
				color: #0284c7;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-badge-license {
				background: rgba(56, 189, 248, 0.1);
				border-color: rgba(56, 189, 248, 0.25);
				color: #38bdf8;
			}
			.compass-modal-tag {
				font-size: 0.65rem;
				font-weight: 500;
				color: #94a3b8;
			}

			.compass-modal-title {
				font-size: 1.75rem;
				font-weight: 700;
				color: #0f172a;
				margin: 0;
				line-height: 1.2;
				letter-spacing: -0.02em;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-title {
				color: #f8fafc;
			}
			.compass-modal-codename {
				font-family: var(--xo-font-mono);
				font-size: 0.75rem;
				color: #94a3b8;
				margin: 0.2rem 0 0 0;
			}

			/* Modal Body */
			.compass-modal-body {
				position: relative;
				z-index: 10;
				display: flex;
				flex-direction: column;
				gap: 1.25rem;
				padding: 1.25rem 0;
			}
			.compass-section-heading {
				font-size: 0.68rem;
				font-weight: 700;
				letter-spacing: 0.12em;
				text-transform: uppercase;
				color: #94a3b8;
				margin: 0 0 0.5rem 0;
			}
			.compass-modal-desc {
				font-size: 0.88rem;
				line-height: 1.6;
				color: #475569;
				margin: 0;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-desc {
				color: #cbd5e1;
			}

			/* Turnkey SaaS Banner */
			.compass-saas-banner {
				padding: 1rem;
				border-radius: 1rem;
				background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(6, 182, 212, 0.04) 100%);
				border: 1px solid rgba(16, 185, 129, 0.2);
				display: flex;
				flex-direction: column;
				gap: 0.75rem;
			}
			.compass-saas-header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-wrap: wrap;
				gap: 0.5rem;
			}
			.compass-saas-left {
				display: flex;
				align-items: center;
				gap: 0.5rem;
			}
			.compass-saas-icon {
				width: 1.75rem;
				height: 1.75rem;
				border-radius: 6px;
				background: rgba(16, 185, 129, 0.15);
				color: #10b981;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.compass-saas-badge {
				display: block;
				font-size: 0.62rem;
				font-weight: 700;
				text-transform: uppercase;
				letter-spacing: 0.08em;
				color: #10b981;
			}
			.compass-saas-headline {
				font-size: 0.85rem;
				font-weight: 700;
				margin: 0;
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-saas-headline {
				color: #f8fafc;
			}
			.compass-saas-link {
				display: inline-flex;
				align-items: center;
				gap: 0.35rem;
				padding: 0.35rem 0.75rem;
				font-size: 0.72rem;
				font-weight: 600;
				border-radius: 6px;
				background: #10b981;
				color: #ffffff;
				text-decoration: none;
			}
			.compass-saas-audience {
				font-size: 0.78rem;
				line-height: 1.45;
				color: #64748b;
				margin: 0;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-saas-audience {
				color: #cbd5e1;
			}
			.compass-saas-footer {
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-wrap: wrap;
				gap: 0.5rem;
				padding-top: 0.5rem;
				border-top: 1px solid rgba(16, 185, 129, 0.15);
				font-size: 0.72rem;
				color: #64748b;
			}
			.compass-saas-selfhost {
				display: flex;
				align-items: center;
				gap: 0.35rem;
			}
			.compass-saas-price {
				font-family: var(--xo-font-mono);
				font-weight: 700;
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-saas-price {
				color: #f8fafc;
			}

			/* Specs Grid (Screenshot 2) */
			.compass-specs-grid {
				display: grid;
				grid-template-columns: repeat(4, 1fr);
				gap: 0.75rem;
				padding: 0.85rem 1rem;
				border-radius: 1rem;
				background: #f8fafc;
				border: 1px solid rgba(228, 228, 231, 0.8);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-specs-grid {
				background: rgba(255, 255, 255, 0.03);
				border-color: rgba(255, 255, 255, 0.08);
			}
			@media (max-width: 600px) {
				.compass-specs-grid {
					grid-template-columns: repeat(2, 1fr);
				}
			}
			.compass-spec-item {
				display: flex;
				flex-direction: column;
				gap: 0.15rem;
			}
			.compass-spec-label {
				font-size: 0.62rem;
				text-transform: uppercase;
				letter-spacing: 0.08em;
				color: #94a3b8;
			}
			.compass-spec-value {
				font-family: var(--xo-font-mono);
				font-size: 0.78rem;
				font-weight: 700;
				color: #0f172a;
				word-break: break-word;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-spec-value {
				color: #e2e8f0;
			}

			/* SHA-256 Checksum Row (Screenshot 2) */
			.compass-checksum-row {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 0.75rem;
				padding: 0.75rem 1rem;
				border-radius: 1rem;
				background: #f8fafc;
				border: 1px solid rgba(228, 228, 231, 0.8);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-checksum-row {
				background: rgba(0, 0, 0, 0.25);
				border-color: rgba(255, 255, 255, 0.08);
			}
			.compass-checksum-left {
				display: flex;
				align-items: center;
				gap: 0.45rem;
				font-size: 0.75rem;
				font-weight: 600;
				color: #10b981;
			}
			.compass-checksum-copy {
				display: inline-flex;
				align-items: center;
				gap: 0.35rem;
				padding: 0.3rem 0.65rem;
				font-size: 0.72rem;
				font-weight: 600;
				border-radius: 6px;
				border: 1px solid rgba(0, 0, 0, 0.08);
				background: #ffffff;
				color: #475569;
				cursor: pointer;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-checksum-copy {
				background: rgba(255, 255, 255, 0.06);
				border-color: rgba(255, 255, 255, 0.1);
				color: #cbd5e1;
			}
			.compass-checksum-copy:hover {
				background: #f1f5f9;
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-checksum-copy:hover {
				background: rgba(255, 255, 255, 0.12);
				color: #ffffff;
			}
			.compass-checksum-copy.is-copied {
				background: rgba(16, 185, 129, 0.1);
				border-color: rgba(16, 185, 129, 0.3);
				color: #10b981;
			}

			/* Modal Footer Actions (Screenshot 2) */
			.compass-modal-footer {
				position: relative;
				z-index: 10;
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				justify-content: space-between;
				gap: 0.75rem;
				padding-top: 1rem;
				border-top: 1px solid rgba(0, 0, 0, 0.08);
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-modal-footer {
				border-top-color: rgba(255, 255, 255, 0.1);
			}
			.compass-btn-view-source {
				display: inline-flex;
				align-items: center;
				gap: 0.4rem;
				padding: 0.5rem 0.9rem;
				font-size: 0.78rem;
				font-weight: 600;
				border-radius: 8px;
				border: 1px solid rgba(0, 0, 0, 0.12);
				background: transparent;
				color: #475569;
				text-decoration: none;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-btn-view-source {
				border-color: rgba(255, 255, 255, 0.15);
				color: #cbd5e1;
			}
			.compass-btn-view-source:hover {
				background: rgba(0, 0, 0, 0.05);
				color: #0f172a;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-btn-view-source:hover {
				background: rgba(255, 255, 255, 0.08);
				color: #ffffff;
			}

			.compass-modal-actions-right {
				display: flex;
				align-items: center;
				gap: 0.5rem;
			}
			.compass-btn-buy-license {
				display: inline-flex;
				align-items: center;
				gap: 0.4rem;
				padding: 0.5rem 1rem;
				font-size: 0.78rem;
				font-weight: 600;
				border-radius: 8px;
				background: rgba(139, 92, 246, 0.12);
				border: 1px solid rgba(139, 92, 246, 0.25);
				color: #8b5cf6;
				text-decoration: none;
				transition: all 0.2s ease;
			}
			:is(.dark, [data-theme="dark"], body.has-surface-body-background-color) .compass-btn-buy-license {
				background: rgba(139, 92, 246, 0.2);
				border-color: rgba(139, 92, 246, 0.35);
				color: #c4b5fd;
			}
			.compass-btn-buy-license:hover {
				background: #8b5cf6;
				border-color: #8b5cf6;
				color: #ffffff;
				box-shadow: 0 4px 14px rgba(139, 92, 246, 0.35);
			}

			.compass-btn-modal-demo {
				display: inline-flex;
				align-items: center;
				gap: 0.35rem;
				padding: 0.5rem 1rem;
				font-size: 0.78rem;
				font-weight: 600;
				border-radius: 8px;
				background: #8b5cf6;
				color: #ffffff;
				text-decoration: none;
				box-shadow: 0 4px 14px rgba(139, 92, 246, 0.35);
				transition: all 0.2s ease;
			}
			.compass-btn-modal-demo:hover {
				background: #7c3aed;
			}
		</style>

		<script id="xophz-compass-catalog-js">
			document.addEventListener('DOMContentLoaded', function() {
				document.querySelectorAll('.compass-catalog-root').forEach(function(root) {
					var searchInput = root.querySelector('.compass-search-input');
					var clearBtn = root.querySelector('.compass-search-clear');
					var pillBtns = root.querySelectorAll('.compass-pill-btn');
					var cards = root.querySelectorAll('.compass-plug-card');
					var countEl = root.querySelector('.compass-visible-count');
					var emptyEl = root.querySelector('.compass-empty-state');
					var modal = root.querySelector('.compass-modal-overlay');
					var modalBox = root.querySelector('.compass-modal-box');
					var closeBtn = root.querySelector('.compass-modal-close');
					var copyBtn = root.querySelector('.js-copy-checksum');
					var copyText = root.querySelector('.js-checksum-btn-text');

					var currentCategory = 'All';
					var currentSearch = '';
					var activeChecksum = '';

					// Filter function
					function filterCards() {
						var visibleCount = 0;
						cards.forEach(function(card) {
							var cat = card.getAttribute('data-category') || '';
							var search = card.getAttribute('data-search') || '';

							var matchCat = (currentCategory === 'All') || (cat.toLowerCase() === currentCategory.toLowerCase());
							var matchSearch = !currentSearch || (search.indexOf(currentSearch) !== -1);

							if (matchCat && matchSearch) {
								card.style.display = 'flex';
								visibleCount++;
							} else {
								card.style.display = 'none';
							}
						});

						if (countEl) {
							countEl.textContent = visibleCount;
						}
						if (emptyEl) {
							emptyEl.style.display = (visibleCount === 0) ? 'block' : 'none';
						}
					}

					// Search handler
					if (searchInput) {
						searchInput.addEventListener('input', function(e) {
							currentSearch = e.target.value.toLowerCase().trim();
							if (clearBtn) {
								clearBtn.style.display = currentSearch ? 'flex' : 'none';
							}
							filterCards();
						});
					}

					if (clearBtn && searchInput) {
						clearBtn.addEventListener('click', function() {
							searchInput.value = '';
							currentSearch = '';
							clearBtn.style.display = 'none';
							searchInput.focus();
							filterCards();
						});
					}

					// Tab button handler
					pillBtns.forEach(function(btn) {
						btn.addEventListener('click', function() {
							pillBtns.forEach(function(b) { b.classList.remove('is-active'); });
							btn.classList.add('is-active');
							currentCategory = btn.getAttribute('data-category') || 'All';
							filterCards();
						});
					});

					// Modal Population & Trigger
					function openModalWithPlugin(plugin) {
						if (!plugin || !modal) return;

						// Set accent glow color
						var plugColor = plugin.color || '#8b5cf6';
						modalBox.style.setProperty('--plug-color', plugColor);

						// Artwork image
						var artworkImg = modal.querySelector('.compass-modal-artwork');
						if (artworkImg) {
							artworkImg.src = plugin.logoUrl ? ('https://xophz.com' + plugin.logoUrl) : '';
							artworkImg.alt = plugin.name || '';
						}

						// Badges & Header
						var mCat = modal.querySelector('.js-m-cat');
						if (mCat) mCat.textContent = plugin.category || '';

						var mGrp = modal.querySelector('.js-m-grp');
						if (mGrp) {
							mGrp.textContent = plugin.group || '';
							mGrp.style.display = plugin.group ? 'inline-flex' : 'none';
						}

						var mPrice = modal.querySelector('.js-m-price');
						if (mPrice) mPrice.textContent = (plugin.price || '$79/yr') + ' License';

						var mTag = modal.querySelector('.js-m-tag');
						if (mTag) mTag.textContent = plugin.tag || '';

						var mTitle = modal.querySelector('.js-m-title');
						if (mTitle) mTitle.textContent = plugin.name || '';

						var mCode = modal.querySelector('.js-m-codename');
						if (mCode) mCode.textContent = plugin.codename || '';

						// Description
						var mDesc = modal.querySelector('.js-m-desc');
						if (mDesc) mDesc.textContent = plugin.desc || '';

						// Turnkey Cloud SaaS Showcase Banner
						var saasBox = modal.querySelector('.js-m-saas-box');
						if (saasBox) {
							var hasSaas = Boolean(plugin.saasOffer || plugin.showcaseUrl);
							saasBox.style.display = hasSaas ? 'flex' : 'none';
							if (hasSaas) {
								var sBdg = modal.querySelector('.js-m-saas-badge');
								var sHdl = modal.querySelector('.js-m-saas-headline');
								var sAud = modal.querySelector('.js-m-saas-audience');
								var sBtn = modal.querySelector('.js-m-saas-btn');
								var sPrc = modal.querySelector('.js-m-saas-price');

								if (sBdg) sBdg.textContent = (plugin.saasOffer && plugin.saasOffer.badge) ? plugin.saasOffer.badge : 'Turnkey Cloud SaaS';
								if (sHdl) sHdl.textContent = (plugin.saasOffer && plugin.saasOffer.headline) ? plugin.saasOffer.headline : (plugin.name + ' Hosted Cloud');
								if (sAud) sAud.textContent = (plugin.saasOffer && plugin.saasOffer.audience) ? plugin.saasOffer.audience : 'Fully managed sovereign cloud instance with zero DevOps or server maintenance required.';
								if (sPrc) sPrc.textContent = plugin.price || '';
								if (sBtn) {
									var targetUrl = (plugin.saasOffer && plugin.saasOffer.url) ? plugin.saasOffer.url : (plugin.showcaseUrl || '#');
									sBtn.href = targetUrl;
									sBtn.style.display = targetUrl ? 'inline-flex' : 'none';
								}
							}
						}

						// Specs Grid
						var mVer = modal.querySelector('.js-m-ver');
						if (mVer) mVer.textContent = plugin.version || 'v26.9.5';

						var mAccess = modal.querySelector('.js-m-access');
						if (mAccess) mAccess.textContent = plugin.isPrivate ? 'Commercial / SaaS' : 'Public Repository';

						var mSize = modal.querySelector('.js-m-size');
						if (mSize) mSize.textContent = plugin.isPrivate ? 'Commercial' : '0.04 MB';

						// Deterministic synthetic SHA-256 hash
						activeChecksum = 'sha256-' + btoa(plugin.codename + (plugin.version || 'v26.9.5')).substring(0, 32).toLowerCase();
						if (copyBtn) {
							copyBtn.classList.remove('is-copied');
							if (copyText) copyText.textContent = 'Copy Checksum';
						}

						// Footer Buttons
						var sourceBtn = modal.querySelector('.js-m-source');
						if (sourceBtn) {
							sourceBtn.href = plugin.repoUrl || 'https://github.com/HalloftheGods/' + (plugin.githubRepo || plugin.codename);
							sourceBtn.style.display = plugin.isPrivate ? 'none' : 'inline-flex';
						}

						var buyBtn = modal.querySelector('.js-m-buy');
						var buyText = modal.querySelector('.js-m-buy-text');
						if (buyBtn) {
							var pattern = root.getAttribute('data-checkout-pattern') || 'https://xophz.com/my-compass?plugin={key}&purchased=true';
							var checkoutHref = pattern.replace('{key}', encodeURIComponent(plugin.key || plugin.codename));
							buyBtn.href = checkoutHref;
							if (buyText) buyText.textContent = 'Buy Site License · ' + (plugin.price || '$79/yr');
						}

						var demoBtn = modal.querySelector('.js-m-demo-btn');
						var demoText = modal.querySelector('.js-m-demo-btn-text');
						if (demoBtn) {
							if (plugin.showcaseUrl) {
								demoBtn.href = plugin.showcaseUrl;
								demoBtn.style.display = 'inline-flex';
								if (demoText) demoText.textContent = plugin.showcaseLabel ? ('Open ' + plugin.showcaseLabel) : 'Launch Live App';
							} else {
								demoBtn.style.display = 'none';
							}
						}

						// Show modal & prevent background scroll
						modal.classList.add('is-open');
						modal.setAttribute('aria-hidden', 'false');
						document.body.style.overflow = 'hidden';
					}

					function closeModal() {
						if (!modal) return;
						modal.classList.remove('is-open');
						modal.setAttribute('aria-hidden', 'true');
						document.body.style.overflow = '';
					}

					// Card Click Handler
					cards.forEach(function(card) {
						card.addEventListener('click', function(e) {
							// If clicked a direct link (like the demo button), let it navigate
							if (e.target.closest('a')) return;

							var raw = card.getAttribute('data-plugin');
							if (!raw) return;
							try {
								var plugin = JSON.parse(raw);
								openModalWithPlugin(plugin);
							} catch (err) {
								console.error('Failed to parse plugin data', err);
							}
						});
					});

					// Checksum Copy Button
					if (copyBtn) {
						copyBtn.addEventListener('click', function() {
							if (!activeChecksum) return;
							if (navigator.clipboard && navigator.clipboard.writeText) {
								navigator.clipboard.writeText(activeChecksum).then(function() {
									copyBtn.classList.add('is-copied');
									if (copyText) copyText.textContent = 'Copied Checksum!';
									setTimeout(function() {
										copyBtn.classList.remove('is-copied');
										if (copyText) copyText.textContent = 'Copy Checksum';
									}, 2500);
								}).catch(function() {
									copyBtn.classList.add('is-copied');
									if (copyText) copyText.textContent = 'Copied!';
								});
							}
						});
					}

					// Close handlers
					if (closeBtn) {
						closeBtn.addEventListener('click', closeModal);
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
