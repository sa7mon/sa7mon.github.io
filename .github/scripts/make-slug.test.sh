#!/usr/bin/env bash
set -euo pipefail

fail_count=0

run_test() {
  local title="$1"
  local date_arg="$2"
  local expected_suffix="$3"

  local today
  today=$(date +%Y-%m-%d)
  local expected="${today}_${expected_suffix}"

  local out
  out="$(./make-slug.sh -t "$title" -d "$date_arg")"

  if [ "$out" = "$expected" ]; then
    printf "[PASS] %s -> %s\n" "$title" "$out"
  else
    printf "[FAIL] %s\n  expected: %s\n  got:      %s\n" "$title" "$expected" "$out"
    fail_count=$((fail_count+1))
  fi
}

# Test cases
# 1) Required test from user: title with punctuation and apostrophe
run_test "SQL Injection Isn't Dead: Smuggling Queries At The Protocol Level - Paul Gerste" "2025-02-11" "sql-injection-isnt-dead-smuggling-queries-at-the-protocol-level-paul-gerste_2025-02-11"

# Multiple spaces collapse
run_test "A   B  C" "2019-12-31" "a-b-c_2019-12-31"

# space-hyphen-space should collapse to single hyphen
run_test "A - B - C" "2020-01-01" "a-b-c_2020-01-01"

if [ "$fail_count" -gt 0 ]; then
  echo "\n$fail_count test(s) failed"
  exit 1
else
  echo "All tests passed"
  exit 0
fi
