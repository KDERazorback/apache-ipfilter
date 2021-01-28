<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/fontawesome/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/attribution.js" ></script>
    <script type="text/javascript" src="js/main.js"></script>
    <title>IpFilter Setup Interface</title>
</head>
<body>
    <div class="frontend">
        <div class="content">
            <header class="header">
                <img src='img/logo_xs.png' alt='logo' height="96px" />
                <h1>IpFilter Driver Installer</h1>
                <div class="progress-header progress-header-initial">
                    <div class="progress-header-title">
                        <h4>Starting setup...</h4>
                    </div>
                </div>
                <div class="progress-header progress-header-running">
                    <div class="progress-header-title">
                        <img src='img/spinner.gif' alt='spinner' height="32px" />
                        <h4>Installing driver...</h4>
                    </div>
                    <span class="progress-header-msg progress-header-running-msg"></span>
                </div>
                <div class="progress-header progress-header-done">
                    <div class="progress-header-title">
                        <i class="fas fa-check"></i>
                        <h4>Installation succeeded</h4>
                    </div>
                </div>
                <div class="progress-header progress-header-error">
                    <div class="progress-header-title">
                        <i class="fas fa-exclamation-circle"></i>
                        <h4>Driver installation failed!</h4>
                    </div>
                    <span class="progress-header-msg progress-header-error-msg">An error has occurred</span>
                </div>
            </header>
            <main class="main">
                <div id="logview" class="logview">
                </div>
            </main>
            <footer class="footer">
                <span>RazorSoftware IpFilter Setup monitoring tool</span>
            </footer>
        </div>
    </div>
</body>
</html>