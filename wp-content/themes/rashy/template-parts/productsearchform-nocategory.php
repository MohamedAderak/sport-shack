
<div class="goal-search-form search-fix clearfix">
	<div class="goal-search-form-inner inner-search">
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<div class="main-search">
				<div class="autocompleate-wrapper">
			  		<input type="text" placeholder="<?php esc_attr_e( 'Search products here...', 'rashy' ); ?>" name="s" class="goal-search form-control goal-autocompleate-input" autocomplete="off"/>
				</div>
			</div>
			<input type="hidden" name="post_type" value="product" class="post_type" />
			<button type="submit" class="btn btn-theme radius-0"><i class="fa fa-search"></i></button>
		</form>
	</div>
</div>