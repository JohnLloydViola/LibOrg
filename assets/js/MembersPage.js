//Add member modal behavior
const addMemberModal = document.getElementById('add-member-modal');
const openAddMemberModalBtn = document.getElementById('open-add-member-modal-btn');
const closeAddMemberModalBtn = document.getElementById('close-add-member-modal-btn');

openAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.showModal();
});

closeAddMemberModalBtn.addEventListener('click', ()=>{
  addMemberModal.close();
});

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
});

// Loading members according to members search input
const memberSearch = document.getElementById('member-search');

// UI pagination logic
const memberEntries = document.getElementById('member-entries');
let currentPage = 1;
let totalPages;

const pagination = document.getElementById('member-pagination');

pagination.addEventListener('click', (event) => {
  event.preventDefault();

  const button = event.target.closest('a');

  if (!button) {
    return;
  }

  if (button.classList.contains('prev')) {
    if (currentPage > 1) {
      currentPage--;
      loadMembers();
    }

    return;
  }

  if (button.classList.contains('next')) {
    if (currentPage < totalPages) {
      currentPage++;
      loadMembers();
    }

    return;
  }

  const page = Number(button.textContent);

  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    loadMembers();
  }
});

async function loadMembers() {
  const search = memberSearch.value;

  try {
    const response = await axios.get(
      `../actions/member/MemberSearch.php?name=${encodeURIComponent(search)}&page=${encodeURIComponent(currentPage)}`
    );

    const totalMembers = response.data.total;

    totalPages = Math.ceil(totalMembers / 6);

    // About sa showing or display of entries
    const startEntry = totalMembers === 0 ? 0 : (currentPage - 1) * 6 + 1;
    const endEntry = Math.min(currentPage * 6, totalMembers);

    memberEntries.textContent = `Showing ${startEntry} to ${endEntry} of ${totalMembers} entries`;

    renderPagination();

    renderMembers(response.data.members);
  } catch (error) {
    console.log('Errormember search');
  }
}

memberSearch.addEventListener('input', () => {
  currentPage = 1;
  loadMembers();
});

// Rendering members table
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

            <img src="../assets/images/Edit.svg" alt="Edit">
          </button>

          <form action="../actions/member/MemberDelete.php" method="POST">
            <input type="hidden" name="id" value="${member.id}">

            <button type="submit">
              <img src="../assets/images/Delete.svg" alt="delete">
            </button>
          </form>
        </td>
      </tr>
    `;
  });
}

function renderPagination() {
  if (totalPages === 0) {
    pagination.innerHTML = '';
    return;
  }

  pagination.innerHTML = `
    <li><a href="#" class="prev">&laquo; Prev</a></li>
  `;

  let startPage = Math.max(1, currentPage - 2);
  let endPage = Math.min(totalPages, startPage + 4);

  startPage = Math.max(1, endPage - 4);

  if (startPage > 1) {
    pagination.innerHTML += `
      <li><a href="#">1</a></li>
      <li><span>...</span></li>
    `;
  }

  for (let page = startPage; page <= endPage; page++) {
    pagination.innerHTML += `
      <li>
        <a href="#" class="${page === currentPage ? 'active' : ''}">${page}</a>
      </li>
    `;
  }

  if (endPage < totalPages) {
    pagination.innerHTML += `
      <li><span>...</span></li>
      <li><a href="#">${totalPages}</a></li>
    `;
  }

  pagination.innerHTML += `
    <li><a href="#" class="next">Next &raquo;</a></li>
  `;
}

loadMembers();