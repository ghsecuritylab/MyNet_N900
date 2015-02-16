<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/inf.php";
include "/htdocs/phplib/xnode.php";

$ipaddr = INF_getcurripaddr($INF);
$model = query("/runtime/device/modelname");

if($model == "MyNetN600"){
    $modelid  = "02BF98D2-1B50-4a1f-83D9-A85402782497";
}
else if($model == "MyNetN750"){
    $modelid  = "02BF98D2-1B50-4a1f-83D9-A85402782498";
}
else if($model == "MyNetN900"){
	$modelid  = "02BF98D2-1B50-4a1f-83D9-A85402782499";
}
else if($model == "MyNetN900C"){
    $modelid  = "02BF98D2-1B50-4a1f-83D9-A8540278249A";
}
/* check ipv6, sam_pan */
$inf_path = XNODE_getpathbytarget("", "inf", "uid", $INF, 0);
$inet     = query($inf_path."/inet");
$inetp    = XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
$addrtype = query($inetp."/addrtype");

if($addrtype == "ipv6")
{
	$ipaddr = "[".$ipaddr."]";
	$dtype = "urn:schemas-upnp-org:device:InternetGatewayDevice:2";
}
else
{
	$dtype = "urn:schemas-upnp-org:device:InternetGatewayDevice:1";
}

$dpath = XNODE_getpathbytarget("/runtime/upnp", "dev", "deviceType", $dtype, 0);
if ($dpath == "") exit;

anchor($dpath);
$port	  = query("port");
set("devdesc/URLBase",					"http://".$ipaddr.":".$port);
set("devdesc/device/presentationURL",	"http://".$ipaddr);

echo "\<\?xml version=\"1.0\"\?\>\n";
echo "<root xmlns=\"urn:schemas-upnp-org:device-1-0\" xmlns:pnpx=\"http://schemas.microsoft.com/windows/pnpx/2005/11\" xmlns:df=\"http://schemas.microsoft.com/windows/2008/09/devicefoundation\">\n";

echo "	<specVersion>\n";
echo dump(2, "devdesc/specVersion");
echo "	</specVersion>\n";
echo "	<URLBase>http://".$ipaddr.":".$port."</URLBase>\n";
echo "	<device>\n";
echo "		<pnpx:X_hardwareId>urn:www-wdc-com:device:InternetGatewayDevice:".$model."</pnpx:X_hardwareId>\n";
	 // Tag <pnpx:X_deviceCategory> changes the icon type of IGD in Windows 7, by Jerry Kao.
echo "		<pnpx:X_deviceCategory>NetworkInfrastructure.Router NetworkInfrastructure.AccessPoint</pnpx:X_deviceCategory>\n";
echo "		<df:X_deviceCategory>Network.Router.Wireless</df:X_deviceCategory>\n";
echo "		<df:X_modelId>".$modelid."</df:X_modelId>\n";
echo dump(2, "devdesc/device");
echo "	</device>\n";
echo "</root>\n";
?>
