<?php
namespace App\Controllers\Painel;
use App\Controllers\PainelController;

class Formatos extends PainelController
{
	protected $formtMD = null;

    public function __construct()
    {
        $this->formtMD = new \App\Models\FormatosModel();

		helper('form');
		helper('text');

		$this->data['menu_active'] = 'categorias';
    }


	public function index()
	{
		return self::filtrar();
	}


	public function filtrar()
	{
		$filtro_pdf = '';
		// filtrar/user:marcio/cliente:123/dini:/dteend:/status:pago

		$uri = service('uri'); // Obter a instância do objeto URI
		$segments = $uri->getSegments();
		$index = array_search('filtrar', $segments); // Encontrar o índice do segmento "filtrar"

		$filteredSegments = array_slice($segments, $index + 1); // Retornar os elementos a partir de $index + 1 até o final


		$this->formtMD
			->select("*")
			->select("DATE_FORMAT(formt_tempo_limit, '%i:%s') AS tempo_formatado")
			->where('insti_id', (int)$this->session_user_id)
			->orderBy('formt_id', 'DESC')
			->limit(1000);
		$query = $this->formtMD->get();

		$this->data['lastQuery'] = $this->formtMD->getLastQuery();
			//->getCompiledSelect();

		if( $query && $query->resultID->num_rows >=1 )
		{
			$this->data['rs_list'] = $query;
		}

		return view($this->directory .'/formatos', $this->data);
	}


	public function filtrar_old()
	{
		$filtro_pdf = '';
		// filtrar/user:marcio/cliente:123/dini:/dteend:/status:pago

		$uri = service('uri'); // Obter a instância do objeto URI
		$segments = $uri->getSegments();
		$index = array_search('filtrar', $segments); // Encontrar o índice do segmento "filtrar"

		$filteredSegments = array_slice($segments, $index + 1); // Retornar os elementos a partir de $index + 1 até o final

		$bsc_usuario = '';
		$bsc_cliente = '';
		$bsc_dte_inicial = '';
		$bsc_dte_final = '';
		$bsc_status = '';

		// vendedor:marcio/cliente:123/data_inicial:/data_final:/status:pago
		$arr_param_filtro = ["vendedor", "cliente", "data_inicial", "data_final", "status"];
		$rs_filtros = (object)[];

		foreach ($filteredSegments as $key => $val) {
			[$tag, $value] = explode(':', $val);
			if (in_array($tag, $arr_param_filtro)) {
				$rs_filtros->{$tag} = $value; 
				$filtro_pdf .=  '/'. $tag .':'. $value;  
			}
		}
		$this->data['rs_filtros'] = $rs_filtros;
		$this->data['linkGerarPDF'] = painel_url( 'historico/filtro_pdf'. $filtro_pdf );

		$this->vendMD->from('venda As VEND', true)
			->select('VEND.*')
			->select('STA.status')
			->select('CLI.nome')
			->select('USER.nome as userNome')
			->selectSum('( VITEM.valor * VITEM.qtd )', 'vlrTotal')
			->selectCount('VITEM.venda_id', 'qtdItens')
			//->select('0 As vlrTotal')
			//->select(" (SELECT SUM(valor) FROM venda_itens WHERE venda_id = VEND.id) as vlrTotal ")
			->join('venda_itens VITEM', 'VITEM.venda_id = VEND.id', 'INNER')
			->join('status STA', 'STA.id = VEND.status_id', 'LEFT')
			->join('cliente CLI', 'CLI.id = VEND.cliente_id', 'LEFT')
			->join('usuario USER', 'USER.id = VEND.usuario_id', 'LEFT');

		if( $this->session_user_permissao == '2'){ //  vendedores
			$this->vendMD->where('VEND.usuario_id', (int)$this->session_user_id);	
		};

		$this->vendMD->where('VEND.arquivo', '0');
		//$this->vendMD->where('VEND.del', '0');

		$bsc_vendedor = (isset($rs_filtros->vendedor) ? $rs_filtros->vendedor : '');
		$bsc_cliente = (isset($rs_filtros->cliente) ? $rs_filtros->cliente : '');
		$bsc_data_inicial = (isset($rs_filtros->data_inicial) ? $rs_filtros->data_inicial : '');
		$bsc_data_final = (isset($rs_filtros->data_final) ? $rs_filtros->data_final : '');
		$bsc_status = (isset($rs_filtros->status) ? $rs_filtros->status : '');

		if( !empty($bsc_vendedor) )			{ $this->vendMD->where('USER.id', $bsc_vendedor); }
		if( !empty($bsc_cliente) )			{ $this->vendMD->where('CLI.id', $bsc_cliente); }
		if( !empty($bsc_status) )			{ $this->vendMD->where('STA.id', $bsc_status); }
		if( !empty($bsc_data_inicial) )		{ $this->vendMD->where('VEND.data >=', ($bsc_data_inicial)); }
		if( !empty($bsc_data_final) )		{ $this->vendMD->where('VEND.data <=', ($bsc_data_final)); }

		$this->vendMD->groupBy('VEND.id')
			->orderBy('VEND.id', 'DESC')
			->limit(1000);
		$query = $this->vendMD->get();

		$this->data['lastQuery'] = $this->vendMD->getLastQuery();
			//->getCompiledSelect();

		if( $query && $query->resultID->num_rows >=1 )
		{
			$this->data['rs_list'] = $query;
		}


		$query_status = $this->statMD
			->select('id, status')
			->where('del', 0)
			->orderBy('status', 'ASC')
			->get();
		if( $query_status && $query_status->resultID->num_rows >=1 )
		{
			$this->data['rs_status'] = $query_status->getResult();
		}

		$query_vendedor = $this->userMD
			->select('*')
			->where('del', 0)
			->get();
		if( $query_vendedor && $query_vendedor->resultID->num_rows >=1 )
		{
			$this->data['rs_vendedor'] = $query_vendedor->getResult();
		}

		$query_cliente = $this->clieMD
			->select('*')
			->where('del', 0)
			->get();
		if( $query_cliente && $query_cliente->resultID->num_rows >=1 )
		{
			$this->data['rs_cliente'] = $query_cliente->getResult();
		}

		return view('subgeneros', $this->data);
	}


