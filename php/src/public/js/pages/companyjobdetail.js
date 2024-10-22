document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById('toggle-button');
    const deleteButton = document.getElementById('delete-button');

    toggleButton.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", 'http://localhost:8000/company/togglejob', true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onload = function () {
            if(xhr.status === 200){
                const response = JSON.parse(xhr.responseText);

                if(response === 'Unauthorized action!' || response === 'Unlawful access' || response === 'Something went wrong!'){
                    //To-Do: Show error dialog
                }else{
                    //To-Do: Show success dialog
                }
            }
        }
    })
});