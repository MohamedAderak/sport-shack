<div class="goal-search-form">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
		<input type="hidden" name="post_type" value="product" class="post_type" />
		<div class="input-group">
			<input type="text" placeholder="<?php esc_attr_e( 'What do you need?', 'rashy' ); ?>" name="s" class="goal-search input-sm form-control" autocomplete="off"/>
			<span class="input-group-btn">
	  			<button type="submit" class="btn btn-theme radius-0 btn-sm"><i class="fa fa-search"></i></button>
			</span>
		</div>
	</form>
</div>