function attachBulkButtonListener() {
    document.addEventListener('change', function(event) {
        if (event.target.matches('#bulkButton')) {
            handleBulkButtonClick(event.target.checked);
        } else {
            updateButtonClass();
        }
    });

    function handleBulkButtonClick(checked) {
        let checkboxes = document.querySelectorAll('.form-check-input');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });

        updateButtonClass();
    }
}

function updateButtonClass() {
    const anyChecked = Array.from(document.querySelectorAll('.form-check-input'))
        .some(checkbox => checkbox.checked);

    const button = document.querySelector('.btn-delete');
    if (anyChecked) {
        button.classList.add('btn-danger');
    } else {
        button.classList.remove('btn-danger');
    }
}

function addColumnHeaderCheckbox() {
    const tableRowsWithCheckboxes = document.querySelectorAll('.table tbody tr .form-check-input');
    if (tableRowsWithCheckboxes.length > 0) {
        const firstHeader = document.querySelector('.table thead tr th:first-child');
        const checkboxHTML = '<input class="form-check-input" type="checkbox" id="bulkButton">';
        firstHeader.innerHTML = checkboxHTML + firstHeader.innerHTML; // Prepend checkbox
        attachBulkButtonListener();
    }
}

// Load the column header checkbox on page load and Turbo load
document.addEventListener('DOMContentLoaded', addColumnHeaderCheckbox);
document.addEventListener('turbo:load', addColumnHeaderCheckbox);

// Update button class on initial load
document.addEventListener('DOMContentLoaded', updateButtonClass);
document.addEventListener('turbo:load', updateButtonClass);