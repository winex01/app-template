// Function to handle checkbox retrieval and set cookie
function handleExportButtonClick() {
  let uncheckedCheckboxes = document.querySelectorAll('.dropdown-column-menu input[type="checkbox"]:not(:checked)');
  
  let uncheckedIds = [];
  uncheckedCheckboxes.forEach(function(checkbox) {
    uncheckedIds.push(checkbox.getAttribute('id'));
  });

  if (uncheckedIds.length > 0) {
    const cookieValue = JSON.stringify(uncheckedIds);
    document.cookie = `excludeColumns=${cookieValue}; path=/`;
    console.log('Stored in excludeColumns cookie:', uncheckedIds);
  }
}

// Function to retrieve array of unchecked IDs from the cookie
function getUncheckedIdsFromCookie() {
  const cookies = document.cookie.split(';').map(cookie => cookie.trim());
  const excludeColumnsCookie = cookies.find(cookie => cookie.startsWith('excludeColumns='));

  if (excludeColumnsCookie) {
    const cookieValue = excludeColumnsCookie.split('=')[1];
    return JSON.parse(cookieValue);
  } else {
    return [];
  }
}

// Initial setup on page load
document.addEventListener('turbo:load', function() {
  // Attach click event to the button after Turbo navigation
  let exportButton = document.getElementById('btn-export');
  if (exportButton) {
    exportButton.addEventListener('click', handleExportButtonClick);
  }

  // Log the retrieved array of unchecked IDs
  const uncheckedIds = getUncheckedIdsFromCookie();
  console.log('Unchecked IDs from cookie:', uncheckedIds);
});

// Execute the initial setup when the page is first loaded
document.addEventListener('DOMContentLoaded', function() {
  let exportButton = document.getElementById('btn-export');
  if (exportButton) {
    exportButton.addEventListener('click', handleExportButtonClick);
  }
});
