# Biscuit the Camping Bulldog — Project Notes

Personal/hobby site for Denise Myers' brand "Biscuit the Camping Bulldog." Denise is not
technical — keep instructions to her simple and concrete, and prefer doing the work
yourself over asking her to do technical steps when there's any other way. See
`working-with-denise` skill guidance if available in the session.

## What this repository actually is

This is **not a normal application repo with a build step**. There is no package.json,
no build tooling, and (as of this writing) no CI configured. It's a lightweight home for:

- A backup/staging copy of the live static site's source (HTML/CSS/images) — but only on
  *some* branches (see below), not the one GitHub currently treats as default.
- These project notes (this file), which function as the persistent memory for whichever
  Claude session picks up work on Biscuit's site/brand next.

**Read the "Branches" section below before assuming any given checkout has the real site
in it — several branches only contain this notes file.**

## Branches — what's actually on each one (checked Aug 30 2026)

The repo's history forked early (common ancestor: "Add project permissions allowlist for
routine website work") into several purpose-specific branches that were never merged back
together. As of this writing there is no `main`/`master` — GitHub's default branch is
`claude/website-picture-approvals-bh09kl`.

| Branch | Contents | Purpose |
|---|---|---|
| `claude/website-picture-approvals-bh09kl` **(GitHub default/HEAD)** | `CLAUDE.md`, `.claude/settings.json` only — **no site source** | Where these project notes live and where PR #1 (PawPlanner investigation) was merged. Despite the name, it does not currently contain any picture-approval work or site files. |
| `claude/biscuit-website-impl-pvzmuz` | `index.html`, `thank-you.html`, `images/` — **the current/newest site build** | Implements the "Biscuit Website" mockup Denise built in Claude Design (claude.ai/design). Denise exported the finished design as a static site zip (`biscuitthecampingbulldogNEWSITE20260830.zip`) and attached it in chat on Aug 30 2026 since this session couldn't reach the Claude Design MCP (no design-system login in a non-interactive/remote session). This build fixes every bug listed in the July 24 audit below and adds FXW + RVLock as new affiliate partners. See "Aug 30 2026 — site rebuild" below for details. **This supersedes `website-review-feedback-bw0nmh` as the source to work from going forward** — start here, not there. |
| `claude/website-review-feedback-bw0nmh` | `index.html`, `thank-you.html`, `images/` (logos, hero photos, adventure tiles, favicons) | Older backed-up site source, now stale as of the Aug 30 rebuild above — kept for history only. |
| `claude/pinterest-integration-5j8hjz` | `index.html` + `images/products/*.jpg` (~25 Veritas Vans product photos) | Working branch for the Pinterest pin-image project (see PawPlanner section below). Stalled on a Zapier monthly quota limit as of Aug 16 2026. |
| `claude/pawplanner-inquiry-khhrib` | Same `CLAUDE.md` content as the default branch | Source branch for the now-merged PR #1; safe to ignore going forward. |

**Important, from earlier investigation:** live deploys are pushed to Netlify via direct
upload/API (`deploy_source: "api"`, `commit_ref: null`), **NOT built from this git repo**.
This repo's git history and the live site's actual current content are disconnected —
pushing commits here does not deploy anything, and no branch here is guaranteed to match
what's actually live. Do not assume any branch (including `website-review-feedback-bw0nmh`)
reflects the current live page without confirming first (see tooling gap below).

## Development workflow / conventions

- Branch naming: `claude/<short-slug>-<random-suffix>` (matches the auto-generated branch
  names Claude Code on the web creates per session). Continue on an existing relevant
  branch rather than creating a fresh throwaway one whenever prior context matters — as
  with this file's own history.
- No install/build/test commands exist yet (no package.json). If real front-end tooling
  is ever added, document the actual commands here so future sessions don't have to
  rediscover them.
- `.claude/settings.json` pre-allows read-only/local git and common site-asset commands
  (image conversion/optimization, npm/node/netlify CLI, `git log`/`diff`/`add`/`commit`)
  and requires confirmation for anything destructive or deploy-affecting (`git push`,
  `git reset --hard`, `rm`, `netlify deploy`, `netlify env:*`, piping curl into a shell).
  Keep that split when editing the file — don't silently widen the auto-allow list.
- Because live deploys are manual/API-based and disconnected from this repo (see above),
  **never assume a commit here is live**. Before making user-facing claims about "the
  site now shows X," confirm against a real downloaded copy of the current deploy (see
  tooling gap below), not just this repo's working tree.
- This file is the continuity mechanism between sessions — when you learn something a
  future session would otherwise have to rediscover (a stalled integration, a verified
  URL, an open question for Denise), write it here rather than leaving it only in chat.

## Hosting setup

- **Live site:** https://biscuitthecampingbulldog.com (custom domain)
- **Netlify project name:** `biscuitthecampingbulldog`
- **Netlify site ID:** `f727ec6b-3062-47b0-a60d-12dd006b8c2d`
- **Netlify team:** "Biscuit the Camping Bulldog" (team ID `6a3a948ae90aff104f36f7a3`)
- **Branch subdomain:** http://main--biscuitthecampingbulldog.netlify.app

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

