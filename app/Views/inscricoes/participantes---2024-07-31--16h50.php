<?php 
	$this->extend('templates/template_default');
	$this->section('content'); 


	$event_hashkey = (isset($rs_event->event_hashkey) ? $rs_event->event_hashkey : "");
	$event_titulo = (isset($rs_event->event_titulo) ? $rs_event->event_titulo : "");

	$grp_id = (isset($rs_event->grp_id) ? $rs_event->grp_id : "");
	$grp_hashkey = (isset($rs_event->grp_hashkey) ? $rs_event->grp_hashkey : "");

	$list_rs_categorias =  (isset($list_rs_categorias) ? $list_rs_categorias : []);
	$list_rs_func_obrig = (isset($list_rs_func_obrig) ? $list_rs_func_obrig : []);
	$list_rs_funcoes = (isset($rs_funcoes) ? $rs_funcoes->getResult() : []);

	$rs_partc_limites = [];
	$rs_config_fields = [];
	$evcfg_seletiva = '';
	$evcfg_exigir_foto_doc = '';
	if( isset($rs_event_config)){
		$evcfg_func_limites = (isset($rs_event_config->evcfg_func_limites) ? $rs_event_config->evcfg_func_limites : "");
		$evcfg_func_limites = json_decode($evcfg_func_limites);
		$rs_partc_limites = $evcfg_func_limites;
		$evcfg_seletiva = (int)(isset($rs_event_config->evcfg_seletiva) ? $rs_event_config->evcfg_seletiva : "");
		$evcfg_exigir_foto_doc = (int)(isset($rs_event_config->evcfg_exigir_foto_doc) ? $rs_event_config->evcfg_exigir_foto_doc : "");
	}
	$rs_config_fields = [
		'evcfg_seletiva' => $evcfg_seletiva,
		'evcfg_exigir_foto_doc' => $evcfg_exigir_foto_doc,
	];

	$rs_partc_cadastrados = (isset($rs_partc_cadastrados) ? $rs_partc_cadastrados : []);

	$link_retorna_grupos = site_url('inscricoes/grupos/'. $event_hashkey);
