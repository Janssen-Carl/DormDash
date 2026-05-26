# DormDash — Risk Mitigation Audit

Mapping each asset/threat from the risk assessment CSV against what the system **actually** implements.

| # | Asset | Threat | Risk Score | Mitigated? | Evidence / Gap |
|---|-------|--------|:----------:|:----------:|----------------|
| 1 | Web Server | Web server overload | 12 | ✅ Yes | Nginx `limit_req_zone` (120r/m global, 5r/m login, 30r/m API); `client_max_body_size 1M`; WAF blocks malformed requests |
| 2 | Database Server | Database DoS | 10 | ⚠️ Partial | SQLi filtered at nginx + Laravel WAF layers; **no query throttling or connection pooling**; DB port 3307 exposed to host |
| 3 | Email Servers | Email server flooding (SPAM) | 9 | ❌ N/A | No email server deployed — out of scope |
| 4 | Database Server Backup | Backup corruption | 10 | ❌ Not mitigated | Docker named volume `dbdata` exists but **no encryption, no integrity checks, no backup schedule** implemented |
| 5 | Administrator/Moderator's PC | Credential spoofing | 16 | ❌ Not mitigated | Endpoint security out of scope; app-level controls (session auth, 120-min timeout) are deterrent only |
| 6 | Customer's Personal Information | Data theft | 20 | ⚠️ Partial | HTTPS/HSTS enforced; CSP `connect-src 'self'` limits exfiltration; WAF blocks XSS; **`APP_DEBUG=true` in production leaks stack traces**; session driver uses `file` not `database` |
| 7 | Product Description Information | Content modification | 6 | ✅ Yes | `CheckRole` middleware gates vendor mutations; WAF blocks SQLi; CSP `frame-ancestors 'none'` prevents clickjacking |
| 8 | Order Records | Order record manipulation | 15 | ⚠️ Partial | `CheckRole` enforces boundaries; WAF blocks injection; CSRF active via `PreventRequestForgery`; **7/11 controllers lack input validation** — OrderController has none |
| 9 | Transaction Records | Transaction tampering | 15 | ⚠️ Partial | WAF inspects POST/PUT/PATCH bodies for injection; `reference_no` generated server-side; **no transaction-level integrity checks (e.g., signatures, checksums)** |
| 10 | Audit Logs (System) | Log deletion | 8 | ⚠️ Partial | Laravel file logs + WAF logs blocked requests; **logs are on the same filesystem — no immutable/append-only storage**; no centralized logging |
| 11 | Business Data | Data leakage | 15 | ⚠️ Partial | CSP restricts connect-src/frame-ancestors; HTTPS enforced; Docker network isolated; **no data-at-rest encryption**; `.env.example` leaks secrets |
| 12 | API Keys | API key modification | 15 | ⚠️ Partial | No API keys in source code; `.env` in `.gitignore`; **`.env.example` contains real `APP_KEY` and `DB_PASSWORD`** — anyone with repo access can see them |
| 13 | Admin Accounts | Privilege escalation | 15 | ⚠️ Partial | `CheckRole` middleware enforced; login rate-limited (5/min); session HttpOnly+Secure+SameSite; **no 2FA**; **no audit trail for role changes** |
| 14 | Moderator Accounts | Unauthorized privilege increase | 12 | ⚠️ Partial | `CheckRole` validates role on every request; WAF logs all blocked requests; **no per-action audit logging for privilege changes** |
| 15 | Customer Accounts | Privilege escalation | 15 | ⚠️ Partial | Session HttpOnly+Secure+SameSite=Lax; 120-min lifetime; password reset 60-min expiry + 60s throttle; **session driver is `file` (config defaults to `database` but `.env` overrides)** — risky in multi-container setup |
| 16 | Payment Gateway | Payment system DoS | 10 | ✅ Yes | Global rate limit 120r/m; API endpoints 30r/m; WAF blocks malformed bodies at nginx + Laravel |
| 17 | Dockerfile (System Configuration) | Configuration tampering | 8 | ✅ Yes | Runs as `www-data` non-root; permissions 775 on storage; secrets from compose env, not hardcoded |
| 18 | Docker Container | Container breakout | 10 | ✅ Yes | All containers run `--privileged=false` (verified); official base images; isolated `laravel` bridge network |
| 19 | Docker Image | Image poisoning | 10 | ✅ Yes | All images pinned to official tags: `nginx:alpine`, `php:8.4-fpm-alpine`, `mysql:8.0`, `node:20-alpine`; no third-party images |
| 20 | MySQL Database System | Data exposure | 15 | ⚠️ Partial | DB on isolated Docker network; port mapped to 3307 (non-standard); health check enabled; **root password `pass` is weak**; **port exposed to host** |
| 21 | Database Schema | Schema modification | 10 | ⚠️ Partial | Migrations version-controlled; **no per-table permissions**; **no schema change audit trail** |
| 22 | Application Source Code | Source code tampering | 10 | ⚠️ Partial | GitHub access control; `.env` in `.gitignore`; **`.env.example` commits real secrets to repo** — `APP_DEBUG=true`, `APP_KEY`, `DB_PASSWORD` all visible |
| 23 | Source Code Frameworks and Libraries | Supply chain attack | 10 | ⚠️ Partial | Dependencies pinned in `composer.json`/`package.json` with lock files committed; **`APP_DEBUG=true` in `.env.example`**; **no automated vulnerability scanning (Dependabot, etc.)** |
| 24 | Product Content Repository | Content data manipulation | 8 | ✅ Yes | Vendor product mutations require auth + `CheckRole`; WAF validates all input; CSP nonces prevent injected script execution |

---

## Summary

| Result | Count | Items |
|--------|:-----:|-------|
| ✅ Yes — Fully mitigated | 7 | Web Server, Product Description, Payment Gateway, Dockerfile, Docker Container, Docker Image, Product Content |
| ⚠️ Partial — Some controls exist | 12 | Database Server, Customer PII, Order Records, Transaction Records, Audit Logs, Business Data, API Keys, Admin Accounts, Moderator Accounts, Customer Accounts, MySQL DB, DB Schema, Source Code, Libraries |
| ❌ Not mitigated | 3 | Email Servers (N/A), DB Backup, Admin PC (out of scope) |

### Critical Gaps to Address

1. **`.env.example` leaks secrets** — real APP_KEY, DB_PASSWORD, APP_DEBUG=true committed to repo
2. **`APP_DEBUG=true` in production** — stack traces exposed on error
3. **Session driver forced to `file`** — config defaults to `database` but `.env` overrides
4. **Missing input validation in 6/10 controllers** — Product, Order, Dashboard, VendorHome, VendorOrder, Home
5. **No backup encryption/integrity** — `dbdata` volume has no protection
6. **Weak DB password** — `pass` in compose.yaml and .env.example
