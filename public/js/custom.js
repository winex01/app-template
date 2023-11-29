document.addEventListener('turbo:load', () => {
    let toggleCheckboxesButtons = document.querySelectorAll('#bulkButton');
    let isChecked = false;

    toggleCheckboxesButtons.forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault(); // Prevent the default action of the button or link
            
            if (!isChecked) {
                checkAllCheckboxes();
                isChecked = true;
            } else {
                uncheckAllCheckboxes();
                isChecked = false;
            }
        });
    });
});

function checkAllCheckboxes() {
    let checkboxes = document.querySelectorAll('.form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true; // Check all checkboxes
    });
    console.log(`All checkboxes checked.`);
}

function uncheckAllCheckboxes() {
    let checkboxes = document.querySelectorAll('.form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false; // Uncheck all checkboxes
    });
    console.log(`All checkboxes unchecked.`);
}
