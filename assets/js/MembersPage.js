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

// Banner messages
const memberSuccess = document.getElementById('member-success');
const memberFailed = document.getElementById('member-failed');

// Para sa edit member modal behavior
const editMemberModal = document.getElementById('edit-member-modal');
const closeEditMemberModalBtn = document.getElementById('close-edit-member-modal-btn');

const editId = document.getElementById('edit-id');
const editFullName = document.getElementById('edit-full-name');
const editEmail = document.getElementById('edit-email');
const editPhoneNumber = document.getElementById('edit-phone-number');
const editAddress = document.getElementById('edit-address');
const editPassword = document.getElementById('edit-password');

closeEditMemberModalBtn.addEventListener('click', () => {
  editMemberModal.close();
});

// Edit Member form using axios
const editMemberForm = document.getElementById('edit-member-form');

editMemberForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  const errorFields = [
  'edit-full_name-error',
  'edit-email-error',
  'edit-password-error',
  'edit-phone_number-error',
  'edit-address-error'
  ];

  errorFields.forEach((field) => {
    document.getElementById(field).textContent = '';
  });

  const memberData = {
  id: editId.value,
  full_name: editFullName.value,
  email: editEmail.value,
  password: editPassword.value,
  phone_number: editPhoneNumber.value,
  address: editAddress.value
  };

  // clear banners
  memberSuccess.textContent = '';
  memberSuccess.hidden = true;

  memberFailed.textContent = '';
  memberFailed.hidden = true;
  try {
    const response = await axios.post('../api/members/update.php', memberData);

    memberSuccess.textContent = response.data.message;
    memberSuccess.hidden = false;

    editMemberModal.close();
    editMemberForm.reset();

    currentPage = 1;
    loadMembers();
  } catch (error) {
    if (error.response && error.response.status === 409) {
      document.getElementById('edit-email-error').textContent =
        'Email already exists';
    } else if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors;

      document.getElementById('edit-full_name-error').textContent =
        errors.full_name ? `*${errors.full_name}` : '';

      document.getElementById('edit-email-error').textContent =
        errors.email ? `*${errors.email}` : '';

      document.getElementById('edit-phone_number-error').textContent =
        errors.phone_number ? `*${errors.phone_number}` : '';

      document.getElementById('edit-address-error').textContent =
        errors.address ? `*${errors.address}` : '';

      document.getElementById('edit-password-error').textContent =
        errors.password ? `*${errors.password}` : '';
    } else {
      memberFailed.textContent =
        error.response?.data?.message ||
        'An error occurred while updating the member.';

      memberFailed.hidden = false;
      console.error('Failed to update member:', error);
    }
  }
});

// Add member gamit axios
const addMemberForm = document.getElementById('add-member-form');

addMemberForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  // Clear previous validation errors
  const errorFields = ['full_name', 'email', 'password', 'phone_number', 'address'];

  errorFields.forEach((field) => {
    document.getElementById(`${field}-error`).textContent = '';
  });

  // Clear previous success and error messages
  memberSuccess.textContent = '';
  memberSuccess.hidden = true;

  memberFailed.textContent = '';
  memberFailed.hidden = true;

  const memberData = {
  full_name: document.getElementById('full_name').value,
  email: document.getElementById('email').value,
  password: document.getElementById('password').value,
  phone_number: document.getElementById('phone_number').value,
  address: document.getElementById('address').value
};

  try {
    // pass yung object as post
    const response = await axios.post('../api/members/create.php', memberData);

    // Display success message
    memberSuccess.textContent = response.data.message;
    memberSuccess.hidden = false;

    addMemberModal.close();
    addMemberForm.reset();

    // Go back to page 1 after adding
    currentPage = 1;
    loadMembers();

    // Hide errors pag successful add ng member
    memberFailed.textContent = '';
    memberFailed.hidden = true;
  } catch (error) {
    if (error.response && error.response.status === 409) {
      document.getElementById('email-error').textContent ='Email already exists';
    } else if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors;

      // Display validation errors
      Object.keys(errors).forEach((field) => {
        const errorElement = document.getElementById(`${field}-error`);

        if (errorElement) {
          errorElement.textContent = `*${errors[field]}`;
        }
      });
    } else {
      memberFailed.textContent = error.response?.data?.message || 'An error occurred while adding the member.';
      memberFailed.hidden = false;
      console.error('Failed to add member:', error);
    }
  }
});

// Event listener sa members table body
const membersTable = document.getElementById('member-table-body');

