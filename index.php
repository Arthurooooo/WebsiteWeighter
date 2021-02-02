<?php ini_set('display_errors', 1); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>Website Weighter by GM</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">


    <!-- Bootstrap core CSS -->
<link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
  </head>
  <body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Greenmetrics</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    </div>
  </div>
</nav>

<main class="container">

  <div class="starter-template text-center py-5 px-3">
    <h1>Website weighter</h1>
    <p class="lead"></p>measure how heavy your website is!<br></p>
  </div>

  <div>
  <form method="post">
    <input type="text" name="site" placeholder="Website URL"/>
    <input type="text" name="sitemap" placeholder="Website sitemap"/>
    <input type="submit"/>
  </form>

  <?php

function startsWith( $haystack, $needle ) {
  $length = strlen( $needle );
  return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle) {
 $length = strlen( $needle );
 if( !$length ) {
     return true;
 }
 return substr( $haystack, -$length ) === $needle;
}
 function first_sentence($content) {

  $pos = strpos($content, '.xml');
  return substr($content, 0, $pos+4);
 }

 function myFilter($var){
  return ($var !== NULL && $var !== FALSE && $var !== "" && $var !== " ");
}

function weightlink($url)
{
  $tmp = shell_exec('/usr/local/bin/wget -O- "'. $url .'" | wc -c'); //mesure tout les liens
  //echo $url . "<br/>" . intval($tmp) * 0.00000006012 . "<br/><br/>";
  $weight = (intval($tmp) * 0.00000006012);
  return ($weight);
}

function measure_sitemap($nodes)
{
  $totalweight = 0;

  echo "<pre>";
  foreach($nodes as $key => $node)
  {
    try
    {
      $url = $node->getAttribute('href'); //recup tout les liens html de la page
    }
    catch(Exception $e)
    {
      $url = $node->getAttribute('loc'); //recup tout les liens html de la page
    }
    //echo $url . '<br/>';
    if(strpos($url, $url) !== false)
    {
      $tmp = shell_exec('/usr/local/bin/wget -O- "'. $url .'" | wc -c'); //mesure tout les liens
      echo $key . " => " . $url . "<br/>" . intval($tmp) * 0.00000006012 . "<br/>";
      $totalweight = ($totalweight + (intval($tmp) * 0.00000006012));
      echo "poids total = " . $totalweight . "g" . "<br/><br/>";
    }
  }
}

function parse($link)
  {

    echo "bien recu " . $link . '<br/>';

    $doc = new DOMDocument();


    if(substr($link, -3) == "xml")
    {
      echo("issa xml" . '<br/>');
      $doc->load($link) or die("can't load subsitemap");

      $xml = simplexml_load_file($link);

      $xpath = new DOMXpath($doc);

      try
      {
        $nodes = $xpath->query('//xhtml:link');
        echo "tried";
      }
      catch(Exception $e)
      {
        $nodes = $xpath->query('//loc');
        echo "fail";
      }
      measure_sitemap($nodes);
    }
    else if(substr($link, -3) == "tml")
    {
      //echo("issa html");
      weightlink($link);
      die();
    }
    else{
      print($link);
      die("invalid sitemap");
    }
    echo "</pre>";
  }


/////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////

if (isset($_POST["sitemap"]) && !empty($_POST["sitemap"])): // Traitement de la sitemap
  if (strpos($_POST['sitemap'], "sitemap.xml") != FALSE)
  {
    echo "Handling your sitemap ... Wait a minute and be patient <3" . "<br/>" . "<br/>";


    $mainsitemaplist = array_values(array_filter(explode(" ", simplexml_load_file($_POST['sitemap'])->asXML()), "myFilter")) or die("can't load main sitemap");

    //var_dump($mainsitemaplist);

    $i = 0;
    foreach ($mainsitemaplist as $sitemap)
    {
      $sitemap = trim(strip_tags($sitemap));
      if(substr($sitemap, 0, 1) == "h")
      {
        parse($sitemap);
      }

      echo('<br/>');
      $i++;


    }
    
    $result = "non defini ";
    
    $html = file_get_contents($_POST['site']);
    
    $xml = new SimpleXMLElement(simplexml_load_file($_POST['sitemap'])->asXML()) or die("Error: Cannot load xml");
    $sitemapslist = $xml;
    die();
  }
  else
  {
    echo "This is not a sitemap...";
    die();
  }
//else: // Traitement de l'url
  //parse($_POST["site"]);
endif;

if(isset($_POST['site']) && !empty($_POST["site"]))
{
  $site = $_POST['site'];
  if ($ret = parse_url($site))
  {
    if (!isset($ret["scheme"]))
     {
        $site = "http://{$site}";
     }
  }
  $weight = weightlink($site);
  echo "<br/>" . "Cette page pese = " . $weight . "g de CO2" . "<br/>";
  //print(exec('wget -O- "greenmetrics.io"'));
}


  ?>

  </div>

</main><!-- /.container -->


    <script src="./assets/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
