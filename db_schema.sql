-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 07:02 AM
-- Server version: 11.4.10-MariaDB-cll-lve-log
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insomni2_wp`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_actions`
--

CREATE TABLE `wp_actionscheduler_actions` (
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `hook` varchar(191) NOT NULL,
  `status` varchar(20) NOT NULL,
  `scheduled_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `scheduled_date_local` datetime DEFAULT '0000-00-00 00:00:00',
  `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT 10,
  `args` varchar(191) DEFAULT NULL,
  `schedule` longtext DEFAULT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `last_attempt_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `extended_args` varchar(8000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_claims`
--

CREATE TABLE `wp_actionscheduler_claims` (
  `claim_id` bigint(20) UNSIGNED NOT NULL,
  `date_created_gmt` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_groups`
--

CREATE TABLE `wp_actionscheduler_groups` (
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_logs`
--

CREATE TABLE `wp_actionscheduler_logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `log_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `log_date_local` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_apsl_users_social_profile_details`
--

CREATE TABLE `wp_apsl_users_social_profile_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `unique_verifier` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `first_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `profile_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `website_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `photo_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `display_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `language` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `age` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `birthday` int(11) NOT NULL,
  `birthmonth` int(11) NOT NULL,
  `birthyear` int(11) NOT NULL,
  `phone` varchar(75) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `country` varchar(75) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `region` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `zip` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_as3cf_items`
--

CREATE TABLE `wp_as3cf_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(18) NOT NULL,
  `region` varchar(255) NOT NULL,
  `bucket` varchar(255) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `original_path` varchar(1024) NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `source_type` varchar(18) NOT NULL,
  `source_id` bigint(20) UNSIGNED NOT NULL,
  `source_path` varchar(1024) NOT NULL,
  `original_source_path` varchar(1024) NOT NULL,
  `extra_info` longtext DEFAULT NULL,
  `originator` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cky_banners`
--

CREATE TABLE `wp_cky_banners` (
  `banner_id` bigint(20) NOT NULL,
  `name` varchar(190) NOT NULL DEFAULT '',
  `slug` varchar(190) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 0,
  `settings` longtext NOT NULL DEFAULT '',
  `banner_default` int(11) NOT NULL DEFAULT 0,
  `contents` longtext NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cky_cookies`
--

CREATE TABLE `wp_cky_cookies` (
  `cookie_id` bigint(20) NOT NULL,
  `name` varchar(190) NOT NULL DEFAULT '',
  `slug` varchar(190) NOT NULL DEFAULT '',
  `description` longtext NOT NULL DEFAULT '',
  `duration` text NOT NULL DEFAULT '',
  `domain` varchar(190) NOT NULL DEFAULT '',
  `category` bigint(20) NOT NULL,
  `type` text NOT NULL DEFAULT '',
  `discovered` int(11) NOT NULL DEFAULT 0,
  `url_pattern` varchar(190) DEFAULT '',
  `meta` longtext DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cky_cookie_categories`
--

CREATE TABLE `wp_cky_cookie_categories` (
  `category_id` bigint(20) NOT NULL,
  `name` text NOT NULL DEFAULT '',
  `slug` varchar(190) NOT NULL DEFAULT '',
  `description` longtext NOT NULL DEFAULT '',
  `prior_consent` int(11) NOT NULL DEFAULT 0,
  `visibility` int(11) NOT NULL DEFAULT 1,
  `priority` int(11) NOT NULL DEFAULT 0,
  `sell_personal_data` int(11) NOT NULL DEFAULT 0,
  `meta` longtext DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_commentmeta`
--

CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_comments`
--

CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) UNSIGNED NOT NULL,
  `comment_post_ID` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `comment_author` tinytext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_email_log`
--

CREATE TABLE `wp_email_log` (
  `id` mediumint(9) NOT NULL,
  `to_email` varchar(500) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` text NOT NULL,
  `headers` text NOT NULL,
  `attachments` text NOT NULL,
  `sent_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attachment_name` varchar(1000) DEFAULT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `result` tinyint(1) DEFAULT NULL,
  `error_message` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_ewwwio_images`
--

CREATE TABLE `wp_ewwwio_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attachment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gallery` varchar(10) DEFAULT NULL,
  `resize` varchar(75) DEFAULT NULL,
  `path` text DEFAULT NULL,
  `converted` mediumtext NOT NULL,
  `image_size` int(10) UNSIGNED DEFAULT NULL,
  `orig_size` int(10) UNSIGNED DEFAULT NULL,
  `backup` varchar(100) DEFAULT NULL,
  `level` int(10) UNSIGNED DEFAULT NULL,
  `pending` tinyint(4) NOT NULL DEFAULT 0,
  `updates` int(10) UNSIGNED DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `retrieve` varchar(100) DEFAULT NULL,
  `resized_width` smallint(5) UNSIGNED DEFAULT NULL,
  `resized_height` smallint(5) UNSIGNED DEFAULT NULL,
  `resize_error` tinyint(3) UNSIGNED DEFAULT NULL,
  `webp_size` int(10) UNSIGNED DEFAULT NULL,
  `webp_error` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_ewwwio_queue`
--

CREATE TABLE `wp_ewwwio_queue` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attachment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gallery` varchar(20) DEFAULT NULL,
  `scanned` tinyint(4) NOT NULL DEFAULT 0,
  `new` tinyint(4) NOT NULL DEFAULT 0,
  `convert_once` tinyint(4) NOT NULL DEFAULT 0,
  `force_reopt` tinyint(4) NOT NULL DEFAULT 0,
  `force_smart` tinyint(4) NOT NULL DEFAULT 0,
  `webp_only` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_ht_tmdb_queue`
--

CREATE TABLE `wp_ht_tmdb_queue` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tmdb_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(10) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `priority` tinyint(4) DEFAULT 0,
  `attempts` tinyint(4) DEFAULT 0,
  `last_attempt` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_imagify_files`
--

CREATE TABLE `wp_imagify_files` (
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `folder_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `file_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `path` varchar(191) NOT NULL DEFAULT '',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `mime_type` varchar(100) NOT NULL DEFAULT '',
  `modified` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `width` smallint(2) UNSIGNED NOT NULL DEFAULT 0,
  `height` smallint(2) UNSIGNED NOT NULL DEFAULT 0,
  `original_size` int(4) UNSIGNED NOT NULL DEFAULT 0,
  `optimized_size` int(4) UNSIGNED DEFAULT NULL,
  `percent` smallint(2) UNSIGNED DEFAULT NULL,
  `optimization_level` tinyint(1) UNSIGNED DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `error` varchar(255) DEFAULT NULL,
  `data` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_imagify_folders`
--

CREATE TABLE `wp_imagify_folders` (
  `folder_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(191) NOT NULL DEFAULT '',
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_ins_view_history`
--

CREATE TABLE `wp_ins_view_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(20) NOT NULL,
  `first_viewed_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_viewed_at` datetime NOT NULL DEFAULT current_timestamp(),
  `view_count` bigint(20) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_ins_watchlists`
--

CREATE TABLE `wp_ins_watchlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `description` text DEFAULT NULL,
  `visibility` varchar(20) NOT NULL DEFAULT 'public',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_ins_watchlist_items`
--

CREATE TABLE `wp_ins_watchlist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `list_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_cm_reference`
--

CREATE TABLE `wp_kirki_cm_reference` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `field_meta_key` varchar(255) NOT NULL,
  `ref_post_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_collaborations`
--

CREATE TABLE `wp_kirki_collaborations` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `parent` varchar(255) NOT NULL COMMENT 'table name',
  `parent_id` bigint(20) DEFAULT NULL COMMENT 'table row id',
  `data` longtext DEFAULT NULL COMMENT 'json data',
  `status` int(1) NOT NULL COMMENT '1=active, 2=sent, 0=expire',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_collaborations_connected`
--

CREATE TABLE `wp_kirki_collaborations_connected` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_collaborations_sent`
--

CREATE TABLE `wp_kirki_collaborations_sent` (
  `id` bigint(20) NOT NULL,
  `collaboration_id` bigint(20) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_comments`
--

CREATE TABLE `wp_kirki_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `meta_data` varchar(255) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1=active, 2=resolved, 0=deleted',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_comments_seen`
--

CREATE TABLE `wp_kirki_comments_seen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_forms`
--

CREATE TABLE `wp_kirki_forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `form_ele_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT 'My Kirki Form',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_kirki_forms_data`
--

CREATE TABLE `wp_kirki_forms_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `input_key` varchar(255) NOT NULL,
  `input_value` varchar(2000) NOT NULL,
  `input_type` varchar(100) NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_license_envato_userlist`
--

CREATE TABLE `wp_license_envato_userlist` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT '',
  `itemid` varchar(30) NOT NULL DEFAULT '',
  `purchasecode` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `licensetype` varchar(255) NOT NULL DEFAULT '',
  `sold_at` varchar(255) NOT NULL DEFAULT '',
  `support_amount` varchar(255) NOT NULL DEFAULT '',
  `supported_until` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_links`
--

CREATE TABLE `wp_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `link_rss` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_litespeed_avatar`
--

CREATE TABLE `wp_litespeed_avatar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(1000) NOT NULL DEFAULT '',
  `md5` varchar(128) NOT NULL DEFAULT '',
  `dateline` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_litespeed_crawler_blacklist`
--

CREATE TABLE `wp_litespeed_crawler_blacklist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(1000) NOT NULL DEFAULT '',
  `res` varchar(255) NOT NULL DEFAULT '' COMMENT '-=Not Blacklist, B=blacklist',
  `reason` text NOT NULL COMMENT 'Reason for blacklist, comma separated',
  `mtime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_litespeed_img_optming`
--

CREATE TABLE `wp_litespeed_img_optming` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `optm_status` tinyint(4) NOT NULL DEFAULT 0,
  `src` varchar(1000) NOT NULL DEFAULT '',
  `server_info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_litespeed_url`
--

CREATE TABLE `wp_litespeed_url` (
  `id` bigint(20) NOT NULL,
  `url` varchar(500) NOT NULL,
  `cache_tags` varchar(1000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_litespeed_url_file`
--

CREATE TABLE `wp_litespeed_url_file` (
  `id` bigint(20) NOT NULL,
  `url_id` bigint(20) NOT NULL,
  `vary` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'md5 of final vary',
  `filename` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'md5 of file content',
  `type` tinyint(4) NOT NULL COMMENT 'css=1,js=2,ccss=3,ucss=4',
  `mobile` tinyint(4) NOT NULL COMMENT 'mobile=1',
  `webp` tinyint(4) NOT NULL COMMENT 'webp=1',
  `expired` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_loginizer_logs`
--

CREATE TABLE `wp_loginizer_logs` (
  `username` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT 0,
  `count` int(10) NOT NULL DEFAULT 0,
  `lockout` int(10) NOT NULL DEFAULT 0,
  `ip` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_mo_openid_linked_user`
--

CREATE TABLE `wp_mo_openid_linked_user` (
  `id` mediumint(9) NOT NULL,
  `linked_social_app` varchar(55) NOT NULL,
  `linked_email` varchar(55) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `identifier` varchar(100) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_options`
--

CREATE TABLE `wp_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `autoload` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_postmeta`
--

CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_posts`
--

CREATE TABLE `wp_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `post_title` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `post_excerpt` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `post_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pinged` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_post_smtp_logmeta`
--

CREATE TABLE `wp_post_smtp_logmeta` (
  `id` bigint(20) NOT NULL,
  `log_id` bigint(20) NOT NULL,
  `meta_key` longtext DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_post_smtp_logs`
--

CREATE TABLE `wp_post_smtp_logs` (
  `id` bigint(20) NOT NULL,
  `solution` longtext DEFAULT NULL,
  `success` longtext DEFAULT NULL,
  `from_header` longtext DEFAULT NULL,
  `to_header` longtext DEFAULT NULL,
  `cc_header` longtext DEFAULT NULL,
  `bcc_header` longtext DEFAULT NULL,
  `reply_to_header` longtext DEFAULT NULL,
  `transport_uri` longtext DEFAULT NULL,
  `original_to` longtext DEFAULT NULL,
  `original_subject` longtext DEFAULT NULL,
  `original_message` longtext DEFAULT NULL,
  `original_headers` longtext DEFAULT NULL,
  `session_transcript` longtext DEFAULT NULL,
  `time` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_404_logs`
--

CREATE TABLE `wp_rank_math_404_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uri` varchar(255) NOT NULL,
  `accessed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `times_accessed` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `referer` varchar(255) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_analytics_adsense`
--

CREATE TABLE `wp_rank_math_analytics_adsense` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL,
  `earnings` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_analytics_ga`
--

CREATE TABLE `wp_rank_math_analytics_ga` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page` varchar(500) NOT NULL,
  `created` timestamp NOT NULL,
  `pageviews` mediumint(6) NOT NULL,
  `visitors` mediumint(6) NOT NULL,
  `referrer` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_analytics_gsc`
--

CREATE TABLE `wp_rank_math_analytics_gsc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL,
  `query` varchar(1000) NOT NULL,
  `page` varchar(500) NOT NULL,
  `clicks` mediumint(6) NOT NULL,
  `impressions` mediumint(6) NOT NULL,
  `position` double NOT NULL,
  `ctr` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_analytics_inspections`
--

CREATE TABLE `wp_rank_math_analytics_inspections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page` varchar(500) NOT NULL,
  `created` timestamp NOT NULL,
  `index_verdict` varchar(64) NOT NULL,
  `indexing_state` varchar(64) NOT NULL,
  `coverage_state` text NOT NULL,
  `page_fetch_state` varchar(64) NOT NULL,
  `robots_txt_state` varchar(64) NOT NULL,
  `rich_results_verdict` varchar(64) NOT NULL,
  `rich_results_items` longtext NOT NULL,
  `last_crawl_time` timestamp NOT NULL,
  `crawled_as` varchar(64) NOT NULL,
  `google_canonical` text NOT NULL,
  `user_canonical` text NOT NULL,
  `sitemap` text NOT NULL,
  `referring_urls` longtext NOT NULL,
  `raw_api_response` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_analytics_keyword_manager`
--

CREATE TABLE `wp_rank_math_analytics_keyword_manager` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `keyword` varchar(1000) NOT NULL,
  `collection` varchar(200) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_analytics_objects`
--

CREATE TABLE `wp_rank_math_analytics_objects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL,
  `title` text NOT NULL,
  `page` varchar(500) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `object_subtype` varchar(100) NOT NULL,
  `object_id` bigint(20) UNSIGNED NOT NULL,
  `primary_key` varchar(255) NOT NULL,
  `seo_score` tinyint(4) NOT NULL DEFAULT 0,
  `page_score` tinyint(4) NOT NULL DEFAULT 0,
  `is_indexable` tinyint(1) NOT NULL DEFAULT 1,
  `schemas_in_use` varchar(500) DEFAULT NULL,
  `desktop_interactive` double DEFAULT 0,
  `desktop_pagescore` double DEFAULT 0,
  `mobile_interactive` double DEFAULT 0,
  `mobile_pagescore` double DEFAULT 0,
  `pagespeed_refreshed` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_internal_links`
--

CREATE TABLE `wp_rank_math_internal_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `target_post_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(8) NOT NULL,
  `anchor_text` varchar(500) DEFAULT NULL,
  `anchor_type` varchar(10) DEFAULT 'HPLNK',
  `is_nofollow` tinyint(1) DEFAULT 0,
  `target_blank` tinyint(1) DEFAULT 0,
  `url_hash` char(32) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_internal_meta`
--

CREATE TABLE `wp_rank_math_internal_meta` (
  `object_id` bigint(20) UNSIGNED NOT NULL,
  `internal_link_count` int(10) UNSIGNED DEFAULT 0,
  `external_link_count` int(10) UNSIGNED DEFAULT 0,
  `incoming_link_count` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_link_genius_audit`
--

CREATE TABLE `wp_rank_math_link_genius_audit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `url_hash` char(32) NOT NULL,
  `http_status_code` smallint(3) DEFAULT NULL,
  `status_category` varchar(10) DEFAULT NULL COMMENT '2xx, 3xx, 4xx, 5xx, timeout, error',
  `robots_blocked` tinyint(1) DEFAULT 0,
  `is_marked_safe` tinyint(1) DEFAULT 0 COMMENT 'User marked as not broken',
  `last_checked_at` datetime DEFAULT NULL,
  `last_error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_link_genius_history`
--

CREATE TABLE `wp_rank_math_link_genius_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` char(32) NOT NULL,
  `source_type` varchar(20) NOT NULL DEFAULT 'bulk_update' COMMENT 'bulk_update|keyword_map',
  `keyword_map_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Reference to keyword map if source_type is keyword_map',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `operation_type` varchar(20) NOT NULL COMMENT 'anchor|url|both|add_link',
  `filters` longtext NOT NULL COMMENT 'JSON encoded search filters',
  `changes_summary` longtext NOT NULL COMMENT 'JSON: {from_anchor, to_anchor, from_url, to_url}',
  `affected_links_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `affected_posts_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'pending|processing|completed|failed|rolled_back',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_link_genius_maps`
--

CREATE TABLE `wp_rank_math_link_genius_maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'User-friendly name for the rule',
  `description` text DEFAULT NULL COMMENT 'Optional description',
  `target_url` varchar(2083) NOT NULL COMMENT 'Destination URL',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active/inactive toggle',
  `max_links_per_post` int(10) UNSIGNED NOT NULL DEFAULT 3 COMMENT 'Limit per post',
  `auto_link_on_publish` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable auto-linking',
  `case_sensitive` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Case-sensitive matching',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_executed_at` datetime DEFAULT NULL COMMENT 'Last manual execution',
  `execution_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total executions'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_link_genius_map_variations`
--

CREATE TABLE `wp_rank_math_link_genius_map_variations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `keyword_map_id` bigint(20) UNSIGNED NOT NULL,
  `variation` varchar(255) NOT NULL COMMENT 'Keyword variation text',
  `source` varchar(20) NOT NULL DEFAULT 'manual' COMMENT 'ai or manual',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_link_genius_snapshots`
--

CREATE TABLE `wp_rank_math_link_genius_snapshots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` char(32) NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `original_content` longtext NOT NULL COMMENT 'Post content before update',
  `link_changes` longtext NOT NULL COMMENT 'JSON array of link changes',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_redirections`
--

CREATE TABLE `wp_rank_math_redirections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sources` longtext NOT NULL,
  `url_to` text NOT NULL,
  `header_code` smallint(4) UNSIGNED NOT NULL,
  `hits` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(25) NOT NULL DEFAULT 'active',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_accessed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_rank_math_redirections_cache`
--

CREATE TABLE `wp_rank_math_redirections_cache` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `redirection_id` bigint(20) UNSIGNED NOT NULL,
  `object_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `object_type` varchar(10) NOT NULL DEFAULT 'post',
  `is_redirected` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_css`
--

CREATE TABLE `wp_revslider_css` (
  `id` int(9) NOT NULL,
  `handle` text NOT NULL,
  `settings` longtext DEFAULT NULL,
  `hover` longtext DEFAULT NULL,
  `advanced` longtext DEFAULT NULL,
  `params` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_layer_animations`
--

CREATE TABLE `wp_revslider_layer_animations` (
  `id` int(9) NOT NULL,
  `handle` text NOT NULL,
  `params` text NOT NULL,
  `settings` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_navigations`
--

CREATE TABLE `wp_revslider_navigations` (
  `id` int(9) NOT NULL,
  `name` varchar(191) NOT NULL,
  `handle` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `css` longtext NOT NULL,
  `markup` longtext NOT NULL,
  `settings` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_sliders`
--

CREATE TABLE `wp_revslider_sliders` (
  `id` int(9) NOT NULL,
  `title` tinytext NOT NULL,
  `alias` tinytext DEFAULT NULL,
  `params` longtext NOT NULL,
  `settings` text DEFAULT NULL,
  `type` varchar(191) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_sliders7`
--

CREATE TABLE `wp_revslider_sliders7` (
  `id` int(9) NOT NULL,
  `title` tinytext NOT NULL,
  `alias` tinytext DEFAULT NULL,
  `params` longtext NOT NULL,
  `settings` text DEFAULT NULL,
  `type` varchar(191) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_slides`
--

CREATE TABLE `wp_revslider_slides` (
  `id` int(9) NOT NULL,
  `slider_id` int(9) NOT NULL,
  `slide_order` int(11) NOT NULL,
  `params` longtext NOT NULL,
  `layers` longtext NOT NULL,
  `settings` text NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_slides7`
--

CREATE TABLE `wp_revslider_slides7` (
  `id` int(9) NOT NULL,
  `slider_id` int(11) NOT NULL,
  `slide_order` int(11) NOT NULL,
  `params` longtext NOT NULL,
  `layers` longtext NOT NULL,
  `settings` text NOT NULL DEFAULT '',
  `static` varchar(191) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_revslider_static_slides`
--

CREATE TABLE `wp_revslider_static_slides` (
  `id` int(9) NOT NULL,
  `slider_id` int(9) NOT NULL,
  `params` longtext NOT NULL,
  `layers` longtext NOT NULL,
  `settings` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_snippets`
--

CREATE TABLE `wp_snippets` (
  `id` bigint(20) NOT NULL,
  `name` tinytext NOT NULL,
  `description` text NOT NULL,
  `code` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `scope` varchar(15) NOT NULL DEFAULT 'global',
  `condition_id` bigint(20) NOT NULL DEFAULT 0,
  `priority` smallint(6) NOT NULL DEFAULT 10,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `modified` datetime NOT NULL DEFAULT current_timestamp(),
  `revision` bigint(20) NOT NULL DEFAULT 1,
  `cloud_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_social_users`
--

CREATE TABLE `wp_social_users` (
  `social_users_id` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `identifier` varchar(100) NOT NULL,
  `register_date` datetime DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `link_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_termmeta`
--

CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_terms`
--

CREATE TABLE `wp_terms` (
  `term_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0,
  `term_order` int(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_relationships`
--

CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_taxonomy`
--

CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `description` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_usermeta`
--

CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

CREATE TABLE `wp_users` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_department`
--

CREATE TABLE `wp_wbca_department` (
  `id` int(10) UNSIGNED NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_message`
--

CREATE TABLE `wp_wbca_message` (
  `id` int(11) NOT NULL,
  `user_sender` int(11) NOT NULL,
  `user_receiver` int(11) NOT NULL,
  `message` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `chat_read` tinyint(1) NOT NULL,
  `has_attachment` tinyint(1) DEFAULT NULL,
  `wbca_transferred` tinyint(1) NOT NULL DEFAULT 0,
  `chat_time` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_search_document`
--

CREATE TABLE `wp_wbca_search_document` (
  `DOCUMENT_ID` int(10) UNSIGNED NOT NULL,
  `DOCUMENT_TITLE` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `DESCRIPTION` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_search_index`
--

CREATE TABLE `wp_wbca_search_index` (
  `TERM_ID` int(10) UNSIGNED NOT NULL,
  `DOCUMENT_ID` int(10) UNSIGNED NOT NULL,
  `OFSET` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_search_term`
--

CREATE TABLE `wp_wbca_search_term` (
  `TERM_ID` int(10) UNSIGNED NOT NULL,
  `TERM_VALUE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_user_department`
--

CREATE TABLE `wp_wbca_user_department` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dept_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wbca_user_personal_info`
--

CREATE TABLE `wp_wbca_user_personal_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `USER_ID` bigint(20) UNSIGNED NOT NULL,
  `macadd` varchar(255) DEFAULT NULL,
  `ipadd` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `is_phone` varchar(10) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `os_name` varchar(255) DEFAULT NULL,
  `userAgent` varchar(255) DEFAULT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `screen_resolution` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_admin_notes`
--

CREATE TABLE `wp_wc_admin_notes` (
  `note_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `locale` varchar(20) NOT NULL,
  `title` longtext NOT NULL,
  `content` longtext NOT NULL,
  `content_data` longtext DEFAULT NULL,
  `status` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_reminder` datetime DEFAULT NULL,
  `is_snoozable` tinyint(1) NOT NULL DEFAULT 0,
  `layout` varchar(20) NOT NULL DEFAULT '',
  `image` varchar(200) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(200) NOT NULL DEFAULT 'info'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_admin_note_actions`
--

CREATE TABLE `wp_wc_admin_note_actions` (
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `note_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `query` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `actioned_text` varchar(255) NOT NULL,
  `nonce_action` varchar(255) DEFAULT NULL,
  `nonce_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_category_lookup`
--

CREATE TABLE `wp_wc_category_lookup` (
  `category_tree_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_customer_lookup`
--

CREATE TABLE `wp_wc_customer_lookup` (
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(60) NOT NULL DEFAULT '',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_last_active` timestamp NULL DEFAULT NULL,
  `date_registered` timestamp NULL DEFAULT NULL,
  `country` char(2) NOT NULL DEFAULT '',
  `postcode` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_download_log`
--

CREATE TABLE `wp_wc_download_log` (
  `download_log_id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` datetime NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_ip_address` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_orders`
--

CREATE TABLE `wp_wc_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `tax_amount` decimal(26,8) DEFAULT NULL,
  `total_amount` decimal(26,8) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `billing_email` varchar(320) DEFAULT NULL,
  `date_created_gmt` datetime DEFAULT NULL,
  `date_updated_gmt` datetime DEFAULT NULL,
  `parent_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_method_title` text DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `customer_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_orders_meta`
--

CREATE TABLE `wp_wc_orders_meta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_order_addresses`
--

CREATE TABLE `wp_wc_order_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `address_type` varchar(20) DEFAULT NULL,
  `first_name` text DEFAULT NULL,
  `last_name` text DEFAULT NULL,
  `company` text DEFAULT NULL,
  `address_1` text DEFAULT NULL,
  `address_2` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `postcode` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_order_coupon_lookup`
--

CREATE TABLE `wp_wc_order_coupon_lookup` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_amount` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_order_operational_data`
--

CREATE TABLE `wp_wc_order_operational_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_via` varchar(100) DEFAULT NULL,
  `woocommerce_version` varchar(20) DEFAULT NULL,
  `prices_include_tax` tinyint(1) DEFAULT NULL,
  `coupon_usages_are_counted` tinyint(1) DEFAULT NULL,
  `download_permission_granted` tinyint(1) DEFAULT NULL,
  `cart_hash` varchar(100) DEFAULT NULL,
  `new_order_email_sent` tinyint(1) DEFAULT NULL,
  `order_key` varchar(100) DEFAULT NULL,
  `order_stock_reduced` tinyint(1) DEFAULT NULL,
  `date_paid_gmt` datetime DEFAULT NULL,
  `date_completed_gmt` datetime DEFAULT NULL,
  `shipping_tax_amount` decimal(26,8) DEFAULT NULL,
  `shipping_total_amount` decimal(26,8) DEFAULT NULL,
  `discount_tax_amount` decimal(26,8) DEFAULT NULL,
  `discount_total_amount` decimal(26,8) DEFAULT NULL,
  `recorded_sales` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_order_product_lookup`
--

CREATE TABLE `wp_wc_order_product_lookup` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variation_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `product_qty` int(11) NOT NULL,
  `product_net_revenue` double NOT NULL DEFAULT 0,
  `product_gross_revenue` double NOT NULL DEFAULT 0,
  `coupon_amount` double NOT NULL DEFAULT 0,
  `tax_amount` double NOT NULL DEFAULT 0,
  `shipping_amount` double NOT NULL DEFAULT 0,
  `shipping_tax_amount` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_order_stats`
--

CREATE TABLE `wp_wc_order_stats` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_paid` datetime DEFAULT '0000-00-00 00:00:00',
  `date_completed` datetime DEFAULT '0000-00-00 00:00:00',
  `num_items_sold` int(11) NOT NULL DEFAULT 0,
  `total_sales` double NOT NULL DEFAULT 0,
  `tax_total` double NOT NULL DEFAULT 0,
  `shipping_total` double NOT NULL DEFAULT 0,
  `net_total` double NOT NULL DEFAULT 0,
  `returning_customer` tinyint(1) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_order_tax_lookup`
--

CREATE TABLE `wp_wc_order_tax_lookup` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `tax_rate_id` bigint(20) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shipping_tax` double NOT NULL DEFAULT 0,
  `order_tax` double NOT NULL DEFAULT 0,
  `total_tax` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_product_attributes_lookup`
--

CREATE TABLE `wp_wc_product_attributes_lookup` (
  `product_id` bigint(20) NOT NULL,
  `product_or_parent_id` bigint(20) NOT NULL,
  `taxonomy` varchar(32) NOT NULL,
  `term_id` bigint(20) NOT NULL,
  `is_variation_attribute` tinyint(1) NOT NULL,
  `in_stock` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_product_download_directories`
--

CREATE TABLE `wp_wc_product_download_directories` (
  `url_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(256) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_product_meta_lookup`
--

CREATE TABLE `wp_wc_product_meta_lookup` (
  `product_id` bigint(20) NOT NULL,
  `sku` varchar(100) DEFAULT '',
  `global_unique_id` varchar(100) DEFAULT '',
  `virtual` tinyint(1) DEFAULT 0,
  `downloadable` tinyint(1) DEFAULT 0,
  `min_price` decimal(19,4) DEFAULT NULL,
  `max_price` decimal(19,4) DEFAULT NULL,
  `onsale` tinyint(1) DEFAULT 0,
  `stock_quantity` double DEFAULT NULL,
  `stock_status` varchar(100) DEFAULT 'instock',
  `rating_count` bigint(20) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `total_sales` bigint(20) DEFAULT 0,
  `tax_status` varchar(100) DEFAULT 'taxable',
  `tax_class` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_rate_limits`
--

CREATE TABLE `wp_wc_rate_limits` (
  `rate_limit_id` bigint(20) UNSIGNED NOT NULL,
  `rate_limit_key` varchar(200) NOT NULL,
  `rate_limit_expiry` bigint(20) UNSIGNED NOT NULL,
  `rate_limit_remaining` smallint(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_reserved_stock`
--

CREATE TABLE `wp_wc_reserved_stock` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `stock_quantity` double NOT NULL DEFAULT 0,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_tax_rate_classes`
--

CREATE TABLE `wp_wc_tax_rate_classes` (
  `tax_rate_class_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wc_webhooks`
--

CREATE TABLE `wp_wc_webhooks` (
  `webhook_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(200) NOT NULL,
  `name` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_url` text NOT NULL,
  `secret` text NOT NULL,
  `topic` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `api_version` smallint(4) NOT NULL,
  `failure_count` smallint(10) NOT NULL DEFAULT 0,
  `pending_delivery` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_api_keys`
--

CREATE TABLE `wp_woocommerce_api_keys` (
  `key_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `permissions` varchar(10) NOT NULL,
  `consumer_key` char(64) NOT NULL,
  `consumer_secret` char(43) NOT NULL,
  `nonces` longtext DEFAULT NULL,
  `truncated_key` char(7) NOT NULL,
  `last_access` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_attribute_taxonomies`
--

CREATE TABLE `wp_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_name` varchar(200) NOT NULL,
  `attribute_label` varchar(200) DEFAULT NULL,
  `attribute_type` varchar(20) NOT NULL,
  `attribute_orderby` varchar(20) NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_downloadable_product_permissions`
--

CREATE TABLE `wp_woocommerce_downloadable_product_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `download_id` varchar(36) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `order_key` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `downloads_remaining` varchar(9) DEFAULT NULL,
  `access_granted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access_expires` datetime DEFAULT NULL,
  `download_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_log`
--

CREATE TABLE `wp_woocommerce_log` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` datetime NOT NULL,
  `level` smallint(4) NOT NULL,
  `source` varchar(200) NOT NULL,
  `message` longtext NOT NULL,
  `context` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_order_itemmeta`
--

CREATE TABLE `wp_woocommerce_order_itemmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_order_items`
--

CREATE TABLE `wp_woocommerce_order_items` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_name` text NOT NULL,
  `order_item_type` varchar(200) NOT NULL DEFAULT '',
  `order_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_payment_tokenmeta`
--

CREATE TABLE `wp_woocommerce_payment_tokenmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `payment_token_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_payment_tokens`
--

CREATE TABLE `wp_woocommerce_payment_tokens` (
  `token_id` bigint(20) UNSIGNED NOT NULL,
  `gateway_id` varchar(200) NOT NULL,
  `token` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(200) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_sessions`
--

CREATE TABLE `wp_woocommerce_sessions` (
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `session_key` char(32) NOT NULL,
  `session_value` longtext NOT NULL,
  `session_expiry` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_shipping_zones`
--

CREATE TABLE `wp_woocommerce_shipping_zones` (
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `zone_name` varchar(200) NOT NULL,
  `zone_order` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_shipping_zone_locations`
--

CREATE TABLE `wp_woocommerce_shipping_zone_locations` (
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `location_code` varchar(200) NOT NULL,
  `location_type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_shipping_zone_methods`
--

CREATE TABLE `wp_woocommerce_shipping_zone_methods` (
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `instance_id` bigint(20) UNSIGNED NOT NULL,
  `method_id` varchar(200) NOT NULL,
  `method_order` bigint(20) UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_tax_rates`
--

CREATE TABLE `wp_woocommerce_tax_rates` (
  `tax_rate_id` bigint(20) UNSIGNED NOT NULL,
  `tax_rate_country` varchar(2) NOT NULL DEFAULT '',
  `tax_rate_state` varchar(200) NOT NULL DEFAULT '',
  `tax_rate` varchar(8) NOT NULL DEFAULT '',
  `tax_rate_name` varchar(200) NOT NULL DEFAULT '',
  `tax_rate_priority` bigint(20) UNSIGNED NOT NULL,
  `tax_rate_compound` int(1) NOT NULL DEFAULT 0,
  `tax_rate_shipping` int(1) NOT NULL DEFAULT 1,
  `tax_rate_order` bigint(20) UNSIGNED NOT NULL,
  `tax_rate_class` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_woocommerce_tax_rate_locations`
--

CREATE TABLE `wp_woocommerce_tax_rate_locations` (
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `location_code` varchar(200) NOT NULL,
  `tax_rate_id` bigint(20) UNSIGNED NOT NULL,
  `location_type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_chatlogs`
--

CREATE TABLE `wp_wpaicg_chatlogs` (
  `id` mediumint(11) NOT NULL,
  `log_session` varchar(255) NOT NULL,
  `data` longtext NOT NULL,
  `page_title` text DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_chattokens`
--

CREATE TABLE `wp_wpaicg_chattokens` (
  `id` mediumint(11) NOT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_formtokens`
--

CREATE TABLE `wp_wpaicg_formtokens` (
  `id` mediumint(11) NOT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_form_feedback`
--

CREATE TABLE `wp_wpaicg_form_feedback` (
  `id` mediumint(11) NOT NULL,
  `formID` mediumint(11) NOT NULL,
  `eventID` mediumint(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `formname` varchar(255) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `feedback` enum('thumbs_up','thumbs_down') NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_form_logs`
--

CREATE TABLE `wp_wpaicg_form_logs` (
  `id` mediumint(11) NOT NULL,
  `prompt` text NOT NULL,
  `source` int(11) NOT NULL DEFAULT 0,
  `data` longtext NOT NULL,
  `prompt_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `eventID` mediumint(11) DEFAULT NULL,
  `userID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_imagetokens`
--

CREATE TABLE `wp_wpaicg_imagetokens` (
  `id` mediumint(11) NOT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_image_logs`
--

CREATE TABLE `wp_wpaicg_image_logs` (
  `id` mediumint(11) NOT NULL,
  `prompt` text NOT NULL,
  `source` int(11) NOT NULL DEFAULT 0,
  `shortcode` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `total` int(11) NOT NULL DEFAULT 0,
  `duration` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_promptbase_logs`
--

CREATE TABLE `wp_wpaicg_promptbase_logs` (
  `id` mediumint(11) NOT NULL,
  `prompt` text NOT NULL,
  `source` int(11) NOT NULL DEFAULT 0,
  `data` longtext NOT NULL,
  `prompt_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `eventID` mediumint(11) DEFAULT NULL,
  `userID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_prompttokens`
--

CREATE TABLE `wp_wpaicg_prompttokens` (
  `id` mediumint(11) NOT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_prompt_feedback`
--

CREATE TABLE `wp_wpaicg_prompt_feedback` (
  `id` mediumint(11) NOT NULL,
  `formID` mediumint(11) NOT NULL,
  `eventID` mediumint(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `formname` varchar(255) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `feedback` enum('thumbs_up','thumbs_down') NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_rsslogs`
--

CREATE TABLE `wp_wpaicg_rsslogs` (
  `id` mediumint(11) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `track_id` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpaicg_token_logs`
--

CREATE TABLE `wp_wpaicg_token_logs` (
  `id` mediumint(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `tokens` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_chat_report`
--

CREATE TABLE `wp_wpbot_chat_report` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conversation_id` int(11) DEFAULT NULL,
  `message` longtext NOT NULL,
  `feedback` varchar(20) DEFAULT NULL,
  `meta_info` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_Conversation`
--

CREATE TABLE `wp_wpbot_Conversation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conversation` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_extented_synonyms`
--

CREATE TABLE `wp_wpbot_extented_synonyms` (
  `id` int(11) NOT NULL,
  `word` varchar(220) DEFAULT NULL,
  `synonyms` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_failed_response`
--

CREATE TABLE `wp_wpbot_failed_response` (
  `id` int(11) NOT NULL,
  `query` varchar(256) NOT NULL,
  `count` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_response`
--

CREATE TABLE `wp_wpbot_response` (
  `id` int(11) UNSIGNED NOT NULL,
  `query` text NOT NULL,
  `keyword` text NOT NULL,
  `response` text NOT NULL,
  `category` varchar(256) NOT NULL,
  `intent` varchar(256) NOT NULL,
  `custom` text NOT NULL,
  `lang` varchar(25) NOT NULL,
  `trigger_intent` varchar(100) NOT NULL,
  `users_answer` text NOT NULL,
  `hidden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_response_category`
--

CREATE TABLE `wp_wpbot_response_category` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `custom` varchar(256) NOT NULL,
  `lang` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_response_entities`
--

CREATE TABLE `wp_wpbot_response_entities` (
  `id` int(11) NOT NULL,
  `entity_name` varchar(256) NOT NULL,
  `entity` varchar(256) NOT NULL,
  `synonyms` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_sessions`
--

CREATE TABLE `wp_wpbot_sessions` (
  `id` int(11) NOT NULL,
  `session` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_subscription`
--

CREATE TABLE `wp_wpbot_subscription` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `url` text NOT NULL,
  `date` datetime NOT NULL,
  `user_agent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpbot_user`
--

CREATE TABLE `wp_wpbot_user` (
  `id` int(11) NOT NULL,
  `session_id` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpfm_backup`
--

CREATE TABLE `wp_wpfm_backup` (
  `id` int(11) NOT NULL,
  `backup_name` text DEFAULT NULL,
  `backup_date` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_batch_log`
--

CREATE TABLE `wp_wpil_ai_batch_log` (
  `log_index` bigint(20) UNSIGNED NOT NULL,
  `batch_id` varchar(128) DEFAULT NULL,
  `batch_data` longtext DEFAULT NULL,
  `process_id` tinyint(1) DEFAULT 0,
  `process_time` bigint(20) UNSIGNED DEFAULT NULL,
  `check_time` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_completed_batch_log`
--

CREATE TABLE `wp_wpil_ai_completed_batch_log` (
  `log_index` bigint(20) UNSIGNED NOT NULL,
  `batch_id` varchar(128) DEFAULT NULL,
  `batch_status` varchar(128) DEFAULT NULL,
  `process_time` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_embedding_calculation_data`
--

CREATE TABLE `wp_wpil_ai_embedding_calculation_data` (
  `embed_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `calculation` longtext DEFAULT NULL,
  `calc_index` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `calc_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_embedding_calculation_data_v2`
--

CREATE TABLE `wp_wpil_ai_embedding_calculation_data_v2` (
  `embed_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `target_post_type` varchar(8) DEFAULT NULL,
  `starting_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `ending_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `calculation` longtext DEFAULT NULL,
  `calc_index` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `calc_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_embedding_data`
--

CREATE TABLE `wp_wpil_ai_embedding_data` (
  `embed_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `embed_data` longtext DEFAULT NULL,
  `is_empty` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_embedding_phrase_calculation_data`
--

CREATE TABLE `wp_wpil_ai_embedding_phrase_calculation_data` (
  `embed_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `post_phrase_id` varchar(168) DEFAULT NULL,
  `calculation` longtext DEFAULT NULL,
  `calc_index` longtext DEFAULT NULL,
  `calc_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `no_data` tinyint(1) DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL,
  `dimension_count` int(8) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_embedding_phrase_data`
--

CREATE TABLE `wp_wpil_ai_embedding_phrase_data` (
  `embed_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `post_phrase_id` varchar(168) DEFAULT NULL,
  `embed_data` longtext DEFAULT NULL,
  `no_data` tinyint(1) DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL,
  `dimension_count` int(8) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_error_log`
--

CREATE TABLE `wp_wpil_ai_error_log` (
  `log_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `batch_id` varchar(128) DEFAULT NULL,
  `batch_data` longtext DEFAULT NULL,
  `message_text` longtext DEFAULT NULL,
  `process_time` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_keyword_data`
--

CREATE TABLE `wp_wpil_ai_keyword_data` (
  `ai_keyword_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `keywords` longtext DEFAULT NULL,
  `keyword_count` int(11) DEFAULT 0,
  `keywords_loaded` tinyint(1) DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_linking`
--

CREATE TABLE `wp_wpil_ai_linking` (
  `ai_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `target_type` varchar(8) DEFAULT NULL,
  `target_data_type` tinyint(1) DEFAULT 1,
  `sentence_text` text DEFAULT NULL,
  `sentence_id` varchar(168) DEFAULT '',
  `sentence_with_anchor_text` text DEFAULT NULL,
  `ai_relation_score` double UNSIGNED NOT NULL DEFAULT 0,
  `ignored` tinyint(1) DEFAULT 0,
  `inserted` tinyint(1) DEFAULT 0,
  `process_key` varchar(64) DEFAULT '',
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_post_data`
--

CREATE TABLE `wp_wpil_ai_post_data` (
  `ai_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `summary` longtext DEFAULT NULL,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_processed_sentences`
--

CREATE TABLE `wp_wpil_ai_processed_sentences` (
  `sentence_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `has_link` tinyint(1) DEFAULT 0,
  `sentence_id` varchar(168) DEFAULT NULL,
  `process_time` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_product_data`
--

CREATE TABLE `wp_wpil_ai_product_data` (
  `ai_product_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `products` longtext DEFAULT NULL,
  `product_count` int(11) DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_suggested_anchors`
--

CREATE TABLE `wp_wpil_ai_suggested_anchors` (
  `suggestion_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `data_type` tinyint(1) DEFAULT 1,
  `sentence_post_id` varchar(168) DEFAULT NULL,
  `sentence_id` varchar(168) DEFAULT NULL,
  `suggestion_words` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `target_type` varchar(8) DEFAULT NULL,
  `target_data_type` tinyint(1) DEFAULT 1,
  `link_score` int(4) DEFAULT NULL,
  `ignore_suggestion` tinyint(1) DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_system_error_log`
--

CREATE TABLE `wp_wpil_ai_system_error_log` (
  `log_index` bigint(20) UNSIGNED NOT NULL,
  `message_text` longtext DEFAULT NULL,
  `log_data` longtext DEFAULT NULL,
  `process_time` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ai_token_use_data`
--

CREATE TABLE `wp_wpil_ai_token_use_data` (
  `token_index` bigint(20) UNSIGNED NOT NULL,
  `query_id` varchar(128) DEFAULT NULL,
  `transaction_type` varchar(24) NOT NULL DEFAULT 'usage',
  `transaction_ref` varchar(128) DEFAULT NULL,
  `transaction_note` varchar(64) DEFAULT NULL,
  `model_version` varchar(168) DEFAULT NULL,
  `batch_processed` tinyint(1) DEFAULT 0,
  `input_tokens` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `output_tokens` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cached_prompt_tokens` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reasoning_tokens` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_tokens` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `credits_used` decimal(10,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `credits_added` decimal(10,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `process_used` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `process_key` varchar(64) NOT NULL DEFAULT '',
  `process_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_broken_links`
--

CREATE TABLE `wp_wpil_broken_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `internal` tinyint(1) DEFAULT 0,
  `code` int(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_checked` datetime DEFAULT NULL,
  `check_count` int(2) DEFAULT 0,
  `ignore_link` tinyint(1) DEFAULT 0,
  `sentence` varchar(255) DEFAULT '0',
  `anchor` text NOT NULL,
  `raw_anchor` text DEFAULT NULL,
  `suggested_url_replacement` text DEFAULT NULL,
  `recommended_action` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_click_data`
--

CREATE TABLE `wp_wpil_click_data` (
  `click_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `post_type` varchar(10) DEFAULT NULL,
  `click_date` datetime DEFAULT NULL,
  `user_ip` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `link_url` text DEFAULT NULL,
  `link_anchor` text DEFAULT NULL,
  `link_location` varchar(64) DEFAULT NULL,
  `tracking_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_ignore_links`
--

CREATE TABLE `wp_wpil_ignore_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `internal` tinyint(1) DEFAULT 0,
  `code` int(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_keywords`
--

CREATE TABLE `wp_wpil_keywords` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `link` varchar(512) NOT NULL,
  `add_same_link` int(1) UNSIGNED NOT NULL,
  `link_once` int(1) UNSIGNED NOT NULL,
  `restrict_to_live` int(1) UNSIGNED NOT NULL,
  `limit_inserts` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `insert_limit` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `select_links` tinyint(1) DEFAULT 0,
  `set_priority` tinyint(1) DEFAULT 0,
  `priority_setting` int(11) DEFAULT 0,
  `prioritize_longtail` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `restrict_date` tinyint(1) DEFAULT 0,
  `restricted_date` datetime DEFAULT NULL,
  `restrict_cats` tinyint(1) DEFAULT 0,
  `restricted_cats` text DEFAULT NULL,
  `case_sensitive` tinyint(1) DEFAULT 0,
  `force_insert` tinyint(1) DEFAULT 0,
  `same_lang` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `auto_imported` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `auto_managed` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_keyword_links`
--

CREATE TABLE `wp_wpil_keyword_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyword_id` int(10) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(10) NOT NULL,
  `anchor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_keyword_select_links`
--

CREATE TABLE `wp_wpil_keyword_select_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyword_id` int(10) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(10) NOT NULL,
  `sentence_text` text DEFAULT NULL,
  `case_keyword` text DEFAULT NULL,
  `meta_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_link_mapping`
--

CREATE TABLE `wp_wpil_link_mapping` (
  `id` int(10) UNSIGNED NOT NULL,
  `map_date` bigint(20) UNSIGNED NOT NULL,
  `map_name` varchar(128) DEFAULT NULL,
  `map_data` longtext DEFAULT NULL,
  `process_key` varchar(128) DEFAULT NULL,
  `item_count` int(10) DEFAULT 0,
  `last_index` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_related_posts`
--

CREATE TABLE `wp_wpil_related_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(8) DEFAULT NULL,
  `processed` tinyint(1) DEFAULT 0,
  `manual_process` tinyint(1) DEFAULT 0,
  `process_time` bigint(20) DEFAULT NULL,
  `related_post_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_relation_mapping`
--

CREATE TABLE `wp_wpil_relation_mapping` (
  `id` int(10) UNSIGNED NOT NULL,
  `map_date` bigint(20) UNSIGNED NOT NULL,
  `map_data` longtext DEFAULT NULL,
  `process_key` varchar(128) DEFAULT NULL,
  `work_scope` varchar(24) NOT NULL DEFAULT 'default',
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `post_type` varchar(24) NOT NULL DEFAULT '',
  `is_pillar` tinyint(1) NOT NULL DEFAULT 0,
  `item_processed` tinyint(1) NOT NULL DEFAULT 0,
  `ai_processed` tinyint(1) NOT NULL DEFAULT 0,
  `pillar_processed` tinyint(1) DEFAULT NULL,
  `map_processed` tinyint(1) DEFAULT NULL,
  `item_count` int(10) DEFAULT 0,
  `last_index` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_report_links`
--

CREATE TABLE `wp_wpil_report_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `target_type` varchar(8) DEFAULT NULL,
  `clean_url` text DEFAULT NULL,
  `raw_url` text DEFAULT NULL,
  `host` text DEFAULT NULL,
  `anchor` text DEFAULT NULL,
  `raw_anchor` text DEFAULT NULL,
  `anchor_word_count` int(10) NOT NULL DEFAULT 0,
  `url_slug_word_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `anchor_slug_positional_match` double UNSIGNED NOT NULL DEFAULT 0,
  `internal` tinyint(1) DEFAULT 0,
  `has_links` tinyint(1) NOT NULL DEFAULT 0,
  `post_type` varchar(8) DEFAULT NULL,
  `location` varchar(20) DEFAULT NULL,
  `broken_link_scanned` tinyint(1) DEFAULT 0,
  `link_whisper_created` tinyint(1) DEFAULT 0,
  `is_autolink` tinyint(1) DEFAULT 0,
  `tracking_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `module_link` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `link_context` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `ai_relation_score` double UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_search_console_data`
--

CREATE TABLE `wp_wpil_search_console_data` (
  `gsc_index` bigint(20) UNSIGNED NOT NULL,
  `page_url` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `clicks` bigint(20) UNSIGNED NOT NULL,
  `impressions` bigint(20) UNSIGNED NOT NULL,
  `ctr` float DEFAULT NULL,
  `position` float DEFAULT NULL,
  `scan_date_start` datetime DEFAULT NULL,
  `scan_date_end` datetime DEFAULT NULL,
  `processed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_sitemaps`
--

CREATE TABLE `wp_wpil_sitemaps` (
  `sitemap_id` bigint(20) UNSIGNED NOT NULL,
  `sitemap_name` text DEFAULT NULL,
  `sitemap_content` longtext DEFAULT NULL,
  `sitemap_type` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_snoozed_links`
--

CREATE TABLE `wp_wpil_snoozed_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` text DEFAULT NULL,
  `normalized_url` varchar(191) NOT NULL DEFAULT '',
  `snoozed_until` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_target_keyword_data`
--

CREATE TABLE `wp_wpil_target_keyword_data` (
  `keyword_index` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `post_type` varchar(10) DEFAULT NULL,
  `keyword_type` varchar(255) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `checked` tinyint(1) DEFAULT NULL,
  `impressions` bigint(20) UNSIGNED DEFAULT 0,
  `clicks` bigint(20) UNSIGNED DEFAULT 0,
  `ctr` float DEFAULT NULL,
  `position` float DEFAULT NULL,
  `save_date` datetime DEFAULT NULL,
  `auto_checked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_telemetry_log`
--

CREATE TABLE `wp_wpil_telemetry_log` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(191) DEFAULT NULL,
  `event_data` longtext DEFAULT NULL,
  `event_time` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pinged` tinyint(1) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_tracked_link_ids`
--

CREATE TABLE `wp_wpil_tracked_link_ids` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `creation_time` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_urls`
--

CREATE TABLE `wp_wpil_urls` (
  `id` int(10) UNSIGNED NOT NULL,
  `old` varchar(255) NOT NULL,
  `new` varchar(255) NOT NULL,
  `wildcard_match` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpil_url_links`
--

CREATE TABLE `wp_wpil_url_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `url_id` int(10) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_type` varchar(10) NOT NULL,
  `anchor` varchar(255) NOT NULL,
  `original_url` text NOT NULL,
  `relative_link` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpmailsmtp_debug_events`
--

CREATE TABLE `wp_wpmailsmtp_debug_events` (
  `id` int(10) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `initiator` text DEFAULT NULL,
  `event_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpmailsmtp_tasks_meta`
--

CREATE TABLE `wp_wpmailsmtp_tasks_meta` (
  `id` bigint(20) NOT NULL,
  `action` varchar(255) NOT NULL,
  `data` longtext NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsal_metadata`
--

CREATE TABLE `wp_wsal_metadata` (
  `id` bigint(20) NOT NULL,
  `occurrence_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsal_occurrences`
--

CREATE TABLE `wp_wsal_occurrences` (
  `id` bigint(20) NOT NULL,
  `site_id` bigint(20) NOT NULL,
  `alert_id` bigint(20) NOT NULL,
  `created_on` double NOT NULL,
  `is_read` bit(1) NOT NULL,
  `is_migrated` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsal_options`
--

CREATE TABLE `wp_wsal_options` (
  `id` bigint(20) NOT NULL,
  `option_name` varchar(100) NOT NULL,
  `option_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wsluserscontacts`
--

CREATE TABLE `wp_wsluserscontacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `full_name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `profile_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `photo_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wslusersprofiles`
--

CREATE TABLE `wp_wslusersprofiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `object_sha` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `profileurl` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `websiteurl` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `photourl` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `displayname` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `firstname` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `lastname` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `language` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `age` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `birthday` int(11) NOT NULL,
  `birthmonth` int(11) NOT NULL,
  `birthyear` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `emailverified` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(75) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `country` varchar(75) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `region` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `zip` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_indexable`
--

CREATE TABLE `wp_yoast_indexable` (
  `id` int(11) UNSIGNED NOT NULL,
  `permalink` longtext DEFAULT NULL,
  `permalink_hash` varchar(40) DEFAULT NULL,
  `object_id` bigint(20) DEFAULT NULL,
  `object_type` varchar(32) NOT NULL,
  `object_sub_type` varchar(32) DEFAULT NULL,
  `author_id` bigint(20) DEFAULT NULL,
  `post_parent` bigint(20) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `breadcrumb_title` text DEFAULT NULL,
  `post_status` varchar(20) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `is_protected` tinyint(1) DEFAULT 0,
  `has_public_posts` tinyint(1) DEFAULT NULL,
  `number_of_pages` int(11) UNSIGNED DEFAULT NULL,
  `canonical` longtext DEFAULT NULL,
  `primary_focus_keyword` varchar(191) DEFAULT NULL,
  `primary_focus_keyword_score` int(3) DEFAULT NULL,
  `readability_score` int(3) DEFAULT NULL,
  `is_cornerstone` tinyint(1) DEFAULT 0,
  `is_robots_noindex` tinyint(1) DEFAULT 0,
  `is_robots_nofollow` tinyint(1) DEFAULT 0,
  `is_robots_noarchive` tinyint(1) DEFAULT 0,
  `is_robots_noimageindex` tinyint(1) DEFAULT 0,
  `is_robots_nosnippet` tinyint(1) DEFAULT 0,
  `twitter_title` text DEFAULT NULL,
  `twitter_image` longtext DEFAULT NULL,
  `twitter_description` longtext DEFAULT NULL,
  `twitter_image_id` varchar(191) DEFAULT NULL,
  `twitter_image_source` text DEFAULT NULL,
  `open_graph_title` text DEFAULT NULL,
  `open_graph_description` longtext DEFAULT NULL,
  `open_graph_image` longtext DEFAULT NULL,
  `open_graph_image_id` varchar(191) DEFAULT NULL,
  `open_graph_image_source` text DEFAULT NULL,
  `open_graph_image_meta` mediumtext DEFAULT NULL,
  `link_count` int(11) DEFAULT NULL,
  `incoming_link_count` int(11) DEFAULT NULL,
  `prominent_words_version` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blog_id` bigint(20) NOT NULL DEFAULT 1,
  `language` varchar(32) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL,
  `schema_page_type` varchar(64) DEFAULT NULL,
  `schema_article_type` varchar(64) DEFAULT NULL,
  `has_ancestors` tinyint(1) DEFAULT 0,
  `estimated_reading_time_minutes` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT 1,
  `object_last_modified` datetime DEFAULT NULL,
  `object_published_at` datetime DEFAULT NULL,
  `inclusive_language_score` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_indexable_hierarchy`
--

CREATE TABLE `wp_yoast_indexable_hierarchy` (
  `indexable_id` int(11) UNSIGNED NOT NULL,
  `ancestor_id` int(11) UNSIGNED NOT NULL,
  `depth` int(11) UNSIGNED DEFAULT NULL,
  `blog_id` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_migrations`
--

CREATE TABLE `wp_yoast_migrations` (
  `id` int(11) UNSIGNED NOT NULL,
  `version` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_primary_term`
--

CREATE TABLE `wp_yoast_primary_term` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `term_id` bigint(20) DEFAULT NULL,
  `taxonomy` varchar(32) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blog_id` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_prominent_words`
--

CREATE TABLE `wp_yoast_prominent_words` (
  `id` int(11) UNSIGNED NOT NULL,
  `stem` varchar(191) DEFAULT NULL,
  `indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `weight` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_yoast_seo_links`
--

CREATE TABLE `wp_yoast_seo_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(8) DEFAULT NULL,
  `indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `target_indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `height` int(11) UNSIGNED DEFAULT NULL,
  `width` int(11) UNSIGNED DEFAULT NULL,
  `size` int(11) UNSIGNED DEFAULT NULL,
  `language` varchar(32) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_actionscheduler_actions`
--
ALTER TABLE `wp_actionscheduler_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `hook` (`hook`),
  ADD KEY `status` (`status`),
  ADD KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  ADD KEY `args` (`args`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `last_attempt_gmt` (`last_attempt_gmt`),
  ADD KEY `claim_id_status_scheduled_date_gmt` (`claim_id`,`status`,`scheduled_date_gmt`),
  ADD KEY `hook_status_scheduled_date_gmt` (`hook`(163),`status`,`scheduled_date_gmt`),
  ADD KEY `status_scheduled_date_gmt` (`status`,`scheduled_date_gmt`),
  ADD KEY `claim_id_status_priority_scheduled_date_gmt` (`claim_id`,`status`,`priority`,`scheduled_date_gmt`),
  ADD KEY `status_last_attempt_gmt` (`status`,`last_attempt_gmt`),
  ADD KEY `status_claim_id` (`status`,`claim_id`);

--
-- Indexes for table `wp_actionscheduler_claims`
--
ALTER TABLE `wp_actionscheduler_claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `date_created_gmt` (`date_created_gmt`);

--
-- Indexes for table `wp_actionscheduler_groups`
--
ALTER TABLE `wp_actionscheduler_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `slug` (`slug`(191));

--
-- Indexes for table `wp_actionscheduler_logs`
--
ALTER TABLE `wp_actionscheduler_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `log_date_gmt` (`log_date_gmt`);

--
-- Indexes for table `wp_apsl_users_social_profile_details`
--
ALTER TABLE `wp_apsl_users_social_profile_details`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider_name` (`provider_name`);

--
-- Indexes for table `wp_as3cf_items`
--
ALTER TABLE `wp_as3cf_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uidx_source` (`source_type`,`source_id`),
  ADD UNIQUE KEY `uidx_is_verified_originator` (`is_verified`,`originator`,`id`),
  ADD UNIQUE KEY `uidx_path` (`path`(190),`id`),
  ADD UNIQUE KEY `uidx_original_path` (`original_path`(190),`id`),
  ADD UNIQUE KEY `uidx_source_path` (`source_path`(190),`id`),
  ADD UNIQUE KEY `uidx_original_source_path` (`original_source_path`(190),`id`),
  ADD UNIQUE KEY `uidx_provider_bucket` (`provider`,`bucket`(190),`id`);

--
-- Indexes for table `wp_cky_banners`
--
ALTER TABLE `wp_cky_banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `wp_cky_cookies`
--
ALTER TABLE `wp_cky_cookies`
  ADD PRIMARY KEY (`cookie_id`);

--
-- Indexes for table `wp_cky_cookie_categories`
--
ALTER TABLE `wp_cky_cookie_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_comments`
--
ALTER TABLE `wp_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10)),
  ADD KEY `woo_idx_comment_type` (`comment_type`),
  ADD KEY `woo_idx_comment_date_type` (`comment_date_gmt`,`comment_type`,`comment_approved`,`comment_post_ID`),
  ADD KEY `woo_idx_comment_approved_type` (`comment_approved`,`comment_type`,`comment_post_ID`);

--
-- Indexes for table `wp_email_log`
--
ALTER TABLE `wp_email_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_ewwwio_images`
--
ALTER TABLE `wp_ewwwio_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachment_info` (`gallery`(3),`attachment_id`),
  ADD KEY `path` (`path`(191));

--
-- Indexes for table `wp_ewwwio_queue`
--
ALTER TABLE `wp_ewwwio_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachment_info` (`gallery`(3),`attachment_id`);

--
-- Indexes for table `wp_ht_tmdb_queue`
--
ALTER TABLE `wp_ht_tmdb_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tmdb_id` (`tmdb_id`),
  ADD KEY `status` (`status`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `wp_imagify_files`
--
ALTER TABLE `wp_imagify_files`
  ADD PRIMARY KEY (`file_id`),
  ADD UNIQUE KEY `path` (`path`),
  ADD KEY `folder_id` (`folder_id`),
  ADD KEY `optimization_level` (`optimization_level`),
  ADD KEY `status` (`status`),
  ADD KEY `modified` (`modified`);

--
-- Indexes for table `wp_imagify_folders`
--
ALTER TABLE `wp_imagify_folders`
  ADD PRIMARY KEY (`folder_id`),
  ADD UNIQUE KEY `path` (`path`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `wp_ins_view_history`
--
ALTER TABLE `wp_ins_view_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_post` (`user_id`,`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `last_viewed_at` (`last_viewed_at`);

--
-- Indexes for table `wp_ins_watchlists`
--
ALTER TABLE `wp_ins_watchlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_slug` (`user_id`,`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `visibility` (`visibility`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `wp_ins_watchlist_items`
--
ALTER TABLE `wp_ins_watchlist_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `list_post` (`list_id`,`post_id`),
  ADD KEY `list_id` (`list_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `wp_kirki_cm_reference`
--
ALTER TABLE `wp_kirki_cm_reference`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `ref_post_id` (`ref_post_id`);

--
-- Indexes for table `wp_kirki_collaborations`
--
ALTER TABLE `wp_kirki_collaborations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_kirki_collaborations_connected`
--
ALTER TABLE `wp_kirki_collaborations_connected`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_kirki_collaborations_sent`
--
ALTER TABLE `wp_kirki_collaborations_sent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_kirki_comments`
--
ALTER TABLE `wp_kirki_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `wp_kirki_comments_seen`
--
ALTER TABLE `wp_kirki_comments_seen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_comment_unique` (`user_id`,`comment_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `wp_kirki_forms`
--
ALTER TABLE `wp_kirki_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_kirki_forms_data`
--
ALTER TABLE `wp_kirki_forms_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `wp_license_envato_userlist`
--
ALTER TABLE `wp_license_envato_userlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_links`
--
ALTER TABLE `wp_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Indexes for table `wp_litespeed_avatar`
--
ALTER TABLE `wp_litespeed_avatar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `md5` (`md5`),
  ADD KEY `dateline` (`dateline`);

--
-- Indexes for table `wp_litespeed_crawler_blacklist`
--
ALTER TABLE `wp_litespeed_crawler_blacklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`(191)),
  ADD KEY `res` (`res`);

--
-- Indexes for table `wp_litespeed_img_optming`
--
ALTER TABLE `wp_litespeed_img_optming`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `optm_status` (`optm_status`),
  ADD KEY `src` (`src`(191));

--
-- Indexes for table `wp_litespeed_url`
--
ALTER TABLE `wp_litespeed_url`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`(191)),
  ADD KEY `cache_tags` (`cache_tags`(191));

--
-- Indexes for table `wp_litespeed_url_file`
--
ALTER TABLE `wp_litespeed_url_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filename` (`filename`),
  ADD KEY `type` (`type`),
  ADD KEY `url_id_2` (`url_id`,`vary`,`type`),
  ADD KEY `filename_2` (`filename`,`expired`),
  ADD KEY `url_id` (`url_id`,`expired`);

--
-- Indexes for table `wp_loginizer_logs`
--
ALTER TABLE `wp_loginizer_logs`
  ADD UNIQUE KEY `ip` (`ip`);

--
-- Indexes for table `wp_mo_openid_linked_user`
--
ALTER TABLE `wp_mo_openid_linked_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_options`
--
ALTER TABLE `wp_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Indexes for table `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_posts`
--
ALTER TABLE `wp_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`),
  ADD KEY `type_status_author` (`post_type`,`post_status`,`post_author`);

--
-- Indexes for table `wp_post_smtp_logmeta`
--
ALTER TABLE `wp_post_smtp_logmeta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_post_smtp_logs`
--
ALTER TABLE `wp_post_smtp_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_rank_math_404_logs`
--
ALTER TABLE `wp_rank_math_404_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uri` (`uri`(191));

--
-- Indexes for table `wp_rank_math_analytics_adsense`
--
ALTER TABLE `wp_rank_math_analytics_adsense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_rank_math_analytics_ga`
--
ALTER TABLE `wp_rank_math_analytics_ga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_object_analytics` (`page`(190));

--
-- Indexes for table `wp_rank_math_analytics_gsc`
--
ALTER TABLE `wp_rank_math_analytics_gsc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_query` (`query`(190)),
  ADD KEY `analytics_page` (`page`(190)),
  ADD KEY `clicks` (`clicks`),
  ADD KEY `rank_position` (`position`);

--
-- Indexes for table `wp_rank_math_analytics_inspections`
--
ALTER TABLE `wp_rank_math_analytics_inspections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_object_page` (`page`(190)),
  ADD KEY `created` (`created`),
  ADD KEY `index_verdict` (`index_verdict`),
  ADD KEY `page_fetch_state` (`page_fetch_state`),
  ADD KEY `robots_txt_state` (`robots_txt_state`),
  ADD KEY `rich_results_verdict` (`rich_results_verdict`);

--
-- Indexes for table `wp_rank_math_analytics_keyword_manager`
--
ALTER TABLE `wp_rank_math_analytics_keyword_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_rank_math_analytics_objects`
--
ALTER TABLE `wp_rank_math_analytics_objects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_object_page` (`page`(190));

--
-- Indexes for table `wp_rank_math_internal_links`
--
ALTER TABLE `wp_rank_math_internal_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link_direction` (`post_id`,`type`),
  ADD KEY `target_post_id` (`target_post_id`),
  ADD KEY `idx_url_hash` (`url_hash`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_target_post_id` (`target_post_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_anchor_type` (`anchor_type`),
  ADD KEY `idx_target_blank` (`target_blank`),
  ADD KEY `idx_is_nofollow` (`is_nofollow`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_post_type` (`post_id`,`type`),
  ADD KEY `idx_type_nofollow` (`type`,`is_nofollow`),
  ADD KEY `idx_post_type_created` (`post_id`,`type`,`created_at`);
ALTER TABLE `wp_rank_math_internal_links` ADD FULLTEXT KEY `idx_search` (`anchor_text`,`url`);

--
-- Indexes for table `wp_rank_math_internal_meta`
--
ALTER TABLE `wp_rank_math_internal_meta`
  ADD PRIMARY KEY (`object_id`);

--
-- Indexes for table `wp_rank_math_link_genius_audit`
--
ALTER TABLE `wp_rank_math_link_genius_audit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_link_id` (`link_id`),
  ADD KEY `idx_url_hash` (`url_hash`),
  ADD KEY `idx_status_code` (`http_status_code`),
  ADD KEY `idx_status_category` (`status_category`),
  ADD KEY `idx_last_checked` (`last_checked_at`),
  ADD KEY `idx_robots_blocked` (`robots_blocked`),
  ADD KEY `idx_marked_safe` (`is_marked_safe`);

--
-- Indexes for table `wp_rank_math_link_genius_history`
--
ALTER TABLE `wp_rank_math_link_genius_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_batch_id` (`batch_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_source_type` (`source_type`),
  ADD KEY `idx_keyword_map_id` (`keyword_map_id`);

--
-- Indexes for table `wp_rank_math_link_genius_maps`
--
ALTER TABLE `wp_rank_math_link_genius_maps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`(191)),
  ADD KEY `idx_is_enabled` (`is_enabled`),
  ADD KEY `idx_auto_link_on_publish` (`auto_link_on_publish`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `wp_rank_math_link_genius_map_variations`
--
ALTER TABLE `wp_rank_math_link_genius_map_variations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_unique_variation_per_map` (`keyword_map_id`,`variation`(191)),
  ADD KEY `idx_keyword_map_id` (`keyword_map_id`),
  ADD KEY `idx_variation` (`variation`(191));

--
-- Indexes for table `wp_rank_math_link_genius_snapshots`
--
ALTER TABLE `wp_rank_math_link_genius_snapshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_batch_id` (`batch_id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_batch_post` (`batch_id`,`post_id`);

--
-- Indexes for table `wp_rank_math_redirections`
--
ALTER TABLE `wp_rank_math_redirections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `wp_rank_math_redirections_cache`
--
ALTER TABLE `wp_rank_math_redirections_cache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `redirection_id` (`redirection_id`);

--
-- Indexes for table `wp_revslider_css`
--
ALTER TABLE `wp_revslider_css`
  ADD PRIMARY KEY (`id`),
  ADD KEY `handle_index` (`handle`(64));

--
-- Indexes for table `wp_revslider_layer_animations`
--
ALTER TABLE `wp_revslider_layer_animations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_revslider_navigations`
--
ALTER TABLE `wp_revslider_navigations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_revslider_sliders`
--
ALTER TABLE `wp_revslider_sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_index` (`type`(8));

--
-- Indexes for table `wp_revslider_sliders7`
--
ALTER TABLE `wp_revslider_sliders7`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_index` (`type`(8));

--
-- Indexes for table `wp_revslider_slides`
--
ALTER TABLE `wp_revslider_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slider_id_index` (`slider_id`);

--
-- Indexes for table `wp_revslider_slides7`
--
ALTER TABLE `wp_revslider_slides7`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slider_id_index` (`slider_id`);

--
-- Indexes for table `wp_revslider_static_slides`
--
ALTER TABLE `wp_revslider_static_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slider_id_index` (`slider_id`);

--
-- Indexes for table `wp_snippets`
--
ALTER TABLE `wp_snippets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scope` (`scope`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `wp_social_users`
--
ALTER TABLE `wp_social_users`
  ADD PRIMARY KEY (`social_users_id`),
  ADD KEY `ID` (`ID`,`type`),
  ADD KEY `identifier` (`identifier`);

--
-- Indexes for table `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_terms`
--
ALTER TABLE `wp_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Indexes for table `wp_term_relationships`
--
ALTER TABLE `wp_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Indexes for table `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Indexes for table `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_users`
--
ALTER TABLE `wp_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `wp_wbca_department`
--
ALTER TABLE `wp_wbca_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wbca_message`
--
ALTER TABLE `wp_wbca_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wbca_search_document`
--
ALTER TABLE `wp_wbca_search_document`
  ADD PRIMARY KEY (`DOCUMENT_ID`);

--
-- Indexes for table `wp_wbca_search_index`
--
ALTER TABLE `wp_wbca_search_index`
  ADD PRIMARY KEY (`DOCUMENT_ID`,`OFSET`),
  ADD KEY `TERM_ID` (`TERM_ID`);

--
-- Indexes for table `wp_wbca_search_term`
--
ALTER TABLE `wp_wbca_search_term`
  ADD PRIMARY KEY (`TERM_ID`),
  ADD UNIQUE KEY `TERM_VALUE` (`TERM_VALUE`);

--
-- Indexes for table `wp_wbca_user_department`
--
ALTER TABLE `wp_wbca_user_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `wp_wbca_user_personal_info`
--
ALTER TABLE `wp_wbca_user_personal_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `wp_wc_admin_notes`
--
ALTER TABLE `wp_wc_admin_notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `wp_wc_admin_note_actions`
--
ALTER TABLE `wp_wc_admin_note_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indexes for table `wp_wc_category_lookup`
--
ALTER TABLE `wp_wc_category_lookup`
  ADD PRIMARY KEY (`category_tree_id`,`category_id`);

--
-- Indexes for table `wp_wc_customer_lookup`
--
ALTER TABLE `wp_wc_customer_lookup`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `wp_wc_download_log`
--
ALTER TABLE `wp_wc_download_log`
  ADD PRIMARY KEY (`download_log_id`),
  ADD KEY `permission_id` (`permission_id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `wp_wc_orders`
--
ALTER TABLE `wp_wc_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `date_created` (`date_created_gmt`),
  ADD KEY `customer_id_billing_email` (`customer_id`,`billing_email`(171)),
  ADD KEY `customer_id_status` (`customer_id`,`status`),
  ADD KEY `billing_email` (`billing_email`(191)),
  ADD KEY `type_status_date` (`type`,`status`,`date_created_gmt`),
  ADD KEY `parent_order_id` (`parent_order_id`),
  ADD KEY `date_updated` (`date_updated_gmt`);

--
-- Indexes for table `wp_wc_orders_meta`
--
ALTER TABLE `wp_wc_orders_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meta_key_value` (`meta_key`(100),`meta_value`(82)),
  ADD KEY `order_id_meta_key_meta_value` (`order_id`,`meta_key`(100),`meta_value`(82));

--
-- Indexes for table `wp_wc_order_addresses`
--
ALTER TABLE `wp_wc_order_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `address_type_order_id` (`address_type`,`order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `email` (`email`(191)),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `wp_wc_order_coupon_lookup`
--
ALTER TABLE `wp_wc_order_coupon_lookup`
  ADD PRIMARY KEY (`order_id`,`coupon_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `date_created` (`date_created`);

--
-- Indexes for table `wp_wc_order_operational_data`
--
ALTER TABLE `wp_wc_order_operational_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `order_key` (`order_key`);

--
-- Indexes for table `wp_wc_order_product_lookup`
--
ALTER TABLE `wp_wc_order_product_lookup`
  ADD PRIMARY KEY (`order_item_id`,`order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `date_created` (`date_created`),
  ADD KEY `customer_product_date` (`customer_id`,`product_id`,`date_created`);

--
-- Indexes for table `wp_wc_order_stats`
--
ALTER TABLE `wp_wc_order_stats`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `date_created` (`date_created`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `status` (`status`),
  ADD KEY `idx_date_paid_status_parent` (`date_paid`,`status`,`parent_id`);

--
-- Indexes for table `wp_wc_order_tax_lookup`
--
ALTER TABLE `wp_wc_order_tax_lookup`
  ADD PRIMARY KEY (`order_id`,`tax_rate_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`),
  ADD KEY `date_created` (`date_created`);

--
-- Indexes for table `wp_wc_product_attributes_lookup`
--
ALTER TABLE `wp_wc_product_attributes_lookup`
  ADD PRIMARY KEY (`product_or_parent_id`,`term_id`,`product_id`,`taxonomy`),
  ADD KEY `is_variation_attribute_term_id` (`is_variation_attribute`,`term_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `wp_wc_product_download_directories`
--
ALTER TABLE `wp_wc_product_download_directories`
  ADD PRIMARY KEY (`url_id`),
  ADD KEY `url` (`url`(191));

--
-- Indexes for table `wp_wc_product_meta_lookup`
--
ALTER TABLE `wp_wc_product_meta_lookup`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `virtual` (`virtual`),
  ADD KEY `downloadable` (`downloadable`),
  ADD KEY `stock_status` (`stock_status`),
  ADD KEY `stock_quantity` (`stock_quantity`),
  ADD KEY `onsale` (`onsale`),
  ADD KEY `min_max_price` (`min_price`,`max_price`),
  ADD KEY `sku` (`sku`(50));

--
-- Indexes for table `wp_wc_rate_limits`
--
ALTER TABLE `wp_wc_rate_limits`
  ADD PRIMARY KEY (`rate_limit_id`),
  ADD UNIQUE KEY `rate_limit_key` (`rate_limit_key`(191));

--
-- Indexes for table `wp_wc_reserved_stock`
--
ALTER TABLE `wp_wc_reserved_stock`
  ADD PRIMARY KEY (`order_id`,`product_id`);

--
-- Indexes for table `wp_wc_tax_rate_classes`
--
ALTER TABLE `wp_wc_tax_rate_classes`
  ADD PRIMARY KEY (`tax_rate_class_id`),
  ADD UNIQUE KEY `slug` (`slug`(191));

--
-- Indexes for table `wp_wc_webhooks`
--
ALTER TABLE `wp_wc_webhooks`
  ADD PRIMARY KEY (`webhook_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wp_woocommerce_api_keys`
--
ALTER TABLE `wp_woocommerce_api_keys`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `consumer_key` (`consumer_key`),
  ADD KEY `consumer_secret` (`consumer_secret`);

--
-- Indexes for table `wp_woocommerce_attribute_taxonomies`
--
ALTER TABLE `wp_woocommerce_attribute_taxonomies`
  ADD PRIMARY KEY (`attribute_id`),
  ADD KEY `attribute_name` (`attribute_name`(20));

--
-- Indexes for table `wp_woocommerce_downloadable_product_permissions`
--
ALTER TABLE `wp_woocommerce_downloadable_product_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(16),`download_id`),
  ADD KEY `download_order_product` (`download_id`,`order_id`,`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_order_remaining_expires` (`user_id`,`order_id`,`downloads_remaining`,`access_expires`),
  ADD KEY `idx_user_email` (`user_email`(100));

--
-- Indexes for table `wp_woocommerce_log`
--
ALTER TABLE `wp_woocommerce_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `wp_woocommerce_order_itemmeta`
--
ALTER TABLE `wp_woocommerce_order_itemmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `meta_key` (`meta_key`(32));

--
-- Indexes for table `wp_woocommerce_order_items`
--
ALTER TABLE `wp_woocommerce_order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `wp_woocommerce_payment_tokenmeta`
--
ALTER TABLE `wp_woocommerce_payment_tokenmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `payment_token_id` (`payment_token_id`),
  ADD KEY `meta_key` (`meta_key`(32));

--
-- Indexes for table `wp_woocommerce_payment_tokens`
--
ALTER TABLE `wp_woocommerce_payment_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wp_woocommerce_sessions`
--
ALTER TABLE `wp_woocommerce_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_key` (`session_key`),
  ADD KEY `session_expiry` (`session_expiry`);

--
-- Indexes for table `wp_woocommerce_shipping_zones`
--
ALTER TABLE `wp_woocommerce_shipping_zones`
  ADD PRIMARY KEY (`zone_id`);

--
-- Indexes for table `wp_woocommerce_shipping_zone_locations`
--
ALTER TABLE `wp_woocommerce_shipping_zone_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `location_type_code` (`location_type`(10),`location_code`(20));

--
-- Indexes for table `wp_woocommerce_shipping_zone_methods`
--
ALTER TABLE `wp_woocommerce_shipping_zone_methods`
  ADD PRIMARY KEY (`instance_id`);

--
-- Indexes for table `wp_woocommerce_tax_rates`
--
ALTER TABLE `wp_woocommerce_tax_rates`
  ADD PRIMARY KEY (`tax_rate_id`),
  ADD KEY `tax_rate_country` (`tax_rate_country`),
  ADD KEY `tax_rate_state` (`tax_rate_state`(2)),
  ADD KEY `tax_rate_class` (`tax_rate_class`(10)),
  ADD KEY `tax_rate_priority` (`tax_rate_priority`);

--
-- Indexes for table `wp_woocommerce_tax_rate_locations`
--
ALTER TABLE `wp_woocommerce_tax_rate_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`),
  ADD KEY `location_type_code` (`location_type`(10),`location_code`(20));

--
-- Indexes for table `wp_wpaicg_chatlogs`
--
ALTER TABLE `wp_wpaicg_chatlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_chattokens`
--
ALTER TABLE `wp_wpaicg_chattokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_formtokens`
--
ALTER TABLE `wp_wpaicg_formtokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_form_feedback`
--
ALTER TABLE `wp_wpaicg_form_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_form_logs`
--
ALTER TABLE `wp_wpaicg_form_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_imagetokens`
--
ALTER TABLE `wp_wpaicg_imagetokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_image_logs`
--
ALTER TABLE `wp_wpaicg_image_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_promptbase_logs`
--
ALTER TABLE `wp_wpaicg_promptbase_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_prompttokens`
--
ALTER TABLE `wp_wpaicg_prompttokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_prompt_feedback`
--
ALTER TABLE `wp_wpaicg_prompt_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpaicg_rsslogs`
--
ALTER TABLE `wp_wpaicg_rsslogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wp_wpaicg_rsslogs_url_index` (`url`),
  ADD KEY `wp_wpaicg_rsslogs_title_index` (`title`);

--
-- Indexes for table `wp_wpaicg_token_logs`
--
ALTER TABLE `wp_wpaicg_token_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wp_wpaicg_token_logs_user_id_index` (`user_id`);

--
-- Indexes for table `wp_wpbot_chat_report`
--
ALTER TABLE `wp_wpbot_chat_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_Conversation`
--
ALTER TABLE `wp_wpbot_Conversation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_extented_synonyms`
--
ALTER TABLE `wp_wpbot_extented_synonyms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_failed_response`
--
ALTER TABLE `wp_wpbot_failed_response`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_response`
--
ALTER TABLE `wp_wpbot_response`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `wp_wpbot_response` ADD FULLTEXT KEY `query` (`query`,`keyword`,`response`);

--
-- Indexes for table `wp_wpbot_response_category`
--
ALTER TABLE `wp_wpbot_response_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_response_entities`
--
ALTER TABLE `wp_wpbot_response_entities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_sessions`
--
ALTER TABLE `wp_wpbot_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_subscription`
--
ALTER TABLE `wp_wpbot_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpbot_user`
--
ALTER TABLE `wp_wpbot_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpfm_backup`
--
ALTER TABLE `wp_wpfm_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpil_ai_batch_log`
--
ALTER TABLE `wp_wpil_ai_batch_log`
  ADD PRIMARY KEY (`log_index`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `wp_wpil_ai_completed_batch_log`
--
ALTER TABLE `wp_wpil_ai_completed_batch_log`
  ADD PRIMARY KEY (`log_index`);

--
-- Indexes for table `wp_wpil_ai_embedding_calculation_data`
--
ALTER TABLE `wp_wpil_ai_embedding_calculation_data`
  ADD PRIMARY KEY (`embed_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `calc_index` (`calc_index`);

--
-- Indexes for table `wp_wpil_ai_embedding_calculation_data_v2`
--
ALTER TABLE `wp_wpil_ai_embedding_calculation_data_v2`
  ADD PRIMARY KEY (`embed_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `target_post_type` (`target_post_type`),
  ADD KEY `starting_id` (`starting_id`),
  ADD KEY `ending_id` (`ending_id`),
  ADD KEY `calc_index` (`calc_index`),
  ADD KEY `target_range` (`target_post_type`,`starting_id`,`ending_id`),
  ADD KEY `source_lookup` (`post_id`,`post_type`);

--
-- Indexes for table `wp_wpil_ai_embedding_data`
--
ALTER TABLE `wp_wpil_ai_embedding_data`
  ADD PRIMARY KEY (`embed_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`);

--
-- Indexes for table `wp_wpil_ai_embedding_phrase_calculation_data`
--
ALTER TABLE `wp_wpil_ai_embedding_phrase_calculation_data`
  ADD PRIMARY KEY (`embed_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`);

--
-- Indexes for table `wp_wpil_ai_embedding_phrase_data`
--
ALTER TABLE `wp_wpil_ai_embedding_phrase_data`
  ADD PRIMARY KEY (`embed_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`);

--
-- Indexes for table `wp_wpil_ai_error_log`
--
ALTER TABLE `wp_wpil_ai_error_log`
  ADD PRIMARY KEY (`log_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `wp_wpil_ai_keyword_data`
--
ALTER TABLE `wp_wpil_ai_keyword_data`
  ADD PRIMARY KEY (`ai_keyword_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`);

--
-- Indexes for table `wp_wpil_ai_linking`
--
ALTER TABLE `wp_wpil_ai_linking`
  ADD PRIMARY KEY (`ai_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `sentence_id` (`sentence_id`),
  ADD KEY `process_key` (`process_key`);

--
-- Indexes for table `wp_wpil_ai_post_data`
--
ALTER TABLE `wp_wpil_ai_post_data`
  ADD PRIMARY KEY (`ai_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`);

--
-- Indexes for table `wp_wpil_ai_processed_sentences`
--
ALTER TABLE `wp_wpil_ai_processed_sentences`
  ADD PRIMARY KEY (`sentence_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `sentence_id` (`sentence_id`);

--
-- Indexes for table `wp_wpil_ai_product_data`
--
ALTER TABLE `wp_wpil_ai_product_data`
  ADD PRIMARY KEY (`ai_product_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`);

--
-- Indexes for table `wp_wpil_ai_suggested_anchors`
--
ALTER TABLE `wp_wpil_ai_suggested_anchors`
  ADD PRIMARY KEY (`suggestion_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `sentence_post_id` (`sentence_post_id`);

--
-- Indexes for table `wp_wpil_ai_system_error_log`
--
ALTER TABLE `wp_wpil_ai_system_error_log`
  ADD PRIMARY KEY (`log_index`);

--
-- Indexes for table `wp_wpil_ai_token_use_data`
--
ALTER TABLE `wp_wpil_ai_token_use_data`
  ADD PRIMARY KEY (`token_index`),
  ADD UNIQUE KEY `credit_transaction_ref` (`transaction_type`,`transaction_ref`),
  ADD KEY `query_id` (`query_id`),
  ADD KEY `transaction_type` (`transaction_type`),
  ADD KEY `transaction_ref` (`transaction_ref`),
  ADD KEY `process_key_id_time` (`process_key`,`process_id`,`process_time`);

--
-- Indexes for table `wp_wpil_broken_links`
--
ALTER TABLE `wp_wpil_broken_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`(512));

--
-- Indexes for table `wp_wpil_click_data`
--
ALTER TABLE `wp_wpil_click_data`
  ADD PRIMARY KEY (`click_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `link_url` (`link_url`(191)),
  ADD KEY `user_ip` (`user_ip`(48)),
  ADD KEY `tracking_id` (`tracking_id`);

--
-- Indexes for table `wp_wpil_ignore_links`
--
ALTER TABLE `wp_wpil_ignore_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`(512));

--
-- Indexes for table `wp_wpil_keywords`
--
ALTER TABLE `wp_wpil_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpil_keyword_links`
--
ALTER TABLE `wp_wpil_keyword_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyword_id` (`keyword_id`);

--
-- Indexes for table `wp_wpil_keyword_select_links`
--
ALTER TABLE `wp_wpil_keyword_select_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyword_id` (`keyword_id`);

--
-- Indexes for table `wp_wpil_link_mapping`
--
ALTER TABLE `wp_wpil_link_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `process_key` (`process_key`);

--
-- Indexes for table `wp_wpil_related_posts`
--
ALTER TABLE `wp_wpil_related_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `wp_wpil_relation_mapping`
--
ALTER TABLE `wp_wpil_relation_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `process_key_scope_post_id_type` (`process_key`,`work_scope`,`post_id`,`post_type`),
  ADD KEY `process_key_item_processed` (`process_key`,`item_processed`),
  ADD KEY `process_key_scope_queue` (`process_key`,`work_scope`,`item_processed`,`ai_processed`,`is_pillar`,`id`);

--
-- Indexes for table `wp_wpil_report_links`
--
ALTER TABLE `wp_wpil_report_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `clean_url` (`clean_url`(500)),
  ADD KEY `host` (`host`(64)),
  ADD KEY `tracking_id` (`tracking_id`);

--
-- Indexes for table `wp_wpil_search_console_data`
--
ALTER TABLE `wp_wpil_search_console_data`
  ADD PRIMARY KEY (`gsc_index`),
  ADD KEY `page_url` (`page_url`(255)),
  ADD KEY `keywords` (`keywords`(255));

--
-- Indexes for table `wp_wpil_sitemaps`
--
ALTER TABLE `wp_wpil_sitemaps`
  ADD PRIMARY KEY (`sitemap_id`);

--
-- Indexes for table `wp_wpil_snoozed_links`
--
ALTER TABLE `wp_wpil_snoozed_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `normalized_url` (`normalized_url`),
  ADD KEY `snoozed_until` (`snoozed_until`);

--
-- Indexes for table `wp_wpil_target_keyword_data`
--
ALTER TABLE `wp_wpil_target_keyword_data`
  ADD PRIMARY KEY (`keyword_index`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `post_type` (`post_type`),
  ADD KEY `keyword_type` (`keyword_type`);

--
-- Indexes for table `wp_wpil_telemetry_log`
--
ALTER TABLE `wp_wpil_telemetry_log`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `event_name` (`event_name`);

--
-- Indexes for table `wp_wpil_tracked_link_ids`
--
ALTER TABLE `wp_wpil_tracked_link_ids`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `wp_wpil_urls`
--
ALTER TABLE `wp_wpil_urls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpil_url_links`
--
ALTER TABLE `wp_wpil_url_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpmailsmtp_debug_events`
--
ALTER TABLE `wp_wpmailsmtp_debug_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wpmailsmtp_tasks_meta`
--
ALTER TABLE `wp_wpmailsmtp_tasks_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsal_metadata`
--
ALTER TABLE `wp_wsal_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `occurrence_name` (`occurrence_id`,`name`);

--
-- Indexes for table `wp_wsal_occurrences`
--
ALTER TABLE `wp_wsal_occurrences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_alert_created` (`site_id`,`alert_id`,`created_on`);

--
-- Indexes for table `wp_wsal_options`
--
ALTER TABLE `wp_wsal_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_wsluserscontacts`
--
ALTER TABLE `wp_wsluserscontacts`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wp_wslusersprofiles`
--
ALTER TABLE `wp_wslusersprofiles`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider` (`provider`);

--
-- Indexes for table `wp_yoast_indexable`
--
ALTER TABLE `wp_yoast_indexable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object_type_and_sub_type` (`object_type`,`object_sub_type`),
  ADD KEY `object_id_and_type` (`object_id`,`object_type`),
  ADD KEY `permalink_hash_and_object_type` (`permalink_hash`,`object_type`),
  ADD KEY `subpages` (`post_parent`,`object_type`,`post_status`,`object_id`),
  ADD KEY `prominent_words` (`prominent_words_version`,`object_type`,`object_sub_type`,`post_status`),
  ADD KEY `published_sitemap_index` (`object_published_at`,`is_robots_noindex`,`object_type`,`object_sub_type`);

--
-- Indexes for table `wp_yoast_indexable_hierarchy`
--
ALTER TABLE `wp_yoast_indexable_hierarchy`
  ADD PRIMARY KEY (`indexable_id`,`ancestor_id`),
  ADD KEY `indexable_id` (`indexable_id`),
  ADD KEY `ancestor_id` (`ancestor_id`),
  ADD KEY `depth` (`depth`);

--
-- Indexes for table `wp_yoast_migrations`
--
ALTER TABLE `wp_yoast_migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wp_yoast_migrations_version` (`version`);

--
-- Indexes for table `wp_yoast_primary_term`
--
ALTER TABLE `wp_yoast_primary_term`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_taxonomy` (`post_id`,`taxonomy`),
  ADD KEY `post_term` (`post_id`,`term_id`);

--
-- Indexes for table `wp_yoast_prominent_words`
--
ALTER TABLE `wp_yoast_prominent_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stem` (`stem`),
  ADD KEY `indexable_id` (`indexable_id`),
  ADD KEY `indexable_id_and_stem` (`indexable_id`,`stem`);

--
-- Indexes for table `wp_yoast_seo_links`
--
ALTER TABLE `wp_yoast_seo_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link_direction` (`post_id`,`type`),
  ADD KEY `indexable_link_direction` (`indexable_id`,`type`),
  ADD KEY `url_index` (`url`),
  ADD KEY `target_indexable_id_index` (`target_indexable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_actionscheduler_actions`
--
ALTER TABLE `wp_actionscheduler_actions`
  MODIFY `action_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_claims`
--
ALTER TABLE `wp_actionscheduler_claims`
  MODIFY `claim_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_groups`
--
ALTER TABLE `wp_actionscheduler_groups`
  MODIFY `group_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_logs`
--
ALTER TABLE `wp_actionscheduler_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_apsl_users_social_profile_details`
--
ALTER TABLE `wp_apsl_users_social_profile_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_as3cf_items`
--
ALTER TABLE `wp_as3cf_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cky_banners`
--
ALTER TABLE `wp_cky_banners`
  MODIFY `banner_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cky_cookies`
--
ALTER TABLE `wp_cky_cookies`
  MODIFY `cookie_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cky_cookie_categories`
--
ALTER TABLE `wp_cky_cookie_categories`
  MODIFY `category_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_comments`
--
ALTER TABLE `wp_comments`
  MODIFY `comment_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_email_log`
--
ALTER TABLE `wp_email_log`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_ewwwio_images`
--
ALTER TABLE `wp_ewwwio_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_ewwwio_queue`
--
ALTER TABLE `wp_ewwwio_queue`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_ht_tmdb_queue`
--
ALTER TABLE `wp_ht_tmdb_queue`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_imagify_files`
--
ALTER TABLE `wp_imagify_files`
  MODIFY `file_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_imagify_folders`
--
ALTER TABLE `wp_imagify_folders`
  MODIFY `folder_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_ins_view_history`
--
ALTER TABLE `wp_ins_view_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_ins_watchlists`
--
ALTER TABLE `wp_ins_watchlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_ins_watchlist_items`
--
ALTER TABLE `wp_ins_watchlist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_cm_reference`
--
ALTER TABLE `wp_kirki_cm_reference`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_collaborations`
--
ALTER TABLE `wp_kirki_collaborations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_collaborations_connected`
--
ALTER TABLE `wp_kirki_collaborations_connected`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_collaborations_sent`
--
ALTER TABLE `wp_kirki_collaborations_sent`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_comments`
--
ALTER TABLE `wp_kirki_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_comments_seen`
--
ALTER TABLE `wp_kirki_comments_seen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_forms`
--
ALTER TABLE `wp_kirki_forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_kirki_forms_data`
--
ALTER TABLE `wp_kirki_forms_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_license_envato_userlist`
--
ALTER TABLE `wp_license_envato_userlist`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_links`
--
ALTER TABLE `wp_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_litespeed_avatar`
--
ALTER TABLE `wp_litespeed_avatar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_litespeed_crawler_blacklist`
--
ALTER TABLE `wp_litespeed_crawler_blacklist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_litespeed_img_optming`
--
ALTER TABLE `wp_litespeed_img_optming`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_litespeed_url`
--
ALTER TABLE `wp_litespeed_url`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_litespeed_url_file`
--
ALTER TABLE `wp_litespeed_url_file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_mo_openid_linked_user`
--
ALTER TABLE `wp_mo_openid_linked_user`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_options`
--
ALTER TABLE `wp_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_posts`
--
ALTER TABLE `wp_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_post_smtp_logmeta`
--
ALTER TABLE `wp_post_smtp_logmeta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_post_smtp_logs`
--
ALTER TABLE `wp_post_smtp_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_404_logs`
--
ALTER TABLE `wp_rank_math_404_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_analytics_adsense`
--
ALTER TABLE `wp_rank_math_analytics_adsense`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_analytics_ga`
--
ALTER TABLE `wp_rank_math_analytics_ga`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_analytics_gsc`
--
ALTER TABLE `wp_rank_math_analytics_gsc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_analytics_inspections`
--
ALTER TABLE `wp_rank_math_analytics_inspections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_analytics_keyword_manager`
--
ALTER TABLE `wp_rank_math_analytics_keyword_manager`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_analytics_objects`
--
ALTER TABLE `wp_rank_math_analytics_objects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_internal_links`
--
ALTER TABLE `wp_rank_math_internal_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_link_genius_audit`
--
ALTER TABLE `wp_rank_math_link_genius_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_link_genius_history`
--
ALTER TABLE `wp_rank_math_link_genius_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_link_genius_maps`
--
ALTER TABLE `wp_rank_math_link_genius_maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_link_genius_map_variations`
--
ALTER TABLE `wp_rank_math_link_genius_map_variations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_link_genius_snapshots`
--
ALTER TABLE `wp_rank_math_link_genius_snapshots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_redirections`
--
ALTER TABLE `wp_rank_math_redirections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_rank_math_redirections_cache`
--
ALTER TABLE `wp_rank_math_redirections_cache`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_css`
--
ALTER TABLE `wp_revslider_css`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_layer_animations`
--
ALTER TABLE `wp_revslider_layer_animations`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_navigations`
--
ALTER TABLE `wp_revslider_navigations`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_sliders`
--
ALTER TABLE `wp_revslider_sliders`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_sliders7`
--
ALTER TABLE `wp_revslider_sliders7`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_slides`
--
ALTER TABLE `wp_revslider_slides`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_slides7`
--
ALTER TABLE `wp_revslider_slides7`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_revslider_static_slides`
--
ALTER TABLE `wp_revslider_static_slides`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_snippets`
--
ALTER TABLE `wp_snippets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_social_users`
--
ALTER TABLE `wp_social_users`
  MODIFY `social_users_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_terms`
--
ALTER TABLE `wp_terms`
  MODIFY `term_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  MODIFY `umeta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_users`
--
ALTER TABLE `wp_users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wbca_department`
--
ALTER TABLE `wp_wbca_department`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wbca_message`
--
ALTER TABLE `wp_wbca_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wbca_search_document`
--
ALTER TABLE `wp_wbca_search_document`
  MODIFY `DOCUMENT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wbca_search_term`
--
ALTER TABLE `wp_wbca_search_term`
  MODIFY `TERM_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wbca_user_department`
--
ALTER TABLE `wp_wbca_user_department`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wbca_user_personal_info`
--
ALTER TABLE `wp_wbca_user_personal_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_admin_notes`
--
ALTER TABLE `wp_wc_admin_notes`
  MODIFY `note_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_admin_note_actions`
--
ALTER TABLE `wp_wc_admin_note_actions`
  MODIFY `action_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_customer_lookup`
--
ALTER TABLE `wp_wc_customer_lookup`
  MODIFY `customer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_download_log`
--
ALTER TABLE `wp_wc_download_log`
  MODIFY `download_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_orders_meta`
--
ALTER TABLE `wp_wc_orders_meta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_order_addresses`
--
ALTER TABLE `wp_wc_order_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_order_operational_data`
--
ALTER TABLE `wp_wc_order_operational_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_product_download_directories`
--
ALTER TABLE `wp_wc_product_download_directories`
  MODIFY `url_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_rate_limits`
--
ALTER TABLE `wp_wc_rate_limits`
  MODIFY `rate_limit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_tax_rate_classes`
--
ALTER TABLE `wp_wc_tax_rate_classes`
  MODIFY `tax_rate_class_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wc_webhooks`
--
ALTER TABLE `wp_wc_webhooks`
  MODIFY `webhook_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_api_keys`
--
ALTER TABLE `wp_woocommerce_api_keys`
  MODIFY `key_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_attribute_taxonomies`
--
ALTER TABLE `wp_woocommerce_attribute_taxonomies`
  MODIFY `attribute_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_downloadable_product_permissions`
--
ALTER TABLE `wp_woocommerce_downloadable_product_permissions`
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_log`
--
ALTER TABLE `wp_woocommerce_log`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_order_itemmeta`
--
ALTER TABLE `wp_woocommerce_order_itemmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_order_items`
--
ALTER TABLE `wp_woocommerce_order_items`
  MODIFY `order_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_payment_tokenmeta`
--
ALTER TABLE `wp_woocommerce_payment_tokenmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_payment_tokens`
--
ALTER TABLE `wp_woocommerce_payment_tokens`
  MODIFY `token_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_sessions`
--
ALTER TABLE `wp_woocommerce_sessions`
  MODIFY `session_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_shipping_zones`
--
ALTER TABLE `wp_woocommerce_shipping_zones`
  MODIFY `zone_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_shipping_zone_locations`
--
ALTER TABLE `wp_woocommerce_shipping_zone_locations`
  MODIFY `location_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_shipping_zone_methods`
--
ALTER TABLE `wp_woocommerce_shipping_zone_methods`
  MODIFY `instance_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_tax_rates`
--
ALTER TABLE `wp_woocommerce_tax_rates`
  MODIFY `tax_rate_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_woocommerce_tax_rate_locations`
--
ALTER TABLE `wp_woocommerce_tax_rate_locations`
  MODIFY `location_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_chatlogs`
--
ALTER TABLE `wp_wpaicg_chatlogs`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_chattokens`
--
ALTER TABLE `wp_wpaicg_chattokens`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_formtokens`
--
ALTER TABLE `wp_wpaicg_formtokens`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_form_feedback`
--
ALTER TABLE `wp_wpaicg_form_feedback`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_form_logs`
--
ALTER TABLE `wp_wpaicg_form_logs`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_imagetokens`
--
ALTER TABLE `wp_wpaicg_imagetokens`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_image_logs`
--
ALTER TABLE `wp_wpaicg_image_logs`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_promptbase_logs`
--
ALTER TABLE `wp_wpaicg_promptbase_logs`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_prompttokens`
--
ALTER TABLE `wp_wpaicg_prompttokens`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_prompt_feedback`
--
ALTER TABLE `wp_wpaicg_prompt_feedback`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_rsslogs`
--
ALTER TABLE `wp_wpaicg_rsslogs`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpaicg_token_logs`
--
ALTER TABLE `wp_wpaicg_token_logs`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_chat_report`
--
ALTER TABLE `wp_wpbot_chat_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_Conversation`
--
ALTER TABLE `wp_wpbot_Conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_extented_synonyms`
--
ALTER TABLE `wp_wpbot_extented_synonyms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_failed_response`
--
ALTER TABLE `wp_wpbot_failed_response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_response`
--
ALTER TABLE `wp_wpbot_response`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_response_category`
--
ALTER TABLE `wp_wpbot_response_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_response_entities`
--
ALTER TABLE `wp_wpbot_response_entities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_sessions`
--
ALTER TABLE `wp_wpbot_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_subscription`
--
ALTER TABLE `wp_wpbot_subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpbot_user`
--
ALTER TABLE `wp_wpbot_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpfm_backup`
--
ALTER TABLE `wp_wpfm_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_batch_log`
--
ALTER TABLE `wp_wpil_ai_batch_log`
  MODIFY `log_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_completed_batch_log`
--
ALTER TABLE `wp_wpil_ai_completed_batch_log`
  MODIFY `log_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_embedding_calculation_data`
--
ALTER TABLE `wp_wpil_ai_embedding_calculation_data`
  MODIFY `embed_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_embedding_calculation_data_v2`
--
ALTER TABLE `wp_wpil_ai_embedding_calculation_data_v2`
  MODIFY `embed_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_embedding_data`
--
ALTER TABLE `wp_wpil_ai_embedding_data`
  MODIFY `embed_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_embedding_phrase_calculation_data`
--
ALTER TABLE `wp_wpil_ai_embedding_phrase_calculation_data`
  MODIFY `embed_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_embedding_phrase_data`
--
ALTER TABLE `wp_wpil_ai_embedding_phrase_data`
  MODIFY `embed_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_error_log`
--
ALTER TABLE `wp_wpil_ai_error_log`
  MODIFY `log_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_keyword_data`
--
ALTER TABLE `wp_wpil_ai_keyword_data`
  MODIFY `ai_keyword_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_linking`
--
ALTER TABLE `wp_wpil_ai_linking`
  MODIFY `ai_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_post_data`
--
ALTER TABLE `wp_wpil_ai_post_data`
  MODIFY `ai_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_processed_sentences`
--
ALTER TABLE `wp_wpil_ai_processed_sentences`
  MODIFY `sentence_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_product_data`
--
ALTER TABLE `wp_wpil_ai_product_data`
  MODIFY `ai_product_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_suggested_anchors`
--
ALTER TABLE `wp_wpil_ai_suggested_anchors`
  MODIFY `suggestion_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_system_error_log`
--
ALTER TABLE `wp_wpil_ai_system_error_log`
  MODIFY `log_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ai_token_use_data`
--
ALTER TABLE `wp_wpil_ai_token_use_data`
  MODIFY `token_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_broken_links`
--
ALTER TABLE `wp_wpil_broken_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_click_data`
--
ALTER TABLE `wp_wpil_click_data`
  MODIFY `click_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_ignore_links`
--
ALTER TABLE `wp_wpil_ignore_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_keywords`
--
ALTER TABLE `wp_wpil_keywords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_keyword_links`
--
ALTER TABLE `wp_wpil_keyword_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_keyword_select_links`
--
ALTER TABLE `wp_wpil_keyword_select_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_link_mapping`
--
ALTER TABLE `wp_wpil_link_mapping`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_related_posts`
--
ALTER TABLE `wp_wpil_related_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_relation_mapping`
--
ALTER TABLE `wp_wpil_relation_mapping`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_report_links`
--
ALTER TABLE `wp_wpil_report_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_search_console_data`
--
ALTER TABLE `wp_wpil_search_console_data`
  MODIFY `gsc_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_sitemaps`
--
ALTER TABLE `wp_wpil_sitemaps`
  MODIFY `sitemap_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_snoozed_links`
--
ALTER TABLE `wp_wpil_snoozed_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_target_keyword_data`
--
ALTER TABLE `wp_wpil_target_keyword_data`
  MODIFY `keyword_index` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_telemetry_log`
--
ALTER TABLE `wp_wpil_telemetry_log`
  MODIFY `event_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_tracked_link_ids`
--
ALTER TABLE `wp_wpil_tracked_link_ids`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_urls`
--
ALTER TABLE `wp_wpil_urls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpil_url_links`
--
ALTER TABLE `wp_wpil_url_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpmailsmtp_debug_events`
--
ALTER TABLE `wp_wpmailsmtp_debug_events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpmailsmtp_tasks_meta`
--
ALTER TABLE `wp_wpmailsmtp_tasks_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsal_metadata`
--
ALTER TABLE `wp_wsal_metadata`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsal_occurrences`
--
ALTER TABLE `wp_wsal_occurrences`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsal_options`
--
ALTER TABLE `wp_wsal_options`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wsluserscontacts`
--
ALTER TABLE `wp_wsluserscontacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wslusersprofiles`
--
ALTER TABLE `wp_wslusersprofiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_indexable`
--
ALTER TABLE `wp_yoast_indexable`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_migrations`
--
ALTER TABLE `wp_yoast_migrations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_primary_term`
--
ALTER TABLE `wp_yoast_primary_term`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_prominent_words`
--
ALTER TABLE `wp_yoast_prominent_words`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_yoast_seo_links`
--
ALTER TABLE `wp_yoast_seo_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wp_kirki_cm_reference`
--
ALTER TABLE `wp_kirki_cm_reference`
  ADD CONSTRAINT `wp_kirki_cm_reference_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `wp_kirki_cm_reference_ibfk_2` FOREIGN KEY (`ref_post_id`) REFERENCES `wp_posts` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `wp_kirki_comments`
--
ALTER TABLE `wp_kirki_comments`
  ADD CONSTRAINT `wp_kirki_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `wp_kirki_comments_seen`
--
ALTER TABLE `wp_kirki_comments_seen`
  ADD CONSTRAINT `wp_kirki_comments_seen_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `wp_kirki_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wp_rank_math_link_genius_map_variations`
--
ALTER TABLE `wp_rank_math_link_genius_map_variations`
  ADD CONSTRAINT `fk_variation_keyword_map_id_1` FOREIGN KEY (`keyword_map_id`) REFERENCES `wp_rank_math_link_genius_maps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wp_wbca_search_index`
--
ALTER TABLE `wp_wbca_search_index`
  ADD CONSTRAINT `wp_wbca_search_index_ibfk_1` FOREIGN KEY (`TERM_ID`) REFERENCES `wp_wbca_search_term` (`TERM_ID`),
  ADD CONSTRAINT `wp_wbca_search_index_ibfk_2` FOREIGN KEY (`DOCUMENT_ID`) REFERENCES `wp_wbca_search_document` (`DOCUMENT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wp_wbca_user_department`
--
ALTER TABLE `wp_wbca_user_department`
  ADD CONSTRAINT `wp_wbca_user_department_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `wp_users` (`ID`),
  ADD CONSTRAINT `wp_wbca_user_department_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `wp_wbca_department` (`id`);

--
-- Constraints for table `wp_wbca_user_personal_info`
--
ALTER TABLE `wp_wbca_user_personal_info`
  ADD CONSTRAINT `wp_wbca_user_personal_info_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `wp_users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
