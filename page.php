<?php
/* Biscuit the Camping Bulldog: per-page share previews (added 2026-10-02).
   Every page address is still served from index.html. This file only swaps in that
   page's own title, description, picture and address, so links shared on Facebook,
   Instagram, texts and search engines show the right preview for each page.
   To add a new page: add one line to $PAGES below, and add the address to .htaccess. */
$SITE = 'https://biscuitthecampingbulldog.com';
$NAME = 'Biscuit the Camping Bulldog';
$IMG  = $SITE . '/images/';
$PAGES = array(
  '/' => array("Biscuit the Camping Bulldog \u{2014} vet-first travel for every pet", "Emergency and regular vets in all 50 states, pet-friendly campgrounds, a pet heat guide and honest RV gear reviews from Biscuit, a full-time RV service dog.", 'biscuit-hero-smokies.jpg'),
  '/gear' => array("Biscuit's Road-Tested Gear | Biscuit the Camping Bulldog", "The RV and pet gear Biscuit's family actually uses on the road, with discount codes from Happy Howl, Necto, RVLock, CRAVOT, Prime RV, Blu Tech and more.", 'picks/pick-blu-tech.jpg'),
  '/vets' => array('Find a Vet Near You | Biscuit the Camping Bulldog', "Find emergency 24-hour vets, regular vets and pet-friendly campgrounds in all 50 states and top RV destinations, checked by hand. Call or get directions fast.", 'biscuit-hero-smokies.jpg'),
  '/heat' => array('Pet Heat Safety Guide | Biscuit the Camping Bulldog', "How hot is too hot for your dog or cat? A temperature-by-temperature pet heat guide for RV travelers, plus how fast a parked car or camper heats up inside.", 'biscuit-about.jpg'),
  '/pet-papers' => array('Pet Travel Papers Checklist | Biscuit the Camping Bulldog', "Keep your pet's microchip, rabies and vet info on one offline card, plus a checklist for taking your dog into Canada and back to the U.S. Saved on your device.", 'biscuit-about-2.jpg'),
  '/reviews' => array('Gear Reviews | Biscuit the Camping Bulldog', "Honest, first-hand reviews of the RV and pet gear Biscuit uses every day, rated in pink paws: dog fence, water filter, temperature monitor and keyless lock.", 'review-fxw-fence-upright.jpg'),
  '/fxw-dog-fence-review' => array('FXW Dog Fence Review | Biscuit the Camping Bulldog', "Our FXW portable dog fence review: how it gives Biscuit her own yard at every campground, the 10 minute setup, what we like and what to know before you buy.", 'review-fxw-fence-upright.jpg'),
  '/blu-tech-review' => array('Blu Technology Review | Biscuit the Camping Bulldog', 'The leak alarm, water filter and softener that keep our camper water clean and our floor dry: what each one does and why we use it, with a video from Biscuit.', 'picks/pick-blu-tech.jpg'),
  '/necto-temperature-monitor-review' => array('Necto Temperature Monitor Review | Biscuit the Camping Bulldog', 'Why Biscuit stays home safely in the camper: the Necto cell-connected temperature monitor, 2 years of monitoring included, alerts straight to our phones.', 'picks/pick-necto.jpg'),
  '/rvlock-keyless-entry-review' => array('RVLock Keyless Door Lock Review | Biscuit the Camping Bulldog', 'The RVLock keyless RV door lock: unlock the camper from a keychain remote, no more fumbling for keys with your hands full.', 'picks/pick-rvlock-2.jpg'),
  '/victron-battery-monitor-review' => array('Victron Battery Monitor Review | Biscuit the Camping Bulldog', 'How the Victron BMV-712 Smart battery monitor showed us our battery and solar during 5 cloudy days off-grid at Raystown Lake. Specs and 5 paws from Biscuit.', 'biscuit-about.jpg'),
  '/prime-rv-tank-treatment-review' => array('Prime RV Tank Treatment Review | Biscuit the Camping Bulldog', 'Blue tablets left our RV gray tank smelling. Liquified (now Prime RV) tank treatment fixed it in two tankfuls. What we use, prices and 5 paws from Biscuit.', 'picks/pick-prime-rv-2.jpg'),
  '/shop' => array('Shop What We Use | Biscuit the Camping Bulldog', "Over 85 things our full-time RV family actually bought and uses, from dog gear, solar and RV parts to plumbing, camp kitchen and campsite favorites on Amazon.", 'biscuit-hero-smokies.jpg'),
  '/giveaway' => array('Necto Giveaway | Biscuit the Camping Bulldog', "Win a Necto pet temperature monitor (\$199 value)! Join Biscuit's Pack by Dec 14, 2026 to enter. Winner drawn Dec 15. US residents 18+, no purchase needed.", 'picks/pick-necto.jpg'),
  '/stories' => array("Biscuit's Stories | Biscuit the Camping Bulldog", 'Stories from the road with Biscuit, a service dog and English Bulldog living full-time in an RV in the Great Smoky Mountains.', 'adv-service-dog-biscuit.jpg'),
  '/my-nose-knows-service-dog' => array('My Nose Knows: What This Service Dog Actually Does | Biscuit the Camping Bulldog', "People see the vest and think I'm playing dress-up. I am not. What a working service dog actually does, from Biscuit herself.", 'adv-service-dog-biscuit.jpg'),
  '/about' => array('About Biscuit | Biscuit the Camping Bulldog', "Meet Biscuit, an English Bulldog service dog who lives full-time in an RV in the Great Smoky Mountains with her mama Denise and daddy Jeffrey. Big heart!", 'adv-roadtrip.jpg'),
  '/install' => array("Get Biscuit's App | Biscuit the Camping Bulldog", "Add Biscuit's free vet finder to your phone's home screen. No app store and no account, and the vet list still works at campgrounds with no cell signal.", 'biscuit-hero-smokies.jpg'),
  '/terms' => array('Terms of Use | Biscuit the Camping Bulldog', "Terms of use and copyright for biscuitthecampingbulldog.com, the vet-first pet travel website from Biscuit, an English Bulldog service dog living in an RV.", 'biscuit-hero-smokies.jpg')
);
$uri = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
$path = rtrim($uri, '/');
if ($path === '' || $path === '/index.html') $path = '/';
if ($path === '/page.php') $path = isset($_GET['p']) ? rtrim($_GET['p'], '/') : '/';
if ($path === '') $path = '/';
/* One page per state for search engines (added 2026-10-26): /vets/tennessee, /vets/new-york ...
   Each gets its own title, description and address, plus a list of that state's vets and
   campgrounds (read straight from vet-data.js, so new areas show up by themselves). */
