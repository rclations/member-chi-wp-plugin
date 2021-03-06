<?php

/**
 * @since      1.2
 * @package    Member_Chi
 * @subpackage Member_Chi/includes/integrations
 * @author     Member Up <travis@memberup.co>
 */
class Member_Chi_BBPress_Integration extends Member_Chi_Membership_Plugin_Integration {

	private $team_hash;
	private $url;
	private $statuses;

	/**
	 * Member_Chi_bbPress_Integration constructor.
	 */
	public function __construct() {
		$this->define_hooks();
		$this->team_hash = 'olejRejN';
		$this->url = 'https://chi.dev/integration/bbpress/   ' . $this->team_hash;
		$this->statuses = array(
			'active',
			'expired',
			'cancelled',
			'pending',
			'free',
		);
	}

	/**
	 *
	 */
	private function define_hooks() {
		add_action( 'bbp_new_topic', array( $this, 'new_topic' ), 10, 4 );
		add_action( 'bbp_new_reply', array( $this, 'new_reply' ), 10, 7 );
	}

	/**
	 * @param int $topic_id
	 * @param int $forum_id
	 * @param $anonymous_data
	 * @param int $topic_author
	 */
	public function new_topic( $topic_id, $forum_id, $anonymous_data, $topic_author ) {

		$user = get_userdata( $topic_author );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $topic_author,
			'event_type' => 'bbpress_new_topic',
		);

		$this->url = 'https://chi.dev/api/integration/bbpress/' . $this->team_hash;

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}

	/**
	 * @param int $reply_id
	 * @param int $topic_id
	 * @param int $forum_id
	 * @param $anonymous_data
	 * @param int $reply_author
	 * @param bool $false
	 * @param int $reply_to
	 */
	public function new_reply( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author, $false, $reply_to ) {

		$user = get_userdata( $reply_author );

		$body = array(
			'email' => $user->user_email,
			'wp_id' => $topic_author,
			'reply_id' => $reply_id,
			'topic' => $topic_id,
			'event_type' => 'bbpress_topic_reply',
		);

		$this->url = 'https://chi.dev/api/integration/bbpress/' . $this->team_hash;

		$response = $this->post( $this->url, $body );

		error_log( print_r( $response, true ) );

		error_log( $this->url );

	}
}