?>
	<section class="pt-3 pb-4">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-12">
					<h3>Inscrições > <?php echo( $event_titulo ); ?></h3>
				</div>
			</div>
			<div class="row pt-3 pb-5">
				<div class="col-12 col-md-12">

					<FORM action="<?php echo(current_url()); ?>" method="post" name="formFieldsInscricao" id="formFieldsInscricao" ref="formFieldsInscricao" enctype="multipart/form-data">

						<div class="d-none">
							<input type="text" name="event_hashkey" id="event_hashkey" class="form-control" ref="event_hashkey" v-model="fields.event_hashkey" value="<?php echo($event_hashkey);?>" />
							<input type="text" name="grp_id" id="grp_id" class="form-control" ref="grp_id" v-model="fields.grp_id" value="<?php echo($grp_id);?>" />
							<input type="text" name="grp_hashkey" id="grp_hashkey" class="form-control" ref="grp_hashkey" v-model="fields.grp_hashkey" value="<?php echo((isset($grp_hashkey) ? $grp_hashkey : ""));?>" />
						</div>

					<div class="row align-items-start">
						<div class="col-12 col-md-12">

							<div class="card card-default">
								<div class="card-header-box" style="display:none !important;">
									<div class="row align-items-center">
										<div class="col-12 col-md-6">
											
										</div>
										<div class="col-12 col-md-6">
											<div class="d-flex justify-content-end">
												<div style="margin-left: 5px;"><a href="<?php echo(painel_url('grupos')); ?>" class="btn btn-sm btn-warning">Voltar</a></div>
												<div style="margin-left: 5px;"><input type="submit" class="btn btn-sm btn-success" value="Salvar"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-body">

									<div class="row ">
										<div class="col-12 col-md-3">

											<?php 
												$w_data['step'] = '2'; // PARTICIPANTES
												$incMenuLateral = view('inscricoes/menu-lateral', $w_data);
												echo( $incMenuLateral );
											?>

										</div>
										<div class="col-12 col-md-9">

											<!-- Step 2 -->
											<div class="h-100" v-show="step == 2" >
												<div class="content-step current justify-content-center align-items-center flex-column h-100">
													<div class="container">
														<div class="row justify-content-center">
															<div class="col-12 col-md-12">
																<div>
																	<div class="row">
																		<div class="col-12 col-md-9">
																			<h2 class="fw-bolder text-dark title-step m-0">Participantes</h2>
																			<!-- <div class="text-muted fs-6 text-center text-md-start">Informe que irão integrar ao grupo.</div> -->
																		</div>
																		<div class="col-12 col-md-3">
																			<div class="" v-if="previewLogotipo">
																				<div>file name: {{ imageLogotipo.name }}</div>
																				<div class="d-none">preview: {{ previewLogotipo }}</div>
																			</div>
																			<div class="personal-image">
																				<label class="label">
																					<input type="file" ref="fileInputLogotipo" v-on:change="pickFileLogotipo" />
																					<figure class="personal-figure">
																						<div v-if="previewLogotipo" class="personal-avatar-bg" v-bind:style="{ 'background-image': 'url(' + previewLogotipo + ')' }"></div>
																						<div v-else class="personal-avatar-bg" style="background-image: url('assets/media/icon-profile2.png');">
																							<!-- <img src="assets/media/icon-profile-default.png" class="personal-avatar" alt="avatar"> -->
																						</div>
																						<figcaption class="personal-figcaption">
																							<img src="https://raw.githubusercontent.com/ThiagoLuizNunes/angular-boilerplate/master/src/assets/imgs/camera-white.png">
																						</figcaption>
																					</figure>
																				</label>
																			</div>
																			<div class="text-center"><label class="form-label text-center">Foto do Participante</label></div>
																		</div>
																	</div>
																</div>
																<div class="content-itens" style="margin-top: 10px;">

																	<div class="row d-none">
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="partc_hashkey">HashKey</label>
																					<input type="text" name="partc_hashkey" id="partc_hashkey" class="form-control" v-model="fields.partc_hashkey" value="" />
																				</div>
																			</div>
																		</div>
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="categ_id">Categ</label>
																					<input type="text" name="categ_id" id="categ_id" class="form-control" v-model="fields.categ_id" value="" />
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="partc_documento">Documento (CPF) *</label>
																					<input type="text" name="partc_documento" id="partc_documento" class="form-control mask-cpf cmdBlurDocumento" v-model="fields.partc_documento" value="" />
																					<div class="text-center mt-1 divError" style="line-height: 1; display:none;">
																						<small style="color: red;">CPF já foi cadastro em outro grupo/companhia</small>
																					</div>
																				</div>
																				<div class="form-error" v-if="error.partc_documento.length"><small>{{ error.partc_documento }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-8">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="partc_nome">Nome *</label>
																					<input type="text" name="partc_nome" id="partc_nome" class="form-control" v-model="fields.partc_nome" value="" />
																				</div>
																				<div class="form-error" v-if="error.partc_nome.length"><small>{{ error.partc_nome }}</small></div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-12 col-md-4">
																			<!-- genero -->
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="partc_genero">Gênero *</label>
																					<select class="form-select" name="partc_genero" id="partc_genero" v-model="fields.partc_genero">
																						<option value="" translate="no">- selecione -</option>
																						<?php
																						if( isset($arr_generos) ){
																							foreach ($arr_generos as $key => $val) {
																						?>
																							<option value="<?php echo($val['value']); ?>" translate="no"><?php echo($val['label']); ?></option>
																						<?php
																							}
																						}
																						?>
																					</select>
																				</div>
																				<div class="form-error" v-if="error.partc_genero.length"><small>{{ error.partc_genero }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-8">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="partc_nome_social">Nome Social (não obrigatorio)</label>
																					<input type="text" name="partc_nome_social" id="partc_nome_social" class="form-control" v-model="fields.partc_nome_social" value="" />
																				</div>
																				<div class="form-error" v-if="error.partc_nome_social.length"><small>{{ error.partc_nome_social }}</small></div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-12 col-md-4">
																			<!-- dte nascimento -->
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="partc_dte_nascto">Data de Nascimento *</label>
																					<div class="position-relative d-flex align-items-center">
																						<input type="text" name="partc_dte_nascto" id="partc_dte_nascto" class="form-control mask-date flatpickr_date" v-model="fields.partc_dte_nascto" value="" style="padding-right: 3rem !important;" />
																						<span class="position-absolute mx-4" style="right: 0;">
																							<img src="assets/svg/icon-calendar.svg" />
																						</span>
																					</div>
																				</div>
																				<div class="form-error" v-if="error.partc_dte_nascto.length"><small>{{ error.partc_dte_nascto }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-8">
																			<!-- funcao -->
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="func_id">Função *</label>
																					<select class="form-select" name="func_id" id="func_id" v-model="fields.func_id">
																						<option value="" translate="no">- selecione -</option>
																						<option value="" translate="no" v-for="(value, key, index) in lista_funcoes" :value='value.func_id'>{{value.func_titulo}}</option>	
																					</select>
																				</div>
																				<div class="form-error" v-if="error.func_id.length"><small>{{ error.func_id }}</small></div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-12 col-md-6">

																			<div class="form-group">
																				<div class="">
																					<label class="form-label text-center">Documento Frente</label>
																				</div>
																				<label for="fileInputDocFrente" style="display: block; width: 100%;">
																					<div class="d-flex align-items-center form-control" style="border: 1.5px solid #e79c32 !important; height: 100%;">
																						<div style="border-right: 1.5px solid #e79c32 !important; padding-right: 0.5rem !important;"><i class="fas fa-file-upload"></i></div>
																						<div style="padding-left: 0.5rem !important; color: gray;">
																							<span class="" v-if="previewDocFrente">
																								{{ imageDocFrente.name }}
																							</span>
																							<span v-else>
																								Nenhum arquivo escolhido
																							</span>
																						</div>
																						<div class="d-none">
																							<input type="file" name="fileInputDocFrente" id="fileInputDocFrente" ref="fileInputDocFrente" v-on:change="pickFileDocFrente" style="display: none;" />
																						</div>
																					</div>
																				</label>
																				<div class="form-error" v-if="error.partc_file_doc_frente.length"><small>{{ error.partc_file_doc_frente }}</small></div>
																			</div>

																		</div>
																		<div class="col-12 col-md-6">

																			<div class="form-group">
																				<div class="">
																					<label class="form-label text-center">Documento Verso</label>
																				</div>
																				<label for="inputFileDocV" style="display: block; width: 100%;">
																					<div class="d-flex align-items-center form-control" style="border: 1.5px solid #e79c32 !important; height: 100%;">
																						<div style="border-right: 1.5px solid #e79c32 !important; padding-right: 0.5rem !important;"><i class="fas fa-file-upload"></i></div>
																						<div style="padding-left: 0.5rem !important; color: gray;">
																							Nenhum arquivo escolhido
																						</div>
																						<div class="d-none">
																							<input type="file" name="inputFileDocV" id="inputFileDocV" class="form-control files">
																						</div>
																					</div>
																				</label>
																			</div>

																		</div>
																	</div>








																	<div class="row" style="margin-top: 50px;">
																		<div class="col-12 col-md-6">

																			<div class="mb-2">
																				<div>
																					<label class="form-label">Documento Frente *</label>
																					<div class="box-file-upload m-0">
																						<div class="img-preview">
																							<div v-if="previewDocFrente" class="docto-avatar-bg" v-bind:style="{ 'background-image': 'url(' + previewDocFrente + ')' }">
																							</div>
																							<div v-else class="docto-avatar-bg">
																								<!-- <img src="https://avatars1.githubusercontent.com/u/11435231?s=460&v=4" class="personal-avatar" alt="avatar"> -->
																							</div>
																						</div>
																						<div class="txt-click">
																							<label>
																								<!-- clique aqui -->
																								<input type="file" ref="fileInputDocFrente_old" v-on:change="pickFileDocFrente" style="display: none;" />
																								<div class="d-none" v-if="previewDocFrente">
																									<div>file name: {{ imageDocFrente.name }}</div>
																									<div class="d-none">preview: {{ previewDocFrente }}</div>
																								</div>
																								<figure class="personal-figures">
																									<figcaption class="personal-figcaptions">
																										<img src="https://raw.githubusercontent.com/ThiagoLuizNunes/angular-boilerplate/master/src/assets/imgs/camera-white.png">
																									</figcaption>
																								</figure>
																							</label>
																						</div>
																					</div>
																				</div>
																				<div class="form-error" v-if="error.partc_file_doc_frente.length"><small>{{ error.partc_file_doc_frente }}</small></div>
																			</div>

																		</div>
																		<div class="col-12 col-md-6">




																			<div class="mb-2">
																				<div>
																					<label class="form-label" for="partc_nome_social">Documento Verso *</label>
																					<div class="box-file-upload m-0">
																						<div class="img-preview">
																							<div v-if="previewDocVerso" class="docto-avatar-bg" v-bind:style="{ 'background-image': 'url(' + previewDocVerso + ')' }">
																							</div>
																							<div v-else class="docto-avatar-bg"></div>
																						</div>
																						<div class="txt-click">
																							<label>
																								<!-- clique aqui -->
																								<input type="file" ref="fileInputDocVerso" v-on:change="pickFileDocVerso" style="display: none;" />
																								<div class="d-none" v-if="previewDocVerso">
																									<div>file name: {{ imageDocVerso.name }}</div>
																									<div class="d-none">preview: {{ previewDocVerso }}</div>
																								</div>
																								<figure class="personal-figures">
																									<figcaption class="personal-figcaptions">
																										<img src="https://raw.githubusercontent.com/ThiagoLuizNunes/angular-boilerplate/master/src/assets/imgs/camera-white.png">
																									</figcaption>
																								</figure>
																							</label>

																						</div>
																					</div>
																				</div>
																				<div class="form-error" v-if="error.partc_file_doc_verso.length"><small>{{ error.partc_file_doc_verso }}</small></div>
																			</div>

																		</div>
																	</div>



																</div>
																<div class="content-actions" style="margin-top: 15px;">

																	<div class="row justify-content-start" v-if="formAcao == 'INSERT'">
																		<div class="col-8 col-md-12">
																			<div class="d-grid">
																				<button type="button" class="btn btn-warning" v-on:click="saveParticipante()" :disabled='partcBTNDisabled'>Adicionar Novo Participante</button>
																			</div>
																		</div>
																	</div>
																	<div class="row justify-content-start" v-if="formAcao == 'UPDATE'">
																		<div class="col-8 col-md-12">
																			<div class="d-grid">
																				<button type="button" class="btn btn-warning" v-on:click="updateParticipante()" :disabled='partcBTNDisabled'>Salvar Alterações</button>
																			</div>
																		</div>
																	</div>

																</div>


															</div>
														</div>

														<!-- 
														{ 
															"partc_documento": "", 
															"partc_nome": "Márcio Lima", 
															"partc_nome_social": "", 
															"partc_genero": "", 
															"partc_dte_nascto": "", 
															"func_id": "", 
															"partc_file_doc_frente": "", 
															"partc_file_doc_verso": "", 
															"partc_file_foto": "" 
														} 
														-->

														<!-- {{fields.participantes}} -->
														<textarea type="text" name="lista_participantes" id="lista_participantes" class="form-control d-none" v-model="fields.participantes_json" style="height: 120px;"></textarea>

														<div class="row justify-content-center mt-5" v-show="fields.participantes.length > 0">
															<div class="col-12 col-md-12">
																<div style="padding: 12px; border-radius: 6px; background-color: rgb(221 221 221);">
																	<h3 style="color: #000000;">Participantes Adicionados</h3>
																	<div class="table-box table-responsive">
																		<table id="example2" class="display nowrap table table-striped table-bordered m-0" style="width:100%">
																			<thead>
																				<tr v-for="(value, key, index) in fields.participantes">
																					<td>
																						<div class="personal-image-header">
																							<label class="label">
																								<figure class="personal-figure-header">
																									<div v-if="previewLogotipo">
																										<img :src="value.partc_file_foto_preview" class="personal-avatar-header" alt="avatar">
																									</div>
																									<!-- <img src="assets/media/icon-profile2.png" class="personal-avatar-header" alt="avatar"> -->
																								</figure>
																							</label>
																						</div>																					
																					</td>
																					<td>
																						<div>{{value.partc_nome}}</div>
																						<div><small>cpf:</small> {{value.partc_documento}}</div>
																					</td>
																					<td>
																						<small>idade:</small> 
																						<strong>({{value.partc_idade}} anos)</strong> 
																						<!-- {{value.partc_dte_nascto}} partc_hashkey -->
																					</td>
																					<td>{{value.partc_categoria}}</td>
																					<td>
																						<!-- {{value.func_id}} |  -->
																						{{value.func_titulo}}</td>
																					<td>
																						<div class="d-flex justify-content-center" style="gap: 10px">
																							<a href="javascript:;" style="font-size: 1.25rem; color: red;" v-on:click="removeParticipante({hashkey: value.partc_hashkey})"><i class="far fa-times-circle"></i></a>
																							<a href="javascript:;" v-on:click="editarParticipante({hashkey: value.partc_hashkey})" style="font-size: 1.25rem; color: #18b918;"><i class="fas fa-user-edit"></i></a>
																						</div>
																					</td>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
															</div>
														</div>

														<div class="content-actions">
															<div class="row justify-content-between">
																<div class="col-4 col-md-3">
																	<div class="d-grid">
																		<a href="<?php echo($link_retorna_grupos); ?>" class="btn btn-secondary">Anterior</a>
																	</div>
																</div>
																<div class="col-8 col-md-6">
																	<div class="d-grid">
																		<a href="javascript:;" class="btn btn-primary" v-on:click="stepGravarParticipante()">Finalizar Cadastro de Participantes</a>
																	</div>
																</div>
															</div>
														</div>

													</div>
												</div>
											</div>

										</div>
									</div>
		
								</div>
							</div>

						</div>
					</div>

					</FORM>

				</div>
				<div class="col-12 col-md-4">
				</div>
			</div>
		</div>
	</section>

<?php
	$this->endSection('content'); 

	$rs_categorias = (isset($rs_categorias) ? $rs_categorias : []);
?>

<?php $time = time(); ?>
<?php $this->section('headers'); ?>

	<style>
		.table-bordered>:not(caption)>* {
			border-color: #ffffff !important;
		}
		.table td {
			border-color: #ffffff !important;
		}
	</style>
	<style>
		.box-file-upload{
			/*padding: 0.5rem 1.0rem !important;*/
			/*background: #f8f9fa !important;*/
			/*background: #FAFAFA !important;*/
			/*color: #000000 !important;*/
			/*font-size: .90rem !important;*/
			/*height: calc(4.3em + 1.5rem + 2px) !important;*/
			/*border-radius: 30px !important;*/
			/*border: 1.5px solid #e79c32 !important;*/
			margin-bottom: 0.75rem;
			display: flex;
			/*gap: 1px;*/
		}
		.box-file-upload figure{ cursor: pointer; margin: 0 !important; text-align: center; }
		.box-file-upload figure img{ cursor: pointer; margin: 0 auto !important; width: 60% !important; }
		.box-file-upload .img-preview{
			/*padding: 0.5rem 1.0rem !important;*/
			width: 60%;
			height: calc(4.3em + 1.5rem + 2px) !important;
			background: #FAFAFA !important;
			border-top-left-radius: 30px;
			border-bottom-left-radius: 30px;
			/*border: 1.5px solid #e79c32 !important;*/
		}
		.box-file-upload .txt-click{
			position: relative;
			cursor: pointer;
			padding: 0.5rem 1.0rem !important;
			width: 40%;
			height: calc(4.3em + 1.5rem + 2px) !important;
			background: #e79c32 !important;
			border-top-right-radius: 30px;
			border-bottom-right-radius: 30px;
			/*border: 1.5px solid #e79c32 !important;*/
			/*border-left: 0px solid #e79c32 !important;*/
			font-size: .90rem !important;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.docto-avatar-bg {
			cursor: pointer;
			/*width: 100%;*/
			/*height: 100%;*/
			/*box-sizing: border-box;*/
			/*border-radius: 100%;*/
			background-size: cover;
			background-position: center;
			/*border: 4px solid #e79c32;*/
			/*box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.2);*/
			/*transition: all ease-in-out .3s;*/

			/*padding: 0.5rem 1.0rem !important;*/
			width: 100%;
			height: 100%;
			height: calc(4.3em + 1.5rem + 2px) !important;
			/*background: #FAFAFA !important;*/
			border-top-left-radius: 30px;
			border-bottom-left-radius: 30px;
			border: 1.5px solid #e79c32 !important;
			display: block;
		}


		/*.box-featured{}*/
		/*.box-featured .item{ text-align: center; }*/
		/*.box-featured .item .itemIcon{*/
		/*	height: 120px;*/
		/*	width: 120px;*/
		/*	*/
		/*	border-radius: 50%;*/
		/*	cursor: pointer;*/
		/*	background: #e1e1e1;*/
		/*	box-shadow: 5px 3px 5px 0px rgb(189 148 90 / 37%);*/
		/*	/*border: 1px solid white;*/*/
		/*	margin-bottom: 10px;*/
		/*	display: flex;*/
		/*	justify-content: center;*/
		/*	align-items: center;*/
		/*}*/
		/*.box-featured .item .itemIcon:hover{*/
		/*	background: #ffffff;*/
		/*}*/
		/*.box-featured .item .itemIcon img{*/
		/*	max-width: 60%;*/
		/*}*/

		/*.card-destaque{*/
		/*	height: 140px;*/
		/*	background: #fff7f1;*/
		/*	/* border: 0px; */*/
		/*	border-radius: .5rem;*/
		/*	border: 1px solid #ffc08f;*/
		/*	background: #e1e1e1;*/
		/*	box-shadow: 5px 3px 5px 0px rgb(189 148 90 / 37%);*/
		/*	border: 1px solid white;*/
		/*}*/
		/*.card-plus{*/
		/*	height: 460px;*/
		/*	background: #fff7f1;*/
		/*	/* border: 0px; */*/
		/*	border-radius: .5rem;*/
		/*	border: 1px solid #ffc08f;*/
		/*	background: #e1e1e1;*/
		/*	box-shadow: 5px 3px 5px 0px rgb(189 148 90 / 37%);*/
		/*	border: 1px solid white;*/
		/*}*/
		/*.card-patrocinador{*/
		/*	height: 280px;*/
		/*	background: #d3d3d3;*/
		/*	/* border: 0px; */*/
		/*	border-radius: .5rem;*/
		/*	border: 3px solid #ffffff;*/
		/*	background: #e1e1e1;*/
		/*	box-shadow: 5px 3px 5px 0px rgb(189 148 90 / 37%);*/
		/*	border: 1px solid white;*/
		/*}*/
	</style>

	<style>
		/*.mb-ST { margin-bottom: 32px !important; }*/
		/*.btn-primary {*/
		/*	color: #fff;*/
		/*	background-color: var(--blue-poravo);*/
		/*	border-color: var(--blue-poravo);*/
		/*	border-radius: 24px;*/
		/*}*/
		/*.btn-secondary {*/
		/*	color: #fff;*/
		/*	border-radius: 24px;*/
		/*}*/
		/*.btn {*/
		/*	padding: 0.9rem 26px !important;*/
		/*}*/

		/*.form-control {*/
		/*	border: 1px solid #F2F2F2;*/
		/*	border-radius: 8px;*/
		/*	padding: 0.9rem !important;*/
		/*	background-color: #F2F2F2;*/
		/*}*/
		.form-control-validate{
			font-size: 3rem;
			text-align: center;
			font-weight: bold;
		}
		.form-control-validate.error {
			border: 1px solid #f1416c;
		}

		.form-error{
			margin-top: 2px;
			background-color: #ffd8d8;
			padding: 2px 16px;
			font-size: .8rem;
			color: red;
			border-radius: 30px;
		}

		.text-error-validacao{
			color: #f1416c;
			margin-right: 16px;
		}


		.content-wrapper{
			min-height: 100vh;
			/*border: 1px dotted red;*/
		}
		.box-content-left{
			z-index: 1;
			position: fixed;
			width: 500px !important;
			background-color: rgba(245,248,250,.5)!important;
			box-shadow: 0 .1rem 1rem .25rem rgba(0,0,0,.05)!important;
			min-height: 100vh;
		}
		.box-content-right{
			width: calc(100% - 500px) !important;
			/*background-color: #f3f3f3;*/
			margin-left: 500px;
		}
		.naveg-logotipo{
			display: flex;
			/*justify-content: center;*/
			margin: 60px 0 30px 0;
		}
		.naveg-logotipo img{
			width: 200px !important;	
		}
		.naveg-steps{
			display: flex;
			/* justify-content: center; */
			flex-direction: column;
			/* align-items: center; */
			margin: 0 auto;
		}
		.naveg-steps .naveg-steps-item{
			display: flex;
			margin: 30px 0;
			line-height: 1;
		}
		.naveg-steps .naveg-steps-item .steps-icon{
			transition: color .2s ease,background-color .2s ease;
			background-color: #04c8c8;
			background-color: #1fb7f0;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: color .2s ease,background-color .2s ease;
			width: 40px;
			height: 40px;
			border-radius: .475rem;
			background-color: #dcfdfd;
			background-color: rgb(31 183 240 / 20%);
			background-color: #e79c32;
			margin-right: 1.5rem;
		}
		.naveg-steps .naveg-steps-item.current .steps-icon{
			background-color: #04c8c8;
			background-color: #1fb7f0;
			background-color: #00b37f;
		}
		.naveg-steps .naveg-steps-item.current .steps-icon .stepper-check{ color: #FFF; }
		.naveg-steps .naveg-steps-item .steps-icon .steps-checked {
		}
		.naveg-steps .naveg-steps-item .steps-icon .steps-number {
			font-size: 1.35rem;
			font-weight: 600;
			color: #04c8c8 !important;
			color: #FFFFFF !important;
		}
		.naveg-steps .naveg-steps-item.current .steps-icon .steps-number {
			color: #FFFFFF !important;
		}
		.naveg-steps .naveg-steps-item .steps-label{
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
		.naveg-steps .naveg-steps-item .steps-label .steps-title{
			color: #3f4254;
			font-weight: 600;
			font-size: 1.25rem;
			margin-bottom: .3rem;
		}
		.naveg-steps .naveg-steps-item .steps-label .steps-desc{ color: #b5b5c3; }

		.content-step{ display:none; }
		.content-step.current{ display:flex !important; }
		.content-itens{ margin-top: 60px; }
		.content-itens .content-item-box{
			border-radius: 0.475rem;
			min-height: 130px;
			border-width: 1px;
			border-style: dashed;
			color: #04c8c8;
			border-color: #b5b5c3;
			background-color: rgb(255,255,255,0) !important;
			padding: 1.75rem;
			cursor: pointer;
		}
		.content-itens .content-item-box.active{
			border-radius: 0.475rem;
			min-height: 130px;
			border-width: 1px;
			border-style: dashed;
			color: #04c8c8;
			border-color: #1fb7f0;
			background-color: rgb(31 183 240 / 10%) !important;
			padding: 1.75rem;
		}
		.content-actions{
			margin-top: 60px;
		}

		.svg-icon.svg-icon-3x svg {
			height: 3rem!important;
			width: 3rem!important;
		}


		.input-tempo-musica{
			font-size: 2rem !important;
			padding: 0rem 1.0rem !important;
			line-height: 1 !important;
			height: 47.11px !important;
			font-weight: bold !important;
			text-align: center !important;	
			color: #ffffff !important;
			background-color: #f1790f !important;
			border-color: #f1790f !important;
		}


		.personal-image {
			text-align: center;
		}
		.personal-image input[type="file"] {
			display: none;
		}
		.personal-figure {
			position: relative;
			width: 120px;
			height: 120px;
			margin: 0;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.personal-avatar {
			cursor: pointer;
			width: 100%;
			height: 100%;
			box-sizing: border-box;
			border-radius: 100%;
			background-color: #e79c32;
			border: 4px solid transparent;
			box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.2);
			transition: all ease-in-out .3s;
		}
		.personal-avatar:hover {
			box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.5);
		}
		.personal-avatar-bg {
			cursor: pointer;
			width: 112px;
			height: 112px;
			box-sizing: border-box;
			border-radius: 100%;
			background-size: contain;
			background-position: center;
			border: 4px solid #e79c32;
			box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.2);
			transition: all ease-in-out .3s;
		}
		.personal-figcaption {
			cursor: pointer;
			position: absolute;
			top: 0px;
			width: inherit;
			height: inherit;
			border-radius: 100%;
			opacity: 0;
			background-color: rgba(0, 0, 0, 0);
			transition: all ease-in-out .3s;
		}
		.personal-figcaption:hover {
			opacity: 1;
			background-color: rgba(0, 0, 0, .5);
		}
		.personal-figcaption > img {
			margin-top: 32.5px;
			width: 50px;
			height: 50px;
		}









		@media only screen and (max-width: 991px){
			main { padding: 0 !important; }
			.naveg-steps .naveg-steps-numbers{
				display: flex !important;
			}
			.naveg-logotipo {
				display: block !important;
				text-align: center !important;
			}
			.naveg-steps .naveg-steps-item .steps-icon {
				width: 50px !important;
				height: 50px !important;
				margin-right: 1.5rem;
			}
			.naveg-steps .naveg-steps-item .steps-label {
				display: none !important;
			}
			.content-wrapper {
				margin-top: 0vh !important;
				min-height: 1vh !important;
				height: 100% !important;
				flex-direction: column !important;
			}
			.title-step{ font-size: 1.5rem !important; text-align: center !important; }
			.box-content-left{ 
				position: relative !important;
				width: 100% !important;
				height: 100% !important;
				min-height: 10vh !important;
				margin-bottom: 30px !important;
			}
			.box-content-right{
				width: calc(100% - 0px) !important;
				margin-left: 0px !important;
			}
			.form-control-validate{
				font-size: 2.5rem !important;
				padding: .5rem 0.1rem !important;
			}
		}

		.personal-image-header {
			text-align: center;
		}
		.personal-image-header label {
			margin: 0 !important;
		}
		.personal-figure-header {
			position: relative;
			width: 42px;
			height: 42px;
			margin: 0;
		}
		.personal-avatar-header {
			cursor: pointer;
			width: 100%;
			height: 100%;
			box-sizing: border-box;
			border-radius: 100%;
			background-color: #e79c32;
			border: 4px solid transparent;
			box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.2);
			transition: all ease-in-out .3s;
		}
		.personal-avatar-header:hover {
			box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.5);
		}
		.personal-figcaption-header {
			cursor: pointer;
			position: absolute;
			top: 0px;
			width: inherit;
			height: inherit;
			border-radius: 100%;
			opacity: 0;
			background-color: rgba(0, 0, 0, 0);
			transition: all ease-in-out .3s;
		}
		.personal-figcaption-header:hover {
			opacity: 1;
			background-color: rgba(0, 0, 0, .5);
		}
		.personal-figcaption-header > img {
			margin-top: 32.5px;
			width: 50px;
			height: 50px;
		}
	</style>

<?php $this->endSection('headers'); ?>

<?php $this->section('scripts'); ?>

	<!-- VueJs -->
	<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.22.0/axios.min.js" integrity="sha512-m2ssMAtdCEYGWXQ8hXVG4Q39uKYtbfaJL5QMTbhl2kc6vYyubrKHhr6aLLXW4ITeXSywQLn1AhsAaqrJl8Acfg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="assets/plugins/flatpickr/flatpickr-locale-br.js"></script>
	<!-- <script src="assets/painel/js/custom/documentation/forms/flatpickr-locale-br.js"></script> -->

	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/hmac-md5.min.js"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/md5.min.js" integrity="sha512-ENWhXy+lET8kWcArT6ijA6HpVEALRmvzYBayGL6oFWl96exmq8Fjgxe2K6TAblHLP75Sa/a1YjHpIZRt+9hGOQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->


	<!-- Sweet Alert -->
	<link href="assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
	<script src="assets/plugins/sweet-alert2/sweetalert2.min.js"></script>

	<script>
		let STR_EVENT_HASHKEY = '<?php echo( $event_hashkey ); ?>';
		let STR_GRUPO_HASHKEY = '<?php echo( $grp_hashkey ); ?>';
		let LIST_FUNCOES = <?php echo( json_encode($list_rs_funcoes) ); ?>;
		let LIST_CATEGORIAS = <?php echo( json_encode($list_rs_categorias) ); ?>;
		let LIST_FUNC_OBRIGATORIA = <?php echo( json_encode($list_rs_func_obrig) ); ?>;
		let LIST_PARTICIPANTES = <?php echo( json_encode($rs_partc_cadastrados) ); ?>;
		let LIST_PARTC_LIMITES = <?php echo( json_encode($rs_partc_limites) ); ?>;
		let LIST_CONFIG_FIELDS = <?php echo( json_encode($rs_config_fields) ); ?>;

		function converterParaMinutosESegundos(segundos) {
			var minutos = Math.floor(segundos / 60);
			var segundosRestantes = segundos % 60;
			return minutos +":"+ segundosRestantes;
		}
	</script>

	<script>
	$(document).ready(function () {
		$('.flatpickr_date').flatpickr({
			"locale": "pt",
			dateFormat:"d/m/Y",
			allowInput: true
		});		
	});
	</script>

	<script type="text/javascript" src="assets/vue/utils.js?t=<?= $time ?>"></script>
	<script type="text/javascript" src="assets/vue/inscricoes-participantes.js?t=<?= $time ?>"></script>

<?php $this->endSection('scripts'); ?>