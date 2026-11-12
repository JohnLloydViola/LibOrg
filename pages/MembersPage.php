<?php 
declare(strict_types=1);

// Login check ito
require_once __DIR__ . "/../config/session.php";

if (!isset($_SESSION["member_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ./LoginPage.php");
    exit;
}
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
    <?php require_once __DIR__ .  "/../includes/Sidebar.php" ?>

    <div class="content">
      <?php require_once __DIR__ . "/../includes/MenuButton.php"; ?>
      
      <p class="success" id="member-success" hidden></p>

      <p class="deleted" id="member-failed" hidden></p>

      <div class="add-member">
        <h1>Members</h1>
        <button type="button" id="open-add-member-modal-btn">+ Add Member</button>
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
    
    <form class="modal-information" id="add-member-form">
      <div class="modal-input">
        <div class="input-label">
          <label for="full_name">Full Name</label><br>
          <p class="error" id="full_name-error"></p>
        </div>

        <input type="text" id="full_name" name="full_name" placeholder="Enter Member Name" minlength="2" maxlength="100" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="email">Email</label><br>
          <p class="error" id="email-error"></p>
        </div>

        <input type="email" id="email" name="email" placeholder="Enter Email" maxlength="255" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="password">Password</label><br>
          <p class="error" id="password-error"></p>
        </div>

        <input type="password" id="password" name="password" placeholder="Enter Password" minlength="8" maxlength="64" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="phone_number">Phone Number</label><br>
          <p class="error" id="phone_number-error"></p>
        </div>

        <input type="text" id="phone_number" name="phone_number" placeholder="eg. 09xxxxxxxxx" pattern="09[0-9]{9}" maxlength="11" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="address">Address</label><br>
          <p class="error" id="address-error"></p>
        </div>

        <input type="text" id="address" name="address" placeholder="Enter Address" minlength="5" maxlength="255" required><br>
      </div>

      <div>
        <button class="modal-submit" type="submit">Save Member</button>
        <button class="modal-cancel" type="button" id="close-add-member-modal-btn">cancel</button>
      </div>
    </form>
  </dialog>

<!-- Modal dialogue para sa Edit Member button -->
  <dialog id="edit-member-modal">
    <div><h1>EDIT MEMBER</h1></div>

    <form class="modal-information" id="edit-member-form">
      <input type="hidden" id="edit-id" name="id">

      <div class="modal-input">
        <div class="input-label">
          <label for="edit-full-name">Full Name</label><br>
          <p class="error" id="edit-full_name-error"></p>
        </div>

        <input type="text" id="edit-full-name" name="full_name" placeholder="Enter Full Name" minlength="2" maxlength="100" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="edit-email">Email</label><br>
          <p class="error" id="edit-email-error"></p>
        </div>

        <input type="email" id="edit-email" name="email" placeholder="Enter Email" maxlength="255" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="edit-password">Password</label><br>
          <p class="error" id="edit-password-error"></p>
        </div>

        <input
          type="password"
          id="edit-password"
          name="password"
          placeholder="Leave blank to keep current password"
          minlength="8"
          maxlength="64"
        >
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="edit-phone-number">Phone Number</label><br>
          <p class="error" id="edit-phone_number-error"></p>
        </div>

        <input type="text" id="edit-phone-number" name="phone_number" placeholder="Enter Phone Number" pattern="09[0-9]{9}" maxlength="11" required><br>
      </div>

      <div class="modal-input">
        <div class="input-label">
          <label for="edit-address">Address</label><br>
          <p class="error" id="edit-address-error"></p>
        </div>

        <input type="text" id="edit-address" name="address" placeholder="Enter Address" minlength="5" maxlength="255" required><br>
      </div>

      <div>
        <button class="modal-submit" type="submit">Save Member</button>
        <button class="modal-cancel" type="button" id="close-edit-member-modal-btn">Cancel</button>
      </div>
    </form>
  </dialog>

  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/api.js"></script>
  <script src="../assets/js/laravelApi.js"></script>
  <script src="../assets/js/MembersPage.js"></script>
  <script src="../assets/js/Logout.js"></script>
  <script src="../assets/js/Menu.js"></script>
</body>
</html>