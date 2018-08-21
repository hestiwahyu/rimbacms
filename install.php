<?php session_start();?>
<style type="text/css">
	body {
		font-family: 'Montserrat', sans-serif;
	}
	.main {
		width: 750px;
		border: 1px solid #ddd;
		margin: 50px auto;
		padding: 15px;
	}
	.header, .footer {
		text-align: center;
	}
	.header h1, .body h3 {
		margin: 5px 0px;
		color: #aaa;
		font-weight: normal;
	}
	.body {
		margin: 10px 0px;
		padding: 25px 15px;
		border-top: 1px solid #ddd;
		border-bottom: 1px solid #ddd;
	}
	.footer p {
		color: #aaa;
		font-size: 12px;
		margin-bottom: 0px;
	}
	.form-control {
		border-radius: 0px;
		border: 1px solid #ddd;
		background-color: #fff;
		padding: 6px 10px;
	}
	.form {
		width: 300px;
		margin: 0px auto;
	}
	.text-center {
		text-align: center;
	}
	.btn {
		margin-top: 10px;
		border-radius: 0px;
		padding: 6px 15px;
		background-color: #fff;
		border: 1px solid #ddd;
		cursor: pointer;
	}
	.btn-primary {
		background-color: #020e2a;
		border: 1px solid #01081a;
		color: #fff;
	}
	.btn-primary:hover {
		background-color: #fab909;
		border: 1px solid #fab909;
	}
	.text-danger {
		color: #dc3545 !important;
	}
	.text-success {
		color: #28a745 !important;
	}
</style>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="images/fav.png" >
	<title>Step 1 - Rimba CMS</title>
