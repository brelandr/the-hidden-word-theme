<?php
/**
 * Marketing site page and menu setup (thehiddenword.org).
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Creates functional lesson pages when the free plugin is active.
 */
class THW_Theme_Marketing_Setup {

	const OPTION_VERSION = 'thw_theme_marketing_setup_version';

	const OPTION_PAGE_IDS = 'thw_theme_page_ids';

	const SETUP_VERSION = '1.8.0';

	const FLUSH_OPTION = 'thw_theme_flush_rewrite_rules';

	/**
	 * Boot hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'after_switch_theme', array( __CLASS__, 'maybe_run_setup' ) );
		add_action( 'activated_plugin', array( __CLASS__, 'on_plugin_activated' ), 10, 1 );
		add_action( 'init', array( __CLASS__, 'maybe_run_setup_when_ready' ), 20 );
		add_action( 'init', array( __CLASS__, 'maybe_flush_rewrite_rules' ), 99 );
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
		add_action( 'admin_post_thw_theme_run_marketing_setup', array( __CLASS__, 'handle_manual_setup' ) );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	/**
	 * Re-run setup when the free plugin is activated after the theme.
	 *
	 * @param string $plugin Plugin basename.
	 * @return void
	 */
	public static function on_plugin_activated( $plugin ) {
		if ( false === strpos( $plugin, 'the-hidden-word' ) ) {
			return;
		}
		if ( get_template() !== 'the-hidden-word-theme' ) {
			return;
		}
		self::run_setup( true );
	}

	/**
	 * Run setup once the theme and plugin are both active (handles any install order).
	 *
	 * @return void
	 */
	public static function maybe_run_setup_when_ready() {
		if ( get_template() !== 'the-hidden-word-theme' || ! thw_theme_has_hidden_word() ) {
			return;
		}
		if ( self::SETUP_VERSION === get_option( self::OPTION_VERSION, '' ) ) {
			return;
		}
		self::run_setup();
	}

	/**
	 * Flush permalinks after setup (deferred so CPTs are registered).
	 *
	 * @return void
	 */
	public static function maybe_flush_rewrite_rules() {
		if ( ! get_option( self::FLUSH_OPTION ) ) {
			return;
		}
		flush_rewrite_rules( false );
		delete_option( self::FLUSH_OPTION );
	}

	/**
	 * Run setup on theme activation if version changed.
	 *
	 * @return void
	 */
	public static function maybe_run_setup() {
		if ( self::SETUP_VERSION === get_option( self::OPTION_VERSION, '' ) ) {
			return;
		}
		self::run_setup();
	}

