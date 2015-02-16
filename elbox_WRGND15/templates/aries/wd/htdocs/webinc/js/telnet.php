<?include "/htdocs/phplib/phyinf.php";?>
<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "TELNET",
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		BODY.ShowContent();
		switch (code)
		{
			case "OK":
				return true;
				break;
			default :
				return false;
		}
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		if (!this.InitialWan()) return false;
		return true;
	},
	PreSubmit: function()
	{
		if (!this.SaveWanXML()) return null;

		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	InitialWan: function()
	{
		var base = PXML.FindModule("TELNET");
		if (parseInt(XG(base+"/telnet_disable")) == 1)
		{
			OBJ("enable_telnet").checked = false;
		}
		else
		{
			OBJ("enable_telnet").checked = true;
		}
		return true;
	},
	SaveWanXML: function()
	{
		var base = PXML.FindModule("TELNET");
		XS(base+"/telnet_disable", OBJ("enable_telnet").checked ? "0" : "1");
		return true;
	}
}
</script>
