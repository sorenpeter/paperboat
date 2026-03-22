<?php

/* Paperboat - A simple portfolio for artist and DIY'ere - by: sp@darch.dk 2026 */

echo "<pre>";

// Get config from settings.ini
$config = parse_ini_file("settings.ini");
$site_title = $config["title"];
$author = $config["author"];
$email = $config["email"];

// TODO: error hadning for setting.ini

// Global variables and arraies
$base_path = preg_replace("/index.php/i", "", $_SERVER['PHP_SELF']);
$base_url = "https://" . $_SERVER['SERVER_NAME'] . $base_path;

$routes = []; 	 // Routing table: [ "SLUG" => "PATH_TO_FILE" ]
$projects = []; // Array of projects
$pages = [];   // Array of pages 

$current_slug = ltrim(preg_replace($base_path, "", $_SERVER['REQUEST_URI']), "/");
$current_slug = parse_url($current_slug, PHP_URL_PATH);
$current_view = [];
$main_content = "";


// TODO: make this function work...
function addSlugToRoutes($slug, $file_path, $routes) {

	// Apped slug and path to html as key-value pair to routing table
	if (!array_key_exists($slug, $routes)) {
		$routes += [$slug => $file_path];
	} else {
		echo "<p class='error'>ERROR: Slug is already in use. Make sure your don't have both a project and pages with the same slug!";
	}
}


/* Scan pages-folder and add to array
- prefix (from file name)
- slug (from file name)
- title (from filename.html / H1, fallback to Slug remove underscore and dash)
*/

// Load pages from folder
$get_pages = glob("pages/*.html");

foreach ($get_pages as $path) {

	// Get slug from folder name without prefix numbers
	$filename = basename($path,".html");
	$slug_pattern = '/[0-9_-]+/i';
	$slug = strtolower(preg_replace($slug_pattern, '', $filename));

	// Get title from the first <h1> in html file
	$html_file = file_get_contents($path) or die("<p class='error'>Unable to open file: " . $path ."</p>");
	$title_pattern = '/<h1>(.*?)<\/h1>/si';
	preg_match($title_pattern, $html_file, $matches);
	$get_title = $matches[1];

	// Set home to root
	if ($slug == "home") {
		$routes += [$slug => $path];
		continue;
	}

	$new_page = array(
		"slug" => $slug,
		"html" => $path,
		"title" => $get_title
	);

	// Add the sub-array to $pages
	$pages[] = $new_page;

	// Apped slug and path to html as key-value pair to routing table
	if (!array_key_exists($slug, $routes)) {
		$routes += [$slug => $path];
	} else {
		echo "<p class='error'>ERROR: Slug is already in use. Make sure your don't have both a project and pages with the same slug!";
	}
}

/* Scan projects-folder and add to array

- prefix (from folder name)
- slug (from folder name)
- title (from index.html / H1, fallback to Slug remove underscore and dash)
- thumbnail.png or thumbnail.jpg (fallback to assets/no_thumbnail.png)

*/

// Load projects from folder
$get_projects = glob("projects/*", GLOB_ONLYDIR);

foreach ($get_projects as $path) {

	// Get slug from folder name without prefix numbers
	$slug_pattern = '/projects\/[0-9_-]+/i';
	$get_slug = strtolower(preg_replace($slug_pattern, '', $path));

	// Get path for the first html file in the folder
	$get_html = glob($path . "/*.html")[0];

	// Get title from the first <h1> in html file
	$html_file = file_get_contents($get_html) or die("Unable to open file: " . $get_html);
	$title_pattern = '/<h1>(.*?)<\/h1>/si';
	preg_match($title_pattern, $html_file, $matches);
	$get_title = $matches[1];

	//$get_thumbnail = glob($path . "/thumbnail.png");

	$new_project = array(
		"slug" => $get_slug,
		"folder" => $path,
		"html" => $get_html,
		"title" => $get_title
		//"thumbnail" => $get_thumbnail[0]
	);

	//print_r($new_project);	
	//echo "<hr>";

	// Add the sub-array to $projects
	$projects[] = $new_project;

	// Apped slug and path to html as key-value pair to routing table
	$routes += [$get_slug => $get_html];
	//addSlugToRoutes($get_slug, $get_html, $routes);
}

