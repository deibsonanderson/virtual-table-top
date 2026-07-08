<?php
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
<!-- FontAwesome for modern icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Custom Owlbear Theme -->
<link rel="stylesheet" href="./css/owlbear-theme.css">
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
			resize : function() {},
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
		var imagem = $(element).css("background-image");
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
    		var person = $('#personagem').children("option:selected").val();
    		$("#box-"+codeBox).css("background-image", 'url(<?php echo './'.$tokensPath; ?>' + person + ')');	
    		
    		$.ajax({
    			url: path,
    			type: 'POST',
    			data: {
    			  personagem: person,	
    			  tipo: 'post',	
    			  code: codeBox,
    			  titulo: titulo,
    			  sessao: sessao,
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

	function toggleUI() {
		var panels = $('.floating-toolbar-left, .floating-panel-top-right, .floating-panel-bottom-right');
		var icon = $('#toggle-ui-icon');
		if (panels.is(':visible')) {
			panels.hide();
			icon.removeClass('fa-eye').addClass('fa-eye-slash');
		} else {
			panels.show();
			icon.removeClass('fa-eye-slash').addClass('fa-eye');
		}
	}
</script>
</head>
<body>
	<input type="hidden" id="data_atualizacao" name="data_atualizacao" value="<?php echo $row["data_atualizacao"]; ?>"> 
	
	<div class="vtt-container">
		<!-- Map Layer -->
		<div class="vtt-map-layer">
			<img id="mapa" width="<?php echo $row["zoom"]; ?>px" src="./imagens/mapas/<?php echo $row["mapa"]; ?>">
		</div>

		<!-- UI Toggle Button -->
		<div class="floating-panel-top-left obr-panel" style="padding: 5px;">
			<button class="obr-btn-icon" onclick="toggleUI()" title="Alternar Interface">
				<i id="toggle-ui-icon" class="fa-solid fa-eye"></i>
			</button>
		</div>

		<!-- Left Toolbar -->
		<div class="floating-toolbar-left obr-panel">
			<a href="./index.php" class="obr-btn-icon" title="Sessões"><i class="fa-solid fa-house"></i></a>
			<a href="manter-mapas.php" class="obr-btn-icon" title="Incluir Mapas"><i class="fa-solid fa-map"></i></a>
			<a href="manter-tokens.php" class="obr-btn-icon" title="Incluir Tokens"><i class="fa-solid fa-chess-knight"></i></a>
			<a href="./anotacao.php" class="obr-btn-icon" title="Anotações"><i class="fa-solid fa-book"></i></a>
			<button class="obr-btn-icon" onclick="abrir()" title="Ajuda"><i class="fa-solid fa-circle-info"></i></button>
		</div>

		<!-- Top Right Panel: Tokens & Maps -->
		<div class="floating-panel-top-right obr-panel">
			<div style="display: flex; flex-direction: column; gap: 10px;">
				<h6 style="margin: 0; font-size: 14px; color: var(--obr-text-muted);">Tokens</h6>
				<select id="personagem" name="personagem" class="obr-select">
					<option value=''>Selecione um token</option>
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
				<input class="obr-input" type="text" id="title" name="title" placeholder="Nome do token">
				<button type="button" class="obr-btn" onclick="create(getCode(), <?php echo $sessao; ?>);">
					<i class="fa-solid fa-plus"></i> Incluir Token
				</button>
			</div>
			
			<hr style="border-color: var(--obr-border); margin: 0;">

			<div style="display: flex; flex-direction: column; gap: 10px;">
				<h6 style="margin: 0; font-size: 14px; color: var(--obr-text-muted);">Mapa</h6>
				<select id="mapas" name="mapas" class="obr-select" onChange="maps(<?php echo $sessao; ?>)">
					<option value="defaut.jpg">Selecione um Mapa</option>
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
		</div>

		<!-- Bottom Right Panel: Zoom & Sync -->
		<div class="floating-panel-bottom-right obr-panel">
			<button type="button" class="obr-btn-icon" onclick="zoom(1, <?php echo $sessao; ?>)" title="Diminuir Zoom">
				<i class="fa-solid fa-minus"></i>
			</button>
			<span id="view-zoom" class="zoom-display"><?php echo $row["zoom"]; ?>px</span>
			<button type="button" class="obr-btn-icon" onclick="zoom(2, <?php echo $sessao; ?>)" title="Aumentar Zoom">
				<i class="fa-solid fa-plus"></i>
			</button>
			<div style="width: 1px; height: 24px; background: var(--obr-border); margin: 0 10px;"></div>
			<button type="button" class="obr-btn" onclick="getNotes(<?php echo $sessao; ?>);" title="Sincronizar Manualmente">
				<i class="fa-solid fa-rotate"></i> Sincronizar
			</button>
		</div>
	</div>

	<script src="./js/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

	<?php include('./modal.php'); ?>
</body>
</html>
<?php 
mysqli_close($conexao);
?>