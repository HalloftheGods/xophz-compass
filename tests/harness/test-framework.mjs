/**
 * Standalone zero-dependency test runner and assertion library for Project Compass PHP Consolidation.
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import assert from 'node:assert';

class TestHarness {
  constructor() {
    this.suites = [];
    this.currentSuite = null;
  }

  describe(name, fn, meta = {}) {
    const suite = {
      name,
      tier: meta.tier || 1,
      featureId: meta.featureId || null,
      tests: [],
      beforeHooks: [],
      afterHooks: [],
    };
    this.suites.push(suite);
    const prevSuite = this.currentSuite;
    this.currentSuite = suite;
    fn();
    this.currentSuite = prevSuite;
  }

  it(name, testFn) {
    if (!this.currentSuite) {
      throw new Error(`Test '${name}' must be defined inside a describe() block`);
    }
    this.currentSuite.tests.push({
      name,
      fn: testFn,
    });
  }

  beforeEach(fn) {
    if (this.currentSuite) {
      this.currentSuite.beforeHooks.push(fn);
    }
  }

  afterEach(fn) {
    if (this.currentSuite) {
      this.currentSuite.afterHooks.push(fn);
    }
  }

  async run({ verbose = false, tierFilter = null, baselineMode = false } = {}) {
    const results = {
      totalSuites: 0,
      totalTests: 0,
      passed: 0,
      failed: 0,
      startTime: Date.now(),
      durationMs: 0,
      failures: [],
      tierStats: {
        1: { total: 0, passed: 0, failed: 0 },
        2: { total: 0, passed: 0, failed: 0 },
        3: { total: 0, passed: 0, failed: 0 },
        4: { total: 0, passed: 0, failed: 0 },
      },
    };

    for (const suite of this.suites) {
      if (tierFilter !== null && suite.tier !== Number(tierFilter)) {
        continue;
      }

      results.totalSuites += 1;
      const tierLabel = `[Tier ${suite.tier}]`;
      const featureLabel = suite.featureId ? `[${suite.featureId}] ` : '';

      if (verbose) {
        console.log(`\n--- ${tierLabel} ${featureLabel}${suite.name} ---`);
      }

      for (const test of suite.tests) {
        results.totalTests += 1;
        const tier = suite.tier;
        if (results.tierStats[tier]) {
          results.tierStats[tier].total += 1;
        }

        const testStart = Date.now();
        let pass = false;
        let errMessage = null;

        try {
          for (const hook of suite.beforeHooks) {
            await hook();
          }

          await test.fn();

          for (const hook of suite.afterHooks) {
            await hook();
          }

          pass = true;
          results.passed += 1;
          if (results.tierStats[tier]) {
            results.tierStats[tier].passed += 1;
          }
        } catch (err) {
          pass = false;
          results.failed += 1;
          if (results.tierStats[tier]) {
            results.tierStats[tier].failed += 1;
          }
          errMessage = err instanceof Error ? err.stack || err.message : String(err);
          results.failures.push({
            tier: suite.tier,
            featureId: suite.featureId,
            suiteName: suite.name,
            testName: test.name,
            error: errMessage,
          });
        }

        const testDuration = Date.now() - testStart;
        if (verbose) {
          const badge = pass ? '  [PASS]' : '  [FAIL]';
          console.log(`${badge} ${test.name} (${testDuration}ms)`);
          if (!pass && errMessage) {
            console.error(`         ${errMessage.split('\n')[0]}`);
          }
        }
      }
    }

    results.durationMs = Date.now() - results.startTime;
    return results;
  }
}

export const harness = new TestHarness();
export const describe = harness.describe.bind(harness);
export const it = harness.it.bind(harness);
export const beforeEach = harness.beforeEach.bind(harness);
export const afterEach = harness.afterEach.bind(harness);

/**
 * Custom Zero-Entropy Assertions
 */
export function assertNoEmDashes(content, label = 'Content') {
  const emDashRegex = /\u2014/g;
  if (emDashRegex.test(content)) {
    const lines = content.split('\n');
    const matches = [];
    lines.forEach((line, index) => {
      if (line.includes('\u2014')) {
        matches.push(`Line ${index + 1}: ${line.trim()}`);
      }
    });
    assert.fail(`${label} contains prohibited em dash characters (\\u2014):\n${matches.slice(0, 5).join('\n')}`);
  }
}

export function assertNoSyntheticData(content, label = 'Content') {
  const syntheticEmailRegex = /@(?:example\.com|gmail\.com|test\.com|mock\.com)/i;
  const dummyPhoneRegex = /555-\d{3,4}/;
  if (syntheticEmailRegex.test(content)) {
    assert.fail(`${label} contains prohibited synthetic email address`);
  }
  if (dummyPhoneRegex.test(content)) {
    assert.fail(`${label} contains prohibited synthetic phone number`);
  }
}

export { assert };
