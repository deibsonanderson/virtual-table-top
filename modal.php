<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1"
	role="dialog" aria-labelledby="exampleModalLongTitle"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="color: black;">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Dicas da Bandeira
				</h5>
				<button type="button" onclick="closeModal()" class="close"
					data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.</p>				
			</div>			
		</div>
	</div>
</div>
<script>
function closeModal(){
	$('#exampleModalLong').modal('hide');
}

function openModal(){
	$('#exampleModalLong').modal('show');
}
</script>