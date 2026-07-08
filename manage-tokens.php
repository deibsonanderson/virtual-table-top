<?php
$path = "./imagens/tokens/";
$page = "manage-tokens.php";

function removeWhiteSpaces($value) {
    $value = str_replace("%20", "_", $value);
    $value = str_replace(" ", "_", $value);
    return $value;
}

if (isset($_FILES['pic'])) {
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
<title>Tokens - Virtual Table Top</title>
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
			<a class="navbar-brand" href="index.php" style="color: var(--obr-text);">Virtual Table Top</a>
			<button class="navbar-toggler" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon" style="filter: invert(1);"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="./index.php">Sessão</a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="manage-maps.php">Incluir Mapas</a></li>
					<li class="nav-item"><a class="nav-link active" style="color: var(--obr-primary);"
						href="manage-tokens.php">Incluir Tokens</a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						onclick="openModal()" href="#" >Ajuda</a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="./notes.php">Anotações</a></li>												
				</ul>
			</div>
		</div>
	</nav>

	<div class="dashboard-container">
		<div class="dashboard-card">
			<div class="flex-between" style="margin-bottom: 20px;">
				<h3 style="margin: 0;">Tokens</h3>
			</div>
			
			<form id="myForm" class="row g-2" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
				<div class="col-8">
					<input class="obr-input" type="file" id="pic" name="pic" accept="image/*">
				</div>
				<div class="col-4">
					<button type="submit" class="obr-btn w-100"><i class="fa-solid fa-upload"></i> Enviar imagem</button>
				</div>
			</form>

			<div style="overflow-x: auto;">
				<table class="dashboard-table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Prévia</th>
							<th scope="col">Nome</th>
							<th scope="col">Ação</th>
						</tr>
					</thead>
					<tbody>
					<?php

					if (is_dir($path)) {
						$directory = dir($path);
						$count = 1;
						while ($token = $directory->read()) {
							if ($token != '.' && $token != '..') {
								?>
								<tr>
									<th scope="row"><?php echo $count++; ?></th>
									<td><img src="<?php echo $path.$token; ?>" width="80px"
										class="img-thumbnail" alt="..." style="background: transparent; border: 1px solid var(--obr-border);"></td>
									<td><?php echo $token; ?></td>
									<td>
										<button type="button" class="obr-btn obr-btn-danger" style="padding: 4px 10px; font-size: 12px;" onclick="removeItem('./<?php echo $page; ?>?pic=<?php echo $token; ?>')"><i class="fa-solid fa-trash"></i> remover</button>
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
		if (window.confirm("Você realmente quer remover ?")) {
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