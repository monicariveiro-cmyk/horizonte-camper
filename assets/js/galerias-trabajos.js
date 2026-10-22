(() => {
	"use strict";

	function iniciarGalerias() {
		document.querySelectorAll("[data-hc-gallery]").forEach((galeria) => {
			if (galeria.dataset.hcReady) return;
			galeria.dataset.hcReady = "true";

			/* Comparador de asientos y cama */
			galeria.querySelectorAll("[data-hc-compare]").forEach((comparador) => {
				const control = comparador.querySelector("[data-hc-range]");
				if (!control) return;

				const actualizarComparador = () => {
					comparador.style.setProperty(
						"--hc-split",
						`${control.value}%`
					);
				};

				control.addEventListener("input", actualizarComparador);
				actualizarComparador();
			});

			/* Visor de fotografías */
			const enlaces = [...galeria.querySelectorAll("[data-hc-image]")];
			const visor = galeria.querySelector("[data-hc-viewer]");

			if (!visor || !enlaces.length) return;

			/* Si el navegador no admite dialog, los enlaces siguen funcionando. */
			if (typeof visor.showModal !== "function") return;

			const imagen = visor.querySelector("[data-hc-full-image]");
			const pie = visor.querySelector("[data-hc-caption]");
			const contador = visor.querySelector("[data-hc-counter]");
			const cerrar = visor.querySelector("[data-hc-close]");
			const anterior = visor.querySelector("[data-hc-prev]");
			const siguiente = visor.querySelector("[data-hc-next]");

			let indice = 0;
			let enlaceOrigen = null;
			let overflowAnterior = "";
			let inicioToque = null;

			function mostrarImagen(nuevoIndice) {
				indice = (nuevoIndice + enlaces.length) % enlaces.length;

				const enlace = enlaces[indice];
				const miniatura = enlace.querySelector("img");

				imagen.alt = miniatura ? miniatura.alt : "";
				imagen.src = enlace.href;
				pie.textContent = enlace.dataset.caption || "";
				contador.textContent = `${indice + 1} / ${enlaces.length}`;
			}

			function abrirVisor(nuevoIndice, enlace) {
				if (visor.open) return;

				enlaceOrigen = enlace;
				mostrarImagen(nuevoIndice);

				overflowAnterior = document.documentElement.style.overflow;
				visor.showModal();
				document.documentElement.style.overflow = "hidden";
				cerrar.focus();
			}

			enlaces.forEach((enlace, posicion) => {
				enlace.addEventListener("click", (evento) => {
					/* Conserva abrir en otra pestaña con Ctrl, Cmd o Shift. */
					if (
						evento.ctrlKey ||
						evento.metaKey ||
						evento.shiftKey ||
						evento.altKey ||
						evento.button !== 0
					) {
						return;
					}

					evento.preventDefault();
					abrirVisor(posicion, enlace);
				});
			});

			cerrar.addEventListener("click", () => visor.close());
			anterior.addEventListener("click", () => mostrarImagen(indice - 1));
			siguiente.addEventListener("click", () => mostrarImagen(indice + 1));

			/* Escape lo gestiona el propio dialog. */
			visor.addEventListener("keydown", (evento) => {
				if (evento.key === "ArrowLeft") {
					evento.preventDefault();
					mostrarImagen(indice - 1);
				}

				if (evento.key === "ArrowRight") {
					evento.preventDefault();
					mostrarImagen(indice + 1);
				}

				/* Mantiene el foco dentro del visor al usar Tab. */
				if (evento.key === "Tab") {
					const botones = [cerrar, anterior, siguiente];
					const primero = botones[0];
					const ultimo = botones[botones.length - 1];

					if (evento.shiftKey && document.activeElement === primero) {
						evento.preventDefault();
						ultimo.focus();
					} else if (
						!evento.shiftKey &&
						document.activeElement === ultimo
					) {
						evento.preventDefault();
						primero.focus();
					}
				}
			});

			/* Cierra al pulsar en el fondo, fuera de imagen y controles. */
			visor.addEventListener("click", (evento) => {
				if (
					evento.target === visor ||
					evento.target.classList.contains("hc-visor__contenido")
				) {
					visor.close();
				}
			});

			/* Navegación táctil horizontal sobre la fotografía. */
			imagen.addEventListener("touchstart", (evento) => {
				inicioToque = null;

				if (evento.touches.length === 1) {
					inicioToque = {
						x: evento.touches[0].clientX,
						y: evento.touches[0].clientY
					};
				}
			}, { passive: true });

			imagen.addEventListener("touchmove", (evento) => {
				if (evento.touches.length !== 1) inicioToque = null;
			}, { passive: true });

			imagen.addEventListener("touchend", (evento) => {
				if (!inicioToque || !evento.changedTouches.length) return;

				const toque = evento.changedTouches[0];
				const desplazamientoX = toque.clientX - inicioToque.x;
				const desplazamientoY = toque.clientY - inicioToque.y;

				if (
					Math.abs(desplazamientoX) > 60 &&
					Math.abs(desplazamientoX) > Math.abs(desplazamientoY) * 1.5
				) {
					mostrarImagen(indice + (desplazamientoX < 0 ? 1 : -1));
				}

				inicioToque = null;
			}, { passive: true });

			imagen.addEventListener("touchcancel", () => {
				inicioToque = null;
			});

			visor.addEventListener("close", () => {
				document.documentElement.style.overflow = overflowAnterior;
				inicioToque = null;

				if (enlaceOrigen && enlaceOrigen.isConnected) {
					enlaceOrigen.focus({ preventScroll: true });
				}
			});
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", iniciarGalerias);
	} else {
		iniciarGalerias();
	}
})();