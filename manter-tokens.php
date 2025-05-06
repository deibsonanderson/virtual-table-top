<?php
$path = "./imagens/tokens/";
$pagina = "manter-tokens.php";

function removeEspacosEmBranco($valor) {
    $valor = str_replace("%20", "_", $valor);
    $valor = str_replace(" ", "_", $valor);
    return $valor;
}

if (isset($_FILES['pic'])) {
    //$ext = strtolower(substr($_FILES['pic']['name'], - 4)); // Pegando extensão do arquivo
    //$new_name = date("Y.m.d-H.i.s") . $ext; // Definindo um novo nome para o arquivo
    $new_name = strtolower($_FILES['pic']['name']);// . $ext; // Definindo um novo nome para o arquivo
    $new_name = removeEspacosEmBranco($new_name);
    $new_name = str_replace("[^a-zA-Z0-9_.]", "", strtr($new_name, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
    move_uploaded_file($_FILES['pic']['tmp_name'], $path . $new_name); // Fazer upload do arquivo
}

if (isset($_GET['pic'])) {
    unlink($path . $_GET['pic']);
    header("location: " . $pagina);
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tokens</title>
<link rel="shortcut icon" href="favicon.ico" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="./bandeira/bootstrap-5.2.0-dist/css/bootstrap.css">
<script src="jquery-1.12.4.js"></script>
<script src="jquery-ui.js"></script>
</head>
<body>

	<nav class="navbar navbar-expand-lg bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="index.php">Virtual Table Top</a>
			<button class="navbar-toggler" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="./index.php">Sessão</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="manter-mapas.php">Incluir Mapas</a></li>
					<li class="nav-item"><a class="nav-link active"
						href="manter-tokens.php">Incluir Tokens</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="./bandeira/manter-fichas.php">Incluir Fichas (ABEA)</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						onclick="abrir()" href="#" >Ajuda</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="./anotacao.php">Anotações</a></li>												
				</ul>
			</div>
		</div>
	</nav>

	<div class="container text-center">
		<div class="row">
			<p class="h3">Tokens</p>		
		</div>
		<div class="row">
			<form id="myForm" class="row g-2" method="POST" enctype="multipart/form-data">
				<div class="col-8">
					<input class="form-control" type="file" id="pic"
						name="pic" accept="image/*">
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-primary mb-3">Enviar imagem</button>
				</div>
			</form>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Previa</th>
						<th scope="col">Nome</th>
						<th scope="col">Ação</th>
					</tr>
				</thead>
				<tbody>
				<?php

                if (is_dir($path)) {
                    $diretorio = dir($path);
                    $count = 1;
                    while ($token = $diretorio->read()) {
                        if ($token != '.' && $token != '..') {
                            ?>
                    			<tr>
            						<th scope="row"><?php echo $count++; ?></th>
            						<td><img src="<?php echo $path.$token; ?>" width="80px"
            							class="img-thumbnail" alt="..."></td>
            						<td><?php echo $token; ?></td>
            						<td>
            							<button type="button" class="btn btn-danger" onclick="removerItem('./<?php echo $pagina; ?>?pic=<?php echo $token; ?>')">remover</button>
            						</td>
            					</tr>
            				<?php
                        }
                    }
                    $diretorio->close();
                }
                ?>
				</tbody>
			</table>
		</div>
	</div>
	<script>
	function removerItem(link){
		if (window.confirm("Você realmente quer remover ?")) {
			window.location.href = link;
		}
	}
	</script>	
	<script src="./bandeira/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
	<?php include('./modal.php'); ?>
</body>
</html>