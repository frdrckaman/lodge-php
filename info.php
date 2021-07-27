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
        elseif (Input::get('edit_citizen')){
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
                'region' => array(
                    'required' => true,
                ),
                'district' => array(
                    'required' => true,
                ),
                'ward' => array(
                    'required' => true,
                ),
                'household' => array(
                    'required' => true,
                ),
                'nationality' => array(
                    'required' => true,
                ),
                'no_dependant' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('citizen', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'tribe' => Input::get('tribe'),
                        'gender' => Input::get('gender'),
                        'marital_status' => Input::get('marital_status'),
                        'occupation' => Input::get('occupation'),
                        'education' => Input::get('education'),
                        'region_id' => Input::get('region'),
                        'district_id' => Input::get('district'),
                        'ward_id'=>Input::get('ward'),
                        'nationality'=>Input::get('nationality'),
                        'address' => Input::get('address'),
                        'household'=>Input::get('household'),
                        'no_elder' => Input::get('no_elder'),
                        'no_children' => Input::get('no_children'),
                        'no_dependant' => Input::get('no_dependant'),
                        'house_hold_income' => Input::get('house_hold_income'),
                        'user_id'=>$user->data()->id,
                    ),Input::get('id'));
                    $successMessage = 'Record Updated Successful';

                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
        elseif (Input::get('delete_citizen')){
            $user->deleteRecord('citizen','id',Input::get('id'));
            $successMessage = 'Record Deleted Successful';
        }
        elseif (Input::get('download')){
            $user->exportData($override->getData('citizen'), 'citizen_data');
            $successMessage = 'Record Deleted Successful';
        }
        elseif (Input::get('download_e')){
            $user->exportData($override->getData('citizen'), 'citizen_data');
            $successMessage = 'Record Deleted Successful';
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
                <?php }elseif ($_GET['id'] == 3){?>
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>List of Citizen</h1>
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
                                    <th width="15%">Education</th>
                                    <th width="15%">Occupation</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($user->data()->accessLevel == 1){$citizens = $override->get('citizen','status', 1);
                                }else{
                                    $citizens = $override->getNews('citizen','status', 1,'user_id', $user->data()->id);
                                }
                                foreach ($citizens as $citizen){
                                    $region=$override->get('region','id', $citizen['region_id'])[0];
                                    $district=$override->get('district','id', $citizen['district_id'])[0];
                                    $ward=$override->get('ward','id', $citizen['ward_id'])[0]?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$citizen['firstname'].' '.$citizen['lastname']?></td>
                                        <td><?=$citizen['gender']?></td>
                                        <td><?=$citizen['tribe']?></td>
                                        <td><?=$citizen['nationality']?></td>
                                        <td><?=$citizen['education']?></td>
                                        <td><?=$citizen['occupation']?></td>
                                        <td>
                                            <a href="#viewCitizen<?=$citizen['id']?>" role="button" class="btn btn-success" data-toggle="modal">Details</a>
                                            <?php if($user->data()->accessLevel == 1){?>
                                                <a href="#editCitizen<?=$citizen['id']?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                                <a href="#deleteCitizen<?=$citizen['id']?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                            <?php }?>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="viewCitizen<?=$citizen['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>View Citizen Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Tribe:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['tribe']?>" class="validate[required]" type="text" name="tribe" id="tribe" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['gender']?>"><?=$citizen['gender']?></option>
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Education</div>
                                                                    <div class="col-md-9">
                                                                        <select name="education" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['education']?>"><?=$citizen['education']?></option>
                                                                            <option value="PHD">PHD</option>
                                                                            <option value="Master Degree">Master Degree</option>
                                                                            <option value="Bachelor Degree">Bachelor Degree</option>
                                                                            <option value="Advance Diploma">Advance Diploma</option>
                                                                            <option value="Diploma">Diploma</option>
                                                                            <option value="Certificate">Certificate</option>
                                                                            <option value="A Level">A Level</option>
                                                                            <option value="O Level">O Level</option>
                                                                            <option value="Primary Education">Primary Education</option>
                                                                            <option value="Didnt go to school">Didnt go to school</option>
                                                                            <option value="Not Applicable">Not Applicable</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Marital Status</div>
                                                                    <div class="col-md-9">
                                                                        <select name="marital_status" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['marital_status']?>"><?=$citizen['marital_status']?></option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Living together as married">Living together as married</option>
                                                                            <option value="Not Married">Not Married</option>
                                                                            <option value="Divorced">Divorced</option>
                                                                            <option value="Separated">Separated</option>
                                                                            <option value="Widow">Widow</option>
                                                                            <option value="Not Applicable">Not Applicable</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Occupation</div>
                                                                    <div class="col-md-9">
                                                                        <select name="occupation" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['occupation']?>"><?=$citizen['occupation']?></option>
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
                                                                    <div class="col-md-3">Region</div>
                                                                    <div class="col-md-9">
                                                                        <select name="region"  style="width: 100%;" readonly>
                                                                            <option value="<?=$region['id']?>"><?=$region['name']?></option>
                                                                            <?php foreach ($override->getData('region') as $position){?>
                                                                                <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">District</div>
                                                                    <div class="col-md-9">
                                                                        <select name="district"  style="width: 100%;" readonly>
                                                                            <option value="<?=$district['id']?>"><?=$district['name']?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Ward</div>
                                                                    <div class="col-md-9">
                                                                        <select name="ward" style="width: 100%;" readonly>
                                                                            <option value="<?=$ward['id']?>"><?=$ward['name']?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Address:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['address']?>" class="validate[required]" type="text" name="address" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Nationality:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['nationality']?>" class="validate[required]" type="text" name="nationality" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Household:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['household']?>" class="validate[required]" type="number" name="household" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">No of Elder:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['no_elder']?>" class="validate[required]" type="number" name="no_elder" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">No of Children:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['no_children']?>" class="validate[required]" type="number" name="no_children" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">No of Dependant:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['no_dependant']?>" class="validate[required]" type="number" name="no_dependant" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Household Income:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['house_hold_income']?>" class="validate[required]" type="number" name="house_hold_income" readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$citizen['id']?>">
                                                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if($user->data()->accessLevel == 1){?>
                                        <div class="modal fade" id="editCitizen<?=$citizen['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                            <input value="<?=$citizen['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Last Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Tribe:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['tribe']?>" class="validate[required]" type="text" name="tribe" id="tribe"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Gender</div>
                                                                        <div class="col-md-9">
                                                                            <select name="gender" style="width: 100%;" required>
                                                                                <option value="<?=$citizen['gender']?>"><?=$citizen['gender']?></option>
                                                                                <option value="Male">Male</option>
                                                                                <option value="Female">Female</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Education</div>
                                                                        <div class="col-md-9">
                                                                            <select name="education" style="width: 100%;" required>
                                                                                <option value="<?=$citizen['education']?>"><?=$citizen['education']?></option>
                                                                                <option value="PHD">PHD</option>
                                                                                <option value="Master Degree">Master Degree</option>
                                                                                <option value="Bachelor Degree">Bachelor Degree</option>
                                                                                <option value="Advance Diploma">Advance Diploma</option>
                                                                                <option value="Diploma">Diploma</option>
                                                                                <option value="Certificate">Certificate</option>
                                                                                <option value="A Level">A Level</option>
                                                                                <option value="O Level">O Level</option>
                                                                                <option value="Primary Education">Primary Education</option>
                                                                                <option value="Didnt go to school">Didnt go to school</option>
                                                                                <option value="Not Applicable">Not Applicable</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Marital Status</div>
                                                                        <div class="col-md-9">
                                                                            <select name="marital_status" style="width: 100%;" required>
                                                                                <option value="<?=$citizen['marital_status']?>"><?=$citizen['marital_status']?></option>
                                                                                <option value="Married">Married</option>
                                                                                <option value="Living together as married">Living together as married</option>
                                                                                <option value="Not Married">Not Married</option>
                                                                                <option value="Divorced">Divorced</option>
                                                                                <option value="Separated">Separated</option>
                                                                                <option value="Widow">Widow</option>
                                                                                <option value="Not Applicable">Not Applicable</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Occupation</div>
                                                                        <div class="col-md-9">
                                                                            <select name="occupation" style="width: 100%;" required>
                                                                                <option value="<?=$citizen['occupation']?>"><?=$citizen['occupation']?></option>
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
                                                                        <div class="col-md-3">Region</div>
                                                                        <div class="col-md-9">
                                                                            <select name="region" id="region" style="width: 100%;" required>
                                                                                <option value="<?=$region['id']?>"><?=$region['name']?></option>
                                                                                <?php foreach ($override->getData('region') as $position){?>
                                                                                    <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <span><img src="img/loaders/loader.gif" id="wait_ds" title="loader.gif"/></span>
                                                                        <div class="col-md-3">District</div>
                                                                        <div class="col-md-9">
                                                                            <select name="district" id="ds_data" style="width: 100%;" required>
                                                                                <option value="<?=$district['id']?>"><?=$district['name']?></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <span><img src="img/loaders/loader.gif" id="wait_wd" title="loader.gif"/></span>
                                                                        <div class="col-md-3">Ward</div>
                                                                        <div class="col-md-9">
                                                                            <select name="ward" id="wd_data" style="width: 100%;" required>
                                                                                <option value="<?=$ward['id']?>"><?=$ward['name']?></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Address:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['address']?>" class="validate[required]" type="text" name="address" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Nationality:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['nationality']?>" class="validate[required]" type="text" name="nationality" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Household:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['household']?>" class="validate[required]" type="number" name="household" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">No of Elder:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['no_elder']?>" class="validate[required]" type="number" name="no_elder" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">No of Children:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['no_children']?>" class="validate[required]" type="number" name="no_children" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">No of Dependant:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['no_dependant']?>" class="validate[required]" type="number" name="no_dependant" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Household Income:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?=$citizen['house_hold_income']?>" class="validate[required]" type="number" name="house_hold_income" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dr"><span></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?=$citizen['id']?>">
                                                            <input type="submit" name="edit_citizen" value="Save updates" class="btn btn-warning">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="deleteCitizen<?=$citizen['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Delete This Record</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong style="font-weight: bold;color: red"><p>Are you sure you want to delete this record ?</p></strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?=$citizen['id']?>">
                                                            <input type="submit" name="delete_citizen" value="Delete" class="btn btn-danger">
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
                    <div class="col-md-6">
<!--                        <form method="post">-->
<!--                            <input type="submit" name="download_e" value="Download Enumerator Report" class="btn btn-info">-->
<!--                        </form>-->
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Enumerator Report</h1>
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
                                    <th width="25%">Data Collected</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('user','accessLevel',3) as $ward){
                                    $no=$override->getCount('citizen','user_id',$ward['id']); ?>
                                    <tr>
                                        <td> <?=$ward['firstname'].' '.$ward['lastname']?></td>
                                        <td><?=$no?></td>
                                    </tr>
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
                            <h1>Data Collection Report</h1>
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
                                    <th width="15%">Education</th>
                                    <th width="15%">Occupation</th>
                                    <th width="30%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($override->get('citizen','status', 1) as $citizen){
                                    $region=$override->get('region','id', $citizen['region_id'])[0];
                                    $district=$override->get('district','id', $citizen['district_id'])[0];
                                    $ward=$override->get('ward','id', $citizen['ward_id'])[0]?>
                                    <tr>
                                        <td><input type="checkbox" name="checkbox"/></td>
                                        <td> <?=$citizen['firstname'].' '.$citizen['lastname']?></td>
                                        <td><?=$citizen['gender']?></td>
                                        <td><?=$citizen['tribe']?></td>
                                        <td><?=$citizen['nationality']?></td>
                                        <td><?=$citizen['education']?></td>
                                        <td><?=$citizen['occupation']?></td>
                                        <td>
                                            <a href="#viewCitizen<?=$citizen['id']?>" role="button" class="btn btn-success" data-toggle="modal">More Details</a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="viewCitizen<?=$citizen['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4>View Citizen Info</h4>
                                                    </div>
                                                    <div class="modal-body modal-body-np">
                                                        <div class="row">
                                                            <div class="block-fluid">
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">First Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['firstname']?>" class="validate[required]" type="text" name="firstname" id="firstname" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Last Name:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['lastname']?>" class="validate[required]" type="text" name="lastname" id="lastname" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Tribe:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['tribe']?>" class="validate[required]" type="text" name="tribe" id="tribe" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Gender</div>
                                                                    <div class="col-md-9">
                                                                        <select name="gender" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['gender']?>"><?=$citizen['gender']?></option>
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Education</div>
                                                                    <div class="col-md-9">
                                                                        <select name="education" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['education']?>"><?=$citizen['education']?></option>
                                                                            <option value="PHD">PHD</option>
                                                                            <option value="Master Degree">Master Degree</option>
                                                                            <option value="Bachelor Degree">Bachelor Degree</option>
                                                                            <option value="Advance Diploma">Advance Diploma</option>
                                                                            <option value="Diploma">Diploma</option>
                                                                            <option value="Certificate">Certificate</option>
                                                                            <option value="A Level">A Level</option>
                                                                            <option value="O Level">O Level</option>
                                                                            <option value="Primary Education">Primary Education</option>
                                                                            <option value="Didnt go to school">Didnt go to school</option>
                                                                            <option value="Not Applicable">Not Applicable</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Marital Status</div>
                                                                    <div class="col-md-9">
                                                                        <select name="marital_status" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['marital_status']?>"><?=$citizen['marital_status']?></option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Living together as married">Living together as married</option>
                                                                            <option value="Not Married">Not Married</option>
                                                                            <option value="Divorced">Divorced</option>
                                                                            <option value="Separated">Separated</option>
                                                                            <option value="Widow">Widow</option>
                                                                            <option value="Not Applicable">Not Applicable</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Occupation</div>
                                                                    <div class="col-md-9">
                                                                        <select name="occupation" style="width: 100%;" readonly>
                                                                            <option value="<?=$citizen['occupation']?>"><?=$citizen['occupation']?></option>
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
                                                                    <div class="col-md-3">Region</div>
                                                                    <div class="col-md-9">
                                                                        <select name="region"  style="width: 100%;" readonly>
                                                                            <option value="<?=$region['id']?>"><?=$region['name']?></option>
                                                                            <?php foreach ($override->getData('region') as $position){?>
                                                                                <option value="<?=$position['id']?>"><?=$position['name']?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">District</div>
                                                                    <div class="col-md-9">
                                                                        <select name="district"  style="width: 100%;" readonly>
                                                                            <option value="<?=$district['id']?>"><?=$district['name']?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Ward</div>
                                                                    <div class="col-md-9">
                                                                        <select name="ward" style="width: 100%;" readonly>
                                                                            <option value="<?=$ward['id']?>"><?=$ward['name']?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Address:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['address']?>" class="validate[required]" type="text" name="address" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Nationality:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['nationality']?>" class="validate[required]" type="text" name="nationality" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Household:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['household']?>" class="validate[required]" type="number" name="household" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">No of Elder:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['no_elder']?>" class="validate[required]" type="number" name="no_elder" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">No of Children:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['no_children']?>" class="validate[required]" type="number" name="no_children" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">No of Dependant:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['no_dependant']?>" class="validate[required]" type="number" name="no_dependant" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="row-form clearfix">
                                                                    <div class="col-md-3">Household Income:</div>
                                                                    <div class="col-md-9">
                                                                        <input value="<?=$citizen['house_hold_income']?>" class="validate[required]" type="number" name="house_hold_income" readonly/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dr"><span></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="id" value="<?=$citizen['id']?>">
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
                    <div class="col-md-6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Summary Report <?php if(!is_null($user->report('men'))){print_r($user->report('men'));}?></h1>
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
                                    <th width="5%">Percentage</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> Men</td>
                                        <td><?=number_format((float)$user->report('men'), 2, '.', '')?>%</td>
                                    </tr>
                                    <tr>
                                        <td> Women</td>
                                        <td><?=number_format((float)$user->report('women'), 2, '.', '')?>%</td>
                                    </tr>
                                    <tr>
                                        <td> Elders</td>
                                        <td><?=number_format((float)$user->report('elders'), 2, '.', '')?>%</td>
                                    </tr>
                                    <tr>
                                        <td> Children</td>
                                        <td><?=number_format((float)$user->report('children'), 2, '.', '')?>%</td>
                                    </tr>
                                    <tr>
                                        <td> Dependant</td>
                                        <td><?=number_format((float)$user->report('dependant'), 2, '.', '')?>%</td>
                                    </tr>
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
        $('#region').change(function(){
            var getUid = $(this).val();
            $('#wait_ds').show();
            $.ajax({
                url:"process.php?cnt=region",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){
                    $('#ds_data').html(data);
                    $('#wait_ds').hide();
                }
            });

        });
        $('#wait_wd').hide();
        $('#ds_data').change(function(){
            $('#wait_wd').hide();
            var getUid = $(this).val();
            $.ajax({
                url:"process.php?cnt=district",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){
                    $('#wd_data').html(data);
                    $('#wait_wd').hide();
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
