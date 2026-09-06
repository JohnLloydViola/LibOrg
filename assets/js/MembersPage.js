const addMemberModal = document.getElementById('add-member-modal');
const openAddMemberModalBtn = document.getElementById('open-add-member-modal-btn');
const closeAddMemberModalBtn = document.getElementById('close-add-member-modal-btn');

openAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.showModal();
});

closeAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.close();
})