<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;$noE=0;$noC=0;$noD=0;
$users = $override->getData('user');
if($user->isLoggedIn()) {

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
                            <span class="number">4</span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock green clearfix">
                        <div class="dSpace">
                            <h3>Available Rooms</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                            <span class="number">4</span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock blue clearfix">
                        <div class="dSpace">
                            <h3>Non Alcoholic Drinks</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number">4</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock yellow clearfix">
                        <div class="dSpace">
                            <h3>Beers</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>

                            <span class="number">4</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock orange clearfix">
                        <div class="dSpace">
                            <h3>Wine</h3>

                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number">4</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock grey clearfix">
                        <div class="dSpace">
                            <h3>Spirit</h3>

                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number">4</span>
                        </div>

                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>

            <div class="row">
                <div class="col-md-12">
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
                            <?php foreach ($override->getData('rooms') as $room){?>
                                <tr>
                                    <td><?=$room['name']?></td>
                                    <td><?=number_format($room['price'])?> Tsh</td>
                                    <td>
                                        <?php if($room['status']==1){?>
                                            <button class="btn btn-sm btn-success" type="button">Occupied</button>
                                        <?php }elseif ($room['status']==0){?>
                                            <button class="btn btn-sm btn-info" type="button">Available</button>
                                        <?php }?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-default" type="button">Allocate</button>
                                    </td>
                                </tr>
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
