<?php 
// Loads the header.php template.
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Creattica
 * @since 1.0.0
 */
get_header();
?>

<!-- vores egen kode -->

	


<template>
        <article class="shows-container">
			<div class="shows-indhold">
			<img class="billeder" src="" alt="">
			<div class="shows-tekst">
			<h2 class="hovednavn"></h2>
			<h3 class="supportnavn_01"></h3>
			<h3 class="supportnavn_02"></h3>
			<h3 class="supportnavn_03"></h3>
            <p class="datoSpillested"></p>
			</div>	
			</div>
			<div class="popUp">
			<p class="information"></p>
			<p class="doors"></p>
			<p class="showStart"></p>
			<button class="billet"></button>
			<button class="hidePopUp"></button>
			</div>	
        </article>
    </template>

	
<!-- allerede eksisterende kode der skal til for at det fungere -->
<?php
// Dispay Loop Meta at top
hoot_display_loop_title_content( 'pre', 'page.php' );
if ( hoot_page_header_attop() ) {
	get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
	hoot_display_loop_title_content( 'post', 'page.php' );
}

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'page.php' );
?>

<div class="hgrid main-content-grid">

	<?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', 'page.php' );
	?>

	<main <?php hybridextend_attr( 'content' ); ?>>
		
		
		
		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_start', 'page.php' );

		// Checks if any posts were found.
		if ( have_posts() ) :

			// Display Featured Image if present
			if ( hoot_get_mod( 'post_featured_image' ) ) {
				$img_size = apply_filters( 'hoot_post_image_page', '' );
				hoot_post_thumbnail( 'entry-content-featured-img', $img_size, true );
			}

			// Dispay Loop Meta in content wrap
			if ( ! hoot_page_header_attop() ) {
				hoot_display_loop_title_content( 'post', 'page.php' );
				get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
			}
			?>

			<div id="content-wrap">

				<?php
				// Template modification Hook
				do_action( 'hoot_loop_start', 'page.php' );

				// Begins the loop through found posts, and load the post data.
				while ( have_posts() ) : the_post();

					// Loads the template-parts/content-{$post_type}.php template.
					hybridextend_get_content_template();

				// End found posts loop.
				endwhile;

				// Template modification Hook
				do_action( 'hoot_loop_end', 'page.php' );
				?>

			</div><!-- #content-wrap -->

			<?php
			// Template modification Hook
			do_action( 'hoot_template_after_content_wrap', 'page.php' );

			// Loads the comments.php template if this page is not being displayed as frontpage or a custom 404 page or if this is attachment page of media attached (uploaded) to a page.
			if ( !is_front_page() && !is_attachment() ) :

				// Loads the comments.php template
				comments_template( '', true );

			endif;

		// If no posts were found.
		else :

			// Loads the template-parts/error.php template.
			get_template_part( 'template-parts/error' );

		// End check for posts.
		endif;

		// Template modification Hook
		do_action( 'hoot_template_main_end', 'page.php' );
		?>

			<!-- ændrer på det her når du når dertil -->
            <nav id="filtrering" class="alignfull"><button class="dropbtn" data-shows="alle">Alle</button>
				<div class="dropdown-content">
				</div>
				</nav>
			
        	<section id="shows-oversigt"></section>
			
			<div class="popUpShow">
			<h2 class="hovednavn"></h2>	
			<h3 class="supportnavn_01"></h3>
			<h3 class="supportnavn_02"></h3>
			<h3 class="supportnavn_03"></h3>
			<p class="datoSpillested"></p>
			<p class="information"></p>
			<p class="doors"></p>
			<p class="showStart"></p>
			<button class="billet"></button>
			<button class="hidePopUp">Luk</button>
			</div>

				
		
	</main><!-- #content -->

	<?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', 'page.php' );
	?>

	<?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

</div><!-- .hgrid -->


