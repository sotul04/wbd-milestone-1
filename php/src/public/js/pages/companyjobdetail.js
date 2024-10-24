var quill = new Quill('#job-description-editor', {
    theme: 'snow',
    readOnly: true,
    modules: {
        toolbar: false
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const statusIndicator = document.getElementById('status-indicator');
    const toggleButton = document.getElementById('toggle-button');
    const deleteButton = document.getElementById('delete-button');
    const jobId = toggleButton.getAttribute('data-job-id');

    toggleButton.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", `http://localhost:8000/company/toggle-job`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const requestBody = JSON.stringify({
            jobId: jobId
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    const isOpen = statusIndicator.classList.contains('open');

                    if (isOpen) {
                        statusIndicator.classList.remove('open');
                        statusIndicator.classList.add('close');
                        statusIndicator.innerHTML = `<p><strong>Closed</strong></p>`;
                        toggleButton.textContent = 'Open';
                        toggleButton.classList.remove('close');
                        toggleButton.classList.add('open');
                    } else {
                        statusIndicator.classList.remove('close');
                        statusIndicator.classList.add('open');
                        statusIndicator.innerHTML = `<p><strong>Open</strong></p>`;
                        toggleButton.textContent = 'Close';
                        toggleButton.classList.remove('open');
                        toggleButton.classList.add('close');
                    }

                    createToast("Data is successfully updated!", 'success');
                } else {
                    createToast("Something went wrong during update!", 'error');
                }
            } else {
                createToast("Request failed!", 'error');
            }
        };

        xhr.send(requestBody);
    });

    function deleteJob() {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `http://localhost:8000/company/job-delete`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const requestBody = JSON.stringify({
            jobId: jobId
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(response);
                if (response.status === 'success') {
                    window.location.href = "http://localhost:8000/home";
                } else {
                    createToast(response.data, 'error');
                }
            } else {
                createToast("Request failed!", 'error');
            }
        };

        xhr.send(requestBody);
    }

    deleteButton.addEventListener('click', () => {
        showModal('Job Deletion', 'Are you sure that you want to delete this job. All application data would be irreversibly lost!', deleteJob);
    });

    const csvbutton = document.getElementById('export-csv');
    if (csvbutton) {
        csvbutton.addEventListener('click', () => {
            downloadApplications(jobId, 'csv');
        });
    }

    const excelbutton = document.getElementById('export-excel');
    if (excelbutton) {
        excelbutton.addEventListener('click', () => {
            downloadApplications(jobId, 'excel');
        });
    }
});

function downloadApplications(jobId, format) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `http://localhost:8000/export/export?jobid=${jobId}`, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            if (result.status === 'success') {
                if (format === 'csv') {
                    const csvData = convertToCSV(result.data);
                    downloadFile('applications.csv', csvData, 'text/csv');
                } else if (format === 'excel') {
                    downloadExcel(result.data);
                }
            } else {
                createToas('Error: failed to get data', 'error');
            }
        } else {
            createToas('Error: Request Failed', 'error');
        }
    };

    xhr.onerror = function () {
        console.error('Request error');
    };

    xhr.send();
}

function convertToCSV(data) {
    const header = ['Applicant Name', 'Job Position', 'Application Date', 'CV URL', 'Video URL', 'Application Status'];
    const rows = data.map(application => [
        application.applicant_name,
        application.job_position,
        application.application_date,
        `http://localhost:8000/storage/files/cv/${application.cv_url}`,
        `${application.video_url ? 'http://localhost:8000/storage/files/videos/'+application.video_url : application.video_url}`,
        application.application_status
    ]);

    return [header, ...rows].map(row => row.join(',')).join('\n');
}

function downloadFile(filename, data, mimeType) {
    const blob = new Blob([data], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function downloadExcel(data) {
    const parsedData = data.map(item => {
        return {
            ...item,
            cv_url: `http://localhost:8000/storage/files/cv/${item.cv_url}`,
            video_url: item.video_url ? 'http://localhost:8000/storage/files/videos/'+item.video_url : item.video_url
        }
    })
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.json_to_sheet(parsedData);
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Applications');

    XLSX.writeFile(workbook, 'applications.xlsx');
}