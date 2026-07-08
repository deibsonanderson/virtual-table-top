<?php
include('./banco.php');
if (isset($_GET['del'])) {
    mysqli_query($conexao, "DELETE FROM sessao WHERE codigo = '".$_GET['del']."'");
    mysqli_query($conexao, "DELETE FROM `token` WHERE `sessao` = '".$_GET['del']."'");
}

if (isset($_POST['nome'])) {
    $date = new DateTime();
    $sql = "INSERT INTO `sessao` (`codigo`, `nome`, `data_criacao`, `data_atualizacao`, `zoom`, `mapa`) VALUES ";
    $sql .= " ('".$date->getTimestamp()."', '".$_POST['nome']."', NOW(), NOW(), '1000', 'defaut.jpg'); ";
    mysqli_query($conexao, $sql);
}

$query = mysqli_query($conexao, "SELECT codigo, nome, date_format(data_criacao, '%d/%m/%Y %H:%i:%s') as data_criacao, date_format(data_atualizacao, '%d/%m/%Y %H:%i:%s') as data_atualizacao  FROM sessao ");
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
					<li class="nav-item"><a class="nav-link active" aria-current="page"
						href="./index.php">Sessão</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="manter-mapas.php">Incluir Mapas</a></li>
					<li class="nav-item"><a class="nav-link" href="manter-tokens.php">Incluir
							Tokens</a></li>
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
			<p class="h3">Sessões</p>		
		</div>
		<div class="row">
			<form id="myForm" class="row g-2" method="POST" enctype="multipart/form-data">
				<div class="col-8">
					<input class="form-control" type="text" id="nome"
						name="nome" >
				</div>
				<div class="col-4">
					<button type="button" onclick="incluir(this);" class="btn btn-primary mb-3">Incluir identificador</button>
				</div>
			</form>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Codigo</th>
						<th scope="col">Nome</th>
						<th scope="col">Data da criação</th>
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
    					<td><?php echo $row["codigo"]; ?></td>
    					<td><?php echo $row["nome"]; ?></td>
    					<td><?php echo $row["data_criacao"]; ?></td>
    					<td><?php echo $row["data_atualizacao"]; ?></td>
    					<td><a href="./mesa-virtual.php?sessao=<?php echo $row["codigo"]; ?>">Mesa Virtual</a></td>
    					<td>
        					<button type="button" class="btn btn-danger" onclick="removerItem('./?del=<?php echo $row["codigo"]; ?>')">remover</button>        					
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

	function incluir(element){
		if($("#nome").val() != null && $("#nome").val() != ''){
			document.getElementById("myForm").submit();
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
mysqli_close($conexao);
?>