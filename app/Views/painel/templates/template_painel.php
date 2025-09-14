<?php $time = time(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?php echo(base_url()); ?>/" />
	<title>JA FestOn</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta charset="utf-8" />

	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="" />
	<meta property="og:url" content="" />
	<meta property="og:site_name" content="" />

	<link rel="canonical" href="<?php echo(current_url()); ?>" />
	<link rel="shortcut icon" href="assets/media/favicon.png" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
	<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link href="assets/line-awesome/css/line-awesome.min.css" rel="stylesheet" type="text/css" />

	<!-- <link href="assets/css/reset.css" rel="stylesheet" type="text/css" /> -->
	<link href="assets/css/style.css" rel="stylesheet" type="text/css" />

	<!-- choose one -->
	<script src="https://unpkg.com/feather-icons"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

	<?php $this->renderSection('headers'); ?>
</head>
<body>

	<?php
		$m_ac = (isset($menu_active) ? $menu_active : '');

		$session_id = (isset($session_id) ? $session_id : '');
		$session_user_id = (int)(isset($session_user_id) ? $session_user_id : ''); 
		$session_user_nome =(isset($session_user_nome) ? $session_user_nome : ''); 
		$session_user_permissao = (int)(isset($session_user_permissao) ? $session_user_permissao : ''); 
		$session_user_label_permissao = (isset($session_user_label_permissao) ? $session_user_label_permissao : '');
	?>

    <!--
	<div class="l-navbar" id="nav-bar" style="height: 100% !important;">
		<div class="bar-icon cmdOpenMenu"><i class="las la-bars"></i> Menu</div>
        <nav class="nav" style="overflow: scroll !important; height: 100% !important; scrollbar-width: none;">
            <div> 
				<div class="d-flex" style="padding: 0rem 4.5rem;">
					<img src="assets/media/logotipo.png" class="img-fluid" />
				</div>

				<div class="nav_user d-none">

				</div>
                <div class="nav_list" style="padding-top:20px !important;"> 

					<a href="<?php echo(painel_url('login')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Login</span></a>
					<a href="<?php echo(painel_url('eventos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Eventos</span></a>
					<a href="<?php echo(painel_url('generos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Gêneros</span></a>
					<a href="<?php echo(painel_url('subgeneros')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">SubGêneros</span></a>
					<a href="<?php echo(painel_url('categorias')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Categorias</span></a>
					<a href="<?php echo(painel_url('grupos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Grupos</span></a>

					<a href="<?php echo(painel_url('cursos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Cursos</span></a>
					<a href="<?php echo(painel_url('cursistas')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Cursistas</span></a>
					<a href="<?php echo(painel_url('participantes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Participantes</span></a>

					<a href="<?php echo(painel_url('coreografias')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Coreografias</span></a>
					<a href="<?php echo(painel_url('instituicoes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Instituições</span></a>

					<br>
					
					<a href="<?php echo(painel_url('perfil/form')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Perfil</span></a>
					<a href="<?php echo(painel_url('apresentacoes/form')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Apresentações</span></a> 
					
					
					
					

					<br>
					<a href="<?php echo(painel_url('ensaios/form')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Ensaios</span></a>

				</div>
			</div> 
        </nav>
    </div>
	-->

	<div class="nftmax-smenu">
		<div class="admin-menu">
            <div> 
				<div class="d-flex" style="padding: 0rem 4.5rem;">
					<img src="assets/media/logotipo.png" class="img-fluid" />
				</div>
                <div class="nav_list" style="padding-top:20px !important;"> 


					<a href="<?php echo(painel_url('grupos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Grupos</span></a>
					<a href="<?php echo(painel_url('participantes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Participantes</span></a>
					<a href="<?php echo(painel_url('coreografias')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Coreografias</span></a>
					
					<!-- <a href="<?php echo(painel_url('login')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Login</span></a> -->
					<a href="<?php echo(painel_url('eventos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Eventos</span></a>
					<a href="<?php echo(painel_url('modalidades')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Modalidades</span></a>
					<a href="<?php echo(painel_url('formatos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Formatos</span></a>
					<a href="<?php echo(painel_url('categorias')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Categorias</span></a>



					
					<a href="<?php echo(painel_url('funcoes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Funções</span></a>

					<a href="<?php echo(painel_url('workshops')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Workshops</span></a>
					<a href="<?php echo(painel_url('bilheteria')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Bilheteria</span></a>
					<!-- <a href="<?php echo(painel_url('cursistas')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Cursistas</span></a> -->

					<a href="<?php echo(painel_url('instituicoes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Instituições</span></a>
					<a href="<?php echo(painel_url('jurados')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Jurados</span></a>
					<a href="<?php echo(painel_url('anuncios')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Anúncios</span></a>
					<a href="<?php echo(painel_url('autorizacoes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Termos/Autoriz</span></a>
					<a href="<?php echo(painel_url('particautorizacoes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Autoriz Partic</span></a>
					<a href="<?php echo(painel_url('criterios')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Critérios</span></a>
					<a href="<?php echo(painel_url('recursoshumanos')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Recursos Humanos</span></a>

					<a href="<?php echo(painel_url('configuracoes')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Configurações</span></a>


					<div class="mt-3">
						<h4>Usuários</h4>
						<a href="<?php echo(painel_url('administradores')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Administradores</span></a>
						<a href="<?php echo(painel_url('responsaveis')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Resp. pelos Grupos</span></a>
					</div>

					<br>
					<br>
					<br>
					
					<!--
					<hr>
					<a href="<?php echo(painel_url('perfil/form')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Perfil</span></a>
					<a href="<?php echo(painel_url('apresentacoes/form')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Apresentações</span></a> 
					<br>
					<a href="<?php echo(painel_url('ensaios/form')); ?>" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Ensaios</span></a>
					-->

				</div>
			</div> 
		</div>
	</div>

	<main>
		<div class="page-content" style="padding-top: 0; margin-top: 0;">
			<div class="container-fluid" style="border-bottom: 1px solid  #f2f2f2; padding-bottom: 8px; margin-bottom: 12px;">
				<div class="row justify-content-end align-items-start">
					<div class="col-12 col-md-9">

					</div>
					<div class="col-12 col-md-3">
						<div class="d-flex align-items-center">
							<div style="padding-right: 12px;">
								<img src="assets/media/icon-usuario.png" style="width: 40px;" />
							</div>
							<div>
								<?php
									echo('<h4>'. $session_user_id .' | '.$session_user_nome .'</h4>');
								?>
								<div class="pt-1" style="line-height:1"><a href="<?php echo(painel_url('logout')); ?>" style="line-height:1">Sair</a></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="d-none">
				<?php
					//$lastQuery = (isset($lastQuery) ? $lastQuery : '');
					//print('<pre>');
					//print_r( $lastQuery );
					//print('</pre>');
				?>
			</div>

			<?php 
				$this->renderSection('content');
			?>

			</div>
		</div>
	</main>


	<?php $this->renderSection('modals'); ?>


	<style>
		/*======================================
			Theme Default
		========================================*/
		.l-navbar .nav{
			  -ms-overflow-style: none;  /* IE and Edge */
			  scrollbar-width: none;  /* Firefox */
		}

		.nftmax-smenu {
			position: fixed;
			left: 0;
			z-index: 1000;
			height: 100%;
			transition: all 0.3s ease;
			transition: all 0.4s;
			width: 250px;
			transform: translateX(0%);
			box-shadow: 0 9px 95px 0 #0000000d;
		}
		.admin-menu {
			background: #fff;
			height: 100%;
			scrollbar-width: none;
			overflow: scroll;
			padding-left: 12px;
			padding-top: 10px;
			padding-right: 12px;
			transition: all .3s ease;
		}


		.step-cadastro{
			display: flex;

		}
		.step-cadastro li{
			margin: 0 1px;
			padding: 4px 12px;
			background-color: #FFFFFF;
			border-radius: .1rem;
			font-size: .7rem;
		}
		.step-cadastro li.active{
			background-color: #ffc107;
			color: white;
		}
	</style>

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="assets/js/jQuery-Mask-Plugin/dist/jquery.mask.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="assets/js/app_plugins.js"></script>

	<script src="assets/js/mustache.js"></script>

	<script>
		let painel_url = '<?php echo(painel_url()); ?>/';	
	</script>


	<script>
	feather.replace();

	$(document).ready(function () {
		$(document).on('click', '.cmdOpenMenu', function (e) {
			e.preventDefault();

			let $navBar = $('#nav-bar');
			//let $navBar = $('#nav-bar');

			if( $navBar.hasClass( "show" ) ){
				$navBar.removeClass('show');
			}else{
				$navBar.addClass('show');
			}

			//let $bsc_vendedor = $("#bsc_vendedor").val();
			//let $bsc_cliente = $("#bsc_cliente").val();
			//let $bsc_data_inicial = $("#bsc_data_inicial").val();
			//let $bsc_data_final = $("#bsc_data_final").val();
			//let $bsc_status = $("#bsc_status").val();

			//let $url = '';
			//if( $bsc_vendedor.length > 0 )	{ $url = $url +'/vendedor:'+ $bsc_vendedor; }
			//if( $bsc_cliente.length > 0 )	{ $url = $url +'/cliente:'+ $bsc_cliente; }
			//if( $bsc_data_inicial.length > 0 )	{ $url = $url +'/data_inicial:'+ ($bsc_data_inicial); }
			//if( $bsc_data_final.length > 0 )	{ $url = $url +'/data_final:'+ ($bsc_data_final); }
			//if( $bsc_status.length > 0 )	{ $url = $url +'/status:'+ $bsc_status; }

			////console.log( painel_url  +'historico/filtrar'+ $url );
			//window.location.href = painel_url  +'historico/filtrar'+ $url;
			return false;
		});
		$(document).on('change', '.mr-image-file', function (e) {
			let $this = $(this);
			let $mrInput = $this.closest('.mr-image-input');
			let $imgWrapper = $mrInput.find('.image-input-wrapper');
			let $iconDelete  = $mrInput.find('.mr-image-remove');

			let input = event.target;
			if (input.files && input.files[0]) {
				$iconDelete.addClass('active');
				//$mrInput.removeClass('image-input-empty');
				var reader = new FileReader();
				reader.onload = function(e) {
					$imgWrapper.css('background-image', 'url(' + e.target.result + ')');
				}
				reader.readAsDataURL(input.files[0]);

			}
		});
		$(document).on('click', '.mr-image-input [data-mr-image-input-action=remove]', function (e) {
			let $this = $(this);
			let $mrInput = $this.closest('.mr-image-input');
			let $imgWrapper = $mrInput.find('.image-input-wrapper');
			let $iconDelete  = $mrInput.find('.mr-image-remove');
			let $mrFile = $mrInput.find('.mr-image-file');
			$mrInput.addClass('image-input-empty');
			$imgWrapper.css('background-image', "url('assets/images/image-camera.png')");
			$mrFile.val('');
			$iconDelete.removeClass('active');
		});
	});
	</script>

	<?php $this->renderSection('scripts'); ?>

</body>
</html>