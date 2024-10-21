document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("jobApplicationForm");
    const submitBtn = document.getElementById("submitBtn");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData();
        const cvFile = document.getElementById("cv").files[0];
        const videoFile = document.getElementById("video").files[0];

        if (videoFile && videoFile.type !== "video/mp4") {
            createToast('Unsuppported video format', 'error');
            return;
        }

        formData.append("cv", cvFile);
        if (videoFile) {
            formData.append("video", videoFile);
        }

        const jobID = window.location.pathname.split("/")[2];

        const xhr = new XMLHttpRequest();
        xhr.open("POST", `http://localhost:8000/job/${jobID}/application`, true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        createToast(`Application sent`, 'success');
                        submitBtn.disabled = true;
                        submitBtn.textContent = "Applied";
                    } else {
                        createToast(`${response.data || "Something went wrong."}`, 'error');
                        submitBtn.disabled = false;
                        submitBtn.textContent = "Apply";
                    }
                } else {
                    const error = JSON.parse(xhr.responseText);
                    createToast(`${error.message || "Something went wrong."}`, 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = "Apply";
                }
            }
        };

        xhr.onerror = function () {
            createToast(`Something went wrong`, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = "Apply";
        };

        submitBtn.disabled = true;
        submitBtn.textContent = "Sending...";
        xhr.send(formData);
    });
});