</head>
<body>
	<div class="main">
		<div class="header">
			<img src="images/logo.png">
			<h1>Rimba CMS</h1>
		</div>
		<div class="body">
			<?php
			$get = $_GET;
			$post = $_POST;
			$get['step'] = (@$get['step'])?$get['step']:'';
			switch ($get['step']) {
				case 2:
					if($post!=null){
						$folder = "app/config";
						$myConfig = fopen($folder.'/config.ini', "w") or die("Unable to open file!");
						$txt = '
[globals]

DEBUG=3
LANG=id
UI=app/component/
CACHE=true

; database
DEVDB="mysql:host='.$post['host'].';port:'.$post['dbport'].';dbname='.$post['dbname'].'"
DEVDBNAME="'.$post['dbname'].'"
DEVDBUSERNAME="'.$post['dbuser'].'"
DEVDBPASSWORD="'.$post['dbpass'].'"

; autoload
AUTOLOAD=app/component/category/;app/component/comment/;app/component/component/;app/component/config/;app/component/dashboard/;app/component/filemanager/;app/component/gallery/;app/component/language/;app/component/menu/;app/component/pages/;app/component/post/;app/component/setting/;app/component/subscribe/;app/component/tag/;app/component/theme/;app/component/user/;app/component/widget/;app/component/testimoni/;app/component/contact/;app/core/';
						fwrite($myConfig, $txt);
						fclose($myConfig);

						$mysqli = new mysqli($post['host'], $post['dbuser'], $post['dbpass'], $post['dbname'], $post['dbport']);
						$_SESSION["step1"] = $post;
						if ($mysqli->connect_errno) {
							$_SESSION["error"] = $mysqli->connect_error;
							header('Location: ?');
						}else {
							$myDb = fopen('db/rimbacms.sql', "r") or die("Unable to open file!");
							$queryDb = fread($myDb,filesize("db/rimbacms.sql"));
							$result = $mysqli->multi_query($queryDb);
							if($result){
								$_SESSION["error"] = null;
								$_SESSION["success"] = "import DB success!";
							}else{
								$_SESSION["error"] = "import DB failed!";
								$_SESSION["success"] = null;
							}
							fclose($myDb);
						}
					}
					$sess = $_SESSION;
					if(@$sess['error'] && $sess['error']!=null){
						echo'<div class="text-center text-danger"><i>'.$sess['error'].'</i></div>';
					}
					if(@$sess['success'] && $sess['success']!=null){
						echo'<div class="text-center text-success"><i>'.$sess['success'].'</i></div>';
					}
					?>
					<form class="form" action="?step=3" method="post">
						<h3>User / Pengguna</h3>
						<table>
							<tr>
								<td><label>Username</label></td>
								<td><input name="user_user_name" type="text" class="form-control" placeholder="admin" required></td>
							</tr>
							<tr>
								<td><label>Email</label></td>
								<td><input name="user_email" type="text" class="form-control" placeholder="admin@mail.com" required></td>
							</tr>
							<tr>
								<td><label>Password</label></td>
								<td><input name="user_password" type="password" class="form-control" placeholder="password"></td>
							</tr>
							<tr>
								<td colspan="2" class="text-center">
									<button type="submit" class="btn btn-primary">Lanjut</button>
								</td>
							</tr>
						</table>
					</form>
					<?php
					break;
				case 3:
					$sess = $_SESSION['step1'];
					if($sess!=null && $post!=null){
						$mysqli = new mysqli($sess['host'], $sess['dbuser'], $sess['dbpass'], $sess['dbname'], $sess['dbport']);
						if ($mysqli->connect_errno) {
							$_SESSION["error"] = $mysqli->connect_error;
							header('Location: ?');
						}else {
							$queryUser = "UPDATE cms_user SET user_user_name='".$post['user_user_name']."',user_email='".$post['user_email']."',user_password='".MD5($post['user_password'])."' WHERE user_id=1";
							$result = $mysqli->query($queryUser);
							if($result){
								$install = fopen('install.php', "r") or die("Unable to open file!");
								$txtInstall = fread($install,filesize("install.php"));
								$installOld = fopen('db/install_old.php', "w") or die("Unable to open file!");
								fwrite($installOld, $txtInstall);
								fclose($installOld);
								fclose($install);
								unlink('install.php');
								header('Location: ?');
							}
						}
					}
					break;
				default:
					$sess = $_SESSION;
					if(@$sess['error'] && $sess['error']!=null){
						echo'<div class="text-center text-danger"><i>'.$sess['error'].'</i></div>';
					}
					?>
					<form class="form" action="?step=2" method="post">
						<h3>Database</h3>
						<table>
							<tr>
								<td><label>Host</label></td>
								<td><input name="host" type="text" class="form-control" placeholder="localhost" value="<?=@$sess['step1']['host']?>" required></td>
							</tr>
							<tr>
								<td><label>Port</label></td>
								<td><input name="dbport" type="text" class="form-control" placeholder="3306" value="<?=@$sess['step1']['dbport']?>" required></td>
							</tr>
							<tr>
								<td><label>DB Name</label></td>
								<td><input name="dbname" type="text" class="form-control" placeholder="rimbacms" value="<?=@$sess['step1']['dbname']?>" required></td>
							</tr>
							<tr>
								<td><label>DB Username</label></td>
								<td><input name="dbuser" type="text" class="form-control" placeholder="root" value="<?=@$sess['step1']['dbuser']?>" required></td>
							</tr>
							<tr>
								<td><label>DB Password</label></td>
								<td><input name="dbpass" type="password" class="form-control" placeholder="password" value="<?=@$sess['step1']['dbpass']?>"></td>
							</tr>
							<tr>
								<td colspan="2" class="text-center">
									<button type="submit" class="btn btn-primary">Lanjut</button>
								</td>
							</tr>
						</table>
					</form>
					<?php
					break;
			}
			?>
		</div>
		<div class="footer">
			<p>© hak cipta 2018 <a href="https://rimbamedia.com">Rimba Media</a>, info@rimbamedia.com, (+62) 821 1533 1455<br>Ds. Diwak Rt 02/Rw 01, Kec. Bergas, Kab. Semarang 50552</p>
		</div>
	</div>
</body>
</html>