<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<base href="http://sakai.rutgers.edu/" />
<title>Linktool Test</title>
<link href="/library/skin/tool_base.css" type="text/css" rel="stylesheet" media="all" />
<link href="/library/skin/default/tool.css" type="text/css" rel="stylesheet" media="all" />
<script type="text/javascript" language="JavaScript" src="/library/js/headscripts.js"></script>
<script type="text/javascript" language="JavaScript">
var _editor_url = "/library/htmlarea/"
</script>
</head><body>

<?
  // in a real application the following two things would be read from
  // a configuration file, located outside the area accessible to the 
  // web server [for security reasons]

  // obviously this should be replaced with your actual contact
  $emailcontact = "root@localhost";
  // this is a session authorization object. It is issued using the "setup"
  // screen. I put the objects into an array indexed by server name, to
  // allow more than one sakai server to use the same PHP script and to make
  // sure we don't present one of these objects to a rogue site. 

  $obj["http://localhost:8080"] = "user=1e307359-c975-4324-80e7-54837b3ad475&sign=10110eb7bf9381bff55d816e60b4ec2c7ee97d856e309c6aeef9ad70f6bdd6c8dc59dd97168c2a48080cbcdd65463b7afda35823c02409016a92c248edbba8c4ffe78cb853d1bf3ea4640297f03b700ec39a9993bdd9e7d28c6268ecb9468b454685ffa7df50e5acbf7ae519b04fa15fc5dfff096ff1566d6978f75b609a1d04";

  // to avoid cross-site scripting problems, arguments should be passed
  // through strip_tags unless you're sure you know what you're doing
  $user = strip_tags($_GET['user']);
  $euid = strip_tags($_GET['euid']);
  $site = strip_tags($_GET['site']);
  $server = strip_tags($_GET['serverurl']);
  $url = geturl($server);

  // in a real application these should be session variables, to avoid
  // parsing wsdl for each page
  $signingProxy = getproxy($url, "SakaiSigning");
  $siteProxy = getproxy($url, "SakaiSite");
///  $infoProxy = getproxy($url, "SakaiRutgersInfo");
///  $gbProxy = getproxy($url, "SakaiGradebook");

  // standard code to verify the arguments passed to us. 
  $result=$signingProxy->testsign($_SERVER['QUERY_STRING']);
  if ($result != "true")
    fatal("Unauthorized call");

  // get a session for doing other web services. This also validates
  // the arguments, so you don't need to do testsign if you're doing
  // getsession
  $session=$signingProxy->getsession($_SERVER['QUERY_STRING'], $obj[$server]);
  // if there's an error, it will be an axis error object, which is not string
  if (gettype($result) != "string")
    fatal("Unauthorized permissions object");

  // see what sites the user can get to. Result is an XML object
  $sites = $siteProxy->getSitesDom($session, "", 1, 9999);
  $sites = str_replace("<", "&lt;", $sites);
  print "<p>$euid ($user) can access the following sites:<pre>$sites</pre>";

/*
  // see what courses are associated with the current site
  $courses = $infoProxy->getSiteCourses($session, "4e984255-8852-4d1e-8016-3ae1286d5687");
  print "<p>Site $site has the following courses: $courses";

  // See whether user can update the site
  $allow = $infoProxy->allowUpdateSite($session, "4e984255-8852-4d1e-8016-3ae1286d5687");
  print "<p>Can $user update $site? $allow";

  // get basic info on user. result is XML
  $userinfo = $infoProxy->getUserInfo($session, $user);
  $userinfo = str_replace("<", "&lt;", $userinfo);
  print "<p>Info on $user:<pre>$userinfo</pre>";

  // does user have grading access? clh is probably nonexistent, so
  // this tests whether the user has access to all students
  $allow = $gbProxy->isUserAbleToGradeStudent($session, $site, "clh");
  print "<p>Can $user grade students? $allow";
*/

/////// functions

  function geturl($url) {
    $url = "$url/sakai-axis/";
    return $url;
  }

  function fatal($msg) {
    print "<h1>Error</h1>";
    print "<p>$msg";
    print "</body></html>";
    exit(0);
  }

  function getproxy($url, $name) {
    require_once('SOAP/Client.php');
    $wsdl=new SOAP_WSDL("$url/$name.jws?wsdl", array("timeout" => 360));
    if (!$wsdl)
      fatal("This error should not happen. Please send email to $emailcontact. Unable to open connection to $url/$name.jws?wsdl");

    $myProxy=$wsdl->getProxy();
    if (!$myProxy)
      fatal("This error should not happen. Please send email to $emailcontact. getProxy returned null.");

    return $myProxy;
  }   

?>

<p>
now is the time now is the time now is the time now is the time now is the time 
now is the time now is the time now is the time now is the time 
now is the time now is the time now is the time now is the time 
now is the time now is the time now is the time now is the time 
now is the time now is the time now is the time now is the time 
now is the time now is the time now is the time now is the time 
</body></html>
