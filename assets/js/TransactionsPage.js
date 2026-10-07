//Sa borrow book modal
const borrowBookModal = document.getElementById('borrow-book-modal');
const OpenBorrowBookModalBtn = document.getElementById('open-borrow-book-modal-btn');
const closeBorrowBookModalBtn = document.getElementById('close-borrow-book-modal-btn');

OpenBorrowBookModalBtn.addEventListener('click', () => {
  borrowBookModal.showModal();
});

closeBorrowBookModalBtn.addEventListener('click', () => {
  borrowBookModal.close();
});

const transactionsTable = document.getElementById('transaction-table-body');
const transactionSearch = document.getElementById('transaction-search');
const transactionStatus = document.getElementById('transaction-status');


// Select elements for member and book in the borrow book modal
const memberSelect = document.getElementById('member_id');
const bookSelect = document.getElementById('book_id');

// Banner message
const transactionSucces = document.getElementById('transaction-success');
const transactionFailed = document.getElementById('transaction-failed');

// UI pagination logic
const transactionEntries = document.getElementById('transaction-entries');
let currentPage = 1;
let totalPages;

const pagination = document.getElementById('transaction-pagination');

pagination.addEventListener('click', (event) => {
  event.preventDefault();

  const button = event.target.closest('a');

  if (!button) {
    return;
  }

  if (button.classList.contains('prev')) {
    if (currentPage > 1) {
      currentPage--;
      loadTransactions();
    }

    return;
  }

  if (button.classList.contains('next')) {
    if (currentPage < totalPages) {
      currentPage++;
      loadTransactions();
    }

    return;
  }

  const page = Number(button.textContent);

  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    loadTransactions();
  }
});

// Borrow book form
const borrowBookForm = document.getElementById('borrow-book-form');

// Error msg near the select input sa borrow book modal
const memberError = document.getElementById('borrow-member-error');
const bookError = document.getElementById('borrow-book-error');

borrowBookForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  // Caler yung previous messages sa banner
  transactionSucces.textContent = '';
  transactionSucces.hidden = true;

  const transactionData = {
    member_id: memberSelect.value,
    book_id: bookSelect.value
  };

  try {
    // Pass the object as POST data
    const response = await axios.post('../api/transactions/Create.php', transactionData);

    // Display success message
    transactionSucces.textContent = response.data.message;
    transactionSucces.hidden = false;

    borrowBookModal.close();
    borrowBookForm.reset();

    // Reload transaction options and table
    loadTransactionOptions();
    currentPage = 1;
    loadTransactions();

    // Hide error after successful borrowing
    transactionFailed.textContent = '';
    transactionFailed.hidden = true;
  } catch (error) {
    if (error.response && error.response.status === 422) {
      memberError.textContent = error.response.data.errors?.memberSelect || '';
      bookError.textContent = error.response.data.errors?.bookSelect || '';
    } else {
      transactionFailed.textContent = error.response?.data?.message || 'Failed to borrow book.';
      transactionFailed.hidden = false;
      console.error('Failed to borrow book:', error);
    }
  }
});

//Event listener sa search transact
transactionSearch.addEventListener('input', () => {
  currentPage = 1;
  loadTransactions();
});

transactionStatus.addEventListener('change', () => {
  currentPage = 1;
  loadTransactions();
});

async function loadTransactions() {
  const search = transactionSearch.value;
  const status = transactionStatus.value;

  try {
    const response = await axios.get('../api/transactions/index.php', {
      params: {
        name: search,
        status: status,
        page: currentPage
      }
    });

    const totalTransactions = response.data.total;

    totalPages = Math.ceil(totalTransactions / 6);

    // About sa showing or display of entries
    const startEntry = totalTransactions === 0 ? 0 : (currentPage - 1) * 6 + 1;
    const endEntry = Math.min(currentPage * 6, totalTransactions);

    transactionEntries.textContent = `Showing ${startEntry} to ${endEntry} of ${totalTransactions} entries`;

    renderPagination();

    renderTransactions(response.data.transactions);
  } catch (error) {
    console.log('Error transaction search');
  }
}

function renderTransactions(transactions) {
  transactionsTable.innerHTML = '';

  transactions.forEach((transaction) => {
    transactionsTable.innerHTML += `
      <tr>
        <td>T${String(transaction.id).padStart(3, '0')}</td>
        <td>${transaction.full_name}</td>
        <td>${transaction.title}</td>
        <td>
          <span class="${transaction.status === 'borrowed' ? 'borrowed' : 'returned'} status">
            ${transaction.status}
          </span>
        </td>
        <td>
          <button class="return-book" type="button" data-id="${transaction.id}">
            Return Book
          </button>

          <button class="delete-book" type="button" data-id="${transaction.id}">
            <img src="../assets/images/Delete.svg" alt="delete">
          </button>
        </td>
      </tr>
    `;
  });
}

transactionsTable.addEventListener('click', async (event) => {
  const returnButton = event.target.closest('.return-book');

  if (!returnButton) {
    return;
  }

  const id = returnButton.dataset.id;

  try {
    const response = await axios.post('../api/transactions/Return.php',{id});

    // Clear error message if the transaction is successful
    transactionFailed.textContent = '';
    transactionFailed.hidden = true;
    
    // Display success message
    transactionSucces.textContent = response.data.message;
    transactionSucces.hidden = false;

    // Refresh transaction options and table
    loadTransactionOptions();
    currentPage = 1;
    loadTransactions();

  } catch (error) {
    transactionFailed.textContent = error.response?.data?.message || 'Failed to return book.';
    transactionFailed.hidden = false;

    console.error('Failed to return book:', error);
  }
});

//Event listener for delete button in the transactions table
transactionsTable.addEventListener('click', async (event) => {
  const deleteButton = event.target.closest('.delete-book');

  if (!deleteButton) return;

  const id = deleteButton.dataset.id;

  try {
    const response = await axios.post('../api/transactions/Delete.php', { id });

    transactionSucces.textContent = response.data.message;
    transactionSucces.hidden = false;
    transactionFailed.hidden = true;

    loadTransactionOptions();
    currentPage = 1;
    loadTransactions();

  } catch (error) {
    transactionFailed.textContent = error.response?.data?.message || 'Failed to delete transaction.';
    transactionFailed.hidden = false;
    transactionSucces.hidden = true;

    console.error('Failed to delete transaction:', error);
  }
});

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

async function loadTransactionOptions() {
  try {
    const response = await axios.get(
      '../api/transactions/TransactionOptions.php'
    );

    const { members, books } = response.data;

    // Refresh everything so no duplicate sa old data.
    memberSelect.innerHTML = '<option disabled selected value="">Select Member</option>';
    bookSelect.innerHTML = '<option disabled selected value="">Select Book</option>';

    members.forEach((member) => {
      memberSelect.innerHTML += `
        <option value="${member.id}">
          ${member.full_name}
        </option>
      `;
    });

    books.forEach((book) => {
      bookSelect.innerHTML += `
        <option value="${book.id}" ${book.available_quantity <= 0 ? 'disabled' : ''}>
          ${book.title} (Available: ${book.available_quantity})
        </option>
      `;
    });
  } catch (error) {
    transactionFailed.textContent = error.response?.data?.message || 'An error occurred while loading transaction options.';
    transactionFailed.hidden = false;
  }
}

loadTransactionOptions();
loadTransactions();