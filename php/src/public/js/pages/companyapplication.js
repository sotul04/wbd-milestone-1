const quill = new Quill('#status-reason', {
    theme: 'snow',
    placeholder: 'Enter response for status...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }]
        ]
    }
});

const statusDropdown = document.getElementById('status');
const form = document.getElementById('form-company');

function updateDropdownColor() {
    statusDropdown.classList.remove('status-accepted', 'status-rejected', 'status-waiting');
    
    if (statusDropdown.value === 'accepted') {
        statusDropdown.classList.add('status-accepted');
    } else if (statusDropdown.value === 'rejected') {
        statusDropdown.classList.add('status-rejected');
    } else {
        statusDropdown.classList.add('status-waiting');
    }
}

updateDropdownColor();

function updateForm() {
    const applicantStatus = form.getAttribute('data-status');
    const submitButton = document.getElementById("submit-button");
    if (applicantStatus === 'accepted' || applicantStatus === 'rejected') {
        statusDropdown.disabled = true;
        quill.enable(false);
        submitButton.disabled = true;
        submitButton.classList.remove('btn-primary');
        submitButton.classList.add('btn-secondary');
    }
}

updateForm();

statusDropdown.addEventListener('change', () => {
    updateDropdownColor();
});

function submitForm(){
    const status = document.getElementById('status');
    let reason = quill.root.innerHTML;

    if (quill.getText().trim().length === 0) {
        reason = null;
    }

    const requestBody = JSON.stringify({
        status: status.value,
        status_reason: reason
    });

    const xhr = new XMLHttpRequest();
    xhr.open('PUT', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    createToast(response.data, 'success');
                    form.setAttribute('data-status', status.value);
                    updateForm();
                } else {
                    createToast(response.data, 'error');
                }
            } else {
                createToast('Request failed', 'error');
            }
        }
    }

    xhr.send(requestBody);
}

document.getElementById('form-company').addEventListener('submit', (event) => {
    event.preventDefault();
    
    showModal('Application Status', 'Please make sure all the data is set correctly. All changes would be irreversible!', submitForm);
});