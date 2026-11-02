// node copy-listing.js jobs.json  -- each job: {sec, srcSt, name, dstSt, city, note}
const fs=require('fs');const jobs=JSON.parse(fs.readFileSync(process.argv[2]));
let L=fs.readFileSync('vet-data.js','utf8').split('\n');const w={};new Function('window',L.join('\n'))(w);const d=w.BISCUIT_VET_DATA;
const q=s=>"'"+String(s??'').replace(/\\/g,'\\\\').replace(/'/g,"\\'")+"'";
for(const j of jobs){
  const e=(d[j.sec][j.srcSt]||[]).find(x=>x.name===j.name&&typeof x.lat==='number'); if(!e){console.log('NOT FOUND',j.name);continue;}
  if((d[j.sec][j.dstSt]||[]).some(x=>x.city===j.city&&x.name===e.name)){console.log('already',j.name,j.city);continue;}
  const line='{ name: '+q(e.name)+', where: '+q(e.where)+', city: '+q(j.city)+', phone: '+q(e.phone)+', tag: '+q(e.tag)+', facts: ['+(e.facts||[]).map(q).join(', ')+'], note: '+q(j.note)+', source: '+q(e.source||'Biscuit researched this')+', zip: '+q(e.zip)+', lat: '+e.lat.toFixed(3)+', lng: '+e.lng.toFixed(3)+' },';
  const s0=L.findIndex(l=>new RegExp('^\\s*'+j.sec+':\\s*\\{').test(l));
  const a=L.findIndex((l,i)=>i>s0&&new RegExp('^\\s*'+j.dstSt+':\\s*\\[').test(l));
  if(a<0){console.log('NO STATE BLOCK',j.dstSt);continue;}
  const nxt=L[a+1]; const eol=nxt.endsWith('\r')?'\r':''; const ind=(nxt.match(/^\s*/)||[''])[0];
  L.splice(a+1,0,ind+line+eol); L=L; console.log('copied',e.name,'->',j.city);
}
fs.writeFileSync('vet-data.js',L.join('\n'));const w2={};new Function('window',fs.readFileSync('vet-data.js','utf8'))(w2);console.log('parses ok');