## Aug 30 2026 — site rebuild (implements the Claude Design mockup)

Denise designed the site visually in Claude Design (project "Biscuit Website", canvas file
`Biscuit Website.dc.html`) and asked for it to be implemented here. This session's
`claude_design`/`DesignSync` MCP access requires a `/design-login` that only works in an
interactive session, so it couldn't be pulled directly — Denise instead exported the
finished design as a static site and attached the zip
(`biscuitthecampingbulldogNEWSITE20260830.zip`) in chat. That export **is** the
implementation (real, complete `index.html` + images, not a raw canvas file), so it was
brought into this repo close to as-is on `claude/biscuit-website-impl-pvzmuz` rather than
re-built from scratch.

What changed vs. the old backup on `website-review-feedback-bw0nmh`:
- All bugs from the July 24 2026 audit (below) are now fixed in this build.
- Two new affiliate partners added: **FXW** (Rollick dog playpen, Amazon link) and
  **RVLock** (keyless RV door lock).
- `thank-you.html` was not included in the export (the form posts to `/thank-you.html`
  via Netlify Forms) — Claude authored a matching branded one from the same color/font
  variables so the form has somewhere to land.
- The export embeds the FXW/RVLock partner logos as inline base64 `data:` URIs directly
  in `index.html` rather than referencing separate image files, which is why
  `index.html` is ~340KB. A `images/logos/` folder of loose `.png`/`.b64` files existed
  in the zip too but isn't referenced anywhere in the page — left out of the repo as
  unused working files. Worth a follow-up performance pass (extract those into real
  `<img src>` files) if page-weight ever becomes a concern, but not done here to avoid
  touching working markup without being asked.
- **Not yet deployed live** — per the hosting note below, deploys are manual/API-based
  and disconnected from this repo, and pushing a full site replacement to the live
  business site is exactly the kind of outward-facing, hard-to-reverse action that needs
  Denise's go-ahead first, not an assumption. Ask her before deploying.

## Known site structure (per user-provided audit, July 24 2026 — superseded by the Aug 30
rebuild above; kept for history)

- Nav: Home / About / Adventures / Picks
- Hero section with a "Follow The Pack" button
- "Picks" section = affiliate/partner links (Veritas Vans, Starlink, Liquified RV, Necto,
  Blue Technology, Chewy) — this is likely what the user calls the "Partners" section/tab
