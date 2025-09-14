<?php
namespace App\Controllers;
use App\Controllers\BaseController;

use \DateTime;
use \DateInterval;

class Inscricoes extends BaseController
{
	
	protected $eventMD = null;
	protected $evcfgMD = null;
	protected $grpMD = null;
	protected $grevtMD = null;
	protected $funcMD = null;
	protected $partcMD = null;
	protected $corgfMD = null;
	protected $crfpaMD = null;
	protected $userMD = null;
	protected $modlMD = null;
	protected $formtMD = null;
	protected $categMD = null;
	protected $cfg = null;

    public function __construct()
    {
        $this->eventMD = new \App\Models\EventosModel();
		$this->evcfgMD = new \App\Models\EventosConfigModel();
		$this->grpMD = new \App\Models\GruposModel();
		$this->grevtMD = new \App\Models\GruposEventosModel();
		$this->funcMD = new \App\Models\FuncoesModel();
		$this->partcMD = new \App\Models\ParticipantesModel();

        $this->corgfMD = new \App\Models\CoreografiasModel();
		$this->crfpaMD = new \App\Models\CoreografiasParticipantesModel();

		$this->userMD = new \App\Models\UsuariosModel();

		$this->modlMD = new \App\Models\ModalidadesModel();
		$this->formtMD = new \App\Models\FormatosModel();
		$this->categMD = new \App\Models\CategoriasModel();

		$this->cfg = new \Config\AppSettings();
		$this->data['cfg'] = $this->cfg;

		$this->data['menu_active'] = 'eventos';

		helper('form');
		helper('text');
    }

