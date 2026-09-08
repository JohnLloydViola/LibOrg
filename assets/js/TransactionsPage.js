const borrowBookModal = document.getElementById('borrow-book-modal');
const OpenBorrowBookModalBtn = document.getElementById('open-borrow-book-modal-btn');
const closeBorrowBookModalBtn = document.getElementById('close-borrow-book-modal-btn');

OpenBorrowBookModalBtn.addEventListener('click', () => {
  borrowBookModal.showModal();
});

closeBorrowBookModalBtn.addEventListener('click', () => {
  borrowBookModal.close();
});