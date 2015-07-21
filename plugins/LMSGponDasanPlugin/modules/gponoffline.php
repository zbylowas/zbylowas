<?php

if (!ConfigHelper::checkConfig('gpon-dasan.enabled')) {
	$layout['pagetitle'] = 'GPON';
	$body = '<P>Moduł GPON jest wyłączony. Włącz w konfiguracji opcję <b>gpon-dasan.enabled</b></P>';

	$SMARTY->assign('body',$body);
	$SMARTY->display('dialog.html');
}

?>