/*
echo "<h4>projects:</h4>";
print_r($projects);

echo "<h4>pages:</h4>";
print_r($pages);

*/

// echo "<h4>routes</h4>";
// print_r($routes);


/* == Routing == */

// Excpetion for home/root
if (empty($current_slug)) {
	$main_content = file_get_contents($routes["home"]) or die("Unable to open file: " . $routes["home"]);
	$title = $site_title;
}

// Check if current URI matches a key in the routing table
elseif (array_key_exists($current_slug, $routes)) {

	// Get main content from html file
	$main_content = file_get_contents($routes[$current_slug]) or die("Unable to open file: " . $routes[$current_slug]);

	// Get title via projecs or pages array
	$current_key = array_search($current_slug, array_column($projects, 'slug'));
	
	if (!empty($current_key)) {
		$current_view = $projects[$current_key];
	} else {
		$current_key = array_search($current_slug, array_column($pages, 'slug'));
		$current_view = $pages[$current_key];		
	}

	$title = $current_view["title"] . " - " . $site_title;

} else {
	$title = $site_title;
 	$main_content = "<h1>404: Page not found</h1>";
}

echo "</pre>";

/*
function showNavList() {

		echo '<ul class="projects">';
		foreach ($projects as $p) {
				echo '<li><a href="' . $base_url . $p["slug"] . ">" . $p["title"] . '</a></li>';
			}
		echo '</ul>';
		echo '<ul class="pages">';
			foreach ($pages as $p) {
				echo '<li><a href="' . $base_url . $p["slug"] . ">" . $p["title"] . '</a></li>';
			}
		echo '</ul>';
}
*/

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>

		/* Basic Styling */

		:root {
		  --back: SeaShell;
		  --text: Navy;
		  --link: Salmon;
		  --code: SeaGreen;
		  --mute: lightgrey;
		}

		.error {
			background: yellow;
			color: red;
		}
		
		html {
			font-family: sans-serif;
			background: var(--back);
			color: var(--text);
		}

		a, a:visited {
			color: var(--link);
			text-decoration: none;
		}

		a:hover {
			text-decoration: underline;
		}

		/* Default Layout with rows on top */

		header h1 {
			margin-top: 0;
		}

		header h1,
		header nav {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		header h1 img {
			display: block;
			margin: 0 auto;
			height: 75px;
		}

		header nav ul {
			display: inline-block;
			padding-left: 0;
		}

		header nav ul li {
			display: inline-block;
			margin: 0 0.5rem;
		}

		footer {
			margin: 1rem auto;
			text-align: center;
			font-size: small;
		}

		footer span {
			display: inline-block;
			line-height: 1.5;
		}

		/* Main content */

		main {
			max-width: 900px;
			margin: 0 auto;
			width: 100%;
		}

		main h1 {
			text-align: center;
		}

		.grid-view {
			display: grid;
			grid-template-columns: repeat(auto-fill,minmax(200px,1fr));
			grid-gap: 1rem;
			margin-bottom: 2rem;
		}

		.grid-view a img {
			width: 100%; 
			aspect-ratio: 1 / 1;
			object-fit: cover;
			cursor: zoom-in;
			border: thin solid var(--mute);
		}

		/* iframe for youtube embed etc. */
		iframe {
			width: 100%;
			aspect-ratio: 16 / 9;
			margin-bottom: 0.5rem;
		}


		@media (min-width: 700px) {
			#menu-open {
				display: none;
			}
			#mobile-menu {
				display: none;
			}
		}

		/* Mobile view */

		@media (max-width: 700px) {

			html {
				padding: 0.5rem;
			}

			body, header, main {
				display: initial !important;
				position: initial !important;
				margin: initial !important;
			}

			header h1 {
				margin: 0;
				display: block;
			}

			header h1 img {
				display: inline-block;
				height: 50px;
				vertical-align: -0.75rem !important;
				margin: 0 0.5rem;
			}

			header nav ul {
				padding-left: 0;
				display: block;
				margin-bottom: 2rem;
			}

			header nav ul li {
				display: block;
				list-style:none;
				margin: 1rem 0;
			}

			main {
				margin-left: 1rem;
			}

			footer {
				margin: 1rem auto;
			}

		/* Mobile menu */

			#menu-open {
				display: initial;
			}

			#dekstop-menu {
				display: none;
			}

			button#menu-open {
				position: absolute;
				top: 0.5rem;
				right: 1rem;
				background-color: transparent;
				font-weight: bold;
				color: var(--text);
				border: none;
				font-size: 2rem;
			}

			button#menu-open:hover {
				cursor: pointer;
				color: var(--link);
			}

			dialog#mobile-menu {
				margin-top: 4rem;
				height: 100%;
				width: 100%;
				background: var(--back);
				border: none;
				border-top: thin solid var(--text);
			}

			dialog#mobile-menu nav {
				max-width: fit-content;
				display: block;
				margin: 1rem auto;
				text-align: center;
			}

			button#menu-close {
				visibility: hidden;
				position: absolute;
				top: 1.1rem;
				right: 1.5rem;
				background-color: transparent;
				font-weight: bold;
				color: var(--text);
				border: none;
				font-size: 1.5rem;
			}

	}
	</style>
	<?php

	if ($config["navigation"] == "sidebar") {
		echo '<link rel="stylesheet" type="text/css" href="assets/layout-sidebar.css">';
	} elseif ($config["navigation"] == "topbar") {
		echo '<link rel="stylesheet" type="text/css" href="assets/layout-topbar.css">';
	} 
	
	if(file_exists(__DIR__ . "/assets/custom.css")) {
		echo '<link rel="stylesheet" type="text/css" href="assets/custom.css">';
	} ?>

	<title><?= $title ?></title>
