(() => {
	"use strict";

	function iniciarSimuladores() {
		document.querySelectorAll("[data-hc-energy]").forEach((simulador) => {
			if (simulador.dataset.energyReady) return;

			const formulario = simulador.querySelector("[data-energy-form]");
			const lista = simulador.querySelector("[data-energy-appliances]");
			const plantilla = simulador.querySelector("[data-energy-template]");
			const añadir = simulador.querySelector("[data-energy-add]");
			const resultados = simulador.querySelector("[data-energy-results]");
			const desglose = simulador.querySelector("[data-energy-breakdown]");
			const error = simulador.querySelector("[data-energy-error]");
			const selectorBateria = simulador.querySelector("[data-energy-battery]");

			if (
				!formulario || !lista || !plantilla || !añadir ||
				!resultados || !desglose || !error || !selectorBateria
			) {
				return;
			}

			simulador.dataset.energyReady = "true";

			const perfiles = simulador.querySelector("[data-energy-profiles]");
			const botonesPerfil = simulador.querySelectorAll("[data-battery-profile]");
			const deslizadores = simulador.querySelectorAll("[data-energy-slider]");
			const pantallaBateria = simulador.querySelector(
				"[data-energy-battery-display]"
			);
			const nivelBateria = simulador.querySelector("[data-energy-battery-level]");
			const etiquetaCarga = simulador.querySelector("[data-energy-charge-label]");

			const campo = (nombre) => formulario.elements.namedItem(nombre);

			const perfilesBateria = {
				agm: {
					capacidad: 100,
					voltaje: 12,
					reserva: 50
				},
				litio: {
					capacidad: 300,
					voltaje: 12.8,
					reserva: 20
				}
			};

			let calculado = false;

			const formato = (valor, decimales = 0) =>
				new Intl.NumberFormat("es-ES", {
					maximumFractionDigits: decimales
				}).format(valor);

			function elemento(etiqueta, clase, texto) {
				const nodo = document.createElement(etiqueta);

				if (clase) nodo.className = clase;
				if (texto !== undefined) nodo.textContent = texto;

				return nodo;
			}

			function invalidarResultado() {
				error.hidden = true;
				error.textContent = "";

				if (!calculado) return;

				calculado = false;

				resultados.replaceChildren(
					elemento(
						"p",
						"hc-energia__estado-inicial",
						"Has cambiado los datos. Pulsa «Calcular autonomía» para actualizar."
					)
				);

				desglose.replaceChildren();
			}

			function actualizarTarjetas() {
				botonesPerfil.forEach((boton) => {
					boton.setAttribute(
						"aria-pressed",
						String(
							boton.dataset.batteryProfile === selectorBateria.value
						)
					);
				});
			}

			function actualizarIndicadores() {
				deslizadores.forEach((deslizador) => {
					const entrada = campo(deslizador.dataset.energySlider);
					if (!entrada) return;

					const valor = entrada.valueAsNumber;

					if (Number.isFinite(valor)) {
						deslizador.value = Math.min(100, Math.max(0, valor));
					}
				});

				const carga = campo("carga").valueAsNumber;
				const cargaValida = Number.isFinite(carga) &&
					carga >= 0 && carga <= 100;

				if (nivelBateria) {
					nivelBateria.style.setProperty(
						"--energy-charge",
						`${cargaValida ? carga : 0}%`
					);
				}

				if (etiquetaCarga) {
					etiquetaCarga.textContent = cargaValida
						? `${formato(carga, 1)} %`
						: "—";
				}
			}

			function aplicarPerfil(nombre) {
				const perfil = perfilesBateria[nombre];

				selectorBateria.value = perfil ? nombre : "personalizada";

				if (perfil) {
					campo("capacidad").value = perfil.capacidad;
					campo("voltaje").value = perfil.voltaje;
					campo("reserva").value = perfil.reserva;
				}

				actualizarTarjetas();
				actualizarIndicadores();
				invalidarResultado();
			}

			selectorBateria.addEventListener("change", () => {
				aplicarPerfil(selectorBateria.value);
			});

			botonesPerfil.forEach((boton) => {
				boton.addEventListener("click", () => {
					aplicarPerfil(boton.dataset.batteryProfile);
				});
			});

			deslizadores.forEach((deslizador) => {
				const entrada = campo(deslizador.dataset.energySlider);
				if (!entrada) return;

				deslizador.addEventListener("input", () => {
					entrada.value = deslizador.value;
					entrada.dispatchEvent(
						new Event("input", { bubbles: true })
					);
				});
			});

			function gestionarCambio(evento) {
				const nombre = evento.target.name;

				if (["capacidad", "voltaje", "reserva"].includes(nombre)) {
					selectorBateria.value = "personalizada";
					actualizarTarjetas();
				}

				actualizarIndicadores();
				invalidarResultado();
			}

			formulario.addEventListener("input", gestionarCambio);
			formulario.addEventListener("change", gestionarCambio);

			function añadirAparato(datos = {}, enfocar = true) {
				const fragmento = plantilla.content.cloneNode(true);
				const fila = fragmento.querySelector(".hc-energia__aparato");

				const nombre = fila.querySelector("[data-device-name]");
				const potencia = fila.querySelector("[data-device-watts]");
				const horas = fila.querySelector("[data-device-hours]");
				const cantidad = fila.querySelector("[data-device-quantity]");
				const conexion = fila.querySelector("[data-device-connection]");

				nombre.value = datos.nombre || "";
				potencia.value = datos.potencia ?? "";
				horas.value = datos.horas ?? "";
				cantidad.value = datos.cantidad ?? 1;
				conexion.value = datos.conexion || "directa";

				lista.append(fragmento);
				invalidarResultado();

				if (enfocar) nombre.focus();
			}

			añadir.addEventListener("click", () => añadirAparato());

			lista.addEventListener("click", (evento) => {
				const boton = evento.target.closest("[data-device-remove]");
				if (!boton) return;

				boton.closest(".hc-energia__aparato").remove();
				invalidarResultado();
				añadir.focus();
			});

			/* Abre los ajustes si un campo inválido está dentro de ellos. */
			formulario.addEventListener("invalid", (evento) => {
				const detalles = evento.target.closest("details");
				if (detalles) detalles.open = true;
			}, true);

			function mostrarError(mensaje) {
				error.textContent = mensaje;
				error.hidden = false;

				resultados.replaceChildren(
					elemento("p", "", "Revisa los datos para obtener una estimación.")
				);

				desglose.replaceChildren();
				calculado = false;
			}

			function añadirMetrica(contenedor, titulo, valor) {
				const grupo = elemento("div");

				grupo.append(
					elemento("dt", "", titulo),
					elemento("dd", "", valor)
				);

				contenedor.append(grupo);
			}

			formulario.addEventListener("submit", (evento) => {
				evento.preventDefault();

				error.hidden = true;
				error.textContent = "";

				if (!formulario.reportValidity()) return;

				const numero = (nombre) => campo(nombre).valueAsNumber;

				const capacidad = numero("capacidad");
				const voltaje = numero("voltaje");
				const carga = numero("carga");
				const reserva = numero("reserva");
				const rendimiento = numero("rendimiento");
				const vacio = numero("vacio");
				const horasInversor = numero("horas_inversor");

				const valores = [
					capacidad, voltaje, carga, reserva,
					rendimiento, vacio, horasInversor
				];

				if (!valores.every(Number.isFinite)) {
					mostrarError("Completa todos los campos con números válidos.");
					return;
				}

				if (reserva > carga) {
					mostrarError(
						"La reserva no puede superar el porcentaje de carga inicial."
					);
					return;
				}

				const consumos = [];
				let usaInversor = false;

				for (const fila of lista.children) {
					const nombre = fila
						.querySelector("[data-device-name]").value.trim();

					const potencia = fila
						.querySelector("[data-device-watts]").valueAsNumber;

					const horas = fila
						.querySelector("[data-device-hours]").valueAsNumber;

					const cantidad = fila
						.querySelector("[data-device-quantity]").valueAsNumber;

					const conexion = fila
						.querySelector("[data-device-connection]").value;

					if (
						!nombre ||
						![potencia, horas, cantidad].every(Number.isFinite)
					) {
						mostrarError("Revisa el nombre y los valores de cada aparato.");
						return;
					}

					if (conexion === "inversor" && potencia * horas > 0) {
						usaInversor = true;

						if (horas > horasInversor) {
							const ajustes = campo("horas_inversor").closest("details");
							if (ajustes) ajustes.open = true;

							mostrarError(
								"En los ajustes del inversor, indica unas horas " +
								"de encendido iguales o superiores al uso diario " +
								"del aparato «" + nombre + "»."
							);
							return;
						}
					}

					const consumoBase = potencia * horas * cantidad;

					const consumo = conexion === "inversor"
						? consumoBase / (rendimiento / 100)
						: consumoBase;

					consumos.push({ nombre, consumo });
				}

				const consumoVacio = vacio * horasInversor;

				if (consumoVacio > 0) {
					consumos.push({
						nombre: "Consumo en vacío del inversor",
						consumo: consumoVacio
					});
				}

				const nominal = capacidad * voltaje;
				const disponible = nominal * ((carga - reserva) / 100);

				const diario = consumos.reduce(
					(total, aparato) => total + aparato.consumo,
					0
				);

				resultados.replaceChildren();
				desglose.replaceChildren();

				const autonomia = elemento("div", "hc-energia__autonomia");

				if (disponible === 0) {
					autonomia.append(
						elemento("strong", "", "0 días"),
						elemento("span", "", "Sin energía por encima de la reserva")
					);
				} else if (diario === 0) {
					autonomia.append(
						elemento("strong", "", "Sin cálculo"),
						elemento("span", "", "Añade un consumo diario mayor que cero")
					);
				} else {
					const dias = disponible / diario;

					autonomia.append(
						elemento(
							"strong",
							"",
							dias < 1
								? `${formato(dias * 24, 1)} h`
								: `${formato(dias, 1)} días`
						),
						elemento("span", "", "Autonomía energética aproximada")
					);
				}

				resultados.append(autonomia);

				const metricas = elemento("dl", "hc-energia__metricas");

				añadirMetrica(
					metricas,
					"Energía nominal del banco",
					`${formato(nominal)} Wh`
				);

				añadirMetrica(
					metricas,
					"Energía disponible hasta la reserva",
					`${formato(disponible)} Wh`
				);

				añadirMetrica(
					metricas,
					"Consumo diario desde la batería",
					`${formato(diario, 1)} Wh/día`
				);

				resultados.append(metricas);

				resultados.append(
					elemento(
						"p",
						"hc-energia__aviso",
						"Estimación sin recargas y con el mismo patrón de uso cada día. " +
						"Las horas expresan una equivalencia energética; " +
						"no predicen el momento exacto en que se agotará la batería."
					)
				);

				if (usaInversor && vacio === 0) {
					resultados.append(
						elemento(
							"p",
							"hc-energia__aviso",
							"El consumo en vacío del inversor está a cero. " +
							"Introduce el dato de tu equipo para incluirlo."
						)
					);
				}

				if (diario > 0) {
					desglose.append(
						elemento("h4", "", "¿Dónde se va tu energía?")
					);

					const listado = elemento("ul", "hc-energia__lista-consumos");

					consumos
						.filter((aparato) => aparato.consumo > 0)
						.sort((a, b) => b.consumo - a.consumo)
						.forEach((aparato) => {
							const porcentaje = aparato.consumo / diario * 100;
							const item = elemento("li");

							const cabecera = elemento(
								"div",
								"hc-energia__consumo-cabecera"
							);

							cabecera.append(
								elemento("span", "", aparato.nombre),
								elemento(
									"span",
									"",
									`${formato(aparato.consumo, 1)} Wh · ` +
									`${formato(porcentaje, 1)} %`
								)
							);

							const barra = elemento("div", "hc-energia__barra");
							barra.setAttribute("aria-hidden", "true");

							const relleno = elemento("span");
							relleno.style.width = `${porcentaje}%`;
							barra.append(relleno);

							item.append(cabecera, barra);
							listado.append(item);
						});

					desglose.append(listado);
				}

				calculado = true;
			});

			/* Activa los controles visuales cuando JavaScript está preparado. */
			if (perfiles && botonesPerfil.length) {
				perfiles.hidden = false;

				const selectorVisible = selectorBateria.closest(
					".hc-energia__tipo-bateria"
				);

				if (selectorVisible) selectorVisible.hidden = true;
			}

			simulador.querySelectorAll("[data-energy-slider-wrap]")
				.forEach((contenedor) => {
					contenedor.hidden = false;
				});

			if (pantallaBateria) pantallaBateria.hidden = false;

			actualizarTarjetas();
			actualizarIndicadores();
			añadirAparato({}, false);
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", iniciarSimuladores);
	} else {
		iniciarSimuladores();
	}
})();