<?php
$session = $_GET["session"];
$tokensPath = "imagens/tokens/";
$mapsPath = "./imagens/mapas/";

include('./lang.php');
include('./database.php');
$query = mysqli_query($connection, "SELECT map, zoom, updated_at FROM tb_vtt_session WHERE code = '".$session."' ");
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
<title><?php echo TXT_PAGE_TITLE_VIRTUAL_TABLE; ?></title>
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
		getNotes(<?php echo $session; ?>);		
		synchronize(<?php echo $session; ?>);
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
	
	function getNotes(session){
		$('.box').each(function() {
			$(this).remove();
		});
		
		$.ajax({
			url: path,
			type: 'POST',
			data: {
				type: 'get',
				session: session
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
				// when clicked
			},
			drag : function() {
				// when dragged
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
		var image = $(element).css("background-image");
		image = image.replace('url("https://rpg.elainelalegourmet.com.br/virtual-table-top/<?php echo $tokensPath; ?>', '');
		image = image.replace('")', '');
		image = image.substr(0, (image.length-2));
		var session = $(element).attr('session');
		var title = $(element).attr('title');
		$.ajax({
			url: path,
			type: 'POST',
			data: {
			  type: 'update',	
			  character: image,
			  code: codeBox,
			  title: title,
			  session: session,
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
	
	function remove(codeBox, session){
		if (window.confirm("<?php echo TXT_CONFIRM_REMOVE; ?>")) {
    		$.ajax({
    			url: path,
    			type: 'POST',
    			data: {
    				  type: 'remove',	
    				  code: codeBox,
    				  session: session				  
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
	
	function create(codeBox, session){
		var title = $("#title").val();

		if(title == '' || title == null){
			alert('<?php echo TXT_ALERT_TOKEN_TITLE; ?>');
		} else {
        
    		$('body').append('<div id="box-'+codeBox+'" session="'+session+'" title="'+title+'" class="ui-widget-content box" style="cursor: pointer; position: absolute;background-size: 100% 100%;"><span class="sombra" style="top: -41px;left: 0px; position: relative;color: #FFF; white-space: nowrap; "><b>'+title+'&nbsp;&nbsp;</b><img onclick="remove('+codeBox+','+session+')" style="width:13px;" src="./imagens/close.png"></span></div>');
    		
    		mountResizable(codeBox);
    
    		mountDraggable(codeBox);
    
            $("#box-"+codeBox).css("top",40);
            $("#box-"+codeBox).css("left",40);
    		var character = $('#personagem').children("option:selected").val();
    		$("#box-"+codeBox).css("background-image", 'url(<?php echo './'.$tokensPath; ?>' + character + ')');	
    		
    		$.ajax({
    			url: path,
    			type: 'POST',
    			data: {
    			  character: character,	
    			  type: 'post',	
    			  code: codeBox,
    			  title: title,
    			  session: session,
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
	
	function synchronize(session){
		var lastUpdateDate = $("#updated_at").val();
	    $.ajax({
			url: path,
			type: 'POST',
			data: {
			  type: 'synchronize',
			  session: session,
			  lastUpdateDate: lastUpdateDate
			},
			headers: {
				"x-access-token":token
			},			
			success: function(result) {
				if(lastUpdateDate != result && result != ''){
					$("#updated_at").val(result);
					getNotes(session);
				}
			},
			error: function() {
				// Lidar com erro silenciosamente
			},
			complete: function(jqXHR, textStatus) {
				var delay = (textStatus === 'error') ? 5000 : 500;
				setTimeout(function(){ synchronize(session); }, delay);
			}
		});	
	}
	
	function maps(session){
		var map = $('#mapas').children("option:selected").val();
		$('#map').attr('src', './imagens/mapas/' + map + '');
		$.ajax({
        	url: path,
        	type: 'POST',
        	data: {
        	  type: 'update_map',
        	  session: session,
        	  map: map			  
        	},
        	headers: {
        		"x-access-token":token
        	},			
        	success: function(result) {},
        	beforeSend: function() {},
        	complete: function() {}
        });	
	}

	function zoom(info, session){
		var size = $('#map').attr('width');
		var number = parseInt(size);		
		if(info == 1 && number >= 500){
			number = (number - 50);
		}else if(info == 2 && number <= 1500){
			number = (number + 50);
		}
		$('#map').attr('width', number+'px');
		$.ajax({
        	url: path,
        	type: 'POST',
        	data: {
        	  type: 'zoom_map',
        	  session: session,
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
	<input type="hidden" id="updated_at" name="updated_at" value="<?php echo $row["updated_at"]; ?>"> 
	
	<div class="vtt-container">
		<!-- Map Layer -->
		<div class="vtt-map-layer">
			<img id="map" width="<?php echo $row["zoom"]; ?>px" src="./imagens/mapas/<?php echo $row["map"]; ?>">
		</div>

		<!-- UI Toggle Button -->
		<div class="floating-panel-bottom-left obr-panel" style="padding: 5px;">
			<button class="obr-btn-icon" onclick="toggleUI()" title="<?php echo TXT_TOGGLE_UI; ?>">
				<i id="toggle-ui-icon" class="fa-solid fa-eye"></i>
			</button>
		</div>

		<!-- Left Toolbar -->
		<div class="floating-toolbar-left obr-panel">
			<a href="./index.php" class="obr-btn-icon" title="<?php echo TXT_SESSIONS; ?>"><i class="fa-solid fa-house"></i></a>
			<a href="manage-maps.php" class="obr-btn-icon" title="<?php echo TXT_INCLUDE_MAPS; ?>"><i class="fa-solid fa-map"></i></a>
			<a href="manage-tokens.php" class="obr-btn-icon" title="<?php echo TXT_INCLUDE_TOKENS; ?>"><i class="fa-solid fa-chess-knight"></i></a>
			<a href="./notes.php" class="obr-btn-icon" title="<?php echo TXT_NOTES; ?>"><i class="fa-solid fa-book"></i></a>
			<button class="obr-btn-icon" onclick="openModal()" title="<?php echo TXT_HELP; ?>"><i class="fa-solid fa-circle-info"></i></button>
		</div>

		<!-- Top Right Panel: Tokens & Maps -->
		<div class="floating-panel-top-right obr-panel">
			<div style="display: flex; flex-direction: column; gap: 10px;">
				<h6 style="margin: 0; font-size: 14px; color: var(--obr-text-muted);"><?php echo TXT_TOKENS; ?></h6>
				<select id="personagem" name="personagem" class="obr-select">
					<option value=''><?php echo TXT_SELECT_TOKEN; ?></option>
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
				<input class="obr-input" type="text" id="title" name="title" placeholder="<?php echo TXT_TOKEN_NAME_PLACEHOLDER; ?>">
				<button type="button" class="obr-btn" onclick="create(getCode(), <?php echo $session; ?>);">
					<i class="fa-solid fa-plus"></i> <?php echo TXT_INCLUDE_TOKEN; ?>
				</button>
			</div>
			
			<hr style="border-color: var(--obr-border); margin: 0;">

			<div style="display: flex; flex-direction: column; gap: 10px;">
				<h6 style="margin: 0; font-size: 14px; color: var(--obr-text-muted);"><?php echo TXT_MAP; ?></h6>
				<select id="mapas" name="mapas" class="obr-select" onChange="maps(<?php echo $session; ?>)">
					<option value="defaut.jpg"><?php echo TXT_SELECT_MAP; ?></option>
					<?php 
					if (is_dir($mapsPath)) {
						$dirMaps = dir($mapsPath);
						while ($map = $dirMaps->read()) {
							if ($map != '.' && $map != '..') {
								echo "<option value='".$map."' >".$map."</option>";
							}
						}
						$dirMaps->close();
					}
					?>
				</select>
			</div>
		</div>

		<!-- Bottom Right Panel: Zoom & Sync -->
		<div class="floating-panel-bottom-right obr-panel">
			<button type="button" class="obr-btn-icon" onclick="zoom(1, <?php echo $session; ?>)" title="<?php echo TXT_ZOOM_OUT; ?>">
				<i class="fa-solid fa-minus"></i>
			</button>
			<span id="view-zoom" class="zoom-display"><?php echo $row["zoom"]; ?>px</span>
			<button type="button" class="obr-btn-icon" onclick="zoom(2, <?php echo $session; ?>)" title="<?php echo TXT_ZOOM_IN; ?>">
				<i class="fa-solid fa-plus"></i>
			</button>
			<div style="width: 1px; height: 24px; background: var(--obr-border); margin: 0 10px;"></div>
			<button type="button" class="obr-btn" onclick="getNotes(<?php echo $session; ?>);" title="<?php echo TXT_SYNC_MANUAL; ?>">
				<i class="fa-solid fa-rotate"></i> <?php echo TXT_SYNC; ?>
			</button>
		</div>
	</div>

	<script src="./js/bootstrap-5.2.0-dist/js/bootstrap.bundle.min.js"></script>

	<?php include('./modal.php'); ?>
</body>
</html>
<?php 
mysqli_close($connection);
?>