# DormDash Final Project Risk Assessment

**Project:** DormDash (DASH)
**Document:** Information Assurance Security 1 — Final Project
**Date:** May 2026

---

## Risk Assessment Matrix

| # | Asset | Threat | Vulnerability | L | I | Risk Score | Security Control Mechanism | Control Type | Residual Risk |
|---|-------|--------|---------------|---|---|------------|---------------------------|-------------|---------------|
| 1 | Web Server | Web server overload | No rate limiting, outdated server software | 3 | 4 | **12** | Nginx `limit_req` zones (5r/m login, 30r/m API, 120r/m global); WAF middleware; nginx `client_max_body_size` 1M | Preventive | **6** |
| 2 | Database Server | Database DoS | Poor query throttling, outdated DB software, weak access control | 2 | 5 | **10** | App container on isolated Docker bridge network; WAF filters SQLi at nginx + Laravel layers; DB exposed to host on port 3307 (non-standard) | Preventive | **5** |
| 3 | Email Servers | Email server flooding (SPAM) | Open relay, weak spam filters | 3 | 3 | **9** | No email server in scope — app does not send outbound email; future consideration | — | **9** |
| 4 | Database Server Backup | Backup corruption | Unencrypted backups, lack of integrity checks | 2 | 5 | **10** | Database volume persisted via Docker named volume `dbdata`; backup integrity managed at infrastructure level | Corrective | **5** |
| 5 | Administrator/Moderator's PC | Credential spoofing | Weak credentials, lack of 2FA, malware endpoint | 4 | 4 | **16** | Out-of-scope (endpoint/personnel security); application enforces session-based auth with 120-min lifetime | Deterrent | **12** |
| 6 | Customer's Personal Information | Data theft | Weak credentials, improper access controls | 4 | 5 | **20** | HTTPS enforced (HSTS + redirect); `SESSION_SECURE_COOKIE=true` (bug: missing space before `#` comment in `.env` but still truthy); CSP `connect-src 'self'` limits exfiltration; WAF blocks XSS stealing `document.cookie` | Preventive | **8** |
| 7 | Product Description Information | Content modification | Unauthorized access to DB, weak authentication | 2 | 3 | **6** | Vendor product mutations gated by `CheckRole` middleware; WAF blocks SQLi; CSP `frame-ancestors 'none'` prevents clickjacking | Preventive | **2** |
| 8 | Order Records | Order record manipulation | Insecure APIs, lack of transaction validation, weak permissions | 3 | 5 | **15** | `CheckRole` enforces vendor vs. customer boundaries; WAF blocks malicious input; CSRF protection via framework default (`PreventRequestForgery`); `SameSite=Lax` cookie attribute | Preventive | **6** |
| 9 | Transaction Records | Transaction tampering | Insufficient input validation, weak DB controls | 3 | 5 | **15** | WAF inspects all POST/PUT/PATCH bodies for SQLi and injection patterns; order `reference_no` generated server-side per transaction | Preventive | **6** |
| 10 | Audit Logs (System) | Log deletion | Log stored on same system, no immutable logging, weak access control | 2 | 4 | **8** | Laravel file-based logging to `storage/logs/`; WAF logs all blocked requests with IP, URI, UA metadata; nginx access logs enabled | Detective | **4** |
| 11 | Business Data | Data leakage | Misconfigured rules, unencrypted data, insider threats | 3 | 5 | **15** | CSP restricts `default-src 'self'`, `connect-src 'self'`, `frame-ancestors 'none'`; HTTPS enforced; app in isolated Docker network | Preventive | **6** |
| 12 | API Keys | API key modification | Hardcoded keys, improper handling | 3 | 5 | **15** | No API keys in source code; DB credentials from `compose.yaml` env vars; `.env` in `.gitignore`; `.env.example` leaks real `APP_KEY` and `DB_PASSWORD` to repo — should be rotated | Preventive | **6** |
| 13 | Admin Accounts | Privilege escalation | Excessive privileges, unpatched software, weak authentication | 3 | 5 | **15** | `CheckRole` enforces role-based access; session `HttpOnly + Secure + SameSite`; WAF login rate limiting (5 req/min) | Preventive | **6** |
| 14 | Moderator Accounts | Unauthorized privilege increase | Weak password, insufficient audit, insecure admin panel | 3 | 4 | **12** | `CheckRole` validates `user->role` on every guarded route; WAF logs all requests; roles gated within same app (no separate admin panel) | Preventive | **5** |
| 15 | Customer Accounts | Privilege escalation | Insecure session management, weak authentication | 3 | 5 | **15** | Session driver set to `file` (config defaults to `database` but `.env` overrides); 120-min lifetime; `http_only=true`, `same_site=lax`; password reset expires 60 min with 60s throttle | Preventive | **8** |
| 16 | Payment Gateway | Payment system DoS | No rate limiting, unprotected endpoints | 2 | 5 | **10** | Global `limit_req` 120 req/min per IP; API endpoints at 30 req/min; WAF blocks malformed POST bodies | Preventive | **4** |
| 17 | Dockerfile (System Configuration) | Configuration tampering | Hardcoded secrets, weak file permissions | 2 | 4 | **8** | Dockerfile runs as `www-data` non-root; permissions 775 on storage; secrets from compose env (not hardcoded) | Preventive | **3** |
| 18 | Docker Container | Container breakout | Privileged containers, unpatched images, vulnerable kernel | 2 | 5 | **10** | All containers run without `--privileged`; official minimal images (`php:8.4-fpm-alpine`, `nginx:alpine`, `mysql:8.0`, `node:20-alpine`); isolated `laravel` bridge network | Preventive | **4** |
| 19 | Docker Image | Image poisoning | Unverified images, weak signing | 2 | 5 | **10** | All images pinned to official tags; no third-party/community images used | Preventive | **4** |
| 20 | MySQL Database System | Data exposure | Compromised system integrity | 3 | 5 | **15** | DB port mapped to host as 3307 (partially mitigates automated scans targeting 3306); health check validates connectivity; root password in compose env | Preventive | **8** |
| 21 | Database Schema | Schema modification | Insufficient DB permissions, weak audit controls | 2 | 5 | **10** | Migrations version-controlled; DB user scoped to single database; no direct external DB access | Preventive | **4** |
| 22 | Application Source Code | Source code tampering | Public repository exposure, weak access controls | 2 | 5 | **10** | Repository on GitHub with access control; `.env` in `.gitignore`; `.env.example` contains real secrets (APP_KEY, DB_PASSWORD) — should be replaced with placeholders | Preventive | **6** |
| 23 | Source Code Frameworks and Libraries | Supply chain attack | Unverified packages, outdated dependencies | 2 | 5 | **10** | Dependencies pinned to semver ranges in `composer.json` + `package.json`; lock files committed for reproducible builds; `APP_DEBUG=true` in `.env.example` should be `false` | Preventive | **5** |
| 24 | Product Content Repository | Content data manipulation | Weak authentication, no integrity verification | 2 | 4 | **8** | Vendor product mutations require auth + `CheckRole`; WAF validates all input; CSP script-src nonces prevent content injection | Preventive | **3** |

