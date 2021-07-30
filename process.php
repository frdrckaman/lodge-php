<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
if($_GET['cnt'] == 'cat'){
    $brands=$override->get('drink_brand','cat_id',$_GET['getUid']);?>
<option value="">Select Brand</option>
    <?php foreach ($brands as $brand){?>
        <option value="<?=$brand['id']?>"><?=$brand['name']?></option>
<?php }}elseif ($_GET['cnt'] == 'district'){
    $wards=$override->get('ward','district_id',$_GET['getUid']);?>
    <option value="">Select Ward</option>
<?php foreach ($wards as $ward){?>
    <option value="<?=$ward['id']?>"><?=$ward['name']?></option>
<?php }}elseif ($_GET['cnt'] == 'download'){ $user->exportData('citizen', 'citizen_data');?>

<?php }?>
