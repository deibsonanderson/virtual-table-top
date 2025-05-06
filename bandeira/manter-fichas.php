<?php
$pagina = "manter-fichas.php";
include('banco.php');

if (isset($_GET['del'])) {
    mysqli_query($conexao, "DELETE FROM ficha_bandeira WHERE identificador = '".$_GET['del']."'");        
}

if (isset($_POST['identificador'])) {
    $sql = "INSERT INTO `ficha_bandeira` (`identificador`, `nome`, `idade`, `caracteristicas`, `historico`, `pontos`, `habilidade_1`, `nivel_habilidade_1`, `habilidade_2`, `nivel_habilidade_2`, `habilidade_3`, `nivel_habilidade_3`, `habilidade_4`, `nivel_habilidade_4`, `habilidade_5`, `nivel_habilidade_5`, `habilidade_6`, `nivel_habilidade_6`, `habilidade_7`, `nivel_habilidade_7`, `habilidade_8`, `nivel_habilidade_8`, `habilidade_9`, `nivel_habilidade_9`, `habilidade_10`, `nivel_habilidade_10`, `habilidade_11`, `nivel_habilidade_11`, `habilidade_12`, `nivel_habilidade_12`, `habilidade_13`, `nivel_habilidade_13`, `habilidade_14`, `nivel_habilidade_14`, `habilidade_15`, `nivel_habilidade_15`, `resistencia_atual`, `resistencia_maxima`, `dano_critico`, `anotacoes`, `defesa_passiva`, `defesa_ativa`, `energia`, `dinheiro_bens`, `arma_1`, `dano_arma_1`, `arma_2`, `dano_arma_2`, `arma_3`, `dano_arma_3`, `arma_4`, `dano_arma_4`) VALUES ";
    $sql .= " ('".$_POST['identificador']."', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL); ";    
    mysqli_query($conexao, $sql);
} 

$query = mysqli_query($conexao, "SELECT identificador FROM ficha_bandeira");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fichas - ABEA</title>
<link rel="shortcut icon" href="./imagens/favicon.ico" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="./bootstrap-5.2.0-dist/css/bootstrap.css">
<script src="../jquery-1.12.4.js"></script>
<script src="../jquery-ui.js"></script>
</head>
<body>

	<nav class="navbar navbar-expand-lg bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="../index.php">Virtual Table Top</a>
			<button class="navbar-toggler" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="../index.php">Sessão</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="../manter-mapas.php">Incluir Mapas</a></li>
					<li class="nav-item"><a class="nav-link "
						href="../manter-tokens.php">Incluir Tokens</a></li>
					<li class="nav-item"><a class="nav-link active" aria-current="page"
						href="./bandeira/manter-fichas.php">Incluir Fichas (ABEA)</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						onclick="abrir()" href="#" >Ajuda</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="../anotacao.php">Anotações</a></li>																		
				</ul>
			</div>
		</div>
	</nav>

	<div class="container text-center">
		<div class="row">
			<p class="h3">Fichas (ABEA)</p>		
		</div>
		<div class="row">
			<form id="myForm" class="row g-2" method="POST" enctype="multipart/form-data">
				<div class="col-8">
					<input class="form-control" type="text" id="identificador"
						name="identificador" >
				</div>
				<div class="col-4">
					<button type="submit" class="btn btn-primary mb-3">Incluir identificador</button>
				</div>
			</form>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Nome</th>
						<th scope="col">Link</th>
						<th scope="col">Ação</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$count = 1;
				while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){				    
                ?>
        			<tr>
    					<th scope="row"><?php echo $count++;?></th>
    					<td><?php echo $row["identificador"]; ?></td>
    					<td><a href="./ficha.php?id=<?php echo $row["identificador"]; ?>">ficha de personagem</a></td>
    					<td>
        					<button type="button" class="btn btn-danger" onclick="removerItem('./<?php echo $pagina; ?>?del=<?php echo $row["identificador"]; ?>')">remover</button>        					
    					</td>
					</tr>
				<?php
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
	<script src="./bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
	<?php include('../modal.php'); ?>
</body>
</html>
<?php 
mysqli_close($conexao);
?>