<?php
/**
 * @comment  user online
 * @projectCode   57CMSS10
 * @tor  12
 * @package core
 * @author Pannawit
 * @access  private
 * @created  27/09/2014
 */
session_start();
#ลองสร้าง

if( !isset($_SESSION['refresh']) ){

	$_SESSION['refresh'] = 30;
}

include_once('config.php');

include_once('useronline.class.php');

$useronline = new useronline();

$exsumSite = $useronline->userGroupBySite();

$date = $useronline->gDateFormat(date('Y-m-d'));

$appname = $useronline->appname();

$appname['etc'] = 'อื่นๆ';

$etc = $exsumSite['etc'];

unset($exsumSite['etc']);

$where = '';

$type = ( isset($_GET['type']) ? strtoupper($_GET['type']) : 'ASC' );

$order = "ORDER BY IF( TRIM(appname) = '','z',LEFT(appname,5) ) ASC,timeupdate desc";

if( isset($_GET['group']) ){
	
	if( substr_count($_GET['group'], ':') > 0 ){
		
		$list = explode(':',$_GET['group']);
		
		array_pop($list);		
		
		$list = "'".implode("','",$list)."'";
		
		$where = " WHERE user.siteid NOT IN($list)";
	
	}else{
		
		$where = " WHERE user.siteid = '{$_GET['group']}' ";
	}
}

$orderBy['ip']['name'] = 'ip';

$orderBy['user']['name'] = 'view.name_th';

$orderBy['secname']['name'] = 'secname';

$orderBy['app']['name'] = 'appname';

$orderBy['time']['name'] = 'timeupdate';

$orderBy['file']['name'] = 'filename';

if( isset($_GET['order']) && isset($orderBy[$_GET['order']]['name']) ){
	
	$order = "ORDER BY ".$orderBy[$_GET['order']]['name']." ".$type;
	
	if( $type == 'ASC' ){
		
		$type = 'DESC';
	}else{
		
		$type = 'ASC';
	}
}

$user = $useronline->userList($where,$order);

?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620"/>
<meta http-equiv="refresh" content="<?php echo $_SESSION['refresh']?>">
<title>User Online</title>
<link href="css/default.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.8.2.js"></script>
<script>

var page = 1;

var longTheRefreshSetBox = function(x){

	w = $('.setRefresh').width();

	if( w == 60 ){
		
		$('.setRefresh').animate({width:'440px'},100);

		$(x).attr('src','images/left-arrow.png');

		$('.refreshValue').show();
		
	}else{

		$('.setRefresh').animate({width:'60px'},100);

		$(x).attr('src','images/down-arrow.png');	

		$('.refreshValue').hide();
	}

}

var setRefreshValue = function(x){

	$.post('setrefresh.php',{set:x},function(data){
		window.location.reload(true);
	});	
}

$(document).ready(function(){
		
	$('.changebg').hover(function(){

		$(this).find('td').css('background','url(images/data_bg_hover.png) repeat');
		
	},function(){

		$(this).find('td').css('background','transparent');
		
	});

	$('.pagination').hide();

	$(".pagination[page='"+page+"']").show();

	$(".paginationLink[page='"+page+"']").css('background','url(images/data_bg_hover.png) repeat');

	$('.paginationLink,.paginationLinkAll').hover(function(){

	//	alert('test');
		$(this).css('background','url(images/data_bg_hover.png) repeat');
		
	},function(){

		on = $(this).attr('active');

		if( on != 'on' ){	
			$(this).css('background','transparent');
		}
		
	});

	$('.paginationLink').click(function(){

		page = $(this).attr('page');

		$('.paginationLink,.paginationLinkAll').removeAttr('active');
		
		$('.paginationLink,.paginationLinkAll').css('background','transparent');

		$(this).css('background','url(images/data_bg_hover.png) repeat');
		
		$(this).attr('active','on');
			
		$('.pagination').hide();

		$(".pagination[page='"+page+"']").show();
	});

	$('.paginationLinkAll').click(function(){

		$('.paginationLink').removeAttr('active');
		
		$('.paginationLink').css('background','transparent');

		$(this).css('background','url(images/data_bg_hover.png) repeat');
		
		$(this).attr('active','on');	

		$('.pagination').show();
	});
})
</script>
</head>
<body>
<h2 style="color:#15CD34; text-align:right">
User Online
</h2>
<div style="color:#15CD34; position:absolute; top:70px; right:20px;">
<div style="display:block; float:left;padding:3px; margin:0 10px 0 0;">ตั้งค่า Refresh</div> 
<div class="setRefresh" style="display:block; float:left; color:#29C7B7; border: 1px solid #1FA093;width:60px; height:25px; overflow: hidden;padding-left:10px;
	-webkit-border-radius: 3px;-moz-border-radius: 3px;	border-radius: 3px;">
   <p style="width:440px; margin:0; padding:0;">	
	<span class="<?php echo ( $_SESSION['refresh'] == 15 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px; cursor:pointer;" onclick="setRefreshValue('15')">15 วินาที</span>
	<span class="<?php echo ( $_SESSION['refresh'] == 30 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px;cursor:pointer;" onclick="setRefreshValue('30')">30 วินาที</span>
	<span class="<?php echo ( $_SESSION['refresh'] == 45 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px;cursor:pointer;" onclick="setRefreshValue('45')">45 วินาที</span>
	<span class="<?php echo ( $_SESSION['refresh'] == 60 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px;cursor:pointer;" onclick="setRefreshValue('60')">1 นาที</span>
	<span class="<?php echo ( $_SESSION['refresh'] == 300 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px;cursor:pointer;" onclick="setRefreshValue('300')">5 นาที</span>
	<span class="<?php echo ( $_SESSION['refresh'] == 600 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px;cursor:pointer;" onclick="setRefreshValue('600')">10 นาที</span>
	<span class="<?php echo ( $_SESSION['refresh'] == 900 ? 'refreshValueActive' : 'refreshValue')?>" style="margin-right:10px;cursor:pointer;" onclick="setRefreshValue('900')">15 นาที</span>
   </p>	