- "Life in the Smokies" tile section (6 tiles; 2 — "Connected Anywhere" and "RV Road
  Trips" — were missing photos as of the audit)
- Known bugs from that audit — **fixed in the Aug 30 rebuild above**: "Follow The
  Pack"/"Follow Us" buttons point to a social-follow section that doesn't exist on the
  page; affiliate disclosure sentence is cut off mid-word; headline has a double space
  ("Biscuit  the Camping Bulldog"); no email signup; link-preview image is the small
  round logo instead of a wide photo.

## Partners / affiliate links — verified correct URLs

- **Happy Howl:** `https://happyhowl.com/biscuit` (NOT happyhowell.com — user
  misspoke/mistyped this once; verified correct via her actual email thread with
  Ashley at thehappyhowl.com). Gives 40% off first order, contact is Ashley
  (ashley@thehappyhowl.com).

## Biscuit's "Board of Directors" — advisor personas (set up Aug 25 2026)

Denise asked for a standing "board of directors" of specialists to help grow Biscuit's
page/brand (goal: grow traffic and start actually making money off it — affiliate income,
partnerships — while she and Biscuit live in/travel around the Smoky Mountains). This
is NOT a piece of software or a separate app — it's a standing instruction for **any**
Claude session working on this project: when Denise asks something that touches growth,
money, content, or partnerships, answer it by reasoning through the relevant board
member's lens(es) below and give her ONE plain, combined answer — she should never have
to pick which "advisor" to talk to. Just talk to her normally; use this as the thinking
structure behind the answer, not a gimmick to perform out loud.

**Board members:**
1. **Social Media & Content Strategist** — posting cadence, what actually performs for a
   pet/travel/lifestyle page, platform-specific best practices, growth tactics.
2. **Affiliate & Partnerships Advisor** — which affiliate/partner programs are worth
   pursuing, negotiating/following up, FTC disclosure compliance, prioritizing effort
   toward partners likely to pay off (see Partners section above).
3. **Finance & Budget Advisor** — keep recommendations ROI-aware; Denise has very little
   free cash right now (affiliate income is minimal to date), so default to free/cheap
   options and only recommend paid tools when the payoff clearly justifies it, and say so
   explicitly when a recommendation costs money.
4. **Brand & Storytelling Advisor** — keeps Biscuit's voice/personality consistent
   (warm, fun, camping-lifestyle) across the website and every platform; photo/caption
   quality.
5. **Travel & Content-Calendar Advisor** — ties actual trips/seasons (Smokies life,
   travel over time) into a content calendar — turns real travel into ready-made posts
   instead of two separate efforts.

Not a fixed/closed list — add a specialist lens here if a new recurring need shows up
(e.g. legal/trademark, SEO). Keep the same principle: Denise talks to one assistant, the
"board" is just how the advice gets reasoned through, not a new interface for her to learn.

## Social media cross-posting ("PawPlanner") — Aug 23 2026 investigation

Denise asked about "PawPlanner" — her name (given by an earlier Claude session) for a
tool to auto-spread a Facebook post to all her other social platforms. She couldn't find
it after it reportedly ate hours of tokens over Aug 2–16. Investigated across this repo's
branches, all her Claude sessions (incl. token/cost usage), scheduled Routines, and
published Artifacts. Findings:

- **No finished "PawPlanner" exists anywhere.** Not committed to any branch of this repo,
  not a saved Routine, not a published Artifact. It does not appear to have ever been
  completed — nothing was lost, it just never got finished/saved.
- **The actual 2-day token spend (session "Pinterest integration", Aug 2–16, ~$11,
  27M+ cached tokens) was a different, narrower project:** hosting ~20 Veritas Vans
  product photos in this repo (branch `claude/pinterest-integration-5j8hjz`) and queuing
  18 Pinterest pin-boards via Zapier. It stalled because the Zapier account hit its
  monthly task quota (104/100 used) and, as of the last retry (Aug 16), was still
  blocked/unconfirmed. This is Pinterest-only — not a multi-platform cross-poster.
- **Zapier connection status (checked live):** Facebook Pages is connected (account
  `nascardreamin@gmail.com`). Instagram for Business is **not** connected — zero
  connections. So even a Facebook→Instagram piece was never actually wired up.
- **Platform reality check via Zapier:** Facebook Pages and Instagram for Business both
  support auto-posting (read+write actions available). Twitter/X and TikTok do **not**
  have a reliable auto-post action available through Zapier (platform API restrictions) —
  a true "post once, goes everywhere including X/TikTok" tool would need a dedicated
  service built for that (e.g. Buffer/Later/Metricool — one-time paid signup, click
  "Connect" per platform, no custom automation to build/maintain).
- **Open item, not yet resolved:** whether those 18 queued Pinterest pins ever actually
  posted after the Aug 16 retry is unconfirmed — worth checking Pinterest directly next
  time this comes up.
- **Related but separate:** a different session, "PawPlanner setup and vet locations"
  (Claude Code CLI on Denise's own computer, started Aug 25 ~9pm, bridge tag
  `remote-control-sdk`), exists and was, as of Aug 25, unreachable ("computer_unreachable")
  — likely because her computer shut down/reset around then. Not investigated further
  from here (different session, no read access into it from this one). If Denise
  mentions content from that session (e.g. "the board of directors" was originally asked
  about there too, or "vet locations"), it should resume once her computer/Claude app is
  back online and reconnects — don't assume it's lost.

**Decision (Aug 25 2026) — going with Option B (one hub app), $0/month version:**
Denise confirmed she wants Facebook + Instagram + TikTok + X covered, but has very
little budget right now (affiliate income is minimal so far). Plan, no monthly cost:
1. **Facebook → Instagram:** use Facebook's own free, built-in Page crossposting
   feature (Meta does this natively — no Zapier/Buffer needed for this leg). Requires
   Instagram to be set as a Business account (free toggle in Instagram's own settings).
2. **Facebook + TikTok + X:** connect these 3 (not Instagram — already covered by step 1)
   to Buffer's **free plan** (3 channels, $0/mo, no card required). Denise composes/posts
   from Buffer for these three instead of posting natively on Facebook — Buffer sends the
   same post to all three at once. (Facebook itself can be posted in Buffer too, or kept
   as the trigger for step 1's native crosspost — either way Instagram is not a paid
   Buffer channel.)
3. Caveat: X started charging ~$0.20 per post that contains a link (Feb 2026 change) —
   small per-post cost, not a subscription; everything else is free.
4. Upgrade path stays open: if/when affiliate income grows, Buffer's paid Essentials
   plan ($5–6/channel/mo) adds more scheduling headroom — no rush, no lock-in from
   starting free.
- Status: waiting on Denise to (a) sign up for Buffer free plan and connect
  Facebook/TikTok/X, (b) turn on Instagram Business + Facebook's native crossposting.
  Both are one-time sign-in/toggle steps only she can do (OAuth logins). Once connected,
  future sessions should verify the Zapier/Buffer side is wired correctly if asked.

## In-progress / outstanding work

- User wants a new tab added to the Partners/Picks section for Happy Howl (logo, their
  brand colors, linking to happyhowl.com/biscuit). Still blocked on a Happy Howl logo
  image from the user (external fetch to happyhowl.com is also blocked, so it can't be
  pulled automatically) — the Aug 30 rebuild (see above) did not add this, it wasn't part
  of that design export.
- User separately wants two specific pictures placed on the site with descriptions —
  never received in this conversation despite repeated requests. Do not assume any
  picture placement has happened without the actual files.
- **Open question for Denise:** should `claude/biscuit-website-impl-pvzmuz` (the Aug 30
  rebuild) actually be pushed live to Netlify, and should it become the GitHub default
  branch? Both need her explicit yes before acting — don't deploy or repoint the default
  branch on assumption.
