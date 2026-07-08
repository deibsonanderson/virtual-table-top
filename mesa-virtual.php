<?php
//session_start();
//unset($_SESSION['cenario']);
$sessao = $_GET["sessao"];
$tokensPath = "imagens/tokens/";
$mapasPath = "./imagens/mapas/";

include('./banco.php');
$query = mysqli_query($conexao, "SELECT mapa, zoom, data_atualizacao FROM sessao WHERE codigo = '".$sessao."' ");
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" href="./css/bootstrap-5.2.0-dist/css/bootstrap.css">
<title>Cenário RPG Virtual</title>
<link rel="stylesheet" href="./css/jquery-ui.css">
<style>
.box {
	width: 50px;
	height: 50px;
	padding: 0.5em;
	top: 10px;
	left: 10px;
}

.sombra {
	text-shadow: 0.1em 0.1em 0.05em #333;
}
</style>
<script src="./js/jquery-1.12.4.js"></script>
<script src="./js/jquery-ui.js"></script>
<script src="./js/jquery.ui.touch-punch.js"></script>
<script>
	
	var token = "";
	var path = "server.php"

	$(function() {
		getNotes(<?php echo $sessao; ?>);		
		sicronizar(<?php echo $sessao; ?>);
	});
	
	function getCode(){
		return new Date().getTime();
	}
	
	function getRandomColor() {
	  var letters = '0123456789ABCDEF';
	  var color = '#';
	  for (var i = 0; i < 6; i++) {
		color += letters[Math.floor(Math.random() * 16)];
	  }
	  return color;
	}
	
	function getNotes(sessao){
		$('.box').each(function() {
			$(this).remove();
		});
		
		$.ajax({
			url: path,
			type: 'POST',
			data: {
				tipo: 'get',
				sessao: sessao
			},	
			headers: {
				"x-access-token":token
			},			
			success: function(result) {
				$('body').append(result);				
			},		
			beforeSend: function() {},
			complete: function() {}
		});
	}

	function mountResizable(codeBox){
		$("#box-"+codeBox).resizable({
			maxHeight : 200,
			maxWidth : 200,
			minHeight : 50,
			minWidth : 50,
			resize : function() {
				//var x = $(this).position();
				//var width = $(this).width();
				//var height = $(this).height();
			},
			stop : function() {
				update(codeBox, this);
			}			
		});
	}
	
	function mountDraggable(codeBox){
		$("#box-"+codeBox).draggable({
			start : function() {
				// quando clicado
			},
			drag : function() {
				// quando arrastado
			},
			stop : function() {
				update(codeBox, this);
			}
		});

	}
	
	var hexDigits = new Array
		("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

	//Function to convert rgb color to hex format
	function rgb2hex(rgb) {
		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		return "" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}

	function hex(x) {
		return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
	}
	
	function update(codeBox, element){
	    var position = $(element).position();
		//var color = $(element).css("background-color");
		var imagem = $(element).css("background-image");
		//imagem = imagem.replace('url("http://localhost/virtual-table-top/<?php echo $tokensPath; ?>', '');
		//imagem = imagem.replace('url("http://dicaseprogramacao.com.br/virtual-table-top/<?php echo $tokensPath; ?>', '');
		imagem = imagem.replace('url("https://rpg.elainelalegourmet.com.br/virtual-table-top/<?php echo $tokensPath; ?>', '');
		imagem = imagem.replace('")', '');
		imagem = imagem.substr(0, (imagem.length-2));
		var sessao = $(element).attr('sessao');
		var titulo = $(element).attr('titulo');
		$.ajax({
			url: path,
			type: 'POST',
			data: {
			  tipo: 'update',	
			  personagem: imagem,
			  code: codeBox,
			  titulo: titulo,
			  sessao: sessao,
			  //color: rgb2hex(color),
			  description: $("#area-"+codeBox).val(),
			  user: 1,
			  top: parseInt(position.top),
			  left: parseInt(position.left),
			  width: parseInt($(element).width())+18,
			  height: parseInt($(element).height())+18
			},
			headers: {
				"x-access-token":token
			},			
			success: function(result) {},
			beforeSend: function() {},
			complete: function() {}
		});	
	}
	
	function remove(codeBox, sessao){
		if (window.confirm("Você realmente quer remover?")) {
    		$.ajax({
    			url: path,
    			type: 'POST',
    			data: {
    				  tipo: 'remove',	
    				  codigo: codeBox,
    				  sessao: sessao				  
    			},
    			headers: {
    				"x-access-token":token
    			},			
    			success: function(result) {
    				$("#box-"+codeBox).remove();
    			},
    			beforeSend: function() {},
    			complete: function() {}
    		});	
    		$("#box-"+codeBox).remove();
		}		
	}
	
	function create(codeBox, sessao){
		var titulo = $("#title").val();

		if(titulo == '' || titulo == null){
			alert('Informe um titulo para o token selecionado!');
		} else {
        
    		$('body').append('<div id="box-'+codeBox+'" sessao="'+sessao+'" titulo="'+titulo+'" class="ui-widget-content box" style="cursor: pointer; position: absolute;background-size: 100% 100%;"><span class="sombra" style="top: -41px;left: 0px; position: relative;color: #FFF; white-space: nowrap; "><b>'+titulo+'&nbsp;&nbsp;</b><img onclick="remove('+codeBox+','+sessao+')" style="width:13px;" src="./imagens/close.png"></span></div>');
    		
    		mountResizable(codeBox);
    
    		mountDraggable(codeBox);
    
            $("#box-"+codeBox).css("top",40);
            $("#box-"+codeBox).css("left",40);
    		//var color = getRandomColor();
    		//$("#box-"+codeBox).css("background-color", color);	
    		var person = $('#personagem').children("option:selected").val();
    		$("#box-"+codeBox).css("background-image", 'url(<?php echo './'.$tokensPath; ?>' + person + ')');	
    		//$("#area-"+codeBox).css("background-color", color);	
    		
    		
    		$.ajax({
    			url: path,
    			type: 'POST',
    			data: {
    			  personagem: person,	
    			  tipo: 'post',	
    			  code: codeBox,
    			  titulo: titulo,
    			  sessao: sessao,
    			  //color: color.replace("#", ""),
    			  description: "",
    			  user: 1,
    			  top: 40,
    			  left: 40,
    			  width: 40,
    			  height: 40
    			},
    			headers: {
    				"x-access-token":token
    			},			
    			success: function(result) {},
    			beforeSend: function() {},
    			complete: function() {}
    		});
		}
	}
	
	function sicronizar(sessao){
		var dat = $("#data_atualizacao").val();
	    $.ajax({
			url: path,
			type: 'POST',
			data: {
			  tipo: 'sicronizar',
			  sessao: sessao,
			  data_atualizacao: dat
			},
			headers: {
				"x-access-token":token
			},			
			success: function(result) {
				if(dat != result && result != ''){
					$("#data_atualizacao").val(result);
					getNotes(sessao);
				}
			},
			error: function() {
				// Lidar com erro silenciosamente
			},
			complete: function(jqXHR, textStatus) {
				var delay = (textStatus === 'error') ? 5000 : 500;
				setTimeout(function(){ sicronizar(sessao); }, delay);
			}
		});	
	}
	
	function maps(sessao){
		var map = $('#mapas').children("option:selected").val();
		//$('#main').css("background-image", 'url(./imagens/mapas/' + map + ')');
		$('#mapa').attr('src', './imagens/mapas/' + map + '');
		$.ajax({
        	url: path,
        	type: 'POST',
        	data: {
        	  tipo: 'update_map',
        	  sessao: sessao,
        	  mapa: map			  
        	},
        	headers: {
        		"x-access-token":token
        	},			
        	success: function(result) {},
        	beforeSend: function() {},
        	complete: function() {}
        });	
	}

	function zoom(infor, sessao){
		var size = $('#mapa').attr('width');
		var number = parseInt(size);		
		if(infor == 1 && number >= 500){
			number = (number - 50);
		}else if(infor == 2 && number <= 1500){
			number = (number + 50);
		}
		$('#mapa').attr('width', number+'px');
		$.ajax({
        	url: path,
        	type: 'POST',
        	data: {
        	  tipo: 'zoom_mapa',
        	  sessao: sessao,
        	  zoom: number	  
        	},
        	headers: {
        		"x-access-token":token
        	},			
        	success: function(result) {},
        	beforeSend: function() {},
        	complete: function() {}
        });
		var size = $('#view-zoom').html(number+'px');	
	}
</script>
</head>
<body>
	<input type="hidden" id="data_atualizacao" name="data_atualizacao" value="<?php echo $row["data_atualizacao"]; ?>"> 
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
					<li class="nav-item"><a class="nav-link " aria-current="page"
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
			<div class="col-6">
    			<div class="row">
    				<div class="col-sm-4">
    					<select id="personagem" name="personagem" class="form-select" aria-label="">
    						<option value='' >Selecione um token</option>
    						<?php 
    						if (is_dir('./'.$tokensPath)) {
    						    $dirTokens = dir('./'.$tokensPath);
    						    while ($token = $dirTokens->read()) {
    						        if ($token != '.' && $token != '..') {
    						            echo "<option value='".$token."' >".$token."</option>";
    						        }
    						    }
    						    $dirTokens->close();
    						}
    						?>
    					</select>
    				</div>
    				<div class="col-sm-4">
    				<input class="form-control" type="text" id="title" name="title" >
    				</div>
    				<div class="col-sm-4">
    					<button type="button" class=" btn btn-primary" onclick="create(getCode(), <?php echo $sessao; ?>);">Incluir Token</button>
    				</div>    						 				
				</div>
			</div>
			<div class="col-2">
    			<select id="mapas" name="mapas" class="form-select" onChange="maps(<?php echo $sessao; ?>)" aria-label="">
    				<option value="defaut.jpg" >Selecione um Mapa</option>
    				<?php 
    				if (is_dir($mapasPath)) {
    				    $dirMapas = dir($mapasPath);
    				    while ($mapa = $dirMapas->read()) {
    				        if ($mapa != '.' && $mapa != '..') {
    				            echo "<option value='".$mapa."' >".$mapa."</option>";
    				        }
    				    }
    				    $dirMapas->close();
    				}
    				?>
    			</select>			
			</div>
			<div class="col-4">
				<button type="button" class="btn btn-success" onclick="getNotes(<?php echo $sessao; ?>);">Sicronizar</button>
				<button type="button" class="btn btn-success" onclick="zoom(1, <?php echo $sessao; ?>)">-</button>
				<button type="button" class="btn btn-success" onclick="zoom(2, <?php echo $sessao; ?>);">+</button>
				<span id="view-zoom"><?php echo $row["zoom"]; ?>px</span>
			</div>				
		</div>		
    </div>
    <img id="mapa" width="<?php echo $row["zoom"]; ?>px" src="./imagens/mapas/<?php echo $row["mapa"]; ?>">
	<!-- div id="main"></div-->
	<script src="./js/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

	<?php include('./modal.php'); ?>
</body>
</html>
<?php 
mysqli_close($conexao);
?>