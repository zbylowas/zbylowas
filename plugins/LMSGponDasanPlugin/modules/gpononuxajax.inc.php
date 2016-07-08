<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2016 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */

function ONU_UpdateProperties($xmlprovisioning, $modelid) {
	global $GPON, $SMARTY;

	// xajax response
	$objResponse = new xajaxResponse();

	$wifisettings = 'none';
	if ($xmlprovisioning) {
		$ports = $GPON->GetGponOnuModelPorts($modelid);
		if (isset($ports['wifi']))
			$wifisettings = '';
	}
	$objResponse->assign("wifisettings", "style.display", $wifisettings);

	if ($xmlprovisioning || ConfigHelper::checkConfig('gpon-dasan.use_radius')) {
		$modelports = $GPON->GetGponOnuModelPorts($modelid);
		$onuports = $GPON->GetGponOnuPorts($_GET['id']);
		$netdevinfo['portsettings'] = $GPON->GetGponOnuAllPorts($modelports, $onuports);
		$SMARTY->assign('netdevinfo', $netdevinfo);
		$contents = $SMARTY->fetch('gpononu/gpononuports.html');
		$objResponse->assign("portsettingstable", "innerHTML", $contents);
		$portsettings = '';
	} else
		$portsettings = 'none';
	$objResponse->assign("portsettings", "style.display", $portsettings);

	return $objResponse;
}

function ONU_GenerateWifiSettings($onuid) {
	global $GPON;

	// xajax response
	$objResponse = new xajaxResponse();

	$onu = $GPON->GetGponOnu($onuid);

	$wifi_ssid = ConfigHelper::getConfig('gpon-dasan.xml_provisioning_default_wifi_ssid', '');
	$wifi_ssid = str_replace('%sn%', $onu['name'], $wifi_ssid);
	$objResponse->assign("wifi_ssid", "value", $wifi_ssid);

	$wifi_password = ConfigHelper::getConfig('gpon-dasan.xml_provisioning_default_wifi_password', '');
	if (preg_match('/%(?<chars>[0-9]+)?random%/', $wifi_password, $m)) {
		$chars = isset($m['chars']) ? intval($m['chars']) : 12;
		$wifi_password = preg_replace('/%[0-9]*random%/', generate_random_string($chars), $wifi_password);
	}
	$objResponse->assign("wifi_password", "value", $wifi_password);

	return $objResponse;
}

?>
