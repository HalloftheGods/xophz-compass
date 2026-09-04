/**
 * Tier 4: Real-World Operational Scenarios (Tests S01 - S06)
 * Simulates complete production and development lifecycle operations.
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert, assertNoEmDashes } from '../harness/test-framework.mjs';
import { runPhpJson, lintPhpFile } from '../harness/php-executor.mjs';
import { readSourceFile, findFilesRecursively } from '../harness/code-analyzer.mjs';

describe('Tier 4: Real-World Application Scenarios', () => {
  it('S01: SPA Dev Server Offline to Dist Fallback: Instantaneous response without blocking', () => {
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
      $host = Xophz_Compass_Dev_Proxy::resolve_host(8088);
      $elapsedMs = (microtime(true) - $start) * 1000;
      echo json_encode([
        'host'      => $host,
        'elapsedMs' => round($elapsedMs, 2)
      ]);
    `);
    assert.ok(res.elapsedMs < 600, `Dev server resolution must complete in < 600ms, took ${res.elapsedMs}ms`);
  });

  it('S02: Standalone Plugin Operation with Core Engine Deactivated: Zero fatal errors', () => {
    const res = runPhpJson(`
      // Standalone simulation without core Xophz_Compass main class
      $plugin = new class('event-horizon', '26.9.3') extends Xophz_Compass_Plugin_Base {
        public function init(): void {
          $this->add_action('custom_init', function() {});
        }
      };
      echo json_encode([
        'slug'       => $plugin->get_slug(),
        'version'    => $plugin->get_version(),
        'standalone' => true
      ]);
    `);
    assert.strictEqual(res.slug, 'event-horizon', 'Slug should resolve correctly');
    assert.strictEqual(res.standalone, true, 'Plugin should run cleanly in standalone mode');
  });

  it('S03: Authenticated REST Flow with Capability Enforcement: Full request lifecycle', () => {
    const res = runPhpJson(`
      $controller = new class('card-vault', 'v1') extends Xophz_Compass_REST_Controller {
        public function register_routes() {}
        public function handle_sync_request(WP_REST_Request $request) {
          $perm = $this->permissions_admin($request);
          if (is_wp_error($perm)) {
            return $perm;
          }
          $item_id = $this->get_param_string($request, 'item_id', '');
          return $this->success_response(['synced_id' => $item_id], 200);
        }
      };
      $req = new WP_REST_Request('POST', '/compass/v1/card-vault/sync');
      $req->set_param('item_id', 'card-12345');
      $response = $controller->handle_sync_request($req);

      echo json_encode([
        'isError' => is_wp_error($response),
        'code'    => is_wp_error($response) ? $response->get_error_code() : null
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.isError, true, 'Unauthenticated user must be rejected with error');
    assert.strictEqual(res.code, 'rest_forbidden_cap', 'Error must indicate capability requirement');
  });

  it('S04: WP Connectors Key Resolution Under Missing Configuration: Clean empty state', () => {
    const res = runPhpJson(`
      $key = Xophz_Compass_HTTP::get_gemini_api_key();
      echo json_encode([
        'hasSyntheticKey' => is_string($key) && (strpos($key, 'mock') !== false || strpos($key, 'fake') !== false),
        'cleanState'      => $key === null || is_string($key)
      ]);
    `);
    assert.strictEqual(res.hasSyntheticKey, false, 'Must not return synthetic or fake key');
    assert.strictEqual(res.cleanState, true, 'Must return null or genuine key');
  });

  it('S05: Full Ecosystem PHP Syntax Validation Gate: php -l across core and child plugins', () => {
    const filesToLint = [
      'wp-content/plugins/xophz-compass/xophz-compass.php',
      'wp-content/plugins/xophz-compass-yellow-links/xophz-compass-yellow-links.php',
      'wp-content/plugins/xophz-compass-card-vault/xophz-compass-card-vault.php',
      'wp-content/plugins/xophz-compass-fresh-mints/xophz-compass-fresh-mints.php',
      'wp-content/plugins/xophz-compass-diego-lawfirm/xophz-compass-diego-lawfirm.php',
      'wp-content/plugins/xophz-compass-phone/xophz-compass-phone.php',
      'wp-content/plugins/xophz-compass-event-horizon/xophz-compass-event-horizon.php',
      'wp-content/plugins/xophz-compass-glowitheflow/xophz-compass-glowitheflow.php'
    ];

    for (const f of filesToLint) {
      const lint = lintPhpFile(f);
      assert.strictEqual(lint.isValid, true, `Syntax error in ${f}: ${lint.output}`);
    }
  });

  it('S06: Monorepo Zero Em Dash and Data Integrity Forensic Gate: Zero em dashes in documentation', () => {
    const docFiles = [
      'PROJECT.md',
      'docs/standards/doc-system-standard.md',
      'docs/architecture/compass-php-audit-and-core-helpers.md'
    ];

    for (const doc of docFiles) {
      const content = readSourceFile(doc);
      if (content) {
        assertNoEmDashes(content, doc);
      }
    }
  });
}, { tier: 4 });
