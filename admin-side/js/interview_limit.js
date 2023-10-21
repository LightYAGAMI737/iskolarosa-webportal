    const limitInputCount = document.getElementById('limit');
    const recordCountElement = document.getElementById('record-count');
    
    limitInputCount.addEventListener('input', function () {
        const userInput = limitInputCount.value.trim();
        let sanitizedInput = userInput.replace(/^0+|(\..*)\./gm, '$0');
        
        if (sanitizedInput === '' || isNaN(sanitizedInput)) {
            limitInputCount.value = '';
        } else {
            const parsedInput = parseInt(sanitizedInput);
            
            // Ensure the value is within the valid range
            const validValue = Math.min(Math.max(parsedInput, 1), 200);
            
            limitInputCount.value = validValue;
        }
    });

    // Function to fetch and display the record count
    function fetchRecordCount() {
        // Make an AJAX request to count_records.php
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const count = xhr.responseText;
                recordCountElement.textContent = `Total count: ${count}`;
            }
        };
        xhr.open('GET', '../../../php/count_records.php', true);
        xhr.send();
    }

    // Call fetchRecordCount to load the count initially
    fetchRecordCount();