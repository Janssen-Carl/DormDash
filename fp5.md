# DormDash — Completed Risk Assessment Matrix

**Project:** DormDash (DASH)
**Document:** Information Assurance Security 1 — Final Project (FP5)
**Assessment Basis:** Full codebase audit of nginx configs, Docker configuration, Laravel middleware, controllers, session config, and secret management.

| # | Asset | Threat | Vulnerability | L | I | Risk Score | Security Control Mechanism | Control Type | Residual Risk |
|---|-------|--------|---------------|---|---|------------|---------------------------|-------------|---------------|
| 1 | Web Server | Web server overload | No rate limiting, outdated server software | 3 | 4 | **12** | Nginx `limit_req` zones: 5r/m login, 30r/m API, 120r/m global, 10r/m strict; `client_max_body_size 1M`; nginx WAF blocks malicious URIs/query strings/bots on HTTPS | Preventive | **6** |
| 2 | Database Server | Database DoS | Poor query throttling, outdated DB software, weak access control | 2 | 5 | **10** | App container on isolated Docker bridge network; nginx WAF filters SQLi patterns in query strings; DB port 3307 exposed to host (non-standard — partial mitigation) | Preventive | **5** |
| 3 | Email Servers | Email server flooding (SPAM) | Open relay, weak spam filters | 3 | 3 | **9** | No email server deployed — app does not send outbound email; out of scope | — | **9** |
| 4 | Database Server Backup | Backup corruption | Unencrypted backups, lack of integrity checks | 2 | 5 | **10** | Docker named volume `dbdata` persists database data across restarts; **no backup schedule, no encryption, no integrity checks implemented** | Corrective | **5** |
| 5 | Administrator/Moderator's PC | Credential spoofing | Weak credentials, lack of 2FA, malware endpoint | 4 | 4 | **16** | Out of scope (endpoint/personnel security); application enforces session-based auth with 120-min lifetime and HttpOnly+Secure cookies | Deterrent | **12** |
| 6 | Customer's Personal Information | Data theft | Weak credentials, improper access controls | 4 | 5 | **20** | HTTPS enforced (HSTS `max-age=31536000` + redirect); `SESSION_SECURE_COOKIE=true`; nginx WAF blocks XSS patterns in query strings; **CSP headers commented out**; **Laravel WAF middleware disabled** — XSS exfiltration defense reduced | Preventive | **10** |
| 7 | Product Description Information | Content modification | Unauthorized access to DB, weak authentication | 2 | 3 | **6** | Vendor product mutations gated by `CheckRole` middleware; nginx WAF blocks SQLi in URIs/query strings; `frame-ancestors` CSP commented out (no clickjacking protection via CSP) | Preventive | **3** |
| 8 | Order Records | Order record manipulation | Insecure APIs, lack of transaction validation, weak permissions | 3 | 5 | **15** | `CheckRole` enforces vendor vs. customer boundaries; nginx WAF blocks malicious input; CSRF protection via Laravel default `VerifyCsrfToken`; `SameSite=Lax` cookie attribute; **7/11 controllers rely on nginx WAF as sole injection defense** | Preventive | **8** |
| 9 | Transaction Records | Transaction tampering | Insufficient input validation, weak DB controls | 3 | 5 | **15** | nginx WAF inspects POST/PUT/PATCH bodies for SQLi and injection patterns; order `reference_no` generated server-side per transaction (`REF` + `uniqid`); **no transaction-level integrity checks (signatures, checksums)** | Preventive | **8** |
| 10 | Audit Logs (System) | Log deletion | Log stored on same system, no immutable logging, weak access control | 2 | 4 | **8** | Laravel file-based logging to `storage/logs/`; nginx WAF logs all blocked requests with IP, URI, UA metadata; nginx access logs enabled; **logs on same filesystem — no immutable/append-only storage** | Detective | **4** |
| 11 | Business Data | Data leakage | Misconfigured rules, unencrypted data, insider threats | 3 | 5 | **15** | HTTPS enforced; Docker network isolation; **CSP connect-src/frame-ancestors commented out**; **no data-at-rest encryption**; `.env.example` leaks DB credentials to repo | Preventive | **8** |
| 12 | API Keys | API key modification | Hardcoded keys, improper handling | 3 | 5 | **15** | No API keys in source code; DB credentials from `compose.yaml` env vars; `.env` in `.gitignore`; **`.env.example` contains real `APP_KEY` and `DB_PASSWORD` committed to repo** | Preventive | **8** |
| 13 | Admin Accounts | Privilege escalation | Excessive privileges, unpatched software, weak authentication | 3 | 5 | **15** | `CheckRole` enforces role-based access; session HttpOnly+Secure+SameSite; login rate-limited (5r/min WAF + 60r/min Laravel throttle); **no 2FA**; **no audit trail for role assignment changes** | Preventive | **8** |
| 14 | Moderator Accounts | Unauthorized privilege increase | Weak password, insufficient audit, insecure admin panel | 3 | 4 | **12** | `CheckRole` validates `user->role` on every guarded route; nginx WAF logs blocked requests; roles gated within same app (no separate admin panel); **no per-action privilege change audit logging** | Preventive | **6** |
| 15 | Customer Accounts | Privilege escalation | Insecure session management, weak authentication | 3 | 5 | **15** | Session driver set to `file` (config defaults to `database` but `.env` overrides); 120-min lifetime; `http_only=true`, `same_site=lax`; password reset expires 60 min with 60s throttle; `SESSION_SECURE_COOKIE=true` | Preventive | **8** |
| 16 | Payment Gateway | Payment system DoS | No rate limiting, unprotected endpoints | 2 | 5 | **10** | Global `limit_req` 120 req/min per IP on HTTPS; API endpoints at 30 req/min; nginx WAF blocks malformed POST bodies; **no rate limiting on HTTP (port 80)** | Preventive | **4** |
| 17 | Dockerfile (System Configuration) | Configuration tampering | Hardcoded secrets, weak file permissions | 2 | 4 | **8** | Dockerfile runs as `www-data` non-root; permissions 775 on storage; secrets from compose env (not hardcoded); **compose.yaml user directive is commented out — app container runs as root by default** | Preventive | **4** |
| 18 | Docker Container | Container breakout | Privileged containers, unpatched images, vulnerable kernel | 2 | 5 | **10** | All containers run without `--privileged`; official minimal images (`php:8.4-fpm-alpine`, `nginx:alpine`, `mysql:8.0`, `node:20-alpine`); isolated `laravel` bridge network | Preventive | **5** |
| 19 | Docker Image | Image poisoning | Unverified images, weak signing | 2 | 5 | **10** | All images pinned to official tags; no third-party/community images used; **no image signing or vulnerability scanning** | Preventive | **4** |
| 20 | MySQL Database System | Data exposure | Compromised system integrity | 3 | 5 | **15** | DB port mapped to host as 3307 (non-standard — partially mitigates automated scans targeting 3306); health check validates connectivity; **root password `pass` is weak**; **port exposed to host**; **single flat network — no service segmentation** | Preventive | **8** |
| 21 | Database Schema | Schema modification | Insufficient DB permissions, weak audit controls | 2 | 5 | **10** | Migrations version-controlled; DB user scoped to single database; no direct external DB access; **no per-table permissions**; **no schema change audit trail** | Preventive | **4** |
| 22 | Application Source Code | Source code tampering | Public repository exposure, weak access controls | 2 | 5 | **10** | Repository on GitHub with access control; `.env` in `.gitignore`; **`.env.example` contains real secrets (APP_KEY, DB_PASSWORD) committed to repo**; **`.env` file present in project root** | Preventive | **6** |
| 23 | Source Code Frameworks and Libraries | Supply chain attack | Unverified packages, outdated dependencies | 2 | 5 | **10** | Dependencies pinned to semver ranges in `composer.json` + `package.json`; lock files committed for reproducible builds; **no automated vulnerability scanning (Dependabot, etc.)**; `APP_DEBUG=true` should be `false` | Preventive | **5** |
| 24 | Product Content Repository | Content data manipulation | Weak authentication, no integrity verification | 2 | 4 | **8** | Vendor product mutations require auth + `CheckRole`; nginx WAF validates input; **CSP script-src nonces commented out** | Preventive | **4** |

