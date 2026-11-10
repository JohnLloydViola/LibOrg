// Modal behaviors
const addBookModal = document.getElementById('add-book-modal');
const openAddBookModalBtn = document.getElementById('open-add-book-modal-btn');
const closeAddBookModalBtn = document.getElementById('close-add-book-modal-btn');

openAddBookModalBtn.addEventListener('click', () => {
  addBookModal.showModal();
});

closeAddBookModalBtn.addEventListener('click', () => {
  addBookModal.close();
});


// Add book gamit axios
const addBookForm = document.getElementById('add-book-form');

const bookSuccess = document.getElementById('book-success');
const bookFailed = document.getElementById('book-failed');

addBookForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  // Clear previous validation errors
  const errorFields = ['title', 'author', 'category', 'year', 'quantity'];

  errorFields.forEach((field) => {
    document.getElementById(`${field}-error`).textContent = '';
  });

  // Clear previous success and error messages
  bookSuccess.textContent = '';
  bookSuccess.hidden = true;

  const bookData = {
    title: document.getElementById('title').value,
    author: document.getElementById('author').value,
    category: document.getElementById('category').value,
    publication_year: document.getElementById('year').value,
    quantity: document.getElementById('quantity').value
  };

  try {
    //pass yung object as post
    const response = await api.post('../api/books/create.php', bookData);

    // Display success message
    bookSuccess.textContent = response.data.message;
    bookSuccess.hidden = false;

    addBookModal.close();
    addBookForm.reset();
    //Go back to page 1 after adding
    currentPage = 1;
    loadBooks();

    // Hide error after successful add ng book
    bookFailed.textContent = "";
    bookFailed.hidden = true;
  } catch (error) {
    if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors;

      // Display validation errors
      Object.keys(errors).forEach((field) => {
        const errorId = field === 'publication_year'
          ? 'year-error'
          : `${field}-error`;

        const errorElement = document.getElementById(errorId);

        if (errorElement) {
          errorElement.textContent = `*${errors[field]}`;
        }
      });
    } else {
      bookFailed.textContent = error.response?.data?.message || "An error occurred while adding the book.";
      bookFailed.hidden = false;
      console.error('Failed to add book:', error);
    }
  } 
});

// Edit book modal
const editBookModal = document.getElementById('edit-book-modal');
const closeEditBookModalBtn = document.getElementById('close-edit-book-modal-btn');

const editId = document.getElementById('edit-id');
const editTitle = document.getElementById('edit-title');
const editAuthor = document.getElementById('edit-author');
const editCategory = document.getElementById('edit-category');
const editYear = document.getElementById('edit-year');
const editQuantity = document.getElementById('edit-quantity');

// Edit book form using axios
const editBookForm = document.getElementById('edit-book-form');

editBookForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  const errorFields = [
    'edit-title-error',
    'edit-author-error',
    'edit-category-error',
    'edit-year-error',
    'edit-quantity-error'
  ];

errorFields.forEach((field) => {
  document.getElementById(field).textContent = '';
});

  const bookData = {
    id: editId.value,
    title: editTitle.value,
    author: editAuthor.value,
    category: editCategory.value,
    publication_year: editYear.value,
    quantity: editQuantity.value
  };

  bookSuccess.textContent = "";
  bookSuccess.hidden = true;

  try {
    const response = await api.post('../api/books/update.php', bookData);

    bookSuccess.textContent = response.data.message;
    bookSuccess.hidden = false;

    editBookModal.close();  
    editBookForm.reset();

    currentPage = 1;
    loadBooks();

    //Hide errors pag successful edit
    bookFailed.textContent = "";
    bookFailed.hidden = true;
  } catch (error) {
    if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors;

      document.getElementById('edit-title-error').textContent =
        errors.title ? `*${errors.title}` : '';

      document.getElementById('edit-author-error').textContent =
        errors.author ? `*${errors.author}` : '';

      document.getElementById('edit-category-error').textContent =
        errors.category ? `*${errors.category}` : '';

      document.getElementById('edit-year-error').textContent =
        errors.publication_year ? `*${errors.publication_year}` : '';

      document.getElementById('edit-quantity-error').textContent =
        errors.quantity ? `*${errors.quantity}` : '';
    } else {
      bookFailed.textContent = error.response?.data?.message || "An error occurred while updating the book.";
      bookFailed.hidden = false;
      console.error('Failed to update book:', error);
    }
  }
});

