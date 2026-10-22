<?php
session_start();
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deploy Bot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Android Style Reset */
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #f5f7fa; 
            margin: 0; 
            display: flex; 
            flex-direction: column;
            height: 100vh;
        }

        /* Material Design App Bar */
        .app-bar {
            background-color: #6200ee;
            color: white;
            padding: 16px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .app-bar i { margin-right: 20px; cursor: pointer; }
        .app-bar span { font-size: 20px; font-weight: 500; }

        /* Main Container */
        .container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Android Card Look */
        .card {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            padding: 24px;
            box-sizing: border-box;
            margin-top: 20px;
        }

        .instruction {
            color: #757575;
            font-size: 14px;
            margin-bottom: 24px;
        }

        /* Standard Android Input Area */
        .file-upload-area {
            border: 2px dashed #bdbdbd;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 24px;
            background-color: #fafafa;
        }
        .file-upload-area i {
            font-size: 48px;
            color: #6200ee;
            margin-bottom: 12px;
        }

        /* Floating Action Button Style */
        .btn-deploy {
            background-color: #6200ee;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1.25px;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 3px 1px -2px rgba(0,0,0,0.2), 0 2px 2px 0 rgba(0,0,0,0.14), 0 1px 5px 0 rgba(0,0,0,0.12);
            transition: background-color 0.2s;
        }
        .btn-deploy:active { background-color: #3700b3; }

        .back-link {
            margin-top: 20px;
            color: #6200ee;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="app-bar">
        <a href="index.php" style="color: white;"><i class="fas fa-arrow-left"></i></a>
        <span>Upload Bot</span>
    </div>

    <div class="container">
        <div class="card">
            <p class="instruction">Select a PHP file from your storage to deploy on the server.</p>
            
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="file-upload-area" onclick="document.getElementById('file-input').click()">
                    <i class="fas fa-file-upload"></i>
                    <p style="margin: 0; color: #616161;">Tap to select file</p>
                    <input type="file" id="file-input" name="botfile" accept=".php" required style="display: none;">
                </div>

                <button type="submit" class="btn-deploy">Deploy Now</button>
            </form>
        </div>
        
        <a href="index.php" class="back-link">Return to Dashboard</a>
    </div>

    <script>
        // Filename dikhane ke liye thoda sa JS
        document.getElementById('file-input').onchange = function() {
            if(this.files[0]) {
                document.querySelector('.file-upload-area p').innerHTML = "Selected: " + this.files[0].name;
                document.querySelector('.file-upload-area').style.borderColor = "#6200ee";
            }
        };
    </script>

</body>
</html>
