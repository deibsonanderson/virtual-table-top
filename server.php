<?php
function debug($value){
	var_dump($value);
	echo '<pre>';
	die();
}

include('./database.php');

if(isset($_POST)){	
	switch($_POST["type"]){
		case('get'):
		    $query = mysqli_query($connection, "SELECT * FROM token WHERE sessao = '".$_POST["session"]."' ");
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
		    $query = mysqli_query($connection, "SELECT * FROM `sessao` WHERE `codigo` = '".$_POST['session']."' ");
		    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		    $html .= "<script>$('#mapa').attr('src', './imagens/mapas/".$row["mapa"]."');$('#mapa').attr('width', ".$row["zoom"]."+'px');$('#view-zoom').html(".$row["zoom"]."+'px');</script>"; 
			echo $html;
		break;
		
		case('post'):
			$sql = "INSERT INTO `token` (`codigo`, `sessao`, `titulo`, `token`, `top_posicao`, `left_posicao`, `width_posicao`, `height_posicao`) VALUES ";
			$sql .= " ('".$_POST['code']."', '".$_POST['session']."', '".$_POST['title']."' , '".$_POST['character']."', '".$_POST['top']."', '".$_POST['left']."', '".$_POST['width']."', '".$_POST['height']."'); ";
			mysqli_query($connection, $sql);			
			mysqli_query($connection, "UPDATE `sessao` SET `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['session']."' ");
		break;
		
		case('update'):
			$sql = "UPDATE `token` SET `top_posicao` = '".$_POST['top']."', `left_posicao` = '".$_POST['left']."', `width_posicao` = '".$_POST['width']."', `height_posicao` = '".$_POST['height']."' WHERE `codigo` = '".$_POST['code']."'; ";
			mysqli_query($connection, $sql);
			mysqli_query($connection, "UPDATE `sessao` SET `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['session']."' ");
		break;
		
		case('remove'):
		    mysqli_query($connection, "DELETE FROM `token` WHERE `codigo` = '".$_POST['code']."'");
		    mysqli_query($connection, "UPDATE `sessao` SET `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['session']."' ");
		break;
		
		case('update_map'):
		    mysqli_query($connection, "UPDATE `sessao` SET `mapa` = '".$_POST['map']."', `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['session']."' ");
	    break;
	    
		case('zoom_map'):
		    mysqli_query($connection, "UPDATE `sessao` SET `zoom` = '".$_POST['zoom']."', `data_atualizacao` = NOW()  WHERE `codigo` = '".$_POST['session']."' ");
	    break;
	    
		case('synchronize'):
			$timeout = 15;
			$startTime = time();
			$clientData = isset($_POST['lastUpdateDate']) ? $_POST['lastUpdateDate'] : '';
			
			while (time() - $startTime < $timeout) {
				$query = mysqli_query($connection, "SELECT data_atualizacao FROM sessao WHERE codigo = '".$_POST['session']."' ");
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
mysqli_close($connection);
?>