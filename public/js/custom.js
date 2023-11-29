function attachBulkButtonListener() {
    let isChecked = false;

    document.addEventListener('change', function(event) {
        if (event.target.matches('#bulkButton')) {
            handleBulkButtonClick(event.target.checked);
        }
    });

    function handleBulkButtonClick(checked) {
        let checkboxes = document.querySelectorAll('.form-check-input');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });

        isChecked = checked;
        
        // check
        if (isChecked) {
            console.log(`All checkboxes checked.`);
        } else {
            console.log(`All checkboxes unchecked.`);
        }
    }
}

// preload
document.addEventListener('turbo:load', () => {
    const tableRowsWithCheckboxes = document.querySelectorAll('.table tbody tr .form-check-input');
    if (tableRowsWithCheckboxes.length > 0) {
        const firstHeader = document.querySelector('.table thead tr th:first-child');
        const checkboxHTML = '<input class="form-check-input" type="checkbox" id="bulkButton">';
        firstHeader.innerHTML = checkboxHTML + firstHeader.innerHTML; // Prepend checkbox
        attachBulkButtonListener();
    }
});

// if user tries to refresh the browser
document.addEventListener('DOMContentLoaded', () => {
    const tableRowsWithCheckboxes = document.querySelectorAll('.table tbody tr .form-check-input');
    if (tableRowsWithCheckboxes.length > 0) {
        const firstHeader = document.querySelector('.table thead tr th:first-child');
        const checkboxHTML = '<input class="form-check-input" type="checkbox" id="bulkButton">';
        firstHeader.innerHTML = checkboxHTML + firstHeader.innerHTML; // Prepend checkbox
        attachBulkButtonListener();
    }
});
