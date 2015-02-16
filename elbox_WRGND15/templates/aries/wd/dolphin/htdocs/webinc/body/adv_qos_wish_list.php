		<tr>
			<td rowspan="3" class="centered">
				<input id="en_<?=$INDEX?>" type="checkbox" />
			</td>
			<td>
				<?echo i18n("Name");?><br>
				<input id="dsc_<?=$INDEX?>" type="text" size="20" maxlength="31" />
			</td>
			<td>
				<?echo i18n("Priority");?><br>
				<select id="pri_<?=$INDEX?>">
					<option value="VO">1 - <?echo i18n("Highest");?></option>
					<option value="VI">2 - <?echo i18n("High");?></option>
					<option value="BG">3 - <?echo i18n("Medium");?></option>
					<option value="BE">4 - <?echo i18n("Low");?></option>
				</select>
			</td>
			<td>
				<?echo i18n("Protocol");?><br>
				<select id="pro_<?=$INDEX?>">
					<option value="TCP">TCP</option>
					<option value="UDP">UDP</option>
				</select>
			</td>			
		</tr>
		<tr>
			<td colspan="2">
				<?echo i18n("Local IP Range");?><br>
				<input id="src_startip_<?=$INDEX?>" type="text" maxlength="15" size="27" />  to 
				<input id="src_endip_<?=$INDEX?>" type="text" maxlength="15" size="27" />
			</td>
			<td>
				<?echo i18n("Local Port Range");?><br>
				<input id="src_startport_<?=$INDEX?>" type="text" maxlength="5" size="5" />  to 
				<input id="src_endport_<?=$INDEX?>" type="text" maxlength="5" size="5" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?echo i18n("Remote IP Range");?><br>
				<input id="dst_startip_<?=$INDEX?>" type="text" maxlength="15" size="27" />  to 
				<input id="dst_endip_<?=$INDEX?>" type="text" maxlength="15" size="27" />
			</td>
			<td>
				<?echo i18n("Remote Port Range");?><br>
				<input id="dst_startport_<?=$INDEX?>" type="text" maxlength="5" size="5" />  to 
				<input id="dst_endport_<?=$INDEX?>" type="text" maxlength="5" size="5" />
			</td>			
		</tr>