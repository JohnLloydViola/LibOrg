//Add member modal behavior
const addMemberModal = document.getElementById('add-member-modal');
const openAddMemberModalBtn = document.getElementById('open-add-member-modal-btn');
const closeAddMemberModalBtn = document.getElementById('close-add-member-modal-btn');

openAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.showModal();
});

closeAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.close();
})

// Para sa edit member modal behavior
const editMemberModal = document.getElementById('edit-member-modal');
const closeEditMemberModalBtn = document.getElementById('close-edit-member-modal-btn');

const editId = document.getElementById('edit-id');
const editFullName = document.getElementById('edit-full-name');
const editEmail = document.getElementById('edit-email');
const editPhoneNumber = document.getElementById('edit-phone-number');
const editAddress = document.getElementById('edit-address');

//event listner sa memebrs body table
const membersTable = document.getElementById('member-table-body');

membersTable.addEventListener('click', (event) => {
  const button = event.target.closest('.open-edit-member-modal-btn');

  if (!button) {
    return;
  }

  editId.value = button.dataset.id;
  editFullName.value = button.dataset.fullName;
  editEmail.value = button.dataset.email;
  editPhoneNumber.value = button.dataset.phoneNumber;
  editAddress.value = button.dataset.address;

  editMemberModal.showModal();
});

closeEditMemberModalBtn.addEventListener('click', () => {
  editMemberModal.close();
})

//Loading members according to members search input
const memberSearch = document.getElementById('member-search');

async function loadMembers() {
  const search = memberSearch.value;

  try {
    const response = await axios.get(`../actions/member/MemberSearch.php?name=${encodeURIComponent(search)}`);

    renderMembers(response.data);
  } catch (error) {
    console.log('Errormember search');
  }
}

memberSearch.addEventListener('input', () => {
  loadMembers();
}) 

//Rendering members table
function renderMembers(members) {
  membersTable.innerHTML = '';

  members.forEach((member) => {
    membersTable.innerHTML += `
      <tr>
        <td>M${String(member.id).padStart(3, '0')}</td>
        <td>${member.full_name}</td>
        <td>${member.email}</td>
        <td>${member.phone_number}</td>
        <td>${member.address}</td>
        <td>
          <button
            type="button"
            class="open-edit-member-modal-btn"
            data-id="${member.id}"
            data-full-name="${member.full_name}"
            data-email="${member.email}"
            data-phone-number="${member.phone_number}"
            data-address="${member.address}">

            <img src="../assets/images/Edit.png" alt="Edit">
          </button>

          <form action="../actions/member/MemberDelete.php" method="POST">
            <input type="hidden" name="id" value="${member.id}">

            <button type="submit">
              <img src="../assets/images/Delete.png" alt="delete">
            </button>
          </form>
        </td>
      </tr>
    `;
  });
}