// Event listener sa books table body
const booksTableBody = document.getElementById('books-table-body');

booksTableBody.addEventListener('click', async (event) => {
  // Edit button
  const editButton = event.target.closest('.open-edit-book-modal-btn');

  if (editButton) {
    editId.value = editButton.dataset.id;
    editTitle.value = editButton.dataset.title;
    editAuthor.value = editButton.dataset.author;
    editCategory.value = editButton.dataset.category;
    editYear.value = editButton.dataset.publication_year;
    editQuantity.value = editButton.dataset.quantity;

    const errorFields = [
      'edit-title-error',
      'edit-author-error',
      'edit-category-error',
      'edit-year-error',
      'edit-quantity-error'
    ];

    errorFields.forEach((field) => {
      document.getElementById(field).textContent = '';
    });

    editBookModal.showModal();
    return;
  }

  // Delete button
  const deleteButton = event.target.closest('.delete-book-btn');

  if (!deleteButton) {
    return;
  }

  const id = deleteButton.dataset.id;

  try {
    const response = await api.post('../api/books/delete.php', { id: id });

    bookSuccess.textContent = response.data.message;
    bookSuccess.hidden = false;

    bookFailed.textContent = "";
    bookFailed.hidden = true;

    loadBooks();
  } catch (error) {
    bookSuccess.textContent = "";
    bookSuccess.hidden = true;

    bookFailed.textContent = error.response?.data?.message || 'An error occurred while deleting the book.';
    bookFailed.hidden = false;

    console.error('Failed to delete book:', error);
  }
});

booksTableBody.addEventListener('click', (event) => {
  const cell = event.target.closest('.truncate.is-truncated, .truncate.expanded');

  if (cell) {
    cell.classList.toggle('expanded');
  }
});

closeEditBookModalBtn.addEventListener('click', () => {
  editBookModal.close();
});

// Search, category filter, and availability sorting sa bookspage and loading books
const bookSearch = document.getElementById('book-search');
const bookCategory = document.getElementById('book-category');
const bookSort = document.getElementById('book-sort');

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

const entriesSection = document.querySelector('.entries');
const booksTable = document.querySelector('.table-section table');
const emptyState = document.createElement('div');

emptyState.className = 'no-books';
emptyState.innerHTML = `<img src="../assets/images/NoBooks.svg" alt="No books"> <p>No books found.</p>`;

async function loadBooks() {
  const search = bookSearch.value;
  const category = bookCategory.value;
  const sort = bookSort.value;

  try {
    const response = await api.get("../api/books/index.php", {
      params: {
        title: search,
        category: category,
        sort: sort,
        page: currentPage
      }
    });
    const totalBooks = response.data.total;

    //check kung may books or wala, if wala, show no books message
    if (totalBooks === 0) {
      booksTable.replaceWith(emptyState);
    } else {
      emptyState.replaceWith(booksTable);
    }

    entriesSection.hidden = totalBooks === 0;

    totalPages = Math.ceil(totalBooks / 6);

    if (totalPages > 0 && currentPage > totalPages) {
      currentPage = totalPages;
      loadBooks();
      return;
    }

    if (totalPages === 0) {
      currentPage = 1;
    }

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
        <td>B${String(book.id).padStart(3, '0')}</td>
        <td class="truncate" title="${escapeHtml(book.title)}">${escapeHtml(book.title)}</td>
        <td class="truncate" title="${escapeHtml(book.author)}">${escapeHtml(book.author)}</td>
        <td>${escapeHtml(book.category)}</td>
        <td>${book.publication_year}</td>
        <td>${book.quantity}</td>
        <td>${book.available_quantity}</td>
        <td>
          <button
            type="button"
            class="open-edit-book-modal-btn"
            data-id="${book.id}"
            data-title="${escapeHtml(book.title)}"
            data-author="${escapeHtml(book.author)}"
            data-category="${escapeHtml(book.category)}"
            data-publication_year="${book.publication_year}"
            data-quantity="${book.quantity}">

            <img src="../assets/images/Edit.svg" alt="Edit">
          </button>

          <button
            type="button"
            class="delete-book-btn"
            data-id="${book.id}">
            <img src="../assets/images/Delete.svg" alt="delete">
          </button>
        </td>
      </tr>
    `;
  });

  markTruncatedCells();
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

loadBooks();
