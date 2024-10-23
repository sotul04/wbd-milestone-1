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

document.getElementById('form-company').addEventListener('submit', (event) => {
    event.preventDefault();
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
                } else {
                    createToast(response.data, 'error');
                }
            } else {
                createToast('Request failed', 'error');
            }
        }
    }

    xhr.send(requestBody);

});