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
        elseif (Input::get('add_citizen')){
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
                    $user->createRecord('citizen', array(
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
                        'status' => 1,
                        'collect_date' => date('Y-m-d'),
                        'user_id'=>$user->data()->id,
                    ));
                    $successMessage = 'Record Created Successful';

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
                            <h1>Add Citizen</h1>
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
                                    <div class="col-md-3">Tribe:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="tribe" id="tribe"/>
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
                                    <div class="col-md-3">Education</div>
                                    <div class="col-md-9">
                                        <select name="education" style="width: 100%;" required>
                                            <option value="">Select Education</option>
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
                                            <option value="">Select Marital Status</option>
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
                                    <div class="col-md-3">Region</div>
                                    <div class="col-md-9">
                                        <select name="region" id="region" style="width: 100%;" required>
                                            <option value="">Select region</option>
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
                                            <option value="">Select district</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <span><img src="img/loaders/loader.gif" id="wait_wd" title="loader.gif"/></span>
                                    <div class="col-md-3">Ward</div>
                                    <div class="col-md-9">
                                        <select name="ward" id="wd_data" style="width: 100%;" required>
                                            <option value="">Select ward</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Address:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="address" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Nationality:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="text" name="nationality" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Household:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="household" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">No of Elder:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="no_elder" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">No of Children:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="no_children" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">No of Dependant:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="no_dependant" />
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Household Income:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required]" type="number" name="house_hold_income" />
                                    </div>
                                </div>

                                <div class="footer tar">
                                    <input type="submit" name="add_citizen" value="Submit" class="btn btn-default">
                                </div>

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
        $('#a_cc').change(function(){
            var getUid = $(this).val();
            $('#wait').show();
            $.ajax({
                url:"process.php?cnt=payAc",
                method:"GET",
                data:{getUid:getUid},
                success:function(data){
                    $('#cus_acc').html(data);
                    $('#wait').hide();
                }
            });

        });
    });
</script>
</body>

</html>

