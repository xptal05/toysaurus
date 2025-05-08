console.log('test')

function productScript() {
    console.log('script called')
    uploadMedia()
    importProductCSV()
}

function uploadMedia() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('media');
    const fileList = document.getElementById('fileList');

    // Prevent default drag-and-drop behavior on the whole page
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        document.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });


    // When the drop zone is clicked, trigger the file input click event
    dropZone.addEventListener('click', () => fileInput.click());

    // Prevent default behavior and add dragover styles
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        console.debug("🟡 dragover on dropZone");
        dropZone.classList.add('dragover');
    });


    // Remove dragover styles when file leaves the drop zone
    dropZone.addEventListener('dragleave', () => {
        console.debug("🔵 dragleave from dropZone");

        dropZone.classList.remove('dragover');
    });

    // Handle file drop
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        console.debug("🟢 drop on dropZone");

        dropZone.classList.remove('dragover');

        const files = e.dataTransfer.files;
        console.debug("📦 Files dropped:", files);

        fileInput.files = files;  // Set files to the file input element

        showFileList(files);
    });

    // Show the list of selected files
    fileInput.addEventListener('change', () => {
        showFileList(fileInput.files);
    });

    function showFileList(files) {
        fileList.innerHTML = '';  // Clear previous file list
        Array.from(files).forEach((file) => {
            const li = document.createElement('li');
            li.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            fileList.appendChild(li);
        });
    }

    // Form submission handler using AJAX
    document.getElementById('mediaUploadForm').addEventListener('submit', function (e) {
        e.preventDefault();  // Prevent the form from submitting traditionally

        const form = e.target;
        const formData = new FormData(form);

        // Call the AJAX function to handle the upload
        submitMediaForm(formData);

    });

    // Submit the form data via AJAX
    async function submitMediaForm(formData) {
        formData.append('action', 'uploadMedia');
        formData.append('type', 'file')
            // Verify the formData by logging each entry
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

        const addResponse = await sendAjaxRequest(baseDir+"/config/api.php", 'POST', formData);

        // Handle response here (success/failure)
        if (addResponse.success) {
            alert('Files uploaded successfully!');
        } else {
            alert('Failed to upload files: ' + addResponse.message);
        }
    }

}


function importProductCSV() {
    const importProductsBtn = document.getElementById('import-products-btn');
    const fileInput = document.getElementById('csv-file');
  
    importProductsBtn.addEventListener('click', (e) => {
      e.preventDefault();
      console.log(fileInput)
  
      const file = fileInput.files[0];
      if (!file) {
        alert("Please select a CSV file first.");
        return;
      }
  
      const reader = new FileReader();
      reader.onload = function (event) {
        const csvText = event.target.result;
        const result = parseCSV(csvText);
        console.log(result);
        // You can now use this parsed data to render a table or import products
      };
  
      reader.readAsText(file);
    });
  }


function parseCSV(csvString) {
    const lines = csvString.trim().split("\n");
    const headers = lines[0].split(",");

    return lines.slice(1).map(line => {
        const values = line.split(",");
        return Object.fromEntries(headers.map((header, i) => [header.trim(), values[i]?.trim()]));
    });
}



const productSectionBtn = document.querySelector('.productSectionBtn')

productSectionBtn.addEventListener('click', (e) => productScript())
productScript()