</div>
<img src="images/down-arrow.png" style="width:15px; margin:5px 0 -5px 5px; cursor:pointer" onclick="longTheRefreshSetBox(this)" />
</div>
<p>
<a href="index.php" class="btnMenu">
	<span style="padding:10px 0 0 40px; display:block; ">จำแนกตามระบบที่เข้าใช้</span>
</a>

<a href="userbysite.php" class="clickActive">
	<span>จำแนกตามหน่วยงาน</span>
</a>
</p>
<p class="clear"> </p>
<div>
<div class="tableInfo" style="width:50%">
	<table class="tableRow" cellpadding='0' cellspacing='0'>
		<tr>
			<th>หน่วยงานที่เข้าใช้งาน</th>
			<th style="width:100px;text-align:right">จำนวน(คน)</th>
			<th style="width:110px">กิจกรรมล่าสุดเมื่อ</th>
		</tr>
		<?php 
			$sumall =  0;
			
			$param = '';
			
			$txtApp = array();
				
			$appVal = array();
			
			foreach($exsumSite as $key => $sum ){
		?>
			<tr class="changebg">
				<td ><?php echo $sum['secname']?></td>
				<td style="text-align: right"><a href="?group=<?php echo $key?>" ><?php echo number_format($sum['total'])?></a></td>
				<td style="text-align:center"><?php echo date('H.i',strtotime($sum['time']))?> น.</td>
			</tr>
		<?php 
				$sumall += $sum['total'];
				
				$param .= $key.':';
				
				array_push($txtApp,iconv('TIS-620','UTF-8',$sum['secname_short']));
				
				array_push($appVal,$sum['total']);
			}
			
			array_push($txtApp,iconv('TIS-620','UTF-8',$appname['etc']));
			
			array_push($appVal,$etc['total']);
		?>
		<tr class="changebg">
				<td><?php echo $appname['etc']?></td>
				<td style="text-align: right"><a href="?group=<?php echo $param?>" ><?php echo number_format($etc['total'])?></a></td>
				<td style="text-align:center"><?php echo date('H.i',strtotime($etc['time']))?> น.</td>
			</tr>
			
	 	<tr>
				<th>รวม</th>
				<th style="text-align: right"><a href="index.php" ><?php echo number_format(($sumall+$etc['total']))?></a></th>
				<th></th>
			</tr> 
	</table>
</div>

<?php 
require_once('nusoap.php');
$ws_client = new nusoap_client('http://soapservices.sapphire.co.th/index.php?wsdl',true);
$file = array('highcharts.js','highcharts-3d.js');
$script = json_encode($file);
$para = array('script' => $script);
$result = $ws_client->call('script', $para);
echo $result;

