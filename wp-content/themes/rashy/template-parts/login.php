<div class="header_customer_login">
	<h2 class="title"><?php esc_html_e( 'Login', 'rashy' ); ?></h2>
	<form method="post" class="login" role="form">

		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<p class="form-group form-row form-row-wide">
			<label for="username"><?php esc_html_e( 'Username or email address', 'rashy' ); ?> <span class="required">*</span></label>
			<input type="text" class="input-text form-control" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
		</p>
		<p class="form-group form-row form-row-wide">
			<label for="password"><?php esc_html_e( 'Password', 'rashy' ); ?> <span class="required">*</span></label>
			<input class="input-text form-control" type="password" name="password" id="password" />
		</p>

		<?php do_action( 'woocommerce_login_form' ); ?>
		<div class="form-group form-row">
			<span class="inline">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'rashy' ); ?>
			</span>
		</div>
		<div class="form-group form-row">
			<?php wp_nonce_field( 'woocommerce-login' ); ?>
			
			<input type="submit" class="btn btn-block btn-sm radius-6" name="login" value="<?php esc_html_e( 'sign in', 'rashy' ); ?>" />
		</div>

		<?php do_action( 'woocommerce_login_form_end' ); ?>

		<div class="form-group clearfix">
			<ul class="topmenu-menu">
				<li class="lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><i class="fa fa-repeat"></i> <?php esc_html_e( 'Lost your password?', 'rashy' ); ?></a>
				</li>
				<li class="register">
					<a class="register" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>?ac=register" title="<?php esc_attr_e('Register','rashy'); ?>"><?php esc_html_e('Register', 'rashy'); ?></a>
				</li>
			</ul>
		</div>
	</form>
</div>