*L = Likelihood (1–5), I = Impact (1–5), Risk Score = L × I (1–25)*

---

## Risk Summary

| Risk Level | Score Range | Count | Assets |
|------------|-------------|-------|--------|
| **Critical** | 16–25 | 1 | Customer's Personal Information (20) |
| **High** | 10–15 | 16 | Admin/Mod/Customer Accounts, Order/Transaction Records, Business Data, API Keys, Payment Gateway, DB Server, DB Backup, Docker Container/Image, MySQL DB, DB Schema, Source Code, Libraries |
| **Medium** | 6–9 | 5 | Web Server, Email Servers, Audit Logs, Dockerfile, Product Content |
| **Low** | 1–5 | 2 | Product Description Info |

---

## Key Security Controls Implemented

| Control | Layer | Status | Notes |
|---------|-------|--------|-------|
| **nginx WAF** (`waf.conf`) | Network/Edge | ✅ Implemented | SQLi, XSS, path traversal, cmd injection, bad bots, rate limiting |
| **Laravel WAF Middleware** | Application | ✅ Implemented | Deep input inspection on all request inputs, headers, URI, raw body |
| **CSP Headers** (`ContentSecurityPolicy`) | Application | ✅ Implemented | Nonce + strict-dynamic; connect-src limits data exfiltration |
| **Rate Limiting** | Network | ✅ Implemented | Login 5/min, API 30/min, global 120/min |
| **Role-Based Access** (`CheckRole`) | Application | ✅ Implemented | Vendor vs. customer authorization on all guarded routes |
| **HTTPS + HSTS** | Network | ✅ Implemented | HSTS max-age=31536000, HTTP→HTTPS redirect |
| **Security Headers** | Network | ✅ Implemented | X-Content-Type-Options, Referrer-Policy, Cross-Origin-Resource-Policy |
| **CSRF Protection** | Application | ✅ Implemented | Framework default `PreventRequestForgery` middleware |
| **Docker Network Isolation** | Infrastructure | ✅ Implemented | Isolated `laravel` bridge network; DB port 3307 exposed to host (risk) |
| **Secure Sessions** | Application | ⚠️ Partial | HttpOnly + SameSite=Lax + Secure active; **driver overridden to `file`** in `.env` (config defaults to `database`) |
| **Input Validation** | Application | ⚠️ Partial | Only 4/11 controllers have validation rules; 7 controllers lack server-side validation |
| **Secret Management** | Infrastructure | ⚠️ Risk | `.env` in `.gitignore` ✅, but `.env.example` contains real `APP_KEY` and `DB_PASSWORD` ❌ |