<!-- vores script -->
<script>
		const siteUrl = "<?php echo esc_url( home_url( '/' ) ); ?>";
		let shows = [];
		let categories = [];
		const liste = document.querySelector("#shows-oversigt");
		const skabelon = document.querySelector("template");
		let filtershows = "alle";

		
		document.addEventListener("DOMContentLoaded", start);
        
        function start() {
            console.log("id er", <?php echo get_the_ID() ?>);
			console.log(siteUrl);
            getJson();
        }
		
		async function getJson() {
			const url = siteUrl + "wp-json/wp/v2/show?per_page=100"
			const catUrl = siteUrl + "wp-json/wp/v2/categories?per_page=100"
			let response = await fetch(url);
            let catResponse = await fetch(catUrl);
			shows = await response.json();
            categories = await catResponse.json();
			visShows();
			lavKnapper();
			klikbareLinks();
			console.log(shows);
		}
	
	function lavKnapper() {
		categories.forEach( cat => {
		if(cat.name != "Ikke-kategoriseret"){
         document.querySelector(".dropdown-content").innerHTML += `<button class="filter" data-shows="${cat.id}">${cat.name}</button>`
                }						   
		})
	}
		
		function visShows(){
		//rydder templaten for indhold inden det bliver sat ind igen
		liste.innerHTML = "";
		//indsættelse af indhold i arrayet gennem et forEach loop	
		shows.forEach(show => {
		if( filtershows == "alle" || filtershows == show.categories){
		const klon = skabelon.cloneNode(true).content;	
		klon.querySelector("h2").textContent = show.hovednavn;
		klon.querySelector(".supportnavn_01").textContent = show.supportnavn_01;
		klon.querySelector(".supportnavn_02").textContent = show.supportnavn_02;
		klon.querySelector(".supportnavn_03").textContent = show.supportnavn_03;
		klon.querySelector("button").textContent = "Køb billet";
		klon.querySelector(".hidePopUp").textContent = "Luk";
		klon.querySelector(".datoSpillested").textContent = show.dato + " @ " + show.spillested;
		klon.querySelector(".information").textContent = show.information;
		klon.querySelector(".doors").textContent = "Dørene åbner " + show.dorene_abner; 
		klon.querySelector(".showStart").textContent = "Første show " + show.forste_show;
		klon.querySelector(".billeder").src = show.billede.guid;
		klon.querySelector(".shows-container").addEventListener("click", ()=> displayText(show))
		liste.appendChild(klon);
				}
			})
		}

	//vælg popUppen
	const popUp = document.querySelector(".popUpShow");
	
	//vis popUppen
	function displayText(showet){
	popUp.style.display = "block";
	popUp.querySelector("h2").textContent = showet.hovednavn;
	popUp.querySelector(".supportnavn_01").textContent = showet.supportnavn_01;
	popUp.querySelector(".supportnavn_02").textContent = showet.supportnavn_02;
	popUp.querySelector(".supportnavn_03").textContent = showet.supportnavn_03;	
	popUp.querySelector(".datoSpillested").textContent = showet.dato + " @ " + showet.spillested;	
	popUp.querySelector(".information").textContent = showet.information;
	popUp.querySelector(".doors").textContent = "Dørene åbner " + showet.dorene_abner;
	popUp.querySelector(".showStart").textContent = "Første show " + showet.forste_show;
	popUp.querySelector("button").textContent = "Køb billet";
	popUp.querySelector(".hidePopUp").textContent = "Luk";
	//document.querySelector(".popUp").classList.toggle("displayInfo");
	}
	
	document.querySelector(".hidePopUp").addEventListener("click", forsvindKnap);
	
	
	function forsvindKnap(){
		popUp.style.display = "none";
	}
	
	
	function klikbareLinks(){
		document.querySelectorAll("#filtrering .filter").forEach(knap => {
		knap.addEventListener("click", filtrering);	
		})
		document.querySelector("#filtrering button").addEventListener("click", filtrering);
	}

	function filtrering(){
	filtershows = this.dataset.shows;
	console.log(filtershows);
	
	//fjern classen så man kan se knappen ikke er tilvalgt
	document.querySelector("#filtrering .dropbtn").classList.remove("selected");	
	document.querySelectorAll("#filtrering .filter").forEach(knap => {
                knap.classList.remove("selected");
            });
	//tilføj classen så man kan se knappen er valgt
	this.classList.add("selected");	

	visShows();
	}
	
	</script>


<?php get_footer(); // Loads the footer.php template. ?>