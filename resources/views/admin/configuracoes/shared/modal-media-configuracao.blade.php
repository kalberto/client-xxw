<div class="modal fade" id="modal-media">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Listagem de Fotos e Vídeos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Fechar</span>
				</button>
			</div>
			<div class="modal-body modal-tab-container">
				<ul class="nav nav-tabs modal-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link" href="#gallery" data-toggle="tab" role="tab" v-on:click="loadAssets">Galeria</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="#upload" data-toggle="tab" role="tab">Carregar</a>
					</li>
				</ul>
				<div class="tab-content modal-tab-content">
					<div class="tab-pane fade" id="gallery" role="tabpanel">
						<div class="images-container">
							<div class="row">
								<div class="col-12 col-sm-4 col-md-4 col-lg-3" v-for="(media, index) in medias" v-bind:class="{plusSign: isSelected(media.id)}">
									<a href="#" :title="media.nome" v-on:click="selectMedia(media.id, index)">
										<div class="image-container">
											<div class="col-12">
												<img class="image" :src="base_url +'/' + media.media_root.path + media.media_root.media_resizes[0].path + (media.tipo == 1 ? (media.file) : (media.thumbnail))" alt="Thumb Image">
											</div>
											<div class="col-12">
												<span class="title"> @{{media.tipo == 1 ? (media.file.length >= 10 ? media.file.substr(0,10)+"..." : media.file) : (media.thumbnail.length >= 10 ? media.thumbnail.substr(0,10)+"..." : media.thumbnail)}} </span>
											</div>
										</div>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade active in show" id="upload" role="tabpanel">
						<div class="upload-container">
							<div id="dropzone">
								<form action="{{route('admin.api.assets.save')}}" method="POST" enctype="multipart/form-data" class="dropzone needsclick dz-clickable" id="demo-upload">
									{{csrf_field()}}
									@if(isset($alias))
										<input name="alias" value="{{$alias}}" hidden>
									@else
										<input name="alias" value="medias" hidden>
									@endif
									<div class="dz-message-block">
										<div class="dz-message needsclick"> Arraste ou clique para carregar arquivos </div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				<button type="button" class="btn btn-primary">Inserir Selecionados</button>
			</div>
		</div>
	</div>
</div>