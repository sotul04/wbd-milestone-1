const description = document.getElementById('job-description-editor');
if (description) {
    new Quill(description, {
        theme: 'snow',
        readOnly: true,
        modules: {
            toolbar: false
        }
    });

}

const applicationResponse = document.getElementById('application-response');
if (applicationResponse) {
    new Quill(applicationResponse, {
        theme: 'snow',
        readOnly: true,
        modules: {
            toolbar: false
        }
    });
}