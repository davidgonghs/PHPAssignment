<?php

$user = new User();
$user->setUserId($create_user_id);
$result = $db->select_one($user::$db_name,"",$user);
if(!empty($result)){
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_object()) {
            $user->setName($row->name);
            $user->setPhoto($row->photo);
            $user->setAge($row->age);
            $user->setEmail($row->email);
            $user->setPhone($row->phone);

        }
    }
}
$html=<<<Eof
              <div class="col-md-6">
                <div class="card card-primary ">
                  <div class="card-header">
                       <h3 class="card-title">Create User Inoformation</h3>
                       
                  </div>
                  <div class="card-body box-profile">
                    <div class="text-center">
                      <img class="profile-user-img img-fluid img-circle" src="{$user->getPhoto()}" alt="User profile picture">
                    </div>
                    <h5 class="profile-username text-center">{$user->getName()}</h5>
                    <ul class="list-group list-group-flush mb-2">
                      <li class="list-group-item">
                        <b>Age</b> <a class="float-right">{$user->getAge()}</a>
                      </li>
                      <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{$user->getEmail()}</a>
                      </li>
                      <li class="list-group-item">
                        <b>Phone</b> <a class="float-right">{$user->getPhone()}</a>
                      </li>
                    </ul>
    
                    <a href="userInfor.php?userId={$user->getUserId()}" class="btn btn-primary btn-block"><b>User Information</b></a>
                  </div>
                  <!-- /.card-body -->
                </div>
            <!-- /.card -->
            </div>
Eof;
echo $html;
?>
