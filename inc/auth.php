<?php
/**
 * Front-end login and registration shortcodes / helpers.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boot auth hooks.
 *
 * @return void
 */
function thw_theme_auth_init() {
	add_shortcode( 'thw_login_form', 'thw_theme_render_login_form' );
	add_shortcode( 'thw_register_form', 'thw_theme_render_register_form' );
	add_action( 'init', 'thw_theme_handle_registration', 20 );
	add_filter( 'login_redirect', 'thw_theme_login_redirect', 10, 3 );
	add_filter( 'wp_nav_menu_objects', 'thw_theme_filter_auth_menu_items', 10, 2 );
}
add_action( 'after_setup_theme', 'thw_theme_auth_init' );

/**
 * Login page URL.
 *
 * @return string
 */
function thw_theme_login_url() {
	$url = thw_theme_get_page_url( 'login' );
	return $url ? $url : wp_login_url();
}

/**
 * Registration page URL.
 *
 * @return string
 */
function thw_theme_register_url() {
	$url = thw_theme_get_page_url( 'register' );
	return $url ? $url : wp_registration_url();
}

/**
 * After login, send members to Today's Lesson when available.
 *
 * @param string           $redirect_to           Requested redirect.
 * @param string           $requested_redirect_to Original request.
 * @param WP_User|WP_Error $user                  User.
 * @return string
 */
