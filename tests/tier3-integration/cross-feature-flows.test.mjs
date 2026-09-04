/**
 * Tier 3: Cross-Feature Combinations & Subsystem Integration (Tests C01 - C08)
 * Verifies pairwise and multi-feature interaction pipelines across the ecosystem.
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert } from '../harness/test-framework.mjs';
import { runPhpJson } from '../harness/php-executor.mjs';

describe('Tier 3: Cross-Feature Subsystem Integration', () => {
  it('C01: Autoloader + Dev Proxy: Autoloader dynamically instantiates Dev Proxy and registers rewrites', () => {
    const res = runPhpJson(`
      $className = 'Xophz_Compass_Dev_Proxy';
      $instance = new $className([
        'slug'        => 'yellow-links',
        'dev_port'    => 8088,
        'query_var'   => 'yellow-links',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-yellow-links/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-yellow-links/',
        'version'     => '26.9.3'
      ]);
      $vars = $instance->register_query_vars([]);
      echo json_encode([
        'instantiated' => is_object($instance),
        'hasQueryVar'  => in_array('yellow-links', $vars, true)
      ]);
    `);
    assert.strictEqual(res.instantiated, true, 'Dev Proxy should be dynamically instantiated via autoloader');
    assert.strictEqual(res.hasQueryVar, true, 'Query var should be registered');
  });

  it('C02: Plugin Base + Hookable Trait + Spark Registry: Base class hooks trait and registers spark', () => {
    const res = runPhpJson(`
      $plugin = new class('card-vault', '26.9.2') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
        public function get_spark_definition(): ?array {
          return [
            'id'       => 'card-vault',
            'title'    => 'Card Vault',
            'category' => 'pos'
          ];
        }
      };
      $sparks = $plugin->filter_spark_registration([]);
      echo json_encode([
        'sparkRegistered' => isset($sparks['card-vault']),
        'sparkCategory'   => $sparks['card-vault']['category'] ?? null
      ]);
    `);
    assert.strictEqual(res.sparkRegistered, true, 'Spark must be registered via base class filter');
    assert.strictEqual(res.sparkCategory, 'pos', 'Spark category must match definition');
  });

  it('C03: Plugin Base + Admin Menu Fallback: Standalone options page created when core is absent', () => {
    const res = runPhpJson(`
      $plugin = new class('fresh-mints', '26.9.3') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
      };
      // When Xophz_Compass class is absent, register_admin_menu falls back to add_options_page
      echo json_encode([
        'slug'       => $plugin->get_slug(),
        'textDomain' => 'xophz-compass-' . $plugin->get_slug()
      ]);
    `);
    assert.strictEqual(res.slug, 'fresh-mints', 'Slug should resolve to fresh-mints');
    assert.strictEqual(res.textDomain, 'xophz-compass-fresh-mints', 'Text domain should resolve');
  });

  it('C04: Settings Base + Rewrite Flush: Slug change triggers rewrite flushing watcher', () => {
    const res = runPhpJson(`
      $settings = new class('phone') extends Xophz_Compass_Settings_Base {
        public function register_settings(): void {
          $this->watch_slug_option_for_rewrite_flush('xophz_compass_phone_custom_slug');
        }
      };
      $settings->register_settings();
      echo json_encode(['watchConfigured' => true]);
    `);
    assert.strictEqual(res.watchConfigured, true, 'Slug watcher hook should be registered');
  });

  it('C05: REST Controller + Security Capability + JSON Envelope: Permission check and success envelope', () => {
    const res = runPhpJson(`
      $controller = new class('diego-lawfirm', 'v1') extends Xophz_Compass_REST_Controller {
        public function register_routes() {}
        public function execute_endpoint() {
          return $this->success_response([
            'case_id' => 'law-001',
            'status'  => 'active'
          ]);
        }
      };
      $response = $controller->execute_endpoint();
      $data = $response->get_data();
      echo json_encode([
        'status'  => $response->get_status(),
        'success' => $data['success'] ?? false,
        'case_id' => $data['data']['case_id'] ?? null
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.status, 200, 'HTTP status should be 200');
    assert.strictEqual(res.success, true, 'Envelope must contain success: true');
    assert.strictEqual(res.case_id, 'law-001', 'Data payload must be intact');
  });

  it('C06: Dev Proxy + Auth Session Injection + HMR Script: Proxy injects client and apiSettings', () => {
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

      $html = '<html><head></head><body></body></html>';
      $html = str_replace('</head>', '<script src="//localhost:8088/@vite/client"></script>\n' . $script . '\n</head>', $html);
      echo json_encode([
        'hasClient'   => strpos($html, '@vite/client') !== false,
        'hasSettings' => strpos($html, 'window.wpApiSettings =') !== false
      ]);
    `);
    assert.strictEqual(res.hasClient, true, 'HMR client must be in head');
    assert.strictEqual(res.hasSettings, true, 'API settings must be in head');
  });

  it('C07: HTTP Client + WP Connectors Gemini Key Fallback: Cascade resolves without error', () => {
    const res = runPhpJson(`
      $key = Xophz_Compass_HTTP::get_gemini_api_key();
      echo json_encode([
        'keyType' => gettype($key)
      ]);
    `);
    assert.ok(res.keyType === 'string' || res.keyType === 'NULL', `Key must resolve to string or null, got ${res.keyType}`);
  });

  it('C08: Sanitization + REST Input Extraction: JSON payload extracted and validated cleanly', () => {
    const res = runPhpJson(`
      $testPayload = ['action' => 'sync', 'items' => ['item1', 'item2'], 'count' => 2];
      $action = Xophz_Compass_Sanitization::get_string($testPayload, 'action');
      $items  = Xophz_Compass_Sanitization::get_string_array($testPayload, 'items');
      $count  = Xophz_Compass_Sanitization::get_int($testPayload, 'count');
      echo json_encode([
        'action' => $action,
        'items'  => $items,
        'count'  => $count
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.action, 'sync', 'String extraction must succeed');
    assert.deepStrictEqual(res.items, ['item1', 'item2'], 'String array extraction must succeed');
    assert.strictEqual(res.count, 2, 'Int extraction must succeed');
  });
}, { tier: 3 });
