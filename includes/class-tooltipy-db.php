<?php
class TooltipyDB{
	protected $table_name;

	public function __construct(){
		global $wpdb;
		
		$this->table_name = $wpdb->base_prefix . 'tooltipy';

		// action hook to create the DB
		register_activation_hook( TOOLTIPY_BASE_FILE, [$this, 'createDB']);
	}

	public function get_table_name(){
		return $this->table_name;
	}

	public function createDB(){
		global $wpdb;

		// set the default character set and collation for the table
		$charset_collate = $wpdb->get_charset_collate();

		// Check that the table does not already exist before continuing
		$sql = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
			`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
			`name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
			`content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,

			`author` bigint(20) unsigned NOT NULL DEFAULT '0',
			`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',

			`synonyms` text COLLATE utf8mb4_unicode_520_ci NULL,
			`case_sensitive` binary NULL,
			`is_prefix` binary NULL,
			`is_wiki` binary NULL,
			`video_url` mediumtext COLLATE utf8mb4_unicode_520_ci NULL,
			`wiki_term` tinytext COLLATE utf8mb4_unicode_520_ci NULL,

			PRIMARY KEY (`ID`)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		$is_error = empty( $wpdb->last_error );
		return $is_error;
	}
}
global $tooltipydb;
$tooltipydb = new TooltipyDB();
