<module>
	<service><?=$GETCFG_SVC?></service>
	<inf><icmp><?

include "/htdocs/phplib/xnode.php";
$infp = XNODE_getpathbytarget("", "inf", "uid", "WAN-3", 0);
echo query($infp."/icmp");

?></icmp></inf>
</module>
