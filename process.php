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
<?php }}elseif ($_GET['cnt'] == 'room'){?>
    <div class="row-form clearfix">
        <div class="col-md-2">Select Room:</div>
        <div class="col-md-9">
            <select name="room" id="r_id" style="width: 100%;" required>
                <option value="">Choose Room...</option>
                <?php foreach ($override->get('rooms','status', 1) as $room){
                    $assign=$override->get('room_assigned','room_id', $room['id'])[0];
                    $client=$override->get('clients','id',$assign['client_id'])[0]?>
                    <option value="<?=$room['id']?>"><?=$room['name'].' ( '.$client['firstname'].' '.$client['lastname'].' ) '?></option>
                <?php }?>
            </select>
        </div>
    </div>

<?php }elseif ($_GET['cnt'] == 'client'){?>


<?php }?>
