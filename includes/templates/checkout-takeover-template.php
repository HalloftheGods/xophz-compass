<?php
/**
 * Bedrock Checkout Takeover Template
 *
 * Immersive full-screen dark glassmorphic takeover for selecting
 * Compass plugin site license count and billing terms (Annual vs Lifetime).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$smoke_js_path = defined( 'WPMU_PLUGIN_DIR' )
	? WPMU_PLUGIN_DIR . '/blackbox-bedrock/assets/js/smoke-canvas.js'
	: WP_CONTENT_DIR . '/mu-plugins/blackbox-bedrock/assets/js/smoke-canvas.js';
$smoke_js = file_exists( $smoke_js_path ) ? file_get_contents( $smoke_js_path ) : '';

$logo_url = function_exists( 'content_url' )
	? content_url( 'mu-plugins/blackbox-bedrock/assets/images/hallofthegodsinc.png' )
	: '/wp-content/mu-plugins/blackbox-bedrock/assets/images/hallofthegodsinc.png';

$plugin_title = $module['name'] ?? ucwords( str_replace( array( 'xophz-compass-', 'xophz-' ), '', $raw_slug ) );
$plugin_desc  = $module['description'] ?? 'Sovereign plugin package for Project Compass and YouMeOS.';
$plugin_category = $module['category'] ?? 'Command Deck';

// Valuations comparison
$market_equivalent = 'Enterprise Solution';
if ( class_exists( '\BlackBOX\Admin\Dashboard' ) ) {
	$vals = \BlackBOX\Admin\Dashboard::get_valuations();
	$clean_key = str_replace( array( 'xophz-compass-', 'xophz-' ), '', sanitize_key( $raw_slug ) );
	$val_entry = $vals[ $raw_slug ] ?? ( $vals[ 'xophz-compass-' . $clean_key ] ?? ( $vals[ $clean_key ] ?? null ) );
	if ( ! empty( $val_entry[1] ) ) {
		$market_equivalent = $val_entry[1];
	}
}

// Compute prices for all tiers
$p_annual   = Xophz_Compass_Modules_API::get_plugin_pricing( $raw_slug, 'personal', 'annual' )['price'];
$p_lifetime = Xophz_Compass_Modules_API::get_plugin_pricing( $raw_slug, 'personal', 'lifetime' )['price'];

$b_annual   = Xophz_Compass_Modules_API::get_plugin_pricing( $raw_slug, 'business', 'annual' )['price'];
$b_lifetime = Xophz_Compass_Modules_API::get_plugin_pricing( $raw_slug, 'business', 'lifetime' )['price'];

$a_annual   = Xophz_Compass_Modules_API::get_plugin_pricing( $raw_slug, 'agency', 'annual' )['price'];
$a_lifetime = Xophz_Compass_Modules_API::get_plugin_pricing( $raw_slug, 'agency', 'lifetime' )['price'];

$return_url = ! empty( $query['return_origin'] )
	? esc_url( $query['return_origin'] )
	: ( ! empty( $query['return_url'] ) ? esc_url( $query['return_url'] ) : 'https://xophz.com/compass' );

$is_test = ! empty( $query['test'] ) || ! empty( $query['test_mode'] ) || ( strpos( $return_url, 'localhost' ) !== false || strpos( $return_url, '127.0.0.1' ) !== false );
$base_buy_url = home_url( '/buy/my-compass/' . sanitize_key( $raw_slug ) );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $plugin_title ); ?> - Choose License Tier | Project Compass</title>
	<style>
		:root {
			--hog-gold: #d9be6f;
			--hog-cyan: #62c9ff;
			--compass-bg: radial-gradient(farthest-corner circle at 0% 0%, #0d1117 0%, #05070a 100%);
			--rough-glass-bg: linear-gradient(135deg, rgba(13, 17, 23, 0.90), rgba(20, 26, 38, 0.75));
			--rough-glass-border: rgba(90, 105, 172, 0.35);
			--rough-glass-filter: blur(20px) saturate(160%);
			--text-main: #f8f8f2;
			--text-muted: #94a3b8;
			--accent: #8b5cf6;
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		html, body {
			min-height: 100vh;
			background: var(--compass-bg);
			background-attachment: fixed;
			color: var(--text-main);
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
			overflow-x: hidden;
		}

		.takeover-wrapper {
			position: relative;
			z-index: 10;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 40px 20px;
		}

		.takeover-panel {
			width: 100%;
			max-width: 1080px;
			background: var(--rough-glass-bg);
			backdrop-filter: var(--rough-glass-filter);
			-webkit-backdrop-filter: var(--rough-glass-filter);
			border: 1px solid var(--rough-glass-border);
			border-radius: 24px;
			padding: 40px 32px;
			box-shadow: 0 25px 80px rgba(0, 0, 0, 0.75), inset 0 1px 0 rgba(255, 255, 255, 0.1);
			text-align: center;
			position: relative;
		}

		.brand-header {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 12px;
			margin-bottom: 24px;
		}

		.badge-gold {
			display: inline-block;
			background: rgba(217, 190, 111, 0.15);
			border: 1px solid rgba(217, 190, 111, 0.3);
			color: var(--hog-gold);
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1.5px;
			padding: 5px 14px;
			border-radius: 20px;
		}

		.badge-cyan {
			display: inline-block;
			background: rgba(98, 201, 255, 0.15);
			border: 1px solid rgba(98, 201, 255, 0.3);
			color: var(--hog-cyan);
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1.5px;
			padding: 5px 14px;
			border-radius: 20px;
		}

		.title-main {
			font-size: 32px;
			font-weight: 700;
			letter-spacing: -0.5px;
			margin-bottom: 8px;
			color: #ffffff;
		}

		.desc-main {
			font-size: 15px;
			color: var(--text-muted);
			max-width: 640px;
			margin: 0 auto 28px;
			line-height: 1.6;
		}

		/* Billing Switcher Toggle */
		.billing-switch-container {
			display: inline-flex;
			align-items: center;
			background: rgba(0, 0, 0, 0.4);
			border: 1px solid rgba(255, 255, 255, 0.12);
			border-radius: 40px;
			padding: 5px;
			margin-bottom: 36px;
			gap: 4px;
		}

		.switch-btn {
			border: none;
			background: transparent;
			color: var(--text-muted);
			padding: 10px 22px;
			border-radius: 30px;
			font-size: 13px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.25s ease;
		}

		.switch-btn.active {
			background: var(--hog-cyan);
			color: #05070a;
			box-shadow: 0 0 15px rgba(98, 201, 255, 0.4);
		}

		.lifetime-pill {
			background: rgba(217, 190, 111, 0.25);
			color: var(--hog-gold);
			font-size: 10px;
			padding: 2px 7px;
			border-radius: 10px;
			margin-left: 6px;
			font-weight: 700;
		}

		/* Pricing Grid */
		.pricing-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 24px;
			text-align: left;
			margin-bottom: 32px;
		}

		.pricing-card {
			background: rgba(15, 23, 42, 0.6);
			border: 1px solid rgba(255, 255, 255, 0.1);
			border-radius: 18px;
			padding: 28px 24px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
			position: relative;
		}

		.pricing-card:hover {
			transform: translateY(-4px);
			border-color: rgba(98, 201, 255, 0.4);
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
		}

		.pricing-card.featured {
			border-color: rgba(217, 190, 111, 0.6);
			background: linear-gradient(135deg, rgba(20, 26, 42, 0.8), rgba(15, 23, 42, 0.7));
			box-shadow: 0 0 25px rgba(217, 190, 111, 0.15);
		}

		.featured-badge {
			position: absolute;
			top: -12px;
			right: 20px;
			background: var(--hog-gold);
			color: #05070a;
			font-size: 10px;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 1px;
			padding: 3px 10px;
			border-radius: 12px;
		}

		.card-tier-name {
			font-size: 20px;
			font-weight: 700;
			margin-bottom: 4px;
			color: #ffffff;
		}

		.card-sites-label {
			font-size: 13px;
			color: var(--hog-cyan);
			margin-bottom: 16px;
			font-weight: 600;
		}

		.card-price-box {
			margin-bottom: 24px;
		}

		.price-val {
			font-size: 38px;
			font-weight: 800;
			color: #ffffff;
			letter-spacing: -1px;
		}

		.price-period {
			font-size: 14px;
			color: var(--text-muted);
			font-weight: 500;
		}

		.card-features {
			list-style: none;
			margin-bottom: 28px;
			font-size: 13px;
			color: #cbd5e1;
		}

		.card-features li {
			margin-bottom: 10px;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.card-features li::before {
			content: "\2713";
			color: var(--hog-cyan);
			font-weight: bold;
		}

		.btn-checkout {
			display: block;
			width: 100%;
			text-align: center;
			padding: 13px 20px;
			border-radius: 12px;
			font-size: 13px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1px;
			text-decoration: none;
			transition: all 0.2s ease;
			border: none;
			cursor: pointer;
		}

		.btn-standard {
			background: rgba(255, 255, 255, 0.08);
			border: 1px solid rgba(255, 255, 255, 0.15);
			color: #ffffff;
		}

		.btn-standard:hover {
			background: rgba(255, 255, 255, 0.16);
			border-color: rgba(255, 255, 255, 0.3);
		}

		.btn-highlight {
			background: linear-gradient(135deg, var(--hog-cyan), #3b82f6);
			color: #05070a;
			box-shadow: 0 0 20px rgba(98, 201, 255, 0.3);
		}

		.btn-highlight:hover {
			opacity: 0.95;
			box-shadow: 0 0 25px rgba(98, 201, 255, 0.5);
		}

		.cancel-link {
			color: var(--text-muted);
			font-size: 13px;
			text-decoration: none;
			transition: color 0.2s ease;
		}

		.cancel-link:hover {
			color: var(--hog-gold);
		}

		.test-indicator {
			margin-top: 14px;
			font-size: 11px;
			color: var(--hog-gold);
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 1px;
		}
	</style>
</head>
<body id="checkout-takeover-page">
	<div class="takeover-wrapper">
		<div class="takeover-panel">
			<div class="brand-header">
				<span class="badge-gold">Sovereign Ecosystem</span>
				<span class="badge-cyan"><?php echo esc_html( $plugin_category ); ?></span>
			</div>

			<h1 class="title-main"><?php echo esc_html( $plugin_title ); ?></h1>
			<p class="desc-main"><?php echo esc_html( $plugin_desc ); ?> (Market Equivalent: <?php echo esc_html( $market_equivalent ); ?>)</p>

			<!-- Toggle Switch -->
			<div class="billing-switch-container">
				<button type="button" class="switch-btn active" id="btn-annual" onclick="setBilling('annual')">Annual License</button>
				<button type="button" class="switch-btn" id="btn-lifetime" onclick="setBilling('lifetime')">
					Lifetime Deal <span class="lifetime-pill">Pay Once</span>
				</button>
			</div>

			<!-- 3 Pricing Cards -->
			<div class="pricing-grid">
				<!-- Tier 1: Personal -->
				<div class="pricing-card">
					<div>
						<h2 class="card-tier-name">Personal</h2>
						<div class="card-sites-label">1 Production Site License</div>
						<div class="card-price-box">
							<span class="price-val" id="price-personal">$<?php echo (int) $p_annual; ?></span>
							<span class="price-period" id="period-personal">/yr</span>
						</div>
						<ul class="card-features">
							<li>1 Active domain activation</li>
							<li>All spark & module updates</li>
							<li>Automated schema migration</li>
							<li>Standard community support</li>
						</ul>
					</div>
					<a id="cta-personal" href="<?php echo esc_url( add_query_arg( array( 'tier' => 'personal', 'billing' => 'annual', 'return_origin' => $return_url, 'test' => $is_test ? '1' : false ), $base_buy_url ) ); ?>" class="btn-checkout btn-standard">
						Select Personal
					</a>
				</div>

				<!-- Tier 2: Business (Featured) -->
				<div class="pricing-card featured">
					<div class="featured-badge">Most Popular</div>
					<div>
						<h2 class="card-tier-name">Business</h2>
						<div class="card-sites-label">5 Production Site Licenses</div>
						<div class="card-price-box">
							<span class="price-val" id="price-business">$<?php echo (int) $b_annual; ?></span>
							<span class="price-period" id="period-business">/yr</span>
						</div>
						<ul class="card-features">
							<li>5 Active domain activations</li>
							<li>Staging & development licenses</li>
							<li>Priority bugfix & feature priority</li>
							<li>Direct ticket support channel</li>
						</ul>
					</div>
					<a id="cta-business" href="<?php echo esc_url( add_query_arg( array( 'tier' => 'business', 'billing' => 'annual', 'return_origin' => $return_url, 'test' => $is_test ? '1' : false ), $base_buy_url ) ); ?>" class="btn-checkout btn-highlight">
						Select Business
					</a>
				</div>

				<!-- Tier 3: Agency -->
				<div class="pricing-card">
					<div>
						<h2 class="card-tier-name">Agency</h2>
						<div class="card-sites-label">Unlimited Client Deployments</div>
						<div class="card-price-box">
							<span class="price-val" id="price-agency">$<?php echo (int) $a_annual; ?></span>
							<span class="price-period" id="period-agency">/yr</span>
						</div>
						<ul class="card-features">
							<li>Unlimited client domain deployments</li>
							<li>White-label client re-branding</li>
							<li>Full source inspection & hooks</li>
							<li>VIP Slack / priority support link</li>
						</ul>
					</div>
					<a id="cta-agency" href="<?php echo esc_url( add_query_arg( array( 'tier' => 'agency', 'billing' => 'annual', 'return_origin' => $return_url, 'test' => $is_test ? '1' : false ), $base_buy_url ) ); ?>" class="btn-checkout btn-standard">
						Select Agency
					</a>
				</div>
			</div>

			<a href="<?php echo esc_url( $return_url ); ?>" class="cancel-link">&larr; Cancel and return to xophz.com</a>

			<?php if ( $is_test ) : ?>
				<div class="test-indicator">&#9888; Sandbox / Test Mode Active (Stripe Test Gateway)</div>
			<?php endif; ?>
		</div>
	</div>

	<script>
		const baseBuyUrl = <?php echo json_encode( $base_buy_url ); ?>;
		const returnUrl  = <?php echo json_encode( $return_url ); ?>;
		const isTestMode = <?php echo json_encode( $is_test ); ?>;

		const pricing = {
			annual: {
				personal: <?php echo (int) $p_annual; ?>,
				business: <?php echo (int) $b_annual; ?>,
				agency:   <?php echo (int) $a_annual; ?>,
				period:   "/yr"
			},
			lifetime: {
				personal: <?php echo (int) $p_lifetime; ?>,
				business: <?php echo (int) $b_lifetime; ?>,
				agency:   <?php echo (int) $a_lifetime; ?>,
				period:   " one-time"
			}
		};

		function setBilling(mode) {
			const btnAnnual   = document.getElementById('btn-annual');
			const btnLifetime = document.getElementById('btn-lifetime');

			if (mode === 'annual') {
				btnAnnual.classList.add('active');
				btnLifetime.classList.remove('active');
			} else {
				btnLifetime.classList.add('active');
				btnAnnual.classList.remove('active');
			}

			const data = pricing[mode];
			['personal', 'business', 'agency'].forEach(tier => {
				document.getElementById(`price-${tier}`).textContent = '$' + data[tier];
				document.getElementById(`period-${tier}`).textContent = data.period;

				const testParam = isTestMode ? '&test=1' : '';
				const targetUrl = `${baseBuyUrl}?tier=${tier}&billing=${mode}&return_origin=${encodeURIComponent(returnUrl)}${testParam}`;
				document.getElementById(`cta-${tier}`).setAttribute('href', targetUrl);
			});
		}
	</script>
	<script><?php echo $smoke_js; ?></script>
</body>
</html>
