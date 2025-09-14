<?php 
	$this->extend('templates/template_default');
	$this->section('content'); 

	$event_hashkey = (isset($rs_event->event_hashkey) ? $rs_event->event_hashkey : "");
	$event_titulo = (isset($rs_event->event_titulo) ? $rs_event->event_titulo : "");

	$grevt_id = (isset($rs_event->grp_id) ? $rs_event->grp_id : "");
	$grp_hashkey = (isset($rs_event->grp_hashkey) ? $rs_event->grp_hashkey : "");

	//print('<pre style="height: 140px; font-size: .65rem; line-height: 1.4;">');
	//print_r($rs_group);
	//print('</pre>');

	//$list_rs_categorias = (isset($rs_categorias) ? $rs_categorias->getResult() : []);
	//$list_rs_func_obrig = (isset($list_rs_func_obrig) ? $list_rs_func_obrig : []);
	//$list_rs_funcoes = (isset($rs_funcoes) ? $rs_funcoes->getResult() : []);

	$rs_corgf_cadastradas = (isset($rs_corgf_cadastradas) ? $rs_corgf_cadastradas->getResult() : []);

	$list_rs_formatos = (isset($rs_formatos) ? $rs_formatos : []);
	$list_rs_coreografos = (isset($rs_coreografos) ? $rs_coreografos : []);

	$evcfg_seletiva = (int)((isset($rs_event_config->evcfg_seletiva) ? $rs_event_config->evcfg_seletiva : "0"));
	$evcfg_max_coreogf_grupo = (int)((isset($rs_event_config->evcfg_max_coreogf_grupo) ? $rs_event_config->evcfg_max_coreogf_grupo : "1"));
	$evcfg_perm_bailarino_grupos = (int)((isset($rs_event_config->evcfg_perm_bailarino_grupos) ? $rs_event_config->evcfg_perm_bailarino_grupos : "1"));

	$evcfg_config_limites = [
		'seletiva' => $evcfg_seletiva,
		'maximo_coreografias' => $evcfg_max_coreogf_grupo,
		'perm_bailarino_grupo' => $evcfg_perm_bailarino_grupos,
	];
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
						<input type="text" name="event_hashkey" id="event_hashkey" class="form-control" ref="event_hashkey" v-model="fields.event_hashkey" value="<?php echo((isset($event_hashkey) ? $event_hashkey : ""));?>" />

						<input type="text" name="grp_id" id="grp_id" class="form-control" ref="grp_id" v-model="fields.grp_id" value="<?php echo((isset($grevt_id) ? $grevt_id : ""));?>" />
						<input type="text" name="grp_hashkey" id="grp_hashkey" class="form-control" ref="grp_hashkey" v-model="fields.grp_hashkey" value="<?php echo((isset($grp_hashkey) ? $grp_hashkey : ""));?>" />

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
												$w_data['step'] = '3'; // COREOGRAFIAS
												$incMenuLateral = view('inscricoes/menu-lateral', $w_data);
												echo( $incMenuLateral );
											?>

										</div>
										<div class="col-12 col-md-9">

											<!-- Step 3 : COREOGRAFIAS -->
											<div class="h-100" v-show="step == 3" >
												<div class="content-step current justify-content-center align-items-center flex-column h-100">
													<div class="container">
														<div class="row justify-content-center">
															<div class="col-11 col-md-11">
																<div>
																	<h2 class="fw-bolder text-dark title-step">Coreografias</h2>
																	<input type="text" name="corgf_hashkey" id="corgf_hashkey" class="" v-model="fields.corgf_hashkey" value="" />
																	<!-- <div class="text-muted fs-6 text-center text-md-start">Informe que irão integrar ao grupo.</div> -->
																</div>
																<div class="content-itens">
																	<div class="row">
																		<div class="col-12 col-md-12">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_titulo">Nome da Coreografia</label>
																					<input type="text" name="corgf_titulo" id="corgf_titulo" class="form-control" v-model="fields.corgf_titulo" value="" />
																				</div>
																				<div class="form-error" v-if="error.corgf_titulo.length"><small>{{ error.corgf_titulo }}</small></div>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_modl_id">Modalidade</label>
																					<select class="form-select" name="corgf_modl_id" id="corgf_modl_id" v-model="fields.corgf_modl_id">
																						<option value="" translate="no">- selecione -</option>
																						<?php
																						if( isset($rs_modalidades)){
																							foreach ($rs_modalidades->getResult() as $row) {
																								$modl_id = ($row->modl_id);
																								$modl_titulo = ($row->modl_titulo);
																								$selected = '';
																							?>
																								<option value="<?php echo($modl_id); ?>" <?php echo($selected); ?> translate="no"><?php echo($modl_titulo); ?></option>
																						<?php
																							}
																						}
																						?>
																					</select>
																				</div>
																				<div class="form-error" v-if="error.corgf_modl_id.length"><small>{{ error.corgf_modl_id }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_formt_id">Formato</label>
																					<select class="form-select" name="corgf_formt_id" id="corgf_formt_id" v-model="fields.corgf_formt_id" v-on:change="selectFormato()">
																						<option value="" translate="no">- selecione -</option>
																						<option value="" translate="no" v-for="(value, key, index) in lista_formatos" :value='value.formt_id '>{{value.formt_titulo}}</option>
																					</select>
																				</div>
																				<div class="form-error" v-if="error.corgf_formt_id.length"><small>{{ error.corgf_formt_id }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_categ_id">Categoria</label>
																					<select class="form-select" name="corgf_categ_id" id="corgf_categ_id" v-model="fields.corgf_categ_id" v-on:change="selectCategCoreografia()">
																						<option value="" translate="no">- selecione -</option>
																						<?php
																						if( isset($rs_categorias)){
																							foreach ($rs_categorias->getResult() as $row) {
																								$categ_id = ($row->categ_id);
																								$categ_titulo = ($row->categ_titulo);
																								$selected = '';
																							?>
																								<option value="<?php echo($categ_id); ?>" <?php echo($selected); ?> translate="no"><?php echo($categ_titulo); ?></option>
																						<?php
																							}
																						}
																						?>
																					</select>
																				</div>
																				<div class="form-error" v-if="error.corgf_categ_id.length"><small>{{ error.corgf_categ_id }}</small></div>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-12 col-md-4 d-none">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_coreografo">Coreógrafo</label>
																					<select class="form-select" name="corgf_coreografo" id="corgf_coreografo" v-model="fields.corgf_coreografo">
																						<option value="" translate="no">- selecione -</option>
																						<option value="" translate="no" v-for="(value, key, index) in lista_coreografos" :value='value.partc_id'>{{value.partc_nome}}</option>
																					</select>
																				</div>
																				<div class="form-error" v-if="error.corgf_coreografo.length"><small>{{ error.corgf_coreografo }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_musica">Música</label>
																					<input type="text" name="corgf_musica" id="corgf_musica" class="form-control" v-model="fields.corgf_musica" value="" />
																				</div>
																				<div class="form-error" v-if="error.corgf_musica.length"><small>{{ error.corgf_musica }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-4">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_compositor">Compositor</label>
																					<input type="text" name="corgf_compositor" id="corgf_compositor" class="form-control" v-model="fields.corgf_compositor" value="" />
																				</div>
																				<div class="form-error" v-if="error.corgf_compositor.length"><small>{{ error.corgf_compositor }}</small></div>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-12 col-md-6">
																			<div class="form-group">
																				<div>
																					<label class="form-label">MP3 da Música <small style="color: #de700e;" v-show="fields.corgf_tempo_max.length > 0">(tempo máximo permitido {{fields.corgf_tempo_max}})</small></label>
																					<input type="file" name="fileMusicaMP3" id="fileMusicaMP3" class="form-control files" accept=".mp3" @change="selecionarArquivo">
																				</div>
																				<div class="form-error" v-if="error.corgf_musica_file.length"><small>{{ error.corgf_musica_file }}</small></div>
																			</div>
																		</div>
																		<div class="col-12 col-md-2">
																			<div class="form-group">
																				<label class="form-label" for="tempo_musica">Tempo</label>
																				<input type="text" name="tempo_musica" id="tempo_musica" class="form-control input-tempo-musica" value="" readonly="readonly" onfocus="this.blur();" v-model="fields.corgf_tempo" v-bind:class="{ 'error': error.corgf_musica_file.length }" />
																			</div>
																		</div>
																	</div>

																	<div class="row" v-show="evcfg_config_limites.seletiva == 1">
																		<div class="col-12 col-md-12">
																			<div class="form-group">
																				<div>
																					<label class="form-label" for="corgf_evcfg_seletiva">Link da Coreografia (Youtube)</label>
																					<input type="text" name="corgf_evcfg_seletiva" id="corgf_evcfg_seletiva" class="form-control" v-model="fields.corgf_evcfg_seletiva" value="" />
																				</div>
																				<div class="form-error" v-if="error.corgf_evcfg_seletiva.length"><small>{{ error.corgf_evcfg_seletiva }}</small></div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-12 col-md-12">
																			<div class="form-group">
																				<label class="form-label" for="corgf_observacao">Observação</label>
																				<textarea type="text" name="corgf_observacao" id="corgf_observacao" class="form-control" style="height: 100px !important;" v-model="fields.corgf_observacao"></textarea>
																			</div>
																		</div>
																	</div>
																</div>

																<div class="row justify-content-center mt-2" v-show="lista_coreografos.length > 0">
																	<div class="col-12 col-md-12">
																		<div style="padding: 12px; border-radius: 6px; background-color: rgb(243 243 243); border: 1px solid gray;">
																			<h4 style="color: #000000; font-size: 1.2rem;">Selecione o elenco de coreógrafos</h4>
																			<div class="table-box table-responsive mt-2">
																				<table id="example2" class="display nowrap table table-striped table-bordered m-0" style="width:100%">
																					<thead>
																						<tr v-for="(value, key, index) in lista_coreografos" :key="index">
																							<td class="text-center" style="width: 48px;">
																								<div><input type="checkbox" :value='value.partc_id' v-model="fields.corgf_coreografo" @change="handleCheckboxChangeCor({partc_id : value.partc_id})"></div>
																							</td>
																							<td class="d-none">
																								avatar do participante
																							</td>
																							<td>
																								<div>{{index}} {{value.partc_nome}}</div>
																							</td>
																							<td style="width: 200px;">
																								<div><small>cpf:</small> {{value.partc_documento}}</div>
																							</td>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																	</div>
																</div>

																<textarea type="text" name="lista_participantes" id="lista_participantes" class="form-control d-none" v-model="fields.participantes_elenco_json" style="height: 60px;"></textarea>

																<div class="row justify-content-center mt-2" v-show="fields.participantes_elenco.length > 0">
																	<div class="col-12 col-md-12">
																		<div style="padding: 12px; border-radius: 6px; background-color: rgb(243 243 243); border: 1px solid gray;">
																			<h4 style="color: #000000; font-size: 1.2rem;">Selecione o elenco de bailarinos</h4>
																			<div class="table-box table-responsive mt-2">
																				<table id="example2" class="display nowrap table table-striped table-bordered m-0" style="width:100%">
																					<thead>
																						<tr v-for="(value, key, index) in fields.participantes_elenco" :key="index">
																							<td class="text-center" style="width: 48px;">
																								<div>
																								<input 
																									type="checkbox" 
																									:value='value.partc_id' 
																									v-model="fields.coreografia_elenco" 
																									@change="handleCheckboxChangeElenc({partc_id : value.partc_id}, $event)"
																									:ref="'ID' + value.partc_id" 
																									:id="'ID' + value.partc_id" 
																								 /></div>
																							</td>
																							<td class="d-none">
																								avatar do participante
																							</td>
																							<td>
																								<div>{{index}} {{value.partc_nome}}</div>
																							</td>
																							<td style="width: 200px;">
																								<div><small>cpf:</small> {{value.partc_documento}}</div>
																							</td>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																	</div>
																</div>

																<textarea type="text" name="lista_participantes_elenco" id="lista_participantes_elenco" class="form-control d-none" v-model="fields.coreografia_elenco_json" style="height: 60px;"></textarea>
																
																<div class="row justify-content-center mt-2" v-show="fields.coreografia_elenco_all.length > 0">
																	<div class="col-12 col-md-12">
																		<div style="padding: 12px; border-radius: 6px; background-color: rgb(222 251 227); border: 1px solid gray;">
																			<h4 style="color: #000000; font-size: 1.2rem;">Elenco Selecionado</h4>
																			<div class="table-box table-responsive mt-2">
																				<table id="example2" class="display nowrap table table-striped table-bordered m-0" style="width:100%">
																					<thead>
																						<tr v-for="(value, key, index) in fields.coreografia_elenco_all" :key="index">
																							<td>
																								<div>{{index}} {{value.partc_nome}}</div>
																							</td>
																							<td style="width: 180px;">
																								<div>{{value.func_titulo}}</div>
																							</td>
																							<td style="width: 160px;">
																								<div><small>cpf:</small> {{value.partc_documento}}</div>
																							</td>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																	</div>
																</div>

																<div class="mt-3" v-show="editar_coreografia == 0">
																	<button type="button" class="btn btn-warning" v-on:click="SalvarCoreografia" :disabled='corgfBTNDisabled'>Adicionar Nova Coreografia</button>
																	<!-- <a href="javascript:;" class="btn btn-warning" v-on:click="addNovaCoreografia()" :disabled='corgfBTNDisabled'>Adicionar Nova Coreografia</a> -->
																</div>
																<div class="mt-3" v-show="editar_coreografia == 1">
																	<button type="button" class="btn btn-warning" v-on:click="SalvarCoreografia" :disabled='corgfBTNDisabled'>Salvar Alterações</button>
																	<!-- <a href="javascript:;" class="btn btn-warning" v-on:click="addNovaCoreografia()" :disabled='corgfBTNDisabled'>Adicionar Nova Coreografia</a> -->
																</div>

																<div class="row justify-content-center mt-5" v-show="lista_corf_cadastradas.length > 0">
																	<div class="col-12 col-md-12">
																		<div style="padding: 12px; border-radius: 6px; background-color: rgb(221 221 221);">
																			<h3 style="color: #000000;">Coreografias Cadastradas</h3>
																			<div class="table-box table-responsive">
																				<table id="example2" class="display nowrap table table-striped table-bordered m-0" style="width:100%">
																					<thead>
																						<tr v-for="(value, key, index) in lista_corf_cadastradas">
																							<td>
																								<div>{{value.corgf_titulo}}</div>
																							</td>
																							<td><div>{{value.modl_titulo}}</div></td>
																							<td><div>{{value.formt_titulo}}</div></td>
																							<td><div>{{value.categ_titulo}}</div></td>
																							<td>
																								<div class="d-flex justify-content-center" style="gap: 10px">
																									<a href="javascript:;" style="font-size: 1.25rem; color: red;" v-on:click="excluirCoreografia({hashkey: value.corgf_hashkey})"><i class="far fa-times-circle"></i></a>
																									<a href="javascript:;" v-on:click="loadEditCoreografia({hashkey: value.corgf_hashkey})" style="font-size: 1.25rem; color: #18b918;"><i class="fas fa-edit"></i></a>
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
																				<a href="javascript:;" class="btn btn-secondary" v-on:click="prevStep(2)">Anterior</a>
																			</div>
																		</div>
																		<div class="col-8 col-md-4">
																			<!-- <div class="d-grid"> -->
																			<!-- 	<button type="button" class="btn btn-primary" v-on:click="SendNextCobranca()" :disabled='btnDisabledContinue'>Continuar</button> -->
																			<!-- </div> -->

																			<div class="d-grid">
																				<button type="button" class="btn btn-primary" v-on:click="SendNextCobranca()" data-bs-toggle="modal" data-bs-target="#modal_autorizacoes">Continuar</button>
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
		.input-tempo-musica.error{
			background-color: #ff0000 !important;
			border-color: #ff0000 !important;
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



<?php $this->section('modals'); ?>

	<div class="modal fade" tabindex="-1" id="modal_autorizacoes">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Termos e Autorizações do Festival</h5>
					<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
						<span class="svg-icon svg-icon-2x"></span>
					</div>
				</div>
				<div class="modal-body" style="max-height: 70vh; overflow: auto;">
					<div class="box-text-autorizacoes">
						<?php
						if( isset($rs_autorizacoes) ){
							foreach ($rs_autorizacoes as $keyParent => $valParent ) {
								$titulo = $valParent['titulo'];
								$dados_itens = $valParent['dados'];
						?>
						<div class="table-box table-responsive">
							<h3><?php echo($titulo); ?></h3>
							<table class="display table table-striped table-bordered" style="width:100%">
								<tbody>
								<?php
									$count = 0;
									foreach ($dados_itens as $row) {
										$count++;
										$autz_id = ($row->autz_id);
										//$autz_parent_id = (int)$row->autz_parent_id;
										//$autz_titulo_parent = $row->autz_titulo_parent;
										//$autz_hashkey = $row->autz_hashkey;
										$autz_titulo = $row->autz_titulo;
										$autz_descricao = $row->autz_descricao;
										//if($autz_parent_id == 0){
										//	$autz_titulo = '';
										//	$autz_titulo_parent = '<strong>'. ($row->autz_titulo) .'</strong>';
										//}else{
										//	$autz_titulo_parent = '';
										//}
									?>
										<tr class="trRow" style="background-color: #e5ffe5;">
											<td class="text-center" style="width:70px;">
												
												<!-- <input type="checkbox" name="chkAutorizacao[]" id="chkAutorizacao_<?php echo($autz_id); ?>"  class="aut_check chkAutorizacao" value="<?php echo($autz_id); ?>" /> -->

												<a href="javascript:;" style="font-size: 1.25rem; color: #000;" data-bs-toggle="modal" data-bs-target="#modal_autorizacoes_full"><i class="far fa-square"></i></a>

												<!-- <a href="javascript:;" style="font-size: 1.25rem; color: #05ac04;" data-bs-toggle="modal" data-bs-target="#modal_autorizacoes_full"><i class="far fa-check-square"></i></a> -->
											</td>
											<td>
												<?php echo($autz_descricao); ?>
											</td>
										</tr>
									<?php
									}
								?>
								</tbody>
							</table>
						</div>
						<?php
							}
						}
						?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="modal_autorizacoes_full" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Autorizo o uso de dados conforme a LPG</h5>
					<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
						<span class="svg-icon svg-icon-2x"></span>
					</div>
				</div>
				<div class="modal-body" style="min-height:70vh; max-height: 70vh; overflow: auto;">

					<p>Lorem ipsum dolor sit amet. Est iste quis rem soluta rerum aut accusantium omnis a adipisci nisi? Et voluptatibus itaque est ducimus porro qui natus doloremque qui voluptas voluptatum et quod ipsam. </p><p>Qui sint fugit et eveniet corrupti qui voluptas dolorem eos autem minima. Ut laudantium dolor hic nisi deleniti id minima quaerat ut aspernatur ratione et eaque dolorum aut temporibus ullam. Ut maiores sunt est repellat voluptatem aut rerum porro et voluptatum explicabo At adipisci magni. </p><p>Et velit voluptas non omnis nemo et totam quam nam esse aperiam? Ex animi ipsum et voluptate explicabo ad nihil corporis. A doloribus quia 33 quibusdam mollitia et voluptatem corrupti qui possimus sint et cumque voluptatem. </p><p>Sed maiores quia aut nihil ipsa est illo dolorem qui sequi tempore eos ipsa quam? Aut animi dolor ex autem repudiandae ut adipisci quia et dignissimos cumque est culpa rerum aut possimus placeat. </p><p>Aut nemo vero eos molestias sapiente qui dignissimos aspernatur est velit dolorem qui quis distinctio. Eum architecto voluptate et itaque voluptate aut accusamus tempora. Qui pariatur dicta non quia enim aut quia sint sed explicabo nobis qui exercitationem ullam eum voluptatem quos eum consequatur tempora? Et recusandae galisum sit sint commodi et atque provident? </p><p>Sit numquam maxime et aspernatur accusamus qui galisum amet sit aspernatur expedita ut placeat iure. Non obcaecati ipsa cum internos pariatur hic laudantium voluptate quo aspernatur iure est alias nobis ab voluptatum minima hic nemo voluptatem. </p><p>Sed omnis optio eum tempora voluptate ut quia unde aut iste tempore ut saepe minima. Non facere nostrum ut cumque soluta est dolorum maxime ab labore atque quo reprehenderit labore ut cumque dolor aut quod facilis! </p><p>Est voluptatum magni sed maiores aspernatur non quae quas cum possimus exercitationem et dicta praesentium qui omnis dolor. Et blanditiis nulla quo consectetur debitis qui excepturi commodi eos accusamus placeat in aspernatur facere ab minus aperiam. Et internos galisum non galisum laudantium qui galisum eligendi. Et molestiae omnis eos possimus saepe aut facere voluptatem eos enim nulla ut eius corporis. </p><p>A tenetur ipsam a voluptatibus magni cum vero nobis At recusandae voluptatum vel reprehenderit modi ea nostrum soluta. Et quasi iure et illum galisum hic veritatis perferendis qui explicabo error non soluta quia sed eveniet sequi. Nam praesentium sequi qui ratione consequatur et molestiae unde qui dignissimos accusantium nam consequuntur unde ut eveniet provident? Ab galisum dolorum qui ipsam minus et vero totam ut velit voluptatem quo error cupiditate est consequatur eaque. </p><p>Aut officia voluptas qui ipsum illo et fugiat repellat est quod quia cum corporis eaque aut aperiam beatae sit incidunt voluptas. Et libero provident ut sunt tempora qui voluptas quibusdam sed expedita quibusdam eos unde perspiciatis aut nemo sunt. Et voluptas vero quo quasi rerum est explicabo neque. Et omnis asperiores et placeat voluptas sit facere modi qui expedita rerum. </p>


				</div>
				<div class="modal-footer">
					<div class="d-flex justify-content-center w-100">
						<div style="margin: 0 10px;">
							<button type="button" class="btn btn-primary">Aceito</button>
						</div>
						<div style="margin: 0 10px;">
							<button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php $this->endSection('modals'); ?>



<?php $this->section('scripts'); ?>

	<style>
		.box-text-autorizacoes{
			 line-height: 1.2;
		}
		.box-text-autorizacoes h3{
			 font-size: 1.25rem;
			 font-weight: 500;
		}
		.aut_check{ 
			height: 16px; 
			width: 16px;
		}
	</style>


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
		let LIST_COREOGRAFOS = <?php echo( json_encode($list_rs_coreografos) ); ?>;
		let LIST_FORMATOS = <?php echo( json_encode($list_rs_formatos) ); ?>;
		let LIST_CATEGORIAS = <?php echo( json_encode($list_rs_categorias) ); ?>;
		//let RS_EVCFG_SELETIVA = <?php echo( $evcfg_seletiva ); ?>;
		let RS_EVCFG_CONFIG_LIMITES = <?php echo( json_encode($evcfg_config_limites) ); ?>;
		let LIST_CORF_CADASTRADAS = <?php echo( json_encode($rs_corgf_cadastradas) ); ?>;
		
		function converterParaMinutosESegundos(segundos) {
			var minutos = Math.floor(segundos / 60);
			var segundosRestantes = segundos % 60;
			return minutos +":"+ segundosRestantes;
		}
		$(document).ready(function () {
			//var tempoTotal = 0;
			//$(document).on('change', '#fileMusicaMP3', function (e) {
			//	console.log('selecionou o arquivo');
			//	var quantidadeDeArquivos = this.files.length;
			//	for (var i = 0; i < quantidadeDeArquivos; i++) {
			//		var esteArquivo = this.files[i];
			//		fileB = URL.createObjectURL(esteArquivo);
			//		var audioElement2 = new Audio(fileB);
			//		audioElement2.setAttribute('src', fileB);
			//		audioElement2.onloadedmetadata = function(e) {
			//			tempoTotal = tempoTotal + parseInt(this.duration);
			//			$("#tempo_musica").val(converterParaMinutosESegundos(tempoTotal));
			//		}
			//	}
			//	tempoTotal = 0;
			//});
		});

		//var quantidadeDeArquivos = itemEl.length;
		//for (var i = 0; i < quantidadeDeArquivos; i++) {
		//	var esteArquivo = itemEl[i];
		//	fileB = URL.createObjectURL(esteArquivo);
		//	var audioElement2 = new Audio(fileB);
		//	audioElement2.setAttribute('src', fileB);
		//	audioElement2.onloadedmetadata = function(e) {
		//		tempoTotal = tempoTotal + parseInt(this.duration);
		//		//$("#musicas").html("Músicas Carregadas: " + quantidadeDeArquivos + " (Tempo Total: " + converterParaMinutosESegundos(tempoTotal) + ")");
		//		$("#tempo_musica").val(converterParaMinutosESegundos(tempoTotal));
		//		//alert("loadedmetadata" + tempoTotal);
		//		//console.log( converterParaMinutosESegundos(tempoTotal) );
		//	}
		//}
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
	<script type="text/javascript" src="assets/vue/inscricoes-coreografias.js?t=<?= $time ?>"></script>

<?php $this->endSection('scripts'); ?>