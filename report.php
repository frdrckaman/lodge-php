<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;
if($user->isLoggedIn()) {
    if(Input::exists('post')){
        $validate = new validate();
        if(Input::get('search')){
            $validate = $validate->check($_POST, array(
                'start_date' => array(
                    'required' => true,
                ),
                'end_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $link='report.php?id=1&start='.Input::get('start_date').'&end='.Input::get('end_date');
                Redirect::to($link);
            } else {
                $pageError = $validate->errors();
            }
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Report - WHYNOT INN </title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Report</a> <span class="divider">></span></li>
            </ul>
            <?php include 'pageInfo.php'?>
        </div>

        <div class="workplace">
            <?php if($errorMessage){?>
                <div class="alert alert-danger">
                    <h4>Error!</h4>
                    <?=$errorMessage?>
                </div>
            <?php }elseif($pageError){?>
                <div class="alert alert-danger">
                    <h4>Error!</h4>
                    <?php foreach($pageError as $error){echo $error.' , ';}?>
                </div>
            <?php }elseif($successMessage){?>
                <div class="alert alert-success">
                    <h4>Success!</h4>
                    <?=$successMessage?>
                </div>
            <?php }?>

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="head clearfix">
                        <div class="isw-ok"></div>
                        <h1>Search Report</h1>
                    </div>
                    <div class="block-fluid">
                        <form id="validation" method="post" >
                            <div class="row-form clearfix">
                                <div class="col-md-2">Start Date:</div>
                                <div class="col-md-3">
                                    <input value="" class="validate[required,custom[date]]" type="text" name="start_date" id="date"/>
                                    <span>Example: 2010-12-01</span>
                                </div>
                                <div class="col-md-2">End Date:</div>
                                <div class="col-md-3">
                                    <input value="" class="validate[required,custom[date]]" type="text" name="end_date" id="date"/>
                                    <span>Example: 2010-12-01</span>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" name="search" value="Search" class="btn btn-default">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <?php if($_GET['id'] == 1 && $user->data()->accessLevel == 1){?>
                    <div class="row">
                        <?php $income=0;$pending=0;$expenses=0;$expect=0;$discount=0;$start=$_GET['start'];$end=$_GET['end']?>
                        <div class="col-md-6">
                            <div class="block-fluid without-head">
                                <div class="toolbar nopadding-toolbar clearfix">
                                    <h4>Room Report</h4>
                                </div>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table images">
                                    <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th>Client Name</th>
                                        <th width="80">No Days</th>
                                        <th width="60">Room</th>
                                        <th width="80">Paid</th>
                                        <th width="80">Discount</th>
                                        <th width="80">Total cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;$ttl=0;$pyd=0;$dsc=0;foreach ($override->dateRange('payment','create_on',$start,$end) as $report){
                                        $client=$override->get('clients','id',$report['client_id'])[0];$room=$override->get('rooms','id',$report['room_id'])[0];
                                        $pyd+=$report['payed'];$ttl+=$report['amount'];$dsc+=$report['discount']?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td class="info"><a class="fancybox" rel="group" href="#"><?=$client['firstname'].' '.$client['lastname']?></a>  <span><?=$report['create_on']?></span></td>
                                            <td><a class="fancybox" rel="group" href="#"><?=$report['no_days']?></a></td>
                                            <td><?=$room['name']?></td>
                                            <td><a href="#"><?=number_format($report['payed']-$report['discount'])?></a></td>
                                            <td><a href="#"><?=number_format($report['discount'])?></a></td>
                                            <td><a href="#"><?=number_format($report['amount'])?></a></td>
                                        </tr>
                                    <?php $x++;}$income+=$pyd;$expect+=$ttl;$pending=$expect-$pyd;$discount+=$dsc?>
                                        <tr>
                                            <td></td>
                                            <td class="info"><a class="fancybox" rel="group" href="#"><strong>Total</strong></a></span></td>
                                            <td><a class="fancybox" rel="group" href="#"></a></td>
                                            <td></td>
                                            <td><a href="#"><strong><?=number_format($pyd-$dsc)?></strong></a></td>
                                            <td><a href="#"><strong><?=number_format($dsc)?></strong></a></td>
                                            <td><a href="#"><strong><?=number_format($ttl)?></strong></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block-fluid without-head">
                                <div class="toolbar nopadding-toolbar clearfix">
                                    <h4>Food Report</h4>
                                </div>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table images">
                                    <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th>Client Name</th>
                                        <th width="60">Room</th>
                                        <th width="80">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;$ttl=0;$pyd=0;foreach ($override->dateRange('food_sales','create_on',$start,$end) as $report){
                                        $client=$override->get('clients','id',$report['client_id'])[0];
                                        $pyd+=$report['payed'];$ttl+=$report['amount'];$room='Out client';
                                        if(!$report['client_id']==0){
                                            $py=$override->get('payment','food_pay',$report['id'])[0]['room_id'];
                                            $room=$override->get('rooms','id',$py)[0]['name'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td class="info"><a class="fancybox" rel="group" href="#">
                                                    <?php if($client){echo $client['firstname'].' '.$client['lastname'];}else{echo 'Out client';}?>
                                                </a>  <span><?=$report['create_on']?></span></td>
                                            <td><?=$room?></td>
                                            <td><a href="#"><?=number_format($report['amount'])?></a></td>
                                        </tr>
                                        <?php $x++;}$income+=$ttl?>
                                    <tr>
                                        <td></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong>Total</strong></a></span></td>
                                        <td></td>
                                        <td><a href="#"><strong><?=number_format($ttl)?></strong></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block-fluid without-head">
                                <div class="toolbar nopadding-toolbar clearfix">
                                    <h4>Drinks Report</h4>
                                </div>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table images">
                                    <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th>Client Name</th>
                                        <th width="60">Room</th>
                                        <th width="80">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;$ttl=0;$pyd=0;foreach ($override->dateRange('drink_sales','create_on',$start,$end) as $report){
                                        $client=$override->get('clients','id',$report['client_id'])[0];
                                        $pyd+=$report['payed'];$ttl+=$report['amount'];$room='Out client';
                                        if(!$report['client_id']==0){
                                            $py=$override->get('payment','drinks_pay',$report['id'])[0]['room_id'];
                                            $room=$override->get('rooms','id',$py)[0]['name'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td class="info"><a class="fancybox" rel="group" href="#">
                                                    <?php if($client){echo $client['firstname'].' '.$client['lastname'];}else{echo 'Out client';}?>
                                                </a>  <span><?=$report['create_on']?></span></td>
                                            <td><?=$room?></td>
                                            <td><a href="#"><?=number_format($report['amount'])?></a></td>
                                        </tr>
                                        <?php $x++;}$income+=$ttl?>
                                    <tr>
                                        <td></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong>Total</strong></a></span></td>
                                        <td></td>
                                        <td><a href="#"><strong><?=number_format($ttl)?></strong></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block-fluid without-head">
                                <div class="toolbar nopadding-toolbar clearfix">
                                    <h4>Expenses Report</h4>
                                </div>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table images">
                                    <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th>BIll Type</th>
                                        <th width="80">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;$ttl=0;$pyd=0;foreach ($override->dateRange('bill_payment','create_on',$start,$end) as $report){
                                        $bill=$override->get('bills','id',$report['bill_type'])[0];$ttl+=$report['amount'];?>
                                        <tr>
                                            <td><?=$x?></td>
                                            <td class="info"><a class="fancybox" rel="group" href="#"><?=$bill['name']?></a>  <span><?=$report['create_on']?></span></td>
                                            <td><a href="#"><?=number_format($report['amount'])?></a></td>
                                        </tr>
                                        <?php $x++;}$expenses+=$ttl;?>
                                    <tr>
                                        <td></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong>Total</strong></a></span></td>
                                        <td><a href="#"><strong><?=number_format($ttl)?></strong></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-offset-3 col-md-6">
                            <div class="block-fluid without-head">
                                <div class="toolbar nopadding-toolbar clearfix">
                                    <h4>Summary Report</h4>
                                </div>
                                <table cellpadding="0" cellspacing="0" width="100%" class="table images">
                                    <thead>
                                    <tr>
                                        <th>Income</th>
                                        <th>Discount</th>
                                        <th>Expected Amount</th>
                                        <th>Pending Amount</th>
                                        <th>Expenses</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong><?=number_format($income-$discount)?></strong></a>  </span></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong><?=number_format($discount)?></strong></a>  </span></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong><?=number_format($income)?></strong></a> </span></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong><?=number_format($pending)?></strong></a> </span></td>
                                        <td class="info"><a class="fancybox" rel="group" href="#"><strong><?=number_format($expenses)?></strong></a> </span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>

            <div class="dr"><span></span></div>
        </div>
    </div>
</div>
</body>
<script>
    <?php if($user->data()->pswd == 0){?>
    $(window).on('load',function(){
        $("#change_password_n").modal({
            backdrop: 'static',
            keyboard: false
        },'show');
    });
    <?php }?>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    $(document).ready(function(){
        $('#wait_ds').hide();
        $('#s2_1').change(function(){
            var getUid = $(this).val();
            $('#wait_ds').show();
            $.ajax({
                url:"process.php?cnt=cat",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){
                    $('#s2_2').html(data);
                    $('#wait_ds').hide();
                }
            });

        });

        $('#download').change(function(){
            var getUid = $(this).val();
            $.ajax({
                url:"process.php?cnt=download",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){

                }
            });

        });
    });
</script>

</html>
