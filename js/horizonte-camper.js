/**
 * ==========================================================
 * Horizonte Camper
 * JavaScript principal del tema hijo
 * Archivo: js/horizonte-camper.js
 * ==========================================================
 */


/* ==========================================================
   1. CABECERA INTELIGENTE
   Se oculta al bajar y aparece al subir
========================================================== */

(function () {

    const cabecera = document.querySelector(
        "#site-header, header.site-header, .site-header"
    );

    if (!cabecera) {
        return;
    }

    let scrollAnterior = window.scrollY;

    window.addEventListener(
        "scroll",
        function () {

            const scrollActual = window.scrollY;

            /* Cerca del inicio, la cabecera permanece visible */
            if (scrollActual <= 80) {
                cabecera.classList.remove("cabecera-oculta");
                scrollAnterior = scrollActual;
                return;
            }

            /* Al bajar, ocultamos la cabecera */
            if (scrollActual > scrollAnterior + 5) {
                cabecera.classList.add("cabecera-oculta");
            }

            /* Al subir, mostramos de nuevo la cabecera */
            else if (scrollActual < scrollAnterior - 5) {
                cabecera.classList.remove("cabecera-oculta");
            }

            scrollAnterior = scrollActual;
        },
        {
            passive: true
        }
    );

})();


/* ==========================================================
   2. BOTÓN VOLVER ARRIBA
========================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const botonArriba = document.getElementById("volver-arriba");

        /*
         * Si el botón no existe en esta página,
         * detenemos esta parte del script.
         */
        if (!botonArriba) {
            return;
        }

        window.addEventListener(
            "scroll",
            function () {

                if (window.scrollY > 500) {
                    botonArriba.classList.add("visible");
                } else {
                    botonArriba.classList.remove("visible");
                }
            },
            {
                passive: true
            }
        );

        botonArriba.addEventListener(
            "click",
            function (e) {

                e.preventDefault();

                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
        );

    }
);


/* ==========================================================
   3. ANIMACIÓN DEL BLOG AL HACER SCROLL
========================================================== */

/*
 * Esta clase indica al CSS que JavaScript
 * se ha cargado correctamente.
 */
document.documentElement.classList.add("hc-js");

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const tarjetas = document.querySelectorAll(".hc-articulo");

        /*
         * Si no hay tarjetas del blog,
         * no hacemos nada.
         */
        if (!tarjetas.length) {
            return;
        }

        /*
         * Compatibilidad con navegadores que
         * no soporten IntersectionObserver.
         */
        if (!("IntersectionObserver" in window)) {

            tarjetas.forEach(
                function (tarjeta) {
                    tarjeta.classList.add("hc-visible");
                }
            );

            return;
        }

        /*
         * Observador que detecta cuándo las tarjetas
         * entran en la zona visible de la pantalla.
         */
        const observador = new IntersectionObserver(
            function (entradas, observer) {

                entradas.forEach(
                    function (entrada) {

                        if (entrada.isIntersecting) {

                            entrada.target.classList.add("hc-visible");

                            /*
                             * Dejamos de observar la tarjeta
                             * una vez que ya se ha mostrado.
                             */
                            observer.unobserve(entrada.target);
                        }
                    }
                );
            },
            {
                threshold: 0.15,
                rootMargin: "0px 0px -40px 0px"
            }
        );

        /*
         * Empezar a observar todas las tarjetas.
         */
        tarjetas.forEach(
            function (tarjeta) {
                observador.observe(tarjeta);
            }
        );

    }
);

/* ==========================================================
   4. ANIMACIÓN DE LA SECCIÓN "NUESTRA FILOSOFÍA"
========================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const filosofia = document.getElementById("filosofia");

        /*
         * Si esta sección no existe en la página,
         * no hacemos nada.
         */
        if (!filosofia) {
            return;
        }

        /*
         * Compatibilidad con navegadores antiguos.
         */
        if (!("IntersectionObserver" in window)) {
            filosofia.classList.add("visible");
            return;
        }

        /*
         * Detectar cuándo la sección entra en pantalla.
         */
        const observadorFilosofia = new IntersectionObserver(
            function (entradas, observer) {

                entradas.forEach(
                    function (entrada) {

                        if (entrada.isIntersecting) {

                            entrada.target.classList.add("visible");

                            /*
                             * La animación solo se ejecuta una vez.
                             */
                            observer.unobserve(entrada.target);
                        }
                    }
                );
            },
            {
                threshold: 0.2,
                rootMargin: "0px 0px -50px 0px"
            }
        );

        observadorFilosofia.observe(filosofia);

    }
);


