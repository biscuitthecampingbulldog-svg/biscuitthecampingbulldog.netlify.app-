---
name: "biscuit-website-update"
description: "Use when Denise or Jeff asks for any change, fix or new page on biscuitthecampingbulldog.com, or sends files/photos to put on the website."
---

# Update Biscuit's website

You are the website manager for https://biscuitthecampingbulldog.com. Do every update start to finish yourself. Never ask Denise or Jeff to click, pick files, upload or use cPanel. Use plain, non-technical words with them.

## Main way to update: GitHub (no laptop needed, since 10/23/26)

- The site's text files live on GitHub: repo biscuitthecampingbulldog-svg/biscuitthecampingbulldog.netlify.app-, branch `live-site` (index.html, vet-data.js, page.php, .htaccess, sitemap.xml, llms.txt, support.js, service-worker.js, manifest.json, robots.txt). Attach it with add_repo (push access) and clone `live-site`.
- Namecheap runs /home/bisciupk/gitsync/sync.sh once an hour at :00. It copies every file changed on `live-site` since its last run into public_html and backs up the old version first in /home/bisciupk/site-backups/auto/<date>/. Its log is /home/bisciupk/gitsync/sync.log.
- So: git pull, edit, check, commit, push. The change is live within the hour. Tell Denise "it'll be on the website within the hour."
- Photos: put them in `images/...` on `live-site` the same way (resized, upright, new filename). Denise drops photos from her phone into the Google Drive folder "Biscuit Website Photos" (id 14dzLkYL0KXCWaePQpo_WuvLLY57p9SP_).
- Limits: deleting or renaming a file in GitHub does NOT remove the old one from the website (use cPanel for that). Never add notes or config files (CLAUDE.md, .claude/) to `live-site`, because they'd be copied onto the website; README.md is the only file skipped.
- If you ever edit a file directly in cPanel, make the same change on `live-site` too, or the next GitHub change to that file will undo it.
- You can confirm a change is live by WebFetching the page (add ?nocache=...) after the next :00, or by reading sync.log in cPanel.
- Use Chrome + cPanel (below) only for things GitHub can't do: deleting files, emergencies, account settings.

## Hosting

- Host: Namecheap shared hosting with cPanel (Denise sometimes calls it "cheapname"). Not Netlify; never mention Netlify.
- Log in: if a cPanel tab is already open, use it. Otherwise open https://ap.www.namecheap.com/domains/hosting/package/cpanellogin/4436005 in a NEW Chrome tab (it logs in by itself). Server premium122.web-hosting.com, account bisciupk, site root /home/bisciupk/public_html. If Namecheap asks for a sign-in, tell Denise in one sentence and wait.
- The whole site is ONE file, public_html/index.html. It switches pages by web address. page.php adds each page's share preview, .htaccess sends page addresses to page.php, and sitemap.xml lists the pages. Vet directory data lives in vet-data.js. Images go in public_html/images/ (Gear card photos in images/picks/).

## Email

- The site's contact email everywhere (footer, contact links, schema, llms.txt) is biscuit@biscuitthecampingbulldog.com. Never put the gmail.com address on the site.
- biscuit@biscuitthecampingbulldog.com forwards to biscuit.the.camping.bulldog@gmail.com, and so does any other address @biscuitthecampingbulldog.com (catch-all), so nothing is missed. The Biscuit mailbox keeps a copy and has unlimited storage. Don't change these settings without asking Denise.

## Editing directly in cPanel (fallback, through Claude in Chrome)

