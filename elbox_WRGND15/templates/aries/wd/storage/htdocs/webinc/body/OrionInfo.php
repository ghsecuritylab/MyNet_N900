﻿<? include "/htdocs/webinc/body/draw_elements.php";?>
<form>
<div>
        <div class="textinput">
            <span class="name"><?echo i18n("Orion Flash Version");?></span>
            <span class="value"><?echo cut(fread("", "/etc/config/OrionInfo.txt"), "0", "\n");?></span>
        </div>
        <div class="textinput">
            <span class="name"><?echo i18n("Orion InternalHDD Version");?></span>
            <span class="value"><?echo cut(fread("", "/internalhd/root/OrionInfo.txt"), "0", "\n");?></span>
        </div>
        <div class="textinput">
            <span class="name"><?echo i18n("Enable SSHD");?></span>
            <span class="value"><input id="enable_sshd" type="checkbox" class="styled" /></span>
        </div>
        <div class="bottom_cancel_save">
            <input type="button" class="button_blue" onclick="BODY.OnSubmit();" value="<?echo I18N('h', 'Save');?>" />
        </div>
</div>
</form>
