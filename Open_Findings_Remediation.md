# Open Findings Requiring Remediation

## DormDash (DASH) — Security Audit Remediation Plan

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

## Priority Matrix

| Priority | Finding | Effort | Impact |
|----------|---------|--------|--------|
| P1 | `.env.example` secrets leak | 5 min | Rotating keys prevents credential-based attacks |
| P2 | `APP_DEBUG=true` | 1 min | Prevents stack trace leakage in production |
| P3 | Missing input validation (7 controllers) | 1–2 hrs | Defense-in-depth for injection attacks |
| P4 | Exposed DB + AI ports | 5 min | Reduces network attack surface |
| P5 | Session driver override | 5 min | Improves session isolation |
| P6 | Password rotation policy | 30 min | Long-term account security |
| P7 | `.env` comment formatting | 30 sec | Correctness/maintainability |
