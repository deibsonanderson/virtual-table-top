<?php
include('./lang.php');
include ('./database.php');

if (isset($_GET['del'])) {
    mysqli_query($connection, "DELETE FROM anotacao WHERE codigo = '" . $_GET['del'] . "'");
}

if (isset($_POST['title'])) {
    $sql = "INSERT INTO `anotacao` (`titulo`, `anotacao`, `data_cadastro`) VALUES ";
    $sql .= " ('" . $_POST['title'] . "', '" . $_POST['note'] . "', NOW()); ";
    mysqli_query($connection, $sql);
}

$query = mysqli_query($connection, "SELECT codigo, titulo, anotacao, date_format(data_cadastro, '%d/%m/%Y %H:%i:%s') as data_cadastro FROM anotacao ");

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo TXT_PAGE_TITLE_NOTES; ?></title>
<link rel="shortcut icon" href="favicon.ico" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="./css/bootstrap-5.2.0-dist/css/bootstrap.css">
<!-- FontAwesome for modern icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Custom Owlbear Theme -->
<link rel="stylesheet" href="./css/owlbear-theme.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg" style="background-color: var(--obr-panel-bg) !important; border-bottom: 1px solid var(--obr-border);">
		<div class="container-fluid">
			<a class="navbar-brand" href="#" style="color: var(--obr-text);"><?php echo TXT_VIRTUAL_TABLE_TOP; ?></a>
			<button class="navbar-toggler" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon" style="filter: invert(1);"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="./index.php"><?php echo TXT_SESSION_MENU; ?></a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="manage-maps.php"><?php echo TXT_INCLUDE_MAPS; ?></a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" href="manage-tokens.php"><?php echo TXT_INCLUDE_TOKENS; ?></a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						onclick="openModal()" href="#"><?php echo TXT_HELP; ?></a></li>
					<li class="nav-item"><a class="nav-link active" style="color: var(--obr-primary);" aria-current="page"
						href="./notes.php"><?php echo TXT_NOTES; ?></a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="dashboard-container">
		<div class="dashboard-card">
			<div class="flex-between" style="margin-bottom: 20px;">
				<h3 style="margin: 0;"><?php echo TXT_NOTES; ?></h3>
			</div>

			<form id="myform" class="row g-2" method="POST" enctype="multipart/form-data">
				<div class="row justify-content-start">
					<div class="col-12" style="margin-bottom: 15px;">
						<button class="obr-btn" type="button"
							data-toggle="collapse" data-target="#collapseExample"
							aria-expanded="false" aria-controls="collapseExample" onclick="showHide(this)"><i class="fa-solid fa-plus"></i> <?php echo TXT_NEW_NOTE; ?></button>
					</div>
				</div>
				<div id="collapseExample" class="collapse" style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid var(--obr-border); border-radius: var(--obr-radius);">
					<div class="row">
						<div class="col-12" style="margin-bottom: 15px;">
							<label for="title" class="form-label" style="color: var(--obr-text-muted);"><?php echo TXT_NOTE_TITLE; ?></label>
							<input type="text" name="title" id="title" class="obr-input" placeholder="">
						</div>
					</div>
					<div class="row">
						<div class="col-12" style="margin-bottom: 15px;">
							<label for="note" class="form-label" style="color: var(--obr-text-muted);"><?php echo TXT_NOTE_TEXT; ?></label>
							<textarea class="obr-input" name="note" id="note" rows="3" style="resize: vertical;"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button class="obr-btn" onclick="add(this);" type="button"><i class="fa-solid fa-check"></i> <?php echo TXT_SAVE; ?></button>
						</div>
					</div>
				</div>
			</form>

			<div style="margin-top: 20px;">
				<?php
				$count = 1;
				while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){				    
				?>
				<div style="background: rgba(0,0,0,0.2); border: 1px solid var(--obr-border); border-radius: var(--obr-radius); padding: 15px; margin-bottom: 15px;">
					<div class="flex-between" style="margin-bottom: 10px;">
						<h5 style="margin: 0; color: var(--obr-primary);"><?php echo $row["titulo"]; ?></h5>
						<button type="button" class="obr-btn-icon" style="color: var(--obr-danger);" onclick="removeItem('./notes.php?del=<?php echo $row["codigo"]; ?>')" title="Remover"><i class="fa-solid fa-trash"></i></button>
					</div>
					<p style="margin: 0; color: var(--obr-text); white-space: pre-wrap;"><?php echo $row["anotacao"]; ?></p>
				</div>
				<?php
				}
				?>				
			</div>
		</div>
	</div>
	<script>
	function removeItem(link){
		if (window.confirm("<?php echo TXT_CONFIRM_REMOVE; ?>")) {
			window.location.href = link;
		}
	}

	function showHide(element){
		var isTrueSet = ($(element).attr('aria-expanded') === 'true');
		
		if(isTrueSet === false){
			$('#collapseExample').collapse('show');
		}else{
			$('#collapseExample').collapse('hide');
		}

		$(element).attr('aria-expanded', !isTrueSet);
		
	}
	
	function add(element){
		if($("#title").val() != null && $("#title").val() != ''){
			document.getElementById("myform").submit();
		}else{
			alert("<?php echo TXT_FILL_FIELD; ?>");
		}
	}
	</script>
	<script src="./js/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
	<script src="./js/jquery-1.12.4.js"></script>
	<script src="./js/jquery-ui.js"></script>
	<?php include('./modal.php'); ?>
</body>
</html>
<?php
 mysqli_close($connection);
?>