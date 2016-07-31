<div id="creation-modal" class="modal">
		<div class="modal-body">
			<a class="modal-close" onclick="hideModal(this.parentNode.parentNode)">&times;</a>
			<div class="flex-parent">
				<div class="flex flex-2">
					<img alt="preview" id="previewer" class="fixed-img" src="./res/creation.png">
				</div>

				<div class="flex flex-2">		
					<form id="creation-form" enctype="multipart/form-data" action="./php/picRouter.php?route=createPic" method="POST">
						<input type="text"  required name="description" class="wide" placeholder="Descrizione"  >
						<div id="pic-file" class="inputfile wide">
							Seleziona un file...
		        		</div>
						<input type="file" required name="pic"><br>

						<input class="submitButton wide" type="submit" value="Invia">
					</form>
				</div>
			</div>
		</div>
	</div>