<?php
include('./lang.php');
$path = "./imagens/mapas/";
$page = "manage-maps.php";

function removeWhiteSpaces($value) {
    $value = str_replace("%20", "_", $value);
    $value = str_replace(" ", "_", $value);
    return $value;
}

if (isset($_FILES['pic'])) {
    //$ext = strtolower(substr($_FILES['pic']['name'], - 4)); // Pegando extensão do arquivo
    $new_name = strtolower($_FILES['pic']['name']);// . $ext; // Definindo um novo nome para o arquivo
    $new_name = removeWhiteSpaces($new_name);
    $new_name = str_replace("[^a-zA-Z0-9_.]", "", strtr($new_name, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
    
    move_uploaded_file($_FILES['pic']['tmp_name'], $path . $new_name); // Fazer upload do arquivo
}

if (isset($_GET['pic'])) {
    unlink($path . $_GET['pic']);
    header("location: " . $page);
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo TXT_PAGE_TITLE_MAPS; ?></title>
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
			<a class="navbar-brand" href="index.php" style="color: var(--obr-text);"><?php echo TXT_VIRTUAL_TABLE_TOP; ?></a>
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
					<li class="nav-item"><a class="nav-link active" style="color: var(--obr-primary);" aria-current="page"
						href="manage-maps.php"><?php echo TXT_INCLUDE_MAPS; ?></a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" href="manage-tokens.php"><?php echo TXT_INCLUDE_TOKENS; ?></a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						onclick="openModal()" href="#" ><?php echo TXT_HELP; ?></a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="./notes.php"><?php echo TXT_NOTES; ?></a></li>						
				</ul>
			</div>			
		</div>
	</nav>

	<div class="dashboard-container">
		<div class="dashboard-card">
			<div class="flex-between" style="margin-bottom: 20px;">
				<h3 style="margin: 0;"><?php echo TXT_MAPS; ?></h3>
			</div>

			<form id="myForm" class="row g-2" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
				<div class="col-8">
					<input class="obr-input" type="file" id="pic" name="pic" accept="image/*">
				</div>
				<div class="col-4">
					<button type="submit" class="obr-btn w-100"><i class="fa-solid fa-upload"></i> <?php echo TXT_SEND_IMAGE; ?></button>
				</div>
			</form>

			<div style="overflow-x: auto;">
				<table class="dashboard-table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col"><?php echo TXT_PREVIEW; ?></th>
							<th scope="col"><?php echo TXT_NAME; ?></th>
							<th scope="col"><?php echo TXT_ACTION; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php

					if (is_dir($path)) {
						$directory = dir($path);
						$count = 1;
						while ($map = $directory->read()) {
							if ($map != '.' && $map != '..' && $map != 'defaut.jpg') {
								?>
								<tr>
									<th scope="row"><?php echo $count++; ?></th>
									<td><img src="<?php echo $path.$map; ?>" width="80px"
										class="img-thumbnail" alt="..." style="background: transparent; border: 1px solid var(--obr-border);"></td>
									<td><?php echo $map; ?></td>
									<td>
										<button type="button" class="obr-btn obr-btn-danger" style="padding: 4px 10px; font-size: 12px;" onclick="removeItem('./<?php echo $page; ?>?pic=<?php echo $map; ?>')"><i class="fa-solid fa-trash"></i> <?php echo TXT_REMOVE; ?></button>
									</td>
								</tr>
								<?php
							}
						}
						$directory->close();
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script>
	function removeItem(link){
		if (window.confirm("<?php echo TXT_CONFIRM_REMOVE; ?>")) {
			window.location.href = link;
		}
	}
	</script>
	<script src="./js/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
	<script src="./js/jquery-1.12.4.js"></script>
	<script src="./js/jquery-ui.js"></script>
	<?php include('./modal.php'); ?>
</body>
</html>