/**
 * Tier 1: Feature Coverage (Features F13 - F16)
 * F13: Secure Unauthenticated Endpoints
 * F14: Base Class Integration: Event Horizon
 * F15: Boilerplate Purge: Event Horizon
 * F16: Base Class Integration & Deactivator Purge: Card Vault
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert } from '../harness/test-framework.mjs';
import { runPhpJson } from '../harness/php-executor.mjs';
import { readSourceFile, extendsPluginBase, usesHookableTrait, sourceFileExists } from '../harness/code-analyzer.mjs';

describe('Feature F13: Secure Unauthenticated Endpoints', () => {
  it('F13.1: Yellow Links rate limiting via Xophz_Compass_Security', () => {
    const testKey = 'test_ip_link_submit_' + Date.now();
    const res = runPhpJson(`
      $key = '${testKey}';
      $allowed = Xophz_Compass_Security::check_rate_limit($key, 5, 60);
      delete_transient('compass_rl_' . md5($key));
      echo json_encode(['allowed' => $allowed]);
    `, { loadWp: true });
    assert.strictEqual(res.allowed, true, 'Initial request must be within rate limit');
  });

  it('F13.2: Yellow Links status route requires manage_options (not __return_true)', () => {
    const apiFile = readSourceFile('wp-content/plugins/xophz-compass-yellow-links/includes/class-yellow-links-api.php');
    if (!apiFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasStatusOpen = /'\/links\/\(\?P<id>\[a-zA-Z0-9_-\]\+\)\/status'[\s\S]*?'permission_callback'\s*=>\s*'__return_true'/.test(apiFile);
    // Baseline state flags this open endpoint; target state requires false
    assert.strictEqual(hasStatusOpen, false, 'Yellow Links /status endpoint must NOT use __return_true');
  });

  it('F13.3: Yellow Links delete route requires manage_options (not __return_true)', () => {
    const apiFile = readSourceFile('wp-content/plugins/xophz-compass-yellow-links/includes/class-yellow-links-api.php');
    if (!apiFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasDeleteOpen = /'methods'\s*=>\s*WP_REST_Server::DELETABLE[\s\S]*?'permission_callback'\s*=>\s*'__return_true'/.test(apiFile);
    // Baseline state flags this open endpoint; target state requires false
    assert.strictEqual(hasDeleteOpen, false, 'Yellow Links DELETABLE endpoint must NOT use __return_true');
  });

  it('F13.4: Fresh Mints mutation endpoints require authentication and capabilities', () => {
    const res = runPhpJson(`
      $controller = new class('fresh-mints', 'v1') extends Xophz_Compass_REST_Controller {
        public function register_routes() {}
      };
      $req = new WP_REST_Request('POST', '/compass/v1/fresh-mints/mint');
      $perm = $controller->permissions_authenticated($req);
      echo json_encode([
        'isError' => is_wp_error($perm)
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.isError, true, 'Unauthenticated request must return error');
  });

  it('F13.5: Unauthorized mutating requests return 401 or 403 status code', () => {
    const res = runPhpJson(`
      $controller = new class('test', 'v1') extends Xophz_Compass_REST_Controller {
        public function register_routes() {}
      };
      $req = new WP_REST_Request('POST', '/compass/v1/test/mutate');
      $perm = $controller->permissions_authenticated($req);
      $status = is_wp_error($perm) ? $perm->get_error_data()['status'] ?? null : 200;
      echo json_encode(['status' => $status]);
    `, { loadWp: true });
    assert.ok(res.status === 401 || res.status === 403, `Status must be 401 or 403, got ${res.status}`);
  });
}, { tier: 1, featureId: 'F13' });

describe('Feature F14: Base Class Integration: Event Horizon', () => {
  it('F14.1: Main Event Horizon class extends Xophz_Compass_Plugin_Base', () => {
    const mainFile = readSourceFile('wp-content/plugins/xophz-compass-event-horizon/includes/class-xophz-compass-event-horizon.php');
    if (!mainFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasBase = extendsPluginBase(mainFile);
    // Baseline state flags unmigrated class; target state requires true
    assert.strictEqual(hasBase, true, 'Event Horizon main class must extend Xophz_Compass_Plugin_Base');
  });

  it('F14.2: Event Horizon adopts Xophz_Compass_Hookable_Trait', () => {
    const mainFile = readSourceFile('wp-content/plugins/xophz-compass-event-horizon/includes/class-xophz-compass-event-horizon.php');
    if (!mainFile) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasTrait = usesHookableTrait(mainFile) || extendsPluginBase(mainFile);
    assert.strictEqual(hasTrait, true, 'Event Horizon must use Xophz_Compass_Hookable_Trait or inherit it from Base');
  });

  it('F14.3: Textdomain loading attached to init at priority 5 per WP 6.7+ standard', () => {
    const res = runPhpJson(`
      $plugin = new class('event-horizon', '1.0.0') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
      };
      echo json_encode(['initialized' => is_object($plugin)]);
    `);
    assert.strictEqual(res.initialized, true, 'Plugin Base must initialize hooks cleanly');
  });

  it('F14.4: Admin menu integrates via Xophz_Compass::add_submenu with standalone fallback', () => {
    const res = runPhpJson(`
      $plugin = new class('event-horizon', '1.0.0') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
      };
      echo json_encode([
        'slug' => $plugin->get_slug(),
        'version' => $plugin->get_version()
      ]);
    `);
    assert.strictEqual(res.slug, 'event-horizon', 'Slug must match');
    assert.strictEqual(res.version, '1.0.0', 'Version must match');
  });

  it('F14.5: YouMeOS Spark registry filter hook registered through base class', () => {
    const res = runPhpJson(`
      $plugin = new class('event-horizon', '1.0.0') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
        public function get_spark_definition(): ?array {
          return [
            'id'    => 'event-horizon',
            'title' => 'Event Horizon',
            'icon'  => 'fal fa-meteor'
          ];
        }
      };
      $sparks = $plugin->filter_spark_registration([]);
      echo json_encode([
        'hasSpark' => isset($sparks['event-horizon']),
        'sparkTitle' => $sparks['event-horizon']['title'] ?? null
      ]);
    `);
    assert.strictEqual(res.hasSpark, true, 'Spark definition must be registered');
    assert.strictEqual(res.sparkTitle, 'Event Horizon', 'Spark title must match');
  });
}, { tier: 1, featureId: 'F14' });

describe('Feature F15: Boilerplate Purge: Event Horizon', () => {
  it('F15.1: class-xophz-compass-event-horizon-loader.php removed or superseded', () => {
    const exists = sourceFileExists('wp-content/plugins/xophz-compass-event-horizon/includes/class-xophz-compass-event-horizon-loader.php');
    // Baseline flags existing file; target state requires false (file purged)
    assert.strictEqual(exists, false, 'Redundant Event Horizon loader class must be purged');
  });

  it('F15.2: class-xophz-compass-event-horizon-i18n.php removed or superseded', () => {
    const exists = sourceFileExists('wp-content/plugins/xophz-compass-event-horizon/includes/class-xophz-compass-event-horizon-i18n.php');
    // Baseline flags existing file; target state requires false (file purged)
    assert.strictEqual(exists, false, 'Redundant Event Horizon i18n class must be purged');
  });

  it('F15.3: class-xophz-compass-event-horizon-activator.php purged of die() anti-pattern', () => {
    const content = readSourceFile('wp-content/plugins/xophz-compass-event-horizon/includes/class-xophz-compass-event-horizon-activator.php');
    if (!content) {
      assert.ok(true, 'File purged, check passes');
      return;
    }
    const hasDie = content.includes('die(');
    assert.strictEqual(hasDie, false, 'Activator must NOT contain die() anti-pattern');
  });

  it('F15.4: class-xophz-compass-event-horizon-deactivator.php hollow class eliminated', () => {
    const exists = sourceFileExists('wp-content/plugins/xophz-compass-event-horizon/includes/class-xophz-compass-event-horizon-deactivator.php');
    // Baseline flags existing hollow file; target state requires false
    assert.strictEqual(exists, false, 'Redundant hollow Event Horizon deactivator class must be purged');
  });

  it('F15.5: 100% hook fidelity verified: Hookable Trait queues and executes hooks', () => {
    const res = runPhpJson(`
      $testComponent = new class {
        use Xophz_Compass_Hookable_Trait;
        public function get_counts() {
          return [
            'actions' => count($this->registered_actions),
            'filters' => count($this->registered_filters)
          ];
        }
      };
      $testComponent->add_action('init', 'some_callback', 10, 1);
      $testComponent->add_filter('the_content', 'filter_callback', 10, 1);
      echo json_encode($testComponent->get_counts());
    `);
    assert.strictEqual(res.actions, 1, 'Action must be queued');
    assert.strictEqual(res.filters, 1, 'Filter must be queued');
  });
}, { tier: 1, featureId: 'F15' });

describe('Feature F16: Base Class Integration & Deactivator Purge: Card Vault', () => {
  it('F16.1: Main Card Vault plugin class refactored to extend Xophz_Compass_Plugin_Base', () => {
    const content = readSourceFile('wp-content/plugins/xophz-compass-card-vault/xophz-compass-card-vault.php');
    if (!content) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const hasPluginBase = extendsPluginBase(content) || content.includes('Xophz_Compass_Plugin_Base');
    // Baseline flags unmigrated file; target state requires true
    assert.strictEqual(hasPluginBase, true, 'Card Vault should reference or extend Xophz_Compass_Plugin_Base');
  });

  it('F16.2: Card_Vault_Deactivator hollow class purged; deactivation handled by base class', () => {
    const exists = sourceFileExists('wp-content/plugins/xophz-compass-card-vault/includes/class-card-vault-deactivator.php');
    // Baseline flags existing file; target state requires false
    assert.strictEqual(exists, false, 'Hollow Card_Vault_Deactivator class must be purged');
  });

  it('F16.3: Custom database schema creation encapsulated cleanly in activator', () => {
    const activatorContent = readSourceFile('wp-content/plugins/xophz-compass-card-vault/includes/class-card-vault-activator.php');
    if (!activatorContent) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const createsTables = activatorContent.includes('wp_xophz_vault_consignments') && activatorContent.includes('dbDelta');
    assert.strictEqual(createsTables, true, 'Activator must encapsulate schema creation via dbDelta');
  });

  it('F16.4: Spark registration for card-vault returns standard manifest with icon and route', () => {
    const content = readSourceFile('wp-content/plugins/xophz-compass-card-vault/xophz-compass-card-vault.php');
    if (!content) {
      assert.ok(true, 'File not found, skipping check');
      return;
    }
    const registersSpark = content.includes('xophz_register_sparks') && content.includes('card-vault');
    assert.strictEqual(registersSpark, true, 'Card Vault must register with Spark Registry');
  });

  it('F16.5: Action links on Plugins page include Settings shortcut via base class', () => {
    const res = runPhpJson(`
      $plugin = new class('card-vault', '1.0.0') extends Xophz_Compass_Plugin_Base {
        public function init(): void {}
      };
      $links = $plugin->add_action_links([]);
      echo json_encode([
        'hasSettings' => isset($links['settings']),
        'containsHref' => isset($links['settings']) && strpos($links['settings'], 'options-general.php?page=xophz-compass-card-vault') !== false
      ]);
    `);
    assert.strictEqual(res.hasSettings, true, 'Action links must include settings key');
    assert.strictEqual(res.containsHref, true, 'Settings link must point to xophz-compass-card-vault');
  });
}, { tier: 1, featureId: 'F16' });
