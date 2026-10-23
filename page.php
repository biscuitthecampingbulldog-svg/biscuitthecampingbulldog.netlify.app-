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
  '/' => array("Biscuit the Camping Bulldog \u{2014} vet-first travel for every pet", 'Emergency and regular vets, pet-friendly campgrounds and a heat guide for travelling pets, from a service dog living full-time in the Great Smoky Mountains.', 'biscuit-hero-smokies.jpg'),
  '/gear' => array("Biscuit's Road-Tested Gear | Biscuit the Camping Bulldog", 'The RV and pet gear our family actually uses, with discount codes and deals for our readers from Happy Howl, Necto, RVLock, CRAVOT, Prime RV and more.', 'picks/pick-blu-tech.jpg'),
  '/vets' => array('Find a Vet Near You | Biscuit the Camping Bulldog', 'Emergency and regular vets plus pet-friendly campgrounds in all 50 states and the top RV destinations, checked by hand. Call or get directions in one tap.', 'biscuit-hero-smokies.jpg'),
  '/heat' => array('Pet Heat Safety Guide | Biscuit the Camping Bulldog', 'How hot is too hot for your pet? A temperature-by-temperature guide for dogs, cats and small animals on the road, and why a parked camper heats up so fast.', 'adv-campsite.jpg'),
  '/pet-papers' => array('Pet Travel Papers Checklist | Biscuit the Camping Bulldog', "Keep your pet's microchip, rabies tag, shots and vet info on one card that opens with no signal. Saved only on your device.", 'adv-campsite.jpg'),
  '/reviews' => array('Gear Reviews | Biscuit the Camping Bulldog', 'Honest reviews of the gear Biscuit uses on the road. If we recommend it, it survived a bulldog.', 'review-fxw-fence-upright.jpg'),
  '/fxw-dog-fence-review' => array('FXW Dog Fence Review | Biscuit the Camping Bulldog', 'The portable fence that gives Biscuit her own yard at every campground: setup, what we like, and what to know before you buy.', 'review-fxw-fence-upright.jpg'),
  '/blu-tech-review' => array('Blu Technology Review | Biscuit the Camping Bulldog', 'The leak alarm, water filter and softener that keep our camper water clean and our floor dry: what each one does and why we use it, with a video from Biscuit.', 'picks/pick-blu-tech.jpg'),
  '/necto-temperature-monitor-review' => array('Necto Temperature Monitor Review | Biscuit the Camping Bulldog', 'Why Biscuit stays home safely in the camper: the Necto cell-connected temperature monitor, 2 years of monitoring included, alerts straight to our phones.', 'picks/pick-necto.jpg'),
  '/eco-worthy-battery-review' => array('ECO-WORTHY Battery Review | Biscuit the Camping Bulldog', 'Our two ECO-WORTHY 280Ah lithium batteries: six straight days off grid with power to spare, no maintenance, and lighter than lead-acid.', 'picks/eco-worthy-batteries-installed.jpg'),
  '/rvlock-keyless-entry-review' => array('RVLock Keyless Door Lock Review | Biscuit the Camping Bulldog', 'The RVLock keyless RV door lock: unlock the camper from a keychain remote, no more fumbling for keys with your hands full.', 'picks/pick-rvlock-2.jpg'),
  '/shop' => array('Shop What We Use | Biscuit the Camping Bulldog', 'Over 85 things our full-time RV family actually bought and uses: dog gear, solar, RV parts, plumbing, camp kitchen and campsite favorites.', 'biscuit-hero-smokies.jpg'),
  '/giveaway' => array('Necto Giveaway | Biscuit the Camping Bulldog', 'Win a Necto ($199 value)! Join Biscuit\'s Pack by December 14, 2026 for a chance to win. US residents 18+, no purchase necessary.', 'picks/pick-necto.jpg'),
  '/stories' => array("Biscuit's Stories | Biscuit the Camping Bulldog", 'Stories from the road with Biscuit, a service dog and English Bulldog living full-time in an RV in the Great Smoky Mountains.', 'adv-service-dog-biscuit.jpg'),
  '/my-nose-knows-service-dog' => array('My Nose Knows: What This Service Dog Actually Does | Biscuit the Camping Bulldog', "People see the vest and think I'm playing dress-up. I am not. What a working service dog actually does, from Biscuit herself.", 'adv-service-dog-biscuit.jpg'),
  '/about' => array('About Biscuit | Biscuit the Camping Bulldog', 'Meet Biscuit, a service dog and English Bulldog living full-time in an RV in the Great Smoky Mountains with her mama Denise and daddy Jeffrey.', 'adv-roadtrip.jpg'),
  '/install' => array("Get Biscuit's App | Biscuit the Camping Bulldog", "Add Biscuit's vet finder to your phone's home screen. Free, no account, no app store.", 'biscuit-hero-smokies.jpg'),
  '/terms' => array('Terms of Use | Biscuit the Camping Bulldog', 'Terms of use and copyright for biscuitthecampingbulldog.com.', 'biscuit-hero-smokies.jpg')
);
$uri = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
$path = rtrim($uri, '/');
if ($path === '' || $path === '/index.html') $path = '/';
if ($path === '/page.php') $path = isset($_GET['p']) ? rtrim($_GET['p'], '/') : '/';
if ($path === '') $path = '/';
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
  $ogType = ($path === '/my-nose-knows-service-dog' || $path === '/fxw-dog-fence-review' || $path === '/blu-tech-review' || $path === '/necto-temperature-monitor-review' || $path === '/eco-worthy-battery-review' || $path === '/rvlock-keyless-entry-review') ? 'article' : 'website';
  $html = $set($html, 'property', 'og:type', $ogType);
  $html = $set($html, 'property', 'og:title', $title);
  $html = $set($html, 'property', 'og:description', $desc);
  $html = $set($html, 'property', 'og:image', $img);
  $html = $set($html, 'property', 'og:url', $url);
  $html = $set($html, 'name', 'twitter:title', $title);
  $html = $set($html, 'name', 'twitter:description', $desc);
  $html = $set($html, 'name', 'twitter:image', $img);
  $head = "\n<title>$title</title>\n<link rel=\"canonical\" href=\"$url\">\n<meta name=\"description\" content=\"$desc\">\n"
    . "<meta property=\"og:type\" content=\"" . ($path === '/my-nose-knows-service-dog' || $path === '/fxw-dog-fence-review' || $path === '/blu-tech-review' || $path === '/necto-temperature-monitor-review' || $path === '/eco-worthy-battery-review' || $path === '/rvlock-keyless-entry-review' ? 'article' : 'website') . "\">\n"
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
$LD['/eco-worthy-battery-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"ECO-WORTHY 280Ah Lithium Battery Review: Six Days Off Grid and Still Going","url":"https://biscuitthecampingbulldog.com/eco-worthy-battery-review","datePublished":"2026-10-19","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"ECO-WORTHY 12.8V 280Ah LiFePO4 Lithium Battery","brand":{"@type":"Brand","name":"ECO-WORTHY"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
$LD['/rvlock-keyless-entry-review'] = <<<'JSON'
{"@context":"https://schema.org","@type":"Review","name":"RVLock Keyless Door Lock Review: No More Fumbling for Keys","url":"https://biscuitthecampingbulldog.com/rvlock-keyless-entry-review","datePublished":"2026-10-19","reviewRating":{"@type":"Rating","ratingValue":5,"bestRating":5,"worstRating":1},"itemReviewed":{"@type":"Product","name":"RVLock Keyless RV Door Lock","brand":{"@type":"Brand","name":"RVLock"}},"author":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"},"publisher":{"@type":"Organization","name":"Biscuit the Camping Bulldog","url":"https://biscuitthecampingbulldog.com/"}}
JSON;
if (isset($LD[$path])) {
  $pos = stripos($html, '</head>');
  if ($pos !== false) $html = substr_replace($html, '<script type="application/ld+json" id="biscuit-review-ld">' . $LD[$path] . "</script>\n", $pos, 0);
}
$html = preg_replace('#<html>#i', '<html lang="en">', $html, 1);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
echo $html;