function thw_theme_login_redirect( $redirect_to, $requested_redirect_to, $user ) {
	unset( $requested_redirect_to );

	if ( is_wp_error( $user ) ) {
		return $redirect_to;
	}

	if ( ! empty( $_REQUEST['redirect_to'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return $redirect_to;
	}

	$lesson = thw_theme_get_page_url( 'todays-lesson' );
	return $lesson ? $lesson : home_url( '/' );
}

/**
 * Process front-end registration form.
 *
 * @return void
 */
function thw_theme_handle_registration() {
	if ( empty( $_POST['thw_theme_register'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}

	if ( ! isset( $_POST['thw_theme_register_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['thw_theme_register_nonce'] ) ), 'thw_theme_register' ) ) {
		wp_safe_redirect( add_query_arg( 'thw_reg', 'invalid', thw_theme_register_url() ) );
		exit;
	}

	if ( is_user_logged_in() ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	if ( ! get_option( 'users_can_register' ) ) {
		wp_safe_redirect( add_query_arg( 'thw_reg', 'disabled', thw_theme_register_url() ) );
		exit;
	}

	$user_login = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ), true ) : '';
	$user_email = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
	$password   = isset( $_POST['user_pass'] ) ? (string) wp_unslash( $_POST['user_pass'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$password2  = isset( $_POST['user_pass_confirm'] ) ? (string) wp_unslash( $_POST['user_pass_confirm'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( '' === $user_login || '' === $user_email || '' === $password ) {
		wp_safe_redirect( add_query_arg( 'thw_reg', 'missing', thw_theme_register_url() ) );
		exit;
	}

	if ( $password !== $password2 ) {
		wp_safe_redirect( add_query_arg( 'thw_reg', 'mismatch', thw_theme_register_url() ) );
		exit;
	}

	if ( strlen( $password ) < 8 ) {
		wp_safe_redirect( add_query_arg( 'thw_reg', 'weak', thw_theme_register_url() ) );
		exit;
	}

	$user_id = wp_create_user( $user_login, $password, $user_email );
	if ( is_wp_error( $user_id ) ) {
		$code = $user_id->get_error_code();
		$key  = in_array( $code, array( 'existing_user_login', 'existing_user_email' ), true ) ? 'exists' : 'error';
		wp_safe_redirect( add_query_arg( 'thw_reg', $key, thw_theme_register_url() ) );
		exit;
	}

	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true );
	do_action( 'wp_login', $user_login, get_userdata( $user_id ) );

	$redirect = thw_theme_get_page_url( 'todays-lesson' );
	wp_safe_redirect( $redirect ? $redirect : home_url( '/' ) );
	exit;
}

/**
 * Registration status notice.
 *
 * @return string
 */
function thw_theme_registration_notice() {
	if ( empty( $_GET['thw_reg'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}

	$code = sanitize_key( wp_unslash( $_GET['thw_reg'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$map  = array(
		'disabled' => __( 'Registration is currently closed on this site.', 'the-hidden-word-theme' ),
		'missing'  => __( 'Please fill in username, email, and password.', 'the-hidden-word-theme' ),
		'mismatch' => __( 'Passwords do not match.', 'the-hidden-word-theme' ),
		'weak'     => __( 'Please choose a password with at least 8 characters.', 'the-hidden-word-theme' ),
		'exists'   => __( 'That username or email is already registered. Try logging in instead.', 'the-hidden-word-theme' ),
		'invalid'  => __( 'Security check failed. Please try again.', 'the-hidden-word-theme' ),
		'error'    => __( 'Could not create your account. Please try again.', 'the-hidden-word-theme' ),
	);

	if ( ! isset( $map[ $code ] ) ) {
		return '';
	}

	return '<p class="thw-auth__notice thw-auth__notice--error" role="alert">' . esc_html( $map[ $code ] ) . '</p>';
}

/**
 * Login form shortcode.
 *
 * @param array<string, string> $atts Attributes.
 * @return string
 */
function thw_theme_render_login_form( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'redirect' => '',
		),
		$atts,
		'thw_login_form'
	);

	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		ob_start();
		?>
		<div class="thw-auth thw-auth--logged-in">
			<p>
				<?php
				printf(
					/* translators: %s: display name */
					esc_html__( 'You are already signed in as %s.', 'the-hidden-word-theme' ),
					esc_html( $user->display_name )
				);
				?>
			</p>
			<p class="thw-auth__actions">
				<a class="thw-btn thw-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to homepage', 'the-hidden-word-theme' ); ?></a>
				<a class="thw-btn thw-btn--outline" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log out', 'the-hidden-word-theme' ); ?></a>
			</p>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	$redirect = $atts['redirect'] ? esc_url_raw( $atts['redirect'] ) : '';
	if ( ! $redirect ) {
		$redirect = thw_theme_get_page_url( 'todays-lesson' );
	}
	if ( ! $redirect ) {
		$redirect = home_url( '/' );
	}

	ob_start();
	?>
	<div class="thw-auth thw-auth--login">
		<?php
		if ( ! empty( $_GET['loggedout'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<p class="thw-auth__notice thw-auth__notice--success">' . esc_html__( 'You have been logged out.', 'the-hidden-word-theme' ) . '</p>';
		}
		if ( ! empty( $_GET['login'] ) && 'failed' === sanitize_key( wp_unslash( $_GET['login'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<p class="thw-auth__notice thw-auth__notice--error" role="alert">' . esc_html__( 'Invalid username or password.', 'the-hidden-word-theme' ) . '</p>';
		}
		?>
		<?php
		wp_login_form(
			array(
				'echo'           => true,
				'redirect'       => $redirect,
				'form_id'        => 'thw-loginform',
				'label_username' => __( 'Username or email', 'the-hidden-word-theme' ),
				'label_password' => __( 'Password', 'the-hidden-word-theme' ),
				'label_remember' => __( 'Remember me', 'the-hidden-word-theme' ),
				'label_log_in'   => __( 'Log in', 'the-hidden-word-theme' ),
				'remember'       => true,
			)
		);
		?>
		<p class="thw-auth__links">
			<a href="<?php echo esc_url( wp_lostpassword_url( thw_theme_login_url() ) ); ?>"><?php esc_html_e( 'Forgot password?', 'the-hidden-word-theme' ); ?></a>
			<?php if ( get_option( 'users_can_register' ) ) : ?>
				<span aria-hidden="true"> · </span>
				<a href="<?php echo esc_url( thw_theme_register_url() ); ?>"><?php esc_html_e( 'Create an account', 'the-hidden-word-theme' ); ?></a>
			<?php endif; ?>
		</p>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Registration form shortcode.
 *
 * @return string
 */
function thw_theme_render_register_form() {
	if ( is_user_logged_in() ) {
		return thw_theme_render_login_form();
	}

	ob_start();
	?>
	<div class="thw-auth thw-auth--register">
		<?php echo thw_theme_registration_notice(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php if ( ! get_option( 'users_can_register' ) ) : ?>
			<p class="thw-auth__notice thw-auth__notice--error">
				<?php esc_html_e( 'Registration is currently closed. Ask a site administrator to enable “Anyone can register” under Settings → General.', 'the-hidden-word-theme' ); ?>
			</p>
			<p class="thw-auth__links">
				<a href="<?php echo esc_url( thw_theme_login_url() ); ?>"><?php esc_html_e( 'Back to log in', 'the-hidden-word-theme' ); ?></a>
			</p>
		<?php else : ?>
			<form class="thw-auth__form" method="post" action="<?php echo esc_url( thw_theme_register_url() ); ?>">
				<?php wp_nonce_field( 'thw_theme_register', 'thw_theme_register_nonce' ); ?>
				<input type="hidden" name="thw_theme_register" value="1" />

				<p class="thw-auth__field">
					<label for="thw-user-login"><?php esc_html_e( 'Username', 'the-hidden-word-theme' ); ?></label>
					<input type="text" name="user_login" id="thw-user-login" autocomplete="username" required />
				</p>
				<p class="thw-auth__field">
					<label for="thw-user-email"><?php esc_html_e( 'Email', 'the-hidden-word-theme' ); ?></label>
					<input type="email" name="user_email" id="thw-user-email" autocomplete="email" required />
				</p>
				<p class="thw-auth__field">
					<label for="thw-user-pass"><?php esc_html_e( 'Password', 'the-hidden-word-theme' ); ?></label>
					<input type="password" name="user_pass" id="thw-user-pass" autocomplete="new-password" minlength="8" required />
				</p>
				<p class="thw-auth__field">
					<label for="thw-user-pass-confirm"><?php esc_html_e( 'Confirm password', 'the-hidden-word-theme' ); ?></label>
					<input type="password" name="user_pass_confirm" id="thw-user-pass-confirm" autocomplete="new-password" minlength="8" required />
				</p>
				<p class="thw-auth__submit">
					<button type="submit" class="thw-btn thw-btn--primary"><?php esc_html_e( 'Create account', 'the-hidden-word-theme' ); ?></button>
				</p>
			</form>
			<p class="thw-auth__links">
				<a href="<?php echo esc_url( thw_theme_login_url() ); ?>"><?php esc_html_e( 'Already have an account? Log in', 'the-hidden-word-theme' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Show Log out instead of Log in when authenticated; hide Register when logged in.
 *
 * @param array<int, WP_Post> $items Menu items.
 * @param stdClass            $args  Menu args.
 * @return array<int, WP_Post>
 */
function thw_theme_filter_auth_menu_items( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location || ! is_array( $items ) ) {
		return $items;
	}

	$login_id    = 0;
	$register_id = 0;
	$page_ids    = get_option( 'thw_theme_page_ids', array() );
	if ( is_array( $page_ids ) ) {
		$login_id    = isset( $page_ids['login'] ) ? (int) $page_ids['login'] : 0;
		$register_id = isset( $page_ids['register'] ) ? (int) $page_ids['register'] : 0;
	}

	$filtered = array();
	foreach ( $items as $item ) {
		$object_id = isset( $item->object_id ) ? (int) $item->object_id : 0;
		$slug      = '';
		if ( $object_id ) {
			$page = get_post( $object_id );
			$slug = $page instanceof WP_Post ? $page->post_name : '';
		}

		$is_login    = ( 'login' === $slug ) || ( $login_id && $object_id === $login_id );
		$is_register = ( 'register' === $slug ) || ( $register_id && $object_id === $register_id );

		if ( is_user_logged_in() ) {
			if ( $is_register ) {
				continue;
			}
			if ( $is_login ) {
				$item->title = __( 'Log out', 'the-hidden-word-theme' );
				$item->url   = wp_logout_url( home_url( '/' ) );
				$item->type  = 'custom';
			}
		}

		$filtered[] = $item;
	}

	return $filtered;
}

/**
 * Detect failed wp-login attempts on the themed login page.
 *
 * @param string|null $username Username.
 * @return void
 */
function thw_theme_login_failed( $username = null ) {
	unset( $username );
	$referrer = wp_get_referer();
	if ( ! $referrer ) {
		return;
	}

	$login_url = thw_theme_get_page_url( 'login' );
	if ( ! $login_url || false === strpos( $referrer, (string) wp_parse_url( $login_url, PHP_URL_PATH ) ) ) {
		return;
	}

	wp_safe_redirect( add_query_arg( 'login', 'failed', $login_url ) );
	exit;
}
add_action( 'wp_login_failed', 'thw_theme_login_failed' );