$STATES = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
$STATE_KEY = '';
if (preg_match('#^/vets/([a-z-]+)$#', $path, $sm)) {
  foreach ($STATES as $k => $nm) {
    if (trim(preg_replace('/[^a-z]+/', '-', strtolower($nm)), '-') === $sm[1]) { $STATE_KEY = $k; break; }
  }
  if ($STATE_KEY === '') { $path = '/vets'; }
}
/* Pull one state's listings out of vet-data.js (one listing per line). */
function biscuit_state_listings($key) {
  $js = @file_get_contents(__DIR__ . '/vet-data.js');
  if ($js === false) return array(array(), array());
  $js = str_replace("\r\n", "\n", $js);
  $parts = explode("\nCAMPS: {", $js, 2);
  $out = array();
  foreach ($parts as $part) {
    $list = array();
    if (preg_match('#\n' . $key . ': \[\n(.*?)\n\]#s', $part, $m)) {
      foreach (explode("\n", $m[1]) as $line) {
        $row = array();
        foreach (array('name', 'where', 'phone', 'tag') as $f) {
          if (preg_match("#\b$f: '((?:\\\\.|[^'\\\\])*)'#", $line, $fm)) $row[$f] = stripslashes($fm[1]);
        }
        if (!empty($row['name'])) $list[strtolower(preg_replace('/[^0-9]/', '', isset($row['phone']) ? $row['phone'] : '') ?: $row['name'])] = $row;
      }
    }
    $out[] = array_values($list);
  }
  return array(isset($out[0]) ? $out[0] : array(), isset($out[1]) ? $out[1] : array());
}
if ($STATE_KEY !== '') {
  $nm = $STATES[$STATE_KEY];
  list($SV, $SC) = biscuit_state_listings($STATE_KEY);
  $er = 0; foreach ($SV as $v) { if (isset($v['tag']) && ($v['tag'] === 'Open 24/7' || $v['tag'] === 'Emergency')) $er++; }
  $PAGES[$path] = array("$nm Vets & Pet-Friendly Campgrounds | Biscuit the Camping Bulldog",
    "$er emergency (24-hour) vets, " . (count($SV) - $er) . " regular vets and " . count($SC) . " pet-friendly campgrounds and state parks in $nm, checked by hand for RV travelers. Call or get directions in one tap.",
    'biscuit-hero-smokies.jpg');
}
$html = @file_get_contents(__DIR__ . '/index.html');
if ($html === false) { http_response_code(500); exit('Site file missing.'); }
if (isset($PAGES[$path])) {
  $p = $PAGES[$path];
  $e = function ($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); };
  $title = $e($p[0]); $desc = $e($p[1]); $img = $e($IMG . $p[2]); $url = $e($SITE . ($path === '/' ? '/' : $path));
  $set = function ($html, $attr, $key, $val) {
    $re = '#(<meta\s+' . $attr . '="' . preg_quote($key, '#') . '"\s+content=")[^"]*(")#';
    return preg_replace_callback($re, function ($m) use ($val) { return $m[1] . $val . $m[2]; }, $html, 1);
  };
  $html = preg_replace_callback('#<title>.*?</title>#s', function ($m) use ($title) { return '<title>' . $title . '</title>'; }, $html, 1);
  $html = $set($html, 'name', 'description', $desc);
  $ogType = ($path === '/my-nose-knows-service-dog' || $path === '/fxw-dog-fence-review' || $path === '/blu-tech-review' || $path === '/necto-temperature-monitor-review' || $path === '/rvlock-keyless-entry-review' || $path === '/victron-battery-monitor-review' || $path === '/prime-rv-tank-treatment-review') ? 'article' : 'website';
  $html = $set($html, 'property', 'og:type', $ogType);
  $html = $set($html, 'property', 'og:title', $title);
  $html = $set($html, 'property', 'og:description', $desc);
  $html = $set($html, 'property', 'og:image', $img);
  $html = $set($html, 'property', 'og:url', $url);
  $html = $set($html, 'name', 'twitter:title', $title);
  $html = $set($html, 'name', 'twitter:description', $desc);
  $html = $set($html, 'name', 'twitter:image', $img);
  $head = "\n<title>$title</title>\n<link rel=\"canonical\" href=\"$url\">\n<meta name=\"description\" content=\"$desc\">\n"
    . "<meta property=\"og:type\" content=\"" . ($path === '/my-nose-knows-service-dog' || $path === '/fxw-dog-fence-review' || $path === '/blu-tech-review' || $path === '/necto-temperature-monitor-review' || $path === '/rvlock-keyless-entry-review' || $path === '/victron-battery-monitor-review' || $path === '/prime-rv-tank-treatment-review' ? 'article' : 'website') . "\">\n"
    . "<meta property=\"og:site_name\" content=\"$NAME\">\n<meta property=\"og:title\" content=\"$title\">\n<meta property=\"og:description\" content=\"$desc\">\n"
    . "<meta property=\"og:image\" content=\"$img\">\n<meta property=\"og:url\" content=\"$url\">\n"
    . "<meta name=\"twitter:card\" content=\"summary_large_image\">\n<meta name=\"twitter:title\" content=\"$title\">\n<meta name=\"twitter:description\" content=\"$desc\">\n<meta name=\"twitter:image\" content=\"$img\">\n";
  $html = preg_replace_callback('#<head><meta charset="utf-8">\s*#i', function ($m) use ($head) { return '<head>' . $head; }, $html, 1);
}
/* Review and story schema written into the page itself, so search engines and AI tools see it without running JavaScript (added 2026-10-06).
   Keep this in sync with window.BISCUIT_REVIEW_SCHEMA in index.html. */