$data = array();
$data['chart'] = array('plotBackgroundColor'=>'null','plotBorderWidth'=>'null','plotShadow'=>'true');
$data['title'] = array('text'=>'');
$data['tooltip'] = array('pointFormat'=>'{series.name} {point.y}'); // or point.percentage:.2f
$data['plotOptions'] = array('allowPointSelect'=>'true','cursor'=>'pointer','depth'=>35);
$data['dataLabels'] = array('enabled'=>'true','format'=>'{point.name}');
$data['categories'] = array('name'=>iconv('TIS-620','UTF-8','จำนวน'));
$data['name'] = $txtApp;
$data['data'] = $appVal;
$data['sliced'] = array('false','false','true','false','false','false');$result = $ws_client->call('graph', $para);

/*กรณีที่ pie3D enable = false ไม่จำเป็นต้องเพิ่ม */
$data['pie3D'] = array('enable'=>'true');
$data['options3d'] = array('enabled'=>'true','alpha'=>45,'beta'=>0);

$data = json_encode($data);

$para =  array(
		"type"	=>	 'pie' ,
		"data"		=>	$data,
		"width"		=>	'520',
		"height"		=>	'260',
		"graphdiv" => 'chart',
		"format" => 'tis-620'
);
$result = $ws_client->call('graph_v2', $para);
?>
	<div style="width:520px; padding:5px; margin-left:40px; float:left; border:1px solid #1FA093;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;">
		<?php echo $result?>
	</div>
</div>
</div>

<p class="clear"> </p>

<p style="padding:10px 0">
<?php 
	
	$totalPage = ceil((count($user)/50));
		
	$rangePage = range(1,$totalPage);
	
	foreach ($rangePage as $eachPage ){
?>
<a href="javascript:void(0)" class="paginationLink" page="<?php echo $eachPage?>"><?php echo $eachPage?></a>
<?php 
	}
?>
<a href="javascript:void(0)" class="paginationLinkAll" >แสดงทั้งหมด</a>
</p>
<p class="clear"> </p>

<div class="tableInfo" style="width:100%">
	<table class="tableRow" cellpadding='0' cellspacing='0'>
		<tr>
			<th style="width:30px">ลำดับ</th>
			<th ><a href="?order=secname&type=<?php echo $type.(isset($_GET['group']) ? '&group='.$_GET['group'] : '' )?>" >สำนักงานเขต / สถานศึกษา</a></th>
			<th ><a href="?order=app&type=<?php echo $type.(isset($_GET['group']) ? '&group='.$_GET['group'] : '' )?>" >ระบบที่เข้าใช้</a></th>
			<th style="width:150px"><a href="?order=user&type=<?php echo $type.(isset($_GET['group']) ? '&group='.$_GET['group'] : '' )?>" >ผู้ใช้งาน</a></th>
			<th style="width:100px"><a href="?order=ip&type=<?php echo $type.(isset($_GET['group']) ? '&group='.$_GET['group'] : '' )?>" >ไอ.พี.แอดเดรส</a></th>
			<th><a href="?order=file&type=<?php echo $type.(isset($_GET['group']) ? '&group='.$_GET['group'] : '' )?>" >ข้อมูลที่เข้าใช้</a></th>
			<th style="width:100px"><a href="?order=time&type=<?php echo $type.(isset($_GET['group']) ? '&group='.$_GET['group'] : '' )?>" >เวลาที่เข้าใช้</a></th>
		</tr>
		<?php 
		$order = 1;
			
		$page = 1;
		
		$countpage = 1;
		
		 foreach( $user as $each => $dataArray ){

			$app = ( trim($dataArray['appname']) == '' ? 'etc' : trim($dataArray['appname']) );
			
			$site = $dataArray['siteid'];
			
			$dataField = sprintf('%d',$dataArray['username']);
		?>
			<tr class="changebg pagination" page="<?php echo $page ?>">
				<td style="text-align:center"><?php echo $order++?></td>
				<td><?php echo ( trim($dataArray['secname']) != '' ? $dataArray['secname'] : 'N/A')?></td>
				<td><?php echo ( isset($appname[$app]) ? $appname[$app] : 'อื่นๆ') ?></td>
				<td><?php echo ( strlen($dataField) == 13 ? 'oesa1.'.$dataArray['siteid'] : (trim($dataArray['username']) == '' ? 'N/A' : $dataArray['username'] )  )?></td>
				<td><?php echo $dataArray['ip']?></td>				
				<td><?php echo ( strlen($dataField) == 13 ? $dataArray['fullname'] :'N/A')?></td>
				<td><?php echo $useronline->convert_time($dataArray['timeupdate'])?> </td>
			</tr>
		<?php 
		
		if( $countpage >= 50 ){
		
			$page += 1;
		
			$countpage = 1;
		}
			
		$countpage++;
		}
		?>
	</table>
</div>
</body>
</html>