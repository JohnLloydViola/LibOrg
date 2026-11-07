// Search, category filter, and availability sorting sa bookspage and loading books
const bookSearch = document.getElementById('book-search');
const bookCategory = document.getElementById('book-category');
const bookSort = document.getElementById('book-sort');

const booksTableBody = document.getElementById('books-table-body');
const bookFailed = document.getElementById('browse-failed');

// UI pagination logic
const bookEntries = document.getElementById('book-entries');
let currentPage = 1;
let totalPages;

const pagination = document.getElementById('book-pagination');

pagination.addEventListener('click', (event) => {
  event.preventDefault();

  const button = event.target.closest('a');

  if (!button) {
    return;
  }

  if (button.classList.contains('prev')) {
    if (currentPage > 1) {
      currentPage--;
      loadBooks();
    }

    return;
  }

  if (button.classList.contains('next')) {
    if (currentPage < totalPages) {
      currentPage++;
      loadBooks();
    }

    return;
  }

  const page = Number(button.textContent);

  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    loadBooks();
  }
});

const booksTableElement = document.querySelector('.table-section table');
const entriesSection = document.querySelector('.entries');

const noBooks = document.createElement('div');
noBooks.className = 'no-books';
noBooks.innerHTML = `<img src="../../assets/images/NoBooks.svg" alt="No books"> <p>No books found.</p>`;

async function loadBooks() {
  const search = bookSearch.value;
  const category = bookCategory.value;
  const sort = bookSort.value;

  try {
    const response = await axios.get("../../api/books/index.php", {
      params: {
        title: search,
        category: category,
        sort: sort,
        page: currentPage
      }
    });

    const totalBooks = response.data.total;
    
    // hide banner msgs
    bookFailed.textContent = '';
    bookFailed.hidden = true;

    if (totalBooks === 0) {
      booksTableElement.replaceWith(noBooks);
    } else {
      noBooks.replaceWith(booksTableElement);
    }

    entriesSection.hidden = totalBooks === 0;

    totalPages = Math.ceil(totalBooks / 6);

    // About sa showing or display of entries
    const startEntry = totalBooks === 0 ? 0 : (currentPage - 1) * 6 + 1;
    const endEntry = Math.min(currentPage * 6, totalBooks);
    bookEntries.textContent = `Showing ${startEntry} to ${endEntry} of ${totalBooks} entries`;

    renderPagination();

    renderBooks(response.data.books);
  } catch (error) {
    bookFailed.textContent = "An error occurred while loading the books.";
    bookFailed.hidden = false;
    console.error('Failed to load books:', error);
  }
}

// Search listener event
bookSearch.addEventListener('input', () => {
  currentPage = 1;
  loadBooks();
});

// Category listener event
bookCategory.addEventListener('change', () => {
  currentPage = 1;
  loadBooks();
});

// Sort listener event
bookSort.addEventListener('change', () => {
  currentPage = 1;
  loadBooks();
});

// Render books sa table body sa bookspage
function renderBooks(books) {
  booksTableBody.innerHTML = '';

  books.forEach((book) => {
    booksTableBody.innerHTML += `
      <tr>
        <td class="truncate" title="${escapeHtml(book.title)}">${escapeHtml(book.title)}</td>
        <td class="truncate" title="${escapeHtml(book.author)}">${escapeHtml(book.author)}</td>
        <td>${book.category}</td>
        <td>${book.publication_year}</td>
        <td>${book.available_quantity}</td>
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

  // Adjust start page if we're near the last page
  startPage = Math.max(1, endPage - 4);

  // Show first page and ellipsis like ...
  if (startPage > 1) {
    pagination.innerHTML += `
      <li><a href="#">1</a></li>
      <li><span>...</span></li>
    `;
  }

  // Show page numbers
  for (let page = startPage; page <= endPage; page++) {
    pagination.innerHTML += `
      <li>
        <a href="#" class="${page === currentPage ? 'active' : ''}">${page}</a>
      </li>
    `;
  }

  // Show ellipsis and last page
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
  booksTableBody.querySelectorAll('.truncate').forEach((cell) => {
    if (cell.classList.contains('expanded')) return;

    cell.classList.toggle('is-truncated', cell.scrollWidth > cell.clientWidth);
  });
}

window.addEventListener('resize', markTruncatedCells);

booksTableBody.addEventListener('click', (event) => {
  const cell = event.target.closest('.truncate.is-truncated, .truncate.expanded');

  if (cell) {
    cell.classList.toggle('expanded');
  }
});
loadBooks();