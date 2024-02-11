<script>
    function updateTaskStatus(checkbox, taskId) {
        const status = checkbox.checked ? 1 : 0;
        
        // Send an AJAX request to update the task status
        // You can use a library like jQuery or fetch for this purpose
        // Example using fetch:
        fetch("update_status.php", {
            method: "POST",
            body: JSON.stringify({ taskId, status }),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Status updated successfully.");
            } else {
                console.error("Status update failed.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
</script>
