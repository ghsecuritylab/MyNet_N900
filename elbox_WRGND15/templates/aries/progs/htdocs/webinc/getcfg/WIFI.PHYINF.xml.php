<module>
	<service><?=$GETCFG_SVC?></service>
	<device>
		<radio24gonoff><? echo query("/device/radio24gonoff");?></radio24gonoff>
	</device>
	<wifi>
<?		echo dump(2, "/wifi");
?>	</wifi>
<?
	foreach("/phyinf")
	{
		if (query("type")== "wifi")
		{
			echo '\t<phyinf>\n';
			echo dump(2, "/phyinf:".$InDeX);
			echo '\t</phyinf>\n';
		}
	}
	foreach("/runtime/phyinf")
	{
		if(query("type")== "wifi")
		{
			echo '\t<extraInfo>\n';
			echo dump(2, "/runtime/phyinf:".$InDeX);
			echo '\t</extraInfo>\n';
		}
	}
?></module>
