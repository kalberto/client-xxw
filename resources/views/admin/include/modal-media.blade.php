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
								<div class="col-12 col-sm-4 col-md-4 col-lg-3" v-for="(media, index) in medias">
									{{-- <input :value="select_media == media.id" class="checkbox rounded" type="checkbox" selected=""> --}}
									{{-- <input class="checkbox rounded" type="checkbox" checked v-if="select_media == media.id"> --}}
									{{-- <span></span> --}}
									<div class="custom-success-mark" v-if="select_media == media.id">
										<svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path></g></svg>
									</div>
									<a href="#" v-on:click="selectMedia(media.id, index)" :title="media.file">
										<div class="image-container">
											<div class="col-12">
												<img class="image" :src="base_url +'/' + media.media_root.path + 'thumb/' + (media.tipo == 1 ? (media.file) : (media.thumbnail))" alt="Thumb Image">
											</div>
											<div class="col-12">
												<span> @{{media.tipo == 1 ? (media.file.length >= 40 ? media.file.substr(0,40)+"..." : media.file) : (media.thumbnail.length >= 40 ? media.thumbnail.substr(0,40)+"..." : media.thumbnail)}} </span>
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
									<input name="alias" :value="media_root" hidden>
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
			</div>
		</div>
	</div>
</div>
