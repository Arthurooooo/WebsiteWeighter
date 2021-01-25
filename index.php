<?php ini_set('display_errors', 1); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>Starter Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">


    <!-- Bootstrap core CSS -->
<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item active">
          <a class="nav-link" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
          <ul class="dropdown-menu" aria-labelledby="dropdown01">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
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

    <input type="text" name="site" />
    <input type="text" name="sitemap" />
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

function weightlink($link)
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
    }
    else if(substr($link, -3) == "tml")
    {
      $doc->loadHTML($link); //helps if html is well formed and has proper use of html entities!
      echo("issa html");


    $xpath = new DOMXpath($doc);

    $nodes = $xpath->query('//a');
    }
    else{
      die("invalid sitemap");
    }

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
      echo $url . '<br/>';
      if(strpos($url, $url) !== false)
      {
        $tmp = shell_exec('/usr/local/bin/wget -O- "'. $url .'" | wc -c'); //mesure tout les liens
        echo $key . " => " . $url . "<br/>" . intval($tmp) * 0.00000006012 . "<br/><br/>";
        $totalweight = ($totalweight + (intval($tmp) * 0.00000006012));
        echo "poids total = " . $totalweight . "g" . "<br/>";
      }
    }
    echo "</pre>";
  }


/////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////


$mainsitemaplist = array_values(array_filter(explode(" ", simplexml_load_file($_POST['sitemap'])->asXML()), "myFilter")) or die("can't load main sitemap");
#print_r ($mainsitemaplist);

$i = 0;
foreach ($mainsitemaplist as $sitemap) {

  $sitemap = trim(strip_tags($sitemap));
  if(substr($sitemap, 0, 1) == "h")
  {
    weightlink($sitemap);
  }

  echo('<br/>');
  $i++;


}

    $result = "non defini ";
    if(isset($_POST['site']))
    {
      $site = $_POST['site'];
      $var = shell_exec('/usr/local/bin/wget -O- "'.$_POST['site'].'" | wc -c');

      $result = intval($var) * 0.00000006012;
      //print(exec('wget -O- "greenmetrics.io"'))
    }
    #$sitemap = $_POST['sitemap'];

    $html = file_get_contents($_POST['site']);

    //if(strtok($xml, "\n"))
    $xml = new SimpleXMLElement(simplexml_load_file($_POST['sitemap'])->asXML()) or die("Error: Cannot load xml");
    $sitemapslist = $xml;


    // echo "<pre>";
    // foreach($xmlnodes as $xmlkey => $xmlnode) {
    //   //echo($xmlnode->nodeValue);
    //   $xmlurl = $xmlnode->getAttribute('loc');
    //   echo($xmlurl);
    //   $xmltmp = shell_exec('/usr/local/bin/wget -O- "'. $xmlurl .'" | wc -c');
    //   echo $xmlkey . " => " . $xmlurl . "<br/>" . intval($xmltmp) * 0.00000006012 . "<br/><br/>";
    //   $xmltotalweight = $xmltotalweight + (intval($xmltmp) * 0.00000006012);
    //   echo "poids total XML = " . $xmltotalweight . "g" . "<br/>";
    // }
    // echo "</pre>";


  ?>
  <pre>sitemap =  <?=$xmlurl;?></pre>

   <pre>Cette page pese <?=$result;?>g de co2</pre>

  </div>

</main><!-- /.container -->


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