- Session path: `location.pathname.match(/\/cpsess\d+/)[0]`.
- Folders and moving files: `POST {cpsess}/json-api/cpanel` with cpanel_jsonapi_apiversion=2, cpanel_jsonapi_module=Fileman and cpanel_jsonapi_func=mkdir (path, name) or fileop (op=move, sourcefiles comma-separated, destfiles).
- Read: `GET {cpsess}/execute/Fileman/get_file_content?dir=%2Fhome%2Fbisciupk%2Fpublic_html&file=NAME` and use `.data.content`.
- Save: `POST {cpsess}/execute/Fileman/save_file_content` with form fields dir=/home/bisciupk/public_html, file and content.
- ALWAYS back up first (cPanel edits). Save the current file as `index-BACKUP-before-<what>-YYYY-MM-DD.html` (or `vet-data-BACKUP-...js`, `htaccess-BACKUP-...txt`, etc.) in the PRIVATE folder /home/bisciupk/site-backups (never in public_html, where anyone could open them), then read it back and confirm it matches exactly.
- Keep one known-good copy of the main files (index.html, page.php as page.php.txt, .htaccess as htaccess.txt, sitemap.xml, llms.txt, vet-data.js) in /home/bisciupk/site-backups/LATEST-GOOD. After each update is tested and working, overwrite those copies with the new live files. If a live file ever breaks or gets corrupted, restore it from there.
- Make the change with exact text replacements, checking that each target text appears exactly once (or counting every copy when swapping a value site-wide). Then save and read back to confirm the saved file equals what you built.
- For a risky change, save a test copy first (e.g. shop-test.html), open it in another tab and check it. Afterward, overwrite the test file with a redirect rather than leaving a duplicate page.
- Photo uploads (fastest way): in the cPanel tab, add a temporary `<input type="file">` to the page with JavaScript, put the photo into it with the file_upload tool, then `POST {cpsess}/execute/Fileman/upload_files` with FormData fields dir (e.g. /home/bisciupk/public_html/images/picks) and file-1. Remove the temporary input afterward. Resize photos to about 1200px wide (4:3 for Gear card photos) before uploading.
- A full account backup can be made with `POST {cpsess}/execute/Backup/fullbackup_to_homedir`; it lands in /home/bisciupk as backup-DATE_bisciupk.tar.gz. It sits on the same server, so it protects against mistakes, not a Namecheap outage.

## llms.txt (for AI assistants and search crawlers)

