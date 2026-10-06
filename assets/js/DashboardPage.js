
// Dashboard elements
const totalBooks = document.getElementById('total-books');
const totalMembers = document.getElementById('total-members');
const totalBorrowed = document.getElementById('total-borrowed');
const recentTransactionsTable = document.getElementById('recent-transactions-table-body');

//banner msg
const recentTransactionsFailed = document.getElementById('recent-transactions-failed');

// Load dashboard data
async function loadDashboard() {
  try {
    const response = await axios.get('../api/dashboard/index.php');

    const books = response.data.totalBooks;
    const members = response.data.totalMembers;
    const borrowed = response.data.totalBorrowed;
    const transactions = response.data.transactions;

    //Hide the error message and clear
    recentTransactionsFailed.innerHTML = '';
    recentTransactionsFailed.hidden = true;

    // Display dashboard counts
    totalBooks.textContent = books;
    totalMembers.textContent = members;
    totalBorrowed.textContent = borrowed;

    // Display recent transactions
    recentTransactionsTable.innerHTML = '';

    transactions.forEach((transaction) => {
      recentTransactionsTable.innerHTML += `
        <tr>
          <td>T${String(transaction.id).padStart(3, '0')}</td>
          <td>${transaction.full_name}</td>
          <td>${transaction.title}</td>
          <td>
            <span class="${transaction.status === 'borrowed' ? 'borrowed' : 'returned'} status">
              ${transaction.status}
            </span>
          </td>
        </tr>
      `;
    });

  } catch (error) {
    recentTransactionsFailed.innerHTML = "Failed to Load Recent Transactions";
    recentTransactionsFailed.hidden = false;

    console.error('Failed to load dashboard:', error);
  }
}

// Automatically load data pag bukas ng transactions page
loadDashboard();