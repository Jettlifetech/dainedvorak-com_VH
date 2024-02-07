<!DOCTYPE html>
<html lang="en">
<head>

    <title>Linkscanner CLI Generator</title>
    <?php include './0-dynamic-content/header.html';?>
</head>
<body>
<?php include './0-dynamic-content/nav.html';?>



    <!-- Jumbotron -->
    <div class="jumbotron text-center">
        <h1 class="display-4">Linkinator CLI Generator</h1>
    </div>

 
 <!-- Main Content -->
 <div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <!-- Card with Form -->
            <div class="card">
                <div class="card-body">
                    <form id="linkscannerForm">
                        <div class="form-group">
                            <label for="urlInput">Enter Website URL To Scan</label>
                            <input type="text" class="form-control" id="urlInput" placeholder="https://example.com">
                        </div>
                        <div class="form-group">
                            <label for="reportNameInput">Report name:</label>
                            <input type="text" class="form-control" id="reportNameInput" placeholder="ReportName">
                        </div>
                        <button type="submit" class="btn btn-primary">Generate CLI Command</button>
                    </form>
                    <div id="commandOutput" class="mt-4" style="display:none;">
                        <textarea class="form-control" id="cliCommand" rows="3" readonly></textarea>
                        <button class="btn btn-success mt-2" onclick="copyCommand()">Click-to-Copy</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</div>
</body>
</html>