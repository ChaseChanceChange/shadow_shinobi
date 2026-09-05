#!/usr/bin/env bash
# Shadow Shinobi local / CI smoke test.
# Usage: from repo root after compose is up on :8080
set -euo pipefail

BASE_URL="${BASE_URL:-http://127.0.0.1:8080}"
COOKIE_JAR="${COOKIE_JAR:-/tmp/shadow-shinobi-smoke.cookies}"
OUT_DIR="${OUT_DIR:-/tmp/shadow-shinobi-smoke}"
mkdir -p "$OUT_DIR"
rm -f "$COOKIE_JAR"

fail() { echo "FAIL: $*" >&2; exit 1; }
pass() { echo "PASS: $*"; }

assert_clean() {
  local file="$1"
  local label="$2"
  if grep -Eqi 'Warning:|Deprecated:|Notice:|Fatal error:|Parse error:|Uncaught ' "$file"; then
    echo "--- $label body (first 40 lines) ---" >&2
    head -n 40 "$file" >&2
    fail "PHP error text in $label"
  fi
}

assert_not_login() {
  local file="$1"
  local label="$2"
  if grep -Eqi 'ss-login__form|name="username"|Enter the world' "$file"; then
    fail "$label rendered the login page (session not authenticated)"
  fi
}

echo "== Public pages =="
for path in 'login.php?do=login' 'help.php' 'rank.php'; do
  out="$OUT_DIR/public-$(echo "$path" | tr '/?=&' '____').html"
  curl --fail --silent --show-error "$BASE_URL/$path" > "$out"
  test -s "$out" || fail "empty response for $path"
  assert_clean "$out" "$path"
  pass "$path"
done

echo "== Developer health =="
curl --fail --silent --show-error "$BASE_URL/dev_health.php" > "$OUT_DIR/dev-health.html"
grep -q 'Core checks passing' "$OUT_DIR/dev-health.html" || fail "dev_health core checks"
pass "dev_health.php"

echo "== Developer login =="
# Capture Set-Cookie without following redirect first
headers="$OUT_DIR/dev-login.headers"
curl --silent --show-error -D "$headers" -o "$OUT_DIR/dev-login-body.html" \
  -c "$COOKIE_JAR" -b "$COOKIE_JAR" \
  "$BASE_URL/login.php?do=dev" || true
if ! grep -qi '^Set-Cookie:.*dkgame=' "$headers"; then
  cat "$headers" >&2
  fail "dev login did not Set-Cookie dkgame"
fi
if ! grep -q dkgame "$COOKIE_JAR"; then
  cat "$COOKIE_JAR" >&2
  fail "cookie jar missing dkgame after dev login"
fi
# Follow to index
curl --fail --silent --show-error -L -c "$COOKIE_JAR" -b "$COOKIE_JAR" \
  "$BASE_URL/login.php?do=dev" > "$OUT_DIR/after-dev-login.html"
assert_clean "$OUT_DIR/after-dev-login.html" "after dev login"
assert_not_login "$OUT_DIR/after-dev-login.html" "after dev login"
pass "developer login cookie + redirect"

echo "== Authenticated routes =="
declare -A ROUTES=(
  [home]='index.php'
  [showchar]='index.php?do=showchar'
  [backpack]='backpack.php'
  [rank]='rank.php'
  [help]='help.php'
  [town_inn]='index.php?do=inn'
  [explore_move]='index.php?do=move'
)
for name in home showchar backpack rank help town_inn explore_move; do
  path="${ROUTES[$name]}"
  out="$OUT_DIR/auth-$name.html"
  # Do not follow redirects: a bounce to login must fail the test
  code=$(curl --silent --show-error -o "$out" -w '%{http_code}' -b "$COOKIE_JAR" "$BASE_URL/$path")
  if [[ "$code" == "302" || "$code" == "301" ]]; then
    loc=$(curl --silent --show-error -I -b "$COOKIE_JAR" "$BASE_URL/$path" | tr -d '\r' | awk -F': ' 'tolower($1)=="location"{print $2}')
    if echo "$loc" | grep -qi 'login.php'; then
      fail "$name redirected to login ($loc)"
    fi
  fi
  [[ "$code" == "200" || "$code" == "302" ]] || fail "$name HTTP $code"
  if [[ -s "$out" ]]; then
    assert_clean "$out" "$name"
    if [[ "$name" != "rank" && "$name" != "help" ]]; then
      assert_not_login "$out" "$name"
    fi
  fi
  pass "$name ($code)"
done

echo "All smoke checks passed."
