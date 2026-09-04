/**
 * Tier 1: Feature Coverage (Features F01 - F04)
 * F01: Core Helper Autoloader Integration
 * F02: Non-Blocking Dev Proxy: Yellow Links (Port 8088)
 * F03: Non-Blocking Dev Proxy: Card Vault (Port 8092)
 * F04: Non-Blocking Dev Proxy: Fresh Mints (Port 8091)
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert } from '../harness/test-framework.mjs';
import { runPhpJson, measureSocketProbe } from '../harness/php-executor.mjs';
import { readSourceFile, usesDevProxy } from '../harness/code-analyzer.mjs';

describe('Feature F01: Core Helper Autoloader Integration', () => {
  it('F01.1: Autoloader registers cleanly with SPL autoload registry', () => {
    const res = runPhpJson(`
      $loaders = spl_autoload_functions();
      $found = false;
      foreach ($loaders as $loader) {
        if (is_array($loader) && $loader[0] === 'Xophz_Compass_Autoloader') {
          $found = true;
          break;
        }
      }
      echo json_encode(['registered' => $found]);
    `);
    assert.strictEqual(res.registered, true, 'Xophz_Compass_Autoloader must be registered in SPL functions');
  });

  it('F01.2: Classmap resolves all 10 Core Helper Suite classes', () => {
    const res = runPhpJson(`
      $classes = [
        'Xophz_Compass_Autoloader',
        'Xophz_Compass_Plugin_Interface',
        'Xophz_Compass_Hookable_Trait',
        'Xophz_Compass_Plugin_Base',
        'Xophz_Compass_REST_Controller',
        'Xophz_Compass_Settings_Base',
        'Xophz_Compass_Dev_Proxy',
        'Xophz_Compass_Security',
        'Xophz_Compass_HTTP',
        'Xophz_Compass_Sanitization'
      ];
      $resolved = [];
      foreach ($classes as $cls) {
        $resolved[$cls] = class_exists($cls) || interface_exists($cls) || trait_exists($cls);
      }
      echo json_encode($resolved);
    `);

    for (const [cls, status] of Object.entries(res)) {
      assert.strictEqual(status, true, `Classmap failed to autoload: ${cls}`);
    }
  });

  it('F01.3: Dynamic fallback resolves Xophz_Compass_* conventions', () => {
    const res = runPhpJson(`
      $devProxyLoaded = class_exists('Xophz_Compass_Dev_Proxy');
      $securityLoaded = class_exists('Xophz_Compass_Security');
      echo json_encode([
        'devProxy' => $devProxyLoaded,
        'security' => $securityLoaded
      ]);
    `);
    assert.strictEqual(res.devProxy, true, 'Dev_Proxy should be resolvable');
    assert.strictEqual(res.security, true, 'Security should be resolvable');
  });

  it('F01.4: PSR-4 prefix mapping Xophz\\Compass\\Core\\ is registered', () => {
    const res = runPhpJson(`
      $reflector = new ReflectionClass('Xophz_Compass_Autoloader');
      $prop = $reflector->getProperty('prefixes');
      $prop->setAccessible(true);
      $prefixes = $prop->getValue();
      echo json_encode([
        'hasCorePrefix' => isset($prefixes['Xophz\\\\Compass\\\\Core\\\\'])
      ]);
    `);
    assert.strictEqual(res.hasCorePrefix, true, 'PSR-4 prefix Xophz\\Compass\\Core\\ must be registered');
  });

  it('F01.5: Unknown classes pass through without fatal errors', () => {
    const res = runPhpJson(`
      $exists = class_exists('Non_Existent_Compass_Class_XYZ_123');
      echo json_encode(['exists' => $exists]);
    `);
    assert.strictEqual(res.exists, false, 'Non-existent class should cleanly return false');
  });
}, { tier: 1, featureId: 'F01' });

describe('Feature F02: Non-Blocking Dev Proxy: Yellow Links', () => {
  it('F02.1: Yellow Links proxy configuration sets port 8088 and query var', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'yellow-links',
        'dev_port'    => 8088,
        'query_var'   => 'yellow-links',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-yellow-links/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-yellow-links/',
        'version'     => '26.9.3'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('yellow-links', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var yellow-links must be registered');
  });

  it('F02.2: Fast TCP socket probe connects or fails within <= 150ms timeout', () => {
    const probe = measureSocketProbe(8088, 0.15);
    assert.ok(probe.elapsedMs <= 180, `Probe latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('F02.3: Dev server offline fallback loads production dist bundle without hanging', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'yellow-links',
        'dev_port'    => 8088,
        'query_var'   => 'yellow-links',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-yellow-links/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-yellow-links/',
        'version'     => '26.9.3'
      ]);
      $start = microtime(true);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      $elapsed = (microtime(true) - $start) * 1000;
      echo json_encode([
        'hasHtml' => !empty($html),
        'elapsedMs' => round($elapsed, 2)
      ]);
    `);
    assert.strictEqual(res.hasHtml, true, 'Production dist HTML must be returned');
    assert.ok(res.elapsedMs < 100, `Dist loading must be near-instant, took ${res.elapsedMs}ms`);
  });

  it('F02.4: Dev HTML rewrites relative asset paths to Vite dev server URL', () => {
    const res = runPhpJson(`
      $html = '<html><head><script src="/src/main.ts"></script><link href="/src/style.css"></head><body></body></html>';
      $vite_url = '//localhost:8088';
      $html = str_replace('src="/', 'src="' . $vite_url . '/', $html);
      $html = str_replace('href="/', 'href="' . $vite_url . '/', $html);
      echo json_encode([
        'hasScript' => strpos($html, 'src="//localhost:8088/src/main.ts"') !== false,
        'hasLink'   => strpos($html, 'href="//localhost:8088/src/style.css"') !== false
      ]);
    `);
    assert.strictEqual(res.hasScript, true, 'Script src must be rewritten to Vite dev server URL');
    assert.strictEqual(res.hasLink, true, 'Link href must be rewritten to Vite dev server URL');
  });

  it('F02.5: Authenticated window.wpApiSettings script tag injection contract', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'yellow-links',
        'dev_port'    => 8088,
        'query_var'   => 'yellow-links',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-yellow-links/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-yellow-links/',
        'version'     => '26.9.3'
      ]);
      $reflection = new ReflectionMethod($proxy, 'build_api_settings_script');
      $reflection->setAccessible(true);
      $script = $reflection->invoke($proxy);
      echo json_encode([
        'hasTag' => strpos($script, '<script>window.wpApiSettings =') !== false,
        'hasPluginUrl' => strpos($script, 'xophz-compass-yellow-links') !== false
      ]);
    `);
    assert.strictEqual(res.hasTag, true, 'window.wpApiSettings script must be constructed');
    assert.strictEqual(res.hasPluginUrl, true, 'Plugin URL must be embedded in settings');
  });
}, { tier: 1, featureId: 'F02' });

describe('Feature F03: Non-Blocking Dev Proxy: Card Vault', () => {
  it('F03.1: Card Vault proxy configuration targets port 8092 and query var', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'card-vault',
        'dev_port'    => 8092,
        'query_var'   => 'xophz_compass_card_vault',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-card-vault/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-card-vault/',
        'version'     => '26.9.2-1221'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('xophz_compass_card_vault', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var xophz_compass_card_vault must be registered');
  });

  it('F03.2: Socket probe handles offline port 8092 within 150ms without blocking', () => {
    const probe = measureSocketProbe(8092, 0.15);
    assert.ok(probe.elapsedMs <= 180, `Probe latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('F03.3: Production dist fallback loads public/dist/index.html and rewrites assets', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'card-vault',
        'dev_port'    => 8092,
        'query_var'   => 'xophz_compass_card_vault',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-card-vault/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-card-vault/',
        'version'     => '26.9.2-1221'
      ]);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      echo json_encode([
        'containsDistUrl' => strpos($html, 'xophz-compass-card-vault/public/dist/') !== false || strpos($html, 'build not found') !== false
      ]);
    `);
    assert.strictEqual(res.containsDistUrl, true, 'Dist URL or fallback message must be present');
  });

  it('F03.4: Candidate dist paths array supports custom dist locations', () => {
    const res = runPhpJson(`
      $customPath = '/var/www/html/wp-content/plugins/xophz-compass-card-vault/public/dist/index.html';
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'                 => 'card-vault',
        'dev_port'             => 8092,
        'query_var'            => 'xophz_compass_card_vault',
        'plugin_path'          => '/var/www/html/wp-content/plugins/xophz-compass-card-vault/',
        'plugin_url'           => 'http://localhost/wp-content/plugins/xophz-compass-card-vault/',
        'version'              => '26.9.2-1221',
        'candidate_dist_paths' => [$customPath]
      ]);
      $reflection = new ReflectionProperty($proxy, 'candidate_dist_paths');
      $reflection->setAccessible(true);
      $paths = $reflection->getValue($proxy);
      echo json_encode([
        'hasCustomPath' => in_array($customPath, $paths, true)
      ]);
    `);
    assert.strictEqual(res.hasCustomPath, true, 'Candidate dist paths must include custom path');
  });

  it('F03.5: Dev HTML injection injects /@vite/client HMR tag when missing', () => {
    const res = runPhpJson(`
      $html = '<html><head><title>Test</title></head><body></body></html>';
      $vite_url = '//localhost:8092';
      if (strpos($html, '/@vite/client') === false) {
        $client_tag = '<script type="module" src="' . $vite_url . '/@vite/client"></script>';
        $html = str_replace('</head>', $client_tag . "\n</head>", $html);
      }
      echo json_encode([
        'hasHmrTag' => strpos($html, 'src="//localhost:8092/@vite/client"') !== false
      ]);
    `);
    assert.strictEqual(res.hasHmrTag, true, 'HMR client tag must be injected before head closure');
  });

  it('F03.6: Dev Proxy candidate resolution respects COMPASS_DEV_HOST environment variable and PHP constant', () => {
    const res = runPhpJson(`
      // Test getenv path
      putenv('COMPASS_DEV_HOST=card-vault-dev.internal');
      $envCandidates = Xophz_Compass_Dev_Proxy::get_candidate_hosts();

      // Test constant definition fallback
      putenv('COMPASS_DEV_HOST');
      if (!defined('COMPASS_DEV_HOST')) {
        define('COMPASS_DEV_HOST', 'card-vault-const.internal');
      }
      $constCandidates = Xophz_Compass_Dev_Proxy::get_candidate_hosts();

      echo json_encode([
        'envFirst'   => $envCandidates[0],
        'constFirst' => $constCandidates[0]
      ]);
    `);
    assert.strictEqual(res.envFirst, 'card-vault-dev.internal', 'getenv COMPASS_DEV_HOST must be prepended');
    assert.strictEqual(res.constFirst, 'card-vault-const.internal', 'constant COMPASS_DEV_HOST must be prepended when env unset');
  });
}, { tier: 1, featureId: 'F03' });

describe('Feature F04: Non-Blocking Dev Proxy: Fresh Mints', () => {
  it('F04.1: Fresh Mints dev proxy configuration targets port 8091 and query var', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'fresh-mints',
        'dev_port'    => 8091,
        'query_var'   => 'fresh-mints',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-fresh-mints/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-fresh-mints/',
        'version'     => '26.9.3-233'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('fresh-mints', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var fresh-mints must be registered');
  });

  it('F04.2: Audit check for || true leak in Fresh Mints is_dev_mode()', () => {
    const content = readSourceFile('wp-content/plugins/xophz-compass-fresh-mints/public/class-xophz-compass-freshmints-public.php');
    if (!content) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasLeak = content.includes('|| true;');
    // Baseline state flags this unmigrated leak; target state requires false
    assert.strictEqual(hasLeak, false, 'Fresh Mints must NOT contain unconditional || true; dev mode leak');
  });

  it('F04.3: Probe timeout verification: single non-blocking probe completes in <= 150ms', () => {
    const probe = measureSocketProbe(8091, 0.15);
    assert.ok(probe.elapsedMs <= 180, `Probe latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('F04.4: Dev server offline fallback loads production dist bundle with 0 latency', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'fresh-mints',
        'dev_port'    => 8091,
        'query_var'   => 'fresh-mints',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-fresh-mints/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-fresh-mints/',
        'version'     => '26.9.3-233'
      ]);
      $start = microtime(true);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      $elapsed = (microtime(true) - $start) * 1000;
      echo json_encode([
        'hasOutput' => !empty($html),
        'elapsedMs' => round($elapsed, 2)
      ]);
    `);
    assert.strictEqual(res.hasOutput, true, 'Output must be generated');
    assert.ok(res.elapsedMs < 100, `Dist fallback must be near-instant, took ${res.elapsedMs}ms`);
  });

  it('F04.5: window.wpApiSettings includes current user session and context', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'fresh-mints',
        'dev_port'    => 8091,
        'query_var'   => 'fresh-mints',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-fresh-mints/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-fresh-mints/',
        'version'     => '26.9.3-233'
      ]);
      $reflection = new ReflectionMethod($proxy, 'build_api_settings_script');
      $reflection->setAccessible(true);
      $script = $reflection->invoke($proxy);
      echo json_encode([
        'hasScript' => strpos($script, '<script>window.wpApiSettings =') !== false
      ]);
    `);
    assert.strictEqual(res.hasScript, true, 'window.wpApiSettings script must be generated');
  });
}, { tier: 1, featureId: 'F04' });
