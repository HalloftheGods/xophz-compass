/**
 * PHP Execution and Verification Bridge.
 * Executes PHP snippets and lints files inside the u-wordpress Docker container.
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { spawnSync } from 'node:child_process';
import path from 'node:path';

const REPO_ROOT = '/home/xopher/www/elysium';
const CONTAINER_ROOT = '/var/www/html';

/**
 * Convert a local host path to the corresponding container path.
 */
export function toContainerPath(localPath) {
  const normalized = path.resolve(localPath);
  if (normalized.startsWith(REPO_ROOT + '/wp-content')) {
    return normalized.replace(REPO_ROOT + '/wp-content', CONTAINER_ROOT + '/wp-content');
  }
  if (normalized.startsWith(REPO_ROOT)) {
    return normalized.replace(REPO_ROOT, CONTAINER_ROOT);
  }
  return localPath;
}

/**
 * Execute a PHP snippet inside the u-wordpress container.
 */
export function runPhpSnippet(code, { loadWp = false, timeoutMs = 15000 } = {}) {
  let fullScript = '<?php\n';
  fullScript += "define('ABSPATH', '" + CONTAINER_ROOT + "/');\n";
  fullScript += "define('WPINC', 'wp-includes');\n";
  fullScript += "define('WP_PLUGIN_DIR', '" + CONTAINER_ROOT + "/wp-content/plugins');\n";

  if (loadWp) {
    fullScript += "require_once '" + CONTAINER_ROOT + "/wp-load.php';\n";
  } else {
    // Lightweight WordPress core stubs for high-speed isolated unit testing
    fullScript += `
if (!class_exists('WP_Error')) {
    class WP_Error {
        protected $code; protected $message; protected $data;
        public function __construct($code = '', $message = '', $data = '') {
            $this->code = $code; $this->message = $message; $this->data = $data;
        }
        public function get_error_code() { return $this->code; }
        public function get_error_message() { return $this->message; }
        public function get_error_data() { return $this->data; }
    }
}
if (!function_exists('is_wp_error')) { function is_wp_error($t) { return $t instanceof WP_Error; } }
if (!function_exists('add_action')) { function add_action($t, $cb, $p = 10, $a = 1) {} }
if (!function_exists('add_filter')) { function add_filter($t, $cb, $p = 10, $a = 1) {} }
if (!function_exists('apply_filters')) { function apply_filters($t, $v) { return $v; } }
if (!function_exists('do_action')) { function do_action($t) {} }
if (!function_exists('esc_url_raw')) { function esc_url_raw($u) { return $u; } }
if (!function_exists('esc_url')) { function esc_url($u) { return $u; } }
if (!function_exists('esc_js')) { function esc_js($s) { return addslashes((string)$s); } }
if (!function_exists('esc_html')) { function esc_html($s) { return htmlspecialchars((string)$s); } }
if (!function_exists('esc_html__')) { function esc_html__($s, $d = '') { return $s; } }
if (!function_exists('esc_html_e')) { function esc_html_e($s, $d = '') { echo htmlspecialchars((string)$s); } }
if (!function_exists('__')) { function __($s, $d = '') { return $s; } }
if (!function_exists('_e')) { function _e($s, $d = '') { echo $s; } }
if (!function_exists('wp_json_encode')) { function wp_json_encode($d) { return json_encode($d); } }
if (!function_exists('get_current_user_id')) { function get_current_user_id() { return 0; } }
if (!function_exists('wp_get_current_user')) { function wp_get_current_user() { return (object)['roles' => [], 'user_login' => '', 'user_email' => '', 'display_name' => '', 'user_registered' => '2026-01-01']; } }
if (!function_exists('get_avatar_url')) { function get_avatar_url($id) { return ''; } }
if (!function_exists('wp_create_nonce')) { function wp_create_nonce($a = -1) { return 'nonce_' . md5((string)$a); } }
if (!function_exists('wp_verify_nonce')) { function wp_verify_nonce($n, $a = -1) { return $n === 'nonce_' . md5((string)$a); } }
if (!function_exists('rest_url')) { function rest_url($p = '') { return 'http://localhost/wp-json/' . ltrim((string)$p, '/'); } }
if (!function_exists('get_option')) { function get_option($k, $d = false) { return $d; } }
if (!function_exists('update_option')) { function update_option($k, $v) { return true; } }
if (!function_exists('add_rewrite_rule')) { function add_rewrite_rule($r, $m, $a = 'bottom') {} }
if (!function_exists('status_header')) { function status_header($c) {} }
if (!function_exists('wp_remote_get')) { function wp_remote_get($u, $a = []) { return new WP_Error('offline', 'Dev server offline'); } }
if (!function_exists('wp_remote_post')) { function wp_remote_post($u, $a = []) { return new WP_Error('offline', 'Dev server offline'); } }
if (!function_exists('wp_safe_remote_get')) { function wp_safe_remote_get($u, $a = []) { return new WP_Error('offline', 'Dev server offline'); } }
if (!function_exists('wp_safe_remote_post')) { function wp_safe_remote_post($u, $a = []) { return new WP_Error('offline', 'Dev server offline'); } }
if (!function_exists('wp_remote_retrieve_response_code')) { function wp_remote_retrieve_response_code($r) { return is_array($r) ? ($r['response']['code'] ?? 0) : 0; } }
if (!function_exists('wp_remote_retrieve_body')) { function wp_remote_retrieve_body($r) { return is_array($r) ? ($r['body'] ?? '') : ''; } }
if (!function_exists('sanitize_text_field')) { function sanitize_text_field($s) { return strip_tags(trim((string)$s)); } }
if (!function_exists('sanitize_key')) { function sanitize_key($k) { return strtolower(preg_replace('/[^a-z0-9_\\-]/', '', (string)$k)); } }
if (!function_exists('wp_unslash')) { function wp_unslash($v) { return $v; } }
if (!function_exists('current_user_can')) { function current_user_can($c) { return false; } }
if (!function_exists('is_user_logged_in')) { function is_user_logged_in() { return false; } }
if (!function_exists('plugin_dir_path')) { function plugin_dir_path($f) { return dirname($f) . '/'; } }
if (!function_exists('plugin_dir_url')) { function plugin_dir_url($f) { return 'http://localhost/wp-content/plugins/' . basename(dirname($f)) . '/'; } }
if (!function_exists('plugin_basename')) { function plugin_basename($f) { return basename(dirname($f)) . '/' . basename($f); } }
if (!function_exists('load_plugin_textdomain')) { function load_plugin_textdomain($d, $dep = false, $p = false) { return true; } }
if (!function_exists('admin_url')) { function admin_url($p = '') { return 'http://localhost/wp-admin/' . ltrim((string)$p, '/'); } }
if (!function_exists('flush_rewrite_rules')) { function flush_rewrite_rules() {} }
if (!function_exists('register_setting')) { function register_setting($g, $o, $a = []) {} }
if (!function_exists('settings_fields')) { function settings_fields($g) {} }
if (!function_exists('do_settings_sections')) { function do_settings_sections($p) {} }
if (!function_exists('submit_button')) { function submit_button() {} }
if (!function_exists('wp_send_json_error')) { function wp_send_json_error($d = null, $s = null) { echo json_encode(['success' => false, 'data' => $d]); } }
if (!function_exists('wp_send_json_success')) { function wp_send_json_success($d = null, $s = null) { echo json_encode(['success' => true, 'data' => $d]); } }
if (!function_exists('get_transient')) { function get_transient($t) { return false; } }
if (!function_exists('set_transient')) { function set_transient($t, $v, $e = 0) { return true; } }
if (!function_exists('wp_parse_args')) { function wp_parse_args($a, $d = []) { return array_merge($d, (array)$a); } }
if (!function_exists('rest_authorization_required_code')) { function rest_authorization_required_code() { return 401; } }

if (!class_exists('WP_REST_Request')) {
    class WP_REST_Request {
        protected $params = [];
        protected $headers = [];
        public function __construct($method = 'GET', $route = '') {}
        public function set_param($k, $v) { $this->params[$k] = $v; }
        public function get_param($k) { return $this->params[$k] ?? null; }
        public function get_header($h) { return $this->headers[$h] ?? null; }
    }
}
if (!class_exists('WP_REST_Response')) {
    class WP_REST_Response {
        protected $data; protected $status; protected $headers = [];
        public function __construct($data = null, $status = 200, $headers = []) {
            $this->data = $data; $this->status = $status; $this->headers = $headers;
        }
        public function get_data() { return $this->data; }
        public function get_status() { return $this->status; }
        public function get_headers() { return $this->headers; }
        public function header($k, $v) { $this->headers[$k] = $v; }
    }
}
if (!class_exists('WP_REST_Controller')) {
    abstract class WP_REST_Controller {
        protected $namespace;
        protected $rest_base;
        abstract public function register_routes();
    }
}
`;
  }

  fullScript += "require_once '" + CONTAINER_ROOT + "/wp-content/plugins/xophz-compass/includes/core/class-compass-autoloader.php';\n";
  fullScript += "Xophz_Compass_Autoloader::register();\n";
  fullScript += code;

  const result = spawnSync('docker', ['exec', '-i', 'u-wordpress', 'php'], {
    input: fullScript,
    encoding: 'utf8',
    timeout: timeoutMs,
  });

  return {
    status: result.status,
    stdout: result.stdout ? result.stdout.trim() : '',
    stderr: result.stderr ? result.stderr.trim() : '',
    error: result.error,
  };
}

