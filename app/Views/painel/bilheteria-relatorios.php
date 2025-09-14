<?php 
	$this->extend('painel/templates/template_painel');
	$this->section('content'); 
?>

	<div class="box-breadcrumb">
		<div class="row">
			<div class="col-12">
				<h2 class="page-title">Gerenciamento de Relatórios : Bilheteria</h2>
			</div>
		</div>
	</div>

	<div id="app">
		<div class="row align-items-start">
			<div class="col-12 col-md-12">

				<div class="row align-items-start">
					<div class="col-12 col-md-12">

						<div class="card card-default mb-3">
							<div class="card-body py-3">

								<!-- Pesquisa Avançada -->
								<div class="mb-5 pb-3" style="border-bottom: 1px dashed #d7d7d7;">
									<h4 class="text-center mb-3">Pesquisa Avançada</h4>
									<div class="row align-items-end">
										<div class="col-12 col-md-3">
											<div class="form-group">
												<label class="form-label" for="curso_titulo">Palavra Chave</label>
												<input type="text" name="wkshp_insc_nome_completo" id="wkshp_insc_nome_completo" class="form-control" value="" />
											</div>
										</div>
										<div class="col-12 col-md-3">
											<div class="form-group">
												<label class="form-label" for="curso_titulo">Tipo</label>
												<select class="form-select" name="partc_genero" id="partc_genero">
													<option value="" translate="no">- selecione -</option>
													<option value="iniciante">Iniciante</option>
													<option value="intermediario">Intermediário</option>
													<option value="avancado">Avançado</option>
												</select>
											</div>
										</div>
										<div class="col-12 col-md-4">
											<div class="row align-items-end">
												<div class="col-12 col-md-6">
													<div class="form-group">
														<label class="form-label" for="curso_dte_inicio">Início</label>
														<div class="position-relative d-flex align-items-center">
															<input type="text" name="curso_dte_inicio" id="curso_dte_inicio" class="form-control flatpickr_date" value="" style="padding-right: 3rem !important;" />
															<span class="position-absolute mx-4" style="right: 0;">
																<img src="assets/svg/icon-calendar.svg" />
															</span>
														</div>
													</div>
												</div>
												<div class="col-12 col-md-6">
													<div class="form-group">
														<label class="form-label" for="curso_dte_inicio">Término</label>
														<div class="position-relative d-flex align-items-center">
															<input type="text" name="curso_dte_inicio" id="curso_dte_inicio" class="form-control flatpickr_date" value="" style="padding-right: 3rem !important;" />
															<span class="position-absolute mx-4" style="right: 0;">
																<img src="assets/svg/icon-calendar.svg" />
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-md">
											<div class="form-group">
												<div class="d-grid">
													<a href="<?php echo(painel_url('bilheteria/list')); ?>" class="btn btn-success" value="Salvar">Pesquisar</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row align-items-start">
									<div class="col-12 col-md-12">
										
										<h4 class="text-center mb-3">Resultado da Pesquisa</h4>

										<div class="row justify-content-end align-items-center mb-2">
											<div class="col-12 col-md-2">
												<div class="d-grid">
													<div class="dropdown">
														<button class="btn btn-sm btn-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
														Ações
														</button>
														<ul class="dropdown-menu dropdown-menu-end">
															<li><a class="dropdown-item" href="<?php echo( painel_url('bilheteria/ingresso')) ?>"><i class="las la-print" style="font-size: 18px;"></i> Imprimir</a></li>
															<li><a class="dropdown-item" href="<?php echo( painel_url('bilheteria/ingresso')) ?>"><i class="las la-file-pdf" style="font-size: 18px;"></i> Exportar PDF</a></li>
															<li><a class="dropdown-item" href="<?php echo( painel_url('bilheteria/ingresso')) ?>"><i class="las la-file-excel" style="font-size: 18px;"></i> Exportar Excel</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>

										<table id="example3" class="display nowrap table table-striped table-bordered m-0" style="width: 100%; background-color: white; border-radius: 8px;">
											<thead>
												<tr>
													<td style="width: 200px;"><strong>Código de Identificação</strong></td>
													<td style="width: 225px;"><strong>Status</strong></td>
													<td><strong>Método Pagto</strong></td>
													<td style="width: 200px;"><strong></strong></td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>YX8WRH-0001</td>
													<td>
														<div class="d-flex justify-content-between align-items-center" style="gap: 4px;">
															<div class="d-flex flex-column" style="gap: 4px; width: calc(100% - 40px);">
																<div class="tagStatus approved">Aprovado</div>
																<div class="tagStatus accredited">Pagamento Realizado</div>
															</div>
														</div>
													</td> 
													<td>
														<div class="d-flex justify-content-between" style="gap:16px;">
															<div style="width: 50%;">
																<div>Transferência Bancária</div>
																<div>Pix</div>
															</div>
															<div style="width: 50%;">
																<div>Parcelas: 1</div>
																<div>Data: 23/05/2024 12:17</div>
															</div>
														</div>
													</td> 
													<td></td> 
												</tr>
												<tr>
													<td>QTY2VU-0001</td>
													<td>
														<div class="d-flex justify-content-between align-items-center" style="gap: 4px;">
															<div class="d-flex flex-column" style="gap: 4px; width: calc(100% - 40px);">
																<div class="tagStatus approved">Aprovado</div>
																<div class="tagStatus accredited">Pagamento Realizado</div>
															</div>
														</div>
													</td> 
													<td>
														<div class="d-flex justify-content-between" style="gap:16px;">
															<div style="width: 50%;">
																<div>Transferência Bancária</div>
																<div>Pix</div>
															</div>
															<div style="width: 50%;">
																<div>Parcelas: 1</div>
																<div>Data: 23/05/2024 12:11</div>
															</div>
														</div>
													</td> 
													<td></td> 
												</tr>
												<tr>
													<td>5Q3CFF-0001</td>
													<td>
														<div class="d-flex justify-content-between align-items-center" style="gap: 4px;">
															<div class="d-flex flex-column" style="gap: 4px; width: calc(100% - 40px);">
																<div class="tagStatus approved">Aprovado</div>
																<div class="tagStatus accredited">Pagamento Realizado</div>
															</div>
														</div>
													</td> 
													<td>
														<div class="d-flex justify-content-between" style="gap:16px;">
															<div style="width: 50%;">
																<div>Transferência Bancária</div>
																<div>Pix</div>
															</div>
															<div style="width: 50%;">
																<div>Parcelas: 1</div>
																<div>Data: 23/05/2024 12:07</div>
															</div>
														</div>
													</td> 
													<td></td> 
												</tr>
											</tbody>
										</table>
									</div>
								</div>


							</div>
						</div>

					</div>
				</div>


			</div>
		</div>
	</div>