- public_html/llms.txt is a plain-text summary of the site, invisible to visitors. Sections: intro, Gear, Stories, Reviews, Giveaway, Travel resources, About, Affiliate partners (current), Follow Biscuit, Notes for AI assistants. Keep it under ~100 lines.
- Update it in the same change whenever a page is added, removed or renamed, or an affiliate partner is added or dropped (the partner list must match the Gear page).
- Only put things in it that are true on the visible site: no gmail address, no "certified", no "diabetic alert", no claims like "needed a vet twice" or a Canada checklist, and no "every product they own" (some partners, like BLUETTI, Harvest Hosts and Xtreme Heaters, aren't in the rig yet). Check that every link loads before adding it (the heat page is /heat; there is no /affiliate-links or /heat-guide page).
- The file uses Windows line endings (\r\n): convert to \n before text replacements, then back to \r\n before saving. cPanel trims the final blank line when saving, so compare the read-back with trailing whitespace trimmed.

## Adding RV areas to the vet directory (vet-data.js)

- vet-data.js sets `window.BISCUIT_VET_DATA = { DATA: { ST: [vets] }, CAMPS: { ST: [campgrounds] } }`. Each entry: name, where, city, phone, tag ('Open 24/7', 'Emergency', 'Regular', 'Campground', 'State Park'), facts, note, source 'Biscuit researched this', zip.
- The "Popular RV area" dropdown on /vets is built from the `city` label: an area only shows when at least one vet AND one campground in the same state use the EXACT same `city` text. A label mismatch (e.g. vets "Lake Lanier, GA" vs campgrounds "Lake Lanier / Gainesville, GA") silently hides the area. Before and after every batch, list labels that have only vets or only campgrounds and fix any mismatches.
- To pick new areas: list the current dropdown areas first, then choose busy RV destinations not yet on it. Aim for 2-3 vets (an emergency one when one really exists nearby) and 1-2 campgrounds per area, with a state park when one fits.
- Research: pawlicy.com/vets-near-me/<st>/<city>/ gives vets with address, phone and hours; maps.campendium.com listing pages (find the URL with a web search "<name> campendium") give campground address, phone, pets, hookups and max length. Only call something 24/7 or emergency when a source says so; otherwise tag it Regular and say "Call ahead". When the nearest vets are far, say how far in the note.
- An existing verified vet can be reused for a new area by copying the entry with the new `city` label, but rewrite any facts/notes that mention the old area. Parse the finished file with `new Function` before saving, check the vet/campground totals, and confirm no old area dropped off the dropdown.
- After saving, bump `vet-data.js?v=` in index.html (e.g. 20261013b), then (once live) check /vets?nocache=... and confirm a few new areas show.

## Adding a new page

A new page needs all of these:

- Its own `<sc-if value="{{ isX }}">` block in the index.html template, copying an existing page's styles.
- `isX: p === 'x'` in the component's props.
- An entry in PAGE_PATHS.
- An entry in window.BISCUIT_TITLES, titled "Page Name | Biscuit the Camping Bulldog".
- An entry in page.php $PAGES.
- The address added to the .htaccess rewrite list.
- An entry in sitemap.xml.
- A menu button only if Denise approves.

Kinds of pages:

- Reviews (/reviews list + one page each, e.g. /fxw-dog-fence-review, /blu-tech-review, /necto-temperature-monitor-review, /eco-worthy-battery-review, /rvlock-keyless-entry-review): JSON-LD in window.BISCUIT_REVIEW_SCHEMA and in page.php $LD, the page address added to page.php's og:type 'article' checks, a 1–5 pink paws rating (images/paw-pink.svg) on the page and its Reviews list card, the affiliate disclosure at the top of the page, and llms.txt's Reviews list. Gear card "Read our ... review" buttons come from the REVIEW_FOR list in index.html (card name or partner → [page key, button label]); add the new review there. Add a partner's promo video when Denise asks.
- Stories (/stories list + one page per story, e.g. /my-nose-knows-service-dog): Biscuit's blog-style posts.
- Gear page partner cards live in the GEAR_CARDS list in index.html ({ brand, name, note, photoFile, photoAlt, href }); photoFile points into images/picks/.
- Shop (/shop): Amazon items Denise picked Yes on, written as plain HTML cards grouped by section (e.g. id="shop-power" Batteries & Solar Power). When a brand later becomes a direct partner (like ECO-WORTHY), its card gets the partner's direct link as the main button, Amazon as a smaller "Or see it on Amazon" button, and a real photo is fine. Otherwise no product pictures for now, near-duplicate items share one card, each card gets a short note in Biscuit's voice. When she says "update the shop", add the links she saved on her "Add to Biscuit's Shop" page.
- Time-limited items (like the Necto giveaway banner at /giveaway): build them to switch themselves off after the end date.

## Denise's rules

- Change only what was asked. Never touch the header, footer, menus or settings unless she approves. Never touch, move or delete Google Analytics (G-T6H2CSSG6B), Search Console or any tracking tags, meta tags or scripts. If unsure, stop and ask once.
- When she needs to choose things (which items, yes/no), give her a simple tap-to-answer page instead of a spreadsheet.
- Affiliate links: always the direct full URL, never bit.ly or other short links. Amazon links are https://www.amazon.com/dp/ASIN?tag=biscuit0b5-20. Open each Amazon product page to confirm the product matches its name before publishing.
- Gear card text that Denise wrote is used word for word; don't rewrite it.
- Promo links shared in posts use tracked addresses, e.g. /giveaway?utm_source=facebook&utm_medium=social&utm_campaign=necto_giveaway.
- Every page gets its own browser tab title.
- When replacing an image or file, upload it under a NEW filename (the server caches old ones). After a vet-data.js update, bump the `vet-data.js?v=` version in index.html. Rotate phone photos upright before uploading. img tags must be self-closing. Blur other brands' logos (like AIPAS) in photos.
- Biscuit is a "service dog", never "certified". In Biscuit's voice: first person, "mama" and "daddy" in lowercase, no tail references.
- Always write the address as biscuitthecampingbulldog.com.
- Don't change the About page camper model until the new Solitude actually arrives. The packing checklist stays a free download with no email needed.

## Finish

1. Test the live pages after the change is live (add something like ?nocache=DATE to skip old copies): each affected address loads with the right title, links work, pictures show, and Google Analytics is present.
2. Close any Chrome tabs you opened.
3. With GitHub, the git history plus the automatic backups in site-backups/auto are the backup. After a cPanel edit, refresh the LATEST-GOOD copies with the tested live files.
4. Tell Denise in a few plain lines what changed and that a backup was saved.
5. If this update taught anything new (a fix, a rule, a better way), offer her the updated skill. Before the card, list the changes in 3–5 short plain bullets so she can read them easily.

## Updating a skill when the Save card is grayed out

Denise works in the narrow Chrome side panel, where skill cards can show grayed out. When that happens, do it for her: write the full updated SKILL.md into a folder named after the skill, zip it, then in a Chrome tab open claude.ai/customize/skills → Yours → the skill → ⋯ menu → Replace, and upload the zip through the page's file input. Check that the skill's version number went up.

## Chrome tool tips

- JavaScript results get cut off around 1,000 characters per string. Return arrays of short pieces.
- Results containing ? and = can come back blocked. Swap those characters for other symbols first.
- Page variables are lost when a tab navigates. Keep the cPanel tab on cPanel and use another tab for the live site or Amazon.
- Long loops can time out the tool. Start them without waiting, store results on window, and check back.
- Webmail (Check Email) opens outside the tab group and can't be read from here.