membersTable.addEventListener('click', async (event) => {
  // Edit button
  const editButton = event.target.closest('.open-edit-member-modal-btn');

  if (editButton) {
    editId.value = editButton.dataset.id;
    editFullName.value = editButton.dataset.fullName;
    editEmail.value = editButton.dataset.email;
    editPhoneNumber.value = editButton.dataset.phoneNumber;
    editAddress.value = editButton.dataset.address;
    editPassword.value = '';

    const errorFields = [
      'edit-full_name-error',
      'edit-email-error',
      'edit-phone_number-error',
      'edit-address-error'
    ];

    errorFields.forEach((field) => {
      document.getElementById(field).textContent = '';
    });

    memberFailed.textContent = '';
    memberFailed.hidden = true;

    editMemberModal.showModal();
    return;
  }

  // Delete button pag click ng delete kada row and API call
  const deleteButton = event.target.closest('.delete-member-btn');

  if (!deleteButton) {
    return;
  }

  const id = deleteButton.dataset.id;

  try {
    const response = await axios.post('../api/members/delete.php', { id: id });

    memberSuccess.textContent = response.data.message;
    memberSuccess.hidden = false;

    memberFailed.textContent = '';
    memberFailed.hidden = true;

    currentPage = 1;
    loadMembers();
  } catch (error) {
    memberSuccess.textContent = '';
    memberSuccess.hidden = true;

    memberFailed.textContent = error.response?.data?.message || 'An error occurred while deleting the member.';
    memberFailed.hidden = false;

    console.error('Failed to delete member:', error);
  }
});

membersTable.addEventListener('click', (event) => {
  const cell = event.target.closest('.truncate.is-truncated, .truncate.expanded');

  if (cell) {
    cell.classList.toggle('expanded');
  }
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

const memberTableElement = document.querySelector('.table-section table');
const entriesSection = document.querySelector('.entries');

const noMembers = document.createElement('div');
noMembers.className = 'no-members';
noMembers.innerHTML = `<img src="../assets/images/NoMembers.svg" alt="No members"> <p>No members found.</p>`;

async function loadMembers() {
  const search = memberSearch.value;

  try {
    const response = await axios.get('../api/members/index.php', {
    params: {
    name: search,
    page: currentPage
    }
  });

    const totalMembers = response.data.total;

    //check kung may members or wala, if wala, show no members message
    if (totalMembers === 0) {
      memberTableElement.replaceWith(noMembers);
    } else {
      noMembers.replaceWith(memberTableElement);
    }

    entriesSection.hidden = totalMembers === 0;

    totalPages = Math.ceil(totalMembers / 6);

    // About sa showing or display of entries
    const startEntry = totalMembers === 0 ? 0 : (currentPage - 1) * 6 + 1;
    const endEntry = Math.min(currentPage * 6, totalMembers);

    memberEntries.textContent = `Showing ${startEntry} to ${endEntry} of ${totalMembers} entries`;

    renderPagination();

    renderMembers(response.data.members);
  } catch (error) {
    memberFailed.textContent = error.response?.data?.message || 'An error occurred while fetching members.';
    memberFailed.hidden = false;

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
        <td class="truncate" title="${escapeHtml(member.full_name)}">${escapeHtml(member.full_name)}</td>
        <td class="truncate" title="${escapeHtml(member.email)}">${escapeHtml(member.email)}</td>
        <td>${escapeHtml(member.phone_number)}</td>
        <td class="truncate" title="${escapeHtml(member.address)}">${escapeHtml(member.address)}</td>
        <td>
          <button
            type="button"
            class="open-edit-member-modal-btn"
            data-id="${member.id}"
            data-full-name="${escapeHtml(member.full_name)}"
            data-email="${escapeHtml(member.email)}"
            data-phone-number="${escapeHtml(member.phone_number)}"
            data-address="${escapeHtml(member.address)}">

            <img src="../assets/images/Edit.svg" alt="Edit">
          </button>

          <button
            type="button"
            class="delete-member-btn"
            data-id="${member.id}">
            <img src="../assets/images/Delete.svg" alt="Delete">
          </button>
        </td>
      </tr>
    `;
  });
  markTruncatedCells();
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

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

// Hanapin yung mga cell na na-cut off (may "..."), tapos lagyan ng class para sa hover at pointer
function markTruncatedCells() {
  membersTable.querySelectorAll('.truncate').forEach((cell) => {
    if (cell.classList.contains('expanded')) return;

    cell.classList.toggle('is-truncated', cell.scrollWidth > cell.clientWidth);
  });
}

window.addEventListener('resize', markTruncatedCells);

loadMembers();