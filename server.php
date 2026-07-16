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
		    $query = mysqli_query($connection, "SELECT * FROM tb_vtt_token WHERE session = '".$_POST["session"]."' ");
		    $html = '';
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){		
		        $html .=  '<div id="box-'.$row["code"].'" session="'.$row["session"].'" title="'.$row["title"].'" class="ui-widget-content box"
    						style="cursor: pointer; width: '.$row["width_position"].'px;
    						height: '.$row["height_position"].'px; top: '.$row["top_position"].'px;
    						left: '.$row["left_position"].'px; background-size: 100% 100%;
    						background-image: url(./imagens/tokens/'.$row["token"].');
    						position: absolute;"><span class="sombra" style="top: -41px;left: 0px; position: relative; color: #FFF; white-space: nowrap; ">
                                <b>'.$row["title"].'&nbsp;&nbsp;</b><img onclick="remove('.$row["code"].','.$row["session"].')" style="width:13px;" src="./imagens/close.png"></span></div>
                            <script> mountResizable('.$row["code"].'); mountDraggable('.$row["code"].'); </script>';
		    }
		    $query = mysqli_query($connection, "SELECT * FROM `tb_vtt_session` WHERE `code` = '".$_POST['session']."' ");
		    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		    $html .= "<script>$('#map').attr('src', './imagens/mapas/".$row["map"]."');$('#map').attr('width', ".$row["zoom"]."+'px');$('#view-zoom').html(".$row["zoom"]."+'px');</script>"; 
			echo $html;
		break;
		
		case('post'):
			$sql = "INSERT INTO `tb_vtt_token` (`code`, `session`, `title`, `token`, `top_position`, `left_position`, `width_position`, `height_position`) VALUES ";
			$sql .= " ('".$_POST['code']."', '".$_POST['session']."', '".$_POST['title']."' , '".$_POST['character']."', '".$_POST['top']."', '".$_POST['left']."', '".$_POST['width']."', '".$_POST['height']."'); ";
			mysqli_query($connection, $sql);			
			mysqli_query($connection, "UPDATE `tb_vtt_session` SET `updated_at` = NOW()  WHERE `code` = '".$_POST['session']."' ");
		break;
		
		case('update'):
			$sql = "UPDATE `tb_vtt_token` SET `top_position` = '".$_POST['top']."', `left_position` = '".$_POST['left']."', `width_position` = '".$_POST['width']."', `height_position` = '".$_POST['height']."' WHERE `code` = '".$_POST['code']."'; ";
			mysqli_query($connection, $sql);
			mysqli_query($connection, "UPDATE `tb_vtt_session` SET `updated_at` = NOW()  WHERE `code` = '".$_POST['session']."' ");
		break;
		
		case('remove'):
		    mysqli_query($connection, "DELETE FROM `tb_vtt_token` WHERE `code` = '".$_POST['code']."'");
		    mysqli_query($connection, "UPDATE `tb_vtt_session` SET `updated_at` = NOW()  WHERE `code` = '".$_POST['session']."' ");
		break;
		
		case('update_map'):
		    mysqli_query($connection, "UPDATE `tb_vtt_session` SET `map` = '".$_POST['map']."', `updated_at` = NOW()  WHERE `code` = '".$_POST['session']."' ");
	    break;
	    
		case('zoom_map'):
		    mysqli_query($connection, "UPDATE `tb_vtt_session` SET `zoom` = '".$_POST['zoom']."', `updated_at` = NOW()  WHERE `code` = '".$_POST['session']."' ");
	    break;
	    
		case('synchronize'):
			$timeout = 15;
			$startTime = time();
			$clientData = isset($_POST['lastUpdateDate']) ? $_POST['lastUpdateDate'] : '';
			
			while (time() - $startTime < $timeout) {
				$query = mysqli_query($connection, "SELECT updated_at FROM tb_vtt_session WHERE code = '".$_POST['session']."' ");
				$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
				
				if ($row && $row["updated_at"] != $clientData) {
					echo $row["updated_at"];
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