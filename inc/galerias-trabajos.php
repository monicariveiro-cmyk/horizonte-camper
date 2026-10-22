<?php
/**
 * Galerías de proyectos de Horizonte Camper.
 */

defined( 'ABSPATH' ) || exit;

function hc_galeria_t6_shortcode() {
	$base = content_url( '/uploads/2026/10/' );

	$imagenes = array(
		array(
			'archivo' => 'volkswagen-t6-comedor.webp',
			'titulo'  => 'Un espacio para compartir',
			'alt'     => 'Comedor de Volkswagen T6 con mesa, asientos grises y muebles de madera clara.',
		),
		array(
			'archivo' => 'volkswagen-t6-cocina.webp',
			'titulo'  => 'Cocina en ruta',
			'alt'     => 'Cocina lateral de Volkswagen T6 con fregadero y dos fogones.',
		),
		array(
			'archivo' => 'volkswagen-t6-nevera.webp',
			'titulo'  => 'Todo a mano',
			'alt'     => 'Nevera abierta integrada en los muebles de la Volkswagen T6.',
		),
		array(
			'archivo' => 'volkswagen-t6-vista-trasera.webp',
			'titulo'  => 'Despertar en otro lugar',
			'alt'     => 'Vista desde el portón trasero de Volkswagen T6 con cama y almacenaje inferior.',
		),
		array(
			'archivo' => 'volkswagen-t6-asientos.webp',
			'titulo'  => 'Modo día',
			'alt'     => 'Asiento trasero de Volkswagen T6 en posición sentada.',
		),
		array(
			'archivo' => 'volkswagen-t6-cama.webp',
			'titulo'  => 'Modo descanso',
			'alt'     => 'Asiento trasero de Volkswagen T6 transformado en cama.',
		),
	);

	$uid = wp_unique_id( 'hc-t6-' );

	ob_start();
	?>
	<section class="hc-proyecto" data-hc-gallery
		aria-labelledby="<?php echo esc_attr( $uid ); ?>titulo">

		<header class="hc-proyecto__cabecera">
			<p class="hc-proyecto__numero">01 / Volkswagen</p>

			<h2 id="<?php echo esc_attr( $uid ); ?>titulo">
				T6 · Pequeña por fuera.<br>
				Grande en posibilidades.
			</h2>

			<p class="hc-proyecto__descripcion">
				Madera clara, cocina lateral y un espacio que cambia
				contigo. Una propuesta para disfrutar del camino y
				sentirse en casa al llegar.
			</p>

			<ul class="hc-proyecto__detalles">
				<li>Techo elevable</li>
				<li>Cocina lateral</li>
				<li>Asiento convertible</li>
			</ul>
		</header>

		<div class="hc-galeria">
			<?php foreach ( $imagenes as $indice => $imagen ) : ?>
				<a
					class="hc-galeria__foto hc-galeria__foto--<?php echo esc_attr( $indice + 1 ); ?>"
					href="<?php echo esc_url( $base . $imagen['archivo'] ); ?>"
					data-hc-image
					data-caption="<?php echo esc_attr( $imagen['titulo'] ); ?>"
					data-elementor-open-lightbox="no"
					aria-label="<?php echo esc_attr( 'Ampliar: ' . $imagen['titulo'] ); ?>"
				>
					<img
						src="<?php echo esc_url( $base . $imagen['archivo'] ); ?>"
						alt="<?php echo esc_attr( $imagen['alt'] ); ?>"
						width="1536"
						height="1024"
						loading="lazy"
						decoding="async"
					>
					<span class="hc-galeria__rotulo">
						<?php echo esc_html( $imagen['titulo'] ); ?>
					</span>
					<span class="hc-galeria__ampliar" aria-hidden="true">↗</span>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="hc-transformacion">
			<div class="hc-transformacion__texto">
				<p class="hc-proyecto__numero">Un espacio, dos momentos</p>

				<h3>Del camino al descanso.</h3>

				<p>
					Desliza el control para descubrir cómo el asiento
					se transforma en una cama.
				</p>
			</div>

			<div class="hc-comparador" data-hc-compare style="--hc-split: 50%;">
				<div class="hc-comparador__imagenes">
					<img
						class="hc-comparador__base"
						src="<?php echo esc_url( $base . 'volkswagen-t6-cama.webp' ); ?>"
						alt="Asiento de la Volkswagen T6 convertido en cama."
						width="1536"
						height="1024"
						loading="lazy"
						decoding="async"
					>

					<img
						class="hc-comparador__superpuesta"
						src="<?php echo esc_url( $base . 'volkswagen-t6-asientos.webp' ); ?>"
						alt="Asiento de la Volkswagen T6 en posición sentada."
						width="1536"
						height="1024"
						loading="lazy"
						decoding="async"
					>

					<span class="hc-comparador__etiqueta hc-comparador__etiqueta--dia">
						Asientos
					</span>
					<span class="hc-comparador__etiqueta hc-comparador__etiqueta--noche">
						Cama
					</span>

					<span class="hc-comparador__divisor" aria-hidden="true">
						<span>↔</span>
					</span>
				</div>

				<label class="hc-comparador__label"
					for="<?php echo esc_attr( $uid ); ?>control">
					Comparar asientos y cama
				</label>

				<input
					id="<?php echo esc_attr( $uid ); ?>control"
					class="hc-comparador__control"
					data-hc-range
					type="range"
					min="0"
					max="100"
					value="50"
				>
			</div>
		</div>

		

		<dialog class="hc-visor" data-hc-viewer
			aria-label="Galería de la Volkswagen T6">
			<div class="hc-visor__contenido">
				<button type="button" class="hc-visor__cerrar"
					data-hc-close aria-label="Cerrar galería">
					×
				</button>

				<button type="button" class="hc-visor__anterior"
					data-hc-prev aria-label="Imagen anterior">
					←
				</button>

				<figure class="hc-visor__figura">
					<img data-hc-full-image alt="">
					<figcaption data-hc-caption aria-live="polite"></figcaption>
				</figure>

				<button type="button" class="hc-visor__siguiente"
					data-hc-next aria-label="Imagen siguiente">
					→
				</button>

				<p class="hc-visor__contador" data-hc-counter></p>
			</div>
		</dialog>
	</section>
	<?php

	return ob_get_clean();
}

