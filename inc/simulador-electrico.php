<?php
/**
 * Calculadora de autonomía eléctrica de Horizonte Camper.
 */

defined( 'ABSPATH' ) || exit;

function hc_simulador_electrico_shortcode() {
	$uid = wp_unique_id( 'hc-energia-' );

	ob_start();
	?>
	<section class="hc-energia hc-energia--app" data-hc-energy
		aria-label="Calculadora de autonomía eléctrica">

		<header class="hc-energia__barra-app">
			<div class="hc-energia__marca-app">
				<span class="hc-energia__icono-app" aria-hidden="true">ϟ</span>
				<div>
					<strong>Horizonte Energy</strong>
					<span>Calculadora de autonomía</span>
				</div>
			</div>
			<span class="hc-energia__distintivo">Sin recargas</span>
		</header>

		<noscript>
			<p>Activa JavaScript para utilizar la calculadora.</p>
		</noscript>

		<form class="hc-energia__formulario" data-energy-form>
			<div class="hc-energia__distribucion">

				<div class="hc-energia__configuracion">
					<div class="hc-energia__titulo-panel">
						<span class="hc-energia__paso" aria-hidden="true">01</span>
						<h2>Configura tu viaje</h2>
					</div>

					<fieldset class="hc-energia__panel">
						<legend>Tu batería</legend>

						<label class="hc-energia__tipo-bateria">
							Configuración de partida
							<select name="tipo_bateria" data-energy-battery>
								<option value="personalizada" selected>
									Personalizada — introduce tus datos
								</option>
								<option value="agm">AGM — ejemplo de 100 Ah</option>
								<option value="litio">Litio LiFePO₄ — 300 Ah</option>
							</select>
						</label>

						<div class="hc-energia__perfiles"
							data-energy-profiles hidden>
							<button type="button"
								class="hc-energia__perfil"
								data-battery-profile="agm"
								aria-pressed="false">
								<strong>AGM</strong>
								<span>100 Ah · 12 V</span>
							</button>

							<button type="button"
								class="hc-energia__perfil"
								data-battery-profile="litio"
								aria-pressed="false">
								<strong>Litio</strong>
								<span>300 Ah · 12,8 V</span>
							</button>

							<button type="button"
								class="hc-energia__perfil"
								data-battery-profile="personalizada"
								aria-pressed="true">
								<strong>Personalizada</strong>
								<span>Tus propios valores</span>
							</button>
						</div>

						<p class="hc-energia__ayuda">
							Los perfiles son ejemplos editables. Ajusta el voltaje
							y la reserva según las indicaciones de tu batería.
						</p>

						<div class="hc-energia__campos">
							<label>
								Capacidad total
								<span class="hc-energia__entrada">
									<input type="number" name="capacidad"
										min="1" max="10000" step="any"
										value="100" required>
									<span aria-hidden="true">Ah</span>
								</span>
								<small>Capacidad del banco en amperios hora.</small>
							</label>

							<label>
								Voltaje nominal
								<span class="hc-energia__entrada">
									<input type="number" name="voltaje"
										min="1" max="100" step="any"
										value="12.8" required>
									<span aria-hidden="true">V</span>
								</span>
								<small>Consulta la etiqueta del fabricante.</small>
							</label>

							<div class="hc-energia__campo-porcentaje">
								<label>
									Carga inicial (%)
									<input type="number" name="carga"
										min="0" max="100" step="any"
										value="100" required>
								</label>

								<div data-energy-slider-wrap hidden>
									<label class="hc-energia__solo-lectores"
										for="<?php echo esc_attr( $uid ); ?>carga">
										Ajustar carga inicial
									</label>
									<input id="<?php echo esc_attr( $uid ); ?>carga"
										type="range" min="0" max="100"
										step="1" value="100"
										data-energy-slider="carga">
								</div>
							</div>

							<div class="hc-energia__campo-porcentaje">
								<label>
									Reserva que quieres conservar (%)
									<input type="number" name="reserva"
										min="0" max="100" step="any"
										value="20" required>
								</label>

								<div data-energy-slider-wrap hidden>
									<label class="hc-energia__solo-lectores"
										for="<?php echo esc_attr( $uid ); ?>reserva">
										Ajustar reserva de batería
									</label>
									<input id="<?php echo esc_attr( $uid ); ?>reserva"
										type="range" min="0" max="100"
										step="1" value="20"
										data-energy-slider="reserva">
								</div>
							</div>
						</div>

						<details class="hc-energia__avanzado">
							<summary>Inversor de 230 V · ajustes avanzados</summary>

							<div class="hc-energia__campos">
								<label>
									Rendimiento supuesto (%)
									<input type="number" name="rendimiento"
										min="1" max="100" step="any"
										value="90" required>
								</label>

								<label>
									Consumo en vacío (W)
									<input type="number" name="vacio"
										min="0" max="1000" step="any"
										value="0" required>
								</label>

								<label>
									Horas encendido al día
									<input type="number" name="horas_inversor"
										min="0" max="24" step="any"
										value="0" required>
								</label>
							</div>

							<p class="hc-energia__ayuda">
								El rendimiento del 90 % es una hipótesis editable.
								Introduce los datos de tu inversor. Su consumo
								en vacío se sumará durante las horas indicadas.
							</p>
						</details>
					</fieldset>

					<fieldset class="hc-energia__panel">
						<legend>Tus aparatos</legend>

						<p class="hc-energia__ayuda">
							Indica la potencia eléctrica de entrada y las horas
							de uso diario. En una nevera, utiliza las horas
							equivalentes durante las que trabaja el compresor.
						</p>

						<div class="hc-energia__aparatos" data-energy-appliances></div>

						<button type="button"
							class="hc-energia__boton hc-energia__boton--secundario"
							data-energy-add>
							<span aria-hidden="true">＋</span> Añadir aparato
						</button>
					</fieldset>

					<button type="submit"
						class="hc-energia__boton hc-energia__boton--principal">
						Calcular autonomía
						<span aria-hidden="true">→</span>
					</button>

					<p class="hc-energia__error"
						data-energy-error role="alert" hidden></p>
				</div>

				<aside class="hc-energia__resultado"
					aria-labelledby="<?php echo esc_attr( $uid ); ?>resultado">

					<div class="hc-energia__titulo-panel">
						<span class="hc-energia__paso" aria-hidden="true">02</span>
						<h2 id="<?php echo esc_attr( $uid ); ?>resultado">
							Tu autonomía
						</h2>
					</div>

					<div class="hc-energia__pantalla-bateria"
						data-energy-battery-display hidden>
						<div class="hc-energia__bateria-grafica" aria-hidden="true">
							<div class="hc-energia__bateria-nivel"
								data-energy-battery-level></div>
							<span class="hc-energia__bateria-simbolo">ϟ</span>
						</div>

						<p class="hc-energia__carga-visible">
							<strong data-energy-charge-label>100 %</strong>
							<span>Carga inicial</span>
						</p>
					</div>

					<div data-energy-results aria-live="polite" aria-atomic="true">
						<p class="hc-energia__estado-inicial">
							Configura tu batería, añade tus aparatos y calcula
							la energía disponible para tu escapada.
						</p>
					</div>

					<div class="hc-energia__desglose" data-energy-breakdown></div>
				</aside>
			</div>
		</form>

		<details class="hc-energia__metodo">
			<summary>Cómo funciona esta calculadora</summary>

			<ul>
				<li>
					Energía nominal (Wh): capacidad en Ah × voltaje nominal.
				</li>
				<li>
					Energía disponible: energía nominal ×
					(carga inicial − reserva) ÷ 100.
				</li>
				<li>
					Consumo diario (Wh): potencia × horas de uso × cantidad.
				</li>
				<li>
					En aparatos de 230 V, el consumo se divide entre
					el rendimiento del inversor expresado como fracción.
				</li>
				<li>
					Se añade el consumo en vacío del inversor durante
					las horas de encendido indicadas.
				</li>
				<li>
					Autonomía en días: energía disponible ÷ consumo diario.
				</li>
			</ul>

			<p>
				Los perfiles AGM y litio son hipótesis de ejemplo editables,
				no límites universales. La reserva debe ajustarse a las
				indicaciones del fabricante.
			</p>

			<p>
				La estimación supone el mismo uso cada día y no incluye
				recargas solares, alternador o red. No modela temperatura,
				envejecimiento, efecto de la intensidad de descarga
				ni otras pérdidas de la instalación.
			</p>

			<p>
				No comprueba la potencia simultánea admisible ni los
				picos de arranque. El indicador gráfico representa
				la carga inicial introducida, no una medición real.
			</p>
		</details>

		<template data-energy-template>
			<div class="hc-energia__aparato">
				<div class="hc-energia__campos">
					<label class="hc-energia__nombre-aparato">
						Aparato
						<input type="text" data-device-name
							maxlength="60"
							placeholder="Por ejemplo: luces LED"
							required>
					</label>

					<label>
						Potencia (W)
						<input type="number" data-device-watts
							min="0" max="100000" step="any" required>
					</label>

					<label>
						Horas/día
						<input type="number" data-device-hours
							min="0" max="24" step="any" required>
					</label>

					<label>
						Unidades
						<input type="number" data-device-quantity
							min="1" max="100" step="1"
							value="1" required>
					</label>

					<label class="hc-energia__conexion-aparato">
						Conexión
						<select data-device-connection>
							<option value="directa">Continua / directa</option>
							<option value="inversor">230 V / inversor</option>
						</select>
					</label>
				</div>

				<button type="button"
					class="hc-energia__quitar" data-device-remove>
					Quitar aparato
				</button>
			</div>
		</template>
	</section>
	<?php

	return ob_get_clean();
}

