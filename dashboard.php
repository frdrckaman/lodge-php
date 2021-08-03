<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;$noE=0;$noC=0;$noD=0;
$users = $override->getData('user');
if($user->isLoggedIn()) {
    if(Input::exists('post')){
        if(Input::get('edit_room_status')){
            if(Input::get('status')==1){$st=0;}else{$st=1;}
            try {
                $user->updateRecord('room_assigned', array('status' => $st), Input::get('id'));
                $user->updateRecord('rooms',array('status'=>$st),Input::get('room_id'));
            } catch (Exception $e) {
                $e->getMessage();
            }
            $successMessage='Room Status changed successful';
        }
    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Dashboard | WHYNOT INN </title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a> <span class="divider">></span></li>
            </ul>

            <?php include 'pageInfo.php'?>

        </div>

        <div class="workplace">

            <div class="row">

                <div class="col-md-4">

                    <div class="wBlock red clearfix">
                        <div class="dSpace">
                            <h3>Occupied Rooms</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                            <span class="number"><?=$override->getCount('rooms','status',1)?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock green clearfix">
                        <div class="dSpace">
                            <h3>Available Rooms</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                            <span class="number"><?=$override->getCount('rooms','status',0)?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock blue clearfix">
                        <div class="dSpace">
                            <h3>Non Alcoholic Drinks</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number"><?=$override->getCount('drinks','cat_id',2)?></span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock yellow clearfix">
                        <div class="dSpace">
                            <h3>Alcoholic Drinks</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>

                            <span class="number"><?=$override->getCount('drinks','cat_id',1)?></span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock orange clearfix">
                        <div class="dSpace">
                            <h3>Staffs</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number"><?=$override->getNo('user')?></span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock grey clearfix">
                        <div class="dSpace">
                            <h3>Spirit</h3>

                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number"><?=$override->getNo('clients')?></span>
                        </div>

                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>

            <div class="row">
                <div class="col-md-12">
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
                    <div class="head clearfix">
                        <div class="isw-grid"></div>
                        <h1>Rooms</h1>
                        <ul class="buttons">
                            <li><a href="#" class="isw-download"></a></li>
                            <li><a href="#" class="isw-attachment"></a></li>
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                    <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                    <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                    <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="block-fluid">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table">
                            <thead>
                            <tr>
                                <th width="25%">Name</th>
                                <th width="25%">Price</th>
                                <th width="25%">Status</th>
                                <th width="25%">Manage</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($override->getData('rooms') as $room){
                                $status=$override->get('room_assigned','room_id',$room['id'])[0]?>
                                <tr>
                                    <td><?=$room['name']?></td>
                                    <td><?=number_format($room['price'])?> Tsh</td>
                                    <td>
                                        <?php if($status['status']==1){?>
                                            <button class="btn btn-sm btn-danger" type="button" disabled>Occupied</button>
                                        <?php }elseif ($status['status']==0){?>
                                            <button class="btn btn-sm btn-success" type="button" disabled>Available</button>
                                        <?php }?>
                                    </td>
                                    <td>
                                        <a href="#rStatus<?=$room['id']?>" class="btn btn-sm btn-default" type="button" data-toggle="modal">Status</a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="rStatus<?=$room['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4> Room Status </h4>
                                                </div>
                                                <div class="modal-body modal-body-np">
                                                    <?php if($status['status'] == 1){ $label='Room is occupied, change the status to unoccupied:';$rmS='';}
                                                    else{ $label='Room is unoccupied, change the status to occupied:';$rmS='checked';}?>
                                                    <div class="row">
                                                        <div class="col-md-9"><strong><?=$label?></strong></div>
                                                        <label class="switch">
                                                            <input type="checkbox" name="status" class="skip" value="<?=$status['status']?>" <?=$rmS?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="dr"><span></span></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="id" value="<?=$status['id']?>">
                                                    <input type="hidden" name="room_id" value="<?=$room['id']?>">
                                                    <input type="submit" name="edit_room_status" class="btn btn-warning"  aria-hidden="true" value="Save updates">
                                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="dr"><span></span></div>
        </div>

    </div>
</div>
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
</script>
</body>

</html>
