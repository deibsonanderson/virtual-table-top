<?php
function selecao($valor1, $valor2){
    $selecao = "";
    if (($valor1 == "") || ($valor1 == $valor2)) {
        $selecao = "selected='selected'";
    }
    return $selecao;
}

try {
    include('banco.php');
    $query = mysqli_query($conexao, "SELECT * FROM ficha_bandeira WHERE identificador = '" . $_GET["id"] . "'");
    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
    mysqli_close($conexao);
} catch (Exception $e) {
    var_dump($e);
    echo '<pre>';
    die();
}

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ficha - ABEA</title>
<link href="./imagens/favicon.ico" rel="shortcut icon" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="./bootstrap-5.2.0-dist/css/bootstrap.css">
<style type="text/css">
body {	
	background-image: url("imagens/textura-de-fundo-da-floresta_35024-474_old.jpg");
}
label, h1, b{
    color:#FFF;
}
</style>
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
						href="./manter-fichas.php">Incluir Fichas (ABEA)</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						onclick="abrir()" href="#" >Ajuda</a></li>
					<li class="nav-item"><a class="nav-link" aria-current="page"
						href="../anotacao.php">Anotações</a></li>													
				</ul>
			</div>
		</div>
	</nav>
	<div class="container text-center">
		<form action="index.php?id=<?php echo $_GET["id"]; ?>" method="POST" id="form_badeira">
			<input type="hidden" id="tipo_operacao" name="tipo_operacao" value="1">
			<input type="hidden" id="identificador" name="identificador" value="<?php echo $row["identificador"]?>">
			<div class="row">&nbsp;</div>
			<div class="row">
				<div class="col-lg-8">
					<div class="row">
						<div class="col">
							<h1>Ficha Personagem</h1>
						</div>
					</div>
					<div class="row">
						<div class="col col-lg-8">
							<div class="row">
								<label for="nome" class="col-sm-2 col-form-label"><b>Nome</b></label>
								<div class="col-sm-10">
									<input type="text" onchange="submitForm(this)"
										class="form-control" id="nome" name="nome" value="<?php echo $row["nome"]?>">
								</div>
							</div>
						</div>
						<div class="col">
							<div class="row">
								<label for="idade" class="col-sm-6 col-form-label"><b>Idade</b></label>
								<div class="col-sm-6">
									<input type="number" class="form-control" id="idade" name="idade" value="<?php echo $row["idade"]?>"
										placeholder="18" min="18" onchange="submitForm(this)">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="caracteristicas" class="form-label"><b>Caracteristicas</b></label>
							<textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" onchange="submitForm(this)"><?php echo $row["caracteristicas"]?></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label for="historico" class="form-label"><b>Historico</b></label>
							<textarea class="form-control" id="historico" name="historico" rows="5" onchange="submitForm(this)"><?php echo $row["historico"]?></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col col-lg-4"><label><b>Pontos a gastar</b></label></div>
						<div class="col col-lg-2">
							<input type="number" onchange="submitForm(this)" class="form-control" id="pontos" name="pontos" value="<?php echo $row["pontos"]?>"
								min="0">
						</div>
						<div class="col col-lg-6"><label><b>Obs. Aprendiz (custo 1) / Praticante
							(custo 3) / Mestre (custo 7)</b></label></div>
					</div>
					<div class="row">
						<div class="col col-lg-8">
							<b>Habilidades</b>
						</div>
						<div class="col col-lg-4">
							<b>Select Nivel</b>
						</div>
					</div>
					<?php  for ($i = 1; $i <= 15; $i ++) { ?>					
					<div class="row">
						<div class="col col-lg-8">
							<input type="text" class="form-control"
								id="habilidades_<?php echo $i; ?>" onchange="submitForm(this)" name="habilidade_<?php echo $i; ?>" value="<?php echo $row["habilidade_".$i]?>">
						</div>
						<div class="col col-lg-4">
							<select id="nivel_<?php echo $i; ?>" onchange="submitForm(this)" name="nivel_habilidade_<?php echo $i; ?>" class="form-select"
								aria-label="Nivel da habilidade">
								<option <?php echo selecao($row["nivel_habilidade_".$i], "0"); ?> value="0"></option>
								<option <?php echo selecao($row["nivel_habilidade_".$i], "1"); ?> value="1">Aprendiz (Bonus +3)</option>
								<option <?php echo selecao($row["nivel_habilidade_".$i], "2"); ?> value="2">Praticante (Bonus +6)</option>
								<option <?php echo selecao($row["nivel_habilidade_".$i], "3"); ?> value="3">Mestre (Bonus +9)</option>
							</select>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="col-lg-4">
					<div class="row">
						<img id="logo" src="imagens/logo.png" class="img"	alt="...">
						<div class="d-flex justify-content-center" >
							<div id="loading" class="spinner-border m-5 text-light" role="status">
								<span class="sr-only" ></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="row">
								<b>Condições Fisicas</b>
							</div>
							<div class="row">
								<label for="dano_critico" class="col-sm-8 col-form-label"><b>Resistencia
									Atual</b></label>
								<div class="col-sm-4">
									<input type="number" onchange="submitForm(this)" class="form-control" id="resistencia_atual"  value="<?php echo $row["resistencia_atual"]?>"
										name="resistencia_atual" max="15" min="0">
								</div>
							</div>
							<div class="row">
								<label for="dano_critico" class="col-sm-8 col-form-label"><b>Resistencia
									Maxima</b></label>
								<div class="col-sm-4">
									<input type="number" onchange="submitForm(this)" class="form-control" id="resistencia_maxima"
										name="resistencia_maxima" value="<?php echo $row["resistencia_maxima"]?>" max="15" min="10">
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="row">
										<label for="dano_critico" class="col-sm-8 col-form-label"><b>Dano
											Critico</b></label>
										<div class="col-sm-4">
											<select id="dano_critico" onchange="submitForm(this)" name="dano_critico" class="form-select" aria-label="Nivel do dano critico">
												<?php 
												for($i = 0; $i <=5; $i++){
												    $select = ($row["dano_critico"] == $i)?'selected="selected"':'';
												    echo "<option value='".$i."' ".$select." >".$i."</option>";
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label for="anotacoes" class="form-label"><b>Anotações</b></label>
									<textarea class="form-control" onchange="submitForm(this)" id="anotacoes" name="anotacoes" rows="2"><?php echo $row["anotacoes"]?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col-lg-8">
							<div class="row">
								<div class="col">
									<b>Defesa</b>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="">
										<input type="number" class="form-control" id="defesa_passiva"
											name="defesa_passiva" onchange="submitForm(this)" value="<?php echo $row["defesa_passiva"]?>" min="0">
									</div>
									<label for="defesa_passiva" class="col-form-label"><b>Passiva</b></label>
								</div>
								<div class="col">
									<div class="">
										<input type="number" class="form-control" id="defesa_ativa"
											name="defesa_ativa" onchange="submitForm(this)" value="<?php echo $row["defesa_ativa"]?>" min="0">
									</div>
									<label for="defesa_ativa" class="col-form-label"><b>Ativa</b></label>
								</div>
							</div>
						</div>
						<div class="col">
							<label for="energia" class="col-form-label"><b>Energia</b></label>
							<div class="">
								<input type="number" class="form-control" onchange="submitForm(this)"  id="energia" name="energia" value="<?php echo $row["energia"]?>"
									min="0">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="row">
								<div class="col">
									<label for="dinheiro_bens" class="form-label"><b>Dinheiro e
											Bens</b></label>
									<textarea rows="12" class="form-control" onchange="submitForm(this)" name="dinheiro_bens" id="dinheiro_bens"><?php echo $row["dinheiro_bens"]?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="row">
								<div class="col col-lg-8">
									<b>Armas</b>
								</div>
								<div class="col">
									<b>Dano</b>
								</div>
							</div>
							<?php for ($i = 1; $i <= 4; $i ++) { ?>
							<div class="row">
								<div class="col col-lg-8">
									<input type="text" class="form-control" onchange="submitForm(this)"
										id="arma_<?php echo $i; ?>" name="arma_<?php echo $i; ?>" value="<?php echo $row["arma_".$i]?>">
								</div>
								<div class="col">
									<input type="number" class="form-control" min="0" onchange="submitForm(this)"
										id="dano_arma_<?php echo $i; ?>" name="dano_arma_<?php echo $i; ?>" value="<?php echo $row["dano_arma_".$i]?>">
								</div>
							</div>
							<?php } ?>							
						</div>
					</div>
				</div>
			</div>
			<div class="row">&nbsp;</div>
		</form>
	</div>
	<script src="./bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>
	<script src="./jquery/jquery-3.3.1.min.js"></script>
	<script>
    	$(document).ready(function(){
    		$("#loading").css("display", "none");
    	});		
    	function submitForm(e){
    		$(e.form).each(function() {
    			campos = $(this).serialize();
    		});
    
    		$.ajax({
    			url : 'server.php',
    			type : 'POST',
    			data : campos,
    			success : function(result) {
    				console.log('Dados atualizados com sucesso!!!');
    			},
    			beforeSend : function() {
    				//$('#logo').css('display','none');
    				//$('#loading').css('display','');
    			},
    			complete : function() {
    				//$('#logo').css('display','');
    				//$('#loading').css('display','none');
    			}, error : function() {
    				alert('Houve um erro na operação de update!!!')
    			}
    		});
    	}
	</script>
	<?php include('../modal.php'); ?>
</body>
</html>