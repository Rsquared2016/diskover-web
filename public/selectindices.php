<?php

require __DIR__ . '/../vendor/autoload.php';
use diskover\Constants;
error_reporting(E_ALL ^ E_NOTICE);
require __DIR__ . "/../src/diskover/Diskover.php";

$host = Constants::ES_HOST;
$port = Constants::ES_PORT;

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>diskover &mdash; Index Changer</title>
<!--<link rel="stylesheet" href="/css/bootstrap.min.css" media="screen" />
<link rel="stylesheet" href="/css/bootstrap-theme.min.css" media="screen" />-->
<link rel="stylesheet" href="/css/bootswatch.min.css" media="screen" />
<link rel="stylesheet" href="/css/diskover.css" media="screen" />
<?php
// set cookies for indices and redirect to index page
if (isset($_POST['index'])) {
    createCookie('index', $_POST['index']);
    if (isset($_POST['index2']) && $_POST['index2'] != "none") {
        createCookie('index2', $_POST['index2']);
    } else if (isset($_POST['index2']) && ($_POST['index2'] == "none" || $_POST['index2'] == "" )) {
        deleteCookie('index2');
    }
    header("location: /index.php");
}
?>
</head>

<body>

<div class="container">
<div class="row">
	<div class="col-xs-12">
		<center><img style="margin-top: 100px;" src="/images/diskover.png" alt="diskover" width="249" height="189" /></center>
		<center><span class="text-success small"><?php echo "diskover-web v".Constants::VERSION; ?></span></center>
	</div>
</div>
<div class="row">
    <br />
<div class="col-xs-6 col-xs-offset-3">
	<form action="" method="post" class="form-horizontal">
	<fieldset>
		<div class="form-group">
			<?php
				// Get cURL resource
				$curl = curl_init();
				// Set curl options
				curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => 'http://'.$host.':'.$port.'/diskover-*?pretty'
				));
				// Send the request & save response to $curlresp
				$curlresp = curl_exec($curl);
				$indices = json_decode($curlresp, true);
				// Close request to clear up some resources
				curl_close($curl);
			?>
            <?php if ($indices == null) { ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <i class="glyphicon glyphicon-exclamation-sign"></i> No diskover indices found in Elasticsearch. Please run a crawl and come back.
                </div>
            <?php } else { ?>
                <div class="alert alert-dismissible alert-info">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <i class="glyphicon glyphicon-cog"></i> Please select the Elasticsearch diskover indices you want to use.
                </div>
            <?php } ?>
            <strong>Index </strong> <small>*required</small>
			<select name="index" id="index" class="form-control">
                <option selected><?php echo getCookie('index') ? getCookie('index') : ""; ?></option>
                <?php
				foreach ($indices as $key => $val) {
					echo "<option>".$key."</option>";
				}
				?></select>
            <br />
            <strong>Index 2 </strong> <small>(previous index for data comparison)</small>
            <select name="index2" id="index2" class="form-control">
                <option selected><?php echo getCookie('index2') ? getCookie('index2') : ""; ?></option>
                <?php
				foreach ($indices as $key => $val) {
					echo "<option>".$key."</option>";
				}
                echo "<option>none</option>";
				?></select>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Select</button>
		</div>
	</fieldset>
	</form>
</div>
</div>
</div>

<script language="javascript" src="/js/jquery.min.js"></script>
<script language="javascript" src="/js/bootstrap.min.js"></script>

</body>

</html>