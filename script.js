let currentPageIndex = 0;

function showPage(index) {
    const pages = document.querySelectorAll('.page');
    const totalPages = pages.length;

    if (index >= 0 && index < totalPages) {
        pages.forEach(page => page.classList.remove('active'));
        pages[index].classList.add('active');
        currentPageIndex = index;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const nextButtons = document.querySelectorAll('.next-button');
    const prevButtons = document.querySelectorAll('.prev-button');

    // Assign event listeners to "Next" buttons
    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            showPage(currentPageIndex + 1);
        });
    });

    // Assign event listeners to "Previous" buttons
    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            showPage(currentPageIndex - 1);
        });
    });

    // Initially show the first page
    showPage(currentPageIndex);
});
