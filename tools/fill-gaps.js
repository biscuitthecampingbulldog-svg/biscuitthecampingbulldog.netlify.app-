// For every area label missing vets or campgrounds, copy the 2 nearest existing listings of the
// missing type in the same state (within MAX miles), relabeled to that area with a distance note.
const fs=require('fs');const dry=process.argv.includes('--dry');const MAX=+(process.env.MAX||60);
const P='vet-data.js';let src=fs.readFileSync(P,'utf8');const w={};new Function('window',src)(w);const d=w.BISCUIT_VET_DATA;
const mi=(a,b)=>{const R=3959,t=x=>x*Math.PI/180,dLa=t(b[0]-a[0]),dLo=t(b[1]-a[1]);const h=Math.sin(dLa/2)**2+Math.cos(t(a[0]))*Math.cos(t(b[0]))*Math.sin(dLo/2)**2;return 2*R*Math.asin(Math.sqrt(h));};
const town=e=>{const m=String(e.where).split('·').pop().split(',');return (m.length>=2?m[m.length-2]:m[0]).replace(/\d{5}.*/,'').trim()||e.city;};
const q=s=>"'"+String(s??'').replace(/\\/g,'\\\\').replace(/'/g,"\\'").replace(/\r?\n/g,' ')+"'";
const out={DATA:{},CAMPS:{}},report=[],none=[];
for(const st of new Set([...Object.keys(d.DATA),...Object.keys(d.CAMPS)])){
  const V=d.DATA[st]||[],C=d.CAMPS[st]||[];
  const labels=new Set([...V,...C].map(e=>e.city));
  for(const lab of labels){
    for(const [have,miss,key] of [[V,C,'CAMPS'],[C,V,'DATA']]){
      if(miss.some(e=>e.city===lab)) continue;
      const mine=have.filter(e=>e.city===lab&&typeof e.lat==='number'); if(!mine.length) continue;
      const ctr=[mine.reduce((s,e)=>s+e.lat,0)/mine.length, mine.reduce((s,e)=>s+e.lng,0)/mine.length];
      const seen=new Set();const cands=miss.filter(e=>typeof e.lat==='number').map(e=>({e,m:mi(ctr,[e.lat,e.lng])})).sort((a,b)=>a.m-b.m)
        .filter(x=>{const k=x.e.name.toLowerCase()+'|'+String(x.e.phone).replace(/\D/g,'');if(seen.has(k))return false;seen.add(k);return true;});
      // prefer an ER/24-7 vet among the picks when one is within range
      let pick=cands.filter(x=>x.m<=MAX).slice(0,2);
      if(key==='DATA'){const er=cands.find(x=>x.m<=MAX&&/24\/7|Emergency/.test(x.e.tag));if(er&&!pick.includes(er))pick=[pick[0],er].filter(Boolean);}
      if(!pick.length){none.push(key+' '+st+' | '+lab+' (nearest '+(cands[0]?Math.round(cands[0].m)+' mi':'none')+')');continue;}
      for(const {e,m} of pick){
        const n=Math.max(1,Math.round(m));
        const where=n<=3?'close by':'about '+n+' miles away in '+town(e);
        const note=(key==='DATA'?'Nearest listed vet to this area, ':'Nearest listed campground to this area, ')+where+'. '+(e.tag==='Open 24/7'?'Open around the clock. ':'')+'Call ahead.';
        (out[key][st]=out[key][st]||[]).push({...e,city:lab,note});
        report.push(key+' '+st+' | '+lab+' <- '+e.name+' ('+n+' mi)');
      }
    }
  }
}
console.log('copies',report.length,'stillMissing',none.length);console.log(none.join('\n'));
if(dry){console.log(report.slice(0,30).join('\n'));process.exit(0);}
// insert each copy right after its source line (same section), keeping line endings
let L=src.split('\n');const sec=k=>L.findIndex(l=>new RegExp('^\\s*'+k+':\\s*\\{').test(l));
for(const key of ['DATA','CAMPS'])for(const st in out[key])for(const e of out[key][st]){
  const s0=sec(key),s1=key==='DATA'?sec('CAMPS'):L.length;
  const i=L.findIndex((l,ix)=>ix>s0&&ix<s1&&l.includes('name: '+q(e.name))&&l.includes('phone: '+q(e.phone))&&l.includes('lat: '+e.lat.toFixed(3)));
  if(i<0){console.log('NO SRC',e.name);continue;}
  const eol=L[i].endsWith('\r')?'\r':''; if(!L[i].replace(/\r$/,'').trimEnd().endsWith(',')) L[i]=L[i].replace(/\}(\s*\r?)$/,'},$1');
  const line='{ name: '+q(e.name)+', where: '+q(e.where)+', city: '+q(e.city)+', phone: '+q(e.phone)+', tag: '+q(e.tag)+', facts: ['+(e.facts||[]).map(q).join(', ')+'], note: '+q(e.note)+', source: '+q(e.source||'Biscuit researched this')+', zip: '+q(e.zip)+', lat: '+e.lat.toFixed(3)+', lng: '+e.lng.toFixed(3)+' },';
  L.splice(i+1,0,L[i].match(/^\s*/)[0]+line+eol);
}
fs.writeFileSync(P,L.join('\n'));console.log('saved');
