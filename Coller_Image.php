<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coller une image et renommer</title>
    <style>
        #image-frame {
            width: 300px;
            height: 300px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        #image-frame img {
            max-width: 100%;
            max-height: 100%;
            display: none;
        }
        #image-frame .placeholder {
            position: absolute;
            text-align: center;
        }
    </style>
</head>
<body>
    <form id="uploadForm" action="upload_Image.php" method="POST" enctype="multipart/form-data">
        <div id="image-frame">
            <div class="placeholder">Collez votre logo ici</div>
            <img id="pastedImage" src="" alt="Pasted Image">
        </div>
        <input type="hidden" name="imageData" id="imageData">
        <label for="companyName">Nom de l'entreprise:</label>
        <input type="text" id="companyName" name="companyName" required>
        <button type="submit">Enregistrer</button>
    </form>

    <script>
        document.addEventListener('paste', function (event) {
            var items = event.clipboardData.items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var blob = items[i].getAsFile();
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        var img = document.getElementById('pastedImage');
                        var placeholder = document.querySelector('#image-frame .placeholder');
                        if (img) {
                            img.src = event.target.result;
                            img.style.display = 'block';
                            document.getElementById('imageData').value = event.target.result;
                            if (placeholder) {
                                placeholder.style.display = 'none';
                            }
                        } else {
                            console.error("Element with id 'pastedImage' not found.");
                        }
                    };
                    reader.readAsDataURL(blob);
                }
            }
        });
    </script>
</body>
</html>
