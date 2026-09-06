const addMemberModal = document.getElementById('add-member-modal');
const openAddMemberModalBtn = document.getElementById('open-add-member-modal-btn');
const closeAddMemberModalBtn = document.getElementById('close-add-member-modal-btn');

openAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.showModal();
});

closeAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.close();
})

const editMemberModal = document.getElementById('edit-member-modal');
const openEditMemberModalBtn = document.querySelectorAll('.open-edit-member-modal-btn');
const closeEditMemberModalBtn = document.getElementById('close-edit-member-modal-btn');

const editId = document.getElementById('edit-id');
const editFullName = document.getElementById('edit-full-name');
const editEmail = document.getElementById('edit-email');
const editPhoneNumber = document.getElementById('edit-phone-number');
const editAddress = document.getElementById('edit-address');

openEditMemberModalBtn.forEach((button) => {
  button.addEventListener('click', () => {
    editId.value = button.dataset.id;
    editFullName.value = button.dataset.fullName;
    editEmail.value = button.dataset.email;
    editPhoneNumber.value = button.dataset.phoneNumber;
    editAddress.value = button.dataset.address;

    editMemberModal.showModal();
  });
});

closeEditMemberModalBtn.addEventListener('click', () => {
  editMemberModal.close();
})