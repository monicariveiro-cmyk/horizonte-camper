# Horizonte Camper

Proyecto de portfolio desarrollado tras cursar el Certificado de Profesionalidad en Confección y Publicación de Páginas Web (IFCD0110).

Horizonte Camper es una empresa ficticia dedicada a la camperización, el alquiler de furgonetas y la venta de accesorios. El proyecto comprende el diseño visual, la maquetación, el desarrollo de funcionalidades y la publicación de la web.

🌐 [Ver la web](https://horizontecamper.monicariveiro.es/)

## Tecnologías

WordPress · Hello Elementor · Elementor · WooCommerce · PHP · JavaScript · HTML · CSS · Git · Composer

## Desarrollo propio

El sitio combina herramientas de WordPress con funcionalidades programadas a medida dentro de un tema hijo:

- Formulario de contacto con validación, verificación nonce, sanitización y envío mediante wp_mail().
- Formulario de alquiler con cálculo del importe e integración con Stripe Checkout en modo de prueba.
- Simulador eléctrico para estimar necesidades de equipamiento camper.
- Galerías de trabajos con visor y comparador de imágenes.
- Personalización de la navegación, el carrito y los estilos de WooCommerce.
- Adaptación del diseño a escritorio, tableta y móvil.

## SEO y analítica

- Configuración SEO mediante Rank Math.
- Integración de Google Analytics 4 y Search Console mediante Site Kit.
- Gestión del consentimiento mediante Complianz y WP Consent API.
- Comprobación de cookies de Analytics antes de aceptar, al rechazar y al aceptar.

## Contenido del repositorio

Este repositorio contiene el tema hijo y su código personalizado.

Las páginas de Elementor, los productos, los archivos multimedia y los ajustes de WordPress se gestionan por separado y no están incluidos. El repositorio no constituye una copia completa de la web.

## Instalación del tema

1. Preparar una instalación de WordPress con el tema Hello Elementor.
2. Copiar este repositorio en wp-content/themes/hello-elementor-child.
3. Ejecutar composer install desde la carpeta del tema para instalar las dependencias PHP.
4. Instalar y configurar Elementor y WooCommerce.
5. Activar el tema hijo.

Las funcionalidades requieren sus contenidos y ajustes correspondientes. La integración de Stripe necesita una clave de prueba configurada fuera del repositorio mediante la constante HC_STRIPE_SECRET_KEY.

## Carácter del proyecto

Proyecto demostrativo para portfolio. La empresa es ficticia y los pagos se utilizan exclusivamente en modo de prueba.

## Autora

**Mónica Riveiro González**

Fotógrafa y diseñadora web.

[Web personal](https://www.monicariveiro.es/)