<?php 
declare(strict_types=1);

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../actions/member/MemberRead.php";

$memberErrors = $_SESSION["memberErrors"] ?? [];

$memberSuccess = $_SESSION["memberSuccess"] ??  "";

$memberUnsuccessful = $_SESSION["memberUnsuccessful"] ?? "";

$editMemberSuccessful = $_SESSION["editMemberSuccessful"] ?? "";

$editMemberUnsuccessful = $_SESSION["editMemberUnsuccessful"] ?? "";

$editMemberErrors = $_SESSION["editMemberErrors"] ?? [];

$memberDeleteSuccess = $_SESSION["memberDeleteSuccess"] ?? "";

$memberDeleteUnsuccessful = $_SESSION["memberDeleteUnsuccessful"] ?? "";

$members = getMembers($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Members</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/images/Members.svg">
</head>
<body>
  <div class="member-container"> 
    <?php include "../includes/Sidebar.php" ?>

    <div class="content">
      <?php if ($memberSuccess !== ""): ?>
        <p class="success"><?=htmlspecialchars($memberSuccess)?></p>

        <?php unset($_SESSION['memberSuccess']) ?>
      <?php endif; ?>

      <?php if ($memberUnsuccessful !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($memberUnsuccessful)?></p>

        <?php unset($_SESSION['memberUnsuccessful']) ?>
      <?php endif; ?>

      <?php if ($editMemberSuccessful !== ""): ?>
        <p class="success"><?=htmlspecialchars($editMemberSuccessful)?></p>

        <?php unset($_SESSION['editMemberSuccessful']) ?>
      <?php endif; ?>

      <?php if ($editMemberUnsuccessful !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($editMemberUnsuccessful)?></p>

        <?php unset($_SESSION['editMemberUnsuccessful']) ?>
      <?php endif; ?>

      <?php if ($memberDeleteSuccess !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($memberDeleteSuccess)?></p>

        <?php unset($_SESSION['memberDeleteSuccess']) ?>
      <?php endif; ?>

      <?php if ($memberDeleteUnsuccessful !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($memberDeleteUnsuccessful)?></p>

        <?php unset($_SESSION['memberDeleteUnsuccessful']) ?>
      <?php endif; ?>

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
        <div class="input-label"> 
          <label for="full_name">Full Name</label><br>

          <?php if (isset($memberErrors["full_name"])): ?>
            <p class="error">*<?= htmlspecialchars($memberErrors["full_name"]) ?></p>
          <?php endif; ?>
        </div>

        <input type="text" id="full_name" name="full_name" placeholder="Enter Member Name" required><br>
      </div>
      
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="email">Email</label><br>

          <?php if (isset($memberErrors["email"])): ?>
            <p class="error">*<?= htmlspecialchars($memberErrors["email"]) ?></p>
          <?php endif; ?>
        </div>
        
        <input type="email" id="email" name="email" placeholder="Enter Email" required><br>
      </div>
  
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="phone_number">Phone Number</label><br>

          <?php if (isset($memberErrors["phone_number"])): ?>
            <p class="error">*<?= htmlspecialchars($memberErrors["phone_number"]) ?></p>
          <?php endif; ?>
        </div>
       
        <input type="text" id="phone_number" name="phone_number" placeholder="eg. 09xxxxxxxxx" maxlength="11" required><br>
      </div>
    
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="address">Address</label><br>

          <?php if (isset($memberErrors["address"])): ?>
            <p class="error">*<?= htmlspecialchars($memberErrors["address"]) ?></p>
          <?php endif; ?>
        </div>
 
        <input type="text" id="address" name="address" placeholder="Enter Address" required><br>
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
        <div class="input-label"> 
          <label for="edit-full-name">Full Name</label><br>

          <?php if (isset($editMemberErrors["full_name"])): ?>
            <p class="error">*<?= htmlspecialchars($editMemberErrors["full_name"]) ?></p>
          <?php endif; ?>
        </div>
       
        <input type="text" id="edit-full-name" name="full_name" placeholder="Enter Full Name" required><br>
      </div>
      
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-email">Email</label><br>

          <?php if (isset($editMemberErrors["email"])): ?>
            <p class="error">*<?= htmlspecialchars($editMemberErrors["email"]) ?></p>
          <?php endif; ?>
        </div>

        <input type="email" id="edit-email" name="email" placeholder="Enter Email" required><br>
      </div>
  
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-phone-number">Phone Number</label><br>

          <?php if (isset($editMemberErrors["phone_number"])): ?>
            <p class="error">*<?= htmlspecialchars($editMemberErrors["phone_number"]) ?></p>
          <?php endif; ?>
        </div>
        
        <input type="text" id="edit-phone-number" name="phone_number" placeholder="Enter Phone Number" maxlength="11" required><br>
      </div>
    
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-address">Address</label><br>

          <?php if (isset($editMemberErrors["address"])): ?>
            <p class="error">*<?= htmlspecialchars($editMemberErrors["address"]) ?></p>
          <?php endif; ?>
        </div>
       
        <input type="text" id="edit-address" name="address" placeholder="Enter Address" required><br>
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