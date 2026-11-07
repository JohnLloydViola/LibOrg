// Search, category filter, sorting, and pagination
const bookSearch = document.getElementById('book-search');
const bookCategory = document.getElementById('book-category');

const booksTableBody = document.getElementById('books-table-body');
const bookEntries = document.getElementById('book-entries');
const pagination = document.getElementById('book-pagination');

let currentPage = 1;
let totalPages = 0;

const booksTableElement = document.querySelector('.table-section table');
const entriesSection = document.querySelector('.entries');

const noBooks = document.createElement('div');
noBooks.className = 'no-books';
noBooks.innerHTML = `<img src="../../assets/images/NoBooks.svg" alt="No borrowed books"> <p>No borrowed books found.</p>`;

// Load borrowed books using Axios
async function loadBooks() {
  const search = bookSearch.value;
  const category = bookCategory.value;

  try {
    const response = await axios.get('../../api/member_page/BorrowedBooks.php', {
      params: {
        title: search,
        category: category,
        page: currentPage
      }
    });

    console.log("API response:", response.data);

    const totalBooks = response.data.total;

    if (totalBooks === 0) {
      booksTableElement.replaceWith(noBooks);
    } else {
      noBooks.replaceWith(booksTableElement);
    }

    entriesSection.hidden = totalBooks === 0;

    totalPages = Math.ceil(totalBooks / 6);

    // Display entries count
    const startEntry = totalBooks === 0
      ? 0
      : (currentPage - 1) * 6 + 1;

    const endEntry = Math.min(currentPage * 6, totalBooks);

    bookEntries.textContent =
      `Showing ${startEntry} to ${endEntry} of ${totalBooks} entries`;

    renderBooks(response.data.transactions);
    renderPagination();

  } catch (error) {
    booksTableBody.innerHTML = `
      <tr>
        <td colspan="4">Unable to load borrowed books.</td>
      </tr>
    `;

    bookEntries.textContent = 'Showing 0 to 0 of 0 entries';

    pagination.innerHTML = '';

    console.error('Failed to load borrowed books:', error);
  }
}


// Display borrowed books in the table
function renderBooks(books) {
  booksTableBody.innerHTML = '';


  books.forEach((book) => {
    booksTableBody.innerHTML += `
      <tr>
        <td>${book.title}</td>
        <td>${book.author}</td>
        <td>${book.category}</td>
        <td>${book.publication_year}</td>
      </tr>
    `;
  });
}


// Pagination
function renderPagination() {
  if (totalPages === 0) {
    pagination.innerHTML = '';
    return;
  }

  pagination.innerHTML = `
    <li>
      <a href="#" class="prev">&laquo; Prev</a>
    </li>
  `;

  let startPage = Math.max(1, currentPage - 2);
  let endPage = Math.min(totalPages, startPage + 4);

  // Adjust start page near the last page
  startPage = Math.max(1, endPage - 4);

  // First page and ellipsis
  if (startPage > 1) {
    pagination.innerHTML += `
      <li><a href="#">1</a></li>
      <li><span>...</span></li>
    `;
  }

  // Page numbers
  for (let page = startPage; page <= endPage; page++) {
    pagination.innerHTML += `
      <li>
        <a href="#" class="${page === currentPage ? 'active' : ''}">
          ${page}
        </a>
      </li>
    `;
  }

  // Last page and ellipsis
  if (endPage < totalPages) {
    pagination.innerHTML += `
      <li><span>...</span></li>
      <li><a href="#">${totalPages}</a></li>
    `;
  }

  pagination.innerHTML += `
    <li>
      <a href="#" class="next">Next &raquo;</a>
    </li>
  `;
}


// Pagination click events
pagination.addEventListener('click', (event) => {
  event.preventDefault();

  const button = event.target.closest('a');

  if (!button) {
    return;
  }

  // Previous page
  if (button.classList.contains('prev')) {
    if (currentPage > 1) {
      currentPage--;
      loadBooks();
    }
    return;
  }

  // Next page
  if (button.classList.contains('next')) {
    if (currentPage < totalPages) {
      currentPage++;
      loadBooks();
    }
    return;
  }

  // Specific page
  const page = Number(button.textContent.trim());

  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    loadBooks();
  }
});


// Search event
bookSearch.addEventListener('input', () => {
  currentPage = 1;
  loadBooks();
});


// Category filter event
bookCategory.addEventListener('change', () => {
  currentPage = 1;
  loadBooks();
});

// Initial load
loadBooks();
