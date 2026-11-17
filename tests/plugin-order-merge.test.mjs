/**
 * Saved launcher order: the pure merge and normalize helpers of
 * Xophz_Compass_Plugin_Order_API. Runs on the host PHP, no WordPress needed.
 *
 *   chemx test wp-content/plugins/xophz-compass/tests/plugin-order-merge.test.mjs
 */

import { test } from 'node:test';
import assert from 'node:assert/strict';
import { spawnSync } from 'node:child_process';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const CLASS_FILE = path.resolve(
  path.dirname(fileURLToPath(import.meta.url)),
  '../includes/class-xophz-compass-plugin-order-api.php'
);

function php(expression) {
  const script = `define('ABSPATH', '/'); require '${CLASS_FILE}'; echo json_encode(${expression});`;
  const run = spawnSync('php', ['-r', script], { encoding: 'utf8' });
  assert.equal(run.status, 0, run.stderr || run.stdout);
  return JSON.parse(run.stdout);
}

test('sent ids reuse their stored slots in the sent order', () => {
  assert.deepEqual(
    php("Xophz_Compass_Plugin_Order_API::merge_order(['a','b','c','d'], ['c','a'])"),
    ['c', 'b', 'a', 'd']
  );
});

test('sent ids that were never stored are appended', () => {
  assert.deepEqual(php("Xophz_Compass_Plugin_Order_API::merge_order(['a'], ['a','z'])"), ['a', 'z']);
});

test('an empty sent order leaves the stored order unchanged', () => {
  assert.deepEqual(php("Xophz_Compass_Plugin_Order_API::merge_order(['a','b'], [])"), ['a', 'b']);
});

test('a first save into an empty store keeps the sent order', () => {
  assert.deepEqual(php("Xophz_Compass_Plugin_Order_API::merge_order([], ['b','a'])"), ['b', 'a']);
});

test('normalize accepts a JSON string, drops non-strings, blanks and duplicates', () => {
  assert.deepEqual(
    php(`Xophz_Compass_Plugin_Order_API::normalize_order('["a"," b ",1,"","a",null]')`),
    ['a', 'b']
  );
});

test('normalize returns null for anything that is not a list', () => {
  assert.equal(php("Xophz_Compass_Plugin_Order_API::normalize_order('nope')"), null);
  assert.equal(php("Xophz_Compass_Plugin_Order_API::normalize_order(42)"), null);
});

test('a new id dragged to the front stays at the front', () => {
  assert.deepEqual(
    php("Xophz_Compass_Plugin_Order_API::merge_order(['a','b','c'], ['s','a','b','c'])"),
    ['s', 'a', 'b', 'c']
  );
});

test('a new id is placed after the sent neighbour it follows', () => {
  assert.deepEqual(
    php("Xophz_Compass_Plugin_Order_API::merge_order(['a','b','c'], ['a','s','b'])"),
    ['a', 's', 'b', 'c']
  );
});
