const addBookModal = document.getElementById('add-book-modal');
const openAddBookModalBtn = document.getElementById('open-add-book-modal-btn');
const closeAddBookModalBtn = document.getElementById('close-add-book-modal-btn');

openAddBookModalBtn.addEventListener('click', ()=>{
  addBookModal.showModal();
});

closeAddBookModalBtn.addEventListener('click', ()=>{
  addBookModal.close();
})