	public function form( $formt_id = 0 )
	{
		if ($this->request->getPost())
		{
			$validation =  \Config\Services::validation();
			$rules = [
				"formt_titulo" => [
					"label" => "Título", 
					"rules" => "required",
					'errors' => [
						'required' => 'Preencha corretamente',
					],
				],
			];

			if ($this->validate($rules)) {
				$formt_titulo = $this->request->getPost('formt_titulo');
				$formt_tempo_limit = $this->request->getPost('formt_tempo_limit');
				$formt_min_partic = $this->request->getPost('formt_min_partic');
				$formt_max_partic = $this->request->getPost('formt_max_partic');
				$formt_ativo = (int)$this->request->getPost('formt_ativo');
				
				$data_db = [
					'insti_id' => (int)$this->session_user_id,
					'formt_hashkey' =>md5(date("Y-m-d H:i:s") ."-". random_string('alnum', 16)),
					'formt_urlpage' => url_title( convert_accented_characters($formt_titulo), '-', TRUE ),
					'formt_titulo' => $formt_titulo,
					'formt_tempo_limit' => '00:'. $formt_tempo_limit,
					'formt_min_partic' => (int)$formt_min_partic,
					'formt_max_partic' => (int)$formt_max_partic,
					'formt_dte_cadastro' => date("Y-m-d H:i:s"),
					'formt_dte_alteracao' => date("Y-m-d H:i:s"),
					'formt_ativo' => $formt_ativo,
				];

				$queryEdit = $this->formtMD->where('formt_id', $formt_id)->get();
				if( $queryEdit && $queryEdit->resultID->num_rows >= 1 )
				{
					unset( $data_db['formt_hashkey'] );
					unset( $data_db['formt_dte_cadastro'] );
					$qryExecute = $this->formtMD->update($formt_id, $data_db);
				}else{
					$formt_id = $this->formtMD->insert($data_db);
				}

				return $this->response->redirect( painel_url('formatos') );
				exit();

			} else {
				$this->data['validation'] = $validation->getErrors();
			}
		}


		$query = $this->formtMD
			->select("*")
			->select("DATE_FORMAT(formt_tempo_limit, '%i:%s') AS tempo_formatado")
			->where('formt_id', $formt_id)
			->get();
		if( $query && $query->resultID->num_rows >= 1 )
		{
			$rs_dados = $query->getRow();
			$this->data['rs_dados'] = $rs_dados;
		}

		return view($this->directory .'/formatos-form', $this->data);
	}


	public function ajaxform( $action = "" )
	{
		$error_num = "1";
		$error_msg = "Erro inesperado";
		$redirect = "";

		switch ($action) {
		case "EXCLUIR-REGISTRO" :

			$formt_hashkey = $this->request->getPost('formt_hashkey');
			$query = $this->formtMD->where('formt_hashkey', $formt_hashkey)->get();
			if( $query && $query->resultID->num_rows >=1 )
			{
				$rs_registro = $query->getRow();
				$formt_id = (int)$rs_registro->formt_id;			

				// excluir registro
				$this->formtMD->where('formt_hashkey', $formt_hashkey)->delete();

				//$this->formtMD->set('solt_excluido', 1);
				//$this->formtMD->where('formt_hashkey', $formt_hashkey);
				//$this->formtMD->where('formt_id', $formt_id);
				//$this->formtMD->update();

				$error_num = "0";
				$error_msg = "Registro excluído com sucesso!";
				$redirect = "";
			}

			$arr_return = array(
				"error_num" => $error_num,
				"error_msg" => $error_msg,
				"redirect" => $redirect 
			);

			echo( json_encode($arr_return) );
			exit();
		break;
		}
	}

}
