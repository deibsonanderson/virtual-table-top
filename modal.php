<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1"
	role="dialog" aria-labelledby="exampleModalLongTitle"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Dicas da Bandeira
				</h5>
				<button type="button" onclick="fechar()" class="close"
					data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>
					<b>Ataque normal</b><br />façanha facil (12 ou mais) menos defesa ativa.
				</p>
				<p>
					<b>Ataque forte (é um ataque corpo a corpo muito
mais intenso que o normal e menos preciso)</b><br />façanha dificil (18 ou mais) menos defesa ativa e
					acrescenta 2 pontos ao dano.
				</p>
				<p>
					<b>Ataque preciso (é um ataque feito para atingir ou
perfurar uma parte específica do corpo.)</b><br />façanha dificil (18 ou mais) menos defesa ativa
					no ataque corpo a corpo e defesa passiva a distancia tambem
					acrescenta 2 pontos ao dano.
				</p>
				<p>
					<b>Ataque a distancia (utiliza uma arma apropriada
para atacar um alvo fora do alcance corpo a corpo.
As habilidades utilizadas incluem Armas de arremesso,
Armas de fogo, Armas mecânicas, Armas
de sopro ou Arqueria.)</b><br />façanha facil (12 ou mais) menos defesa
					passiva.
				</p>
				<hr>
				<table class="table">
					<thead>
						<tr>
							<th scope="col">Façanha</th>
							<th scope="col">Valor</th>							
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">fácil</th>
							<td>12</td>							
						</tr>
						<tr>
							<th scope="row">intermediária</th>
							<td>15</td>							
						</tr>
						<tr>
							<th scope="row">difícil</th>
							<td>18</td>							
						</tr>
						<tr>
							<th scope="row">lendária</th>
							<td>21</td>
							
						</tr>
					</tbody>
				</table>
			</div>
			<!--  div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="fechar()">Fechar</button>
      </div-->
		</div>
	</div>
</div>
<script>
function fechar(){
	$('#exampleModalLong').modal('hide');
}

function abrir(){
	$('#exampleModalLong').modal('show');
}
</script>