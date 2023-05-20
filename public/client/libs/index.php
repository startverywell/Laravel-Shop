<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FORMSTYLEE</title>

    <!-- javascript -->
    <script src="./libs/jquery/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <!-- css -->
    <link rel="stylesheet" href="./assets/css/style.css?20220319-2">
</head>

<?php
$survey_id = (isset($_GET['id'])) ? $_GET['id'] : -1;
$click_status = $_GET['name'];
?>
<?php
if ($click_status == 'close' || $click_status == NULL) { ?>
<body style="background-color: transparent; position: absolute; bottom: 0;">
    <div style="display: flex" class="chat-widget">
        <div class="ballon-icon-left icon" style="width: 50px; height: 50px; background-size: cover;">
        </div>
        <div class="q-area" style="width: auto; margin: 0 0 0 40px;">
            <div id="q-txt-row-main" class="q-txt-row">
                <div id="q-txt-main" class="q-txt">
                    <div class="loadingContainer">
                    </div>
                </div>
            </div>
        </div>
        <div class="ballon-icon-right icon" style="width: 50px; height: 50px; background-size: cover;">
        </div>
    </div>
    <script type="text/javascript">
            var survey_id = '<?=$survey_id;?>';
        </script>
    <script src="./assets/js/script.js"></script>
</body>
<?php   } elseif( $click_status == 'open') {  ?>
<body style="position: absolute; bottom: 80px">
    <header id="header" class="header">
        <div class="site-header">
            <div class="site-header-inner">
                <div class="brand-wrapper">
                    <div id="brand" class="brand"><img src="" alt="" /></div>
                    <p id="brand-name" class="brand-name"></p>
                </div>
                <div id="brand-desc" class="brand-desc"></div>
                <div id="title-desc" class="title-desc">
                    <h1 id="title" class="title"><span></span></h1>
                    <p id="description" class="description"></p>
                </div>
                <div id="btn-start" class="btn-start">START</div>
                <div id="progress-row" class="progress-row">
                    <div class="point"></div>
                    <div id="progress-inner" class="progress-inner"></div>
                    <div class="point"></div>
                </div>
            </div>
        </div>
    </header>
    <div id="content" class="content">

        <script type="text/javascript">var submitted=false;</script>
        <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;" onload="if(submitted) {window.location='thanks.php';}"></iframe>
            
        <form action="<?=domain_name;?>api/v1/client/save" method="POST" target="hidden_iframe" onsubmit="submitted=true;">
            <input type="hidden" name="survey_id" value="<?=$survey_id;?>" />
            <div id="chatview" class="chatview">
            </div>
            <div id="end-anchor"></div>
        </form>
    </div>
    <div id="loading-area">
        <div class="loader-wrapper">
            <div class="loader">Loading...</div>
        </div>
    </div>
    <script type="text/javascript">
        var survey_id = '<?=$survey_id;?>';
    </script>
    <script src="./assets/js/script.js"></script>

    
</body>

<div style="display: flex; position: absolute; bottom: 0;" class="chat-widget">
        <div class="ballon-icon-left icon" style="width: 50px; height: 50px; background-size: cover;">
        </div>
        <div class="q-area" style="width: auto; margin: 0 0 0 40px;">
            <div id="q-txt-row-main" class="q-txt-row">
                <div id="q-txt-main" class="q-txt">
                    <div class="loadingContainer">
                    </div>
                </div>
            </div>
        </div>
        <div class="ballon-icon-right icon" style="width: 50px; height: 50px; background-size: cover;">
        </div>
    </div>
<?php } ?>
</html>