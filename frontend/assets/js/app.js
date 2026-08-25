function scrollToSection(id) {
    const element = document.getElementById(id);

    if (element) {
        element.scrollIntoView({
            behavior: 'smooth'
        });
    }
}

function openModal(title, text) {
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const modal = document.getElementById('customModal');

    if (modalTitle && modalText && modal) {
        modalTitle.innerText = title;
        modalText.innerText = text;
        modal.style.display = 'flex';
    }
}

function closeModal() {
    const modal = document.getElementById('customModal');

    if (modal) {
        modal.style.display = 'none';
    }
}