	/**
	 * Register Appearance submenu.
	 *
	 * @return void
	 */
	public static function register_admin_page() {
		add_theme_page(
			__( 'Marketing Setup', 'the-hidden-word-theme' ),
			__( 'Marketing Setup', 'the-hidden-word-theme' ),
			'manage_options',
			'thw-theme-marketing-setup',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Admin setup page.
	 *
	 * @return void
	 */
	public static function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$ran = get_option( self::OPTION_VERSION, '' );
		$ids = get_option( self::OPTION_PAGE_IDS, array() );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'The Hidden Word — Marketing Setup', 'the-hidden-word-theme' ); ?></h1>
			<p><?php esc_html_e( 'Creates lesson pages, Read the Bible, Verse of the Day, study tools, and primary navigation. Safe to re-run.', 'the-hidden-word-theme' ); ?></p>
			<?php if ( $ran ) : ?>
				<p><strong><?php esc_html_e( 'Last setup version:', 'the-hidden-word-theme' ); ?></strong> <?php echo esc_html( $ran ); ?></p>
			<?php endif; ?>
			<?php if ( ! thw_theme_has_hidden_word() ) : ?>
				<p class="description"><?php esc_html_e( 'Install and activate The Hidden Word (free) plugin first so lesson shortcodes render.', 'the-hidden-word-theme' ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $ids ) ) : ?>
				<ul>
					<?php foreach ( $ids as $slug => $page_id ) : ?>
						<li>
							<strong><?php echo esc_html( $slug ); ?>:</strong>
							<a href="<?php echo esc_url( get_permalink( (int) $page_id ) ); ?>"><?php echo esc_html( get_the_title( (int) $page_id ) ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'thw_theme_run_marketing_setup', 'thw_theme_marketing_setup_nonce' ); ?>
				<input type="hidden" name="action" value="thw_theme_run_marketing_setup" />
				<?php submit_button( __( 'Run Marketing Setup', 'the-hidden-word-theme' ), 'primary', 'submit', false ); ?>
			</form>
			<p class="description">
				<?php
				printf(
					/* translators: %s: permalinks settings URL */
					esc_html__( 'After setup, open %s and click Save Changes (no edits needed) if lesson URLs still 404.', 'the-hidden-word-theme' ),
					'<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">' . esc_html__( 'Settings → Permalinks', 'the-hidden-word-theme' ) . '</a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Handle manual setup.
	 *
	 * @return void
	 */
	public static function handle_manual_setup() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'the-hidden-word-theme' ) );
		}
		if ( ! isset( $_POST['thw_theme_marketing_setup_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['thw_theme_marketing_setup_nonce'] ) ), 'thw_theme_run_marketing_setup' ) ) {
			wp_die( esc_html__( 'Invalid security token.', 'the-hidden-word-theme' ) );
		}

		self::run_setup( true );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'           => 'thw-theme-marketing-setup',
					'thw_setup_done' => '1',
				),
				admin_url( 'themes.php' )
			)
		);
		exit;
	}

	/**
	 * Admin notices.
	 *
	 * @return void
	 */
	public static function admin_notices() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['thw_setup_done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Marketing setup completed.', 'the-hidden-word-theme' ) . '</p></div>';
		}

		if ( thw_theme_has_hidden_word() && self::SETUP_VERSION !== get_option( self::OPTION_VERSION, '' ) ) {
			echo '<div class="notice notice-info"><p>';
			printf(
				/* translators: %s: setup page URL */
				esc_html__( 'The Hidden Word theme: run %s to create Today\'s Lesson and navigation.', 'the-hidden-word-theme' ),
				'<a href="' . esc_url( admin_url( 'themes.php?page=thw-theme-marketing-setup' ) ) . '">' . esc_html__( 'Marketing Setup', 'the-hidden-word-theme' ) . '</a>'
			);
			echo '</p></div>';
		}
	}

	/**
	 * Run full marketing setup.
	 *
	 * @param bool $force Force re-run even if version matches.
	 * @return void
	 */
	public static function run_setup( $force = false ) {
		if ( ! $force && self::SETUP_VERSION === get_option( self::OPTION_VERSION, '' ) ) {
			return;
		}

		self::ensure_curriculum_seeded();

		$existing = get_option( self::OPTION_PAGE_IDS, array() );
		$page_ids = is_array( $existing ) ? $existing : array();

		$login_intro = '<p>' . esc_html__( 'Sign in to save memorization progress, claim streaks, and use study tools on this site.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
		$page_ids['login'] = self::upsert_page(
			__( 'Log in', 'the-hidden-word-theme' ),
			'login',
			$login_intro . '[thw_login_form]',
			'page-templates/auth.php'
		);

		$register_intro = '<p>' . esc_html__( 'Create a free account to practice verses, track progress, and join study features on this church site.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
		$page_ids['register'] = self::upsert_page(
			__( 'Register', 'the-hidden-word-theme' ),
			'register',
			$register_intro . '[thw_register_form]',
			'page-templates/auth.php'
		);

		// Enable open registration for church/demo sites when Marketing Setup runs.
		update_option( 'users_can_register', 1 );

		if ( thw_theme_has_hidden_word() ) {
			$page_ids['todays-lesson'] = self::upsert_page(
				thw_theme_schedule_phrase( 'memorize' ),
				'todays-lesson',
				'[hwbl_lesson]',
				'page-templates/full-width-lesson.php'
			);

			$page_ids['verse-of-the-week'] = self::upsert_page(
				thw_theme_schedule_phrase( 'compact' ),
				'verse-of-the-week',
				'[hwbl_verse_of_week]',
				'page-templates/full-width-lesson.php'
			);

			$page_ids['lesson-catalog'] = self::upsert_page(
				thw_theme_schedule_phrase( 'catalog' ),
				'lesson-catalog',
				'[hwbl_lesson_list group="flat" per_page="20"]',
				'page-templates/full-width-lesson.php'
			);

			$reader_intro = '<p>' . esc_html__( 'Read and listen to any book and chapter of the Bible. Text comes from the free Hello AO Bible API (no key required). Optional Biblia.com and API.Bible keys unlock additional translations in the picker below.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			$page_ids['read-the-bible'] = self::upsert_page(
				__( 'Read the Bible', 'the-hidden-word-theme' ),
				'read-the-bible',
				$reader_intro . '[hwbl_bible_reader]',
				'page-templates/full-width-lesson.php'
			);

			$memorize_intro = '<p>' . esc_html__( 'Pick any verse from translations your site can access—bundled NIV/KJV/WEB, Hello AO, Biblia.com, or API.Bible—and practice with spaced repetition (SM-2), daily reviews, and modes like hide, recall, first-letter, and scramble.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			$page_ids['memorize-a-verse'] = self::upsert_page(
				__( 'Memorize a Verse', 'the-hidden-word-theme' ),
				'memorize-a-verse',
				$memorize_intro . '[hwbl_memorize_verse]',
				'page-templates/full-width-lesson.php'
			);

			if ( shortcode_exists( 'hwbl_memorize_reviews' ) ) {
				$reviews_intro = '<p>' . esc_html__( 'Review verses that are due today with spaced-repetition quality ratings. Part of the free Hidden Word Bible Lessons plugin.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['memorization-reviews'] = self::upsert_page(
					__( 'Memorization Reviews', 'the-hidden-word-theme' ),
					'memorization-reviews',
					$reviews_intro . '[hwbl_memorize_reviews]',
					'page-templates/full-width-lesson.php'
				);
			}

			if ( thw_theme_has_premium_plugin() ) {
				$votd_intro = '<p>' . esc_html__( 'Today’s verse with your chosen Bible translation. Reference and image can come from Bible.com or your site curriculum schedule. Verse text uses bundled translations plus Hello AO, Biblia.com, and API.Bible when configured.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['verse-of-the-day'] = self::upsert_page(
					__( 'Verse of the Day', 'the-hidden-word-theme' ),
					'verse-of-the-day',
					$votd_intro . '[hwbl_verse_of_the_day]',
					'page-templates/full-width-lesson.php'
				);
				$finder_intro = '<p>' . esc_html__( 'Choose your faith tradition, then search a topic. Answers follow curated digests and doctrinal sources (Catholic CIC/CCC, Southern Baptist BF&M, Pentecostal statements, UPCI, Non-denominational including Destiny Leaders, ARC, Churches In Covenant, and more).', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['find-a-lesson'] = self::upsert_page(
					thw_theme_schedule_phrase( 'find' ),
					'find-a-lesson',
					$finder_intro . '[hwbl_study_finder title="' . esc_attr__( 'Find a Bible Study by Topic', 'the-hidden-word-theme' ) . '"]',
					'page-templates/full-width-lesson.php'
				);

				$ask_intro = '<p>' . esc_html__( 'Ask a Bible or faith question. Answers follow your selected faith tradition’s digests and doctrinal gates—the same routing used by Explain and Find a Verse.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['ask-a-question'] = self::upsert_page(
					__( 'Ask a Question', 'the-hidden-word-theme' ),
					'ask-a-question',
					$ask_intro . '[hwbl_ask_question title="' . esc_attr__( 'Ask a Bible Question', 'the-hidden-word-theme' ) . '"]',
					'page-templates/full-width-lesson.php'
				);
			}

			// Discipleship & outreach pages (plans / prayer / journal / apologetics / share).
			if ( shortcode_exists( 'hwbl_plan_list' ) ) {
				$plans_intro  = '<p>' . esc_html__( 'Multi-day reading plans for anxiety, prayer, identity, life’s hard places (health, marriage strain, addiction, finances, loneliness, depression, and more), grief, new believers, foundations, Advent, Lent, kids Bible stories, the gospel, and discipleship. Start a plan while signed in to track daily progress.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$plans_intro .= '<p>' . esc_html__( 'Prefer larger type and spacing? Use Easy read on any lesson or Bible reader page (or open Reading Comfort below).', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['reading-plans'] = self::upsert_page(
					__( 'Reading Plans', 'the-hidden-word-theme' ),
					'reading-plans',
					$plans_intro . '[hwbl_plan_list]',
					'page-templates/full-width-lesson.php'
				);

				$kids_intro  = '<p>' . esc_html__( 'Short Bible stories for kids (about ages 6–10). Turn on Kids mode from a lesson toolbar or Reading Comfort to highlight these plans site-wide.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$kids_intro .= '<p><button type="button" class="thw-btn thw-btn--secondary" data-hwbl-kids-mode-toggle="1" aria-pressed="false">' . esc_html__( 'Toggle Kids mode', 'the-hidden-word-theme' ) . '</button></p>' . "\n\n";
				$page_ids['kids-bible-stories'] = self::upsert_page(
					__( 'Kids Bible Stories', 'the-hidden-word-theme' ),
					'kids-bible-stories',
					$kids_intro . '[hwbl_plan_list topic="kids"]',
					'page-templates/full-width-lesson.php'
				);

				$life_intro  = '<p>' . esc_html__( 'Plans for hard seasons and new beginnings—grief, marriage, and first steps as a believer.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$life_intro .= '<h2>' . esc_html__( 'Grief', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="grief"]' . "\n\n";
				$life_intro .= '<h2>' . esc_html__( 'New believer', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="new-believer"]' . "\n\n";
				$life_intro .= '<h2>' . esc_html__( 'Marriage', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="marriage"]' . "\n\n";
				$page_ids['life-season-plans'] = self::upsert_page(
					__( 'Life Season Plans', 'the-hidden-word-theme' ),
					'life-season-plans',
					$life_intro,
					'page-templates/full-width-lesson.php'
				);

				$hard_intro  = '<p>' . esc_html__( 'Scripture for life’s hard places—overwhelm, health, addiction, finances, violence, doubt, loneliness, and depression. These plans are pastoral study, not crisis counseling. If you are in danger, call local emergency services; in the US and Canada call or text 988.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'When life feels overwhelming', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="life-problems"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Health', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="health"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Marriage under strain', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="marriage"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Alcohol', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="alcoholism"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Addiction', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="addiction"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Finances', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="finances"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Domestic violence', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="domestic-violence"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Doubt / thin faith', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="doubt"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Loneliness', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="loneliness"]' . "\n\n";
				$hard_intro .= '<h2>' . esc_html__( 'Depression', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="depression"]' . "\n\n";
				$page_ids['lifes-hard-places'] = self::upsert_page(
					__( "Life's Hard Places", 'the-hidden-word-theme' ),
					'lifes-hard-places',
					$hard_intro,
					'page-templates/full-width-lesson.php'
				);

				$faith_intro  = '<p>' . esc_html__( 'Grow deeper in everyday faith—prayer, identity in Christ, forgiveness, calling at work, and following Jesus day by day.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$faith_intro .= '<h2>' . esc_html__( 'Prayer', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="prayer"]' . "\n\n";
				$faith_intro .= '<h2>' . esc_html__( 'Identity', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="identity"]' . "\n\n";
				$faith_intro .= '<h2>' . esc_html__( 'Forgiveness', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="forgiveness"]' . "\n\n";
				$faith_intro .= '<h2>' . esc_html__( 'Purpose', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="purpose"]' . "\n\n";
				$faith_intro .= '<h2>' . esc_html__( 'Discipleship', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="discipleship"]' . "\n\n";
				$page_ids['faith-formation'] = self::upsert_page(
					__( 'Faith Formation', 'the-hidden-word-theme' ),
					'faith-formation',
					$faith_intro,
					'page-templates/full-width-lesson.php'
				);
			}

			if ( shortcode_exists( 'hwbl_liturgical_season' ) ) {
				$seasons_intro  = '<p>' . esc_html__( 'Follow the church year—Advent, Christmas, Lent, Holy Week, and Eastertide—with suggested reading plans when they are in season.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$seasons_intro .= '[hwbl_liturgical_season]' . "\n\n";
				$seasons_intro .= '<h2>' . esc_html__( 'Advent', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="advent"]' . "\n\n";
				$seasons_intro .= '<h2>' . esc_html__( 'Lent', 'the-hidden-word-theme' ) . '</h2>' . "\n\n" . '[hwbl_plan_list topic="lent"]' . "\n\n";
				$page_ids['church-seasons'] = self::upsert_page(
					__( 'Church Seasons', 'the-hidden-word-theme' ),
					'church-seasons',
					$seasons_intro,
					'page-templates/full-width-lesson.php'
				);
			}

			$comfort_intro  = '<p>' . esc_html__( 'Make Scripture easier to read. Easy read increases spacing and uses a clearer font. Kids mode enlarges type and highlights kids Bible story plans.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			$comfort_intro .= '<p><button type="button" class="thw-btn thw-btn--primary" data-hwbl-easy-read-toggle="1" aria-pressed="false">' . esc_html__( 'Toggle Easy read', 'the-hidden-word-theme' ) . '</button> ';
			$comfort_intro .= '<button type="button" class="thw-btn thw-btn--secondary" data-hwbl-kids-mode-toggle="1" aria-pressed="false">' . esc_html__( 'Toggle Kids mode', 'the-hidden-word-theme' ) . '</button></p>' . "\n\n";
			$comfort_intro .= '<p>' . esc_html__( 'These preferences are saved in your browser (and to your account when signed in). They also appear on lesson and Bible reader toolbars.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			if ( ! empty( $page_ids['kids-bible-stories'] ) ) {
				$comfort_intro .= '<p><a class="thw-btn thw-btn--secondary" href="' . esc_url( get_permalink( (int) $page_ids['kids-bible-stories'] ) ) . '">' . esc_html__( 'Kids Bible Stories', 'the-hidden-word-theme' ) . '</a></p>' . "\n\n";
			}
			$page_ids['reading-comfort'] = self::upsert_page(
				__( 'Reading Comfort', 'the-hidden-word-theme' ),
				'reading-comfort',
				$comfort_intro,
				'page-templates/full-width-lesson.php'
			);

			$gospel_url = self::get_shareable_gospel_url();
			$gospel_intro = '<p>' . esc_html__( 'Share a clear gospel presentation with anyone—no account required for the recipient. Copy the link below, share via WhatsApp or SMS, or print the QR code.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			if ( $gospel_url ) {
				$gospel_intro .= '<p><a class="thw-btn thw-btn--primary" href="' . esc_url( $gospel_url ) . '">' . esc_html__( 'Open the Romans Road presentation', 'the-hidden-word-theme' ) . '</a></p>' . "\n\n";
				$gospel_intro .= '[hwbl_share_tools url="' . esc_attr( $gospel_url ) . '" title="' . esc_attr__( 'The gospel', 'the-hidden-word-theme' ) . '"]' . "\n\n";
			}
			$gospel_intro .= '<p>' . esc_html__( 'Site admins can mark any Reading Plan as shareable in the plan editor to publish additional outreach pages at /gospel/{id}/.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			$page_ids['share-the-gospel'] = self::upsert_page(
				__( 'Share the Gospel', 'the-hidden-word-theme' ),
				'share-the-gospel',
				$gospel_intro,
				'page-templates/full-width-lesson.php'
			);

			if ( shortcode_exists( 'hwbl_network_impact' ) ) {
				$impact_intro = '<p>' . esc_html__( 'Anonymized network totals — testimonies shared, gospel pages viewed, responses, and registered churches.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['impact'] = self::upsert_page(
					__( 'Network Impact', 'the-hidden-word-theme' ),
					'impact',
					$impact_intro . '[hwbl_network_impact]',
					'page-templates/full-width-lesson.php'
				);
			}

			if ( shortcode_exists( 'hwbl_prayer' ) ) {
				$prayer_intro = '<p>' . esc_html__( 'Write personal prayer requests and see who is praying with you. Sign in to add or update requests. Cohort prayer walls also appear on small-group pages.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['prayer'] = self::upsert_page(
					__( 'Prayer', 'the-hidden-word-theme' ),
					'prayer',
					$prayer_intro . '[hwbl_prayer]',
					'page-templates/full-width-lesson.php'
				);
			}

			if ( shortcode_exists( 'hwbl_my_journal' ) ) {
				$journal_intro = '<p>' . esc_html__( 'Your answers to lesson Discussion questions, saved as a spiritual-growth timeline. Sign in and open any lesson’s Discussion tab to write; review everything here.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['my-journal'] = self::upsert_page(
					__( 'My Journal', 'the-hidden-word-theme' ),
					'my-journal',
					$journal_intro . '[hwbl_my_journal]',
					'page-templates/full-width-lesson.php'
				);
			}

			if ( shortcode_exists( 'hwbl_apologetics' ) ) {
				$apologetics_intro = '<p>' . esc_html__( 'Curated, reviewed answers to common faith questions. This library is distinct from live AI Ask—these entries are authored and reviewed for this site.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
				$page_ids['apologetics'] = self::upsert_page(
					__( 'Apologetics', 'the-hidden-word-theme' ),
					'apologetics',
					$apologetics_intro . '[hwbl_apologetics]',
					'page-templates/full-width-lesson.php'
				);
			}

			$connect_url = class_exists( 'HWBL_App_Connect' )
				? HWBL_App_Connect::connect_url()
				: trailingslashit( home_url( '/app/connect' ) );
			$testimony_intro  = '<p>' . esc_html__( 'Write your story in three parts—Before, Turning point, and After—then optionally share a public link. Use the Hidden Word companion app to create and share your testimony.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			$testimony_intro .= '<p><a class="thw-btn thw-btn--primary" href="' . esc_url( $connect_url ) . '">' . esc_html__( 'Open companion connect', 'the-hidden-word-theme' ) . '</a></p>' . "\n\n";
			$testimony_intro .= '<p>' . esc_html__( 'Public testimonies are available at /testimony/{id}/ when you choose to share.', 'the-hidden-word-theme' ) . '</p>' . "\n\n";
			$page_ids['my-testimony'] = self::upsert_page(
				__( 'My Testimony', 'the-hidden-word-theme' ),
				'my-testimony',
				$testimony_intro,
				'page-templates/full-width-lesson.php'
			);
		}

		self::seed_primary_menu( $page_ids );
		self::seed_verse_widget();

		update_option( self::OPTION_PAGE_IDS, $page_ids, false );
		update_option( self::OPTION_VERSION, self::SETUP_VERSION, false );
		update_option( self::FLUSH_OPTION, 1, false );
	}

	/**
	 * Create or update a page by slug.
	 *
	 * @param string $title    Page title.
	 * @param string $slug     Page slug.
	 * @param string $content  Post content.
	 * @param string $template Page template.
	 * @return int Page ID.
	 */
	private static function upsert_page( $title, $slug, $content, $template = '' ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		$id   = $page instanceof WP_Post ? (int) $page->ID : 0;

		$data = array(
			'post_title'   => $title,
			'post_name'    => sanitize_title( $slug ),
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => $content,
		);

		if ( $id > 0 ) {
			$data['ID'] = $id;
			wp_update_post( $data );
		} else {
			$id = wp_insert_post( $data, true );
			if ( is_wp_error( $id ) ) {
				return 0;
			}
		}

		if ( $template ) {
			update_post_meta( (int) $id, '_wp_page_template', $template );
		}

		return (int) $id;
	}

	/**
	 * Public URL for the first shareable gospel plan, if any.
	 *
	 * @return string
	 */
	private static function get_shareable_gospel_url() {
		if ( ! post_type_exists( 'hwbl_plan' ) ) {
			return '';
		}
		$plans = get_posts(
			array(
				'post_type'      => 'hwbl_plan',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_hwbl_plan_shareable',
						'value' => '1',
					),
				),
				'orderby'        => 'date',
				'order'          => 'ASC',
			)
		);
		if ( ! $plans ) {
			return '';
		}
		$plan_id = (int) $plans[0];
		if ( class_exists( 'HWBL_Gospel_Share' ) ) {
			return HWBL_Gospel_Share::share_url( $plan_id );
		}
		return trailingslashit( home_url( '/gospel/' . $plan_id ) );
	}

	/**
	 * Build primary navigation for marketing site.
	 *
	 * @param array<string,int> $page_ids Page slug to ID map.
	 * @return void
	 */
	private static function seed_primary_menu( $page_ids ) {
		$menu_name = __( 'Primary', 'the-hidden-word-theme' );
		$menu      = wp_get_nav_menu_object( $menu_name );
		$menu_id   = $menu ? (int) $menu->term_id : 0;

		if ( ! $menu_id ) {
			$menu_id = wp_create_nav_menu( $menu_name );
		}

		if ( is_wp_error( $menu_id ) || ! $menu_id ) {
			return;
		}

		$items = wp_get_nav_menu_items( $menu_id );
		if ( is_array( $items ) ) {
			foreach ( $items as $item ) {
				wp_delete_post( (int) $item->ID, true );
			}
		}

		$position = 1;
		$parent_ids = array();

		$home_id = self::insert_nav_menu_item(
			$menu_id,
			array(
				'title' => __( 'Home', 'the-hidden-word-theme' ),
				'url'   => home_url( '/' ),
				'type'  => 'custom',
			),
			$position++
		);

		unset( $home_id );

		$memorize_parent_url = ! empty( $page_ids['todays-lesson'] )
			? get_permalink( (int) $page_ids['todays-lesson'] )
			: home_url( '/' );

		$memorize_parent_id = self::insert_nav_menu_item(
			$menu_id,
			array(
				'title' => thw_theme_memorize_nav_heading(),
				'url'   => $memorize_parent_url ? $memorize_parent_url : home_url( '/' ),
				'type'  => 'custom',
			),
			$position++
		);

		if ( ! is_wp_error( $memorize_parent_id ) && $memorize_parent_id ) {
			$parent_ids['memorize'] = (int) $memorize_parent_id;
		}

		foreach ( thw_theme_memorize_nav_pages() as $slug => $label ) {
			if ( empty( $page_ids[ $slug ] ) ) {
				continue;
			}
			self::insert_nav_menu_item(
				$menu_id,
				array(
					'title'   => $label,
					'page_id' => (int) $page_ids[ $slug ],
					'type'    => 'post_type',
				),
				$position++,
				isset( $parent_ids['memorize'] ) ? $parent_ids['memorize'] : 0
			);
		}

		$archive = get_post_type_archive_link( 'hwbl_lesson' );
		if ( ! $archive ) {
			$archive = get_post_type_archive_link( 'thw_lesson' );
		}
		if ( $archive && isset( $parent_ids['memorize'] ) ) {
			self::insert_nav_menu_item(
				$menu_id,
				array(
					'title' => __( 'All Verses', 'the-hidden-word-theme' ),
					'url'   => $archive,
					'type'  => 'custom',
				),
				$position++,
				$parent_ids['memorize']
			);
		}

		$top_level = array(
			'read-the-bible' => __( 'Read the Bible', 'the-hidden-word-theme' ),
		);

		if ( thw_theme_has_premium_plugin() ) {
			$top_level['find-a-lesson']  = thw_theme_schedule_phrase( 'find' );
			$top_level['ask-a-question'] = __( 'Ask a Question', 'the-hidden-word-theme' );
		}

		foreach ( $top_level as $slug => $label ) {
			if ( empty( $page_ids[ $slug ] ) ) {
				continue;
			}
			self::insert_nav_menu_item(
				$menu_id,
				array(
					'title'   => $label,
					'page_id' => (int) $page_ids[ $slug ],
					'type'    => 'post_type',
				),
				$position++
			);
		}

		$grow_children = array(
			'reading-plans'       => __( 'Reading Plans', 'the-hidden-word-theme' ),
			'faith-formation'     => __( 'Faith Formation', 'the-hidden-word-theme' ),
			'life-season-plans'   => __( 'Life Season Plans', 'the-hidden-word-theme' ),
			'lifes-hard-places'   => __( "Life's Hard Places", 'the-hidden-word-theme' ),
			'kids-bible-stories'  => __( 'Kids Bible Stories', 'the-hidden-word-theme' ),
			'church-seasons'      => __( 'Church Seasons', 'the-hidden-word-theme' ),
			'reading-comfort'     => __( 'Reading Comfort', 'the-hidden-word-theme' ),
			'share-the-gospel'    => __( 'Share the Gospel', 'the-hidden-word-theme' ),
			'impact'              => __( 'Network Impact', 'the-hidden-word-theme' ),
			'prayer'              => __( 'Prayer', 'the-hidden-word-theme' ),
			'my-journal'          => __( 'My Journal', 'the-hidden-word-theme' ),
			'apologetics'         => __( 'Apologetics', 'the-hidden-word-theme' ),
			'my-testimony'        => __( 'My Testimony', 'the-hidden-word-theme' ),
		);
		$grow_first_url = home_url( '/' );
		foreach ( $grow_children as $slug => $label ) {
			if ( ! empty( $page_ids[ $slug ] ) ) {
				$grow_first_url = get_permalink( (int) $page_ids[ $slug ] );
				break;
			}
		}
		$grow_parent_id = self::insert_nav_menu_item(
			$menu_id,
			array(
				'title' => __( 'Grow', 'the-hidden-word-theme' ),
				'url'   => $grow_first_url ? $grow_first_url : home_url( '/' ),
				'type'  => 'custom',
			),
			$position++
		);
		if ( ! is_wp_error( $grow_parent_id ) && $grow_parent_id ) {
			$parent_ids['grow'] = (int) $grow_parent_id;
			foreach ( $grow_children as $slug => $label ) {
				if ( empty( $page_ids[ $slug ] ) ) {
					continue;
				}
				self::insert_nav_menu_item(
					$menu_id,
					array(
						'title'   => $label,
						'page_id' => (int) $page_ids[ $slug ],
						'type'    => 'post_type',
					),
					$position++,
					$parent_ids['grow']
				);
			}
		}

		$auth_pages = array(
			'login'    => __( 'Log in', 'the-hidden-word-theme' ),
			'register' => __( 'Register', 'the-hidden-word-theme' ),
		);
		foreach ( $auth_pages as $slug => $label ) {
			if ( empty( $page_ids[ $slug ] ) ) {
				continue;
			}
			self::insert_nav_menu_item(
				$menu_id,
				array(
					'title'   => $label,
					'page_id' => (int) $page_ids[ $slug ],
					'type'    => 'post_type',
				),
				$position++
			);
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Insert one nav menu item.
	 *
	 * @param int                  $menu_id   Menu term ID.
	 * @param array<string, mixed> $link      Item data.
	 * @param int                  $position  Menu order.
	 * @param int                  $parent_id Parent menu item ID.
	 * @return int|WP_Error
	 */
	private static function insert_nav_menu_item( $menu_id, $link, $position, $parent_id = 0 ) {
		$args = array(
			'menu-item-title'     => $link['title'],
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position,
			'menu-item-parent-id' => max( 0, (int) $parent_id ),
		);

		if ( 'post_type' === $link['type'] && ! empty( $link['page_id'] ) ) {
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = (int) $link['page_id'];
			$args['menu-item-type']      = 'post_type';
		} else {
			$args['menu-item-url']  = $link['url'];
			$args['menu-item-type'] = 'custom';
		}

		return wp_update_nav_menu_item( $menu_id, 0, $args );
	}

	/**
	 * Seed verse of the week widget in sidebar.
	 *
	 * @return void
	 */
	private static function seed_verse_widget() {
		if ( ! thw_theme_has_hidden_word() ) {
			return;
		}

		$sidebars = get_option( 'sidebars_widgets', array() );
		if ( ! is_array( $sidebars ) ) {
			$sidebars = array();
		}

		$widget_option = get_option( 'widget_thw_verse_of_week', array() );
		if ( ! is_array( $widget_option ) ) {
			$widget_option = array();
		}

		$widget_option['_multiwidget'] = 1;
		$instance_id                   = 1;
		$widget_option[ $instance_id ] = array(
			'title'  => thw_theme_schedule_phrase( 'compact' ),
			'lesson' => 0,
		);
		update_option( 'widget_thw_verse_of_week', $widget_option );

		$widget_ref = 'thw_verse_of_week-' . $instance_id;
		if ( empty( $sidebars['sidebar-1'] ) || ! is_array( $sidebars['sidebar-1'] ) ) {
			$sidebars['sidebar-1'] = array();
		}
		if ( ! in_array( $widget_ref, $sidebars['sidebar-1'], true ) ) {
			array_unshift( $sidebars['sidebar-1'], $widget_ref );
		}
		update_option( 'sidebars_widgets', $sidebars );
	}

	/**
	 * Advance curriculum seeding and rebuild lookup map.
	 *
	 * @return void
	 */
	private static function ensure_curriculum_seeded() {
		if ( ! class_exists( 'THW_Scheduler' ) || ! class_exists( 'THW_Activator' ) ) {
			return;
		}

		THW_Scheduler::rebuild_lookup_map();

		$queue = get_option( 'thw_seed_queue', false );
		if ( ! is_array( $queue ) || empty( $queue ) ) {
			return;
		}

		for ( $i = 0; $i < 25; $i++ ) {
			THW_Activator::process_seed_batch();
			$queue = get_option( 'thw_seed_queue', false );
			if ( ! is_array( $queue ) || empty( $queue ) ) {
				break;
			}
		}

		THW_Scheduler::rebuild_lookup_map();
	}
}

THW_Theme_Marketing_Setup::init();

/**
 * Get a marketing page URL by slug key.
 *
 * @param string $slug Page slug key.
 * @return string
 */
function thw_theme_get_page_url( $slug ) {
	$map = get_option( THW_Theme_Marketing_Setup::OPTION_PAGE_IDS, array() );
	if ( ! empty( $map[ $slug ] ) ) {
		$url = get_permalink( (int) $map[ $slug ] );
		if ( $url ) {
			return $url;
		}
	}
	return home_url( '/' . $slug . '/' );
}
