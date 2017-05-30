-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 
-- Версия на сървъра: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vanime`
--

-- --------------------------------------------------------

--
-- Структура на таблица `actors`
--

CREATE TABLE `actors` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name_japanese` varchar(100) NOT NULL,
  `last_name_japanese` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `language` varchar(50) NOT NULL,
  `image_file_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `actors_users_status`
--

CREATE TABLE `actors_users_status` (
  `id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `age_ratings`
--

CREATE TABLE `age_ratings` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `slug` varchar(30) NOT NULL,
  `description` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `animes`
--

CREATE TABLE `animes` (
  `id` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `age_rating` int(11) DEFAULT NULL,
  `episode_count` int(11) DEFAULT NULL,
  `episode_length` int(11) DEFAULT NULL,
  `synopsis` text,
  `youtube_video_id` varchar(255) DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `cover_image_file_name` varchar(255) DEFAULT '',
  `cover_image_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `average_rating` double DEFAULT '0',
  `rank` int(11) DEFAULT NULL,
  `total_votes` int(11) NOT NULL,
  `age_rating_guide` varchar(255) DEFAULT NULL,
  `show_type` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT '0000-00-00',
  `poster_image_file_name` varchar(255) DEFAULT NULL,
  `poster_image_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cover_image_top_offset` int(11) NOT NULL DEFAULT '150',
  `started_airing_date_known` tinyint(1) NOT NULL DEFAULT '1',
  `titles` text,
  `abbreviated_titles` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `anime_comments`
--

CREATE TABLE `anime_comments` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `anime_franchise`
--

CREATE TABLE `anime_franchise` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) DEFAULT NULL,
  `franchise_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура на таблица `anime_genres`
--

CREATE TABLE `anime_genres` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура на таблица `anime_lists`
--

CREATE TABLE `anime_lists` (
  `id` int(11) NOT NULL,
  `user_list_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `anime_stats`
--

CREATE TABLE `anime_stats` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `watched` int(11) NOT NULL,
  `watching` int(11) NOT NULL,
  `stalled` int(11) NOT NULL,
  `dropped` int(11) NOT NULL,
  `want_to_watch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `characters`
--

CREATE TABLE `characters` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `alt_name` varchar(200) DEFAULT NULL,
  `japanese_name` varchar(200) DEFAULT NULL,
  `info` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `image_file_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `characters_users_status`
--

CREATE TABLE `characters_users_status` (
  `id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `character_actors`
--

CREATE TABLE `character_actors` (
  `character_id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `character_animes`
--

CREATE TABLE `character_animes` (
  `anime_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `character_comments`
--

CREATE TABLE `character_comments` (
  `id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `character_lists`
--

CREATE TABLE `character_lists` (
  `id` int(11) NOT NULL,
  `user_list_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `character_tags`
--

CREATE TABLE `character_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `character_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `facebook_accounts`
--

CREATE TABLE `facebook_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fb_user_id` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `changed_pass` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `franchises`
--

CREATE TABLE `franchises` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `titles` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `slug` varchar(30) DEFAULT NULL,
  `description` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `additional_info` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `notification_users`
--

CREATE TABLE `notification_users` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seen` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `wall_owner` int(11) NOT NULL,
  `post_owner` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `commenter` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `recommendations`
--

CREATE TABLE `recommendations` (
  `id` int(11) NOT NULL,
  `from_anime` int(11) NOT NULL,
  `to_anime` int(11) NOT NULL,
  `recommendation` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `rel_anime_studios`
--

CREATE TABLE `rel_anime_studios` (
  `anime_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `story` tinyint(4) NOT NULL,
  `animation` tinyint(4) NOT NULL,
  `sound` tinyint(4) NOT NULL,
  `characters` tinyint(4) NOT NULL,
  `enjoyment` tinyint(4) NOT NULL,
  `overall` tinyint(4) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `studios`
--

CREATE TABLE `studios` (
  `id` int(11) NOT NULL,
  `slug` varchar(60) DEFAULT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `slug` varchar(30) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `joined_on` date NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Japan',
  `profile_image` varchar(100) NOT NULL,
  `cover_image` varchar(100) NOT NULL,
  `top_offset` varchar(10) NOT NULL DEFAULT '0.01',
  `gender` varchar(10) NOT NULL DEFAULT 'unknown',
  `bio` text NOT NULL,
  `birthdate` date NOT NULL DEFAULT '0000-00-00',
  `last_online` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `user_lists`
--

CREATE TABLE `user_lists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `user_list_comments`
--

CREATE TABLE `user_list_comments` (
  `id` int(11) NOT NULL,
  `user_list_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `show_age` tinyint(1) NOT NULL DEFAULT '0',
  `default_watchlist_page` tinyint(2) NOT NULL DEFAULT '2',
  `default_watchlist_sort` varchar(50) NOT NULL DEFAULT 'DEFAULT 1',
  `show_last_online` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `user_temp_passes`
--

CREATE TABLE `user_temp_passes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `temp_pass` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `watchlists`
--

CREATE TABLE `watchlists` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `eps_watched` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `actors` ADD FULLTEXT KEY `first_name` (`first_name`,`last_name`,`first_name_japanese`,`last_name_japanese`);

--
-- Indexes for table `actors_users_status`
--
ALTER TABLE `actors_users_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actor_id` (`actor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `age_ratings`
--
ALTER TABLE `age_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `animes`
--
ALTER TABLE `animes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `show_type` (`show_type`),
  ADD KEY `age_rating` (`age_rating`),
  ADD KEY `start_date` (`start_date`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `average_rating` (`average_rating`);
ALTER TABLE `animes` ADD FULLTEXT KEY `titles_2` (`titles`);
ALTER TABLE `animes` ADD FULLTEXT KEY `slug` (`slug`);
ALTER TABLE `animes` ADD FULLTEXT KEY `synopsis` (`synopsis`);

--
-- Indexes for table `anime_comments`
--
ALTER TABLE `anime_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexes for table `anime_franchise`
--
ALTER TABLE `anime_franchise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_id` (`anime_id`),
  ADD KEY `franchise_id` (`franchise_id`);

--
-- Indexes for table `anime_genres`
--
ALTER TABLE `anime_genres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `anime_lists`
--
ALTER TABLE `anime_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`user_list_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexes for table `anime_stats`
--
ALTER TABLE `anime_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_at` (`created_at`);
ALTER TABLE `characters` ADD FULLTEXT KEY `first_name` (`first_name`,`last_name`,`alt_name`);

--
-- Indexes for table `characters_users_status`
--
ALTER TABLE `characters_users_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `character_id` (`character_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `character_actors`
--
ALTER TABLE `character_actors`
  ADD PRIMARY KEY (`character_id`,`actor_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `character_id` (`character_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `character_animes`
--
ALTER TABLE `character_animes`
  ADD PRIMARY KEY (`anime_id`,`character_id`),
  ADD KEY `character_id` (`character_id`);

--
-- Indexes for table `character_comments`
--
ALTER TABLE `character_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `character_id` (`character_id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `from_anime` (`from_anime`),
  ADD KEY `to_anime` (`to_anime`);

--
-- Indexes for table `rel_anime_studios`
--
ALTER TABLE `rel_anime_studios`
  ADD PRIMARY KEY (`anime_id`,`studio_id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `studio_id` (`studio_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_timestamp` (`timestamp`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_lists`
--
ALTER TABLE `user_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_list_comments`
--
ALTER TABLE `user_list_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`user_list_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_temp_passes`
--
ALTER TABLE `user_temp_passes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `watchlists`
--
ALTER TABLE `watchlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_lists`
--
ALTER TABLE `user_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_list_comments`
--
ALTER TABLE `user_list_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `user_temp_passes`
--
ALTER TABLE `user_temp_passes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `watchlists`
--
ALTER TABLE `watchlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=746;
--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `rel_anime_studios`
--
ALTER TABLE `rel_anime_studios`
  ADD CONSTRAINT `rel_anime_studios_ibfk_3` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
