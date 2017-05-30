<?php 
/*
Plugin Name: Webmaestro Columns Shortcode
Version: 1.6
Author: Jan Bien
Author URI: http://www.janbien.cz/
Copyright: Jan Bien
*/

if (!defined('ABSPATH')) exit;

class Webmaestro_Columns {

	var $splitter = '-';
	var $shortcode = 'columns';
	var $row_class = 'webmaestro-columns';
	var $column_class = 'webmaestro-column';
	var $columns = 12;
	var $gutter = '20px';
	var $min_width = '768px';
	var $render_styles = true;
	var $unautop = true;

	function __construct() {
		add_action( 'init', array( $this, 'init') );
		add_action( 'wp_head', array( $this, 'wp_head') );
		add_filter( 'the_content', array( $this, 'the_content') );
		add_filter( 'acf_the_content', array( $this, 'the_content'), 11 );
		add_shortcode( $this->shortcode, array( $this, 'shortcode') );
	}

	function init() {
		$config = array(
			'splitter' => '-',
			'shortcode' =>'columns',
			'row_class' => 'webmaestro-columns',
			'column_class' => 'webmaestro-column',
			'columns' => 12,
			'gutter' => '20px',
			'min_width' => '768px',
			'render_styles' => true,
			'unautop' => true
		);
		$config = apply_filters ( 'webmaestro_columns_config', $config );
		$this->splitter = $config['splitter'];
		$this->shortcode = $config['shortcode'];
		$this->row_class = $config['row_class'];
		$this->column_class = $config['column_class'];
		$this->columns = $config['columns'];
		$this->gutter = $config['gutter'];
		$this->min_width = $config['min_width'];
		$this->render_styles = $config['render_styles'];
		$this->unautop = $config['unautop'];
	}

	function the_content( $content ) {
		if ( ! $this->unautop ) return $content;
		$tagregexp = join( '|', array( $this->shortcode, $this->splitter ) );
		$reg_str = '/<(p|div)[^>]*>[\s]*(\[\/?(' . $tagregexp . ')[^\]]*\])[\s]*<\/(p|div)>/si';
		$content = preg_replace( $reg_str, "\\2", $content ); 
		return $content;
	}

	function wp_head() {
		if ( ! $this->render_styles ) return;
		echo '<style>';
		if ( $this->min_width ) echo '@media (min-width: ' . $this->min_width  . ') {'; 
		echo '.' . $this->row_class . ' {margin-left:-' . $this->gutter . ';}';
		echo '.' . $this->row_class . ':after {content:"\0020";display:block;height:0;clear:both;visibility:hidden;}';
		echo '.' . $this->row_class . ' > .' . $this->column_class . '{box-sizing:border-box;padding-left:' . $this->gutter . ';float:left;zoom:1;}';
		for ( $i = 1; $i <= $this->columns; $i++ ) {
			echo '.' . $this->row_class . ' > .' . $this->column_class . '-' . $i . ' {width:' . ( $i / $this->columns * 100 ) . '%}';
			echo '.' . $this->row_class . ' > .' . $this->column_class . '-1-' . $i . ' {width:' . ( 100 / $i ) . '%}';
		}
		if ( $this->min_width ) echo '}';
		echo '</style>';
	}

	function shortcode( $atts, $content ) {
		$atts = shortcode_atts( array(
			0 => '',
			'class' => ''
		), $atts, $this->shortcode);
		$content = str_replace("[{$this->splitter}]", "<!--columns-separator-->", $content);	
		if ( $this->unautop ) {
			$content = preg_replace( '/^(<\/(p|div)>)(.*)(<(p|div)[^>]*>)$/si', "\\3", $content ); 
		}
		$content = trim ( do_shortcode( shortcode_unautop ( $content ) ) );
		$contents = explode( '<!--columns-separator-->' , $content);
		$columns = count( $contents );
		$html = '';
		if ( $atts[0] ) {
			$spans = array();
			$spans = explode( '-', $atts[0] );
			$i = 0;
			$free_spans = $this->columns;
			foreach ( $contents as $key => $item ) {
				if ( isset( $spans[$key] ) ) $span = $spans[$key];
					else $span = round ( $free_spans / ( $columns - $i ) );
				$free_spans = $free_spans - $span;
				$class = $this->column_class . ' ' . $this->column_class . '-' . $span;
				$html .= '<div class="' . $class . '">' . $item . '</div>';
				$i++;
			}
		} else {
			$class = $this->column_class . ' ' . $this->column_class . '-1-' . $columns;
			foreach ( $contents as $key => $item )
				$html .= '<div class="' . $class . '">' . $item . '</div>';
		}
		$class = trim( $this->row_class . ' ' . sanitize_text_field ( $atts['class'] ) );
		return '<div class="' . $class . '">' . $html . '</div>';
	}
}

new Webmaestro_Columns;
