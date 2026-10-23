# Biscuit the Camping Bulldog — project notes (updated Oct 23 2026)

Denise is not technical: use plain words, and do the work yourself instead of asking her
to do technical steps.

## Where the real website lives
- Live site: https://biscuitthecampingbulldog.com, hosted on **Namecheap** (cPanel account
  `bisciupk`, server premium122.web-hosting.com). Netlify is no longer used.
- The current site's text files are on the **`live-site`** branch of this repo
  (index.html, vet-data.js, page.php, .htaccess, sitemap.xml, llms.txt, support.js,
  service-worker.js, manifest.json, robots.txt). Its README explains the setup.
- Namecheap runs `/home/bisciupk/gitsync/sync.sh` hourly at :00. It copies files changed
  on `live-site` since the last run into public_html, backing up old versions to
  /home/bisciupk/site-backups/auto/. So: to update the site, commit to `live-site`
  and it goes live within the hour. Never add notes/config files to `live-site`
  (they would be copied to the website); README.md is the only exception.
- Photos can also be added under images/ on `live-site`. Denise drops new website photos
  into the Google Drive folder "Biscuit Website Photos".

## Other branches
Everything else here (this branch, website-review-feedback, pinterest-integration,
pawplanner-inquiry) is old Netlify-era material from Aug 2026 and does not match the live
site. Don't edit the site from those branches.
