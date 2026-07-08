<?php
function debug($value){
	var_dump($value);
	echo '<pre>';
	die();
}

include('./banco.php');

if(isset($_POST)){	
	switch($_POST["tipo"]){
		case('get'):
		    $query = mysqli_query($conexao, "SELECT * FROM token WHERE sessao = '".$_POST["sessao"]."' ");
		    $html = '';
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){		
		        $html .=  '<div id="box-'.$row["codigo"].'" sessao="'.$row["sessao"].'" titulo="'.$row["titulo"].'" class="ui-widget-content box"
    						style="cursor: pointer; width: '.$row["width_posicao"].'px;
    						height: '.$row["height_posicao"].'px; top: '.$row["top_posicao"].'px;
    						left: '.$row["left_posicao"].'px; background-size: 100% 100%;
    						background-image: url(./imagens/tokens/'.$row["token"].');
    						position: absolute;"><span class="sombra" style="top: -41px;left: 0px; position: relative; color: #FFF; white-space: nowrap; ">
                                <b>'.$row["titulo"].'&nbsp;&nbsp;</b><img onclick="remove('.$row["codigo"].','.$row["sessao"].')" style="width:13px;" src="./imagens/close.png"></span></div>
                            <script> mountResizable('.$row["codigo"].'); mountDraggable('.$row["codigo"].'); </script>';
		    }
		    $query = mysqli_query($conexao, "SELECT * FROM `sessao` WHERE `codigo` = '".$_POST['sessao']."' ");
		    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		    $html .= "<script>$('#mapa').attr('src', './imagens/mapas/".$row["mapa"]."');$('#mapa').attr('width', ".$row["zoom"]."+'px');$('#view-zoom').html(".$row["zoom"]."+'px');</script>"; 
			echo $html;
		break;
		
		case('post'):
			$sql = "INSERT INTO `token` (`codigo`, `sessao`, `titulo`, `token`, `top_posicao`, `left_posicao`, `width_posicao`, `height_posicao`) VALUES ";
			$sql .= " ('".$_POST['code']."', '".$_POST['sessao']."', '".$_POST['titulo']."' , '".$_POST['personagem']."', '".$_POST['top']."', '".$_POST['left']."', '".$_POST['width']."', '".$_POST['height']."'); ";
			mysqli_query($conexao, $sql);			
			mysqli_query($conexao, "UPDATE `sessao` SET `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['sessao']."' ");
		break;
		
		case('update'):
			$sql = "UPDATE `token` SET `top_posicao` = '".$_POST['top']."', `left_posicao` = '".$_POST['left']."', `width_posicao` = '".$_POST['width']."', `height_posicao` = '".$_POST['height']."' WHERE `codigo` = '".$_POST['code']."'; ";
			mysqli_query($conexao, $sql);
			mysqli_query($conexao, "UPDATE `sessao` SET `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['sessao']."' ");
		break;
		
		case('remove'):
		    mysqli_query($conexao, "DELETE FROM `token` WHERE `codigo` = '".$_POST['codigo']."'");
		    mysqli_query($conexao, "UPDATE `sessao` SET `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['sessao']."' ");
		break;
		
		case('update_map'):
		    mysqli_query($conexao, "UPDATE `sessao` SET `mapa` = '".$_POST['mapa']."', `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['sessao']."' ");
	    break;
	    
		case('zoom_mapa'):
		    mysqli_query($conexao, "UPDATE `sessao` SET `zoom` = '".$_POST['zoom']."', `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['sessao']."' ");
	    break;
	    
		case('sicronizar'):
			$timeout = 15;
			$startTime = time();
			$clientData = isset($_POST['data_atualizacao']) ? $_POST['data_atualizacao'] : '';
			
			while (time() - $startTime < $timeout) {
				$query = mysqli_query($conexao, "SELECT data_atualizacao FROM sessao WHERE codigo = '".$_POST['sessao']."' ");
				$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
				
				if ($row && $row["data_atualizacao"] != $clientData) {
					echo $row["data_atualizacao"];
					break;
				}
				sleep(1);
			}
			
			if (time() - $startTime >= $timeout) {
				echo $clientData;
			}
	    break;		
	}	
}
mysqli_close($conexao);
?>