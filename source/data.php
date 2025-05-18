<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Disable caching -->
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    
    <meta charset="utf-8">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../../../../favicon.ico">

    <!-- Mobile responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap stylesheets -->
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim for IE < 9 -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- iOS Touch Icons and Favicon -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>
    <div class="container">
      <!-- Navigation/header loaded from tb.php -->
      <div class="masthead">
        <?php require_once('tb.php'); ?>
      </div>

      <!-- Center alignment style -->
      <style>
        .center-container {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
        }
      </style>

      <div class="container">
        <!-- User guidance paragraph -->
        <p>I recommend to not delete anything without downloading first. I encourage you to back up your data every now and then, just in case this site gets blocked.</p>
        
        <!-- Upload Section -->
        <div class="well">
          <h2>Upload <u>.save</u> Files</h2>
          <form id="uploadForm" class="form-inline">
            <div class="form-group">
              <!-- File input restricted to .save files -->
              <input type="file" id="fileInput" class="input-large" accept=".save" />
            </div>
            <!-- Hidden submit button (upload handled by JS) -->
            <button type="submit" class="btn btn-primary" style="display:none;">Upload</button>
          </form>
        </div>

        <!-- Local Storage File Management Section -->
        <div class="well" style="margin-top: 20px;">
          <h2>Manage Files</h2>
          <!-- List of uploaded files will be displayed here -->
          <ul id="fileList" class="unstyled"></ul>
        </div>
      </div>

      <script>
        const fileInput = document.getElementById("fileInput");
        const fileList = document.getElementById("fileList");

        // Create a list item for each file with Download and Delete buttons
        function createFileListItem(key) {
          const listItem = document.createElement("li");
          listItem.classList.add("clearfix");

          const fileName = document.createTextNode(key);
          listItem.appendChild(fileName);

          // Download Button
          const downloadBtn = document.createElement("button");
          downloadBtn.classList.add("btn", "btn-success", "btn-xs", "ml-2", "pull-right");
          downloadBtn.textContent = "Download";
          downloadBtn.addEventListener("click", function () {
            downloadFile(key);
          });
          listItem.appendChild(downloadBtn);

          // Delete Button
          const deleteBtn = document.createElement("button");
          deleteBtn.classList.add("btn", "btn-danger", "btn-xs", "ml-2", "pull-right");
          deleteBtn.textContent = "Delete";
          deleteBtn.addEventListener("click", function () {
            deleteFile(key, listItem);
          });
          listItem.appendChild(deleteBtn);

          return listItem;
        }

        // Remove file from localStorage and the DOM
        function deleteFile(key, listItem) {
          if (confirm(`Are you sure you want to delete ${key}?`)) {
            localStorage.removeItem(key);
            listItem.remove();
          }
        }

        // Download file from localStorage as a .save file
        function downloadFile(key) {
          const fileContent = localStorage.getItem(key);
          if (fileContent) {
            const blob = new Blob([fileContent], { type: "text/plain" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = key + ".save";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url); // Clean up memory
          }
        }

        // Display all saved files on page load or update
        function displayLocalStorage() {
          fileList.innerHTML = "";
          for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            fileList.appendChild(createFileListItem(key));
          }
        }

        // Upload handler: reads .save file and stores it in localStorage
        fileInput.addEventListener("change", function (event) {
          const file = event.target.files[0];
          if (file && file.name.endsWith(".save")) {
            const reader = new FileReader();
            reader.onload = function (e) {
              const fileNameWithoutExtension = file.name.slice(0, -5); // Strip ".save"
              localStorage.setItem(fileNameWithoutExtension, e.target.result);
              displayLocalStorage(); // Refresh display
            };
            reader.readAsText(file);
          } else {
            alert("Please upload only files with the .save extension.");
          }
        });

        // Prevent form submission (handled by JS instead)
        document.getElementById("uploadForm").addEventListener("submit", function (event) {
          event.preventDefault();
        });

        // Load stored files on page load
        displayLocalStorage();
      </script>
    </div> <!-- /container -->

    <!-- Bootstrap JS and jQuery (loaded at the end for performance) -->
    <script src="https://jscdn.ct.ws/../assets/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-transition.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-alert.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-modal.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-dropdown.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-scrollspy.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-tab.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-tooltip.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-popover.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-button.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-collapse.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-carousel.js"></script>
    <script src="https://jscdn.ct.ws/../assets/js/bootstrap-typeahead.js"></script>

    <!-- Example of form submission with jQuery Form plugin -->
    <script>
      document.getElementById('bootstrapForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form behavior
      });

      $(document).ready(function () {
        $('#bootstrapForm').submit(function (event) {
          event.preventDefault();
          alert('Successfully submitted.');
          var extraData = {};
          $('#bootstrapForm').ajaxSubmit({
            data: extraData,
            dataType: 'jsonp',
            error: function () {
              alert('Successfully submitted.');
            }
          });
        });
      });
    </script>
  </body>
</html>
