<?php
include('./database.php');
if (isset($_GET['del'])) {
    mysqli_query($connection, "DELETE FROM sessao WHERE codigo = '".$_GET['del']."'");
    mysqli_query($connection, "DELETE FROM `token` WHERE `sessao` = '".$_GET['del']."'");
}

if (isset($_POST['name'])) {
    $date = new DateTime();
    $sql = "INSERT INTO `sessao` (`codigo`, `nome`, `data_criacao`, `data_atualizacao`, `zoom`, `mapa`) VALUES ";
    $sql .= " ('".$date->getTimestamp()."', '".$_POST['name']."', NOW(), NOW(), '1000', 'defaut.jpg'); ";
    mysqli_query($connection, $sql);
}

$query = mysqli_query($connection, "SELECT codigo, nome, date_format(data_criacao, '%d/%m/%Y %H:%i:%s') as data_criacao, date_format(data_atualizacao, '%d/%m/%Y %H:%i:%s') as data_atualizacao  FROM sessao ");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sessão - Virtual Table Top</title>
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
			<a class="navbar-brand" href="#" style="color: var(--obr-text);">Virtual Table Top</a>
			<button class="navbar-toggler" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon" style="filter: invert(1);"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link active" style="color: var(--obr-primary);" aria-current="page"
						href="./index.php">Sessão</a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" aria-current="page"
						href="manage-maps.php">Incluir Mapas</a></li>
					<li class="nav-item"><a class="nav-link" style="color: var(--obr-text-muted);" href="manage-tokens.php">Incluir
							Tokens</a></li>
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
				<h3 style="margin: 0;">Sessões</h3>
			</div>
			
			<form id="myForm" class="row g-2" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
				<div class="col-8">
					<input class="obr-input" type="text" id="name" name="name" placeholder="Nome da Sessão">
				</div>
				<div class="col-4">
					<button type="button" onclick="add(this);" class="obr-btn w-100"><i class="fa-solid fa-plus"></i> Incluir identificador</button>
				</div>
			</form>

			<div style="overflow-x: auto;">
				<table class="dashboard-table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Codigo</th>
							<th scope="col">Nome</th>
							<th scope="col">Data da criação</th>
							<th scope="col">Atualização</th>
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
							<td><a href="./virtual-table.php?session=<?php echo $row["codigo"]; ?>"><i class="fa-solid fa-gamepad"></i> Mesa Virtual</a></td>
							<td>
								<button type="button" class="obr-btn obr-btn-danger" style="padding: 4px 10px; font-size: 12px;" onclick="removeItem('./?del=<?php echo $row["codigo"]; ?>')"><i class="fa-solid fa-trash"></i> remover</button>        					
							</td>
						</tr>
					<?php
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

	function add(element){
		if($("#name").val() != null && $("#name").val() != ''){
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
mysqli_close($connection);
?>