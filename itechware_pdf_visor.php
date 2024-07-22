<?php
/*
Plugin Name: PDF Visor
Description: El plugin "PDF Visor" permite a los usuarios de WordPress insertar un visor de PDF interactivo directamente en su contenido con solo un clic. Una vez activado, el plugin agrega un botón en el editor de WordPress que, al ser clicado, abre la biblioteca de medios para seleccionar un archivo PDF. Al seleccionar el archivo, se genera automáticamente un elemento HTML <object> que incrusta el PDF en el contenido, facilitando la visualización directa del documento en la página. Este plugin es ideal para quienes desean mostrar documentos PDF de manera rápida y sencilla dentro de sus publicaciones o páginas de WordPress.
Version: 1.0.0
Author: Ismael Flores Rubio
Author URI: https://itechware.com
License: GPL2
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class Itechware_PDF_Visor {
    private $version = '1.0.0';
    public function __construct() {
        add_action( 'media_buttons', array( $this, 'itechware_pdf_visor_media_buttons' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'itechware_pdf_visor_enqueue_admin_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'itechware_pdf_visor_enqueue_frontend_assets' ) );
        add_shortcode( 'itw_pdf_visor', array( $this, 'itechware_pdf_shortcode' ) );
    }
    public function itechware_pdf_visor_media_buttons() {
        echo '<button type="button" id="itechware-pdf-visor-button" class="button"><span class="pdf-visor-icon"></span>Añadir documento PDF</button>';
    }
    public function itechware_pdf_visor_enqueue_admin_assets( $hook ) {
        if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
            wp_enqueue_style( 'itechware_pdf_visor_admin_style', plugin_dir_url( __FILE__ ) . 'assets/admin-style.css', array(),  $this->version );
            wp_enqueue_script( 'itechware_pdf_visor_admin_script', plugin_dir_url( __FILE__ ) . 'assets/admin-script.js', array( 'jquery', 'media-editor' ), $this->version, true );
        }
    }
    public function itechware_pdf_visor_enqueue_frontend_assets() {
        if ( is_singular() && has_shortcode( get_post()->post_content, 'itw_pdf_visor' ) ) {
            wp_enqueue_style( 'itechware_pdf_visor_frontend_style', plugin_dir_url( __FILE__ ) . 'assets/style.css', array(), $this->version );
        }
    }
    public function itechware_pdf_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'src' => '',
            'width' => '100%',
            'height' => '100%',
        ), $atts, 'itw_pdf_visor' );
        
        return '<object class="itechware-pdf-visor" data="' . esc_url( $atts['src'] ) . '" type="application/pdf" width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '">
                    <p>Tu navegador no tiene un visor de PDF compatible. Puedes descargar el PDF para verlo: <a href="' . esc_url( $atts['src'] ) . '">Descargar PDF</a>.</p>
                </object>';
    }
}
new Itechware_PDF_Visor();