/**
 * Run a PHP snippet and parse JSON response from stdout.
 */
export function runPhpJson(code, options = {}) {
  const res = runPhpSnippet(code, options);
  if (res.status !== 0) {
    throw new Error(`PHP execution failed with exit code ${res.status}: ${res.stderr || res.stdout}`);
  }
  try {
    return JSON.parse(res.stdout);
  } catch (err) {
    throw new Error(`Failed to parse PHP JSON output: ${res.stdout}. Stderr: ${res.stderr}`);
  }
}

/**
 * Run syntax linting (php -l) on a PHP file inside the container.
 */
export function lintPhpFile(localPath) {
  const containerPath = toContainerPath(localPath);
  const result = spawnSync('docker', ['exec', 'u-wordpress', 'php', '-l', containerPath], {
    encoding: 'utf8',
    timeout: 10000,
  });

  const output = (result.stdout + '\n' + result.stderr).trim();
  const isValid = result.status === 0 && output.includes('No syntax errors detected');

  return {
    isValid,
    status: result.status,
    output,
    file: localPath,
  };
}

/**
 * Check if a core class exists and can be resolved by the autoloader.
 */
export function checkCoreClassAutoload(className) {
  return runPhpJson(`
    $exists = class_exists('${className}') || interface_exists('${className}') || trait_exists('${className}');
    echo json_encode([
      'class' => '${className}',
      'resolved' => $exists
    ]);
  `);
}

/**
 * Measure socket probe latency for a given port.
 */
export function measureSocketProbe(port, timeoutSeconds = 0.15) {
  return runPhpJson(`
    $start = microtime(true);
    $active = Xophz_Compass_Dev_Proxy::is_dev_active(${port}, '127.0.0.1', ${timeoutSeconds});
    $elapsedMs = (microtime(true) - $start) * 1000;
    echo json_encode([
      'port' => ${port},
      'active' => $active,
      'elapsedMs' => round($elapsedMs, 2)
    ]);
  `);
}