	public function inicial( $event_hashkey = "" )
	{
		if( !session()->get('isLoggedInUserInscricao') == TRUE ){
			return $this->response->redirect( site_url('inscricoes/login/'. $event_hashkey) );	
		}else{
			return $this->response->redirect( site_url('inscricoes/grupos/'. $event_hashkey) );		
		}
		exit();


		$user_id = (int)session()->get('inscUser_id');


		/*
		 * -------------------------------------------------------------
		 * Gravando a informações por meio de POST tradicional
		 * -------------------------------------------------------------
		**/
			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Precisa Relacionar o User com o Grupo
				// ---------------------------------------------------------
				$grp_titulo = $this->request->getPost('grp_titulo');
				$grp_responsavel = $this->request->getPost('grp_responsavel');
				$grp_cpf = $this->request->getPost('grp_cpf');
				$grp_telefone = $this->request->getPost('grp_telefone');
				$grp_celular = $this->request->getPost('grp_celular');

				$grp_sm_instagram = $this->request->getPost('grp_sm_instagram');
				$grp_sm_facebook = $this->request->getPost('grp_sm_facebook');
				$grp_sm_youtube = $this->request->getPost('grp_sm_youtube');
				$grp_sm_vimeo = $this->request->getPost('grp_sm_vimeo');

				$grp_redes_sociais = [
					'instagram' => $grp_sm_instagram,
					'facebook' => $grp_sm_facebook,
					'youtube' => $grp_sm_youtube,
					'vimeo' => $grp_sm_vimeo
				];

				$grp_end_cep = $this->request->getPost('grp_end_cep');
				$grp_end_logradouro = $this->request->getPost('grp_end_logradouro');
				$grp_end_numero = $this->request->getPost('grp_end_numero');
				$grp_end_compl = $this->request->getPost('grp_end_compl');
				$grp_end_bairro = $this->request->getPost('grp_end_bairro');
				$grp_end_cidade = $this->request->getPost('grp_end_cidade');
				$grp_end_estado = $this->request->getPost('grp_end_estado');

				//$partc_documento = $this->request->getPost('partc_documento');
				//$partc_nome = $this->request->getPost('partc_nome');
				//$partc_nome_social = $this->request->getPost('partc_nome_social');
				//$partc_genero = $this->request->getPost('partc_genero');
				//$partc_dte_nascto = $this->request->getPost('partc_dte_nascto');
				//$func_id = $this->request->getPost('func_id');
				$lista_participantes = $this->request->getPost('lista_participantes');

				$corgf_titulo = $this->request->getPost('corgf_titulo');
				$corgf_coreografo = $this->request->getPost('corgf_coreografo');
				$corgf_musica = $this->request->getPost('corgf_musica');
				$corgf_compositor = $this->request->getPost('corgf_compositor');
				$corgf_observacao = $this->request->getPost('corgf_observacao');
				$corgf_modl_id = (int)$this->request->getPost('corgf_modl_id');
				$corgf_formt_id = (int)$this->request->getPost('corgf_formt_id');
				$corgf_categ_id = (int)$this->request->getPost('corgf_categ_id');

				$all_fields_post[] = $this->request->getPost();

				$insti_id = 0;

				/*
				 * -------------------------------------------------------------
				 * Recuperamos as informações do Evento
				 * -------------------------------------------------------------
				**/
				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 

					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações do Grupo
					 * -------------------------------------------------------------
					**/
					$data_db = [
						'insti_id' => (int)$insti_id,
						'user_id' => (int)$user_id,
						'grp_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
						'grp_urlpage' => url_title( convert_accented_characters($grp_titulo), '-', TRUE ),
						'grp_titulo' => $grp_titulo,
						'grp_responsavel' => $grp_responsavel,
						'grp_telefone' => $grp_telefone,
						'grp_celular' => $grp_celular,
						'grp_cpf' => $grp_cpf,
						'grp_redes_sociais' => json_encode($grp_redes_sociais),
						'grp_end_cep' => $grp_end_cep,
						'grp_end_logradouro' => $grp_end_logradouro,
						'grp_end_numero' => $grp_end_numero,
						'grp_end_compl' => $grp_end_compl,
						'grp_end_bairro' => $grp_end_bairro,
						'grp_end_cidade' => $grp_end_cidade,
						'grp_end_estado' => $grp_end_estado,
						'grp_dte_cadastro' => date("Y-m-d H:i:s"),
						'grp_dte_alteracao' => date("Y-m-d H:i:s"),
						'grp_ativo' => 1,
					];

					$query_grupo = $this->grpMD
						->where('insti_id', (int)$insti_id)
						->where('user_id', (int)$user_id)
						->where('grp_urlpage', url_title( convert_accented_characters($grp_titulo), '-', TRUE ))
						->limit(1)
						->get();
					if( $query_grupo && $query_grupo->resultID->num_rows == 0 )
					{
						$grp_id = $this->grpMD->insert($data_db);

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações dos participantes
						 * -------------------------------------------------------------
						**/
							if( !empty( $lista_participantes ) ){
								//print '<pre>';
								//print_r( json_decode($lista_participantes) );
								//print '</pre>';
								$lista_participantes_json = json_decode($lista_participantes);
								foreach ($lista_participantes_json as $key => $val) {
									//print '<hr>';
									//print ' | '. $val->partc_documento;
									//print ' | '. $val->partc_nome;
									//print ' | '. $val->partc_nome_social;
									//print ' | '. $val->partc_genero;
									//print ' | '. $val->partc_dte_nascto;
									//print ' | '. $val->partc_idade;
									//print ' | '. $val->partc_categoria;
									//print ' | '. $val->func_id;
									//print ' | '. $val->partc_file_doc_frente;
									//print ' | '. $val->partc_file_doc_verso;
									//print ' | '. $val->partc_file_foto;

									$data_participante_db = [
										'insti_id' => (int)$insti_id,
										'partc_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
										'partc_urlpage' => url_title( convert_accented_characters($val->partc_nome), '-', TRUE ),
										'grp_id' => $grp_id,
										//'func_id' => $func_id,
										'partc_nome' => $val->partc_nome,
										'partc_nome_social' => $val->partc_nome_social,
										'partc_genero' => $val->partc_genero,
										'partc_documento' => $val->partc_documento,
										'partc_dte_nascto' => fct_date2bd($val->partc_dte_nascto),
										'partc_dte_cadastro' => date("Y-m-d H:i:s"),
										'partc_dte_alteracao' => date("Y-m-d H:i:s"),
										'partc_ativo' => 1,
									];

									//if( !empty($file_foto)){
									//	$data_participante_db['partc_file_foto'] = $file_foto;
									//}
									//if( !empty($file_doc_frente)){
									//	$data_participante_db['partc_file_doc_frente'] = $file_doc_frente;
									//}
									//if( !empty($file_doc_verso)){
									//	$data_participante_db['partc_file_doc_verso'] = $file_doc_verso;
									//}

									$query_participante = $this->partcMD
										->where('insti_id', (int)$insti_id)
										->where('grp_id', (int)$grp_id)
										->where('partc_documento', $val->partc_documento)
										->limit(1)
										->get();
									if( $query_participante && $query_participante->resultID->num_rows == 0 )
									{
										$partc_id = $this->partcMD->insert($data_participante_db);
									}
								}
							}

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações de coreografia
						 * -------------------------------------------------------------
						**/
							$data_coreografia_db = [
								'insti_id' => (int)$insti_id,
								'grp_id' => (int)$grp_id,
								'modl_id' => (int)$corgf_modl_id,
								'formt_id' => (int)$corgf_formt_id,
								'categ_id' => (int)$corgf_categ_id,
								'corgf_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
								'corgf_urlpage' => url_title( convert_accented_characters($corgf_titulo), '-', TRUE ),
								'corgf_titulo' => $corgf_titulo,
								'corgf_coreografo' => $corgf_coreografo,
								'corgf_musica' => $corgf_musica,
								//'corgf_tempo' => $corgf_tempo,
								'corgf_compositor' => $corgf_compositor,
								'corgf_observacao' => $corgf_observacao,
								'corgf_dte_cadastro' => date("Y-m-d H:i:s"),
								'corgf_dte_alteracao' => date("Y-m-d H:i:s"),
								'corgf_ativo' => 1,
							];

							//if( !empty($file_musica_mp3)){
							//	$data_db['corgf_musica_file'] = $file_musica_mp3;
							//}

							$query_coreografia = $this->corgfMD
								->where('insti_id', (int)$insti_id)
								->where('grp_id', (int)$grp_id)
								->where('corgf_urlpage', url_title( convert_accented_characters($corgf_titulo), '-', TRUE ))
								->get();
							if( $query_coreografia && $query_coreografia->resultID->num_rows == 0 )
							{
								$corgf_id = $this->corgfMD->insert($data_coreografia_db);
							}
					}

					//print '<pre>';
					//print_r( $all_fields_post );
					//print '<pre>';
					exit();
				}
			}


		$this->eventMD->select('*');
		$this->eventMD->where('event_hashkey', $event_hashkey);
		$this->eventMD->orderBy('event_id', 'DESC');
		$this->eventMD->limit(1);
		$query_event = $this->eventMD->get();
		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		if( $query_event && $query_event->resultID->num_rows >=1 )
		{
			$rs_event = $query_event->getRow();
			$this->data['rs_event'] = $rs_event;


			$insti_id = (int)$rs_event->insti_id;
			$event_id = (int)$rs_event->event_id;

			/*
			 * -------------------------------------------------------------
			 * Informações referente ao grupo e evento
			 * -------------------------------------------------------------
			**/
				$this->grpMD->from('tbl_grupos As GRP', true)
					->select('GRP.*')
					->select('GREVT.grevt_id')
					->join('tbl_grupos_x_eventos AS GREVT', 'GREVT.grp_id = GRP.grp_id', 'INNER')
					->where('GRP.user_id', $user_id)
					->where('GREVT.event_id', $event_id)
					->orderBy('GREVT.grevt_id', 'DESC')
					->limit(1);
				$query_group_evt = $this->grpMD->get();
				if( $query_group_evt && $query_group_evt->resultID->num_rows >=1 )
				{
					$rs_group_evt = $query_group_evt->getRow();
					$this->data['rs_group'] = $rs_group_evt;
				}else{
					/*
					 * -------------------------------------------------------------
					 * Recuperar informações do último grupo criado
					 * -------------------------------------------------------------
					**/
						$this->grpMD->select('*');
						//$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
						$this->grpMD->where('user_id', $user_id);
						$this->grpMD->orderBy('grp_id', 'DESC');
						$this->grpMD->limit(1);
						$query_group = $this->grpMD->get();
						//$query_grupos = $this->grpMD->select_all_by_insti_id();
						if( $query_group && $query_group->resultID->num_rows >=1 )
						{
							$rs_group = $query_group->getRow();
							$this->data['rs_group'] = $rs_group;
						}
				}






			// GRUPOS -- não será necessário esta query
			$this->grpMD->select('*');
			$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
			//$this->grpMD->where('insti_id', (int)$this->session_user_id);
			$this->grpMD->orderBy('grp_titulo', 'ASC');
			$this->grpMD->limit(1000);
			$query_grupos = $this->grpMD->get();
			//$query_grupos = $this->grpMD->select_all_by_insti_id();
			if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
			{
				$this->data['rs_grupos'] = $query_grupos;
			}


			$query_modalidades = $this->modlMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_modalidades && $query_modalidades->resultID->num_rows >=1 )
			{
				$this->data['rs_modalidades'] = $query_modalidades;
			}

			$query_formatos = $this->formtMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_formatos && $query_formatos->resultID->num_rows >=1 )
			{
				$this->data['rs_formatos'] = $query_formatos;
			}

			$this->data['list_rs_categorias'] = [];
			$query_categorias = $this->categMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_categorias && $query_categorias->resultID->num_rows >=1 )
			{
				$rs_categorias = $query_categorias->getResult();
				$this->data['rs_categorias'] = $query_categorias;

				$categorias = [];
				foreach ($rs_categorias as $row) {
					$categoria = [
						'id' => $row->categ_id,
						'titulo' => $row->categ_titulo,
						'idade_min' => (int)$row->categ_idade_min,
						'idade_max' => (int)$row->categ_idade_max,
					];
					$categorias[] = $categoria;
				}
				$this->data['list_rs_categorias'] = $categorias;
			}


			$this->data['list_rs_func_obrig'] = [];
			$query_func_obrig = $this->funcMD
				->select('func_id, func_titulo')
				->where('func_obrigatorio', 1)
				->get();
			if( $query_func_obrig && $query_func_obrig->resultID->num_rows >=1 )
			{
				$rs_func_obrig = $query_func_obrig->getResult();
				$this->data['list_rs_func_obrig'] = $rs_func_obrig;
			}
		}




		// FUNCOES
		$this->funcMD->select('func_id, func_titulo');
		//$this->funcMD->where('insti_id', (int)$rs_event->insti_id);
		$this->funcMD->where('func_ativo', 1);
		$this->funcMD->orderBy('func_titulo', 'ASC');
		$this->funcMD->limit(1000);
		$query_funcoes = $this->funcMD->get();
		if( $query_funcoes && $query_funcoes->resultID->num_rows >=1 )
		{
			$this->data['rs_funcoes'] = $query_funcoes;
		}

		// GENERO SEXUAL
		$this->data['arr_generos'] = $this->cfg->getGeneros();

		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		//if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
		//{
		//	$this->data['rs_grupos'] = $query_grupos;
		//}	

		//var_dump( session()->get('isLoggedInUserInscricao') );
		//exit();
	
		return view('inscricoes/index', $this->data);
	}





	public function grupos( $event_hashkey = "" )
	{
		if( !session()->get('isLoggedInUserInscricao') == TRUE ){
			return $this->response->redirect( site_url('inscricoes/login/'. $event_hashkey) );	
		}
		$user_id = (int)session()->get('inscUser_id');


		/*
		 * -------------------------------------------------------------
		 * Gravando a informações por meio de POST tradicional
		 * -------------------------------------------------------------
		**/
			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Precisa Relacionar o User com o Grupo
				// ---------------------------------------------------------
				$grp_titulo = $this->request->getPost('grp_titulo');
				$grp_responsavel = $this->request->getPost('grp_responsavel');
				$grp_cpf = $this->request->getPost('grp_cpf');
				$grp_telefone = $this->request->getPost('grp_telefone');
				$grp_celular = $this->request->getPost('grp_celular');

				$grp_sm_instagram = $this->request->getPost('grp_sm_instagram');
				$grp_sm_facebook = $this->request->getPost('grp_sm_facebook');
				$grp_sm_youtube = $this->request->getPost('grp_sm_youtube');
				$grp_sm_vimeo = $this->request->getPost('grp_sm_vimeo');

				$grp_redes_sociais = [
					'instagram' => $grp_sm_instagram,
					'facebook' => $grp_sm_facebook,
					'youtube' => $grp_sm_youtube,
					'vimeo' => $grp_sm_vimeo
				];

				$grp_end_cep = $this->request->getPost('grp_end_cep');
				$grp_end_logradouro = $this->request->getPost('grp_end_logradouro');
				$grp_end_numero = $this->request->getPost('grp_end_numero');
				$grp_end_compl = $this->request->getPost('grp_end_compl');
				$grp_end_bairro = $this->request->getPost('grp_end_bairro');
				$grp_end_cidade = $this->request->getPost('grp_end_cidade');
				$grp_end_estado = $this->request->getPost('grp_end_estado');

				/*
				 * -------------------------------------------------------------
				 * Recuperamos as informações do Evento
				 * -------------------------------------------------------------
				**/
				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 

					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações do Grupo
					 * -------------------------------------------------------------
					**/
					$data_db = [
						'insti_id' => (int)$insti_id,
						'user_id' => (int)$user_id,
						'grp_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
						'grp_urlpage' => url_title( convert_accented_characters($grp_titulo), '-', TRUE ),
						'grp_titulo' => $grp_titulo,
						'grp_responsavel' => $grp_responsavel,
						'grp_telefone' => $grp_telefone,
						'grp_celular' => $grp_celular,
						'grp_cpf' => $grp_cpf,
						'grp_redes_sociais' => json_encode($grp_redes_sociais),
						'grp_end_cep' => $grp_end_cep,
						'grp_end_logradouro' => $grp_end_logradouro,
						'grp_end_numero' => $grp_end_numero,
						'grp_end_compl' => $grp_end_compl,
						'grp_end_bairro' => $grp_end_bairro,
						'grp_end_cidade' => $grp_end_cidade,
						'grp_end_estado' => $grp_end_estado,
						'grp_dte_cadastro' => date("Y-m-d H:i:s"),
						'grp_dte_alteracao' => date("Y-m-d H:i:s"),
						'grp_ativo' => 1,
					];

					$query_grupo = $this->grpMD
						->where('insti_id', (int)$insti_id)
						->where('user_id', (int)$user_id)
						->where('grp_urlpage', url_title( convert_accented_characters($grp_titulo), '-', TRUE ))
						->limit(1)
						->get();
					if( $query_grupo && $query_grupo->resultID->num_rows == 0 )
					{
						$grp_id = $this->grpMD->insert($data_db);

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações dos participantes
						 * -------------------------------------------------------------
						**/
							if( !empty( $lista_participantes ) ){
								//print '<pre>';
								//print_r( json_decode($lista_participantes) );
								//print '</pre>';
								$lista_participantes_json = json_decode($lista_participantes);
								foreach ($lista_participantes_json as $key => $val) {
									//print '<hr>';
									//print ' | '. $val->partc_documento;
									//print ' | '. $val->partc_nome;
									//print ' | '. $val->partc_nome_social;
									//print ' | '. $val->partc_genero;
									//print ' | '. $val->partc_dte_nascto;
									//print ' | '. $val->partc_idade;
									//print ' | '. $val->partc_categoria;
									//print ' | '. $val->func_id;
									//print ' | '. $val->partc_file_doc_frente;
									//print ' | '. $val->partc_file_doc_verso;
									//print ' | '. $val->partc_file_foto;

									$data_participante_db = [
										'insti_id' => (int)$insti_id,
										'partc_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
										'partc_urlpage' => url_title( convert_accented_characters($val->partc_nome), '-', TRUE ),
										'grp_id' => $grp_id,
										//'func_id' => $func_id,
										'partc_nome' => $val->partc_nome,
										'partc_nome_social' => $val->partc_nome_social,
										'partc_genero' => $val->partc_genero,
										'partc_documento' => $val->partc_documento,
										'partc_dte_nascto' => fct_date2bd($val->partc_dte_nascto),
										'partc_dte_cadastro' => date("Y-m-d H:i:s"),
										'partc_dte_alteracao' => date("Y-m-d H:i:s"),
										'partc_ativo' => 1,
									];

									//if( !empty($file_foto)){
									//	$data_participante_db['partc_file_foto'] = $file_foto;
									//}
									//if( !empty($file_doc_frente)){
									//	$data_participante_db['partc_file_doc_frente'] = $file_doc_frente;
									//}
									//if( !empty($file_doc_verso)){
									//	$data_participante_db['partc_file_doc_verso'] = $file_doc_verso;
									//}

									$query_participante = $this->partcMD
										->where('insti_id', (int)$insti_id)
										->where('grp_id', (int)$grp_id)
										->where('partc_documento', $val->partc_documento)
										->limit(1)
										->get();
									if( $query_participante && $query_participante->resultID->num_rows == 0 )
									{
										$partc_id = $this->partcMD->insert($data_participante_db);
									}
								}
							}

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações de coreografia
						 * -------------------------------------------------------------
						**/
							$data_coreografia_db = [
								'insti_id' => (int)$insti_id,
								'grp_id' => (int)$grp_id,
								'modl_id' => (int)$corgf_modl_id,
								'formt_id' => (int)$corgf_formt_id,
								'categ_id' => (int)$corgf_categ_id,
								'corgf_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
								'corgf_urlpage' => url_title( convert_accented_characters($corgf_titulo), '-', TRUE ),
								'corgf_titulo' => $corgf_titulo,
								'corgf_coreografo' => $corgf_coreografo,
								'corgf_musica' => $corgf_musica,
								//'corgf_tempo' => $corgf_tempo,
								'corgf_compositor' => $corgf_compositor,
								'corgf_observacao' => $corgf_observacao,
								'corgf_dte_cadastro' => date("Y-m-d H:i:s"),
								'corgf_dte_alteracao' => date("Y-m-d H:i:s"),
								'corgf_ativo' => 1,
							];

							//if( !empty($file_musica_mp3)){
							//	$data_db['corgf_musica_file'] = $file_musica_mp3;
							//}

							$query_coreografia = $this->corgfMD
								->where('insti_id', (int)$insti_id)
								->where('grp_id', (int)$grp_id)
								->where('corgf_urlpage', url_title( convert_accented_characters($corgf_titulo), '-', TRUE ))
								->get();
							if( $query_coreografia && $query_coreografia->resultID->num_rows == 0 )
							{
								$corgf_id = $this->corgfMD->insert($data_coreografia_db);
							}
					}

					//print '<pre>';
					//print_r( $all_fields_post );
					//print '<pre>';
					exit();
				}
			}


		$this->eventMD->select('*');
		$this->eventMD->where('event_hashkey', $event_hashkey);
		$this->eventMD->orderBy('event_id', 'DESC');
		$this->eventMD->limit(1);
		$query_event = $this->eventMD->get();
		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		if( $query_event && $query_event->resultID->num_rows >=1 )
		{
			$rs_event = $query_event->getRow();
			$this->data['rs_event'] = $rs_event;

			$insti_id = (int)$rs_event->insti_id;
			$event_id = (int)$rs_event->event_id;



			/*
			 * -------------------------------------------------------------
			 * Informações referente ao grupo e evento
			 * -------------------------------------------------------------
			**/
				$this->grpMD->from('tbl_grupos As GRP', true)
					->select('GRP.*')
					->select('GREVT.grevt_id')
					->join('tbl_grupos_x_eventos AS GREVT', 'GREVT.grp_id = GRP.grp_id', 'INNER')
					->where('GRP.user_id', $user_id)
					->where('GREVT.event_id', $event_id)
					->orderBy('GREVT.grevt_id', 'DESC')
					->limit(1);
				$query_group_evt = $this->grpMD->get();
				if( $query_group_evt && $query_group_evt->resultID->num_rows >=1 )
				{
					$rs_group_evt = $query_group_evt->getRow();
					$this->data['rs_group'] = $rs_group_evt;
				}else{
					/*
					 * -------------------------------------------------------------
					 * Recuperar informações do último grupo criado
					 * -------------------------------------------------------------
					**/
						$this->grpMD->select('*');
						//$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
						$this->grpMD->where('user_id', $user_id);
						$this->grpMD->orderBy('grp_id', 'DESC');
						$this->grpMD->limit(1);
						$query_group = $this->grpMD->get();
						//$query_grupos = $this->grpMD->select_all_by_insti_id();
						if( $query_group && $query_group->resultID->num_rows >=1 )
						{
							$rs_group = $query_group->getRow();
							$this->data['rs_group'] = $rs_group;
						}
				}






			// GRUPOS -- não será necessário esta query
			$this->grpMD->select('*');
			$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
			//$this->grpMD->where('insti_id', (int)$this->session_user_id);
			$this->grpMD->orderBy('grp_titulo', 'ASC');
			$this->grpMD->limit(1000);
			$query_grupos = $this->grpMD->get();
			//$query_grupos = $this->grpMD->select_all_by_insti_id();
			if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
			{
				$this->data['rs_grupos'] = $query_grupos;
			}


			$query_modalidades = $this->modlMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_modalidades && $query_modalidades->resultID->num_rows >=1 )
			{
				$this->data['rs_modalidades'] = $query_modalidades;
			}

			$query_formatos = $this->formtMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_formatos && $query_formatos->resultID->num_rows >=1 )
			{
				$this->data['rs_formatos'] = $query_formatos;
			}

			$this->data['list_rs_categorias'] = [];
			$query_categorias = $this->categMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_categorias && $query_categorias->resultID->num_rows >=1 )
			{
				$rs_categorias = $query_categorias->getResult();
				$this->data['rs_categorias'] = $query_categorias;

				$categorias = [];
				foreach ($rs_categorias as $row) {
					$categoria = [
						'id' => $row->categ_id,
						'titulo' => $row->categ_titulo,
						'idade_min' => (int)$row->categ_idade_min,
						'idade_max' => (int)$row->categ_idade_max,
					];
					$categorias[] = $categoria;
				}
				$this->data['list_rs_categorias'] = $categorias;
			}


			$this->data['list_rs_func_obrig'] = [];
			$query_func_obrig = $this->funcMD
				->select('func_id, func_titulo')
				->where('func_obrigatorio', 1)
				->get();
			if( $query_func_obrig && $query_func_obrig->resultID->num_rows >=1 )
			{
				$rs_func_obrig = $query_func_obrig->getResult();
				$this->data['list_rs_func_obrig'] = $rs_func_obrig;
			}
		}




		// FUNCOES
		$this->funcMD->select('func_id, func_titulo');
		//$this->funcMD->where('insti_id', (int)$rs_event->insti_id);
		$this->funcMD->where('func_ativo', 1);
		$this->funcMD->orderBy('func_titulo', 'ASC');
		$this->funcMD->limit(1000);
		$query_funcoes = $this->funcMD->get();
		if( $query_funcoes && $query_funcoes->resultID->num_rows >=1 )
		{
			$this->data['rs_funcoes'] = $query_funcoes;
		}

		// GENERO SEXUAL
		$this->data['arr_generos'] = $this->cfg->getGeneros();

		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		//if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
		//{
		//	$this->data['rs_grupos'] = $query_grupos;
		//}	

		//var_dump( session()->get('isLoggedInUserInscricao') );
		//exit();
	
		return view('inscricoes/grupos', $this->data);
	}

	public function participantes( $event_hashkey = "" )
	{
		if( !session()->get('isLoggedInUserInscricao') == TRUE ){
			return $this->response->redirect( site_url('inscricoes/login/'. $event_hashkey) );	
		}
		$user_id = (int)session()->get('inscUser_id');


		/*
		 * -------------------------------------------------------------
		 * Gravando a informações por meio de POST tradicional
		 * -------------------------------------------------------------
		**/
			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Precisa Relacionar o User com o Grupo
				// ---------------------------------------------------------
				$grp_titulo = $this->request->getPost('grp_titulo');
				$grp_responsavel = $this->request->getPost('grp_responsavel');
				$grp_cpf = $this->request->getPost('grp_cpf');
				$grp_telefone = $this->request->getPost('grp_telefone');
				$grp_celular = $this->request->getPost('grp_celular');

				$grp_sm_instagram = $this->request->getPost('grp_sm_instagram');
				$grp_sm_facebook = $this->request->getPost('grp_sm_facebook');
				$grp_sm_youtube = $this->request->getPost('grp_sm_youtube');
				$grp_sm_vimeo = $this->request->getPost('grp_sm_vimeo');

				$grp_redes_sociais = [
					'instagram' => $grp_sm_instagram,
					'facebook' => $grp_sm_facebook,
					'youtube' => $grp_sm_youtube,
					'vimeo' => $grp_sm_vimeo
				];

				$grp_end_cep = $this->request->getPost('grp_end_cep');
				$grp_end_logradouro = $this->request->getPost('grp_end_logradouro');
				$grp_end_numero = $this->request->getPost('grp_end_numero');
				$grp_end_compl = $this->request->getPost('grp_end_compl');
				$grp_end_bairro = $this->request->getPost('grp_end_bairro');
				$grp_end_cidade = $this->request->getPost('grp_end_cidade');
				$grp_end_estado = $this->request->getPost('grp_end_estado');

				//$partc_documento = $this->request->getPost('partc_documento');
				//$partc_nome = $this->request->getPost('partc_nome');
				//$partc_nome_social = $this->request->getPost('partc_nome_social');
				//$partc_genero = $this->request->getPost('partc_genero');
				//$partc_dte_nascto = $this->request->getPost('partc_dte_nascto');
				//$func_id = $this->request->getPost('func_id');
				$lista_participantes = $this->request->getPost('lista_participantes');

				$corgf_titulo = $this->request->getPost('corgf_titulo');
				$corgf_coreografo = $this->request->getPost('corgf_coreografo');
				$corgf_musica = $this->request->getPost('corgf_musica');
				$corgf_compositor = $this->request->getPost('corgf_compositor');
				$corgf_observacao = $this->request->getPost('corgf_observacao');
				$corgf_modl_id = (int)$this->request->getPost('corgf_modl_id');
				$corgf_formt_id = (int)$this->request->getPost('corgf_formt_id');
				$corgf_categ_id = (int)$this->request->getPost('corgf_categ_id');

				$all_fields_post[] = $this->request->getPost();

				$insti_id = 0;

				/*
				 * -------------------------------------------------------------
				 * Recuperamos as informações do Evento
				 * -------------------------------------------------------------
				**/
				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 

					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações do Grupo
					 * -------------------------------------------------------------
					**/
					$data_db = [
						'insti_id' => (int)$insti_id,
						'user_id' => (int)$user_id,
						'grp_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
						'grp_urlpage' => url_title( convert_accented_characters($grp_titulo), '-', TRUE ),
						'grp_titulo' => $grp_titulo,
						'grp_responsavel' => $grp_responsavel,
						'grp_telefone' => $grp_telefone,
						'grp_celular' => $grp_celular,
						'grp_cpf' => $grp_cpf,
						'grp_redes_sociais' => json_encode($grp_redes_sociais),
						'grp_end_cep' => $grp_end_cep,
						'grp_end_logradouro' => $grp_end_logradouro,
						'grp_end_numero' => $grp_end_numero,
						'grp_end_compl' => $grp_end_compl,
						'grp_end_bairro' => $grp_end_bairro,
						'grp_end_cidade' => $grp_end_cidade,
						'grp_end_estado' => $grp_end_estado,
						'grp_dte_cadastro' => date("Y-m-d H:i:s"),
						'grp_dte_alteracao' => date("Y-m-d H:i:s"),
						'grp_ativo' => 1,
					];

					$query_grupo = $this->grpMD
						->where('insti_id', (int)$insti_id)
						->where('user_id', (int)$user_id)
						->where('grp_urlpage', url_title( convert_accented_characters($grp_titulo), '-', TRUE ))
						->limit(1)
						->get();
					if( $query_grupo && $query_grupo->resultID->num_rows == 0 )
					{
						$grp_id = $this->grpMD->insert($data_db);

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações dos participantes
						 * -------------------------------------------------------------
						**/
							if( !empty( $lista_participantes ) ){
								//print '<pre>';
								//print_r( json_decode($lista_participantes) );
								//print '</pre>';
								$lista_participantes_json = json_decode($lista_participantes);
								foreach ($lista_participantes_json as $key => $val) {
									//print '<hr>';
									//print ' | '. $val->partc_documento;
									//print ' | '. $val->partc_nome;
									//print ' | '. $val->partc_nome_social;
									//print ' | '. $val->partc_genero;
									//print ' | '. $val->partc_dte_nascto;
									//print ' | '. $val->partc_idade;
									//print ' | '. $val->partc_categoria;
									//print ' | '. $val->func_id;
									//print ' | '. $val->partc_file_doc_frente;
									//print ' | '. $val->partc_file_doc_verso;
									//print ' | '. $val->partc_file_foto;

									$data_participante_db = [
										'insti_id' => (int)$insti_id,
										'partc_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
										'partc_urlpage' => url_title( convert_accented_characters($val->partc_nome), '-', TRUE ),
										'grp_id' => $grp_id,
										//'func_id' => $func_id,
										'partc_nome' => $val->partc_nome,
										'partc_nome_social' => $val->partc_nome_social,
										'partc_genero' => $val->partc_genero,
										'partc_documento' => $val->partc_documento,
										'partc_dte_nascto' => fct_date2bd($val->partc_dte_nascto),
										'partc_dte_cadastro' => date("Y-m-d H:i:s"),
										'partc_dte_alteracao' => date("Y-m-d H:i:s"),
										'partc_ativo' => 1,
									];

									//if( !empty($file_foto)){
									//	$data_participante_db['partc_file_foto'] = $file_foto;
									//}
									//if( !empty($file_doc_frente)){
									//	$data_participante_db['partc_file_doc_frente'] = $file_doc_frente;
									//}
									//if( !empty($file_doc_verso)){
									//	$data_participante_db['partc_file_doc_verso'] = $file_doc_verso;
									//}

									$query_participante = $this->partcMD
										->where('insti_id', (int)$insti_id)
										->where('grp_id', (int)$grp_id)
										->where('partc_documento', $val->partc_documento)
										->limit(1)
										->get();
									if( $query_participante && $query_participante->resultID->num_rows == 0 )
									{
										$partc_id = $this->partcMD->insert($data_participante_db);
									}
								}
							}

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações de coreografia
						 * -------------------------------------------------------------
						**/
							$data_coreografia_db = [
								'insti_id' => (int)$insti_id,
								'grp_id' => (int)$grp_id,
								'modl_id' => (int)$corgf_modl_id,
								'formt_id' => (int)$corgf_formt_id,
								'categ_id' => (int)$corgf_categ_id,
								'corgf_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
								'corgf_urlpage' => url_title( convert_accented_characters($corgf_titulo), '-', TRUE ),
								'corgf_titulo' => $corgf_titulo,
								'corgf_coreografo' => $corgf_coreografo,
								'corgf_musica' => $corgf_musica,
								//'corgf_tempo' => $corgf_tempo,
								'corgf_compositor' => $corgf_compositor,
								'corgf_observacao' => $corgf_observacao,
								'corgf_dte_cadastro' => date("Y-m-d H:i:s"),
								'corgf_dte_alteracao' => date("Y-m-d H:i:s"),
								'corgf_ativo' => 1,
							];

							//if( !empty($file_musica_mp3)){
							//	$data_db['corgf_musica_file'] = $file_musica_mp3;
							//}

							$query_coreografia = $this->corgfMD
								->where('insti_id', (int)$insti_id)
								->where('grp_id', (int)$grp_id)
								->where('corgf_urlpage', url_title( convert_accented_characters($corgf_titulo), '-', TRUE ))
								->get();
							if( $query_coreografia && $query_coreografia->resultID->num_rows == 0 )
							{
								$corgf_id = $this->corgfMD->insert($data_coreografia_db);
							}
					}

					//print '<pre>';
					//print_r( $all_fields_post );
					//print '<pre>';
					exit();
				}
			}


		$this->eventMD->select('*');
		$this->eventMD->where('event_hashkey', $event_hashkey);
		$this->eventMD->orderBy('event_id', 'DESC');
		$this->eventMD->limit(1);
		$query_event = $this->eventMD->get();
		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		if( $query_event && $query_event->resultID->num_rows >=1 )
		{
			$rs_event = $query_event->getRow();
			$this->data['rs_event'] = $rs_event;

			$insti_id = (int)$rs_event->insti_id;
			$event_id = (int)$rs_event->event_id;

			/*
			 * -------------------------------------------------------------
			 * Configurações do Evento
			 * -------------------------------------------------------------
			**/
				$this->evcfgMD->select('*');
				$this->evcfgMD->where('event_id', $event_id);
				$this->evcfgMD->orderBy('event_id', 'DESC');
				$this->evcfgMD->limit(1);
				$query_event_config = $this->evcfgMD->get();
				if( $query_event_config && $query_event_config->resultID->num_rows >= 1 )
				{
					$rs_event_config = $query_event_config->getRow();
					$this->data['rs_event_config'] = $rs_event_config;
				}


			/*
			 * -------------------------------------------------------------
			 * categorias
			 * -------------------------------------------------------------
			**/
				$list_rs_categorias = [];
				$query_categorias = $this->categMD->select_all_by_insti_id( (int)$insti_id );
				if( $query_categorias && $query_categorias->resultID->num_rows >=1 )
				{
					$rs_categorias = $query_categorias->getResult();
					$this->data['rs_categorias'] = $query_categorias;

					$categorias = [];
					foreach ($rs_categorias as $row) {
						$categoria = [
							'id' => $row->categ_id,
							'titulo' => $row->categ_titulo,
							'idade_min' => (int)$row->categ_idade_min,
							'idade_max' => (int)$row->categ_idade_max,
						];
						$categorias[] = $categoria;
					}
					$list_rs_categorias = $categorias;
				}
				$this->data['list_rs_categorias'] = $list_rs_categorias;


			/*
			 * -------------------------------------------------------------
			 * Informações referente ao grupo e evento
			 * -------------------------------------------------------------
			**/
				$this->grpMD->from('tbl_grupos As GRP', true)
					->select('GRP.*')
					->select('GREVT.grevt_id')
					->join('tbl_grupos_x_eventos AS GREVT', 'GREVT.grp_id = GRP.grp_id', 'INNER')
					->where('GRP.user_id', $user_id)
					->where('GREVT.event_id', $event_id)
					->orderBy('GREVT.grevt_id', 'DESC')
					->limit(1);
				$query_group_evt = $this->grpMD->get();
				if( $query_group_evt && $query_group_evt->resultID->num_rows >=1 )
				{
					$rs_group_evt = $query_group_evt->getRow();
					$this->data['rs_group'] = $rs_group_evt;
				}else{
					/*
					 * -------------------------------------------------------------
					 * Recuperar informações do último grupo criado
					 * -------------------------------------------------------------
					**/
						$this->grpMD->select('*');
						//$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
						$this->grpMD->where('user_id', $user_id);
						$this->grpMD->orderBy('grp_id', 'DESC');
						$this->grpMD->limit(1);
						$query_group = $this->grpMD->get();
						//$query_grupos = $this->grpMD->select_all_by_insti_id();
						if( $query_group && $query_group->resultID->num_rows >=1 )
						{
							$rs_group = $query_group->getRow();
							$this->data['rs_group'] = $rs_group;
						}
				}


			/*
			 * -------------------------------------------------------------
			 * participantes já cadastrados
			 * -------------------------------------------------------------
			**/
				$query_partc_cadastrados = $this->partcMD->from('tbl_participantes As PARTC', true)
					->select('PARTC.*')
					->select('CATEG.categ_titulo')
					->select('FUNC.func_titulo')
					->join('tbl_categorias AS CATEG', 'CATEG.categ_id = PARTC.categ_id', 'LEFT')
					->join('tbl_funcoes AS FUNC', 'FUNC.func_id = PARTC.func_id', 'LEFT')
					->where('PARTC.insti_id', (int)$rs_event->insti_id)
					->orderBy('PARTC.partc_nome', 'ASC')
					->limit(1000)
					->get();
				//$query_partc_cadastrados = $this->partcMD
				//	->select('*')
				//	->where('insti_id', (int)$rs_event->insti_id)
				//	->orderBy('partc_nome', 'ASC')
				//	->limit(1000)
				//	->get();
				if( $query_partc_cadastrados && $query_partc_cadastrados->resultID->num_rows >=1 )
				{
					$rs_partc_cadastrados = $query_partc_cadastrados->getResult();

					$arr_partc_cadastrados = [];
					foreach ($rs_partc_cadastrados as $row) {
						$partc_hashkey = ($row->partc_hashkey);
						$partc_documento = ($row->partc_documento);
						$partc_nome = ($row->partc_nome);
						$partc_nome_social = ($row->partc_nome_social);
						$partc_genero = ($row->partc_genero);
						$partc_dte_nascto = ($row->partc_dte_nascto);

						$categ_id = (int)($row->categ_id);
						$func_id = (int)($row->func_id);

						$categ_titulo = ($row->categ_titulo);
						$func_titulo = ($row->func_titulo);

						$dte_nascto = new DateTime( $partc_dte_nascto );	
						$dte_current = new DateTime(date('Y-m-d H:i:s'));

						$nasc_diff = $dte_current->diff($dte_nascto);
						$idade_years = (int)$nasc_diff->format('%y');
						$idade_months = (int)$nasc_diff->format('%m');
						$idade_days = (int)$nasc_diff->format('%d');
						//if( $idade_years < 18 ){
						//	$error_num = 1;
						//	$error_msg = "A Promoção válida somente para maiores de 18 anos.";
						//	$prosseguir = false;
						//}
						$partc_idade = $idade_years;

						//encontrarCategoria($list_rs_categorias, $idade = 0 )

						/*
							var partes = dataNascimento.split("/");
							var dia = parseInt(partes[0]);
							var mes = parseInt(partes[1]) - 1; // Meses em JavaScript são indexados a partir de 0
							var ano = parseInt(partes[2]);

							var hoje = new Date();
							var nascimento = new Date(ano, mes, dia);
							var idade = hoje.getFullYear() - nascimento.getFullYear();
							var mes = hoje.getMonth() - nascimento.getMonth();

							if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
								idade--;
							}

							return idade;						
						*/

						$arr_temp = [
							"partc_hashkey" => $partc_hashkey,
							"partc_documento" => $partc_documento,
							"partc_nome" => $partc_nome,
							"partc_nome_social" => $partc_nome_social,
							"partc_genero" => "X",
							"partc_dte_nascto" => fct_formatdate($partc_dte_nascto, "d/m/Y"),
							"partc_idade" => $partc_idade,
							"partc_categoria" => $categ_titulo,
							"categ_id" => $categ_id,
							"func_id" => $func_id,
							"func_titulo" => $func_titulo,
							"partc_file_foto" => null,
							"partc_file_foto_preview" => null
						];
						array_push($arr_partc_cadastrados, $arr_temp);
					}
					$this->data['rs_partc_cadastrados'] = $arr_partc_cadastrados;

					/*
					[
						{
							"partc_hashkey":"4stQsKkE1EkSmvyXBeEfjcNyCedrVW4G",
							"partc_documento":"668.645.030-89",
							"partc_nome":"teste 017",
							"partc_nome_social":"teste 017",
							"partc_genero":"X",
							"partc_dte_nascto":"24/03/2000",
							"partc_idade":23,
							"partc_categoria":"Adulto",
							"categ_id":"8",
							"func_id":"4",
							"func_titulo":"Bailarino",
							"partc_file_foto":null,
							"partc_file_foto_preview":null
						}
					]					
					*/


				}





			// GRUPOS -- não será necessário esta query
			$this->grpMD->select('*');
			$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
			//$this->grpMD->where('insti_id', (int)$this->session_user_id);
			$this->grpMD->orderBy('grp_titulo', 'ASC');
			$this->grpMD->limit(1000);
			$query_grupos = $this->grpMD->get();
			//$query_grupos = $this->grpMD->select_all_by_insti_id();
			if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
			{
				$this->data['rs_grupos'] = $query_grupos;
			}


			$query_modalidades = $this->modlMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_modalidades && $query_modalidades->resultID->num_rows >=1 )
			{
				$this->data['rs_modalidades'] = $query_modalidades;
			}

			$query_formatos = $this->formtMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_formatos && $query_formatos->resultID->num_rows >=1 )
			{
				$this->data['rs_formatos'] = $query_formatos;
			}


			$this->data['list_rs_func_obrig'] = [];
			$query_func_obrig = $this->funcMD
				->select('func_id, func_titulo')
				->where('func_obrigatorio', 1)
				->get();
			if( $query_func_obrig && $query_func_obrig->resultID->num_rows >=1 )
			{
				$rs_func_obrig = $query_func_obrig->getResult();
				$this->data['list_rs_func_obrig'] = $rs_func_obrig;
			}
		}




		// FUNCOES
		$this->funcMD->select('func_id, func_titulo');
		//$this->funcMD->where('insti_id', (int)$rs_event->insti_id);
		$this->funcMD->where('func_ativo', 1);
		$this->funcMD->orderBy('func_titulo', 'ASC');
		$this->funcMD->limit(1000);
		$query_funcoes = $this->funcMD->get();
		if( $query_funcoes && $query_funcoes->resultID->num_rows >=1 )
		{
			$this->data['rs_funcoes'] = $query_funcoes;
		}

		// GENERO SEXUAL
		$this->data['arr_generos'] = $this->cfg->getGeneros();

		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		//if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
		//{
		//	$this->data['rs_grupos'] = $query_grupos;
		//}	

		//var_dump( session()->get('isLoggedInUserInscricao') );
		//exit();
	
		return view('inscricoes/participantes', $this->data);
	}

	public function coreografias( $event_hashkey = "" )
	{
		if( !session()->get('isLoggedInUserInscricao') == TRUE ){
			return $this->response->redirect( site_url('inscricoes/login/'. $event_hashkey) );	
		}
		$user_id = (int)session()->get('inscUser_id');

		/*
		 * -------------------------------------------------------------
		 * Gravando a informações por meio de POST tradicional
		 * -------------------------------------------------------------
		**/
			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Precisa Relacionar o User com o Grupo
				// ---------------------------------------------------------
				$grp_titulo = $this->request->getPost('grp_titulo');
				$grp_responsavel = $this->request->getPost('grp_responsavel');
				$grp_cpf = $this->request->getPost('grp_cpf');
				$grp_telefone = $this->request->getPost('grp_telefone');
				$grp_celular = $this->request->getPost('grp_celular');

				$grp_sm_instagram = $this->request->getPost('grp_sm_instagram');
				$grp_sm_facebook = $this->request->getPost('grp_sm_facebook');
				$grp_sm_youtube = $this->request->getPost('grp_sm_youtube');
				$grp_sm_vimeo = $this->request->getPost('grp_sm_vimeo');

				$grp_redes_sociais = [
					'instagram' => $grp_sm_instagram,
					'facebook' => $grp_sm_facebook,
					'youtube' => $grp_sm_youtube,
					'vimeo' => $grp_sm_vimeo
				];

				$grp_end_cep = $this->request->getPost('grp_end_cep');
				$grp_end_logradouro = $this->request->getPost('grp_end_logradouro');
				$grp_end_numero = $this->request->getPost('grp_end_numero');
				$grp_end_compl = $this->request->getPost('grp_end_compl');
				$grp_end_bairro = $this->request->getPost('grp_end_bairro');
				$grp_end_cidade = $this->request->getPost('grp_end_cidade');
				$grp_end_estado = $this->request->getPost('grp_end_estado');

				//$partc_documento = $this->request->getPost('partc_documento');
				//$partc_nome = $this->request->getPost('partc_nome');
				//$partc_nome_social = $this->request->getPost('partc_nome_social');
				//$partc_genero = $this->request->getPost('partc_genero');
				//$partc_dte_nascto = $this->request->getPost('partc_dte_nascto');
				//$func_id = $this->request->getPost('func_id');
				$lista_participantes = $this->request->getPost('lista_participantes');

				$corgf_titulo = $this->request->getPost('corgf_titulo');
				$corgf_coreografo = $this->request->getPost('corgf_coreografo');
				$corgf_musica = $this->request->getPost('corgf_musica');
				$corgf_compositor = $this->request->getPost('corgf_compositor');
				$corgf_observacao = $this->request->getPost('corgf_observacao');
				$corgf_modl_id = (int)$this->request->getPost('corgf_modl_id');
				$corgf_formt_id = (int)$this->request->getPost('corgf_formt_id');
				$corgf_categ_id = (int)$this->request->getPost('corgf_categ_id');

				$all_fields_post[] = $this->request->getPost();

				$insti_id = 0;

				/*
				 * -------------------------------------------------------------
				 * Recuperamos as informações do Evento
				 * -------------------------------------------------------------
				**/
				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 



					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações do Grupo
					 * -------------------------------------------------------------
					**/
					$data_db = [
						'insti_id' => (int)$insti_id,
						'user_id' => (int)$user_id,
						'grp_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
						'grp_urlpage' => url_title( convert_accented_characters($grp_titulo), '-', TRUE ),
						'grp_titulo' => $grp_titulo,
						'grp_responsavel' => $grp_responsavel,
						'grp_telefone' => $grp_telefone,
						'grp_celular' => $grp_celular,
						'grp_cpf' => $grp_cpf,
						'grp_redes_sociais' => json_encode($grp_redes_sociais),
						'grp_end_cep' => $grp_end_cep,
						'grp_end_logradouro' => $grp_end_logradouro,
						'grp_end_numero' => $grp_end_numero,
						'grp_end_compl' => $grp_end_compl,
						'grp_end_bairro' => $grp_end_bairro,
						'grp_end_cidade' => $grp_end_cidade,
						'grp_end_estado' => $grp_end_estado,
						'grp_dte_cadastro' => date("Y-m-d H:i:s"),
						'grp_dte_alteracao' => date("Y-m-d H:i:s"),
						'grp_ativo' => 1,
					];

					$query_grupo = $this->grpMD
						->where('insti_id', (int)$insti_id)
						->where('user_id', (int)$user_id)
						->where('grp_urlpage', url_title( convert_accented_characters($grp_titulo), '-', TRUE ))
						->limit(1)
						->get();
					if( $query_grupo && $query_grupo->resultID->num_rows == 0 )
					{
						$grp_id = $this->grpMD->insert($data_db);

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações dos participantes
						 * -------------------------------------------------------------
						**/
							if( !empty( $lista_participantes ) ){
								//print '<pre>';
								//print_r( json_decode($lista_participantes) );
								//print '</pre>';
								$lista_participantes_json = json_decode($lista_participantes);
								foreach ($lista_participantes_json as $key => $val) {
									//print '<hr>';
									//print ' | '. $val->partc_documento;
									//print ' | '. $val->partc_nome;
									//print ' | '. $val->partc_nome_social;
									//print ' | '. $val->partc_genero;
									//print ' | '. $val->partc_dte_nascto;
									//print ' | '. $val->partc_idade;
									//print ' | '. $val->partc_categoria;
									//print ' | '. $val->func_id;
									//print ' | '. $val->partc_file_doc_frente;
									//print ' | '. $val->partc_file_doc_verso;
									//print ' | '. $val->partc_file_foto;

									$data_participante_db = [
										'insti_id' => (int)$insti_id,
										'partc_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
										'partc_urlpage' => url_title( convert_accented_characters($val->partc_nome), '-', TRUE ),
										'grp_id' => $grp_id,
										//'func_id' => $func_id,
										'partc_nome' => $val->partc_nome,
										'partc_nome_social' => $val->partc_nome_social,
										'partc_genero' => $val->partc_genero,
										'partc_documento' => $val->partc_documento,
										'partc_dte_nascto' => fct_date2bd($val->partc_dte_nascto),
										'partc_dte_cadastro' => date("Y-m-d H:i:s"),
										'partc_dte_alteracao' => date("Y-m-d H:i:s"),
										'partc_ativo' => 1,
									];

									//if( !empty($file_foto)){
									//	$data_participante_db['partc_file_foto'] = $file_foto;
									//}
									//if( !empty($file_doc_frente)){
									//	$data_participante_db['partc_file_doc_frente'] = $file_doc_frente;
									//}
									//if( !empty($file_doc_verso)){
									//	$data_participante_db['partc_file_doc_verso'] = $file_doc_verso;
									//}

									$query_participante = $this->partcMD
										->where('insti_id', (int)$insti_id)
										->where('grp_id', (int)$grp_id)
										->where('partc_documento', $val->partc_documento)
										->limit(1)
										->get();
									if( $query_participante && $query_participante->resultID->num_rows == 0 )
									{
										$partc_id = $this->partcMD->insert($data_participante_db);
									}
								}
							}

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações de coreografia
						 * -------------------------------------------------------------
						**/
							$data_coreografia_db = [
								'insti_id' => (int)$insti_id,
								'grp_id' => (int)$grp_id,
								'modl_id' => (int)$corgf_modl_id,
								'formt_id' => (int)$corgf_formt_id,
								'categ_id' => (int)$corgf_categ_id,
								'corgf_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
								'corgf_urlpage' => url_title( convert_accented_characters($corgf_titulo), '-', TRUE ),
								'corgf_titulo' => $corgf_titulo,
								'corgf_coreografo' => $corgf_coreografo,
								'corgf_musica' => $corgf_musica,
								//'corgf_tempo' => $corgf_tempo,
								'corgf_compositor' => $corgf_compositor,
								'corgf_observacao' => $corgf_observacao,
								'corgf_dte_cadastro' => date("Y-m-d H:i:s"),
								'corgf_dte_alteracao' => date("Y-m-d H:i:s"),
								'corgf_ativo' => 1,
							];

							//if( !empty($file_musica_mp3)){
							//	$data_db['corgf_musica_file'] = $file_musica_mp3;
							//}

							$query_coreografia = $this->corgfMD
								->where('insti_id', (int)$insti_id)
								->where('grp_id', (int)$grp_id)
								->where('corgf_urlpage', url_title( convert_accented_characters($corgf_titulo), '-', TRUE ))
								->get();
							if( $query_coreografia && $query_coreografia->resultID->num_rows == 0 )
							{
								$corgf_id = $this->corgfMD->insert($data_coreografia_db);
							}
					}

					//print '<pre>';
					//print_r( $all_fields_post );
					//print '<pre>';
					exit();
				}
			}


		$this->eventMD->select('*');
		$this->eventMD->where('event_hashkey', $event_hashkey);
		$this->eventMD->orderBy('event_id', 'DESC');
		$this->eventMD->limit(1);
		$query_event = $this->eventMD->get();
		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		if( $query_event && $query_event->resultID->num_rows >=1 )
		{
			$rs_event = $query_event->getRow();
			$this->data['rs_event'] = $rs_event;

			$insti_id = (int)$rs_event->insti_id;
			$event_id = (int)$rs_event->event_id;


			/*
			 * -------------------------------------------------------------
			 * Configurações do Evento
			 * -------------------------------------------------------------
			**/
				$this->evcfgMD->select('*');
				$this->evcfgMD->where('event_id', $event_id);
				$this->evcfgMD->orderBy('event_id', 'DESC');
				$this->evcfgMD->limit(1);
				$query_event_config = $this->evcfgMD->get();
				if( $query_event_config && $query_event_config->resultID->num_rows >=1 )
				{
					$rs_event_config = $query_event_config->getRow();
					$this->data['rs_event_config'] = $rs_event_config;
				}


			/*
			 * -------------------------------------------------------------
			 * Informações referente ao grupo e evento
			 * -------------------------------------------------------------
			**/
				$this->grpMD->from('tbl_grupos As GRP', true)
					->select('GRP.*')
					->select('GREVT.grevt_id')
					->join('tbl_grupos_x_eventos AS GREVT', 'GREVT.grp_id = GRP.grp_id', 'INNER')
					->where('GRP.user_id', $user_id)
					->where('GREVT.event_id', $event_id)
					->orderBy('GREVT.grevt_id', 'DESC')
					->limit(1);
				$query_group_evt = $this->grpMD->get();
				if( $query_group_evt && $query_group_evt->resultID->num_rows >=1 )
				{
					$rs_group_evt = $query_group_evt->getRow();
					$this->data['rs_group'] = $rs_group_evt;
				}else{
					/*
					 * -------------------------------------------------------------
					 * Recuperar informações do último grupo criado
					 * -------------------------------------------------------------
					**/
						$this->grpMD->select('*');
						//$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
						$this->grpMD->where('user_id', $user_id);
						$this->grpMD->orderBy('grp_id', 'DESC');
						$this->grpMD->limit(1);
						$query_group = $this->grpMD->get();
						//$query_grupos = $this->grpMD->select_all_by_insti_id();
						if( $query_group && $query_group->resultID->num_rows >=1 )
						{
							$rs_group = $query_group->getRow();
							$this->data['rs_group'] = $rs_group;
						}
				}






			// GRUPOS -- não será necessário esta query
			$this->grpMD->select('*');
			$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
			//$this->grpMD->where('insti_id', (int)$this->session_user_id);
			$this->grpMD->orderBy('grp_titulo', 'ASC');
			$this->grpMD->limit(1000);
			$query_grupos = $this->grpMD->get();
			//$query_grupos = $this->grpMD->select_all_by_insti_id();
			if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
			{
				$this->data['rs_grupos'] = $query_grupos;
			}


			$query_modalidades = $this->modlMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_modalidades && $query_modalidades->resultID->num_rows >=1 )
			{
				$this->data['rs_modalidades'] = $query_modalidades;
			}

			//$query_formatos = $this->formtMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			$query_formatos = $this->formtMD
				->select('formt_id, formt_titulo, formt_tempo_limit, formt_max_partic')
				->where('insti_id', (int)$rs_event->insti_id)
				->orderBy('formt_titulo', 'ASC')
				->get();
			if( $query_formatos && $query_formatos->resultID->num_rows >=1 )
			{
				$this->data['rs_formatos'] = $query_formatos->getResult();
			}

			$query_formatos = $this->formtMD
				->select('formt_id, formt_titulo, formt_tempo_limit, formt_max_partic')
				->where('insti_id', (int)$insti_id)
				->orderBy('formt_titulo', 'ASC')
				->get();

			$this->data['list_rs_categorias'] = [];
			$query_categorias = $this->categMD->select_all_by_insti_id( (int)$rs_event->insti_id );
			if( $query_categorias && $query_categorias->resultID->num_rows >=1 )
			{
				$rs_categorias = $query_categorias->getResult();
				$this->data['rs_categorias'] = $query_categorias;

				$categorias = [];
				foreach ($rs_categorias as $row) {
					$categoria = [
						'id' => $row->categ_id,
						'titulo' => $row->categ_titulo,
						'idade_min' => (int)$row->categ_idade_min,
						'idade_max' => (int)$row->categ_idade_max,
					];
					$categorias[] = $categoria;
				}
				$this->data['list_rs_categorias'] = $categorias;
			}


			//$this->data['list_rs_func_obrig'] = [];
			//$query_func_obrig = $this->funcMD
			//	->select('func_id, func_titulo')
			//	->where('func_obrigatorio', 1)
			//	->get();
			//if( $query_func_obrig && $query_func_obrig->resultID->num_rows >=1 )
			//{
			//	$rs_func_obrig = $query_func_obrig->getResult();
			//	$this->data['list_rs_func_obrig'] = $rs_func_obrig;
			//}


			$func_id = 3; 
			$query_coreografos = $this->partcMD
				->select('partc_id, partc_nome, partc_documento')
				->where('insti_id', (int)$insti_id)
				->where('func_id', $func_id)
				//->where('grp_id', (int)$grp_id)
				->get();
			if( $query_coreografos && $query_coreografos->resultID->num_rows >= 1 )
			{
				$this->data['rs_coreografos'] = $query_coreografos->getResult();
			}
		}
	
		return view('inscricoes/coreografias', $this->data);
	}




	public function cadastro( $event_hashkey = "" )
	{
		if ($this->request->getPost())
		{
			$session = session();

			$prosseguir = true;
			$validation =  \Config\Services::validation();

			$user_nome = $this->request->getPost('user_nome');
			$user_sobrenome = $this->request->getPost('user_sobrenome');
			$user_email = $this->request->getPost('user_email');
			$user_senha = $this->request->getPost('user_senha');

			$all_fields_post[] = $this->request->getPost();

			/*
			 * -------------------------------------------------------------
			 * Verificamos os campos
			 * -------------------------------------------------------------
			**/
				if( $prosseguir == true ){
					$validateFields = [
						'user_nome' => [ 'label' => 'user_nome', 'rules' => 'required',
							'errors' => [
								'required' => 'Preencha corretamente {field}.',
								//'validar_cpf' => 'O {field} informado é inválido.',
							],
						],
						'user_email' => [ 'label' => 'user_email', 'rules' => 'required',
							'errors' => [
								'required' => 'Preencha corretamente {field}.',
								//'validar_cpf' => 'O {field} informado é inválido.',
							],
						],
						'user_senha' => [ 'label' => 'user_senha', 'rules' => 'required',
							'errors' => [
								'required' => 'Preencha corretamente {field}.',
								//'validar_cpf' => 'O {field} informado é inválido.',
							],
						],
					];
					$fields_valid = $validation->setRules($validateFields);
					if( $validation->withRequest($this->request)->run() === FALSE )
					{
						$error_num = 1;
						$error_msg = "Preencha corretamente os campos!";
						$error_infos[] = $validation->getErrors();
						$prosseguir = false;
					}
				}


			/*
			 * -------------------------------------------------------------
			 * Todas validações ok! Gravar no banco de dados
			 * -------------------------------------------------------------
			**/
				$prosseguir_x = false;
				if( $prosseguir == true ){
					$user_ativo = 1;

					$data_db = [
						//'insti_id' => (int)$this->session_user_id,
						'user_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
						'user_urlpage' => url_title( convert_accented_characters($user_nome), '-', TRUE ),
						'user_nome' => $user_nome,
						'user_sobrenome' => $user_sobrenome,
						'user_email' => $user_email,
						'user_senha' => fct_password_hash($user_senha),
						'user_nivel' => 'inscricao',
						'user_dte_cadastro' => date("Y-m-d H:i:s"),
						'user_dte_alteracao' => date("Y-m-d H:i:s"),
						'user_ativo' => $user_ativo,
					];

					$query_user = $this->userMD->where('user_email', $user_email)->get();
					if( $query_user && $query_user->resultID->num_rows >=1 )
					{
						//unset( $data_db['user_hashkey'] );
						//unset( $data_db['user_dte_cadastro'] );
						//$qryExecute = $this->userMD->update($partc_id, $data_db);
					}else{
						$user_id = $this->userMD->insert($data_db);
					}

					/*
					 * -------------------------------------------------------------
					 * Gera Sessão
					 * -------------------------------------------------------------
					**/
						$ses_data = [
							//'cad_id' => $rs_cad->cad_id,
							//'cad_hashkey' => $rs_cad->cad_hashkey,
							//'cad_nome_completo' => $rs_cad->cad_nome_completo,
							//'cad_email' => $rs_cad->cad_email,
							//'cad_associado' => (int)$rs_cad->cad_associado,
							'isLoggedInUserInscricao' => TRUE
						];
						$session = session();
						$session->set($ses_data);

					/*
					 * -------------------------------------------------------------
					 * COOKIE
					 * -------------------------------------------------------------
					**/
						//$config = new \Config\AppSettings();
						$CFG_COOKIE_NAME = 'JAFESTON-USUARIO-GRUPO';

						$cookieValue = json_encode($ses_data); // valor a ser armazenado no cookie;
						//$cookieExpiration = 3600; // Tempo em segundos (aqui, 1 hora)
						$cookieExpiration = 30 * 24 * 60 * 60; // 30 dias em segundos

						$cookie = [
							'name'   => $CFG_COOKIE_NAME,
							'value'  => $cookieValue,
							'expire' => $cookieExpiration,
							'secure' => FALSE
						];
						set_cookie($cookie);



					return $this->response->redirect( site_url('inscricoes/'. $event_hashkey) );














					$this->cadMD->where('cad_email', $cad_email);
					//$this->cadMD->where('cad_senha', fct_password_hash($cad_senha));
					$this->cadMD->limit(1);
					$query_cad = $this->cadMD->get();
					if( $query_cad && $query_cad->resultID->num_rows >= 1 )
					{
						$rs_cad = $query_cad->getRow();

						/*
						 * -------------------------------------------------------------
						 * Gera Sessão
						 * -------------------------------------------------------------
						**/
							$ses_data = [
								'cad_id' => $rs_cad->cad_id,
								'cad_hashkey' => $rs_cad->cad_hashkey,
								'cad_nome_completo' => $rs_cad->cad_nome_completo,
								'cad_email' => $rs_cad->cad_email,
								'cad_associado' => (int)$rs_cad->cad_associado,
								'isLoggedInFrontEnd' => TRUE
							];
							$session = session();
							$session->set($ses_data);

						/*
						 * -------------------------------------------------------------
						 * COOKIE
						 * -------------------------------------------------------------
						**/
							$config = new \Config\AppSettings();
							$CFG_COOKIE_NAME = $config->CFG_COOKIE_NAME;

							$cookieValue = json_encode($ses_data); // valor a ser armazenado no cookie;
							//$cookieExpiration = 3600; // Tempo em segundos (aqui, 1 hora)
							$cookieExpiration = 30 * 24 * 60 * 60; // 30 dias em segundos

							$cookie = [
								'name'   => $CFG_COOKIE_NAME,
								'value'  => $cookieValue,
								'expire' => $cookieExpiration,
								'secure' => FALSE
							];
							set_cookie($cookie);

						return $this->response->redirect( site_url('dashboard') );
					}else{
						$redirect = site_url('login/?error');
					}
				}

			exit();
		}



		$this->eventMD->select('*');
		$this->eventMD->where('event_hashkey', $event_hashkey);
		$this->eventMD->orderBy('event_id', 'DESC');
		$this->eventMD->limit(1);
		$query_event = $this->eventMD->get();
		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		if( $query_event && $query_event->resultID->num_rows >=1 )
		{
			$rs_event = $query_event->getRow();
			$this->data['rs_event'] = $rs_event;



			// aqui iremos obter informações do evento para filtrar algumas informações
			$this->grpMD->select('*');
			$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
			//$this->grpMD->where('insti_id', (int)$this->session_user_id);
			$this->grpMD->orderBy('grp_id', 'DESC');
			$this->grpMD->limit(1000);
			$query_grupos = $this->grpMD->get();
			//$query_grupos = $this->grpMD->select_all_by_insti_id();
			if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
			{
				$this->data['rs_grupos'] = $query_grupos;
			}

		}		

		return view('inscricoes-cadastro', $this->data);
	}

	public function login( $event_hashkey = "" )
	{
		if ($this->request->getPost())
		{
			$session = session();

			$prosseguir = true;
			$validation =  \Config\Services::validation();

			$user_email = $this->request->getPost('user_email');
			$user_senha = $this->request->getPost('user_senha');

			$all_fields_post[] = $this->request->getPost();

			/*
			 * -------------------------------------------------------------
			 * Verificamos os campos
			 * -------------------------------------------------------------
			**/
				if( $prosseguir == true ){
					$validateFields = [
						'user_email' => [ 'label' => 'user_email', 'rules' => 'required',
							'errors' => [
								'required' => 'Preencha corretamente {field}.',
								//'validar_cpf' => 'O {field} informado é inválido.',
							],
						],
						'user_senha' => [ 'label' => 'user_senha', 'rules' => 'required',
							'errors' => [
								'required' => 'Preencha corretamente {field}.',
								//'validar_cpf' => 'O {field} informado é inválido.',
							],
						],
					];
					$fields_valid = $validation->setRules($validateFields);
					if( $validation->withRequest($this->request)->run() === FALSE )
					{
						$error_num = 1;
						$error_msg = "Preencha corretamente os campos!";
						$error_infos[] = $validation->getErrors();
						$prosseguir = false;
					}
				}


			/*
			 * -------------------------------------------------------------
			 * Todas validações ok!
			 * -------------------------------------------------------------
			**/
				$prosseguir_x = false;
				if( $prosseguir == true ){
					$this->userMD->where('user_nivel', 'inscricao');
					$this->userMD->where('user_email', $user_email);
					$this->userMD->where('user_senha', fct_password_hash($user_senha));
					$this->userMD->limit(1);
					$query_user = $this->userMD->get();
					if( $query_user && $query_user->resultID->num_rows >= 1 )
					{
						$rs_user = $query_user->getRow();

						/*
						 * -------------------------------------------------------------
						 * Gera Sessão
						 * -------------------------------------------------------------
						**/
							$ses_data = [
								'inscUser_id' => $rs_user->user_id,
								'inscUser_hashkey' => $rs_user->user_hashkey,
								'inscUser_nome' => $rs_user->user_nome,
								'inscUser_email' => $rs_user->user_email,
								'isLoggedInUserInscricao' => TRUE
							];
							$session = session();
							$session->set($ses_data);

						/*
						 * -------------------------------------------------------------
						 * COOKIE
						 * -------------------------------------------------------------
						**/
							$config = new \Config\AppSettings();
							//$CFG_COOKIE_NAME = $config->CFG_COOKIE_NAME;
							$CFG_COOKIE_NAME = 'JAFESTON-USUARIO-GRUPO';

							$cookieValue = json_encode($ses_data); // valor a ser armazenado no cookie;
							//$cookieExpiration = 3600; // Tempo em segundos (aqui, 1 hora)
							$cookieExpiration = 30 * 24 * 60 * 60; // 30 dias em segundos

							$cookie = [
								'name'   => $CFG_COOKIE_NAME,
								'value'  => $cookieValue,
								'expire' => $cookieExpiration,
								'secure' => FALSE
							];
							set_cookie($cookie);

						return $this->response->redirect( site_url('inscricoes/grupos/'. $event_hashkey) );
					}else{
						$redirect = site_url('inscricoes/login/?error');
					}
				}

			exit();
		}



		$this->eventMD->select('*');
		$this->eventMD->where('event_hashkey', $event_hashkey);
		$this->eventMD->orderBy('event_id', 'DESC');
		$this->eventMD->limit(1);
		$query_event = $this->eventMD->get();
		//$query_grupos = $this->grpMD->select_all_by_insti_id();
		if( $query_event && $query_event->resultID->num_rows >=1 )
		{
			$rs_event = $query_event->getRow();
			$this->data['rs_event'] = $rs_event;



			// aqui iremos obter informações do evento para filtrar algumas informações
			$this->grpMD->select('*');
			$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
			//$this->grpMD->where('insti_id', (int)$this->session_user_id);
			$this->grpMD->orderBy('grp_id', 'DESC');
			$this->grpMD->limit(1000);
			$query_grupos = $this->grpMD->get();
			//$query_grupos = $this->grpMD->select_all_by_insti_id();
			if( $query_grupos && $query_grupos->resultID->num_rows >=1 )
			{
				$this->data['rs_grupos'] = $query_grupos;
			}

		}		

		$template = 'inscricoes/login';
		return view($template, $this->data);
	}

	public function logout()
	{
		$session = session();
		$session->destroy();	

		return $this->response->redirect( site_url() );
	}

	public function meus_grupos()
	{
		$this->grpMD->select('*');
		//$this->grpMD->where('insti_id', (int)$rs_event->insti_id);
		$this->grpMD->where('user_id', (int)session()->get('inscUser_id'));
		$this->grpMD->orderBy('grp_id', 'DESC');
		$this->grpMD->limit(1000);
		$query_meus_grupos = $this->grpMD->get();
		if( $query_meus_grupos && $query_meus_grupos->resultID->num_rows >=1 )
		{
			$this->data['rs_meus_grupos'] = $query_meus_grupos;
		}	

		return view('inscricoes/meus-grupos', $this->data);
	}

	public function encontrarCategoria($LISTA_CATEG = [], $idade = 0 )
	{
		if( (int)$idade > 0 ){
			foreach ($LISTA_CATEG as $categoria) {
				if ($idade >= $categoria['idade_min'] && $idade <= $categoria['idade_max']) {
					return ['id' => $categoria['id'], 'titulo' => $categoria['titulo']];
				}
			}
		}
		return '';

		/*
			let LISTA_CATEG = vue.lista_categorias;
			for (let categoria of LISTA_CATEG) {
				if (idade >= categoria.idade_min && idade <= categoria.idade_max) {
					return { id : categoria.id, titulo : categoria.titulo } ;
				}
			}
		*/

	}






	public function ajaxform( $action = "")
	{
		$error_num = "1";
		$error_msg = "Erro inesperado";
		$error_infos = "";

		switch ($action) {
		case "SALVAR-GRUPO" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$grp_id = 0;
			$grp_hashkey = '';

			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Recuperamos o evento selecionado
				// ---------------------------------------------------------
				$event_hashkey = $this->request->getPost('event_hashkey');

				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 
					$event_id = (int)$rs_event->event_id; 

					// Precisa Relacionar o User com o Grupo
					// ---------------------------------------------------------
					$grp_titulo = $this->request->getPost('grp_titulo');
					$grp_responsavel = $this->request->getPost('grp_responsavel');
					$grp_cpf = $this->request->getPost('grp_cpf');
					$grp_telefone = $this->request->getPost('grp_telefone');
					$grp_celular = $this->request->getPost('grp_celular');
					$grp_sm_instagram = $this->request->getPost('grp_sm_instagram');
					$grp_sm_facebook = $this->request->getPost('grp_sm_facebook');
					$grp_sm_youtube = $this->request->getPost('grp_sm_youtube');
					$grp_sm_vimeo = $this->request->getPost('grp_sm_vimeo');

					$grp_redes_sociais = [
						'instagram' => $grp_sm_instagram,
						'facebook' => $grp_sm_facebook,
						'youtube' => $grp_sm_youtube,
						'vimeo' => $grp_sm_vimeo
					];

					$grp_end_cep = $this->request->getPost('grp_end_cep');
					$grp_end_logradouro = $this->request->getPost('grp_end_logradouro');
					$grp_end_numero = $this->request->getPost('grp_end_numero');
					$grp_end_compl = $this->request->getPost('grp_end_compl');
					$grp_end_bairro = $this->request->getPost('grp_end_bairro');
					$grp_end_cidade = $this->request->getPost('grp_end_cidade');
					$grp_end_estado = $this->request->getPost('grp_end_estado');

					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações do Grupo
					 * -------------------------------------------------------------
					**/
					$grp_hashkey = md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16));

					$data_db_grp = [
						'insti_id' => (int)$insti_id,
						'user_id' => (int)$user_id,
						'grp_hashkey' => $grp_hashkey,
						'grp_urlpage' => url_title( convert_accented_characters($grp_titulo), '-', TRUE ),
						'grp_titulo' => $grp_titulo,
						'grp_responsavel' => $grp_responsavel,
						'grp_telefone' => $grp_telefone,
						'grp_celular' => $grp_celular,
						'grp_cpf' => $grp_cpf,
						'grp_redes_sociais' => json_encode($grp_redes_sociais),
						'grp_end_cep' => $grp_end_cep,
						'grp_end_logradouro' => $grp_end_logradouro,
						'grp_end_numero' => $grp_end_numero,
						'grp_end_compl' => $grp_end_compl,
						'grp_end_bairro' => $grp_end_bairro,
						'grp_end_cidade' => $grp_end_cidade,
						'grp_end_estado' => $grp_end_estado,
						'grp_dte_cadastro' => date("Y-m-d H:i:s"),
						'grp_dte_alteracao' => date("Y-m-d H:i:s"),
						'grp_ativo' => 1,
					];

					$query_grupo = $this->grpMD
						->where('insti_id', (int)$insti_id)
						->where('user_id', (int)$user_id)
						//->where('grp_urlpage', url_title( convert_accented_characters($grp_titulo), '-', TRUE ))
						->limit(1)
						->get();
					if( $query_grupo && $query_grupo->resultID->num_rows >= 1 )
					{
						$rs_grupo = $query_grupo->getRow();
						$grp_id = (int)$rs_grupo->grp_id; 
						$grp_hashkey = $rs_grupo->grp_hashkey; 


						$grp_id = $this->grpMD->insert($data_db_grp);

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações do Grupo x Eventos
						 * -------------------------------------------------------------
						**/
							$data_db_grevt = [
								'insti_id' => (int)$insti_id,
								'user_id' => (int)$user_id,
								'grp_id' => (int)$grp_id,
								'event_id' => (int)$event_id,
								'grevt_dte_cadastro' => date("Y-m-d H:i:s"),
								'grevt_dte_alteracao' => date("Y-m-d H:i:s"),
								'grevt_ativo' => 1,
							];
							$grevt_id = $this->grevtMD->insert($data_db_grevt);

						$error_num = "0";
						$error_msg = "Grupo cadastrado com sucesso. Novo";
						$error_infos = "";

					}else{
						$grp_id = $this->grpMD->insert($data_db_grp);

						/*
						 * -------------------------------------------------------------
						 * Gravamos as informações do Grupo x Eventos
						 * -------------------------------------------------------------
						**/
							//$data_db_grevt = [
							//	'insti_id' => (int)$insti_id,
							//	'user_id' => (int)$user_id,
							//	'grp_id' => (int)$grp_id,
							//	'event_id' => (int)$event_id,
							//	'grevt_dte_cadastro' => date("Y-m-d H:i:s"),
							//	'grevt_dte_alteracao' => date("Y-m-d H:i:s"),
							//	'grevt_ativo' => 1,
							//];
							//$grevt_id = $this->grevtMD->insert($data_db_grevt);

						$error_num = "0";
						$error_msg = "Grupo cadastrado com sucesso. Existente";
						$error_infos = "";
					}


				}
			}


			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
				'grp_id' => (int)$grp_id,
				"grp_hashkey" => $grp_hashkey,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "SALVAR-PARTICIPANTE" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$grp_id = 0;
			$grp_hashkey = '';

			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Recuperamos o evento selecionado
				// ---------------------------------------------------------
				$event_hashkey = $this->request->getPost('event_hashkey');
				$participantes_json = $this->request->getPost('participantes_json');

				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 
					$event_id = (int)$rs_event->event_id; 

				/*
				 * -------------------------------------------------------------
				 * Gravamos as informações dos participantes
				 * -------------------------------------------------------------
				**/
					if( !empty( $participantes_json ) ){
						//print '<pre>';
						//print_r( json_decode($lista_participantes) );
						//print '</pre>';
						$lista_participantes_json = json_decode($participantes_json);
						foreach ($lista_participantes_json as $key => $val) {
							//print '<hr>';
							//print ' | '. $val->partc_documento;
							//print ' | '. $val->partc_nome;
							//print ' | '. $val->partc_nome_social;
							//print ' | '. $val->partc_genero;
							//print ' | '. $val->partc_dte_nascto;
							//print ' | '. $val->partc_idade;
							//print ' | '. $val->partc_categoria;
							//print ' | '. $val->func_id;
							//print ' | '. $val->partc_file_doc_frente;
							//print ' | '. $val->partc_file_doc_verso;
							//print ' | '. $val->partc_file_foto;

							$partc_hashkey = $val->partc_hashkey;
							if( empty($partc_hashkey) ){ $partc_hashkey = md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)); }

							$data_participante_db = [
								'insti_id' => (int)$insti_id,
								'partc_hashkey' => $partc_hashkey,
								'partc_urlpage' => url_title( convert_accented_characters($val->partc_nome), '-', TRUE ),
								'grp_id' => $grp_id,
								'func_id' => (int)$val->func_id,
								'categ_id' => (int)$val->categ_id,
								'partc_nome' => $val->partc_nome,
								'partc_nome_social' => $val->partc_nome_social,
								'partc_genero' => $val->partc_genero,
								'partc_documento' => $val->partc_documento,
								'partc_dte_nascto' => fct_date2bd($val->partc_dte_nascto),
								'partc_dte_cadastro' => date("Y-m-d H:i:s"),
								'partc_dte_alteracao' => date("Y-m-d H:i:s"),
								'partc_ativo' => 1,
							];

							$imgLogotipo = "";
							$fileInputLogotipo = $this->request->getFile('fileInputLogotipo');
							if( $fileInputLogotipo ){
								if ($fileInputLogotipo->isValid() && ! $fileInputLogotipo->hasMoved()){
									$cpf_limpo = url_title( convert_accented_characters($val->partc_documento), '', TRUE );
									$newName = $fileInputLogotipo->getRandomName();
									//$ext = $fileInputLogotipo->guessExtension();
									$imgLogotipo = 'participante_'. $cpf_limpo .'.'. $fileInputLogotipo->guessExtension();
									$fileInputLogotipo->move( WRITEPATH ."/uploads/participantes/", $imgLogotipo);
									$data_participante_db['partc_file_foto'] = $imgLogotipo;
								}
							}

							//$data_participante_db['partc_file_doc_frente'] = $imgLogotipo;
							//$data_participante_db['partc_file_doc_verso'] = $imgLogotipo;

							//if( !empty($file_foto)){
							//	$data_participante_db['partc_file_foto'] = $file_foto;
							//}
							//if( !empty($file_doc_frente)){
							//	$data_participante_db['partc_file_doc_frente'] = $file_doc_frente;
							//}
							//if( !empty($file_doc_verso)){
							//	$data_participante_db['partc_file_doc_verso'] = $file_doc_verso;
							//}

							$query_participante = $this->partcMD
								->where('insti_id', (int)$insti_id)
								->where('grp_id', (int)$grp_id)
								->where('partc_hashkey', $val->partc_hashkey)
								->limit(1)
								->get();
							if( $query_participante && $query_participante->resultID->num_rows == 0 )
							{
								$partc_id = $this->partcMD->insert($data_participante_db);
							}else{
								unset($data_participante_db['partc_hashkey']);
								unset($data_participante_db['partc_dte_cadastro']);
								$this->partcMD.set($data_participante_db);
								$this->partcMD
								->where('insti_id', (int)$insti_id)
								->where('grp_id', (int)$grp_id)
								->where('partc_hashkey', $val->partc_hashkey);
								$this->partcMD->update();
							}
						}
					}

					$error_num = "0";
					$error_msg = "Participante Ok";
					$error_infos = "";
				}
			}

			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "UPDATE-PARTICIPANTE" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$grp_id = 0;
			$grp_hashkey = '';

			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Recuperamos o evento selecionado
				// ---------------------------------------------------------
				$event_hashkey = $this->request->getPost('event_hashkey');
				$partc_hashkey = $this->request->getPost('partc_hashkey');
				$partc_documento = $this->request->getPost('partc_documento');
				$partc_nome = $this->request->getPost('partc_nome');
				$partc_nome_social = $this->request->getPost('partc_nome_social');
				$partc_genero = $this->request->getPost('partc_genero');
				$func_id = (int)$this->request->getPost('func_id');
				$categ_id = (int)$this->request->getPost('categ_id');
				$partc_dte_nascto = $this->request->getPost('partc_dte_nascto');

				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 
					$event_id = (int)$rs_event->event_id; 

				/*
				 * -------------------------------------------------------------
				 * Gravamos as informações dos participantes
				 * -------------------------------------------------------------
				**/
					//if( empty($partc_hashkey) ){ $partc_hashkey = md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)); }
					$data_participante_db = [
						'insti_id' => (int)$insti_id,
						'partc_hashkey' => $partc_hashkey,
						'partc_urlpage' => url_title( convert_accented_characters($partc_nome), '-', TRUE ),
						//'grp_id' => $grp_id,
						'func_id' => (int)$func_id,
						'categ_id' => (int)$categ_id,
						'partc_nome' => $partc_nome,
						'partc_nome_social' => $partc_nome_social,
						'partc_genero' => $partc_genero,
						'partc_documento' => $partc_documento,
						'partc_dte_nascto' => fct_date2bd($partc_dte_nascto),
						'partc_dte_alteracao' => date("Y-m-d H:i:s"),
						'partc_ativo' => 1,
					];

					$imgLogotipo = "";
					$fileInputLogotipo = $this->request->getFile('fileInputLogotipo');
					if( $fileInputLogotipo ){
						if ($fileInputLogotipo->isValid() && ! $fileInputLogotipo->hasMoved()){
							$cpf_limpo = url_title( convert_accented_characters($partc_documento), '', TRUE );
							$newName = $fileInputLogotipo->getRandomName();
							//$ext = $fileInputLogotipo->guessExtension();
							$imgLogotipo = 'participante_'. $cpf_limpo .'.'. $fileInputLogotipo->guessExtension();
							$fileInputLogotipo->move( WRITEPATH ."/uploads/participantes/", $imgLogotipo);
							$data_participante_db['partc_file_foto'] = $imgLogotipo;
						}
					}

					$query_participante = $this->partcMD
						->where('insti_id', (int)$insti_id)
						->where('grp_id', (int)$grp_id)
						->where('partc_hashkey', $partc_hashkey)
						->limit(1)
						->get();
					if( $query_participante && $query_participante->resultID->num_rows >= 1 )
					{
						$this->partcMD->set($data_participante_db);
						$this->partcMD
						->where('insti_id', (int)$insti_id)
						->where('grp_id', (int)$grp_id)
						->where('partc_hashkey', $partc_hashkey);
						$this->partcMD->update();
					}

					$error_num = "0";
					$error_msg = "Participante alterado com sucesso.";
					$error_infos = "";
				}
			}

			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "EXCLUIR-PARTICIPANTE" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$grp_id = 0;
			$grp_hashkey = '';

			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();


				$partc_hashkey = $this->request->getPost('partc_hashkey');


				$query_participante = $this->partcMD
					//->where('insti_id', (int)$insti_id)
					//->where('grp_id', (int)$grp_id)
					->where('partc_hashkey', $partc_hashkey)
					->limit(1)
					->get();
				if( $query_participante && $query_participante->resultID->num_rows >= 1 )
				{
					$this->partcMD->where('partc_hashkey', $partc_hashkey)->delete();

					$error_num = "0";
					$error_msg = "Participante removido com sucesso.";
				}else{
					$error_num = "1";
					$error_msg = "Participante inexistente.";
				}
			}

			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "LIST-PARTICIPANTE-POR-CATEG" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$grp_id = (int)$this->request->getPost('grp_id');
			$grp_hashkey = '';

			$func_id = 4;

			$participantes = [];

			// Recuperamos o evento selecionado
			// ---------------------------------------------------------
			$event_hashkey = $this->request->getPost('event_hashkey');
			$categ_id = $this->request->getPost('corgf_categ_id');
			
			$participantes_json = $this->request->getPost('participantes_json');

			$this->eventMD->select('*');
			$this->eventMD->where('event_hashkey', $event_hashkey);
			$this->eventMD->orderBy('event_id', 'DESC');
			$this->eventMD->limit(1);
			$query_event = $this->eventMD->get();
			if( $query_event && $query_event->resultID->num_rows >=1 )
			{
				$rs_event = $query_event->getRow();
				$insti_id = (int)$rs_event->insti_id; 
				//$event_id = (int)$rs_event->event_id;

				$query_participante = $this->partcMD
					->select('partc_id, partc_nome, partc_documento')
					->where('insti_id', (int)$insti_id)
					->where('categ_id', (int)$categ_id)
					->where('func_id', (int)$func_id)
					->where('grp_id', (int)$grp_id)
					->get();
				if( $query_participante && $query_participante->resultID->num_rows >= 1 )
				{
					$participantes = $query_participante->getResult();

					$error_num = "0";
					$error_msg = "Lista de Participantes";
				}else{
					$error_num = "1";
					$error_msg = "Não existe participantes relacionados a este grupo";
				}
			}else{
				$error_num = "1";
				$error_msg = "Evento não encontrado";
			}

			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
				"participantes" => $participantes,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "LIST-PARTICIPANTE-COREOGRAFOS" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$coreografos = '';

			// Recuperamos o evento selecionado
			// ---------------------------------------------------------
			$event_hashkey = $this->request->getPost('event_hashkey');
			$grp_id = (int)$this->request->getPost('grp_id');

			$this->eventMD->select('*');
			$this->eventMD->where('event_hashkey', $event_hashkey);
			$this->eventMD->orderBy('event_id', 'DESC');
			$this->eventMD->limit(1);
			$query_event = $this->eventMD->get();
			if( $query_event && $query_event->resultID->num_rows >=1 )
			{
				$rs_event = $query_event->getRow();
				$insti_id = (int)$rs_event->insti_id; 
				$func_id = 3; 

				$query_coreografos = $this->partcMD
					->select('partc_id, partc_nome, partc_documento')
					->where('insti_id', (int)$insti_id)
					->where('func_id', $func_id)
					->where('grp_id', (int)$grp_id)
					->get();
				if( $query_coreografos && $query_coreografos->resultID->num_rows >= 1 )
				{
					$coreografos = $query_coreografos->getResult();

					$error_num = "0";
					$error_msg = "Lista de Participantes Ok | ". $query_coreografos->resultID->num_rows;
					//$error_msg = $this->partcMD->getLastQuery();
					//print $error_msg;
					//exit();

				}else{
					$error_num = "1";
					$error_msg = "Não existe participantes relacionados a este grupo";
				}
			}else{
				$error_num = "1";
				$error_msg = "Evento não encontrado";
			}

			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
				"coreografos" => $coreografos,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "SALVAR-ELENCO-COREOGRAFIA" :
			$arr_dados = [];

			$user_id = (int)session()->get('inscUser_id');
			$grp_hashkey = '';

			if ($this->request->getPost())
			{
				$prosseguir = true;
				$validation =  \Config\Services::validation();

				// Recuperamos o evento selecionado
				// ---------------------------------------------------------
				$event_hashkey = $this->request->getPost('event_hashkey');
				$grp_id = (int)$this->request->getPost('grp_id');

				$corgf_titulo = $this->request->getPost('corgf_titulo');
				$corgf_coreografo = $this->request->getPost('corgf_coreografo');
				$corgf_musica = $this->request->getPost('corgf_musica');
				$corgf_compositor = $this->request->getPost('corgf_compositor');
				$corgf_observacao = $this->request->getPost('corgf_observacao');
				$corgf_modl_id = $this->request->getPost('corgf_modl_id');
				$corgf_formt_id = $this->request->getPost('corgf_formt_id');
				$corgf_categ_id = $this->request->getPost('corgf_categ_id');

				$coreografia_elenco_json = $this->request->getPost('coreografia_elenco_json');


				$this->eventMD->select('*');
				$this->eventMD->where('event_hashkey', $event_hashkey);
				$this->eventMD->orderBy('event_id', 'DESC');
				$this->eventMD->limit(1);
				$query_event = $this->eventMD->get();
				if( $query_event && $query_event->resultID->num_rows >=1 )
				{
					$rs_event = $query_event->getRow();
					$insti_id = (int)$rs_event->insti_id; 
					$event_id = (int)$rs_event->event_id; 

					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações da coreografia
					 * -------------------------------------------------------------
					**/
						$data_coreografia_db = [
							'corgf_id' => (int)$corgf_id,
							'partc_id' => (int)$partc_id,
							'crfpadte_cadastro' => date("Y-m-d H:i:s"),
							'crfpadte_alteracao' => date("Y-m-d H:i:s"),
						];
						$data_coreografia_db = [
							'insti_id' => (int)$insti_id,
							'grp_id' => (int)$grp_id,
							'modl_id' => (int)$corgf_modl_id,
							'formt_id' => (int)$corgf_formt_id,
							'categ_id' => (int)$corgf_categ_id,
							'corgf_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
							'corgf_urlpage' => url_title( convert_accented_characters($corgf_titulo), '-', TRUE ),
							'corgf_titulo' => $corgf_titulo,
							'corgf_coreografo' => $corgf_coreografo,
							//'corgf_musica' => $corgf_musica,
							//'corgf_tempo' => $corgf_tempo,
							'corgf_compositor' => $corgf_compositor,
							'corgf_observacao' => $corgf_observacao,
							'corgf_dte_cadastro' => date("Y-m-d H:i:s"),
							'corgf_dte_alteracao' => date("Y-m-d H:i:s"),
							'corgf_ativo' => (int)$corgf_ativo,
						];
						$corgf_id = $this->corgfMD->insert($data_coreografia_db);
						//$query_elenco = $this->corgfMD
						//	//->where('insti_id', (int)$insti_id)
						//	->where('corgf_id', (int)$corgf_id)
						//	->where('corgf_id', (int)$corgf_id)
						//	->limit(1)
						//	->get();
						//if( $query_elenco && $query_elenco->resultID->num_rows == 0 )
						//{
						//	$crfpa_id = $this->crfpaMD->insert($data_coreografia_db);
						//}

					/*
					 * -------------------------------------------------------------
					 * Gravamos as informações dos participantes na tabela de coreografia x participantes
					 * -------------------------------------------------------------
					**/
						if( !empty( $coreografia_elenco_json ) ){
							$lista_elenco_json = json_decode($coreografia_elenco_json);
							foreach ($lista_elenco_json as $key => $val) {
								$partc_id = (int)$val->partc_id;

								$data_elenco_db = [
									'corgf_id' => (int)$corgf_id,
									'partc_id' => (int)$partc_id,
									'crfpadte_cadastro' => date("Y-m-d H:i:s"),
									'crfpadte_alteracao' => date("Y-m-d H:i:s"),
								];
								$query_elenco = $this->crfpaMD
									->where('corgf_id', (int)$corgf_id)
									->where('corgf_id', (int)$corgf_id)
									->limit(1)
									->get();
								if( $query_elenco && $query_elenco->resultID->num_rows == 0 )
								{
									$crfpa_id = $this->crfpaMD->insert($data_elenco_db);
								}
							}
						}

					$error_num = "0";
					$error_msg = "Participante Ok";
					$error_infos = "";
				}
			}

			$json_arr = [
				"error_num" => $error_num,
				"error_msg" => $error_msg,
			];
			print json_encode($json_arr);
			exit();

		break;
		case "autocomplete" :

			$arr_dados = [];
			$rs_clientes = [];

			$search = $this->request->getPost('search');

			$query = $this->clieMD
				->select('id, nome')
				->where('del', '0')
				->like('nome', $search)
				->orderBy('nome', 'ASC')
				->get();
			if( $query && $query->resultID->num_rows >=1 )
			{
				$rs_clientes = $query->getResult();
			}

			$arr_return = array(
				"clientes" => $rs_clientes,
			);
			echo( json_encode($arr_return) );
			exit();

		break;
		case "DELETAR-REGISTRO" :

			$codigo = (int)$this->request->getPost('codigo');
			$query = $this->clieMD
				->select('*')
				->where('id', $codigo)
				->orderBy('id', 'DESC')
				->limit(1)
				->get();
			if( $query && $query->resultID->num_rows >=1 )
			{
				$rs_registro = $query->getRow();
				//$this->clieMD->where('id', $cliente_id);
				//$this->clieMD->delete();

				$data_db = [ 'del' => '1' ];
				$this->clieMD->set($data_db);
				$this->clieMD->where('id', $codigo);
				$this->clieMD->update();

				$error_num = 0;
				$error_msg = "Ação registrada com sucesso!";
			}

			$arr_return = array(
				"error_num" => $error_num,
				"error_msg" => $error_msg,
			);
			echo( json_encode($arr_return) );
			exit();

		break;
		}
	}



}