<?php
	$this->endSection('content'); 
?>

<?php $time = time(); ?>
<?php $this->section('headers'); ?>

	<style>
		.list_cart{
			margin: 3px 0;
		}
		.list_cart a{
			border: 1px solid #ebeced;
			padding: 8px;
			display: block;
			border-radius: 0.25rem;
			color: #000;
		}
		.list_cart a:hover{
			background-color: #edeeef;
			color: #000;
		}
	</style>
	<style>
		.table th {
			color: #303e67;
			font-weight: 500;
			vertical-align: middle;
			border-color: #4f4f4f;
			background-color: #f8f8f8;
		}
		.table-box {
			width: 100%;
			border: 1px solid  #f2f2f2;
			border-radius: 0.35rem !important;
			padding: 8px;
		}
		.table td {
			border-color: #dee2e6 !important;
			/*border-width: 1px !important;*/
			vertical-align: top;
		}

		div.dataTables_wrapper div.dataTables_length select {
			width: auto;
			display: inline-block;
			padding-top: 0.25rem !important;
			padding-bottom: 0.25rem !important;
			padding-left: 0.5rem !important;
			padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			padding: 0 !important;
			margin-left: 2px !important;
			border: 0px solid transparent !important;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
			border: 0px solid #fff !important;
			background-color: #585858 !important;
			background-color: #ffffff !important;
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #ffffff));
			background: -webkit-linear-gradient(top, #ffffff 0%, #ffffff 100%);
			background: -moz-linear-gradient(top, #ffffff 0%, #ffffff 100%);
			background: -ms-linear-gradient(top, #ffffff 0%, #ffffff 100%);
			background: -o-linear-gradient(top, #ffffff 0%, #ffffff 100%);
			background: linear-gradient(to bottom, #ffffff 0%, #ffffff 100%);
			box-shadow: inset 0 0 3px #ffffff;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button:active {
			outline: none;
			background-color: #ffffff !important;
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #ffffff)) !important;
			background: -webkit-linear-gradient(top, #ffffff 0%, #ffffff 100%) !important;
			background: -moz-linear-gradient(top, #ffffff 0%, #ffffff 100%) !important;
			background: -ms-linear-gradient(top, #ffffff 0%, #ffffff 100%) !important;
			background: -o-linear-gradient(top, #ffffff 0%, #ffffff 100%) !important;
			background: linear-gradient(to bottom, #ffffff 0%, #ffffff 100%) !important;
			box-shadow: inset 0 0 3px #ffffff !important;
		}
	</style>

<?php $this->endSection('headers'); ?>

<?php $this->section('scripts'); ?>

	<style>
		.symbol {
			display: inline-block;
			flex-shrink: 0;
			position: relative;
			border-radius: .475rem;
		}
		.symbol>img {
			width: 100%;
			flex-shrink: 0;
			display: inline-block;
			border-radius: .475rem;
		}
		.symbol.symbol-45px>img {
			width: 45px;
			height: 45px;
		}


		.card.card-counter .card-body{
			padding: 1rem 1rem !important;	
		}



		.card.card-green{ border: 1px solid rgb(61 207 61 / 80%) !important; }
		.card.card-green .card-header{ background-color: #eeffed; }
		.card.card-green .body-color{ background-color: #eeffed; border-radius: .35rem !important; }
		.card.card-green .card-icon-circle{ background-color: #ffffff; border: 1px solid rgb(61 207 61 / 80%) !important; }

		.card.card-orange{ border: 1px solid rgb(255 196 128 / 80%) !important; }
		.card.card-orange .card-header{ background-color: #fffbd9; }
		.card.card-orange .body-color{ background-color: #fffbd9; border-radius: .35rem !important; }
		.card.card-orange .card-icon-circle{ background-color: #ffffff; border: 1px solid rgb(255 196 128 / 80%) !important; }

		.card.card-red{ border: 1px solid rgb(253 153 153 / 80%) !important; }
		.card.card-red .card-header{ background-color: #fff1f1; }
		.card.card-red .body-color{ background-color: #fff1f1; border-radius: .35rem !important; }
		.card.card-red .card-icon-circle{ background-color: #ffffff; border: 1px solid rgb(253 153 153 / 80%) !important; }

		.card.card-blue{ border: 1px solid rgb(136 185 227 / 80%) !important; }
		.card.card-blue .card-header{ background-color: #c8ecf7; }
		.card.card-blue .body-color{ background-color: #c8ecf7; border-radius: .35rem !important; }
		.card.card-blue .card-icon-circle{ background-color: #ffffff; border: 1px solid rgb(136 185 227 / 80%) !important; }

		.card.card-pink{ border: 1px solid rgb(225 105 162 / 80%) !important; }
		.card.card-pink .card-header{ background-color: #e7daf1; }
		.card.card-pink .body-color{ background-color: #e7daf1; border-radius: .35rem !important; }
		.card.card-pink .card-icon-circle{ background-color: #ffffff; border: 1px solid rgb(225 105 162 / 80%) !important; }

		.card-icon-circle {
			font-size: 28px;
			width: 52px;
			height: 52px;
			align-items: center;
			justify-content: center;
			display: flex;
			border-radius: 50%;
		}

		.card.card-indic{
			padding: 32px 16px;
			border: 0;
			background-color: #eee;
			text-align: center;		
		}
		.card.card-indic h4{ 
			margin: 0;
			margin-bottom: 10px;
			font-size: 1.2rem;
			font-weight: normal;		
		}
		.card.card-indic h3{ margin: 0; font-size: 1.5rem; }


		.card-indic-gen{ }
		.card-indic-gen .itemGen{ 
			width: 25%; 
			border: 1px dotted #FFFFFF; 
			border-radius: 4px; 
			margin-right: 4px; 
			padding: 12px 2px; 
		}
		.card-indic-gen .itemGen:last-child{ margin-right: 0px; }
		.card-indic-gen .itemGen .txt{ line-height: 1.1; }
		.card-indic-gen .itemGen h3{ margin-top: 4px; }


		.card.card-resultitens{
			height: auto;
			border-radius: 8px;
			border: 1px solid #dbdbdb;
			border: 1.5px solid #5356FB30 !important;
			background-color: #fafafa;
			/*padding: 16px;*/
			margin-bottom: 12px;
		}
		.card.card-resultitens.active{
			background-color: #e2fff3;
		}
		.card.card-resultitens .card-body{
			padding: 1rem 0rem !important;
		}

		.itemDots{
			position: relative;
			margin-right: 12px;		
		}
		.itemDots:not(:last-child):after {
			content: "\2022";
			margin-left: 12px;
			color: #6C6D70;
		}

		.list-grupo-info{}
		.list-grupo-info li{ display: flex; }
		.list-grupo-info li .label{ font-size: 1rem; font-weight: 600; }
		.list-grupo-info li .value{ padding-left:10px; font-size: 1rem; font-weight: lighter; }

		.list-partc{}
		.list-partc .item{ margin-bottom: 4px; }
		.list-partc .item span{ display: block; font-size: .6rem; color: gray; }

		.table-participantes td{ font-size: .75rem; }
		.table-participantes thead tr { background-color: #e8e8e8; }
		.table-participantes thead tr td{ font-weight: bold; }


		#chart {
			max-width: 650px;
			margin: 35px auto;
		}
	</style>




	<link href="assets/plugins/DataTables/DataTables-1.13.4/css/jquery.dataTables.css" rel="stylesheet">
	<link href="assets/plugins/DataTables/DataTables-1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

	<script src="assets/plugins/DataTables/DataTables-1.13.4/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables/DataTables-1.13.4/js/dataTables.bootstrap5.min.js"></script>

	<!-- Sweet Alert -->
	<link href="assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
	<script src="assets/plugins/sweet-alert2/sweetalert2.min.js"></script>

	<script>
		//let LIST_PRODUTOS = [];
		//let LIST_STATUS = [];
		//let CLIENTE_ID = '<?php echo( $cliente_id ); ?>';
	</script>

	<!-- VueJs -->
	<!-- <script src="assets/vue/vue.min.js"></script> -->
	<!-- <script src="assets/vue/axios.min.js"></script> -->
	<!-- <script src="assets/vue/carrinho.js"></script> -->


	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = {
          series: [{
          name: 'PRODUCT A',
          data: [44, 55, 41, 67, 22, 43]
        }, {
          name: 'PRODUCT B',
          data: [13, 23, 20, 8, 13, 27]
        }, {
          name: 'PRODUCT C',
          data: [11, 17, 15, 15, 21, 14]
        }, {
          name: 'PRODUCT D',
          data: [21, 7, 25, 13, 22, 8]
        }],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        xaxis: {
          type: 'datetime',
          categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
            '01/05/2011 GMT', '01/06/2011 GMT'
          ],
        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

	<script>
		let LIST_CATEGORIA = [];
	</script>

	<script>
		//function converterData(data) {
		//	var partes = data.split("/");
		//	var dataFormatada = partes[2] + "-" + partes[1] + "-" + partes[0];
		//	return dataFormatada;
		//}

		//var dataBrasileira = "18/05/2023";
		//var dataAmericana = converterData(dataBrasileira);
		//console.log(dataAmericana); // Saída: 2023-05-18
		$(document).ready(function () {
			
			$(document).on('click', '.cmdFiltrar', function (e) {
				e.preventDefault();

				let $bsc_vendedor = $("#bsc_vendedor").val();
				let $bsc_cliente = $("#bsc_cliente").val();
				let $bsc_data_inicial = $("#bsc_data_inicial").val();
				let $bsc_data_final = $("#bsc_data_final").val();
				let $bsc_status = $("#bsc_status").val();

				let $url = '';
				if( $bsc_vendedor.length > 0 )	{ $url = $url +'/vendedor:'+ $bsc_vendedor; }
				if( $bsc_cliente.length > 0 )	{ $url = $url +'/cliente:'+ $bsc_cliente; }
				if( $bsc_data_inicial.length > 0 )	{ $url = $url +'/data_inicial:'+ ($bsc_data_inicial); }
				if( $bsc_data_final.length > 0 )	{ $url = $url +'/data_final:'+ ($bsc_data_final); }
				if( $bsc_status.length > 0 )	{ $url = $url +'/status:'+ $bsc_status; }

				//console.log( painel_url  +'historico/filtrar'+ $url );
				window.location.href = painel_url  +'historico/filtrar'+ $url;
				return false;
			});
			$(document).on('click', '.cmdUpdateStatus', function (e) {
				e.preventDefault();

				let $this = $(this);
				let $codigo = $this.data( "codigo" );
				let $msg = $( ".msg-email" );

				Swal.fire({
					title: 'Atenção!',
					icon: 'warning',
					html:
						'Confirme a alteração de status deste pedido.',
					type: 'warning',
					showCancelButton: true,
					cancelButtonColor: "#AAAAAA",
					confirmButtonColor: "#3c973e",
					//confirmButtonColor: '$danger',
					//cancelButtonColor: '$success',
					confirmButtonText: 'Sim! Confirmo.',
					cancelButtonText: 'Cancelar',
					reverseButtons: true
				}).then(function(result) {
					if (result.value) {
						// ------------------------------------------------------
						let $formData = {
							venda_id: $codigo
						};

						$msg.html('Aguarde. Estamos processando').show();
						$.ajax({
							url: painel_url  +'pedidos/ajaxform/ALTERAR-STATUS',
							method:"POST",
							type: "POST",
							dataType: "json",
							data: $formData,
							crossDomain: true,
							beforeSend: function(response) {
								console.log('1 beforeSend');
								console.log(response);
							},
							complete: function(response) { 
								//console.log('3 complete');
								//console.log(response);
							},
							success:function(response){
								console.log('2 success');
								console.log(response);
								$msg.html(response.error_msg).show();
							},
							error: function (jqXHR, textStatus, errorThrown) {
							}
						});
						// ------------------------------------------------------
					}
				});
			});
			$(document).on('click', '.cmdArquivarRegistro', function (e) {
				e.preventDefault();
				let $this = $(this);
				let $codigo = $this.data( "codigo" );
				let $row = $this.closest( ".trRow" );

				Swal.fire({
					title: 'Atenção!',
					icon: 'warning',
					html:
						'Confirme o arquivamento deste pedido.',
					type: 'warning',
					showCancelButton: true,
					cancelButtonColor: "#AAAAAA",
					confirmButtonColor: "#3c973e",
					//confirmButtonColor: '$danger',
					//cancelButtonColor: '$success',
					confirmButtonText: 'Sim! Confirmo.',
					cancelButtonText: 'Cancelar',
					reverseButtons: true
				}).then(function(result) {
					if (result.value) {
						// ------------------------------------------------------
						let $formData = {
							codigo: $codigo
						};

						$.ajax({
							url: painel_url  +'historico/ajaxform/ARQUIVAR-REGISTRO',
							method:"POST",
							type: "POST",
							dataType: "json",
							data: $formData,
							crossDomain: true,
							beforeSend: function(response) {
								console.log('1 beforeSend');
								console.log(response);
							},
							complete: function(response) { 
								console.log('3 complete');
								console.log(response);
							},
							success:function(response){
								console.log('2 success');
								console.log(response);
								$row.remove();
							},
							error: function (jqXHR, textStatus, errorThrown) {
								console.log('4 error');
								console.log(errorThrown);
							}
						});
						// ------------------------------------------------------
					}
				});
			});
			$(document).on('click', '.cmdExcluirRegistro', function (e) {
				e.preventDefault();
				let $this = $(this);
				let $hashkey = $this.data( "codigo" );
				let $row = $this.closest( ".trRow" );

				Swal.fire({
					title: 'Atenção!',
					icon: 'warning',
					html:
						'Você está prestes a excluir este registros. <br>'+
						'Esta ação não poderá ser revertida.<br>'+
						'Deseja continuar assim mesmo?',
					type: 'warning',
					showCancelButton: true,
					cancelButtonColor: "#AAAAAA",
					confirmButtonColor: "#3c973e",
					//confirmButtonColor: '$danger',
					//cancelButtonColor: '$success',
					confirmButtonText: 'Sim! Desejo Excluir',
					cancelButtonText: 'Cancelar',
					reverseButtons: true
				}).then(function(result) {
					if (result.value) {
						// ------------------------------------------------------
						let $formData = {
							curso_hashkey: $hashkey
						};

						$.ajax({
							url: painel_url  +'cursos/ajaxform/EXCLUIR-REGISTRO',
							method:"POST",
							type: "POST",
							dataType: "json",
							data: $formData,
							crossDomain: true,
							beforeSend: function(response) {
								console.log('1 beforeSend');
								console.log(response);
							},
							complete: function(response) { 
								console.log('3 complete');
								console.log(response);
							},
							success:function(response){
								console.log('2 success');
								console.log(response);
								$row.remove();
							},
							error: function (jqXHR, textStatus, errorThrown) {
								console.log('4 error');
								console.log(errorThrown);
							}
						});
						// ------------------------------------------------------
					}
				});
			});

			var table = $('#example2').DataTable({
				"pageLength": 100,
				order: [[ 0, "desc" ]],
				responsive: true,
				searching: false,
				paging: true,
				pagingType: "full_numbers",
				fixedHeader: {
					header: true,
					footer: false
				},
				"language": {
					"search": "Procurar",
					"lengthMenu": "Mostrar _MENU_ registro por página",
					"zeroRecords": "Nothing found - sorry",
					"info": "Monstrando _PAGE_ de _PAGES_",
					"infoEmpty": "Sem registros disponíveis",
					"infoFiltered": "(filtered from _MAX_ total records)",
					"oPaginate": {
						"sNext": "Próximo",
						"sPrevious": "Anterior",
						"sFirst": "Primeiro",
						"sLast": "Último"
					},
				}
			});
			//new $.fn.dataTable.FixedHeader( table );
		});
	</script>


<?php $this->endSection('scripts'); ?>