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

// Edit book modal
const editBookModal = document.getElementById('edit-book-modal');
const closeEditBookModalBtn = document.getElementById('close-edit-book-modal-btn');

const editId = document.getElementById('edit-id');
const editTitle = document.getElementById('edit-title');
const editAuthor = document.getElementById('edit-author');
const editCategory = document.getElementById('edit-category');
const editYear = document.getElementById('edit-year');
const editQuantity = document.getElementById('edit-quantity');

// Event listener sa books table body
const booksTableBody = document.getElementById('books-table-body');

booksTableBody.addEventListener('click', (event) => {
  const button = event.target.closest('.open-edit-book-modal-btn');

  if (!button) {
    return;
  }

  editId.value = button.dataset.id;
  editTitle.value = button.dataset.title;
  editAuthor.value = button.dataset.author;
  editCategory.value = button.dataset.category;
  editYear.value = button.dataset.publication_year;
  editQuantity.value = button.dataset.quantity;

  editBookModal.showModal();
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

async function loadBooks() {
  const search = bookSearch.value;
  const category = bookCategory.value;
  const sort = bookSort.value;

  try {
    const response = await axios.get(
      `../actions/book/BookSearch.php?title=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}&sort=${encodeURIComponent(sort)}&page=${encodeURIComponent(currentPage)}`);

    const totalBooks = response.data.total;

    totalPages = Math.ceil(totalBooks / 6);

    // About sa showing or display of entries
    const startEntry = totalBooks === 0 ? 0 : (currentPage - 1) * 6 + 1;
    const endEntry = Math.min(currentPage * 6, totalBooks);
    bookEntries.textContent = `Showing ${startEntry} to ${endEntry} of ${totalBooks} entries`;

    renderPagination();

    renderBooks(response.data.books);
  } catch (error) {
    console.log('Error loading books');
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
        <td>${book.title}</td>
        <td>${book.author}</td>
        <td>${book.category}</td>
        <td>${book.publication_year}</td>
        <td>${book.quantity}</td>
        <td>${book.available_quantity}</td>
        <td>
          <button
            type="button"
            class="open-edit-book-modal-btn"
            data-id="${book.id}"
            data-title="${book.title}"
            data-author="${book.author}"
            data-category="${book.category}"
            data-publication_year="${book.publication_year}"
            data-quantity="${book.quantity}">

            <img src="../assets/images/Edit.png" alt="Edit">
          </button>

          <form action="../actions/book/BookDelete.php" method="POST">
            <input type="hidden" name="id" value="${book.id}">

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

loadBooks();
