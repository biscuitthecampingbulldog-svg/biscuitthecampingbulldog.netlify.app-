#!/usr/bin/env node
// Biscuit vet directory: add one or more checked batch files to vet-data.js, giving every
// new listing a map spot (lat/lng). Usage:
//   node add-vet-batch.js <path/to/vet-data.js> <batch1.json> [batch2.json ...] [--dry]
// Needs the npm package "zipcodes" (npm i zipcodes@8) next to this script or globally.
// Map spot order: 1) lat/lng already in the batch entry, 2) same ZIP already on the site,
// 3) zipcodes package (5-digit ZIP center), 4) zip3.js rough 3-digit ZIP center.
// Anything still without a spot is NOT added and is listed under "noSpot".
const fs = require('fs'), path = require('path');
const args = process.argv.slice(2); const dry = args.includes('--dry');
const [R, ...batches] = args.filter(a => a !== '--dry');
if (!R || !batches.length) { console.error('usage: add-vet-batch.js vet-data.js batch.json [...] [--dry]'); process.exit(2); }
let zipLib = null; try { zipLib = require('zipcodes'); } catch (e) { try { zipLib = require(path.join(__dirname, 'node_modules', 'zipcodes')); } catch (e2) {} }
const src = fs.readFileSync(R, 'utf8');
const load = s => { const w = {}; new Function('window', s)(w); return w.BISCUIT_VET_DATA; };
const cur = load(src);
const zip3File = path.join(path.dirname(R), 'zip3.js');
let ZIP3 = {}; if (fs.existsSync(zip3File)) { const w = {}; new Function('window', fs.readFileSync(zip3File, 'utf8'))(w); ZIP3 = w.BISCUIT_ZIP3 || {}; }
const areas = d => { const a = new Set(); for (const st in d.DATA) for (const v of d.DATA[st]) for (const c of (d.CAMPS[st] || [])) if (v.city === c.city) a.add(st + '|' + v.city); return a; };
const allCities = d => { const a = new Set(); for (const k of ['DATA', 'CAMPS']) for (const st in d[k]) for (const e of d[k][st]) a.add(st + '|' + e.city); return a; };
const count = d => ['DATA', 'CAMPS'].map(k => Object.values(d[k]).reduce((n, a) => n + a.length, 0));
const before = areas(cur), beforeCities = allCities(cur), cb = count(cur);
// existing spots by ZIP, existing names and phones per state/section
const zipSpot = {}; const seenName = new Set(); const seenPhone = new Set();
const digits = p => String(p || '').replace(/\D/g, '').slice(-10);
// toll-free numbers are shared reservation lines (e.g. a state's parks line), so they never count as a duplicate
const sharedLine = ph => /^8(00|33|44|55|66|77|88)/.test(ph);
for (const k of ['DATA', 'CAMPS']) for (const st in cur[k]) for (const e of cur[k][st]) {
  if (e.zip && typeof e.lat === 'number' && !zipSpot[e.zip]) zipSpot[e.zip] = [e.lat, e.lng];
  seenName.add(k + '|' + st + '|' + e.name.toLowerCase().trim());
  if (digits(e.phone).length === 10 && !sharedLine(digits(e.phone))) seenPhone.add(k + '|' + digits(e.phone));
}
const r3 = x => Math.round(x * 1000) / 1000;
// sanity check: a spot must be within 450 miles of the middle of that state's existing listings
const stMid = {}; for (const k of ['DATA', 'CAMPS']) for (const st in cur[k]) for (const e of cur[k][st]) if (typeof e.lat === 'number') { const m = stMid[st] || (stMid[st] = [0, 0, 0]); m[0] += e.lat; m[1] += e.lng; m[2]++; }
const miles = (a, b) => { const R = 3959, t = x => x * Math.PI / 180, dLa = t(b[0] - a[0]), dLo = t(b[1] - a[1]); const h = Math.sin(dLa / 2) ** 2 + Math.cos(t(a[0])) * Math.cos(t(b[0])) * Math.sin(dLo / 2) ** 2; return 2 * R * Math.asin(Math.sqrt(h)); };
const fits = (st, sp) => { const m = stMid[st]; return !m || miles([m[0] / m[2], m[1] / m[2]], sp) < 450; };
function spotFor(e, st) {
  const cand = [];
  if (typeof e.lat === 'number' && typeof e.lng === 'number') cand.push([r3(e.lat), r3(e.lng), 'batch']);
  const z = String(e.zip || '').slice(0, 5);
  if (zipSpot[z]) cand.push([zipSpot[z][0], zipSpot[z][1], 'site']);
  if (zipLib && /^\d{5}$/.test(z)) { const l = zipLib.lookup(z); if (l && l.latitude && (!l.state || l.state === st)) cand.push([r3(l.latitude), r3(l.longitude), 'zip']); }
  const t = ZIP3[z.slice(0, 3)]; if (t) cand.push([t[0], t[1], 'zip3']);
  return cand.find(c => fits(st, c)) || null;
}
const lines = src.split('\n');
const sec = k => lines.findIndex(l => new RegExp('^\\s*' + k + ':\\s*\\{').test(l));
if (sec('DATA') < 0 || sec('CAMPS') < 0) throw new Error('DATA/CAMPS sections not found');
const q = s => "'" + String(s ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/\r?\n/g, ' ') + "'";
const fmt = (e, sp) => '{ name: ' + q(e.name) + ', where: ' + q(e.where) + ', city: ' + q(e.city) + ', phone: ' + q(e.phone) + ', tag: ' + q(e.tag) + ', facts: [' + (e.facts || []).map(q).join(', ') + '], note: ' + q(e.note) + ', source: ' + q(e.source || 'Biscuit researched this') + ', zip: ' + q(e.zip) + ', lat: ' + sp[0].toFixed(3) + ', lng: ' + sp[1].toFixed(3) + ' },';
const added = [], skipped = [], noSpot = [], spotSource = { batch: 0, site: 0, zip: 0, zip3: 0 };
for (const f of batches) {
  const b = JSON.parse(fs.readFileSync(f, 'utf8'));
  for (const k of ['DATA', 'CAMPS']) for (const st in (b[k] || {})) for (const e of b[k][st]) {
    const idN = k + '|' + st + '|' + e.name.toLowerCase().trim(), ph = digits(e.phone);
    if (seenName.has(idN)) { skipped.push(st + ' ' + e.name + ' (same name already there)'); continue; }
    if (ph.length === 10 && !sharedLine(ph) && seenPhone.has(k + '|' + ph)) { skipped.push(st + ' ' + e.name + ' (same phone already there)'); continue; }
    const sp = spotFor(e, st); if (!sp) { noSpot.push(st + ' ' + e.name + ' zip ' + e.zip); continue; }
    spotSource[sp[2]]++;
    let li = -1; const start = sec(k), end = k === 'DATA' ? sec('CAMPS') : lines.length;
    for (let i = start; i < end; i++) if (new RegExp('^\\s*' + st + ':\\s*\\[').test(lines[i])) { li = i; break; }
    if (li < 0) { // new state block: add right after the section opener
      lines.splice(start + 1, 0, st + ': [' + (lines[start].endsWith('\r') ? '\r' : ''), ']' + ',' + (lines[start].endsWith('\r') ? '\r' : ''));
      li = start + 1;
    }
    let j = li + 1; while (!/^\s*\],?\s*\r?$/.test(lines[j])) j++;
    const cr = lines[j].endsWith('\r') ? '\r' : '';
    const pv = j - 1, pvT = lines[pv].replace(/\r$/, '');
    if (pv > li && !/,\s*$/.test(pvT)) lines[pv] = pvT.replace(/\s*$/, '') + ',' + (lines[pv].endsWith('\r') ? '\r' : '');
    lines.splice(j, 0, fmt(e, sp) + cr);
    seenName.add(idN); if (ph.length === 10) seenPhone.add(k + '|' + ph);
    if (!zipSpot[e.zip]) zipSpot[e.zip] = [sp[0], sp[1]];
    added.push(st + ' ' + k + ' | ' + e.city + ' | ' + e.name);
  }
}
const out = lines.join('\n');
const nw = load(out); // throws if the file no longer parses
let missingSpots = 0; for (const k of ['DATA', 'CAMPS']) for (const st in nw[k]) for (const e of nw[k][st]) if (typeof e.lat !== 'number' || typeof e.lng !== 'number') missingSpots++;
const after = areas(nw), ca = count(nw);
const dropped = [...before].filter(a => !after.has(a));
const newCityLabels = [...allCities(nw)].filter(c => !beforeCities.has(c));
const vetOnlyOrCampOnly = newCityLabels.filter(c => !after.has(c));
const ok = !dropped.length && !missingSpots && ca[0] >= cb[0] && ca[1] >= cb[1];
if (!dry && ok && added.length) fs.writeFileSync(R, out);
console.log(JSON.stringify({ saved: !dry && ok && added.length > 0, ok, before: { vets: cb[0], camps: cb[1], fullAreas: before.size }, after: { vets: ca[0], camps: ca[1], fullAreas: after.size }, newAreaLabels: newCityLabels, labelsMissingVetOrCamp: vetOnlyOrCampOnly, droppedAreas: dropped, listingsWithoutSpot: missingSpots, spotSource, added: added.length, skipped, noSpot }, null, 1));
process.exit(ok ? 0 : 1);