---

## Open Findings Requiring Remediation

| # | Finding | Severity | Recommendation |
|---|---------|----------|----------------|
| 1 | `.env.example` contains real secrets (APP_KEY, DB_PASSWORD) | **High** | Replace with placeholder values and rotate the real APP_KEY + DB_PASSWORD |
| 2 | `APP_DEBUG=true` in `.env.example` | **High** | Set to `false` for production |
| 3 | `SESSION_DRIVER=file` overrides config default of `database` | **Medium** | Change to `database` for better session isolation in containerized deployment |
| 4 | `SESSION_SECURE_COOKIE=true# remove this in prod` missing space before `#` | **Low** | Add space so `#` is parsed as comment: `true # remove this in prod` |
| 5 | Input validation missing in 7/11 controllers | **Medium** | Add `->validate()` calls to ProductController, OrderController, DashboardController, VendorHomeController, VendorOrderController |
| 6 | DB port 3307 exposed to host | **Medium** | Remove ports mapping from `db` service in `compose.yaml` if host access not needed |
| 7 | AI service port 5000 exposed to host | **Medium** | Remove ports mapping if host access not needed |
| 8 | No password rotation/expiration policy | **Low** | Consider implementing periodic password change enforcement |

---

## Residual Risk Assessment

After applying the implemented security controls, **Critical** risk items (Customer PII) have been reduced from score 20 to 8. **High** items have been reduced to medium or low across the board.

The most impactful remaining risks are:
- **`.env.example` secret leakage** — immediately actionable, would bring source code risk from 6→4
- **Session driver using files** rather than database in containerized deployment — elevates session-related risks
- **Missing input validation in 7/11 controllers** — WAF provides compensating control but defense-in-depth is incomplete

Overall residual risk posture is **Low-to-Medium** with no items in the critical range after controls, contingent on remediating the open findings above.
