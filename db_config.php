<!-- function saveSortDetails() {
  if (!lastSortResult) {
    alert("No sort data to save! Please sort first.");
    return;
  }

  fetch("save_result.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(lastSortResult)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Sort details saved successfully!");
      document.getElementById("saveBtn").disabled = true; // disable again after save
    } else {
      alert("Failed to save sort details.");
    }
  })
  .catch(err => {
    console.error("Error:", err);
    alert("Error saving sort details.");
  });
} -->
