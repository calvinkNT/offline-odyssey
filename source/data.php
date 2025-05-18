<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta charset="utf-8">
<link rel="icon" type="image/x-icon" href="../../../../../favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap.css" rel="stylesheet">
    <link href="https://jscdn.ct.ws/../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

    <div class="container">

      <div class="masthead">
      
<?php require_once('tb.php'); ?>
      </div>


<style>
    .center-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>


    <div class="container">
    <p>I recommend to not delete anything without downloading first. I encourage you to back up your data every now and then, just in case this site gets blocked.</p>
        <!-- File Upload Section -->
        <div class="well">
            <h2>Upload <u>.save</u> Files</h2>
            <form id="uploadForm" class="form-inline">
                <div class="form-group">
                    <input type="file" id="fileInput" class="input-large" accept=".save" />
                </div>
                <button type="submit" class="btn btn-primary" style="display:none;">Upload</button>
            </form>
        </div>

        <!-- Local Storage Section -->
        <div class="well" style="margin-top: 20px;">
            <h2>Manage Files</h2>
            <ul id="fileList" class="unstyled"></ul>
        </div>

    </div>

    <script>
        const fileInput = document.getElementById("fileInput");
        const fileList = document.getElementById("fileList");

        // Create file list item with download and delete buttons
        function createFileListItem(key) {
            const listItem = document.createElement("li");
            listItem.classList.add("clearfix");

            const fileName = document.createTextNode(key);
            listItem.appendChild(fileName);

            // Create a Download Button
            const downloadBtn = document.createElement("button");
            downloadBtn.classList.add("btn", "btn-success", "btn-xs", "ml-2", "pull-right");
            downloadBtn.textContent = "Download";
            downloadBtn.addEventListener("click", function() {
                downloadFile(key);
            });
            listItem.appendChild(downloadBtn);

            // Create a Delete Button
            const deleteBtn = document.createElement("button");
            deleteBtn.classList.add("btn", "btn-danger", "btn-xs", "ml-2", "pull-right");
            deleteBtn.textContent = "Delete";
            deleteBtn.addEventListener("click", function() {
                deleteFile(key, listItem);
            });
            listItem.appendChild(deleteBtn);

            return listItem;
        }

        // Function to delete file
        function deleteFile(key, listItem) {
            if (confirm(`Are you sure you want to delete ${key}?`)) {
                localStorage.removeItem(key);
                listItem.remove();
            }
        }

        // Function to download file
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

                // Revoke the object URL after download
                URL.revokeObjectURL(url);
            }
        }

        // Display files in localStorage
        function displayLocalStorage() {
            fileList.innerHTML = "";
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                fileList.appendChild(createFileListItem(key));
            }
        }

        // Handle file upload
        fileInput.addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file && file.name.endsWith(".save")) {  // Only accept ".save" files
                const reader = new FileReader();
                reader.onload = function (e) {
                    const fileNameWithoutExtension = file.name.slice(0, -5); // Remove ".save" extension
                    localStorage.setItem(fileNameWithoutExtension, e.target.result);
                    displayLocalStorage();
                };
                reader.readAsText(file);
            } else {
                alert("Please upload only files with the .save extension.");
            }
        });

        // Prevent the form from submitting and handle file upload instead
        document.getElementById("uploadForm").addEventListener("submit", function(event) {
            event.preventDefault();  // Prevent form submission
        });

        // Initial display of files
        displayLocalStorage();
    </script>
    </div> <!-- /container -->


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
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

    <script>
    document.getElementById('bootstrapForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the usual way
    });

    $(document).ready(function () {
        $('#bootstrapForm').submit(function (event) {
            event.preventDefault();
            alert('Successfully submitted.');
            var extraData = {}
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


