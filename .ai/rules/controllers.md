---
paths:
  - app/Http/Controllers/AdminSessionController.php
---

# Controllers

## Week-attendance uses ISO week param YYYY-Www
Week-based attendance history uses an ISO week query param `?week=YYYY-Www` (format `o-\WW`). Any other week/date-range query in this app must reuse this convention: parse with `Carbon::parse($week.'-1')->startOfWeek()`, reject non-matching values by falling back to the current week, and filter `scheduled_at` with `whereBetween([startOfWeek, endOfWeek])`. `scheduled_at` is indexed for this.

## Concurrent open sessions selected via ?session=
Multiple sessions may be open at once. admin/attendance accepts ?session={id} to choose which open session to monitor; invalid/non-open ids fall back to the first open session by scheduled_at (latest). Admin view adds a "Pantau Sesi" dropdown and per-row "Pantau" links when >1 session is open.

## Session materials managed via per-session modal
Session materials are managed from a per-session "Materi" button in the session list (opens an Alpine modal: list + add via admin.materials.store, publish via PATCH admin.session-materials.publish, delete via DELETE admin.session-materials.destroy). No inline "Tambah Materi Sesi" form on the attendance page. publishSessionMaterial/destroySessionMaterial abort 404 when club_session_id is null (subject materials live under SubjectController instead).
