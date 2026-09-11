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

//Event listener sa books table body
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

// Search, category filter, and availability sorting sa bookspage
const bookSearch = document.getElementById('book-search');
const bookCategory = document.getElementById('book-category');
const bookSort = document.getElementById('book-sort');

async function loadBooks() {
  const search = bookSearch.value;
  const category = bookCategory.value;
  const sort = bookSort.value;

  try {
    const response = await axios.get(
      `../actions/book/BookSearch.php?title=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}&sort=${encodeURIComponent(sort)}`
    );

    renderBooks(response.data);

  } catch (error) {
    console.log('Error loading books');
  }
}

// Search listener event
bookSearch.addEventListener('input', () => {
  loadBooks();
});

// Category listener event
bookCategory.addEventListener('change', () => {
  loadBooks();
});

// Sort listener event
bookSort.addEventListener('change', () => {
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