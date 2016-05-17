<?php
include '../config.php';
$prihod = isset($_GET["prihodx"]) ? $_GET["prihodx"] : 0;
$period = isset($_GET["periodx"]) ? $_GET["periodx"] : 0;
$datod = isset($_GET["datod"]) ? $_GET["datod"] : 0;
$datdo = isset($_GET["datdo"]) ? $_GET["datdo"] : 0;

if ($prihod==2) $prihod="zarada";
else $prihod="zauplatu";
$passhtml=array();
$grafikon="";
$podaci=array();

if ($period==1) {
	$sql="SELECT MAX($prihod) AS MaxUplata
			FROM (SELECT SUM($prihod) AS $prihod
				FROM prodaja
				GROUP BY month(datprometa)) AS tmp";
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	$MaxUplata=$row['MaxUplata'];
	if ($MaxUplata>20000000) {
			$gradval=5;
			$top=50000000;
			$unit='M';
		}
	elseif ($MaxUplata>10000000) {
			$gradval=2;
			$top=20000000;
			$unit='M';
		}
	elseif ($MaxUplata>5000000) {
			$gradval=1;
			$top=10000000;
			$unit='M';
		}
	elseif ($MaxUplata>2000000) {
			$gradval=0.5;
			$top=5000000;
			$unit='M';
		}
	elseif ($MaxUplata>1000000) {
			$gradval=0.2;
			$top=2000000;
			$unit='M';
		}
	elseif ($MaxUplata>500000) {
			$gradval=100;
			$top=1000000;
			$unit='k';
		}
	elseif ($MaxUplata>200000) {
			$gradval=50;
			$top=500000;
			$unit='k';
		}
	elseif ($MaxUplata>100000) {
			$gradval=20;
			$top=200000;
			$unit='k';
		}
	elseif ($MaxUplata>50000) {
			$gradval=10;
			$top=100000;
			$unit='k';
		}
	else {
			$gradval=5;
			$top=50000;
			$unit='k';
	}
	$skala='<div style="height:620px;margin-top:12px; padding-right: 15px; width:45px;float:left;"><div class="skala">'.($gradval*10).$unit.'</div><div class="skala">'.($gradval*9).$unit.'</div><div class="skala">'.($gradval*8).$unit.'</div><div class="skala">'.($gradval*7).$unit.'</div><div class="skala">'.($gradval*6).$unit.'</div><div class="skala">'.($gradval*5).$unit.'</div><div class="skala">'.($gradval*4).$unit.'</div><div class="skala">'.($gradval*3).$unit.'</div><div class="skala">'.($gradval*2).$unit.'</div><div class="skala">'.($gradval*1).$unit.'</div><div class="skala">0</div></div>';
	$grafikon='';
	$sql='SELECT MONTH(datprometa) mesec, YEAR (datprometa) godina, SUM('.$prihod.') zauplatu FROM prodaja WHERE brpracuna<>"" GROUP BY year(datprometa), month(datprometa) ORDER BY datprometa DESC';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		$godina=$row['godina'];
		$mesec=$row['mesec'];
		$promet=$row['zauplatu'];
		$podaci[$mesec.'-'.$godina]['vp']=$promet;
		$podaci[$mesec.'-'.$godina]['mesec']=$mesec;
		$podaci[$mesec.'-'.$godina]['godina']=$godina;
	}
	$sql='SELECT MONTH(datprometa) mesec, YEAR (datprometa) godina, SUM('.$prihod.') zauplatu FROM prodaja WHERE brpracuna="" GROUP BY year(datprometa), month(datprometa) ORDER BY datprometa DESC';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		$godina=$row['godina'];
		$mesec=$row['mesec'];
		$promet=$row['zauplatu'];
		$podaci[$mesec.'-'.$godina]['mp']=$promet;
		$podaci[$mesec.'-'.$godina]['mesec']=$mesec;
		$podaci[$mesec.'-'.$godina]['godina']=$godina;
	}
	foreach ($podaci as $podatak) {
		if (empty($podatak['mp'])) $podatak['mp']=0;
		if (empty($podatak['vp'])) $podatak['vp']=0;
		$mpgraf=round($podatak['mp']/$top*500);
		$vpgraf=round($podatak['vp']/$top*500);
		$podatak['mp']=round($podatak['mp']);
		$podatak['vp']=round($podatak['vp']);
		$ukupno=$podatak['mp']+$podatak['vp'];
		$podatak['mp']=number_format($podatak['mp'], 0, '', ',');
		$podatak['vp']=number_format($podatak['vp'], 0, '', ',');
		$ukupno=number_format($ukupno, 0, '', ',');
		$prazgraf=500-$mpgraf-$vpgraf;
		if ($vpgraf<0) {
		$mpgraf=$mpgraf+$vpgraf;
		$prazgraf=$prazgraf+$vpgraf;
		}
		$grafikon.='<span class="element" style="width:60px">';
		if ($prazgraf!=0) $grafikon.='<div class="elprazno" style="height:'.$prazgraf.'px;width:60px"></div>';
		if ($vpgraf!=0) $grafikon.='<div class="elvp" style="height:'.$vpgraf.'px;width:60px">'.$podatak['vp'].'</div>';
		if ($mpgraf!=0) $grafikon.='<div class="elmp" style="height:'.$mpgraf.'px;width:60px">'.$podatak['mp'].'</div>';
		$grafikon.='<div class="elcom" style="width:60px">mp:<br/>'.$podatak['mp'].'<br/>vp:<br/>'.$podatak['vp'].'<br/>ukupno:<br/>'.$ukupno.'<br/>period:<br/>'.$podatak['mesec'].'.'.$podatak['godina'].'.</div></span>';
	}

}
elseif ($period==2) {
	$sql="SELECT MAX($prihod) AS MaxUplata
			FROM (SELECT SUM($prihod) AS $prihod
				FROM prodaja
				GROUP BY year(datprometa), week(datprometa)) AS tmp";
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	$MaxUplata=$row['MaxUplata'];
	if ($MaxUplata>5000000) {
			$gradval=1;
			$top=10000000;
			$unit='M';
		}
	elseif ($MaxUplata>2000000) {
			$gradval=0.5;
			$top=5000000;
			$unit='M';
		}
	elseif ($MaxUplata>1000000) {
			$gradval=0.2;
			$top=2000000;
			$unit='M';
		}
	elseif ($MaxUplata>500000) {
			$gradval=100;
			$top=1000000;
			$unit='k';
		}
	elseif ($MaxUplata>200000) {
			$gradval=50;
			$top=500000;
			$unit='k';
		}
	elseif ($MaxUplata>100000) {
			$gradval=20;
			$top=200000;
			$unit='k';
		}
	elseif ($MaxUplata>50000) {
			$gradval=10;
			$top=100000;
			$unit='k';
		}
	elseif ($MaxUplata>20000) {
			$gradval=5;
			$top=50000;
			$unit='k';
		}
	elseif ($MaxUplata>10000) {
			$gradval=2;
			$top=20000;
			$unit='k';
		}
	else {
			$gradval=1;
			$top=10000;
			$unit='k';
	}
	$skala='<div style="height:620px;margin-top:12px; padding-right: 15px; width:45px;float:left;"><div class="skala">'.($gradval*10).$unit.'</div><div class="skala">'.($gradval*9).$unit.'</div><div class="skala">'.($gradval*8).$unit.'</div><div class="skala">'.($gradval*7).$unit.'</div><div class="skala">'.($gradval*6).$unit.'</div><div class="skala">'.($gradval*5).$unit.'</div><div class="skala">'.($gradval*4).$unit.'</div><div class="skala">'.($gradval*3).$unit.'</div><div class="skala">'.($gradval*2).$unit.'</div><div class="skala">'.($gradval*1).$unit.'</div><div class="skala">0</div></div>';
	$grafikon='';
	$sql='SELECT WEEK(datprometa) sedmica, YEAR (datprometa) godina, SUM('.$prihod.') zauplatu FROM prodaja WHERE brpracuna<>"" GROUP BY year(datprometa), week(datprometa) ORDER BY datprometa DESC';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		$godina=$row['godina'];
		$sedmica=$row['sedmica'];
		$promet=$row['zauplatu'];
		$podaci[$sedmica.'-'.$godina]['vp']=$promet;
		$podaci[$sedmica.'-'.$godina]['sedmica']=$sedmica;
		$podaci[$sedmica.'-'.$godina]['godina']=$godina;
	}
	$sql='SELECT WEEK(datprometa) sedmica, YEAR (datprometa) godina, SUM('.$prihod.') zauplatu FROM prodaja WHERE brpracuna="" GROUP BY year(datprometa), week(datprometa) ORDER BY datprometa DESC';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		$godina=$row['godina'];
		$sedmica=$row['sedmica'];
		$promet=$row['zauplatu'];
		$podaci[$sedmica.'-'.$godina]['mp']=$promet;
		$podaci[$sedmica.'-'.$godina]['sedmica']=$sedmica;
		$podaci[$sedmica.'-'.$godina]['godina']=$godina;
	}
	foreach ($podaci as $podatak) {
		if (empty($podatak['mp'])) $podatak['mp']=0;
		if (empty($podatak['vp'])) $podatak['vp']=0;
		$mpgraf=round($podatak['mp']/$top*500);
		$vpgraf=round($podatak['vp']/$top*500);
		$podatak['mp']=round($podatak['mp']);
		$podatak['vp']=round($podatak['vp']);
		$ukupno=$podatak['mp']+$podatak['vp'];
		$podatak['mp']=number_format($podatak['mp'], 0, '', ',');
		$podatak['vp']=number_format($podatak['vp'], 0, '', ',');
		$ukupno=number_format($ukupno, 0, '', ',');
		$prazgraf=500-$mpgraf-$vpgraf;
		if ($vpgraf<0) {
		$mpgraf=$mpgraf+$vpgraf;
		$prazgraf=$prazgraf+$vpgraf;
		}
		$grafikon.='<span class="element" style="width:60px">';
		if ($prazgraf!=0) $grafikon.='<div class="elprazno" style="height:'.$prazgraf.'px;width:60px"></div>';
		if ($vpgraf!=0) $grafikon.='<div class="elvp" style="height:'.$vpgraf.'px;width:60px">'.$podatak['vp'].'</div>';
		if ($mpgraf!=0) $grafikon.='<div class="elmp" style="height:'.$mpgraf.'px;width:60px">'.$podatak['mp'].'</div>';
		$grafikon.='<div class="elcom" style="width:60px">mp:<br/>'.$podatak['mp'].'<br/>vp:<br/>'.$podatak['vp'].'<br/>ukupno:<br/>'.$ukupno.'<br/>period:<br/>'.$podatak['sedmica'].'-'.$podatak['godina'].'.</div></span>';
	}

}
elseif ($period==3) {
	$sdatum="";
	$sql="SELECT MAX($prihod) AS MaxUplata
			FROM (SELECT SUM($prihod) AS $prihod
				FROM prodaja
				GROUP BY datprometa) AS tmp";
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	$MaxUplata=$row['MaxUplata'];
	if ($MaxUplata>5000000) {
			$gradval=1;
			$top=10000000;
			$unit='M';
		}
	elseif ($MaxUplata>2000000) {
			$gradval=0.5;
			$top=5000000;
			$unit='M';
		}
	elseif ($MaxUplata>1000000) {
			$gradval=0.2;
			$top=2000000;
			$unit='M';
		}
	elseif ($MaxUplata>500000) {
			$gradval=100;
			$top=1000000;
			$unit='k';
		}
	elseif ($MaxUplata>200000) {
			$gradval=50;
			$top=500000;
			$unit='k';
		}
	elseif ($MaxUplata>100000) {
			$gradval=20;
			$top=200000;
			$unit='k';
		}
	elseif ($MaxUplata>50000) {
			$gradval=10;
			$top=100000;
			$unit='k';
		}
	elseif ($MaxUplata>20000) {
			$gradval=5;
			$top=50000;
			$unit='k';
		}
	elseif ($MaxUplata>10000) {
			$gradval=2;
			$top=20000;
			$unit='k';
		}
	else {
			$gradval=1;
			$top=10000;
			$unit='k';
	}
	$skala='<div style="height:620px;margin-top:12px; padding-right: 15px; width:45px;float:left;"><div class="skala">'.($gradval*10).$unit.'</div><div class="skala">'.($gradval*9).$unit.'</div><div class="skala">'.($gradval*8).$unit.'</div><div class="skala">'.($gradval*7).$unit.'</div><div class="skala">'.($gradval*6).$unit.'</div><div class="skala">'.($gradval*5).$unit.'</div><div class="skala">'.($gradval*4).$unit.'</div><div class="skala">'.($gradval*3).$unit.'</div><div class="skala">'.($gradval*2).$unit.'</div><div class="skala">'.($gradval*1).$unit.'</div><div class="skala">0</div></div>';
	$grafikon='';
	$sql='SELECT datprometa, '.$prihod.' zauplatu, IF(brpracuna="",1,2) vrsta FROM prodaja ORDER BY datprometa DESC';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		$datum=$row['datprometa'];
		$promet=$row['zauplatu'];
		$vrsta=$row['vrsta'];
		if ($sdatum!=$datum) {
			$podaci[$datum]['mp']=0;
			$podaci[$datum]['vp']=0;
		}
		if ($vrsta==1) $podaci[$datum]['mp']=$podaci[$datum]['mp']+$promet;
			else $podaci[$datum]['vp']=$podaci[$datum]['vp']+$promet;
		$sdatum=$datum;
		$podaci[$datum]['datum']=$datum;
	}
	foreach ($podaci as $podatak) {
		$mpgraf=round($podatak['mp']/$top*500);
		$vpgraf=round($podatak['vp']/$top*500);
		$podatak['mp']=round($podatak['mp']);
		$podatak['vp']=round($podatak['vp']);
		$ukupno=$podatak['mp']+$podatak['vp'];
		$podatak['mp']=number_format($podatak['mp'], 0, '', ',');
		$podatak['vp']=number_format($podatak['vp'], 0, '', ',');
		$ukupno=number_format($ukupno, 0, '', ',');
		$prazgraf=500-$mpgraf-$vpgraf;
		if ($vpgraf<0) {
		$mpgraf=$mpgraf+$vpgraf;
		$prazgraf=$prazgraf+$vpgraf;
		}
		$datum=date('d.m.Y.',strtotime($podatak['datum']));
		$grafikon.='<span class="element">';
		if ($prazgraf!=0) $grafikon.='<div class="elprazno" style="height:'.$prazgraf.'px"></div>';
		if ($vpgraf!=0) $grafikon.='<div class="elvp" style="height:'.$vpgraf.'px">'.$podatak['vp'].'</div>';
		if ($mpgraf!=0) $grafikon.='<div class="elmp" style="height:'.$mpgraf.'px">'.$podatak['mp'].'</div>';
		$grafikon.='<div class="elcom">mp:<br/>'.$podatak['mp'].'<br/>vp:<br/>'.$podatak['vp'].'<br/>ukupno:<br/>'.$ukupno.'<br/>datum:<br/>'.$datum.'</div></span>';
	}
}
elseif ($period==4) {
	$sdatum="";
	$sql="SELECT MAX($prihod) AS MaxUplata
			FROM (SELECT SUM($prihod) AS $prihod
				FROM prodaja WHERE datprometa > '$datod' AND datprometa < '$datdo'
				GROUP BY datprometa) AS tmp";
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	$MaxUplata=$row['MaxUplata'];
	if ($MaxUplata>5000000) {
			$gradval=1;
			$top=10000000;
			$unit='M';
		}
	elseif ($MaxUplata>2000000) {
			$gradval=0.5;
			$top=5000000;
			$unit='M';
		}
	elseif ($MaxUplata>1000000) {
			$gradval=0.2;
			$top=2000000;
			$unit='M';
		}
	elseif ($MaxUplata>500000) {
			$gradval=100;
			$top=1000000;
			$unit='k';
		}
	elseif ($MaxUplata>200000) {
			$gradval=50;
			$top=500000;
			$unit='k';
		}
	elseif ($MaxUplata>100000) {
			$gradval=20;
			$top=200000;
			$unit='k';
		}
	elseif ($MaxUplata>50000) {
			$gradval=10;
			$top=100000;
			$unit='k';
		}
	elseif ($MaxUplata>20000) {
			$gradval=5;
			$top=50000;
			$unit='k';
		}
	elseif ($MaxUplata>10000) {
			$gradval=2;
			$top=20000;
			$unit='k';
		}
	else {
			$gradval=1;
			$top=10000;
			$unit='k';
	}
	$skala='<div style="height:620px;margin-top:12px; padding-right: 15px; width:45px;float:left;"><div class="skala">'.($gradval*10).$unit.'</div><div class="skala">'.($gradval*9).$unit.'</div><div class="skala">'.($gradval*8).$unit.'</div><div class="skala">'.($gradval*7).$unit.'</div><div class="skala">'.($gradval*6).$unit.'</div><div class="skala">'.($gradval*5).$unit.'</div><div class="skala">'.($gradval*4).$unit.'</div><div class="skala">'.($gradval*3).$unit.'</div><div class="skala">'.($gradval*2).$unit.'</div><div class="skala">'.($gradval*1).$unit.'</div><div class="skala">0</div></div>';
	$grafikon='';
	$sql='SELECT datprometa, '.$prihod.' zauplatu, IF(brpracuna="",1,2) vrsta FROM prodaja WHERE datprometa > \''.$datod.'\' AND datprometa < \''.$datdo.'\' ORDER BY datprometa DESC';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		$datum=$row['datprometa'];
		$promet=$row['zauplatu'];
		$vrsta=$row['vrsta'];
		if ($sdatum!=$datum) {
			$podaci[$datum]['mp']=0;
			$podaci[$datum]['vp']=0;
		}
		if ($vrsta==1) $podaci[$datum]['mp']=$podaci[$datum]['mp']+$promet;
			else $podaci[$datum]['vp']=$podaci[$datum]['vp']+$promet;
		$sdatum=$datum;
		$podaci[$datum]['datum']=$datum;
	}
	foreach ($podaci as $podatak) {
		$mpgraf=round($podatak['mp']/$top*500);
		$vpgraf=round($podatak['vp']/$top*500);
		$podatak['mp']=round($podatak['mp']);
		$podatak['vp']=round($podatak['vp']);
		$ukupno=$podatak['mp']+$podatak['vp'];
		$podatak['mp']=number_format($podatak['mp'], 0, '', ',');
		$podatak['vp']=number_format($podatak['vp'], 0, '', ',');
		$ukupno=number_format($ukupno, 0, '', ',');
		$prazgraf=500-$mpgraf-$vpgraf;
		if ($vpgraf<0) {
		$mpgraf=$mpgraf+$vpgraf;
		$prazgraf=$prazgraf+$vpgraf;
		}
		$datum=date('d.m.Y.',strtotime($podatak['datum']));
		$grafikon.='<span class="element">';
		if ($prazgraf!=0) $grafikon.='<div class="elprazno" style="height:'.$prazgraf.'px"></div>';
		if ($vpgraf!=0) $grafikon.='<div class="elvp" style="height:'.$vpgraf.'px">'.$podatak['vp'].'</div>';
		if ($mpgraf!=0) $grafikon.='<div class="elmp" style="height:'.$mpgraf.'px">'.$podatak['mp'].'</div>';
		$grafikon.='<div class="elcom">mp:<br/>'.$podatak['mp'].'<br/>vp:<br/>'.$podatak['vp'].'<br/>ukupno:<br/>'.$ukupno.'<br/>datum:<br/>'.$datum.'</div></span>';
	}

}

$passhtml['skala']=$skala;
$passhtml['grafikon']=$grafikon;
echo json_encode($passhtml);
?>