// Initialize Quill editor
const quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Enter job description...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }]
        ]
    }
});

// Handle form submission
document.getElementById('save-button').addEventListener('click', function (event) {
    event.preventDefault();  // Prevent the form's default submission

    // Get the error message element
    const descriptionError = document.getElementById('description-error');
    const nameError = document.getElementById('position-error');
    const inputPosisi = document.getElementById('posisi');

    const description = quill.root.innerHTML;

    if (inputPosisi.value.trim().length === 0) {
        nameError.classList.add('show-error');  
        return;  
    } else {
        nameError.classList.remove('show-error');  
    }

    if (quill.getText().trim().length === 0) {
        descriptionError.classList.add('show-error');  
        return;  
    } else {
        descriptionError.classList.remove('show-error');  
    }


    console.log("Description:", description);

    const formData = new FormData(document.getElementById('addForm'));
    formData.append('description', description); 

    formData.delete('attachments[]');

    // Get file inputs
    const attachments = document.getElementById('attachments');
    if (attachments.files.length > 0) {
        for (let i = 0; i < attachments.files.length; i++) {
            formData.append('attachments[]', attachments.files[i]); // Append each file to FormData
        }
    }

    // Create an AJAX request to submit the form
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost:8000/company/jobCreate', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                window.location.href = `http://localhost:8000/company/job/${response.data}`;
            } else {
                createToast(response.data, 'error');
            }
        } else {
            createToast('Failed to create new job.', 'error');
        }
    };

    xhr.send(formData);
});