add_shortcode( 'hc_galeria_t6', 'hc_galeria_t6_shortcode' );



/**
 * Galería Fiat Ducato Sportsvan.
 */
function hc_galeria_ducato_shortcode() {
	$base = content_url( '/uploads/2026/10/' );

	$imagenes = array(
		array(
			'archivo' => 'fiat-ducato-sportsvan-exterior.webp',
			'titulo'  => 'El horizonte como destino',
			'alt'     => 'Fiat Ducato L3H2 Sportsvan camperizada junto a la playa.',
		),
		array(
			'archivo' => 'fiat-ducato-sportsvan-interior.webp',
			'titulo'  => 'Espacio para vivir el viaje',
			'alt'     => 'Interior de Fiat Ducato Sportsvan con muebles de madera clara e iluminación LED.',
		),
		array(
			'archivo' => 'fiat-ducato-sportsvan-cocina.webp',
			'titulo'  => 'Cocinar sin prisas',
			'alt'     => 'Cocina de Fiat Ducato Sportsvan con fregadero, fogones y muebles claros.',
		),
		array(
			'archivo' => 'fiat-ducato-sportsvan-cama.webp',
			'titulo'  => 'Tu refugio al terminar el día',
			'alt'     => 'Cama de Fiat Ducato Sportsvan con textiles claros e iluminación cálida.',
		),
		array(
			'archivo' => 'fiat-ducato-sportsvan-dormitorio-desde-exterior.webp',
			'titulo'  => 'Descansar cerca del mar',
			'alt'     => 'Dormitorio de Fiat Ducato Sportsvan visto desde las puertas traseras abiertas.',
		),
		array(
			'archivo' => 'fiat-ducato-sportsvan-mesa-cocina-exterior.webp',
			'titulo'  => 'Una cocina abierta al paisaje',
			'alt'     => 'Cocina y mesa exterior de Fiat Ducato Sportsvan con vistas a la playa.',
		),
	);

	$uid = wp_unique_id( 'hc-ducato-' );

	ob_start();
	?>
	<section class="hc-proyecto hc-proyecto--ducato" data-hc-gallery
		aria-labelledby="<?php echo esc_attr( $uid ); ?>titulo">

		<header class="hc-proyecto__cabecera">
			<p class="hc-proyecto__numero">02 / Fiat Ducato</p>

			<h2 id="<?php echo esc_attr( $uid ); ?>titulo">
				Sportsvan · Más espacio.<br>
				Más caminos por descubrir.
			</h2>

			<p class="hc-proyecto__descripcion">
				Una propuesta de camperización sobre Fiat Ducato L3H2
				para tres personas. Madera clara, iluminación cálida
				y espacios pensados para combinar aventura y descanso.
			</p>

			<ul class="hc-proyecto__detalles">
				<li>Fiat Ducato L3H2</li>
				<li>3 plazas</li>
				<li>Cama elevada</li>
				<li>Iluminación LED</li>
			</ul>
		</header>

		<div class="hc-galeria">
			<?php foreach ( $imagenes as $indice => $imagen ) : ?>
				<a
					class="hc-galeria__foto hc-galeria__foto--<?php echo esc_attr( $indice + 1 ); ?>"
					href="<?php echo esc_url( $base . $imagen['archivo'] ); ?>"
					data-hc-image
					data-caption="<?php echo esc_attr( $imagen['titulo'] ); ?>"
					data-elementor-open-lightbox="no"
					aria-label="<?php echo esc_attr( 'Ampliar: ' . $imagen['titulo'] ); ?>"
				>
					<img
						src="<?php echo esc_url( $base . $imagen['archivo'] ); ?>"
						alt="<?php echo esc_attr( $imagen['alt'] ); ?>"
						width="1536"
						height="1024"
						loading="lazy"
						decoding="async"
					>
					<span class="hc-galeria__rotulo">
						<?php echo esc_html( $imagen['titulo'] ); ?>
					</span>
					<span class="hc-galeria__ampliar" aria-hidden="true">↗</span>
				</a>
			<?php endforeach; ?>
		</div>

		<p class="hc-proyecto__nota">
			Proyecto conceptual de camperización. Imágenes generadas con IA.
		</p>

		<dialog class="hc-visor" data-hc-viewer
			aria-label="Galería de la Fiat Ducato Sportsvan">
			<div class="hc-visor__contenido">
				<button type="button" class="hc-visor__cerrar"
					data-hc-close aria-label="Cerrar galería">
					×
				</button>

				<button type="button" class="hc-visor__anterior"
					data-hc-prev aria-label="Imagen anterior">
					←
				</button>

				<figure class="hc-visor__figura">
					<img data-hc-full-image alt="">
					<figcaption data-hc-caption aria-live="polite"></figcaption>
				</figure>

				<button type="button" class="hc-visor__siguiente"
					data-hc-next aria-label="Imagen siguiente">
					→
				</button>

				<p class="hc-visor__contador" data-hc-counter></p>
			</div>
		</dialog>
	</section>
	<?php

	return ob_get_clean();
}

add_shortcode( 'hc_galeria_ducato', 'hc_galeria_ducato_shortcode' );