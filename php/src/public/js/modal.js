// Function to show the modal with dynamic title, description, and action
function showModal(title, description, okAction) {
    // Get the modal container
    const modalContainer = document.getElementById('modalContainer');

    // Create modal overlay element
    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');

    // Create modal box element with dynamic content
    const modalBox = document.createElement('div');
    modalBox.classList.add('modal-box');

    modalBox.innerHTML = `
        <div class="modal-title">${title}</div>
        <div class="modal-description">${description}</div>
        <div class="modal-buttons">
            <button class="btn-ok">OK</button>
            <button class="btn-cancel">Cancel</button>
        </div>
    `;

    // Append modal box to modal overlay
    modalOverlay.appendChild(modalBox);

    // Append modal overlay to the modal container
    modalContainer.appendChild(modalOverlay);

    // Add show class for animations
    setTimeout(() => {
        modalOverlay.classList.add('show');
        modalBox.classList.add('show');
    }, 100);

    // Handle "OK" button click
    const okButton = modalBox.querySelector('.btn-ok');
    okButton.onclick = function () {
        okAction(); // Execute the provided action
        closeModal(modalOverlay); // Close the modal
    };

    // Handle "Cancel" button click
    const cancelButton = modalBox.querySelector('.btn-cancel');
    cancelButton.onclick = function () {
        closeModal(modalOverlay); // Just close the modal
    };

    // Disable background interaction
    document.body.style.pointerEvents = 'none';
}

// Function to close the modal
function closeModal(modalOverlay) {
    // Enable background interaction
    document.body.style.pointerEvents = '';

    modalOverlay.classList.remove('show');
    modalOverlay.querySelector('.modal-box').classList.remove('show');

    // Remove modal from DOM after transition ends
    modalOverlay.addEventListener('transitionend', () => {
        modalOverlay.remove();
    });
}
