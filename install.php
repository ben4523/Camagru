<?php

include('./theme/header.php');

if (!is_writable("./config/database.php")) {
	print('<section class="connection_session" style="height: 100%;">
			<div>
				<p id="warning_info_pass" class="warning_error" style="color: red;">PLEASE EXECUTE <b>CHMOD -R 777 ./</b> TO A FOLDER SITE TO CONTINUE</p>
				<form method="post" action="">
					<button type="button" class="send_connection" onclick="javascript:location.reload();">RELOAD</button>
				</form>
			</div>
		   </section>');
} else if (!isset($_POST['cc_url']))
	print('<section class="connection_session" style="height: 100%;">
			<div>
				<p id="warning_info_pass" class="warning_error" style="color: red;display:none;"></p>
			 	<div class="connection_R1">
			 		<div id="header_connection">
			 			<h1>SETUP CAMAGRU</h1>
			 			<form method="post" action="">
			 				<input type="text" name="cc_url" id="cc_url" placeholder="DATABASE URL" value="localhost">
			 				<input type="text" name="cc_username" id="cc_username" placeholder="DATABASE USERNAME">
			 				<input type="password" name="cc_password" id="cc_password" placeholder="DATABASE PASSWORD">
			 				<input type="text" name="cc_name" id="cc_name" placeholder="DATABASE NAME" value="admin_camagru">
			 				<button type="submit" class="send_connection">SEND</button>
			 			</form>
			 		</div>
			 	</div>
			 </div>
		   </section>');
else {
	$host = $_POST['cc_url']; 
	$root = $_POST['cc_username']; 
	$root_password = $_POST['cc_password']; 
	$db = $_POST['cc_name'];

    try {
        $dbh = new PDO("mysql:host=$host", $root, $root_password);
        $dbh->exec("CREATE DATABASE IF NOT EXISTS `$db`;");
        if ($dbh->errorInfo()[2] != '')
        	print('<section class="connection_session" style="height: 100%;">
			<div>
				<p id="warning_info_pass" class="warning_error" style="color: red;">A ERROR WAS OCCURED ! PLEASE RETRY !</p>
			 	<div class="connection_R1">
			 		<div id="header_connection">
			 			<h1>SETUP CAMAGRU</h1>
			 			<form method="post" action="">
			 				<input type="text" name="cc_url" id="cc_url" placeholder="DATABASE URL" value="'.$host.'">
			 				<input type="text" name="cc_username" id="cc_username" placeholder="DATABASE USERNAME" value="'.$root.'">
			 				<input type="password" name="cc_password" id="cc_password" placeholder="DATABASE PASSWORD" value="'.$root_password.'">
			 				<input type="text" name="cc_name" id="cc_name" placeholder="DATABASE NAME" value="'.$db.'">
			 				<button type="submit" class="send_connection">SEND</button>
			 			</form>
			 		</div>
			 	</div>
			 	<p id="warning_info_pass" class="warning_error" style="color: red;">'.$dbh->errorInfo()[2].'</p>
			 </div>
		   </section>');
        else {
        	$DB_DSN = 'mysql:host='.$host.';dbname='.$db;
        	$bdd = new PDO($DB_DSN, $root, $root_password);
        	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url_page = str_replace("install.php", "", $actual_link);
        	$bdd->query("CREATE TABLE `comment` (
						  `id` int(11) NOT NULL,
						  `id_post` int(11) NOT NULL,
						  `id_user` int(11) NOT NULL,
						  `comment` varchar(200) NOT NULL,
						  `date` datetime NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;

						--
						-- Contenu de la table `comment`
						--

						INSERT INTO `comment` (`id`, `id_post`, `id_user`, `comment`, `date`) VALUES
						(1, 1, 2, 'Wawwww !! where is it ?\r\n', '2017-12-22 20:13:20'),
						(2, 1, 1, 'In Alaska Ben ! Come !!!\r\n', '2017-12-22 20:16:00'),
						(3, 2, 1, 'This is Amazing !!\r\n', '2017-12-22 20:16:45'),
						(4, 2, 2, 'I know i know Mick :)\r\n', '2017-12-22 20:17:11'),
						(5, 3, 1, 'The color of the sea is just perfect ! :)\r\n', '2017-12-22 20:23:56'),
						(6, 3, 3, 'Oh yeahhhh Mick \r\n', '2017-12-22 20:24:30'),
						(7, 1, 4, 'I think i have to take a big jacket for here :)\r\n', '2017-12-22 20:31:07'),
						(8, 4, 1, 'This is in Miami ?\r\n', '2017-12-22 20:31:23'),
						(9, 4, 4, 'Yes it is ! Mick\r\n', '2017-12-22 20:31:46'),
						(10, 4, 3, 'Good job ! The picture is so nice ;)\r\n', '2017-12-22 20:33:08'),
						(11, 4, 2, 'The picture\r\n is from you ?', '2017-12-22 20:34:57'),
						(12, 4, 4, 'Yes Ben i just shoot this morning\r\n', '2017-12-22 20:35:41'),
						(13, 5, 2, 'I\'m in Love from this city !\r\n', '2017-12-22 20:41:33'),
						(14, 6, 2, 'Oh yes i see :)\r\n', '2017-12-22 20:46:09'),
						(15, 6, 1, 'Yesss me too\r\n', '2017-12-22 20:46:29'),
						(16, 6, 3, 'Good Idea Laura\r\n', '2017-12-22 20:46:54'),
						(17, 6, 6, 'Oh thanks Ben, Mick and Lisa\r\n', '2017-12-22 20:47:13'),
						(19, 7, 3, 'All in blue Alsoooo\r\n', '2017-12-22 20:52:12');

						-- --------------------------------------------------------

						--
						-- Structure de la table `config`
						--

						CREATE TABLE `config` (
						  `id` int(11) NOT NULL,
						  `name` varchar(300) NOT NULL,
						  `value` varchar(2000) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;

						--
						-- Contenu de la table `config`
						--

						INSERT INTO `config` (`id`, `name`, `value`) VALUES
						(1, 'base_url', '".$url_page."');

						-- --------------------------------------------------------

						--
						-- Structure de la table `language`
						--

						CREATE TABLE `language` (
						  `id` int(11) NOT NULL,
						  `name` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;

						--
						-- Contenu de la table `language`
						--

						INSERT INTO `language` (`id`, `name`) VALUES
						(1, 'Francais'),
						(2, 'Anglais');

						-- --------------------------------------------------------

						--
						-- Structure de la table `likes`
						--

						CREATE TABLE `likes` (
						  `id` int(11) NOT NULL,
						  `id_post` int(11) NOT NULL,
						  `id_user` int(11) NOT NULL,
						  `date` datetime NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;

						--
						-- Contenu de la table `likes`
						--

						INSERT INTO `likes` (`id`, `id_post`, `id_user`, `date`) VALUES
						(1, 1, 2, '2017-12-22 20:13:29'),
						(2, 2, 1, '2017-12-22 20:16:10'),
						(3, 3, 1, '2017-12-22 20:23:09'),
						(4, 2, 3, '2017-12-22 20:23:13'),
						(5, 1, 3, '2017-12-22 20:23:16'),
						(6, 3, 4, '2017-12-22 20:30:32'),
						(7, 2, 4, '2017-12-22 20:30:34'),
						(8, 1, 4, '2017-12-22 20:30:36'),
						(9, 4, 1, '2017-12-22 20:31:15'),
						(10, 4, 3, '2017-12-22 20:32:21'),
						(11, 4, 2, '2017-12-22 20:33:58'),
						(12, 3, 2, '2017-12-22 20:34:00'),
						(14, 4, 5, '2017-12-22 20:40:50'),
						(15, 3, 5, '2017-12-22 20:40:54'),
						(17, 5, 2, '2017-12-22 20:41:06'),
						(18, 6, 2, '2017-12-22 20:45:54'),
						(19, 6, 1, '2017-12-22 20:46:18'),
						(20, 6, 3, '2017-12-22 20:46:56'),
						(21, 7, 3, '2017-12-22 20:50:48'),
						(22, 7, 4, '2017-12-22 20:52:23'),
						(23, 7, 5, '2017-12-22 20:52:34'),
						(25, 3, 3, '2017-12-22 20:53:04');

						-- --------------------------------------------------------

						--
						-- Structure de la table `post`
						--

						CREATE TABLE `post` (
						  `id` int(11) NOT NULL,
						  `id_user` int(11) DEFAULT NULL,
						  `img` varchar(200) NOT NULL,
						  `comment` varchar(30) NOT NULL,
						  `date` datetime NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;

						--
						-- Contenu de la table `post`
						--

						INSERT INTO `post` (`id`, `id_user`, `img`, `comment`, `date`) VALUES
						(1, 1, './theme/img/post_pictures/5a3d5819ddc71_1.png', 'So Beautiful ', '2017-12-22 20:08:09'),
						(2, 2, './theme/img/post_pictures/5a3d59344ebfd_2.png', 'I want go here !! <3', '2017-12-09 20:12:52'),
						(3, 3, './theme/img/post_pictures/5a3d5b8e22c80_3.png', 'BLUE VIEWWWW #FAN', '2017-12-08 20:22:54'),
						(4, 4, './theme/img/post_pictures/5a3d5d5299f1b_4.png', 'Palmiers in red sky !!!  ', '2017-12-17 20:30:26'),
						(5, 5, './theme/img/post_pictures/5a3d5fbcbdf79_5.png', 'ALL WHITE IN PARIS #LOUVREPARI', '2017-12-15 20:40:44'),
						(6, 6, './theme/img/post_pictures/5a3d60e77fbb8_6.png', 'Darkvador is the Best @BottomD', '2017-12-04 20:45:43'),
						(7, 3, './theme/img/post_pictures/5a3d620fe5f0b_3.png', 'All in the Dark !! #DARKMOON', '2017-12-03 20:50:39');

						-- --------------------------------------------------------

						--
						-- Structure de la table `user`
						--

						CREATE TABLE `user` (
						  `id` int(11) NOT NULL,
						  `name` varchar(100) NOT NULL,
						  `username` varchar(40) NOT NULL,
						  `email` varchar(100) NOT NULL,
						  `password` varchar(128) NOT NULL,
						  `default_lang` char(11) NOT NULL,
						  `active` tinyint(1) DEFAULT NULL,
						  `token` varchar(60) NOT NULL,
						  `img_profil` varchar(1000) NOT NULL,
						  `notification` tinyint(1) NOT NULL DEFAULT '1'
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;

						--
						-- Contenu de la table `user`
						--

						INSERT INTO `user` (`id`, `name`, `username`, `email`, `password`, `default_lang`, `active`, `token`, `img_profil`, `notification`) VALUES
						(1, 'Mike Arlety', 'Mick Le Bg', 'ybitton@42.fr', '3714e87b4c780dad105742d196fd95cc1ad6a147435b1fd013802255cf096a675bc6d0ebf634312ef906e73eea63f7b834b3a5fc800a9ebe3934ac9cefa56ecd', 'EN', 1, '20002090085a23403a666196.14789146', './theme/img/profil_pictures/5a3d559d6a026png', 1),
						(2, 'Benjamin Jones', 'Ben La Terreur', 'ybitton1@42.fr', '3714e87b4c780dad105742d196fd95cc1ad6a147435b1fd013802255cf096a675bc6d0ebf634312ef906e73eea63f7b834b3a5fc800a9ebe3934ac9cefa56ecd', 'EN', 1, '9625408565a3d585f3ad580.83321985', './theme/img/profil_pictures/5a3d58b85368cpng', 0),
						(3, 'Lisa Blunt', 'Lisa La star', 'ybitton2@42.fr', '3714e87b4c780dad105742d196fd95cc1ad6a147435b1fd013802255cf096a675bc6d0ebf634312ef906e73eea63f7b834b3a5fc800a9ebe3934ac9cefa56ecd', 'FR', 1, '13948908505a3d5abfbb9d83.77200538', './theme/img/profil_pictures/5a3d5af99fbfcpng', 1),
						(4, 'Leah Botbol', 'Leah perfect Beauty', 'ybitton3@42.fr', '3714e87b4c780dad105742d196fd95cc1ad6a147435b1fd013802255cf096a675bc6d0ebf634312ef906e73eea63f7b834b3a5fc800a9ebe3934ac9cefa56ecd', 'EN', 1, '1998313095a3d5c9f4f56c9.61349677', './theme/img/profil_pictures/5a3d5ccbc15d2png', 1),
						(5, 'Jessica Jones', 'Jessy#Gucci#Gang', 'ybitton4@42.fr', '3714e87b4c780dad105742d196fd95cc1ad6a147435b1fd013802255cf096a675bc6d0ebf634312ef906e73eea63f7b834b3a5fc800a9ebe3934ac9cefa56ecd', 'FR', 1, '158391195a3d5f15243825.83436292', './theme/img/profil_pictures/5a3d5f822f33cpng', 0),
						(6, 'Laura Abitan', 'Laura #GoodVibe', 'ybitton5@42.fr', '3714e87b4c780dad105742d196fd95cc1ad6a147435b1fd013802255cf096a675bc6d0ebf634312ef906e73eea63f7b834b3a5fc800a9ebe3934ac9cefa56ecd', 'EN', 1, '9210058855a3d6044795065.96687994', './theme/img/profil_pictures/5a3d60876ed0epng', 1);

						--
						-- Index pour les tables exportées
						--

						--
						-- Index pour la table `comment`
						--
						ALTER TABLE `comment`
						  ADD PRIMARY KEY (`id`);

						--
						-- Index pour la table `config`
						--
						ALTER TABLE `config`
						  ADD PRIMARY KEY (`id`);

						--
						-- Index pour la table `language`
						--
						ALTER TABLE `language`
						  ADD PRIMARY KEY (`id`);

						--
						-- Index pour la table `likes`
						--
						ALTER TABLE `likes`
						  ADD PRIMARY KEY (`id`);

						--
						-- Index pour la table `post`
						--
						ALTER TABLE `post`
						  ADD PRIMARY KEY (`id`);

						--
						-- Index pour la table `user`
						--
						ALTER TABLE `user`
						  ADD PRIMARY KEY (`id`);

						--
						-- AUTO_INCREMENT pour les tables exportées
						--

						--
						-- AUTO_INCREMENT pour la table `comment`
						--
						ALTER TABLE `comment`
						  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
						--
						-- AUTO_INCREMENT pour la table `config`
						--
						ALTER TABLE `config`
						  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
						--
						-- AUTO_INCREMENT pour la table `language`
						--
						ALTER TABLE `language`
						  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
						--
						-- AUTO_INCREMENT pour la table `likes`
						--
						ALTER TABLE `likes`
						  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
						--
						-- AUTO_INCREMENT pour la table `post`
						--
						ALTER TABLE `post`
						  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
						--
						-- AUTO_INCREMENT pour la table `user`
						--
						ALTER TABLE `user`
						  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
						/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
						/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
						/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;");
			$current = "<?php\n \$DB_DSN = 'mysql:host=".$host.";dbname=".$db."';\n \$DB_USER = '".$root."';\n \$DB_PASSWORD = '".$root_password."';\n?>\n";
			file_put_contents("./config/database.php", $current);
			unlink('./install.php');
			header("Location: ./index.php");
        }

    } catch (PDOException $e) {
        print('<section class="connection_session" style="height: 100%;">
			<div>
				<p id="warning_info_pass" class="warning_error" style="color: red;">A ERROR WAS OCCURED ! PLEASE RETRY !</p>
			 	<div class="connection_R1">
			 		<div id="header_connection">
			 			<h1>SETUP CAMAGRU</h1>
			 			<form method="post" action="">
			 				<input type="text" name="cc_url" id="cc_url" placeholder="DATABASE URL" value="'.$host.'">
			 				<input type="text" name="cc_username" id="cc_username" placeholder="DATABASE USERNAME" value="'.$root.'">
			 				<input type="password" name="cc_password" id="cc_password" placeholder="DATABASE PASSWORD" value="'.$root_password.'">
			 				<input type="text" name="cc_name" id="cc_name" placeholder="DATABASE NAME" value="'.$db.'">
			 				<button type="submit" class="send_connection">SEND</button>
			 			</form>
			 		</div>
			 	</div>
			 	<p id="warning_info_pass" class="warning_error" style="color: red;">'.$e->getMessage().'</p>
			 </div>
		   </section>');
    }
}
?>