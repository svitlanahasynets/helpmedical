<!-- BEGIN HEADER -->
<div class="homepage-header">
	<!-- BEGIN HEADER INNER -->
	<div class="homepage-header-inner">
		<!-- BEGIN LOGO -->
		<div class="homepage-logo">
			<a href="{{ route('homepage') }}">
				<img src="/assets/img/login/logo.png" alt="logo" class="logo-default"/>
			</a>
			<div class="menu-toggler sidebar-toggler hide">
			</div>
		</div>
		<!-- END LOGO -->

		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
		<!-- END RESPONSIVE MENU TOGGLER -->
	</div>
	<!-- END HEADER INNER -->

	<div class="site-name">
		<a href="{{ route('homepage') }}">첨단기술연구소 체육홈페지</a>
	</div>

</div>
<!-- END HEADER -->
<div class="clearfix">
</div>

<!-- BEGIN SLOGAN SECTION -->
<div class="slogan-section">
	@if (trim(strip_tags($slogan_titles['slogan'])) != '')
	<h4 class="carousel slide slogan" id="slogan" style="display: none;" data-interval="10000">
		<div class="carousel-inner">
	    	{!! str_replace('<p>', '<p class="item">', $slogan_titles['slogan']) !!}
	    </div>
	</h4>
	@endif
</div>
<!-- END SLOGAN SECTION -->