/* ==========================================================
   SERVICIOS - ACORDEONES
========================================================== */

document.querySelectorAll('.hc-servicio-titulo').forEach((boton) => {

    boton.addEventListener('click', () => {

        const servicio = boton.closest('.hc-servicio-desplegable');

        const abierto = servicio.classList.toggle('activo');

        boton.setAttribute('aria-expanded', abierto);

    });

});


/* ==========================================================
   CONFIGURADOR CAMPER - CREAR FORMULARIO
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const configurador = document.querySelector('.hc-configurador');

    if (!configurador) {
        return;
    }

    /* Evita crear el formulario dos veces */
    if (configurador.querySelector('#hc-configurador-form')) {
        return;
    }

    const formulario = document.createElement('form');

    formulario.id = 'hc-configurador-form';
    formulario.className = 'hc-configurador-form';
    formulario.method = 'post';


    /* Mueve todo el contenido del configurador dentro del formulario */
    while (configurador.firstChild) {
        formulario.appendChild(configurador.firstChild);
    }

    configurador.appendChild(formulario);


    /* Recuperamos admin-post.php desde el shortcode */
    const meta = formulario.querySelector('.hc-config-meta');

    if (meta && meta.dataset.action) {
        formulario.action = meta.dataset.action;
    }

});

// Sticky dinámico del aside: se fija cuando entra completo en pantalla.

// Sticky bidireccional del aside del configurador.
// Al bajar, se fija cuando el aside entra completo en pantalla.
// Al subir, vuelve a acompañar el scroll hasta alcanzar la parte superior.

document.addEventListener('DOMContentLoaded', function () {

    const aside = document.querySelector('.hc-servicios-aside-sticky');

    if (!aside) return;

    const margenSuperior = 30;
    const margenInferior = 30;

    let ultimoScroll = window.scrollY;
    let topSticky = margenSuperior;
    let limiteInferior = 0;
    let ticking = false;


    function calcularLimites() {

        if (window.innerWidth <= 1024) {
            aside.style.removeProperty('--hc-aside-top');
            return;
        }

        const alturaAside = aside.offsetHeight;
        const alturaVentana = window.innerHeight;

        limiteInferior =
            alturaVentana - alturaAside - margenInferior;

        /*
         * Si el aside cabe entero en pantalla,
         * simplemente lo dejamos fijado arriba.
         */
        if (limiteInferior > margenSuperior) {
            limiteInferior = margenSuperior;
        }

        const posicionActual = aside.getBoundingClientRect().top;

        topSticky = Math.max(
            limiteInferior,
            Math.min(margenSuperior, posicionActual)
        );

        aside.style.setProperty(
            '--hc-aside-top',
            `${topSticky}px`
        );
    }


    function actualizarSticky() {

        if (window.innerWidth <= 1024) {
            ticking = false;
            return;
        }

        const scrollActual = window.scrollY;
        const diferencia = scrollActual - ultimoScroll;

        /*
         * BAJANDO:
         * reducimos progresivamente el top hasta que
         * el final del aside queda visible.
         */
        if (diferencia > 0) {

            topSticky = Math.max(
                limiteInferior,
                topSticky - diferencia
            );

        /*
         * SUBIENDO:
         * aumentamos progresivamente el top hasta
         * alcanzar el margen superior.
         */
        } else if (diferencia < 0) {

            topSticky = Math.min(
                margenSuperior,
                topSticky - diferencia
            );
        }

        aside.style.setProperty(
            '--hc-aside-top',
            `${topSticky}px`
        );

        ultimoScroll = scrollActual;
        ticking = false;
    }


    window.addEventListener('scroll', function () {

        if (!ticking) {

            window.requestAnimationFrame(
                actualizarSticky
            );

            ticking = true;
        }
    });


    window.addEventListener('resize', function () {
        calcularLimites();
        ultimoScroll = window.scrollY;
    });


    calcularLimites();

});


