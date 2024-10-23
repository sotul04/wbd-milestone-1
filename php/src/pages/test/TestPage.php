<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<section>
    <h2>Test your Code here</h2>
    <button
        onclick="showModal('Delete File', 'Are you sure you want to delete this file?', () => { alert('File deleted!'); })">Show
        Modal</button>
    <button onclick="createToast('This is the success toast', 'success');">Show Toast</button>
</section>

<button onclick="downloadApplications('7a6ae491-22b5-46a7-b458-85dd467d5051', 'csv')">Download as CSV</button>
<button onclick="downloadApplications('7a6ae491-22b5-46a7-b458-85dd467d5051', 'excel')">Download as Excel</button>

<script>
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
                    console.error('Error fetching data:', result.message);
                }
            } else {
                console.error('Request failed with status:', xhr.status);
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
            application.cv_url,
            application.video_url,
            application.application_status
        ]);

        // Join header and rows into a CSV string
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
        URL.revokeObjectURL(url); // Clean up the URL object
    }

    function downloadExcel(data) {
        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.json_to_sheet(data);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Applications');

        // Generate Excel file and trigger download
        XLSX.writeFile(workbook, 'applications.xlsx');
    }
</script>

<?php require_once __DIR__ . "/../template/footer.php" ?>