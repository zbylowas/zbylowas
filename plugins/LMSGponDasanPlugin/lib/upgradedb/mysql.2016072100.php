<?php

$this->BeginTrans();

$this->Execute("ALTER TABLE gpononumodels CHANGE xmltemplate xmltemplate DEFAULT ''");

$this->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2016072100', 'dbversion_LMSGponDasanPlugin'));

$this->CommitTrans();

?>