add_shortcode( 'hc_simulador_electrico', 'hc_simulador_electrico_shortcode' );


/**
 * Schema del simulador eléctrico integrado con Rank Math.
 */
function hc_simulador_electrico_schema( $data, $jsonld ) {
	if ( ! is_page( 'simulador-electrico' ) ) {
		return $data;
	}

	$url    = get_permalink( get_queried_object_id() );
	$app_id = $url . '#simulador-electrico';

	$app = array(
		'@type'               => 'WebApplication',
		'@id'                 => $app_id,
		'name'                => 'Horizonte Energy — Simulador eléctrico',
		'url'                 => $url,
		'description'         => 'Calculadora de autonomía eléctrica para camper. Estima el consumo diario y la duración de la batería según su capacidad, carga inicial, reserva y los aparatos utilizados, sin incluir recargas.',
		'applicationCategory' => 'UtilitiesApplication',
		'operatingSystem'     => 'Any',
		'browserRequirements' => 'Requiere un navegador con JavaScript activado.',
		'inLanguage'          => 'es',
		
		'featureList'         => array(
			'Configuración de baterías AGM, litio y personalizadas.',
			'Estimación del consumo diario de los aparatos.',
			'Cálculo de autonomía sin recargas.',
			'Ajustes de rendimiento y consumo del inversor.',
			'Desglose del consumo por aparato.',
		),
	);

	/*
	 * Conecta la aplicación con el nodo de página
	 * que ya genera Rank Math.
	 */
	foreach ( $data as &$nodo ) {
		if ( ! is_array( $nodo ) ) {
			continue;
		}

		$tipos = isset( $nodo['@type'] )
			? (array) $nodo['@type']
			: array();

		if ( in_array( 'WebPage', $tipos, true ) ) {
			$nodo['mainEntity'] = array(
				'@id' => $app_id,
			);

			if ( ! empty( $nodo['@id'] ) ) {
				$app['mainEntityOfPage'] = array(
					'@id' => $nodo['@id'],
				);
			}

			break;
		}
	}
	unset( $nodo );

	$data['hc_energy_application'] = $app;

	return $data;
}

add_filter(
	'rank_math/json_ld',
	'hc_simulador_electrico_schema',
	99,
	2
);