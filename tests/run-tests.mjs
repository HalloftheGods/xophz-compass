#!/usr/bin/env node

/**
 * Master E2E Test Suite Runner for Project Compass PHP Consolidation.
 * Executes the complete 4-tier requirement-driven verification matrix.
 *
 * Usage:
 *   node tests/e2e/compass-php/run-tests.mjs
 *   node tests/e2e/compass-php/run-tests.mjs --verbose
 *   node tests/e2e/compass-php/run-tests.mjs --tier=1
 *   node tests/e2e/compass-php/run-tests.mjs --tier=2
 *   node tests/e2e/compass-php/run-tests.mjs --tier=3
 *   node tests/e2e/compass-php/run-tests.mjs --tier=4
 *   node tests/e2e/compass-php/run-tests.mjs --baseline
 *
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import { harness } from './harness/test-framework.mjs';

// Import all test suites across Tiers 1 through 4
await import('./tier1-features/f01-f04-autoloader-proxy.test.mjs');
await import('./tier1-features/f05-f08-proxy-capabilities.test.mjs');
await import('./tier1-features/f09-f12-caps-nonce-mock.test.mjs');
await import('./tier1-features/f13-f16-endpoints-baseclass.test.mjs');
await import('./tier1-features/f17-f20-boilerplate-syntax-glow.test.mjs');
await import('./tier2-boundaries/boundary-corner-cases.test.mjs');
await import('./tier3-integration/cross-feature-flows.test.mjs');
await import('./tier4-real-world/operational-scenarios.test.mjs');

// Parse CLI flags
const args = process.argv.slice(2);
const isVerbose = args.includes('--verbose') || args.includes('-v');
const isBaseline = args.includes('--baseline');
const tierArg = args.find((a) => a.startsWith('--tier='));
const tierFilter = tierArg ? Number(tierArg.split('=')[1]) : null;

console.log('================================================================');
console.log('       PROJECT COMPASS: E2E PHP CONSOLIDATION TEST SUITE        ');
console.log('        Opaque-Box 4-Tier Architecture Verification             ');
console.log('================================================================');

if (tierFilter) {
  console.log(`Filter: Running Tier ${tierFilter} only\n`);
} else if (isBaseline) {
  console.log('Mode: Establishing empirical baseline results for upcoming milestones\n');
} else {
  console.log('Running complete verification matrix (Tiers 1-4)...\n');
}

const startTime = Date.now();
const results = await harness.run({ verbose: isVerbose, tierFilter, baselineMode: isBaseline });
const totalElapsed = Date.now() - startTime;

console.log('\n----------------------------------------------------------------');
console.log('                   VERIFICATION MATRIX SUMMARY                  ');
console.log('----------------------------------------------------------------');

const tierDescriptions = {
  1: 'Tier 1: Feature Coverage (Features F01 - F20)',
  2: 'Tier 2: Boundary & Corner Cases',
  3: 'Tier 3: Cross-Feature Subsystem Integration',
  4: 'Tier 4: Real-World Operational Scenarios',
};

for (const [tier, stats] of Object.entries(results.tierStats)) {
  if (tierFilter && Number(tier) !== tierFilter) continue;
  const desc = tierDescriptions[tier] || `Tier ${tier}`;
  const statusBadge = stats.failed === 0 ? '[PASS]' : '[FAIL]';
  console.log(
    `  ${statusBadge} ${desc.padEnd(46)} : ${stats.passed}/${stats.total} passed (${stats.failed} failed)`
  );
}

console.log('----------------------------------------------------------------');
console.log(`Total Suites Executed : ${results.totalSuites}`);
console.log(`Total Test Assertions : ${results.totalTests}`);
console.log(`Passing Tests         : ${results.passed}`);
console.log(`Failing Tests         : ${results.failed}`);
console.log(`Execution Duration    : ${totalElapsed}ms`);
console.log('================================================================');

if (results.failed > 0) {
  console.log('\nFAILED / UNMIGRATED TESTS:');
  for (const fail of results.failures) {
    const fId = fail.featureId ? `[${fail.featureId}] ` : '';
    console.log(`  - [Tier ${fail.tier}] ${fId}${fail.suiteName} > ${fail.testName}`);
    if (isVerbose) {
      console.log(`    Error: ${fail.error.split('\n')[0]}`);
    }
  }

  if (isBaseline) {
    console.log('\nBaseline capture complete. The failed assertions accurately identify the');
    console.log('unmigrated child plugin boilerplate and anti-patterns to be fixed in M1-M4.');
    process.exit(0);
  } else {
    console.log('\nResult: FAILED (Run with --baseline to record baseline state)');
    process.exit(1);
  }
} else {
  console.log('\nResult: 100% PASSED (Zero Defects)');
  process.exit(0);
}
