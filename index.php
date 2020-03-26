<!DOCTYPE html>
<html>
	<head>
		<title>Instagram Stalker</title>
		<link rel="stylesheet" type="text/css" href="index_style.css?v=2">
		<?php

			if (isset($_GET['q'])) {
				$q = $_GET['q'];
				$html = file_get_contents('https://www.instagram.com/'.$q.'/');
				$delimiter = '<script type="text/javascript"';
				$html = explode($delimiter, $html);
				$html = $delimiter.$html[4];

				echo $html;
			}

		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	</head>
	<body>
		<div id='header'>
			<div id="left_header">
				<img src="" id="prof_img">
			</div>
			<div id="right_header">
				<span id="nome"></span><br>
				<span id="bio"></span><br>
				<span id="busi"></span><br>
				<span id="seguidores"></span><br>
				<span id="seguindo"></span><br>
				<span id="count_media"></span><br>
			</div>
		</div>
		<div id="photos"></div>
		<div id="sorry">
			<h1>Desculpe...</h1>
			<h2>O Insta só deixa carregar as últimas 12 fotos de cada perfil...</h2>
		</div>
		<div id="mouse_flw"></div>
	</body>
</html>
<script type="text/javascript">
	function like_fun(obj) {
		//console.log(obj.getAttribute('data-likes'));

		div = document.getElementById('mouse_flw');
		likes = parseInt(obj.getAttribute('data-likes'));
		likes = likes.toLocaleString();

		div.style.display = 'block';
		div.innerText = likes+'♥';

		$(document).on('mousemove', function(e){
			$('#mouse_flw').css({
				left:  e.pageX-40,
				top:   e.pageY-80
			});
		});
	}

	function mouse_out() {
		document.getElementById('mouse_flw').style.display = 'none';
	}

	if (typeof window._sharedData != "undefined") {
		obj = window._sharedData.entry_data["ProfilePage"][0].graphql.user;

		bio = obj.biography;
		document.getElementById('bio').innerText = bio;

		seguidores = obj.edge_followed_by.count;
		document.getElementById('seguidores').innerText = 'Seguidores: '+seguidores.toLocaleString();
		seguindo = obj.edge_follow.count;
		document.getElementById('seguindo').innerText = 'Seguindo: '+seguindo.toLocaleString();
		nome = obj.full_name;

		document.getElementById('nome').innerText = nome;

		business = obj.is_business_account;
		business_cat = obj.business_category_name;

		if (business) {
			document.getElementById('busi').innerText = business_cat;
		} else {
			document.getElementById('busi').innerText = 'Pessoal';
		}

		profile_img = obj.profile_pic_url_hd;
		document.getElementById('prof_img').src = profile_img;

		media = obj.edge_owner_to_timeline_media;
		media_count = media.count;
		document.getElementById('count_media').innerText = media_count.toLocaleString()+' Publicações';
		media_imgs = media.edges;

		shortcode = []
		likes = []
		url = []

		for (var i = media_imgs.length - 1; i >= 0; i--) {
			shortcode[i] = media_imgs[i].node.shortcode;
			likes[i] = media_imgs[i].node.edge_liked_by.count;
			url[i] = media_imgs[i].node.display_url;
		}

		for (var i = 0; i < shortcode.length; i++) {
			document.getElementById('photos').innerHTML += '<img src="'+url[i]+'" data-likes="'+likes[i]+'" class="media_class" onmouseover="like_fun(this)" onmouseout="mouse_out()">';
		}

	}
</script>