// Desplegables de vehículos en la página de Alquiler.
document.addEventListener('click', function (event) {

    const boton = event.target.closest('.hc-alquiler-titulo');

    if (!boton) return;

    const bloque = boton.closest('.hc-alquiler-desplegable');

    if (!bloque) return;

    const abierto = bloque.classList.toggle('activo');

    boton.setAttribute(
        'aria-expanded',
        abierto ? 'true' : 'false'
    );

});


// Tarifas interactivas de la página Alquiler.
// Cambia el precio de cada vehículo según la temporada seleccionada.

document.addEventListener('click', function (event) {

    const boton = event.target.closest('.hc-temporada-btn');

    if (!boton) return;

    const temporada = boton.dataset.temporada;

    const botones = document.querySelectorAll('.hc-temporada-btn');
    const precios = document.querySelectorAll('.hc-precio');

    botones.forEach(function (item) {
        item.classList.remove('activo');
        item.setAttribute('aria-pressed', 'false');
    });

    boton.classList.add('activo');
    boton.setAttribute('aria-pressed', 'true');

    precios.forEach(function (precio) {
        precio.textContent = precio.dataset[temporada];
    });

});




 // =========================================================
// ALQUILER · SISTEMA DE RESERVAS
// Vehículo + calendario + disponibilidad + temporadas
// + cálculo automático del precio.
// =========================================================

