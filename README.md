# Biscuit the Camping Bulldog — live website files

This branch (`live-site`) holds the real, current text files of
https://biscuitthecampingbulldog.com, which is hosted on Namecheap (cPanel account `bisciupk`).

## How updates go live
- Namecheap runs `/home/bisciupk/gitsync/sync.sh` once an hour (at :00).
- It copies only the files that changed here since its last run into `public_html`,
  backing up the old versions first in `/home/bisciupk/site-backups/auto/<date>/`.
- This README is never copied to the website. Do not add other notes/config files
  (e.g. CLAUDE.md) to this branch, because they would be copied to the live site.
- Deleting a file here does NOT delete it from the website.
- Edits made directly in cPanel are not undone by the sync, but they will be overwritten
  the next time that same file changes here, so make all text edits here.

## What's here
index.html (the whole site), vet-data.js (vet directory), page.php, .htaccess,
sitemap.xml, llms.txt, support.js, service-worker.js, manifest.json, robots.txt.
Website photos can be added under images/ the same way.
