# Biscuit the Camping Bulldog — Project Notes

Personal/hobby site for Denise Myers' brand "Biscuit the Camping Bulldog." Owner is not
technical — keep instructions to her simple and concrete, and prefer doing the work
yourself over asking her to do technical steps when there's any other way.

## Hosting setup

- **Live site:** https://biscuitthecampingbulldog.com (custom domain)
- **Netlify project name:** `biscuitthecampingbulldog`
- **Netlify site ID:** `f727ec6b-3062-47b0-a60d-12dd006b8c2d`
- **Netlify team:** "Biscuit the Camping Bulldog" (team ID `6a3a948ae90aff104f36f7a3`)
- **Branch subdomain:** http://main--biscuitthecampingbulldog.netlify.app

**Important:** live deploys are pushed to Netlify via direct upload/API
(`deploy_source: "api"`, `commit_ref: null`), NOT built from this git repo. This repo's
git history and the live site's actual content are currently disconnected — pushing
commits here does not deploy anything. Do not assume `git log` reflects what's live.

**Known tooling gap:** as of Aug 2026, none of the available Netlify MCP tools
(`netlify-project-services-reader/updater`, `netlify-deploy-services-reader/updater`,
etc.) can download the actual deployed file contents (no "get deploy files" operation
exists). Outbound WebFetch/curl to the live domain is also blocked by this environment's
network policy. So there is currently **no way to read the live page's actual HTML/CSS
from within a session** — the only way to get it is to have the user download it
manually: Netlify dashboard → the site → Deploys → latest deploy → Download, then have
her attach the zip in chat. Don't attempt to blindly reconstruct/redeploy the whole site
from a guess; the blast radius (breaking the live business site) is too high without
seeing the real source first.

## Newsletter signups

- Netlify Forms is enabled. Form name `biscuit-newsletter`, form ID
  `6a6d0589f7097d0008dc928c`.
- Check via `netlify-project-services-updater` → `manage-form-submissions` →
  `get-submissions` (siteId + formId above).
- Dashboard shortcut: https://app.netlify.com/projects/biscuitthecampingbulldog/forms
- Two known test signups to exclude from "new" counts: `dmyers0608@gmail.com`,
  `nascardreamin@gmail.com`.
- A weekly Routine (trigger) checks this and reports in chat. Scheduled Routines in this
  org currently can't be granted MCP connectors, so if the Netlify tools aren't loaded
  when it fires, it should say so plainly rather than error.

## Known site structure (per user-provided audit, July 24 2026 — may be stale, confirm against actual downloaded files before editing)

- Nav: Home / About / Adventures / Picks
- Hero section with a "Follow The Pack" button
- "Picks" section = affiliate/partner links (Veritas Vans, Starlink, Liquified RV, Necto,
  Blue Technology, Chewy) — this is likely what the user calls the "Partners" section/tab
- "Life in the Smokies" tile section (6 tiles; 2 — "Connected Anywhere" and "RV Road
  Trips" — were missing photos as of the audit)
- Known bugs from that audit, not yet confirmed fixed: "Follow The Pack"/"Follow Us"
  buttons point to a social-follow section that doesn't exist on the page; affiliate
  disclosure sentence is cut off mid-word; headline has a double space
  ("Biscuit  the Camping Bulldog"); no email signup; link-preview image is the small
  round logo instead of a wide photo.

## Partners / affiliate links — verified correct URLs

- **Happy Howl:** `https://happyhowl.com/biscuit` (NOT happyhowell.com — user
  misspoke/mistyped this once; verified correct via her actual email thread with
  Ashley at thehappyhowl.com). Gives 40% off first order, contact is Ashley
  (ashley@thehappyhowl.com).

## In-progress / outstanding work

- User wants a new tab added to the Partners/Picks section for Happy Howl (logo, their
  brand colors, linking to happyhowl.com/biscuit). Blocked on: (1) the actual site
  source files (see tooling gap above), (2) a Happy Howl logo image from the user
  (external fetch to happyhowl.com is also blocked, so it can't be pulled
  automatically).
- User separately wants two specific pictures placed on the site with descriptions —
  never received in this conversation despite repeated requests. Do not assume any
  picture placement has happened without the actual files.
