<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$orgid = $user->orgid;
$placesid = GetDef('placesid');
$addnone = GetDef('addnone');
$oldopgroup = '';
if ($user->TestRoles('1,3,4,5,6')) {
	$SQL = "SELECT * FROM places WHERE orgid='$orgid' AND active=1 ORDER BY binary(opgroup), binary(name)";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу выбрать список помещений!'.mysqli_error($sqlcn->idsqlconnection));
	$sts = '<select name="splaces" id="splaces">';
	if ($addnone == 'true') {
		$sts .= '<option value="-1">нет выбора</option>';
	};

	if (isset($user->mode)==false){$user->mode=0;};
	if (isset($user->orgid)==false){$user->orgid=-1;};
	
	$flag = 0;
	while ($row = mysqli_fetch_array($result)) {
		$vl = $row['id'];
		if (($user->mode==1) or ($user->orgid==$row['orgid'])){
		    $opgroup = $row['opgroup'];
		    if ($opgroup != $oldopgroup) {
			    if ($flag != 0) {
				    $sts .= '</optgroup>';
			    }
			    $sts .= '<optgroup label="'.$opgroup.'">';
			    $flag = 1;
		    }
		    $sts .= '<option value="'.$vl.'" ';
		    if ($placesid == $row['id']) {
			    $sts .= 'selected';
		    }
		    $sts .= '>'.$row['name'].'</option>';
		    $oldopgroup = $opgroup;
	    };
	}
	$sts .= '</optgroup>';
	$sts .= '</select>';
	echo $sts;
} else {
	echo 'Не достаточно прав!!!';
}
