// Dashboard elements
const totalBooks = document.getElementById('total-books');
const totalMembers = document.getElementById('total-members');
const totalBorrowed = document.getElementById('total-borrowed');
const recentTransactionsTable = document.getElementById('recent-transactions-table-body');

const transactionsTable = document.querySelector('.table-section table');
const viewAllSection = document.getElementById('view-all');

const noTransactions = document.createElement('div');
noTransactions.className = 'no-dashboard-transactions';
noTransactions.innerHTML = `<img src="../assets/images/NoTransactions.svg" alt="No transactions"> <p>No recent transactions.</p>`;

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

    //check kung may recent transactions or wala, if wala, show no recent transactions message
    const hasNoTransactions = transactions.length === 0;

    if (hasNoTransactions) {
      transactionsTable.replaceWith(noTransactions);
    } else {
      noTransactions.replaceWith(transactionsTable);
    }

    viewAllSection.hidden = hasNoTransactions;

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
          <td class="truncate" title="${escapeHtml(transaction.full_name)}">${escapeHtml(transaction.full_name)}</td>
          <td class="truncate" title="${escapeHtml(transaction.title)}">${escapeHtml(transaction.title)}</td>
          <td>
            <span class="${transaction.status === 'borrowed' ? 'borrowed' : 'returned'} status">
              ${transaction.status}
            </span>
          </td>
        </tr>
      `;
    });

    markTruncatedCells();
  } catch (error) {
    recentTransactionsFailed.innerHTML = "Failed to Load Recent Transactions";
    recentTransactionsFailed.hidden = false;

    console.error('Failed to load dashboard:', error);
  }
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}


// hover sa truncate cells, if truncated, show tooltip
function markTruncatedCells() {
  recentTransactionsTable.querySelectorAll('.truncate').forEach((cell) => {
    if (cell.classList.contains('expanded')) return;

    cell.classList.toggle('is-truncated', cell.scrollWidth > cell.clientWidth);
  });
}

window.addEventListener('resize', markTruncatedCells);

recentTransactionsTable.addEventListener('click', (event) => {
  const cell = event.target.closest('.truncate.is-truncated, .truncate.expanded');

  if (cell) {
    cell.classList.toggle('expanded');
  }
});

// Automatically load data pag bukas ng transactions page
loadDashboard();