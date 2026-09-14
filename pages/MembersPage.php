<?php 
declare(strict_types=1);

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../actions/member/MemberRead.php";

$members = getMembers($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="member-container"> 
    <?php include "../includes/SideBar.php" ?>

    <div class="content">
      <div class="add-member">
        <h1>Members</h1>
        <button id="open-add-member-modal-btn">+ Add Member</button>
      </div> 

      <div class="table-section"> 
        <div class="filter"> 
          <div>
            <input id="member-search" name="memberName" type="text" placeholder="Search By Full Name...">
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th style="width:20%;">FULL NAME</th>
              <th style="width:20%;">EMAIL</th>
              <th style="width:20%;">PHONE</th>
              <th style="width:20%;">ADDRESS</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody id="member-table-body">
            <?php foreach($members as $member): ?> 
              <tr>
                <td><?='M'. str_pad((string) $member['id'], 3, "0", STR_PAD_LEFT)?></td>
                <td><?= htmlspecialchars($member['full_name'])?></td>
                <td><?= htmlspecialchars($member['email'])?></td>
                <td><?=htmlspecialchars($member['phone_number'])?></td>
                <td><?=htmlspecialchars($member['address'])?></td>
                <td> 
                  <button 
                    type="button" 
                    class="open-edit-member-modal-btn"
                    data-id="<?=$member['id'] ?>"
                    data-full-name="<?=htmlspecialchars($member['full_name'])?>"
                    data-email="<?=htmlspecialchars($member['email'])?>"
                    data-phone-number="<?=htmlspecialchars($member['phone_number'])?>"
                    data-address="<?=htmlspecialchars($member['address'])?>">

                    <img src="../assets/images/Edit.svg" alt="Edit">
                  </button> 
                  
                  <form action="../actions/member/MemberDelete.php" method="POST">
                    <input type="hidden" name="id" value="<?=$member['id']?>">

                    <button type="submit">
                      <img src="../assets/images/Delete.svg" alt="delete">
                    </button>
                  </form>
                </td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>

         <div class="entries"> 
          <div> 
            <p id="member-entries">Showing 0 to 0 of 0 entries</p>
          </div>

          <nav>
            <ul id="member-pagination" class="pagination">
            </ul>
          </nav>
        </div>

      </div>

    </div>

  </div>

   <!--Modal dialogue para sa add member button-->
  <dialog id="add-member-modal"> 
    <div><h1>ADD MEMBER</h1> </div>
    <form class="modal-information" action="../actions/member/MemberStore.php" method="POST">
      <div class="modal-input"> 
        <label for="full_name">Full Name</label><br>
        <input type="text" id="full_name" name="full_name" placeholder="Enter Member Name"><br>
      </div>
      
      <div class="modal-input"> 
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" placeholder="Enter Email"><br>
      </div>
  
      <div class="modal-input"> 
        <label for="phone_number">Phone Number</label><br>
        <input type="text" id="phone_number" name="phone_number" placeholder="Enter Phone Number"><br>
      </div>
    
      <div class="modal-input"> 
        <label for="address">Address</label><br>
        <input type="text" id="address" name="address" placeholder="Enter Address"><br>
      </div>
      
      <div> 
        <button class="modal-submit" type="submit">Save Member</button>
        <button class="modal-cancel" type="button" id="close-add-member-modal-btn"> cancel</button>
      </div>
    </form>
  </dialog>

  <!--Modal dialogue para sa Edit Member button-->
  <dialog id="edit-member-modal"> 
    <div><h1>EDIT MEMBER</h1> </div>
    <form class="modal-information" action="../actions/member/MemberUpdate.php" method="POST">
      <input type="hidden" id="edit-id" name="id">

      <div class="modal-input"> 
        <label for="edit-full-name">Full Name</label><br>
        <input type="text" id="edit-full-name" name="full_name" placeholder="Enter Full Name"><br>
      </div>
      
      <div class="modal-input"> 
        <label for="edit-email">Email</label><br>
        <input type="email" id="edit-email" name="email" placeholder="Enter Email"><br>
      </div>
  
      <div class="modal-input"> 
        <label for="edit-phone-number">Phone Number</label><br>
        <input type="text" id="edit-phone-number" name="phone_number" placeholder="Enter Phone Number"><br>
      </div>
    
      <div class="modal-input"> 
        <label for="edit-address">Address</label><br>
        <input type="text" id="edit-address" name="address" placeholder="Enter Address"><br>
      </div>
      
      <div> 
        <button class="modal-submit" type="submit">Save Member</button>
        <button class="modal-cancel" type="button" id="close-edit-member-modal-btn">cancel</button>
      </div>
    </form>
  </dialog>

  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/MembersPage.js"></script>
</body>
</html>