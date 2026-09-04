/**
 * Code Analysis and Static Inspection Utility.
 * Inspects PHP source files, class definitions, and checks Zero-Entropy compliance.
 * Strictly zero em dashes in code, copy, comments, and reports.
 * Zero mock or synthetic data.
 */

import fs from 'node:fs';
import path from 'node:path';

const REPO_ROOT = '/home/xopher/www/elysium';

/**
 * Read file content safely as string.
 */
export function readSourceFile(relativePath) {
  const fullPath = path.isAbsolute(relativePath) ? relativePath : path.join(REPO_ROOT, relativePath);
  if (!fs.existsSync(fullPath)) {
    return null;
  }
  return fs.readFileSync(fullPath, 'utf8');
}

/**
 * Check if a file exists.
 */
export function sourceFileExists(relativePath) {
  const fullPath = path.isAbsolute(relativePath) ? relativePath : path.join(REPO_ROOT, relativePath);
  return fs.existsSync(fullPath);
}

/**
 * Find files matching a regex in a directory recursively.
 */
export function findFilesRecursively(dirPath, pattern, fileList = []) {
  const fullPath = path.isAbsolute(dirPath) ? dirPath : path.join(REPO_ROOT, dirPath);
  if (!fs.existsSync(fullPath)) {
    return fileList;
  }

  const entries = fs.readdirSync(fullPath, { withFileTypes: true });
  for (const entry of entries) {
    if (entry.name === 'node_modules' || entry.name === '.git' || entry.name === '.agents') {
      continue;
    }
    const entryFullPath = path.join(fullPath, entry.name);
    if (entry.isDirectory()) {
      findFilesRecursively(entryFullPath, pattern, fileList);
    } else if (entry.isFile()) {
      if (typeof pattern === 'string' ? entry.name.includes(pattern) : pattern.test(entry.name)) {
        fileList.push(entryFullPath);
      }
    }
  }
  return fileList;
}

/**
 * Scan a file or string for prohibited em dashes (\u2014).
 */
export function scanForEmDashes(content) {
  const lines = content.split('\n');
  const violations = [];
  lines.forEach((line, idx) => {
    if (line.includes('\u2014')) {
      violations.push({
        line: idx + 1,
        content: line.trim(),
      });
    }
  });
  return violations;
}

/**
 * Check if file uses Xophz_Compass_Dev_Proxy.
 */
export function usesDevProxy(content) {
  if (!content) return false;
  return content.includes('Xophz_Compass_Dev_Proxy') || content.includes('new Xophz_Compass_Dev_Proxy');
}

/**
 * Check if file has legacy blocking dev proxy loop.
 */
export function hasBlockingDevProxyLoop(content) {
  if (!content) return false;
  return content.includes('$dev_hosts = array') && content.includes('@file_get_contents');
}

/**
 * Check if file extends Xophz_Compass_Plugin_Base.
 */
export function extendsPluginBase(content) {
  if (!content) return false;
  return /extends\s+Xophz_Compass_Plugin_Base\b/.test(content);
}

/**
 * Check if file uses Xophz_Compass_Hookable_Trait.
 */
export function usesHookableTrait(content) {
  if (!content) return false;
  return /use\s+Xophz_Compass_Hookable_Trait\b/.test(content);
}

/**
 * Check if file contains role check for administrator.
 */
export function hasAdminRoleCheck(content) {
  if (!content) return false;
  return /current_user_can\(\s*['"]administrator['"]\s*\)/.test(content) ||
         /in_array\(\s*['"]administrator['"],\s*\$roles/.test(content) ||
         /in_array\(\s*['"]administrator['"],\s*\(array\)\s*\$u->roles/.test(content);
}