$LD = array();
$LD['/my-nose-knows-service-dog'] = <<<'JSON'
{"@context":"https://schema.org","@type":"BlogPosting","headline":"My Nose Knows: What This Service Dog Actually Does","url":"https://biscuitthecampingbulldog.com/my-nose-knows-service-dog","datePublished":"2026-09-28","image":"https://biscuitthecampingbulldog.com/images/adv-service-dog-biscuit.jpg","author":{"@type":"Organization","name":"Biscuit the Camping Bulldog"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/fxw-dog-fence-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"FXW Dog Fence Review: Our Portable Fence for Campground Life","url":"https://biscuitthecampingbulldog.com/fxw-dog-fence-review","datePublished":"2026-09-24","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"FXW Dog Fence","brand":{"@type":"Brand","name":"FXW"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/blu-tech-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"Blu Technology Review: Clean Water and a Dry Floor in Our Camper","url":"https://biscuitthecampingbulldog.com/blu-tech-review","datePublished":"2026-10-13","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"Blu Technology RV water products (WaterStop, PURBLU AR3 filter, Portable RV Water Softener with Smart Cap)","brand":{"@type":"Brand","name":"Blu Technology"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/necto-temperature-monitor-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"Necto Temperature Monitor Review: Peace of Mind When I Stay Home","url":"https://biscuitthecampingbulldog.com/necto-temperature-monitor-review","datePublished":"2026-10-19","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"Necto Temperature Monitor","brand":{"@type":"Brand","name":"Necto"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/rvlock-keyless-entry-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"RVLock Keyless Door Lock Review: No More Fumbling for Keys","url":"https://biscuitthecampingbulldog.com/rvlock-keyless-entry-review","datePublished":"2026-10-19","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"RVLock Keyless RV Door Lock","brand":{"@type":"Brand","name":"RVLock"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/victron-battery-monitor-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"Victron BMV-712 Battery Monitor Review: No More Guessing Off-Grid","url":"https://biscuitthecampingbulldog.com/victron-battery-monitor-review","datePublished":"2026-11-16","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"Victron BMV-712 Smart Battery Monitor","brand":{"@type":"Brand","name":"Victron Energy"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/prime-rv-tank-treatment-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"Prime RV (Liquified) Tank Treatment Review: Goodbye, Gray Tank Smell","url":"https://biscuitthecampingbulldog.com/prime-rv-tank-treatment-review","datePublished":"2026-10-27","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"Liquified RV Tank Treatments","brand":{"@type":"Brand","name":"Prime RV"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
if ($STATE_KEY !== '') {
  $items = array(); $pos = 1;
  foreach ($SV as $v) {
    $it = array('@type' => 'VeterinaryCare', 'name' => $v['name']);
    if (!empty($v['where'])) $it['address'] = $v['where'];
    if (!empty($v['phone'])) $it['telephone'] = $v['phone'];
    $items[] = array('@type' => 'ListItem', 'position' => $pos++, 'item' => $it);
  }
  foreach ($SC as $v) {
    $it = array('@type' => 'Campground', 'name' => $v['name'], 'petsAllowed' => true);
    if (!empty($v['where'])) $it['address'] = $v['where'];
    if (!empty($v['phone'])) $it['telephone'] = $v['phone'];
    $items[] = array('@type' => 'ListItem', 'position' => $pos++, 'item' => $it);
  }
  $crumbs = array('@type' => 'BreadcrumbList', 'itemListElement' => array(
    array('@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $SITE . '/'),
    array('@type' => 'ListItem', 'position' => 2, 'name' => 'Find a vet', 'item' => $SITE . '/vets'),
    array('@type' => 'ListItem', 'position' => 3, 'name' => $STATES[$STATE_KEY], 'item' => $SITE . $path)));
  $LD[$path] = json_encode(array('@context' => 'https://schema.org', '@graph' => array($crumbs, array('@type' => 'ItemList', 'name' => $STATES[$STATE_KEY] . ' vets and pet-friendly campgrounds', 'url' => $SITE . $path, 'numberOfItems' => count($items), 'itemListElement' => $items))), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
/* Extra schema (added 2026-10-27): breadcrumbs on every subpage, FAQ on the heat guide and pet papers
   (questions answered on those pages), and the gear list. Keep the FAQ answers matching the page text. */
$LD2 = array();
$CRUMB_NAMES = array('/gear' => 'Gear', '/vets' => 'Find a vet', '/heat' => 'Pet heat safety guide', '/pet-papers' => 'Pet papers', '/reviews' => 'Reviews', '/shop' => 'Shop', '/giveaway' => 'Necto giveaway', '/stories' => 'Stories', '/about' => 'About Biscuit', '/install' => "Get Biscuit's app", '/terms' => 'Terms of use');
$CRUMB_PARENT = array('/fxw-dog-fence-review' => '/reviews', '/blu-tech-review' => '/reviews', '/necto-temperature-monitor-review' => '/reviews', '/rvlock-keyless-entry-review' => '/reviews', '/victron-battery-monitor-review' => '/reviews', '/prime-rv-tank-treatment-review' => '/reviews', '/my-nose-knows-service-dog' => '/stories');
if ($path !== '/' && $STATE_KEY === '' && isset($PAGES[$path])) {
  $cl = array(array('@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $SITE . '/'));
  if (isset($CRUMB_PARENT[$path])) { $pp = $CRUMB_PARENT[$path]; $cl[] = array('@type' => 'ListItem', 'position' => 2, 'name' => $CRUMB_NAMES[$pp], 'item' => $SITE . $pp); }
  $cl[] = array('@type' => 'ListItem', 'position' => count($cl) + 1, 'name' => isset($CRUMB_NAMES[$path]) ? $CRUMB_NAMES[$path] : preg_replace('/ \| Biscuit the Camping Bulldog$/', '', $PAGES[$path][0]), 'item' => $SITE . $path);
  $LD2[] = array('@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $cl);
}
$faq = function ($qa) { $m = array(); foreach ($qa as $q => $a) $m[] = array('@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $a)); return array('@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $m); };
if ($path === '/heat') $LD2[] = $faq(array(
  'How much hotter does a parked camper or car get than the air outside?' => 'A parked camper, car or trailer runs 30 to 40 degrees F hotter than the air outside, and it gets there in under an hour. On an 85 degree F day, the inside of a parked car hits about 104 degrees F in 10 minutes and 114 degrees F in 20.',
  'Which pets overheat soonest?' => 'Flat-faced dogs, cats, rabbits and birds overheat sooner than people expect. Cut every temperature in the guide by ten degrees for bulldogs, frenchies, pugs, boxers and Persian cats.',
  'What are the signs a pet is overheating?' => 'Loud raspy breathing, thick ropey drool, bright red or purple gums, wobbling, or refusing to move. Get into air conditioning and call the nearest emergency vet.',
  'What if the RV air conditioning stops working?' => 'Treat power loss as an emergency. If the trailer air conditioning quits in summer, a pet shut inside has minutes, not hours. Know where the nearest cooled building is before you settle in for the night.'));
if ($path === '/pet-papers') $LD2[] = $faq(array(
  'What does my dog need to go into Canada from the U.S.?' => 'Dogs over 3 months old need a rabies certificate signed by a licensed vet that describes the dog and shows a rabies shot given within the last 3 years. Puppies under 3 months need proof of age. No health certificate is needed for a pet dog traveling with you. Rules change, so check the official USDA and Canadian pages before each trip.',
  'Can I bring dog food into Canada?' => 'Yes, up to 20 kg (about 44 lb) per pet, if it is made in the USA, in the original store packaging, and only for the pet traveling with you.',
  'What does my dog need to come back into the U.S. from Canada?' => 'The dog must be at least 6 months old, have a microchip a standard scanner can read, look healthy, and you need a receipt for the free CDC Dog Import Form, filled in online before you reach the border (one per dog, good for 6 months).',
  'Where is the pet papers card saved?' => 'Only in the browser on your own device. Nothing is uploaded or sent to the website, and it opens with no signal.'));
if ($path === '/gear') {
  $GEAR = array(array('Happy Howl Dog Food', 'https://www.happyhowl.com/Biscuit'), array('Chewy', 'https://chewy.sjv.io/c/7017016/2846786/32975'), array('Necto temperature monitor', 'https://getnecto.com/?ref=biscuit'), array('FXW Dog Playpen', 'https://www.amazon.com/dp/B08MF1XBZW?tag=biscuit0b5-20'), array('Starlink', 'https://starlink.com/'), array('Veritas Vans Starlink Mini Mount', 'https://veritasvans.com/product/starlink-mini-mount/'), array('Veritas Vans Starlink Mini Dish', 'https://veritasvans.com/product/starlink-mini-dish/'), array('Blu Tech Water Filter', 'https://goblutech.com/Biscuit'), array('Prime RV tank treatment', 'https://liquifiedrv.com/'), array('RVLock keyless door lock', 'https://club.co/s/UW-J-NcpZXQtQ'), array('CRAVOT CyberRack S2', 'https://cravot.com/discount/biscuit10'), array('Xtreme Heaters', 'https://xtremeheaters.com/?ref=Biscuit'), array('BLUETTI Power Stations', 'https://www.bluettipower.com/'), array('Harvest Hosts', 'https://www.harvesthosts.com/'));
  $gi = array(); foreach ($GEAR as $n => $g) $gi[] = array('@type' => 'ListItem', 'position' => $n + 1, 'name' => $g[0], 'url' => $g[1]);
  $LD2[] = array('@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => "Biscuit's Road-Tested Gear", 'url' => $SITE . '/gear', 'numberOfItems' => count($gi), 'itemListElement' => $gi);
}
foreach ($LD2 as $ld) {
  $pos = stripos($html, '</head>');
  if ($pos !== false) $html = substr_replace($html, '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n", $pos, 0);
}
if (isset($LD[$path])) {
  $pos = stripos($html, '</head>');
  if ($pos !== false) $html = substr_replace($html, '<script type="application/ld+json" id="biscuit-review-ld">' . $LD[$path] . "</script>\n", $pos, 0);
}
/* Plain-HTML version of each page for search engines and AI tools that don't run JavaScript (added 2026-10-26).
   Visitors never see it (it sits inside <noscript>); it gives crawlers the real page title, summary,
   links to every page and, on state pages, every vet and campground. */
$e2 = function ($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); };
$NAV = array('/' => 'Home', '/vets' => 'Find a vet near you (all 50 states)', '/heat' => 'Pet heat safety guide', '/pet-papers' => 'Pet travel papers checklist', '/gear' => "Biscuit's road-tested gear", '/shop' => 'Shop what we use', '/reviews' => 'Gear reviews', '/stories' => "Biscuit's stories", '/giveaway' => 'Necto giveaway', '/about' => 'About Biscuit', '/install' => "Get Biscuit's app", '/terms' => 'Terms of use');
if (isset($PAGES[$path])) {
  $np = $PAGES[$path];
  $ns = '<noscript><div style="max-width:760px;margin:0 auto;padding:16px;font-family:sans-serif">';
  $ns .= '<h1>' . $e2(preg_replace('/ \| Biscuit the Camping Bulldog$/', '', $np[0])) . '</h1><p>' . $e2($np[1]) . '</p>';
  if ($STATE_KEY !== '') {
    $ns .= '<h2>Vets in ' . $e2($STATES[$STATE_KEY]) . '</h2><ul>';
    foreach ($SV as $v) $ns .= '<li><strong>' . $e2($v['name']) . '</strong>' . (!empty($v['tag']) ? ' (' . $e2($v['tag']) . ')' : '') . (!empty($v['where']) ? ' &mdash; ' . $e2($v['where']) : '') . (!empty($v['phone']) ? ' &mdash; <a href="tel:' . preg_replace('/[^0-9]/', '', $v['phone']) . '">' . $e2($v['phone']) . '</a>' : '') . '</li>';
    $ns .= '</ul><h2>Pet-friendly campgrounds and state parks in ' . $e2($STATES[$STATE_KEY]) . '</h2><ul>';
    foreach ($SC as $v) $ns .= '<li><strong>' . $e2($v['name']) . '</strong>' . (!empty($v['where']) ? ' &mdash; ' . $e2($v['where']) : '') . (!empty($v['phone']) ? ' &mdash; ' . $e2($v['phone']) : '') . '</li>';
    $ns .= '</ul><p>Hours and services change. Call ahead when you can. In an emergency, go to the nearest open clinic.</p>';
  }
  if ($path === '/vets' || $STATE_KEY !== '') {
    $ns .= '<h2>Vets and pet-friendly campgrounds by state</h2><p>';
    $links = array();
    foreach ($STATES as $k => $nm) $links[] = '<a href="/vets/' . trim(preg_replace('/[^a-z]+/', '-', strtolower($nm)), '-') . '">' . $e2($nm) . '</a>';
    $ns .= implode(' &middot; ', $links) . '</p>';
  }
  $ns .= '<h2>More from Biscuit the Camping Bulldog</h2><ul>';
  foreach ($NAV as $u => $label) if ($u !== $path) $ns .= '<li><a href="' . $u . '">' . $e2($label) . '</a></li>';
  $ns .= '</ul><p>Contact: <a href="mailto:biscuit@biscuitthecampingbulldog.com">biscuit@biscuitthecampingbulldog.com</a></p></div></noscript>';
  $html = preg_replace_callback('#<body>#i', function ($m) use ($ns) { return '<body>' . $ns; }, $html, 1);
}
$html = preg_replace('#<html>#i', '<html lang="en">', $html, 1);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
echo $html;