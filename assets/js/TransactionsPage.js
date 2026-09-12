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
    const response = await axios.get(
      `../actions/transaction/TransactionSearch.php?name=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}&page=${encodeURIComponent(currentPage)}`
    );

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
        <td>${transaction.status}</td>
        <td>
          <form action="../actions/transaction/TransactionReturn.php" method="POST">
            <input type="hidden" name="id" value="${transaction.id}">
            <button type="submit">Return Book</button>
          </form>

          <form action="../actions/transaction/TransactionDelete.php" method="POST">
            <input type="hidden" name="id" value="${transaction.id}">

            <button type="submit">
              <img src="../assets/images/Delete.png" alt="delete">
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

  for (let page = 1; page <= totalPages; page++) {
    pagination.innerHTML += `
      <li>
        <a href="#" class="${page === currentPage ? 'active' : ''}">${page}</a>
      </li>
    `;
  }

  pagination.innerHTML += `
    <li><a href="#" class="next">Next &raquo;</a></li>
  `;
}

loadTransactions();