// Initialize Quill editor for job description
var quill = new Quill('#editor', {
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

// Handle form submission for updating job
document.getElementById('editJobForm').addEventListener('submit', function (event) {
    event.preventDefault();  // Prevent default form submission
    console.log("Save button clicked!");

    // Get necessary elements
    const jobID = document.getElementById('save-button').getAttribute('job-id');  // Retrieve job ID from the button
    const positionInput = document.getElementById('position');
    const jenisPekerjaanInput = document.getElementById('jenisPekerjaan');
    const lokasiInput = document.getElementById('lokasi');
    const attachmentsInput = document.getElementById('attachments');  // File input for attachments

    const descriptionError = document.getElementById('description-error');
    const nameError = document.getElementById('position-error');

    // Get the description content from Quill editor
    const description = quill.root.innerHTML;

    // Validate position input
    if (positionInput.value.trim().length === 0) {
        nameError.classList.add('show-error');  
        return;  
    } else {
        nameError.classList.remove('show-error');  
    }

    // Validate description input
    if (quill.getText().trim().length === 0) {
        descriptionError.classList.add('show-error');  
        return;  
    } else {
        descriptionError.classList.remove('show-error');  
    }

    // Prepare FormData to handle both text and file data
    const formData = new FormData();
    formData.append('jobID', jobID);  // Append job ID
    formData.append('posisi', positionInput.value);  // Append position
    formData.append('deskripsi', description);  // Append description from Quill editor
    formData.append('jenisPekerjaan', jenisPekerjaanInput.value);  // Append job type
    formData.append('jenisLokasi', lokasiInput.value);  // Append job location

    // Append attachments (if any)
    if (attachmentsInput.files.length > 0) {
        for (let i = 0; i < attachmentsInput.files.length; i++) {
            formData.append('attachments[]', attachmentsInput.files[i]);  // Add each file to FormData
        }
    }

    // Create an AJAX request using the PUT method to update the job
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost:8000/company/update-job', true);  // Assuming the endpoint for updating jobs
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                // Redirect to the job detail page after successful update
                window.location.href = `http://localhost:8000/company/job/${response.data}`;
                createToast("Job updated successfully!", 'success');
            } else {
                createToast("Failed to update job: " + response.data, 'error');
            }
        } else {
            createToast("Failed to send request.", 'error');
        }
    };

    xhr.onerror = function () {
        createToast("Network error occurred.", 'error');
    };

    // Send the FormData with the POST request
    xhr.send(formData);
});