*L = Likelihood (1–5), I = Impact (1–5), Risk Score = L × I (1–25)*

---

## Residual Risk Summary

| Risk Level | Score Range | Count | Assets |
|------------|-------------|-------|--------|
| **Critical** | 16–25 | 0 | — |
| **High** | 10–15 | 1 | Admin/Moderator PC (12) |
| **Medium** | 6–9 | 8 | Customer PII (10), Order Records (8), Transaction Records (8), Business Data (8), API Keys (8), Admin Accounts (8), Customer Accounts (8), MySQL DB (8) |
| **Low** | 1–5 | 15 | Web Server (6), DB Server (5), DB Backup (5), Product Description (3), Audit Logs (4), Moderator Accounts (6), Payment Gateway (4), Dockerfile (4), Docker Container (5), Docker Image (4), DB Schema (4), Source Code (6), Libraries (5), Product Content (4), Email Servers (9) |

---

## Critical Security Gaps Uncovered by Audit

| # | Finding | Severity | Impact on Residual Risk |
|---|---------|----------|------------------------|
| 1 | **CSP headers commented out** in both `default.conf:10` and `ssl.conf:43` | **HIGH** | Increases data exfiltration risk for Customer PII, Business Data, Product Content |
| 2 | **Laravel WAF middleware disabled** — `bootstrap/app.php:33` commented out | **HIGH** | Removes application-layer injection defense; relies solely on nginx WAF |
| 3 | **App container runs as root** — `user:` directive commented out in `compose.yaml:7` | **HIGH** | Container breakout grants host root access |
| 4 | **`.env.example` leaks real secrets** — APP_KEY, DB_PASSWORD, APP_DEBUG=true committed | **CRITICAL** | Anyone with repo access has production credentials |
| 5 | **Weak DB root password `pass`** — hardcoded in `.env:17` and `compose.yaml:49` | **CRITICAL** | Database accessible with trivial credentials |
| 6 | **No global rate limiting on HTTP (port 80)** — `waf_global` zone missing in `default.conf` | **MEDIUM** | HTTP flood bypasses rate limiting |
| 7 | **No backup strategy** — no cron, dump scripts, or backup service configured | **MEDIUM** | Complete data loss on volume corruption |
| 8 | **`.env` file present in project root** — may be included in Docker build context | **HIGH** | Secrets leak into container images |
| 9 | **MySQL port 3306 exposed to host on 3307** + AI port 5000 + Vite 5173 exposed | **MEDIUM** | Unnecessary attack surface |
| 10 | **No HTTPS redirect from HTTP (port 80) `default.conf`** — only `ssl.conf` has redirect | **MEDIUM** | Traffic can be served in plaintext on port 80 |

