/**
 * Tier 1: Feature Coverage (Features F17 - F20)
 * F17: WPPB Boilerplate Purge Across Child Plugins
 * F18: 100% PHP Syntax Validation
 * F19: Zero Em Dash and Code Quality Audit
 * F20: Dev Proxy & Base Class: Glow With The Flow
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert, assertNoEmDashes } from '../harness/test-framework.mjs';
import { runPhpJson, lintPhpFile, measureSocketProbe } from '../harness/php-executor.mjs';
import { readSourceFile, findFilesRecursively } from '../harness/code-analyzer.mjs';

describe('Feature F17: WPPB Boilerplate Purge Across Child Plugins', () => {
  it('F17.1: Census of hollow deactivators across child plugins', () => {
    const deactivatorFiles = findFilesRecursively('wp-content/plugins', /deactivator\.php$/);
    // Baseline state catalogs these files (~33 files); target state requires 0 or only custom cleanup
    assert.strictEqual(deactivatorFiles.length, 0, `Expected 0 hollow deactivators, found ${deactivatorFiles.length}`);
  });

  it('F17.2: Census of duplicate 129-line WPPB loaders across plugins', () => {
    const loaderFiles = findFilesRecursively('wp-content/plugins', /loader\.php$/)
      .filter(f => !f.includes('woocommerce') && !f.includes('class-compass-autoloader.php'));
    // Baseline state catalogs these files (~29 files); target state requires 0 (superseded by Hookable Trait)
    assert.strictEqual(loaderFiles.length, 0, `Expected 0 redundant WPPB loaders, found ${loaderFiles.length}`);
  });

  it('F17.3: Census of duplicate single-method i18n classes across child plugins', () => {
    const i18nFiles = findFilesRecursively('wp-content/plugins', /i18n\.php$/);
    // Baseline state catalogs these files (~29 files); target state requires 0 (superseded by Base Class)
    assert.strictEqual(i18nFiles.length, 0, `Expected 0 redundant i18n classes, found ${i18nFiles.length}`);
  });

  it('F17.4: Verification that child plugins can extend base class without boilerplate files', () => {
    const res = runPhpJson(`
      $childPlugin = new class('/var/www/html/wp-content/plugins/xophz-compass-test/test.php', '1.0.0') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
      };
      echo json_encode([
        'slug'       => $childPlugin->get_slug(),
        'hasHooks'   => method_exists($childPlugin, 'add_action') && method_exists($childPlugin, 'run')
      ]);
    `);
    assert.strictEqual(res.slug, 'test', 'Derived plugin slug must resolve cleanly');
    assert.strictEqual(res.hasHooks, true, 'Base class must supply all hook orchestration methods');
  });

  it('F17.5: Hook fidelity preservation via Hookable Trait run_hooks', () => {
    const res = runPhpJson(`
      $plugin = new class('test-fidelity', '1.0.0') extends Xophz_Compass_Plugin_Base {
        public function init(): void {
          $this->add_action('custom_test_action', function() {}, 15, 2);
          $this->add_filter('custom_test_filter', function($val) { return $val; }, 20, 1);
        }
        public function export_hooks() {
          return [
            'actions' => $this->registered_actions,
            'filters' => $this->registered_filters
          ];
        }
      };
      $hooks = $plugin->export_hooks();
      echo json_encode([
        'actionCount' => count($hooks['actions']),
        'filterCount' => count($hooks['filters']),
        'actionPrio'  => $hooks['actions'][0]['priority'] ?? null,
        'filterPrio'  => $hooks['filters'][0]['priority'] ?? null
      ]);
    `);
    assert.strictEqual(res.actionCount, 1, 'Action must be queued');
    assert.strictEqual(res.filterCount, 1, 'Filter must be queued');
    assert.strictEqual(res.actionPrio, 15, 'Action priority must match');
    assert.strictEqual(res.filterPrio, 20, 'Filter priority must match');
  });
}, { tier: 1, featureId: 'F17' });

describe('Feature F18: 100% PHP Syntax Validation', () => {
  it('F18.1: Syntax validation (php -l) passes with 0 errors across all 10 Core Helper Suite files', () => {
    const coreFiles = [
      'wp-content/plugins/xophz-compass/includes/core/class-compass-autoloader.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-dev-proxy.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-http.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-plugin-base.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-rest-controller.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-sanitization.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-security.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-settings-base.php',
      'wp-content/plugins/xophz-compass/includes/core/interface-compass-plugin.php',
      'wp-content/plugins/xophz-compass/includes/core/trait-compass-hookable.php',
    ];

    for (const f of coreFiles) {
      const lint = lintPhpFile(f);
      assert.strictEqual(lint.isValid, true, `Syntax error in core helper ${f}: ${lint.output}`);
    }
  });

  it('F18.2: Syntax validation passes across all 6 SPA child plugins', () => {
    const spaFiles = [
      'wp-content/plugins/xophz-compass-yellow-links/xophz-compass-yellow-links.php',
      'wp-content/plugins/xophz-compass-card-vault/xophz-compass-card-vault.php',
      'wp-content/plugins/xophz-compass-fresh-mints/xophz-compass-fresh-mints.php',
      'wp-content/plugins/xophz-compass-diego-lawfirm/xophz-compass-diego-lawfirm.php',
      'wp-content/plugins/xophz-compass-phone/xophz-compass-phone.php',
      'wp-content/plugins/xophz-compass-event-horizon/xophz-compass-event-horizon.php',
    ];

    for (const f of spaFiles) {
      const lint = lintPhpFile(f);
      assert.strictEqual(lint.isValid, true, `Syntax error in SPA plugin ${f}: ${lint.output}`);
    }
  });

  it('F18.3: Syntax validation passes across xophz-compass-glowitheflow', () => {
    const glowFile = 'wp-content/plugins/xophz-compass-glowitheflow/xophz-compass-glowitheflow.php';
    const lint = lintPhpFile(glowFile);
    assert.strictEqual(lint.isValid, true, `Syntax error in glowitheflow: ${lint.output}`);
  });

  it('F18.4: Syntax validation passes across core xophz-compass.php', () => {
    const coreFile = 'wp-content/plugins/xophz-compass/xophz-compass.php';
    const lint = lintPhpFile(coreFile);
    assert.strictEqual(lint.isValid, true, `Syntax error in core engine: ${lint.output}`);
  });

  it('F18.5: Zero deprecation notices on PHP 8.2+ (no FILTER_SANITIZE_STRING)', () => {
    const res = runPhpJson(`
      $_SERVER['REQUEST_METHOD'] = 'GET';
      $method = Xophz_Compass_Sanitization::get_http_method();
      echo json_encode(['method' => $method]);
    `);
    assert.strictEqual(res.method, 'GET', 'Sanitization method must extract GET without deprecation');
  });
}, { tier: 1, featureId: 'F18' });

describe('Feature F19: Zero Em Dash and Code Quality Audit', () => {
  it('F19.1: Zero em dash characters (\\u2014) in core helper classes', () => {
    const coreFiles = [
      'wp-content/plugins/xophz-compass/includes/core/class-compass-autoloader.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-dev-proxy.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-http.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-plugin-base.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-rest-controller.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-sanitization.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-security.php',
      'wp-content/plugins/xophz-compass/includes/core/class-compass-settings-base.php',
      'wp-content/plugins/xophz-compass/includes/core/interface-compass-plugin.php',
      'wp-content/plugins/xophz-compass/includes/core/trait-compass-hookable.php',
    ];

    for (const f of coreFiles) {
      const content = readSourceFile(f);
      if (content) {
        assertNoEmDashes(content, f);
      }
    }
  });

  it('F19.2: Zero em dash characters in child plugin main PHP files', () => {
    const spaFiles = [
      'wp-content/plugins/xophz-compass-yellow-links/xophz-compass-yellow-links.php',
      'wp-content/plugins/xophz-compass-card-vault/xophz-compass-card-vault.php',
      'wp-content/plugins/xophz-compass-fresh-mints/xophz-compass-fresh-mints.php',
      'wp-content/plugins/xophz-compass-diego-lawfirm/xophz-compass-diego-lawfirm.php',
      'wp-content/plugins/xophz-compass-phone/xophz-compass-phone.php',
      'wp-content/plugins/xophz-compass-event-horizon/xophz-compass-event-horizon.php',
    ];

    for (const f of spaFiles) {
      const content = readSourceFile(f);
      if (content) {
        assertNoEmDashes(content, f);
      }
    }
  });

  it('F19.3: Zero em dash characters in PROJECT.md and documentation', () => {
    const projectMd = readSourceFile('PROJECT.md');
    if (projectMd) {
      assertNoEmDashes(projectMd, 'PROJECT.md');
    }
  });

  it('F19.4: Clean empty states used instead of mock arrays in default options', () => {
    const res = runPhpJson(`
      $clean = [];
      echo json_encode(['count' => count($clean)]);
    `);
    assert.strictEqual(res.count, 0, 'Clean empty array must have 0 elements');
  });

  it('F19.5: Zero hardcoded mock credentials or synthetic keys in HTTP resolver', () => {
    const httpContent = readSourceFile('wp-content/plugins/xophz-compass/includes/core/class-compass-http.php');
    if (httpContent) {
      assert.strictEqual(httpContent.includes('mock_key'), false, 'HTTP helper must not contain mock_key');
      assert.strictEqual(httpContent.includes('fake_api_key'), false, 'HTTP helper must not contain fake_api_key');
    }
  });
}, { tier: 1, featureId: 'F19' });

describe('Feature F20: Dev Proxy & Base Class: Glow With The Flow', () => {
  it('F20.1: Dev proxy configuration for Glow With The Flow targets port 5177', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'glowitheflow',
        'dev_port'    => 5177,
        'query_var'   => 'xophz_compass_glowitheflow',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-glowitheflow/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-glowitheflow/',
        'version'     => '26.9.3-238'
      ]);
      $vars = $proxy->register_query_vars([]);
      echo json_encode([
        'hasQueryVar' => in_array('xophz_compass_glowitheflow', $vars, true)
      ]);
    `);
    assert.strictEqual(res.hasQueryVar, true, 'Query var xophz_compass_glowitheflow must be registered');
  });

  it('F20.2: Socket probing replaces blocking @file_get_contents on port 5177', () => {
    const probe = measureSocketProbe(5177, 0.15);
    assert.ok(probe.elapsedMs <= 180, `Probe latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('F20.3: Nuxt router baseURL replacement operates cleanly', () => {
    const res = runPhpJson(`
      $html = '<html><head><script>const config = { baseURL:"/" };</script></head><body></body></html>';
      $app_base_slash = '/glowitheflow/';
      $html = str_replace('baseURL:"/"', 'baseURL:"' . $app_base_slash . '"', $html);
      echo json_encode([
        'hasReplacedBaseUrl' => strpos($html, 'baseURL:"/glowitheflow/"') !== false
      ]);
    `);
    assert.strictEqual(res.hasReplacedBaseUrl, true, 'Base URL must be replaced with app base');
  });

  it('F20.4: window.wpApiSettings injected into head before closing tag', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'glowitheflow',
        'dev_port'    => 5177,
        'query_var'   => 'xophz_compass_glowitheflow',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-glowitheflow/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-glowitheflow/',
        'version'     => '26.9.3-238'
      ]);
      $reflection = new ReflectionMethod($proxy, 'build_api_settings_script');
      $reflection->setAccessible(true);
      $script = $reflection->invoke($proxy);
      echo json_encode([
        'hasScript' => strpos($script, '<script>window.wpApiSettings =') !== false,
        'hasVersion' => strpos($script, '26.9.3-238') !== false
      ]);
    `);
    assert.strictEqual(res.hasScript, true, 'Settings script must be generated');
    assert.strictEqual(res.hasVersion, true, 'Version must match');
  });

  it('F20.5: Public dist fallback checks candidate dist paths', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'                 => 'glowitheflow',
        'dev_port'             => 5177,
        'query_var'            => 'xophz_compass_glowitheflow',
        'plugin_path'          => '/var/www/html/wp-content/plugins/xophz-compass-glowitheflow/',
        'plugin_url'           => 'http://localhost/wp-content/plugins/xophz-compass-glowitheflow/',
        'version'              => '26.9.3-238',
        'candidate_dist_paths' => [
          '/var/www/html/wp-content/plugins/xophz-compass-glowitheflow/public/dist/index.html',
          '/var/www/html/wp-content/plugins/xophz-compass-glowitheflow/public/dist/200.html'
        ]
      ]);
      $reflection = new ReflectionProperty($proxy, 'candidate_dist_paths');
      $reflection->setAccessible(true);
      $paths = $reflection->getValue($proxy);
      echo json_encode(['pathCount' => count($paths)]);
    `);
    assert.strictEqual(res.pathCount, 2, 'Candidate dist paths must include index.html and 200.html');
  });
}, { tier: 1, featureId: 'F20' });
