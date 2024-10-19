// Function to create toast
function createToast(message, type = 'default') {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.classList.add('toast', type);
    toast.innerHTML = `
            <span>${message}</span>
            <button class="close-btn">&times;</button>
        `;


    setTimeout(() => {
        toast.classList.add('show');
    }, 100);


    setTimeout(() => {
        removeToast(toast);
    }, 10000);

    const closeButton = toast.querySelector('.close-btn');
    closeButton.addEventListener('click', () => removeToast(toast));

    toastContainer.appendChild(toast);
}

function removeToast(toast) {
    toast.classList.add('hide');
    toast.addEventListener('transitionend', () => {
        toast.classList.add('removing');
        toast.addEventListener('transitionend', () => {
            toast.remove();
        });
    });
}