---

## Recommendations

| Priority | Action | Effort | Risk Reduction |
|----------|--------|--------|----------------|
| P1 | Replace `.env.example` secrets with placeholders; rotate APP_KEY and DB_PASSWORD | 5 min | Eliminates credential exposure in repo |
| P2 | Set `APP_DEBUG=false` in `.env` and `.env.example` | 1 min | Prevents stack trace leakage |
| P3 | Uncomment CSP headers in both nginx configs | 5 min | Restores exfiltration protection for PII/business data |
| P4 | Uncomment Laravel WAF middleware in `bootstrap/app.php` | 1 min | Restores application-layer injection defense |
| P5 | Add `user: "${UID}:${GID}"` to app service in `compose.yaml` | 1 min | App container no longer runs as root |
| P6 | Implement database backup with encryption | 1–2 hrs | Data recovery capability |
| P7 | Remove unnecessary port exposures (3307, 5000, 5173) | 5 min | Reduces network attack surface |
| P8 | Add global rate limiting to HTTP `default.conf` | 1 min | Consistent DoS protection across both ports |
| P9 | Add `->validate()` calls to controllers missing input validation | 1–2 hrs | Defense-in-depth for injection attacks |
| P10 | Change `SESSION_DRIVER` from `file` to `database` | 5 min | Better session isolation in multi-container deployment |
