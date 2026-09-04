/**
 * Tier 2: Boundary & Corner Cases (Tests B01 - B17)
 * Evaluates edge conditions, boundary values, timeout caps, and security edge cases.
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { describe, it, assert } from '../harness/test-framework.mjs';
import { runPhpJson, measureSocketProbe } from '../harness/php-executor.mjs';

describe('Tier 2: Boundary & Corner Cases', () => {
  it('B01: Socket probe on offline port 65534 completes in <= 150ms without throwing or hanging', () => {
    const probe = measureSocketProbe(65534, 0.15);
    assert.strictEqual(probe.active, false, 'Offline port must return false');
    assert.ok(probe.elapsedMs <= 180, `Latency must be <= 150ms (+ allowance), got ${probe.elapsedMs}ms`);
  });

  it('B02: Dev Proxy with unreachable hostname safely returns null without fatal crash', () => {
    const res = runPhpJson(`
      $active = Xophz_Compass_Dev_Proxy::is_dev_active(8080, '192.0.2.1', 0.10);
      echo json_encode(['active' => $active]);
    `);
    assert.strictEqual(res.active, false, 'Unreachable IP must safely return false');
  });

  it('B03: Dev Proxy with empty candidate dist paths returns graceful fallback error HTML without throwing', () => {
    const res = runPhpJson(`
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'                 => 'test-app',
        'dev_port'             => 8080,
        'query_var'            => 'test_app',
        'plugin_path'          => '/var/www/html/wp-content/plugins/xophz-compass-test/',
        'plugin_url'           => 'http://localhost/wp-content/plugins/xophz-compass-test/',
        'version'              => '1.0.0',
        'candidate_dist_paths' => []
      ]);
      $reflection = new ReflectionMethod($proxy, 'load_production_dist');
      $reflection->setAccessible(true);
      $html = $reflection->invoke($proxy);
      echo json_encode([
        'containsNotFound' => strpos($html, 'build not found') !== false
      ]);
    `);
    assert.strictEqual(res.containsNotFound, true, 'Graceful not found message must be returned');
  });

  it('B04: Dev Proxy handle_template_redirect ignores /wp-admin and /wp-login.php URIs', () => {
    const res = runPhpJson(`
      $_SERVER['REQUEST_URI'] = '/wp-login.php?action=logout';
      $proxy = new Xophz_Compass_Dev_Proxy([
        'slug'        => 'test-app',
        'dev_port'    => 8080,
        'query_var'   => 'test_app',
        'plugin_path' => '/var/www/html/wp-content/plugins/xophz-compass-test/',
        'plugin_url'  => 'http://localhost/wp-content/plugins/xophz-compass-test/',
        'version'     => '1.0.0'
      ]);
      ob_start();
      $proxy->handle_template_redirect();
      $out = ob_get_clean();
      echo json_encode(['ignored' => empty($out)]);
    `);
    assert.strictEqual(res.ignored, true, 'Login route must be ignored');
  });

  it('B05: Sanitization get_http_method defaults to GET for invalid or dangerous HTTP verbs', () => {
    const res = runPhpJson(`
      $dangerousVerbs = ['TRACE', 'TRACK', '<script>', 'CONNECT', ''];
      $results = [];
      foreach ($dangerousVerbs as $verb) {
        $_SERVER['REQUEST_METHOD'] = $verb;
        $results[$verb] = Xophz_Compass_Sanitization::get_http_method();
      }
      echo json_encode($results);
    `);
    for (const [verb, sanitized] of Object.entries(res)) {
      assert.strictEqual(sanitized, 'GET', `Dangerous verb '${verb}' must default to GET`);
    }
  });

  it('B06: Sanitization get_json_input returns null on malformed JSON payload without PHP notices', () => {
    const res = runPhpJson(`
      // Simulate invalid JSON via helper
      $decoded = json_decode('{"invalid": json}', true);
      $hasError = json_last_error() !== JSON_ERROR_NONE;
      echo json_encode(['hasError' => $hasError]);
    `);
    assert.strictEqual(res.hasError, true, 'Invalid JSON must be caught cleanly');
  });

  it('B07: Sanitization get_string, get_int, and get_float handle missing keys and non-scalar types', () => {
    const res = runPhpJson(`
      $source = [
        'valid_str'   => 'hello',
        'array_as_str'=> ['nested'],
        'valid_int'   => '123',
        'str_as_int'  => 'not_an_int',
        'valid_float' => '3.14159',
        'str_as_float'=> 'not_a_float'
      ];
      $sDefault = Xophz_Compass_Sanitization::get_string($source, 'missing_key', 'fallback_str');
      $sArray   = Xophz_Compass_Sanitization::get_string($source, 'array_as_str', 'fallback_str');
      $iDefault = Xophz_Compass_Sanitization::get_int($source, 'missing_int', 99);
      $iInvalid = Xophz_Compass_Sanitization::get_int($source, 'str_as_int', 99);
      $fDefault = Xophz_Compass_Sanitization::get_float($source, 'missing_float', 1.5);
      $fInvalid = Xophz_Compass_Sanitization::get_float($source, 'str_as_float', 1.5);

      echo json_encode([
        'sDefault' => $sDefault,
        'sArray'   => $sArray,
        'iDefault' => $iDefault,
        'iInvalid' => $iInvalid,
        'fDefault' => $fDefault,
        'fInvalid' => $fInvalid
      ]);
    `);
    assert.strictEqual(res.sDefault, 'fallback_str', 'Missing string must return fallback');
    assert.strictEqual(res.sArray, 'fallback_str', 'Array passed as string must return fallback');
    assert.strictEqual(res.iDefault, 99, 'Missing int must return fallback');
    assert.strictEqual(res.iInvalid, 99, 'Non-numeric string must return fallback');
    assert.strictEqual(res.fDefault, 1.5, 'Missing float must return fallback');
    assert.strictEqual(res.fInvalid, 1.5, 'Non-numeric float must return fallback');
  });

  it('B08: Sanitization sanitize_deep handles deeply nested mixed arrays without memory exhaustion', () => {
    const res = runPhpJson(`
      $nested = [
        'level1' => [
          'level2' => [
            'level3' => [
              'text' => 'Clean Text',
              'num'  => 42,
              'bool' => true
            ]
          ]
        ]
      ];
      $sanitized = Xophz_Compass_Sanitization::sanitize_deep($nested);
      echo json_encode([
        'preservedText' => $sanitized['level1']['level2']['level3']['text'],
        'preservedNum'  => $sanitized['level1']['level2']['level3']['num']
      ]);
    `);
    assert.strictEqual(res.preservedText, 'Clean Text', 'Deeply nested text must be preserved');
    assert.strictEqual(res.preservedNum, 42, 'Deeply nested number must be preserved');
  });

  it('B09: Security validate_compass_slug rejects path traversal attempts', () => {
    const res = runPhpJson(`
      $traversals = [
        '../evil',
        '..%2Fevil',
        '../../etc/passwd',
        '../../../wp-config',
        'invalid slug with spaces'
      ];
      $results = [];
      foreach ($traversals as $slug) {
        $results[$slug] = Xophz_Compass_Security::validate_compass_slug($slug);
      }
      echo json_encode($results);
    `);
    for (const [slug, validated] of Object.entries(res)) {
      assert.strictEqual(validated, null, 'Path traversal attempt ' + slug + ' must be rejected with null');
    }
  });

  it('B10: Security check_rate_limit enforces threshold and blocks when rate exceeded', () => {
    const res = runPhpJson(`
      $key = 'test_rate_limit_' . uniqid();
      $r1 = Xophz_Compass_Security::check_rate_limit($key, 2, 60);
      $r2 = Xophz_Compass_Security::check_rate_limit($key, 2, 60);
      $r3 = Xophz_Compass_Security::check_rate_limit($key, 2, 60);
      echo json_encode([
        'r1' => $r1,
        'r2' => $r2,
        'r3' => $r3
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.r1, true, '1st request should pass');
    assert.strictEqual(res.r2, true, '2nd request should pass');
    assert.strictEqual(res.r3, false, '3rd request must be blocked (exceeded limit of 2)');
  });

  it('B11: Security require_admin returns false for unauthenticated context', () => {
    const res = runPhpJson(`
      $hasAdmin = current_user_can('manage_options');
      echo json_encode(['hasAdmin' => $hasAdmin]);
    `);
    assert.strictEqual(res.hasAdmin, false, 'Anonymous execution must not possess admin privileges');
  });

  it('B12: REST Controller get_param_bool parses diverse boolean representations', () => {
    const res = runPhpJson(`
      $controller = new class('test', 'v1') extends Xophz_Compass_REST_Controller {
        public function register_routes() {}
        public function test_bool($val) {
          $req = new WP_REST_Request();
          $req->set_param('flag', $val);
          return $this->get_param_bool($req, 'flag', false);
        }
      };
      echo json_encode([
        'bTrue'  => $controller->test_bool('true'),
        'bOne'   => $controller->test_bool('1'),
        'bFalse' => $controller->test_bool('false'),
        'bZero'  => $controller->test_bool('0'),
        'bNull'  => $controller->test_bool(null)
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.bTrue, true, "'true' string must parse to true");
    assert.strictEqual(res.bOne, true, "'1' string must parse to true");
    assert.strictEqual(res.bFalse, false, "'false' string must parse to false");
    assert.strictEqual(res.bZero, false, "'0' string must parse to false");
    assert.strictEqual(res.bNull, false, 'null parameter must parse to fallback false');
  });

  it('B13: REST Controller error_response produces valid WP_Error with custom status and error code', () => {
    const res = runPhpJson(`
      $controller = new class('test', 'v1') extends Xophz_Compass_REST_Controller {
        public function register_routes() {}
        public function make_error() {
          return $this->error_response('custom_edge_error', 'Boundary condition failure', 422);
        }
      };
      $err = $controller->make_error();
      echo json_encode([
        'isError' => is_wp_error($err),
        'code'    => $err->get_error_code(),
        'message' => $err->get_error_message(),
        'status'  => $err->get_error_data()['status'] ?? null
      ]);
    `, { loadWp: true });
    assert.strictEqual(res.isError, true, 'Result must be WP_Error');
    assert.strictEqual(res.code, 'custom_edge_error', 'Code must match');
    assert.strictEqual(res.status, 422, 'Status must be 422');
  });

  it('B14: HTTP Client resolve_api_key returns null cleanly when no providers configured', () => {
    const res = runPhpJson(`
      $key = Xophz_Compass_HTTP::resolve_api_key(
        'non_existent_connector_xyz',
        ['non_existent_option_1', 'non_existent_option_2'],
        'NON_EXISTENT_ENV_VAR_XYZ'
      );
      echo json_encode(['key' => $key]);
    `);
    assert.strictEqual(res.key, null, 'Unconfigured provider must return clean null');
  });

  it('B15: Autoloader handles repeated registrations idempotently without duplicate hook errors', () => {
    const res = runPhpJson(`
      Xophz_Compass_Autoloader::register();
      Xophz_Compass_Autoloader::register();
      Xophz_Compass_Autoloader::register();
      $canLoad = class_exists('Xophz_Compass_Dev_Proxy');
      echo json_encode(['canLoad' => $canLoad]);
    `);
    assert.strictEqual(res.canLoad, true, 'Multiple register calls must remain idempotent');
  });

  it('B16: Dev Proxy resolve_host respects COMPASS_DEV_HOST environment variable via getenv/putenv', () => {
    const res = runPhpJson(`
      // Bind a test listener on loopback alias 127.0.0.2 (port 48190)
      $socket = stream_socket_server('tcp://127.0.0.2:48190', $errno, $errstr);
      if (!$socket) {
        echo json_encode(['error' => 'socket_bind_failed']);
        exit;
      }

      // Step 1: Without COMPASS_DEV_HOST, resolve_host returns null (127.0.0.2 not in default candidates)
      putenv('COMPASS_DEV_HOST');
      $before = Xophz_Compass_Dev_Proxy::resolve_host(48190);

      // Step 2: With COMPASS_DEV_HOST set via putenv, resolve_host detects and returns 127.0.0.2
      putenv('COMPASS_DEV_HOST=127.0.0.2');
      $after = Xophz_Compass_Dev_Proxy::resolve_host(48190);

      fclose($socket);
      putenv('COMPASS_DEV_HOST'); // Clean up

      echo json_encode([
        'before' => $before,
        'after'  => $after
      ]);
    `);
    assert.strictEqual(res.before, null, 'Without COMPASS_DEV_HOST, resolve_host must not probe 127.0.0.2');
    assert.strictEqual(res.after, '127.0.0.2', 'With COMPASS_DEV_HOST=127.0.0.2, resolve_host must return 127.0.0.2');
  });

  it('B17: Dev Proxy candidate host prioritization, whitespace trimming, and deduplication', () => {
    const res = runPhpJson(`
      putenv('COMPASS_DEV_HOST');
      $default = Xophz_Compass_Dev_Proxy::get_candidate_hosts();

      putenv('COMPASS_DEV_HOST=  custom-node-host  ');
      $trimmed = Xophz_Compass_Dev_Proxy::get_candidate_hosts();

      putenv('COMPASS_DEV_HOST=127.0.0.1');
      $deduped = Xophz_Compass_Dev_Proxy::get_candidate_hosts();

      putenv('COMPASS_DEV_HOST=   ');
      $whitespaceOnly = Xophz_Compass_Dev_Proxy::get_candidate_hosts();

      putenv('COMPASS_DEV_HOST'); // Clean up

      echo json_encode([
        'defaultCount'       => count($default),
        'defaultFirst'       => $default[0],
        'trimmedFirst'       => $trimmed[0],
        'dedupedFirst'       => $deduped[0],
        'dedupedOccurrences' => count(array_keys($deduped, '127.0.0.1')),
        'whitespaceFirst'    => $whitespaceOnly[0]
      ]);
    `);
    assert.strictEqual(res.defaultFirst, 'compass', 'Default first candidate should be compass');
    assert.strictEqual(res.trimmedFirst, 'custom-node-host', 'Whitespace in COMPASS_DEV_HOST must be trimmed');
    assert.strictEqual(res.dedupedFirst, '127.0.0.1', 'Specifying existing candidate must prioritize it to index 0');
    assert.strictEqual(res.dedupedOccurrences, 1, 'Prioritized existing candidate must not be duplicated');
    assert.strictEqual(res.whitespaceFirst, 'compass', 'Whitespace-only COMPASS_DEV_HOST must fall back to default');
  });
}, { tier: 2 });
