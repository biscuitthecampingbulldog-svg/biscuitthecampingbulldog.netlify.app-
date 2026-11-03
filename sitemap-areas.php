<?php
/* Sitemap of every RV area page in Biscuit's vet finder (added 2026-11-03).
   Served at /sitemap-areas.xml. It is built fresh from vet-data.js on every visit,
   so new areas show up here by themselves - nothing to update by hand.
   Page addresses use the same rule as page.php (biscuit_area_slug) and index.html (areaSlug). */
$SITE = 'https://biscuitthecampingbulldog.com';
$STATES = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
$file = __DIR__ . '/vet-data.js';
$js = @file_get_contents($file);
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: no-cache');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
if ($js !== false) {
  $mod = date('Y-m-d', filemtime($file));
  $js = str_replace("\r\n", "\n", $js);
  $seen = array();
  foreach (explode("\nCAMPS: {", $js, 2) as $part) {
    foreach ($STATES as $key => $nm) {
      if (!preg_match('#\n' . $key . ': \[\n(.*?)\n\]#s', $part, $m)) continue;
      if (!preg_match_all("#\bcity: '((?:\\\\.|[^'\\\\])*)'#", $m[1], $cm)) continue;
      $st = trim(preg_replace('/[^a-z]+/', '-', strtolower($nm)), '-');
      foreach ($cm[1] as $c) {
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(preg_replace('/,\s*[A-Z]{2}$/', '', stripslashes($c)))), '-');
        $u = '/vets/' . $st . '/' . $slug;
        if ($slug === '' || isset($seen[$u])) continue;
        $seen[$u] = 1;
        echo "  <url>\n    <loc>" . htmlspecialchars($SITE . $u, ENT_XML1, 'UTF-8') . "</loc>\n    <lastmod>$mod</lastmod>\n  </url>\n";
      }
    }
  }
}
echo "</urlset>\n";