document.addEventListener('DOMContentLoaded', function () {

    const calendarioDias = document.querySelector('.hc-calendario-dias');
    const tituloMes = document.querySelector('.hc-calendario-mes');
    const botonAnterior = document.querySelector('.hc-calendario-anterior');
    const botonSiguiente = document.querySelector('.hc-calendario-siguiente');

    const resumenVehiculo = document.getElementById('hc-resumen-vehiculo');
    const resumenInicio = document.getElementById('hc-resumen-inicio');
    const resumenFin = document.getElementById('hc-resumen-fin');
    const resumenDias = document.getElementById('hc-resumen-dias');
    const resumenTemporada = document.getElementById('hc-resumen-temporada');
    const resumenPrecio = document.getElementById('hc-resumen-precio');

    const botonContinuar = document.querySelector('.hc-reserva-continuar');

    if (
        !calendarioDias ||
        !tituloMes ||
        !botonAnterior ||
        !botonSiguiente
    ) {
        return;
    }


    // =====================================================
    // ESTADO DE LA RESERVA
    // =====================================================

    let fechaActual = new Date();

    let vehiculoActual = 't6';

    let fechaInicio = null;
    let fechaFin = null;


    // =====================================================
    // NOMBRES DE VEHÍCULOS
    // =====================================================

    const nombresVehiculos = {

        t6: 'Volkswagen T6',

        l2h2: 'Fiat Ducato L2H2',

        l3h2: 'Fiat Ducato L3H2',

        sprinter: 'Mercedes Sprinter L3H2'

    };


    // =====================================================
    // TARIFAS POR VEHÍCULO Y TEMPORADA
    // =====================================================

    const tarifas = {

        t6: {
            baja: 95,
            media: 110,
            alta: 125
        },

        l2h2: {
            baja: 110,
            media: 125,
            alta: 145
        },

        l3h2: {
            baja: 125,
            media: 145,
            alta: 165
        },

        sprinter: {
            baja: 145,
            media: 165,
            alta: 190
        }

    };


    // =====================================================
    // FECHAS OCUPADAS
    // =====================================================

    const fechasOcupadas = {

        t6: [
            '2026-10-08',
            '2026-10-09',
            '2026-10-10',
            '2026-10-18',
            '2026-10-19'
        ],

        l2h2: [
            '2026-10-12',
            '2026-10-13',
            '2026-10-14',
            '2026-10-26',
            '2026-10-27'
        ],

        l3h2: [
            '2026-10-05',
            '2026-10-06',
            '2026-10-07',
            '2026-10-21',
            '2026-10-22',
            '2026-10-23'
        ],

        sprinter: [
            '2026-10-15',
            '2026-10-16',
            '2026-10-17',
            '2026-10-28',
            '2026-10-29'
        ]

    };


    const nombresMeses = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];


    // =====================================================
    // CONVERTIR FECHA A YYYY-MM-DD
    // =====================================================

    function convertirFechaISO(fecha) {

        return [
            fecha.getFullYear(),
            String(fecha.getMonth() + 1).padStart(2, '0'),
            String(fecha.getDate()).padStart(2, '0')
        ].join('-');

    }


    // =====================================================
    // FORMATEAR FECHA PARA EL RESUMEN
    // =====================================================

    function formatearFecha(fecha) {

        return fecha.toLocaleDateString('es-ES', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

    }


    // =====================================================
    // SABER LA TEMPORADA DE UNA FECHA
    // =====================================================

    function obtenerTemporada(fecha) {

        const mes = fecha.getMonth() + 1;
        const dia = fecha.getDate();


        // TEMPORADA ALTA
        // 21 diciembre - 7 enero
        // 15 junio - 15 septiembre

        if (
            (mes === 12 && dia >= 21) ||
            (mes === 1 && dia <= 7) ||
            (mes === 6 && dia >= 15) ||
            mes === 7 ||
            mes === 8 ||
            (mes === 9 && dia <= 15)
        ) {
            return 'alta';
        }


        // TEMPORADA BAJA
        // 8 enero - 31 marzo
        // 1 noviembre - 20 diciembre

        if (
            (mes === 1 && dia >= 8) ||
            mes === 2 ||
            mes === 3 ||
            mes === 11 ||
            (mes === 12 && dia <= 20)
        ) {
            return 'baja';
        }


        // El resto es temporada media.
        return 'media';

    }


    // =====================================================
    // COMPROBAR SI EL RANGO ESTÁ DISPONIBLE
    // =====================================================

    function rangoDisponible(inicio, fin) {

        const fechaComprobacion = new Date(inicio);

        while (fechaComprobacion <= fin) {

            const fechaISO = convertirFechaISO(fechaComprobacion);

            if (
                fechasOcupadas[vehiculoActual] &&
                fechasOcupadas[vehiculoActual].includes(fechaISO)
            ) {
                return false;
            }

            fechaComprobacion.setDate(
                fechaComprobacion.getDate() + 1
            );

        }

        return true;

    }


    // =====================================================
    // CALCULAR RESERVA
    // =====================================================

    function calcularReserva() {

        if (!fechaInicio || !fechaFin) {

            return {
                dias: 0,
                precio: 0,
                temporadas: []
            };

        }


        let precioTotal = 0;

        const temporadasUsadas = new Set();

        const fechaCalculo = new Date(fechaInicio);


        /*
         * Se cobra desde el día de recogida
         * hasta el día anterior a la devolución.
         *
         * Ejemplo:
         * recogida 10 octubre
         * devolución 15 octubre
         * = 5 días de alquiler.
         */

        while (fechaCalculo < fechaFin) {

            const temporada = obtenerTemporada(fechaCalculo);

            temporadasUsadas.add(temporada);

            precioTotal += tarifas[vehiculoActual][temporada];

            fechaCalculo.setDate(
                fechaCalculo.getDate() + 1
            );

        }


        const diferencia =
            fechaFin.getTime() - fechaInicio.getTime();

        const dias =
            Math.round(
                diferencia / (1000 * 60 * 60 * 24)
            );


        return {

            dias: dias,

            precio: precioTotal,

            temporadas: Array.from(temporadasUsadas)

        };

    }


    // =====================================================
    // NOMBRE DE TEMPORADA
    // =====================================================

    function nombreTemporada(temporada) {

        const nombres = {
            baja: 'Baja',
            media: 'Media',
            alta: 'Alta'
        };

        return nombres[temporada];

    }


    // =====================================================
    // ACTUALIZAR RESUMEN
    // =====================================================

    function actualizarResumen() {

        if (resumenVehiculo) {

            resumenVehiculo.textContent =
                nombresVehiculos[vehiculoActual];

        }


        if (resumenInicio) {

            resumenInicio.textContent =
                fechaInicio
                    ? formatearFecha(fechaInicio)
                    : '—';

        }


        if (resumenFin) {

            resumenFin.textContent =
                fechaFin
                    ? formatearFecha(fechaFin)
                    : '—';

        }


        const reserva = calcularReserva();


        if (resumenDias) {

            resumenDias.textContent =
                reserva.dias > 0
                    ? reserva.dias
                    : '—';

        }


        if (resumenTemporada) {

            if (reserva.temporadas.length === 0) {

                resumenTemporada.textContent = '—';

            }

            else if (reserva.temporadas.length === 1) {

                resumenTemporada.textContent =
                    nombreTemporada(
                        reserva.temporadas[0]
                    );

            }

            else {

                resumenTemporada.textContent =
                    reserva.temporadas
                        .map(nombreTemporada)
                        .join(' + ');

            }

        }


        if (resumenPrecio) {

            resumenPrecio.textContent =
                reserva.precio > 0
                    ? reserva.precio
                    : '—';

        }


        const resumenSenal =
    document.getElementById('hc-resumen-senal');

if (resumenSenal) {

    const senal =
        reserva.precio > 0
            ? reserva.precio * 0.25
            : 0;

    resumenSenal.textContent =
        senal > 0
            ? senal.toFixed(2).replace('.', ',')
            : '—';

};


        if (botonContinuar) {

            botonContinuar.disabled =
                !(fechaInicio && fechaFin);

        }

    }


    // =====================================================
    // RENDERIZAR CALENDARIO
    // =====================================================

    function renderizarCalendario() {

        calendarioDias.innerHTML = '';

        const anio = fechaActual.getFullYear();
        const mes = fechaActual.getMonth();

        tituloMes.textContent =
            `${nombresMeses[mes]} ${anio}`;


        const primerDia =
            new Date(anio, mes, 1);

        const ultimoDia =
            new Date(anio, mes + 1, 0);

        const totalDias =
            ultimoDia.getDate();


        let posicionPrimerDia =
            primerDia.getDay();

        if (posicionPrimerDia === 0) {
            posicionPrimerDia = 7;
        }


        // Espacios vacíos antes del día 1.
        for (
            let i = 1;
            i < posicionPrimerDia;
            i++
        ) {

            const vacio =
                document.createElement('span');

            vacio.classList.add(
                'hc-calendario-dia',
                'vacio'
            );

            calendarioDias.appendChild(vacio);

        }


        // Días del mes.
        for (
            let dia = 1;
            dia <= totalDias;
            dia++
        ) {

            const botonDia =
                document.createElement('button');

            botonDia.type = 'button';

            botonDia.classList.add(
                'hc-calendario-dia'
            );

            botonDia.textContent = dia;


            const fechaDia =
                new Date(anio, mes, dia);

            const fechaISO =
                convertirFechaISO(fechaDia);

            botonDia.dataset.fecha =
                fechaISO;


            // FECHAS PASADAS
const hoy = new Date();

hoy.setHours(0, 0, 0, 0);
fechaDia.setHours(0, 0, 0, 0);

if (fechaDia < hoy) {

    botonDia.classList.add('ocupado');
    botonDia.disabled = true;

}


            // FECHA OCUPADA
            if (
                fechasOcupadas[vehiculoActual] &&
                fechasOcupadas[vehiculoActual].includes(fechaISO)
            ) {

                botonDia.classList.add('ocupado');

                botonDia.disabled = true;

            }


            // FECHA DE RECOGIDA
            if (
                fechaInicio &&
                fechaISO === convertirFechaISO(fechaInicio)
            ) {

                botonDia.classList.add(
                    'seleccionado'
                );

            }


            // FECHA DE DEVOLUCIÓN
            if (
                fechaFin &&
                fechaISO === convertirFechaISO(fechaFin)
            ) {

                botonDia.classList.add(
                    'seleccionado'
                );

            }


            // DÍAS INTERMEDIOS
            if (
                fechaInicio &&
                fechaFin &&
                fechaDia > fechaInicio &&
                fechaDia < fechaFin
            ) {

                botonDia.classList.add(
                    'en-rango'
                );

            }


            botonDia.addEventListener(
                'click',
                function () {

                    seleccionarFecha(fechaDia);

                }
            );


            calendarioDias.appendChild(
                botonDia
            );

        }

    }


    // =====================================================
    // SELECCIONAR FECHAS
    // =====================================================

    function seleccionarFecha(fechaSeleccionada) {

        if (!fechaInicio) {

            fechaInicio =
                fechaSeleccionada;

            fechaFin = null;

        }


        else if (!fechaFin) {

            if (
                fechaSeleccionada <= fechaInicio
            ) {

                fechaInicio =
                    fechaSeleccionada;

                fechaFin = null;

            }

            else {

                if (
                    rangoDisponible(
                        fechaInicio,
                        fechaSeleccionada
                    )
                ) {

                    fechaFin =
                        fechaSeleccionada;

                }

                else {

                    alert(
                        'Ese rango incluye fechas no disponibles para esta camper.'
                    );

                    return;

                }

            }

        }


        else {

            fechaInicio =
                fechaSeleccionada;

            fechaFin = null;

        }


        actualizarResumen();

        renderizarCalendario();

    }


    // =====================================================
    // NAVEGACIÓN DE MESES
    // =====================================================

    botonAnterior.addEventListener(
        'click',
        function () {

            fechaActual.setMonth(
                fechaActual.getMonth() - 1
            );

            renderizarCalendario();

        }
    );


    botonSiguiente.addEventListener(
        'click',
        function () {

            fechaActual.setMonth(
                fechaActual.getMonth() + 1
            );

            renderizarCalendario();

        }
    );


    // =====================================================
    // SELECCIÓN DE VEHÍCULO
    // =====================================================

    const botonesVehiculo =
        document.querySelectorAll(
            '.hc-reserva-vehiculo-btn'
        );


    botonesVehiculo.forEach(
        function (boton) {

            boton.addEventListener(
                'click',
                function () {

                    vehiculoActual =
                        boton.dataset.vehiculo;


                    botonesVehiculo.forEach(
                        function (item) {

                            item.classList.remove(
                                'activo'
                            );

                            item.setAttribute(
                                'aria-pressed',
                                'false'
                            );

                        }
                    );


                    boton.classList.add('activo');

                    boton.setAttribute(
                        'aria-pressed',
                        'true'
                    );


                    // Cambiar de vehículo
                    // reinicia las fechas.
                    fechaInicio = null;
                    fechaFin = null;


                    actualizarResumen();

                    renderizarCalendario();

                }
            );

        }
    );



// =====================================================
// ABRIR FORMULARIO FINAL DE RESERVA
// =====================================================

if (botonContinuar) {

    botonContinuar.addEventListener('click', function () {

        const formularioReserva =
            document.querySelector('.hc-reserva-formulario');

        const campoVehiculo =
            document.getElementById('hc-form-vehiculo');

        const campoInicio =
            document.getElementById('hc-form-inicio');

        const campoFin =
            document.getElementById('hc-form-fin');

        const campoDias =
            document.getElementById('hc-form-dias');

        const campoPrecio =
            document.getElementById('hc-form-precio');


        if (
            !formularioReserva ||
            !fechaInicio ||
            !fechaFin
        ) {
            return;
        }


        const reserva = calcularReserva();


        // Copiamos los datos seleccionados
        // a los campos ocultos del formulario.

        if (campoVehiculo) {
            campoVehiculo.value =
                nombresVehiculos[vehiculoActual];
        }

        if (campoInicio) {
            campoInicio.value =
                convertirFechaISO(fechaInicio);
        }

        if (campoFin) {
            campoFin.value =
                convertirFechaISO(fechaFin);
        }

        if (campoDias) {
            campoDias.value =
                reserva.dias;
        }

        if (campoPrecio) {
            campoPrecio.value =
                reserva.precio;
        }


        // Mostramos el formulario.
        formularioReserva.hidden = false;


        // Scroll suave hasta el formulario.
        formularioReserva.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

    });

}


    // =====================================================
    // PRIMERA CARGA
    // =====================================================

    actualizarResumen();

    renderizarCalendario();

});


// =====================================================
// MODAL · PAGO CONFIRMADO
// =====================================================

document.addEventListener('click', function (event) {

    const modal =
        document.getElementById('hc-pago-modal');

    if (!modal) return;


    if (
        event.target.closest('.hc-pago-modal-cerrar') ||
        event.target.closest('.hc-pago-modal-boton') ||
        event.target === modal
    ) {

        modal.remove();

    }

});


/* ==========================================================
   FAQ · ACORDEÓN
========================================================== */

document.addEventListener("click", function (event) {

    const boton = event.target.closest(".hc-faq-pregunta");

    if (!boton) {
        return;
    }

    const item = boton.closest(".hc-faq-item");

    if (!item) {
        return;
    }

    const abierto = item.classList.toggle("activo");

    boton.setAttribute(
        "aria-expanded",
        abierto ? "true" : "false"
    );

});

