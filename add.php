<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage=null;$pageError=null;$errorMessage=null;
if($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_user')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'position' => array(
                    'required' => true,
                ),
                'username' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'email_address' => array(
                    'unique' => 'user'
                ),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '12345678';
                switch (Input::get('position')) {
                    case 1:
                        $accessLevel = 1;
                        break;
                    case 2:
                        $accessLevel = 2;
                        break;
                    case 3:
                        $accessLevel = 3;
                        break;
                }
                try {
                    $user->createRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'username' => Input::get('username'),
                        'position' => Input::get('position'),
                        'phone_number' => Input::get('phone_number'),
                        'password' => Hash::make($password,$salt),
                        'salt' => $salt,
                        'create_on' => date('Y-m-d'),
                        'last_login'=>'',
                        'status' => 1,
                        'power'=>0,
                        'email_address' => Input::get('email_address'),
                        'accessLevel' => $accessLevel,
                        'user_id'=>$user->data()->id,
                        'count' => 0,
                        'pswd'=>0,
                    ));
                    $successMessage = 'Account Created Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_position')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('position', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Position Successful Added';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_room')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'price' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('rooms', array(
                        'name' => Input::get('name'),
                        'price' => Input::get('price'),
                        'status' => 0,
                    ));
                    $successMessage = 'Room Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_drink_cat')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('drink_cat', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Drink Category Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_drink_brand')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'drink_category' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('drink_brand', array(
                        'name' => Input::get('name'),
                        'cat_id' => Input::get('drink_category'),
                    ));
                    $successMessage = 'Drink Brand Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_client')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'gender' => array(
                    'required' => true,
                ),
                'nationality' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'clients'
                ),
                'email_address' => array(
                    'unique' => 'clients'
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('clients', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'gender' => Input::get('gender'),
                        'address' => Input::get('address'),
                        'nationality'=>Input::get('nationality'),
                        'tribe' => Input::get('tribe'),
                        'place_of_birth' => Input::get('place_of_birth'),
                        'occupation' => Input::get('occupation'),
                        'passport_no' => Input::get('passport_no'),
                        'phone_number' => Input::get('phone_number'),
                        'email_address'=>Input::get('email_address'),
                        'create_on'=>date('Y-m-d'),
                        'status' => 1,
                        'staff_id'=>$user->data()->id,
                    ));
                    $successMessage = 'Client Created Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('assign_room')){
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'client' => array(
                    'required' => true,
                ),
                'room' => array(
                    'required' => true,
                ),
                'arrival_date' => array(
                    'required' => true,
                ),
                'departure_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {$amount=0;
                $price = $override->get('rooms','id',Input::get('room'))[0];
                $noDays = $user->dateDiff(Input::get('departure_date'),Input::get('arrival_date'));
                $assignR=$override->get('room_assigned','room_id',Input::get('room'))[0];
                if($assignR){$rm_st=true;$rm_v=$assignR['status'];}else{$rm_st=false;$rm_v=0;}
                $amount = $noDays * $price['price'];
                if(Input::get('payment') <= $amount){
                    if($rm_v == 0){
                        try {
                            if($assignR['room_id'] == Input::get('room')){
                                $user->updateRecord('room_assigned', array(
                                    'arrival_date' => Input::get('arrival_date'),
                                    'departure_date' => Input::get('departure_date'),
                                    'room_id' => Input::get('room'),
                                    'client_id' => Input::get('client'),
                                    'create_on'=>date('Y-m-d'),
                                    'status' => 1,
                                    'staff_id'=>$user->data()->id,
                                ),$assignR['id']);

                            }else{
                                $user->createRecord('room_assigned', array(
                                    'arrival_date' => Input::get('arrival_date'),
                                    'departure_date' => Input::get('departure_date'),
                                    'room_id' => Input::get('room'),
                                    'client_id' => Input::get('client'),
                                    'create_on'=>date('Y-m-d'),
                                    'status' => 1,
                                    'staff_id'=>$user->data()->id,
                                ));
                            }
                            $user->createRecord('room_assigned_rec', array(
                                'arrival_date' => Input::get('arrival_date'),
                                'departure_date' => Input::get('departure_date'),
                                'room_id' => Input::get('room'),
                                'client_id' => Input::get('client'),
                                'create_on'=>date('Y-m-d'),
                                'staff_id'=>$user->data()->id,
                            ));
                            if($amount == Input::get('payment')){$status=1;}else{$status=0;}
                            $user->createRecord('payment', array(
                                'amount' => $amount,
                                'payed' => Input::get('payment'),
                                'room_id' => Input::get('room'),
                                'no_days' => $noDays,
                                'client_id' => Input::get('client'),
                                'create_on'=>date('Y-m-d'),
                                'status' => $status,
                                'staff_id'=>$user->data()->id,
                            ));
                            if(Input::get('payment') > 0){
                                $user->createRecord('payment_rec', array(
                                    'amount' => Input::get('payment'),
                                    'no_days' => $noDays,
                                    'room_id' => Input::get('room'),
                                    'client_id' => Input::get('client'),
                                    'create_on'=>date('Y-m-d'),
                                    'staff_id'=>$user->data()->id,
                                ));
                            }
                            $user->updateRecord('rooms',array('status'=>1),Input::get('room'));
                            $successMessage = 'Room assigned to the Client Successful';

                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                    }else{
                        $errorMessage='Room is occupied, if not so please update room status to unoccupied then try again';
                    }
                }else{
                    $errorMessage='Paid Amount exceeded the required amount';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('add_drink')) {
            $validate = $validate->check($_POST, array(
                'drink_category' => array(
                    'required' => true,
                ),
                'drink_brand' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
                'price_per_item' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $drinks=$override->getNews('drinks','cat_id',Input::get('drink_category'),'brand_id',Input::get('drink_brand'));
                if($drinks){
                    $quantity=$drinks[0]['quantity']+Input::get('quantity');
                    try {
                        $user->updateRecord('drinks', array(
                            'quantity' => $quantity,
                            'price_per_item' => Input::get('price_per_item'),
                            'status' => 1,
                            'staff_id' => $user->data()->id,
                        ),$drinks[0]['id']);
                        $user->createRecord('drinks_rec', array(
                            'quantity' => Input::get('quantity'),
                            'price_per_item' => Input::get('price_per_item'),
                            'cat_id' => Input::get('drink_category'),
                            'brand_id' => Input::get('drink_brand'),
                            'create_on' => date('Y-m-d'),
                            'staff_id' => $user->data()->id,
                        ));
                        $successMessage = 'Drinks Added to the stock Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    try {
                        $user->createRecord('drinks', array(
                            'quantity' => Input::get('quantity'),
                            'price_per_item' => Input::get('price_per_item'),
                            'cat_id' => Input::get('drink_category'),
                            'brand_id' => Input::get('drink_brand'),
                            'status' => 1,
                            'staff_id' => $user->data()->id,
                        ));
                        $user->createRecord('drinks_rec', array(
                            'quantity' => Input::get('quantity'),
                            'price_per_item' => Input::get('price_per_item'),
                            'cat_id' => Input::get('drink_category'),
                            'brand_id' => Input::get('drink_brand'),
                            'create_on' => date('Y-m-d'),
                            'staff_id' => $user->data()->id,
                        ));
                        $successMessage = 'Drinks Added to the stock Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('sell_drink')) {
            if(Input::get('complete_sell')){
                $validate = $validate->check($_POST, array(
                    'amount' => array(
                        'required' => true,
                    ),
                ));
                if ($validate->passed()) {
                    if(Input::get('amount') == Input::get('total_cost')){
                        try {
                            $user->createRecord('drink_sales', array(
                                'amount' => Input::get('amount'),
                                'create_on' => date('Y-m-d'),
                                'staff_id' => $user->data()->id,
                            ));
                            $sale_id=$override->lastRow('drink_sales','id')[0];
                            $si=0;
                            foreach (Input::get('s_id') as $sid){
                                $q=Input::get('qt')[$si];
                            $user->createRecord('drink_sale_item', array(
                                'amount' => Input::get('prc')[$si],
                                'quantity' => $q,
                                'drinks_id' => $sid,
                                'sale_id' => $sale_id['id'],
                                'create_on' => date('Y-m-d'),
                                'staff_id' => $user->data()->id,
                            ));
                            $dr_stock = $override->get('drinks','id',$sid)[0];
                            $new_stock=$dr_stock['quantity']-$q;
                            $user->updateRecord('drinks',array(
                                    'quantity' => $new_stock,
                            ),$dr_stock['id']);
                                $si++;
                            }
                            $successMessage = 'Drinks Sold Successful';
                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                    }else{
                        $errorMessage='Amount entered is either insufficient  or exceeded the required amount, Please enter the correct amount';
                    }
                } else {
                    $pageError = $validate->errors();
                }
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
    <title> Add | WHYNOT INN </title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Simple Admin</a> <span class="divider">></span></li>
                <li class="active">Add Info</li>
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
                <?php if($_GET['id'] == 1 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add User</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">First Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Last Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Username:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="username" id="username"/>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Position</div>
                                    <div class="col-md-9">
                                        <select name="position" style="width: 100%;" required>
                                            <option value="">Select position</option>
                                            <?php foreach ($override->getData('position') as $position){?>
                                                <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Phone Number:</div>
                                    <div class="col-md-9"><input value="" class="" type="text" name="phone_number" id="phone" required />  <span>Example: 0700 000 111</span></div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">E-mail Address:</div>
                                    <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email_address" id="email" />  <span>Example: someone@nowhere.com</span></div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_user" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 2 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Position</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="name" id="name"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_position" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 3 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Room</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="name" id="name"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Price:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="price" id="price"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_room" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 4 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Drinks Category</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="name" id="name"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_drink_cat" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 5 && $user->data()->position == 1){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Drink Brand</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="name" id="name"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Drink Category</div>
                                    <div class="col-md-9">
                                        <select name="drink_category" id="region" style="width: 100%;" required>
                                            <option value="">Select category</option>
                                            <?php foreach ($override->getData('drink_cat') as $cat){?>
                                                <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_drink_brand" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 6){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Clients</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">First Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Last Name:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Gender</div>
                                    <div class="col-md-9">
                                        <select name="gender" style="width: 100%;" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Tribe:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="tribe" id="tribe"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Place of Birth:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="place_of_birth" id="birth_place"/>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Nationality:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="nationality" />
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Address:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="address" />
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Occupation</div>
                                    <div class="col-md-9">
                                        <select name="occupation" style="width: 100%;" required>
                                            <option value="">Select Occupation</option>
                                            <option value="Farmer">Farmer</option>
                                            <option value="Fisherman">Fisherman</option>
                                            <option value="Employed">Employed</option>
                                            <option value="Businessman">Businessman</option>
                                            <option value="Peasant">Peasant</option>
                                            <option value="Unemployed">Unemployed</option>
                                            <option value="Retired">Retired</option>
                                            <option value="Housewife">Housewife</option>
                                            <option value="Student">Student</option>
                                            <option value="Driver">Driver</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Passport Number:</div>
                                    <div class="col-md-9">
                                        <input value="" class="" type="text" name="passport_no" />
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Phone Number:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="phone_number" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Email Address:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[custom[email]]" type="email" name="email_address" />
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_client" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 7){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Assign Room</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Client:</div>
                                    <div class="col-md-9">
                                        <select name="client" id="s2_1" style="width: 100%;">
                                            <option value="">choose a client...</option>
                                            <?php foreach ($override->getData('clients') as $client){?>
                                                <option value="<?=$client['id']?>"><?=$client['firstname'].' '.$client['lastname'].' ( '.$client['phone_number'].' ) '?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Room:</div>
                                    <div class="col-md-9">
                                        <select name="room" id="" style="width: 100%;">
                                            <option value="">Choose a Room...</option>
                                            <?php foreach ($override->get('rooms','status',0) as $room){?>
                                                <option value="<?=$room['id']?>"><?=$room['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Arrival Date:</div>
                                    <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="arrival_date" id="date"/> <span>Example: 2010-12-01</span></div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Departure Date:</div>
                                    <div class="col-md-9"><input value="" class="validate[required,custom[date]]" type="text" name="departure_date" id="date"/> <span>Example: 2010-12-01</span></div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Payment:</div>
                                    <div class="col-md-9">
                                        <input value="0" class="" type="number" name="payment" id="payment"/>
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="assign_room" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 8){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Add Drinks</h1>
                        </div>
                        <div class="block-fluid">
                            <form id="validation" method="post" >

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Drink Category:</div>
                                    <div class="col-md-9">
                                        <select name="drink_category" id="s2_1" style="width: 100%;">
                                            <option value="">Choose Category...</option>
                                            <?php foreach ($override->getData('drink_cat') as $category){?>
                                                <option value="<?=$category['id']?>"><?=$category['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <span><img src="img/loaders/loader.gif" id="wait_ds" title="loader.gif"/></span>
                                    <div class="col-md-3">Drink Brand:</div>
                                    <div class="col-md-9">
                                        <select name="drink_brand" id="s2_2" style="width: 100%;">
                                            <option value="">choose brand...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Quantity:</div>
                                    <div class="col-md-9"><input value="" class="validate[required]" type="number" name="quantity" id="quantity"/> <span>Note: Count individual items</span></div>
                                </div>

                                <div class="row-form clearfix">
                                    <div class="col-md-3">Price per Item:</div>
                                    <div class="col-md-9"><input value="" class="validate[required]" type="number" name="price_per_item" id="cost"/> <span>Note: Cost of single item</span></div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_drink" value="Submit" class="btn btn-default">
                                </div>

                            </form>
                        </div>

                    </div>
                <?php }elseif ($_GET['id'] == 9){?>
                    <div class="col-md-offset-1 col-md-8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Sell Drinks</h1>
                        </div>
                        <div class="block-fluid">
                            <h5>&nbsp;</h5>
                            <form id="validation" method="post" >
                                <?php if(!Input::get('drink') && !Input::get('drink_1')){?>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Select Drinks:</div>
                                        <div class="col-md-9">
                                            <select name="drink[]" id="s2_2" style="width: 100%;" multiple="multiple" required>
                                                <option value="">Choose Drink...</option>
                                                <?php foreach ($override->get('drinks','status', 1) as $drinks){
                                                    $brand=$override->get('drink_brand','id',$drinks['brand_id'])[0];?>
                                                    <option value="<?=$drinks['id']?>"><?=$brand['name']?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="footer tar">
                                        <input type="submit" name="sell_drink" value="Submit" class="btn btn-default">
                                    </div>
                                <?php }?>
                                <?php if (Input::get('drink')){$f=0;
                                    foreach (Input::get('drink') as $drk){
                                        $dnk=$override->get('drinks','id',$drk)[0];
                                        $brnd=$override->get('drink_brand','id',$dnk['brand_id'])[0];?>
                                        <div class="row-form clearfix">
                                            <div class="col-md-2"><strong><?=$brnd['name']?> : </strong></div>
                                            <input type="hidden" name="drink_1[<?=$f?>]" value="<?=$drk?>">
                                            <div class="col-md-3"><input value="" class="validate[required]" type="number" name="quantity[]" id="quantity"/> </div>
                                        </div>
                                <?php $f++;}?>
                                    <div class="footer tar">
                                        <input type="submit" name="sell_drink" value="Submit" class="btn btn-default">
                                    </div>
                                <?php }if(Input::get('quantity')){$x=0;$total=0;
                                    foreach (Input::get('quantity') as $qty){
                                        $dnk=$override->get('drinks','id',Input::get('drink_1')[$x])[0];
                                        $brnd=$override->get('drink_brand','id',$dnk['brand_id'])[0];
                                        $total += $dnk['price_per_item']*$qty?>
                                        <div class="col-md-12"><?=$brnd['name']?> : <?=$qty?> => <?=number_format($dnk['price_per_item']*$qty)?> Tsh</div>
                                        <input type="hidden" name="prc[<?=$x?>]" value="<?=$dnk['price_per_item']*$qty?>">
                                        <input type="hidden" name="s_id[<?=$x?>]" value="<?=$dnk['id']?>">
                                        <input type="hidden" name="qt[<?=$x?>]" value="<?=$qty?>">
                                        <div class="dr"><span></span></div>
                                        <?php $x++;}?>
                                    <div class="col-md-12"><strong> Total Cost : <?=number_format($total)?> Tsh</strong></div>
                                    <div class="dr"><span></span></div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Amount:</div>
                                        <div class="col-md-9"><input value="" class="validate[required]" type="number" name="amount" id="amount" required/></div>
                                    </div>
                                    <div class="footer tar">
                                        <input type="hidden" name="complete_sell" value="1">
                                        <input type="hidden" name="total_cost" value="<?=$total?>">
                                        <input type="submit" name="sell_drink" value="Submit" class="btn btn-default">
                                    </div>
                                 <?php }?>
                            </form>
                        </div>

                    </div>
                <?php }?>
                <div class="dr"><span></span></div>
            </div>

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
    });
</script>
</body>

</html>

