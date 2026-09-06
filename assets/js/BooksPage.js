const addBookModal = document.getElementById('add-book-modal');
const openAddBookModalBtn = document.getElementById('open-add-book-modal-btn');
const closeAddBookModalBtn = document.getElementById('close-add-book-modal-btn');

openAddBookModalBtn.addEventListener('click', ()=>{
  addBookModal.showModal();
});

closeAddBookModalBtn.addEventListener('click', ()=>{
  addBookModal.close();
})

const editBookModal = document.getElementById('edit-book-modal');
const openEditBookModalBtn = document.querySelectorAll('.open-edit-book-modal-btn');
const closeEditBookModalBtn = document.getElementById('close-edit-book-modal-btn');

const editId = document.getElementById('edit-id');
const editTitle = document.getElementById('edit-title');
const editAuthor = document.getElementById('edit-author');
const editCategory = document.getElementById('edit-category');
const editYear = document.getElementById('edit-year');
const editQuantity = document.getElementById('edit-quantity');

openEditBookModalBtn.forEach((button) => {
  button.addEventListener('click', () => {
    editId.value = button.dataset.id;
    editTitle.value = button.dataset.title;
    editAuthor.value = button.dataset.author;
    editCategory.value = button.dataset.category;
    editYear.value = button.dataset.publication_year;
    editQuantity.value = button.dataset.quantity;

    editBookModal.showModal();
  });
});

closeEditBookModalBtn.addEventListener('click', () => {
  editBookModal.close();
})