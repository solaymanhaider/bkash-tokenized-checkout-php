<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Loading...</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="box">
        <div class="loading">
            <div class="loading_icon">
                <img src="/img/bkash.png" alt="bKash" class="bounce">
            </div>
            <div class="loading_text">Payment Loading...</div>
        </div>
    </div>
    <?php if(isset($_GET["amount"])){ ?>
        <script>
            window.location.href = "/process.php?amount=<?php echo $_GET["amount"];?>";
        </script>
    <?php } ?>
    
</body>
</html>