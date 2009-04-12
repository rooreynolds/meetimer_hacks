<html>
 <head>
  <script type="application/x-javascript">
function draw() {
 var canvas = document.getElementById("canvas");
 var ctx = canvas.getContext("2d");
 ctx.mozTextStyle = "9pt Verdana";
 //there must be a nicer way to do background fill?
 //if I don't do this, when I save the Canvas to PNG it has a transparent background
 ctx.fillStyle = "rgb(1000,1000,1000)";
 ctx.fillRect (0, 0, 1120, 585);

<?php
print"  ctx.fillStyle = 'rgb(0,0,0)'; ";
//draw the rows, and day labels
for ($day = 0; $day < 7; $day++) {
  $dayheight = 65 + $day * 80; // vertical offset for label
  print"  ctx.translate(0, $dayheight);";
  print"  ctx.fillStyle = 'rgba(0,0, 0, 1)'; ";
  if ($day == 0) {
    $daystring = "        today"; //total hack with leading whitespace to more-or-less align the labels
  } else if ($day == 1) {
    $daystring = "  yesterday";
  } else {
    $daystring = "$day days ago";
  }
  print"  ctx.mozDrawText('$daystring'); ";
  print"  ctx.translate(0, -$dayheight);"; 
  print"  ctx.fillStyle = 'rgba(0, 0, 0, 0.1)'; ";
  print"  ctx.fillRect (90, 20 + $day * 80, 1000, 1);"; // draw the horizontal lines, one for each day row
}
print"  ctx.fillRect (90, 20 + $day * 80, 1000, 1);"; //and another one at the bottom. (Ahem).

//draw the columns, one per hour
print"  ctx.fillStyle = 'rgba(0,0, 0, 1)'; ";
for ($hour = 0; $hour < 24; $hour++) {
  print"  ctx.translate(" . (floor(76 + $hour / 24 * 1000)) . ", 15);";
  print"  ctx.fillStyle = 'rgba(0,0, 0, 1)'; ";
  print"  ctx.mozDrawText('$hour:00'); ";
  print"  ctx.translate(-" . (floor(76 + $hour / 24 * 1000)) . ", -15);";
  print"  ctx.fillStyle = 'rgba(0,0, 0, 0.1)'; ";
  print"  ctx.fillRect (" . floor(90 + $hour / 24 * 1000) . ", 15, 1, 565);";	
}
print"  ctx.fillRect (1090, 15, 1, 566);";	

try{

  $db = new PDO('sqlite:meetimer.sqlite');

  $sqlGetView = "select startdate / 1000, " .
  "strftime('%s','now') - startdate / 1000 as age, " .
  "url, duration, " . 
  "strftime('%j', 'now') - strftime('%j', datetime(startdate / 1000, 'unixepoch') ) as daysold, " .
  "strftime('%H:%M:%S', datetime(startdate / 1000, 'unixepoch')) as time, " .
  "round(((60 * 60 * strftime('%H', datetime(startdate / 1000, 'unixepoch'))) + (60 * strftime('%M', datetime(startdate / 1000, 'unixepoch'))) + (strftime('%S', datetime(startdate / 1000, 'unixepoch')))) / 60.0 / 1440 * 1000, 1) as offset " .
  " from log " .
  "    left join urls on log.url_id=urls.id " .
  "    where (url = 'twitter.com' " .
  "    or url = 'mail.google.com' " .
  "    or url = 'google.com/reader/' " .
  "    or url = 'delicious.com' " .
  "    or url = 'technorati.com' " .
  "    or url = 'facebook.com' " .
  "    or url like 'feedburner.%' " .
  "    or url = 'gmail.com' " .
  "    or url = 'youtube.com' " .
  "    or url = 'google.com' " .
  "    or url = 'google.co.uk' " .
  "    or url = 'flickr.com') " .
  "and daysold < 7 order by startdate desc;";
  $result = $db->query($sqlGetView);
  $arrValues = $result->fetchAll(PDO::FETCH_ASSOC);
  $i = 0;
  foreach ($arrValues as $row){
    if ($i > 4) $i = 0;
    print"  ctx.drawImage(document.getElementById('$row[url]'), 90 + $row[offset], 35 + $row[daysold] * 80 + ($i * 10), " . floor(max($row[duration] / 35, 6)) . ", 16);";
    $i++;
  }
}catch( PDOException $exception) {
  die($exception->getMessage());
}
?> 
}
  </script>
 </head>
 <body onload="draw()">
   <canvas id="canvas" width="1120" height="585"></canvas>
<img id="twitter.com" src="icons/twitter.com.ico" width="0" height="0">
<img id="search.twitter.com" src="icons/twitter.com.ico" width="0" height="0">
<img id="mail.google.com" src="icons/gmail.com.ico" width="0" height="0">
<img id="gmail.com" src="icons/gmail.com.ico" width="0" height="0">
<img id="google.com/reader/" src="icons/reader.google.com.ico" width="1" height="1" alt="Google Reader">
<img id="delicious.com" src="icons/delicious.com.ico" width="0" height="0">
<img id="technorati.com" src="icons/technorati.com.ico" width="0" height="0">
<img id="facebook.com" src="icons/facebook.com.ico" width="0" height="0">
<img id="feedburner.google.com" src="icons/feedburner.com.ico" width="0" height="0">
<img id="feedburner.com" src="icons/feedburner.com.ico" width="0" height="0">
<img id="youtube.com" src="icons/youtube.com.ico" width="0" height="0">
<img id="google.com" src="icons/google.com.ico" width="0" height="0">
<img id="google.co.uk" src="icons/google.com.ico" width="0" height="0">
<img id="flickr.com" src="icons/flickr.com.ico" width="0" height="0">
 </body>
</html>
