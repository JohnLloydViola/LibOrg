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

//Event listener sa search transact
transactionSearch.addEventListener('input', () => {
  loadTransactions();
});

transactionStatus.addEventListener('change', () => {
  loadTransactions();
});

async function loadTransactions() {
  const search = transactionSearch.value;
  const status = transactionStatus.value;

  try {
    const response = await axios.get(`../actions/transaction/TransactionSearch.php?name=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`);

    renderTransactions(response.data);
  } catch (error) {
    console.log('Error transaction seasrch');
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