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
        if(Input::get('edit_position')){
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('position', array(
                        'name' => Input::get('name'),
                    ),Input::get('id'));
                    $successMessage = 'Position Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_room')){
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
                    $user->updateRecord('rooms', array(
                        'name' => Input::get('name'),
                        'price' => Input::get('price'),
                    ),Input::get('id'));
                    $successMessage = 'Room Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_drink_cat')){
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('drink_cat', array(
                        'name' => Input::get('name'),
                    ),Input::get('id'));
                    $successMessage = 'Drink Category Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_drink_brand')){
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
                    $user->updateRecord('drink_brand', array(
                        'name' => Input::get('name'),
                        'cat_id' => Input::get('drink_category'),
                    ),Input::get('id'));
                    $successMessage = 'Drink Brand Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_staff')){
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
                'phone_number' => array(
                    'required' => true,
                ),
                'email_address' => array(
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
                    $user->updateRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'accessLevel' => $accessLevel,
                        'user_id'=>$user->data()->id,
                    ),Input::get('id'));

                    $successMessage = 'Account Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('reset_pass')){
            $salt = $random->get_rand_alphanumeric(32);
            $password = '12345678';
            $user->updateRecord('user', array(
                'password' => Hash::make($password,$salt),
                'salt' => $salt,
            ),Input::get('id'));
            $successMessage = 'Password Reset Successful';
        }
        elseif (Input::get('delete_staff')){
            $user->updateRecord('user', array(
                'status' => 0,
            ),Input::get('id'));
            $successMessage = 'User Deleted Successful';
        }
        elseif (Input::get('edit_client')){
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
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('clients', array(
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
                    ),Input::get('id'));
                    $successMessage = 'Record Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('delete_client')){
            $user->deleteRecord('client','id',Input::get('id'));
            $successMessage = 'Record Deleted Successful';
        }
        elseif (Input::get('p_payment')){
            $validate = $validate->check($_POST, array(
                'amount' => array(
                    'required' => true,
                ),
                'payment_method' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
//                $dicAmount=(Input::get('amount')-Input::get('discount'));
                $r_amount=Input::get('amount_p')+Input::get('amount');
//                $r_amount=Input::get('amount_p')+$dicAmount;
                if($r_amount <= Input::get('amount_r')){
                    if($r_amount == Input::get('amount_r')){$st=1;}else{$st=0;}
                    try {
                        $user->updateRecord('payment', array(
                            'payed' => $r_amount,
                            'payment_method' => Input::get('payment_method'),
                            'status' => $st,
                        ),Input::get('id'));
                        $user->createRecord('payment_rec', array(
                            'amount' => Input::get('amount'),
                            'payment_method' => Input::get('payment_method'),
                            'no_days' => Input::get('no_days'),
                            'room_id' => Input::get('room_id'),
                            'client_id' => Input::get('client_id'),
                            'create_on'=>date('Y-m-d'),
                            'staff_id'=>$user->data()->id,
                        ));
                        if($st == 1){
                            $roomP=$override->get('rooms','id',Input::get('room_id'))[0];
                            $rp=$roomP['price']*Input::get('no_days');
                            $user->updateRecord('payment', array(
                                'amount' => $rp,
                                'payed' => $rp,
                            ),Input::get('id'));
                            foreach ($override->getNews('drink_sales','client_id',Input::get('client_id'),'status',0) as $dSale){
                                $user->updateRecord('drink_sales',array('status'=>1),Input::get('d_sale'));
                            }
                            foreach ($override->getNews('food_sales','client_id',Input::get('client_id'),'status',0) as $dSale){
                                $user->updateRecord('food_sales',array('status'=>1),Input::get('f_sale'));
                            }
                        }
                        $successMessage = 'Payment Successful Updated';

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{$errorMessage='Paid Amount exceeded the required amount';}
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_drinks')){
            $validate = $validate->check($_POST, array(
                'drink_category' => array(
                    'required' => true,
                ),
                'drink_brand' => array(
                    'required' => true,
                ),
                'price_per_item' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('drinks', array(
                        'price_per_item' => Input::get('price_per_item'),
                        'cat_id' => Input::get('drink_category'),
                        'brand_id' => Input::get('drink_brand'),
                        'staff_id' => $user->data()->id,
                    ),Input::get('id'));
                    $successMessage = 'Drinks Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_payment_method')){
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('payment_method', array(
                        'name' => Input::get('name'),
                    ),Input::get('id'));
                    $successMessage = 'Payment Method Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('edit_food')){
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
                    $user->updateRecord('food', array(
                        'name' => Input::get('name'),
                        'price' => Input::get('price'),
                    ),Input::get('id'));
                    $successMessage = 'Food Menu Successful Updated';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
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
    <title> Info - WHYNOT INN </title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Info</a> <span class="divider">></span></li>
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
                <?php if($_GET['id'] == 1 && $user->data()->accessLevel == 1){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Staff</h1>
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
                                    <th><input type="checkbox" name="checkall"/></th>
                                    <th width="25%">Name</th>
                                    <th width="25%">Username</th>
                                    <th width="25%">Position</th>
                                    <th width="25%">Branch</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('user','status', 1) as $staff){
                                    $position=$override->get('position','id', $staff['position'])[0]?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$staff['firstname'].' '.$staff['lastname']?></td>
                                        <td><?=$staff['username']?></td>
                                        <td><?=$position['name']?></td>
                                        <td>
                                            <a href="#user<?=$staff['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                            <a href="#reset<?=$staff['id']?>" role="button" class="btn btn-warning" data-toggle="modal">Reset</a>
                                            <a href="#delete<?=$staff['id']?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="user<?=$staff['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit User Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First name:</div>
                                                                    <div class="col-md-9"><input type="text" name="firstname" value="<?=$staff['firstname']?>" required/></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last name:</div>
                                                                    <div class="col-md-9"><input type="text" name="lastname" value="<?=$staff['lastname']?>" required/></div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Position</div>
                                                                    <div class="col-md-9">
                                                                        <select name="position" style="width: 100%;" required>
                                                                            <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                            <?php foreach ($override->getData('position') as $position){?>
                                                                                <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Phone Number:</div>
                                                                    <div class="col-md-9"><input value="<?=$staff['phone_number']?>" class="" type="text" name="phone_number" id="phone" required />  <span>Example: 0700 000 111</span></div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">E-mail Address:</div>
                                                                    <div class="col-md-9"><input value="<?=$staff['email_address']?>" class="validate[required,custom[email]]" type="text" name="email_address" id="email" />  <span>Example: someone@nowhere.com</span></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$staff['id']?>">
                                                        <input type="submit" name="edit_staff" value="Save updates" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="reset<?=$staff['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Reset Password</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reset password to default (12345678)</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$staff['id']?>">
                                                        <input type="submit" name="reset_pass" value="Reset" class="btn btn-warning">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="delete<?=$staff['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Delete User</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong style="font-weight: bold;color: red"><p>Are you sure you want to delete this user</p></strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$staff['id']?>">
                                                        <input type="submit" name="delete_staff" value="Delete" class="btn btn-danger">
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
                <?php } elseif ($_GET['id'] == 2 && $user->data()->accessLevel == 1){?>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Positions</h1>
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
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('position') as $position){?>
                                    <tr>
                                        <td> <?=$position['name']?></td>
                                        <td><a href="#position<?=$position['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="position<?=$position['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Position Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?=$position['name']?>" required/></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$position['id']?>">
                                                        <input type="submit" name="edit_position" class="btn btn-warning" value="Save updates">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Rooms</h1>
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
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('rooms') as $room){?>
                                    <tr>
                                        <td><?=$room['name']?></td>
                                        <td><?=$room['price']?></td>
                                        <td><a href="#room<?=$room['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="room<?=$room['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Room Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?=$room['name']?>" /></div>
                                                                </div>
                                                            </div>
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Price:</div>
                                                                    <div class="col-md-9"><input type="text" name="price" value="<?=$room['price']?>" /></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$room['id']?>">
                                                        <input type="submit" name="edit_room" class="btn btn-warning"  aria-hidden="true" value="Save updates">
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
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Drinks Categories</h1>
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
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('drink_cat') as $room){?>
                                    <tr>
                                        <td><?=$room['name']?></td>
                                        <td><a href="#cat<?=$room['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="cat<?=$room['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Edit Drinks Categories </h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?=$room['name']?>" /></div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$room['id']?>">
                                                        <input type="submit" name="edit_drink_cat" class="btn btn-warning"  aria-hidden="true" value="Save updates">
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
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Payment Methods</h1>
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
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('payment_method') as $brand){?>
                                    <tr>
                                        <td><?=$brand['name']?></td>
                                        <td><a href="#brand<?=$brand['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="brand<?=$brand['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4> Edit Payment Methods </h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?=$brand['name']?>" /></div>
                                                                </div>
                                                            </div>

                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$brand['id']?>">
                                                        <input type="submit" name="edit_payment_method" class="btn btn-warning"  aria-hidden="true" value="Save updates">
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
                <?php }elseif ($_GET['id'] == 3){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Clients</h1>
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
                                    <th><input type="checkbox" name="checkall"/></th>
                                    <th width="15%">Name</th>
                                    <th width="10%">Gender</th>
                                    <th width="15%">Tribe</th>
                                    <th width="10%">Nationality</th>
                                    <th width="15%">Occupation</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('clients','status', 1) as $client){?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$client['firstname'].' '.$client['lastname']?></td>
                                        <td><?=$client['gender']?></td>
                                        <td><?=$client['tribe']?></td>
                                        <td><?=$client['nationality']?></td>
                                        <td><?=$client['occupation']?></td>
                                        <td>
                                            <a href="#viewClient<?=$client['id']?>" role="button" class="btn btn-success" data-toggle="modal">Details</a>
                                            <?php if($user->data()->accessLevel == 1){?>
                                                <a href="#editClient<?=$client['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                                <a href="#deleteClient<?=$client['id']?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                            <?php }?>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="viewClient<?=$client['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>View Client Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" readonly>
                                                                            <option value="<?=$client['gender']?>"><?=$client['gender']?></option>
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Tribe:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['tribe']?>" class="validate[required]" type="text" name="tribe" id="tribe" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Place of Birth:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['place_of_birth']?>" class="validate[required]" type="text" name="place_of_birth" id="birth_place"/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Nationality:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['nationality']?>" class="validate[required]" type="text" name="nationality" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Address:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['address']?>" class="validate[required]" type="text" name="address" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Occupation</div>
                                                                    <div class="col-md-9">
                                                                        <select name="occupation" style="width: 100%;" readonly>
                                                                            <option value="<?=$client['occupation']?>"><?=$client['occupation']?></option>
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
                                                                        <input value="<?=$client['passport_no']?>" class="" type="text" name="passport_no" readonly/>
                                                                    </div>
                                                                </div>

                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Phone Number:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['phone_number']?>" class="validate[required]" type="text" name="phone_number" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Email Address:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$client['email_address']?>" class="validate[required,custom[email]]" type="email" name="email_address" readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$client['id']?>">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if($user->data()->accessLevel == 1){?>
                                        <div class="modal fade" id="editClient<?=$client['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Edit Citizen Info</h4>
                                                        </div>
                                                        <div class="modal-body modal-body-np">
                                                            <div class="row">
                                                                <div class="block-fluid">
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">First Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Last Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Gender</div>
                                                                        <div class="col-md-9">
                                                                            <select name="gender" style="width: 100%;" required>
                                                                                <option value="<?=$client['gender']?>"><?=$client['gender']?></option>
                                                                                <option value="Male">Male</option>
                                                                                <option value="Female">Female</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Tribe:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['tribe']?>" class="validate[required]" type="text" name="tribe" id="tribe" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Place of Birth:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['place_of_birth']?>" class="validate[required]" type="text" name="place_of_birth" id="birth_place"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Nationality:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['nationality']?>" class="validate[required]" type="text" name="nationality" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Address:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['address']?>" class="validate[required]" type="text" name="address" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Occupation</div>
                                                                        <div class="col-md-9">
                                                                            <select name="occupation" style="width: 100%;" required>
                                                                                <option value="<?=$client['occupation']?>"><?=$client['occupation']?></option>
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
                                                                            <input value="<?=$client['passport_no']?>" class="" type="text" name="passport_no" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Phone Number:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['phone_number']?>" class="validate[required]" type="text" name="phone_number" required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Email Address:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$client['email_address']?>" class="validate[custom[email]]" type="email" name="email_address" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dr"><span></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?=$client['id']?>">
                                                            <input type="submit" name="edit_client" value="Save updates" class="btn btn-warning">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="deleteClient<?=$client['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Delete Client</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong style="font-weight: bold;color: red"><p>Are you sure you want to delete this Client ?</p></strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?=$client['id']?>">
                                                            <input type="submit" name="delete_client" value="Delete" class="btn btn-danger">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php }?>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 4){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>
                        <?php if($_GET['typ'] == 'a'){
                            $title='Payment Room Report';
                            $payments=$override->getData('payment');
                        }elseif ($_GET['typ'] == 'p'){
                            $title='Pending Room Payment Report';
                            $payments=$override->get('payment','status',0);
                        }?>
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1><?=$title?></h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall"/></th>
                                    <th width="10%">Client</th>
                                    <th width="5%">Room</th>
                                    <th width="10%">No Days</th>
                                    <th width="20%">Amount</th>
                                    <th width="15%">Discount</th>
                                    <th width="15%">Paid</th>
                                    <th width="10%">Remained</th>
                                    <th width="10%">Status</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($payments as $payment){
                                    $client=$override->get('clients','id',$payment['client_id'])[0];
                                    $room=$override->get('rooms','id',$payment['room_id'])[0]; ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$client['firstname'].' '.$client['lastname']?></td>
                                        <td><?=$room['name']?></td>
                                        <td><?=$payment['no_days']?></td>
                                        <td><?='Original: '.number_format($payment['amount']).' Discounted: '.number_format(($payment['amount']-$payment['discount']))?> Tsh</td>
                                        <td><?=number_format($payment['discount'])?> Tsh</td>
                                        <td><?php if(($payment['payed']-$payment['discount'])>0){echo number_format($payment['payed']-$payment['discount']);}else{number_format($payment['payed']);}?> Tsh</td>
                                        <td><?php if((($payment['amount']-$payment['discount'])-$payment['payed'])>0){echo number_format(($payment['amount']-$payment['discount'])-$payment['payed']);}else{echo 0;}?> Tsh</td>
                                        <td>
                                            <?php if($payment['status'] == 1){?>
                                                <button class="btn btn-sm btn-success" type="button">Complete</button>
                                            <?php }else{?>
                                                <button class="btn btn-sm btn-warning" type="button">Pending</button>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <a href="#payment<?=$payment['id']?>" role="button" class="btn btn-info" data-toggle="modal">Update Payment</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="payment<?=$payment['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Payment Details</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <p>&nbsp;&nbsp;<strong style="color: orangered">Note: The remain amount to be paid is <?=number_format(($payment['amount']-$payment['discount'])-$payment['payed'])?> Tsh</strong></p>
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Payment Method:</div>
                                                                    <div class="col-md-9">
                                                                        <select name="payment_method" id="" style="width: 100%;" required>
                                                                            <option value="">Choose payment method...</option>
                                                                            <?php foreach ($override->getData('payment_method') as $method){?>
                                                                                <option value="<?=$method['id']?>"><?=$method['name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Amount:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="" class="validate[required]" type="number" name="amount" id="amount" required/>
                                                                        <input type="hidden" name="amount_r" value="<?=($payment['amount']-$payment['discount'])?>">
                                                                        <input type="hidden" name="no_days" value="<?=$payment['no_days']?>">
                                                                        <input type="hidden" name="room_id" value="<?=$payment['room_id']?>">
                                                                        <input type="hidden" name="discount" value="<?=$payment['discount']?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$payment['id']?>">
                                                        <input type="hidden" name="paid" value="<?=$payment['payed']?>">
                                                        <input type="hidden" name="client_id" value="<?=$payment['client_id']?>">
                                                        <input type="hidden" name="amount_p" value="<?=$payment['payed']?>">
                                                        <input type="hidden" name="d_sale" value="<?=$payment['drinks_pay']?>">
                                                        <input type="hidden" name="f_sale" value="<?=$payment['food_pay']?>">
                                                        <input type="submit" name="p_payment" value="Save Update" class="btn btn-info">
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
                <?php }elseif ($_GET['id'] == 5){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Drinks</h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkall"/></th>
                                    <th width="20%">Brand</th>
                                    <th width="20%">Category</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Price per drink</th>
                                    <th width="15%">Total Cost</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('drinks') as $drink){
                                    $category=$override->get('drink_cat','id',$drink['cat_id'])[0];
                                    $brand=$override->get('drink_brand','id',$drink['brand_id'])[0];
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$brand['name']?></td>
                                        <td><?=$category['name']?></td>
                                        <td><?=number_format($drink['quantity'])?></td>
                                        <td><?=number_format($drink['price_per_item'])?> Tsh</td>
                                        <td><?=number_format($drink['quantity']*$drink['price_per_item'])?> Tsh</td>
                                        <td>
                                            <a href="#drinks<?=$drink['id']?>" role="button" class="btn btn-info" data-toggle="modal">Update Info</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="drinks<?=$drink['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Drinks Details</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Drink Category:</div>
                                                                <div class="col-md-9">
                                                                    <select name="drink_category" id="s2_1" style="width: 100%;">
                                                                        <?php $ct=$override->get('drink_cat','id',$drink['cat_id'])[0]?>
                                                                        <option value="<?=$drink['cat_id']?>"><?=$ct['name']?></option>
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
                                                                        <?php $br=$override->get('drink_brand','id',$drink['brand_id'])[0]?>
                                                                        <option value="<?=$drink['cat_id']?>"><?=$br['name']?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Price per Item:</div>
                                                                <div class="col-md-9"><input value="<?=$drink['price_per_item']?>" class="validate[required]" type="number" name="price_per_item" id="cost"/> <span>Note: Cost of single item</span></div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$drink['id']?>">
                                                        <input type="submit" name="edit_drinks" value="Save Update" class="btn btn-info">
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
                <?php }elseif ($_GET['id'] == 6){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Drinks Sales Report</h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Date</th>
                                    <th width="20%">Amount</th>
                                    <th width="30%">Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('drink_sales') as $drink){
                                    $category=$override->get('drink_cat','id',$drink['cat_id'])[0];
                                    $brand=$override->get('drink_brand','id',$drink['brand_id'])[0];
                                    ?>
                                    <tr>
                                        <td> <?=$drink['create_on']?></td>
                                        <td><?=number_format($drink['amount'])?> Tsh</td>
                                        <td>
                                            <a href="#drinks<?=$drink['id']?>" role="button" class="btn btn-info" data-toggle="modal">Details</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="drinks<?=$drink['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Drinks Sales Details</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <?php $ttl=0;foreach ($override->get('drink_sale_item','sale_id',$drink['id']) as $details){
                                                                $d=$override->get('drinks','id',$details['drinks_id'])[0];
                                                                $b=$override->get('drink_brand','id',$d['brand_id'])[0];
                                                                $ttl+=$details['amount'] ?>
                                                                <div class="col-md-12"><?=$b['name']?> : <?=$details['quantity']?> => <?=number_format($details['amount'])?>Tsh</div>
                                                                <div class="dr"><span></span></div>
                                                            <?php }?>
                                                            <div class="col-md-12"><strong>Total Cost : <?=number_format($ttl)?>Tsh</strong></div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$drink['id']?>">
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
                <?php }elseif ($_GET['id'] == 7){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Salary Payment</h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Year</th>
                                    <th width="20%">Amount</th>
                                    <th width="30%">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getSortNoRepeatAll('salary', 'year') as $year){
                                    $yrSum=$override->getSum1('salary','amount','year',$year['year']);
                                    ?>
                                    <tr>
                                        <td> <?=$year['year']?></td>
                                        <td> <?=number_format($yrSum[0]['SUM(amount)'])?>Tsh</td>
                                        <td>
                                            <a href="info.php?id=8&yr=<?=$year['year']?>" role="button" class="btn btn-info" data-toggle="modal">Details</a>
                                        </td>

                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 8){if($_GET['yr']){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Salary Payment <?=$_GET['yr']?></h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Month</th>
                                    <th width="20%">Amount</th>
                                    <th width="30%">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getSortNoRepeatAll1('salary', 'month','year',$_GET['yr']) as $year){
                                    $yrSum=$override->getSum2('salary','amount','year',$_GET['yr'], 'month', $year['month']);
                                    ?>
                                    <tr>
                                        <td> <?=$user->monthName($year['month'])?></td>
                                        <td> <?=number_format($yrSum[0]['SUM(amount)'])?>Tsh</td>
                                        <td>
                                            <a href="info.php?id=9&yr=<?=$_GET['yr']?>&m=<?=$year['month']?>" role="button" class="btn btn-info" data-toggle="modal">Details</a>
                                        </td>

                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }}elseif ($_GET['id'] == 9){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Salary Payment <?=$_GET['yr'].' '.$user->monthName($_GET['m'])?></h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Name</th>
                                    <th width="20%">Position</th>
                                    <th width="20%">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $total=0;foreach ($override->getNews('salary', 'month',$_GET['m'],'year',$_GET['yr']) as $month){
                                    $staff=$override->get('user','id',$month['staff_id'])[0];$total+=$month['amount'] ?>
                                    <tr>
                                        <td><?=$staff['firstname'].' '.$staff['lastname']?></td>
                                        <td><?=$override->get('position','id',$staff['position'])[0]['name']?></td>
                                        <td> <?=number_format($month['amount'])?>Tsh</td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong></strong></td>
                                    <td><strong><?=number_format($total)?>Tsh</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 10){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Bills Payment</h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Year</th>
                                    <th width="20%">Amount</th>
                                    <th width="30%">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getSortNoRepeatAll('bill_payment', 'year') as $year){
                                    $yrSum=$override->getSum1('bill_payment','amount','year',$year['year']);
                                    ?>
                                    <tr>
                                        <td> <?=$year['year']?></td>
                                        <td> <?=number_format($yrSum[0]['SUM(amount)'])?>Tsh</td>
                                        <td>
                                            <a href="info.php?id=11&yr=<?=$year['year']?>" role="button" class="btn btn-info" data-toggle="modal">Details</a>
                                        </td>

                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 11){if($_GET['yr']){?>
                    <div class="col-md-12">
                    <form method="post">
                        <input type="submit" name="download" value="Download Data" class="btn btn-info">
                    </form>

                    <div class="head clearfix">
                        <div class="isw-grid"></div>
                        <h1>Bill Payment <?=$_GET['yr']?></h1>
                    </div>
                    <div class="block-fluid">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table">
                            <thead>
                            <tr>
                                <th width="20%">Month</th>
                                <th width="20%">Amount</th>
                                <th width="30%">Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($override->getSortNoRepeatAll1('bill_payment', 'month','year',$_GET['yr']) as $year){
                                $yrSum=$override->getSum2('bill_payment','amount','year',$_GET['yr'], 'month', $year['month']);
                                ?>
                                <tr>
                                    <td> <?=$user->monthName($year['month'])?></td>
                                    <td> <?=number_format($yrSum[0]['SUM(amount)'])?>Tsh</td>
                                    <td>
                                        <a href="info.php?id=12&yr=<?=$_GET['yr']?>&m=<?=$year['month']?>" role="button" class="btn btn-info" data-toggle="modal">Details</a>
                                    </td>

                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }}elseif ($_GET['id'] == 12){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Bill Payment <?=$_GET['yr'].' '.$user->monthName($_GET['m'])?></h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Name</th>
                                    <th width="20%">Date</th>
                                    <th width="20%">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $total=0;foreach ($override->getNews('bill_payment', 'month',$_GET['m'],'year',$_GET['yr']) as $month){
                                    $billT=$override->get('bills','id',$month['bill_type'])[0];$total+=$month['amount'] ?>
                                    <tr>
                                        <td><?=$billT['name']?></td>
                                        <td><?=$month['create_on']?></td>
                                        <td> <?=number_format($month['amount'])?>Tsh</td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td></td>
                                    <td><strong><?=number_format($total)?>Tsh</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php }elseif ($_GET['id'] == 13){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Drinks Brands</h1>
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
                                    <th width="25%">Category</th>
                                    <th width="5%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('drink_brand') as $brand){?>
                                    <tr>
                                        <td><?=$brand['name']?></td>
                                        <td><?=$override->get('drink_cat','id', $brand['cat_id'])[0]['name']?></td>
                                        <td><a href="#brand<?=$brand['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a></td>
                                        <!-- EOF Bootrstrap modal form -->
                                    </tr>
                                    <div class="modal fade" id="brand<?=$brand['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4> Edit Drinks Brands </h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Name:</div>
                                                                    <div class="col-md-9"><input type="text" name="name" value="<?=$brand['name']?>" /></div>
                                                                </div>
                                                            </div>

                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Drink Category</div>
                                                                <div class="col-md-9">
                                                                    <select name="drink_category" id="region" style="width: 100%;" required>
                                                                        <option value="<?=$brand['cat_id']?>"><?=$override->get('drink_cat','id',$brand['cat_id'])[0]['name']?></option>
                                                                        <?php foreach ($override->getData('drink_cat') as $cat){?>
                                                                            <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$brand['id']?>">
                                                        <input type="submit" name="edit_drink_brand" class="btn btn-warning"  aria-hidden="true" value="Save updates">
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
                <?php }elseif ($_GET['id'] == 14){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Manage Food Menu</h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Name</th>
                                    <th width="20%">Price</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('food') as $food){?>
                                    <tr>
                                        <td> <?=$food['name']?></td>
                                        <td><?=number_format($food['price'])?> Tsh</td>
                                        <td>
                                            <a href="#drinks<?=$food['id']?>" role="button" class="btn btn-info" data-toggle="modal">Update Info</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="drinks<?=$food['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Food Menu Details</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Name:</div>
                                                                <div class="col-md-9"><input value="<?=$food['name']?>" class="validate[required]" type="text" name="name" id="name"/></div>
                                                            </div>

                                                            <div class="row-form clearfix">
                                                                <div class="col-md-3">Price:</div>
                                                                <div class="col-md-9"><input value="<?=$food['price']?>" class="validate[required]" type="number" name="price" id="cost"/></div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$food['id']?>">
                                                        <input type="submit" name="edit_food" value="Save Update" class="btn btn-info">
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
                <?php }elseif ($_GET['id'] == 15){?>
                    <div class="col-md-12">
                        <form method="post">
                            <input type="submit" name="download" value="Download Data" class="btn btn-info">
                        </form>

                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Food Sales Report</h1>
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                <tr>
                                    <th width="20%">Date</th>
                                    <th width="20%">Amount</th>
                                    <th width="30%">Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->getData('food_sales') as $food){?>
                                    <tr>
                                        <td> <?=$food['create_on']?></td>
                                        <td><?=number_format($food['amount'])?> Tsh</td>
                                        <td>
                                            <a href="#food<?=$food['id']?>" role="button" class="btn btn-info" data-toggle="modal">Details</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="food<?=$food['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>Food Sales Details</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <?php $ttl=0;foreach ($override->get('food_sale_item','sale_id',$food['id']) as $details){
                                                                $d=$override->get('food','id',$details['food_id'])[0];
                                                                $ttl+=$details['amount'] ?>
                                                                <div class="col-md-12"><?=$d['name']?> : <?=$details['quantity']?> => <?=number_format($details['amount'])?>Tsh</div>
                                                                <div class="dr"><span></span></div>
                                                            <?php }?>
                                                            <div class="col-md-12"><strong>Total Cost : <?=number_format($ttl)?>Tsh</strong></div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$food['id']?>">
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
