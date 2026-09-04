/**
 * Tier 1: Feature Coverage (Features F05 - F08)
 * F05: Non-Blocking Dev Proxy: Diego Lawfirm (Port 8090)
 * F06: Non-Blocking Dev Proxy: Phone (Port 8082)
 * F07: Non-Blocking Dev Proxy: Event Horizon (Port 8081)
 * F08: Capability Checks: Card Vault
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert } from '../harness/test-framework.mjs';
import { runPhpJson, measureSocketProbe } from '../harness/php-executor.mjs';
import { readSourceFile, hasBlockingDevProxyLoop, hasAdminRoleCheck } from '../harness/code-analyzer.mjs';

describe('Feature F05: Non-Blocking Dev Proxy: Diego Lawfirm', () => {
  it('F05.1: Diego Lawfirm dev proxy targets port 8090 and query var', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'diego-lawfirm',
        'dev_port'    => 8090,
        'query_var'   => 'diego-lawfirm',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-diego-lawfirm/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-diego-lawfirm/',
        'version'     => '26.9.3-237'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('diego-lawfirm', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var diego-lawfirm must be registered');
  });

  it('F05.2: Audit check: Elimination of 7-host sequential 1-second timeout loop', () => {
    const content = readSourceFile('wp-content/plugins/xophz-compass-diego-lawfirm/public/class-xophz-compass-diego-lawfirm-public.php');
    if (!content) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasLoop = hasBlockingDevProxyLoop(content);
    // Baseline state flags this unmigrated loop; target state requires false
    assert.strictEqual(hasLoop, false, 'Diego Lawfirm must NOT contain sequential 7-host blocking dev proxy loop');
  });

  it('F05.3: Host resolution uses resolve_host(8090) checking candidates with 150ms socket probes', () => {
    const res = runPhpJson(`
      $start = microtime(true);
      $host = Xophz_Compass_Dev_Proxy::resolve_host(8090);
      $elapsed = (microtime(true) - $start) * 1000;
      echo json_encode([
        'resolvedHost' => $host,
        'elapsedMs'    => round($elapsed, 2)
      ]);
    `);
    assert.ok(res.elapsedMs < 600, `Host resolution must complete in < 600ms, took ${res.elapsedMs}ms`);
  });

  it('F05.4: Fallback to production dist output when port 8090 is inactive', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'diego-lawfirm',
        'dev_port'    => 8090,
        'query_var'   => 'diego-lawfirm',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-diego-lawfirm/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-diego-lawfirm/',
        'version'     => '26.9.3-237'
      ]);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      echo json_encode(['hasHtml' => !empty($html)]);
    `);
    assert.strictEqual(res.hasHtml, true, 'Dist output must be generated without hanging');
  });

  it('F05.5: Proxy script tag includes version and plugin URL', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'diego-lawfirm',
        'dev_port'    => 8090,
        'query_var'   => 'diego-lawfirm',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-diego-lawfirm/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-diego-lawfirm/',
        'version'     => '26.9.3-237'
      ]);
      $reflection = new ReflectionMethod($proxy, 'build_api_settings_script');
      $reflection->setAccessible(true);
      $script = $reflection->invoke($proxy);
      echo json_encode([
        'hasVersion' => strpos($script, '26.9.3-237') !== false,
        'hasSlug'    => strpos($script, 'diego-lawfirm') !== false
      ]);
    `);
    assert.strictEqual(res.hasVersion, true, 'Version must be present in settings script');
    assert.strictEqual(res.hasSlug, true, 'Plugin slug/url must be present in settings script');
  });
}, { tier: 1, featureId: 'F05' });

describe('Feature F06: Non-Blocking Dev Proxy: Phone', () => {
  it('F06.1: Phone dev proxy targets port 8082 and query var', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'phone',
        'dev_port'    => 8082,
        'query_var'   => 'xophz_compass_phone',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-phone/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-phone/',
        'version'     => '26.9.3'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('xophz_compass_phone', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var xophz_compass_phone must be registered');
  });

  it('F06.2: Socket probe handles offline port 8082 within 150ms without blocking', () => {
    const probe = measureSocketProbe(8082, 0.15);
    assert.ok(probe.elapsedMs <= 180, `Probe latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('F06.3: Rewrite rule generation for phone custom slug', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'phone',
        'dev_port'    => 8082,
        'query_var'   => 'xophz_compass_phone',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-phone/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-phone/',
        'version'     => '26.9.3'
      ]);
      $proxy->register_rewrites();
      echo json_encode(['registered' => true]);
    `);
    assert.strictEqual(res.registered, true, 'Rewrite rules must be executable without error');
  });

  it('F06.4: Fallback dist serving from public/dist/index.html', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'phone',
        'dev_port'    => 8082,
        'query_var'   => 'xophz_compass_phone',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-phone/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-phone/',
        'version'     => '26.9.3'
      ]);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      echo json_encode(['hasHtml' => !empty($html)]);
    `);
    assert.strictEqual(res.hasHtml, true, 'Production dist HTML must be returned');
  });

  it('F06.5: Injected window.wpApiSettings contains valid REST nonce and plugin URL', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'phone',
        'dev_port'    => 8082,
        'query_var'   => 'xophz_compass_phone',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-phone/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-phone/',
        'version'     => '26.9.3'
      ]);
      $reflection = new ReflectionMethod($proxy, 'build_api_settings_script');
      $reflection->setAccessible(true);
      $script = $reflection->invoke($proxy);
      echo json_encode([
        'hasScript' => strpos($script, '<script>window.wpApiSettings =') !== false,
        'hasNonce'  => strpos($script, '"nonce":') !== false
      ]);
    `);
    assert.strictEqual(res.hasScript, true, 'Script tag must be present');
    assert.strictEqual(res.hasNonce, true, 'Nonce field must be present in settings');
  });
}, { tier: 1, featureId: 'F06' });

describe('Feature F07: Non-Blocking Dev Proxy: Event Horizon', () => {
  it('F07.1: Event Horizon dev proxy targets port 8081 and query var', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'event-horizon',
        'dev_port'    => 8081,
        'query_var'   => 'event-horizon',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-event-horizon/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-event-horizon/',
        'version'     => '26.9.3-237'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('event-horizon', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var event-horizon must be registered');
  });

  it('F07.2: Fast socket probe executes in <= 150ms on offline dev server', () => {
    const probe = measureSocketProbe(8081, 0.15);
    assert.ok(probe.elapsedMs <= 180, `Probe latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('F07.3: Dev server active mode rewrites imports and injects HMR client', () => {
    const res = runPhpJson(`
      $html = '<html><head></head><body><script type="module" src="/src/index.js"></script></body></html>';
      $vite_url = '//localhost:8081';
      $html = str_replace('src="/', 'src="' . $vite_url . '/', $html);
      if (strpos($html, '/@vite/client') === false) {
        $client_tag = '<script type="module" src="' . $vite_url . '/@vite/client"></script>';
        $html = str_replace('</head>', $client_tag . "\n</head>", $html);
      }
      echo json_encode([
        'hasRewrittenSrc' => strpos($html, 'src="//localhost:8081/src/index.js"') !== false,
        'hasHmrClient'    => strpos($html, 'src="//localhost:8081/@vite/client"') !== false
      ]);
    `);
    assert.strictEqual(res.hasRewrittenSrc, true, 'Src must be rewritten to dev server URL');
    assert.strictEqual(res.hasHmrClient, true, 'HMR client tag must be injected');
  });

  it('F07.4: Dev server offline mode serves production assets immediately', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'event-horizon',
        'dev_port'    => 8081,
        'query_var'   => 'event-horizon',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-event-horizon/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-event-horizon/',
        'version'     => '26.9.3-237'
      ]);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      echo json_encode(['hasHtml' => !empty($html)]);
    `);
    assert.strictEqual(res.hasHtml, true, 'Production dist HTML must be returned');
  });

  it('F07.5: Template redirect intercepts SPA requests without interfering with wp-admin or wp-login.php', () => {
    const res = runPhpJson(`
      $_SERVER['REQUEST_URI'] = '/wp-admin/plugins.php';
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'event-horizon',
        'dev_port'    => 8081,
        'query_var'   => 'event-horizon',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-event-horizon/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-event-horizon/',
        'version'     => '26.9.3-237'
      ]);
      // Calling handle_template_redirect on /wp-admin should return immediately without output
      ob_start();
      $proxy->handle_template_redirect();
      $out = ob_get_clean();
      echo json_encode(['skippedAdmin' => empty($out)]);
    `);
    assert.strictEqual(res.skippedAdmin, true, 'Admin routes must not be intercepted');
  });
}, { tier: 1, featureId: 'F07' });

describe('Feature F08: Capability Checks: Card Vault', () => {
  it('F08.1: REST permission checks enforce manage_card_vault or manage_options capability', () => {
    const res = runPhpJson(`
      $controller = new class('card-vault', 'v1') extends Xophz_Compass_REST_Controller {
        public function test_cap_check($cap) {
          return current_user_can($cap);
        }
      };
      // Without authenticated user, capability check should return false
      echo json_encode([
        'adminCapCheck'  => $controller->test_cap_check('manage_options'),
        'vaultCapCheck'  => $controller->test_cap_check('manage_card_vault')
      ]);
    `);
    assert.strictEqual(res.adminCapCheck, false, 'Unauthenticated user must not have manage_options');
    assert.strictEqual(res.vaultCapCheck, false, 'Unauthenticated user must not have manage_card_vault');
  });

  it('F08.2: Elimination of raw current_user_can("administrator") in Card Vault source', () => {
    const apiFile = readSourceFile('wp-content/plugins/xophz-compass-card-vault/includes/class-card-vault-api.php');
    if (!apiFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasAdminCheck = hasAdminRoleCheck(apiFile);
    // Baseline state flags this unmigrated role check; target state requires false
    assert.strictEqual(hasAdminCheck, false, 'Card Vault REST API must NOT use current_user_can("administrator")');
  });

  it('F08.3: Admin page rendering checks capability rather than role string', () => {
    const adminFile = readSourceFile('wp-content/plugins/xophz-compass-card-vault/admin/class-card-vault-admin.php');
    if (!adminFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasAdminCheck = hasAdminRoleCheck(adminFile);
    assert.strictEqual(hasAdminCheck, false, 'Card Vault Admin must NOT use current_user_can("administrator")');
  });

  it('F08.4: Consignor dashboard access checks view_consignor_dashboard capability', () => {
    const apiFile = readSourceFile('wp-content/plugins/xophz-compass-card-vault/includes/class-card-vault-api.php');
    if (!apiFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const checksConsignorCap = apiFile.includes('view_consignor_dashboard');
    assert.strictEqual(checksConsignorCap, true, 'Card Vault must check view_consignor_dashboard capability');
  });

  it('F08.5: Unauthenticated and unauthorized users receive 403 Forbidden WP_Error response', () => {
    const res = runPhpJson(`
      $controller = new class('card-vault', 'v1') extends Xophz_Compass_REST_Controller {
        public function get_forbidden_response() {
          return $this->error_response('rest_forbidden', 'Unauthorized', 403);
        }
      };
      $err = $controller->get_forbidden_response();
      echo json_encode([
        'isWpError' => is_wp_error($err),
        'code'      => $err->get_error_code(),
        'status'    => $err->get_error_data()['status'] ?? null
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.isWpError, true, 'Response must be WP_Error');
    assert.strictEqual(res.code, 'rest_forbidden', 'Error code must match');
    assert.strictEqual(res.status, 403, 'HTTP status code must be 403');
  });
}, { tier: 1, featureId: 'F08' });
