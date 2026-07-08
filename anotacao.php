<?php
include ('./banco.php');

if (isset($_GET['del'])) {
    mysqli_query($conexao, "DELETE FROM anotacao WHERE codigo = '" . $_GET['del'] . "'");
}

if (isset($_POST['titulo'])) {
    $sql = "INSERT INTO `anotacao` (`titulo`, `anotacao`, `data_cadastro`) VALUES ";
    $sql .= " ('" . $_POST['titulo'] . "', '" . $_POST['anotacao'] . "', NOW()); ";
    mysqli_query($conexao, $sql);
}

$query = mysqli_query($conexao, "SELECT codigo, titulo, anotacao, date_format(data_cadastro, '%d/%m/%Y %H:%i:%s') as data_cadastro FROM anotacao ");

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sessão</title>
<link rel="shortcut icon" href="favicon.ico" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="./css/bootstrap-5.2.0-dist/css/bootstrap.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">Virtual Table Top</a>
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
					<li class="nav-item"><a class="nav-link" href="manter-tokens.php">Incluir
							Tokens</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						onclick="abrir()" href="#">Ajuda</a></li>
					<li class="nav-item"><a class="nav-link active" aria-current="page"
						href="./anotacao.php">Anotações</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container text-center">
		<form id="myform" class="row g-2" method="POST"	enctype="multipart/form-data">
			<div class="row">
				<p class="h3">Anotação</p>
			</div>
			<div class="row justify-content-center">
				<div class="col-6">
					<button class="btn btn-primary" type="button"
						data-toggle="collapse" data-target="#collapseExample"
						aria-expanded="false" aria-controls="collapseExample" onclick="showHide(this)">Campos</button>
				</div>
			</div>
			<div id="collapseExample" class="collapse">
				<div class="row justify-content-center">
					<div class="col-6">
						<label for="titulo" class="form-label">Título</label>
						<input type="text" name="titulo" id="titulo" class="form-control" placeholder="">
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-6">
						<label for="anotacao" class="form-label">Anotação</label>
						<textarea class="form-control" name="anotacao" id="anotacao" rows="3"></textarea>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-6">
						<br />
						<button class="btn btn-primary" onclick="incluir(this);" type="button">Incluir</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="container ">
		<br />
		<div class="row">
			<div class="col-6">
				<?php
				$count = 1;
				while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){				    
                ?>
				<p>
					<b><?php echo $row["titulo"]; ?></b><br /><?php echo $row["anotacao"]; ?>
					<br/><a href="#"  onclick="removerItem('./anotacao.php?del=<?php echo $row["codigo"]; ?>')">remover</a>
				</p>
				<?php
                }
                ?>				
			</div>
		</div>
	</div>
	<script>
	function removerItem(link){
		if (window.confirm("Você realmente quer remover ?")) {
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
	
	function incluir(element){
		if($("#titulo").val() != null && $("#titulo").val() != ''){
			document.getElementById("myform").submit();
		}else{
			alert("favor preencher o campo !!!");
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
// mysqli_close($conexao);
?>