</head>
<body>

<header>

	<h1>
		<a href="<?= $base_url ?>">
			<?php if(file_exists(__DIR__ . "/assets/logo.png")) { echo '<img src="assets/logo.png">'; } ?>
		<?= $site_title ?></a>
	</h1>	

	<!-- Mobile Menu (dialog) -->
	<button id="menu-open" popovertarget="mobile-menu">&#9776;</button>
	<dialog id="mobile-menu" popover>
		<button id="menu-close" popovertarget="mobile-menu" popovertargetaction="hide" >X</button>
		<nav>
			<ul class="projects">
				<?php foreach ($projects as $p) { ?>
					<li><a href="<?= $base_url . $p["slug"] ?>"><?= $p["title"] ?></a></li>
				<?php } ?>
			</ul>
			<ul class="pages">
				<?php foreach ($pages as $p) { ?>
					<li><a href="<?= $base_url . $p["slug"] ?>"><?= $p["title"] ?></a></li>
				<?php } ?>
			</ul>
		</nav>
	</dialog>

	<nav id="dekstop-menu">
		<ul class="projects">
			<?php foreach ($projects as $p) { ?>
				<li><a href="<?= $base_url . $p["slug"] ?>"><?= $p["title"] ?></a></li>
			<?php } ?>
		</ul>		
		<ul class="pages">
			<?php foreach ($pages as $p) { ?>
				<li><a href="<?= $base_url . $p["slug"] ?>"><?= $p["title"] ?></a></li>
			<?php } ?>
		</ul>
	</nav>
</header>

<main>

<?= $main_content ?>	

<?php

// TODO: Make into short code: <!-- GALLERY -->

if (!empty($current_view["folder"])){

	// Gallery for images in project
	$images = glob($current_view["folder"] . "/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF,svg,SVG,webp,WEBP}", GLOB_BRACE);

	// TODO: Exclude: thumb*

	echo '<div class="grid-view">';
	foreach ($images as $img) {
		// echo '<a href="'.$num.'" alt="'.$num.'" style=cursor:zoom-in>';
	    echo '<a href="'.$img.'" alt="'.$img.'">';
	    echo '<img class="gallery grid" src="'.$img.'" alt="'.$img.'" loading=lazy>';
	    echo '</a>';
	}
	echo '</div>';
}

?>

</main>

<footer>
	<span>&copy; Copyright 2026</span>
	<span class="footsteps">&nbsp;&ndash;&nbsp;</span>
	<span><a href="mailto:<?= $email ?>"><?= $author ?></a></span>
	<span class="footsteps">&nbsp;&ndash;&nbsp;</span>
	<span>Made with <a href="https://github.com/sorenpeter/paperboat">Paperboat</a></span>
</footer>

</body>
</html