<?php

/*
Plugin Name: Droide List Category
Description: Possíbilita criar uma listagem de cateogiras para o wordPress
Version: 0.1
License: GPL
Author: André Luiz Pereira
Author URI: https://www.facebook.com/androidemastercode/
License: GPLv2

 *      Copyright 2018 Aluno da Escola WordPress <email@exemplo.org>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
*/

function custom_add_menu_meta_box( $object ) {
	add_meta_box( 'custom-menu-metabox', 'Lista de Categorias', 'custom_menu_meta_box', 'nav-menus', 'side', 'low' );
	return $object;
}

add_filter( 'nav_menu_meta_box_object', 'custom_add_menu_meta_box', 10, 1);

function custom_menu_meta_box(){
	global $nav_menu_selected_id;
	$walker = new Walker_Nav_Menu_Checklist();

	$categorias = get_categories( array(
		'orderby' => 'name',
		'order'   => 'ASC'
	));


	$list_categories =[];

	$categorias = array_map('wp_setup_nav_menu_item',$categorias);

	$getlanguage = pll_languages_list(array('current_lang'=>true));
	
	foreach($categorias as &$categoria)
	{

		$language = pll_get_term_language($categoria->term_id, 'slug');
		$site = get_site_url();

		$url = $site.'/'.$language.'/categorias/'.$categoria->slug.'/';

		$categoria->classes = array('blog-style');
		$categoria->type = 'custom';
		$categoria->object_id = $categoria->name;
		$categoria->title = $categoria->name;
		$categoria->object = 'custom';
		$categoria->url =$url; 
		//$categoria->attr_title = 'teste-aqui';

	}
	

	?>

	<div id="authorarchive" class="categorydiv">
	<ul id="authorarchive-tabs" class="authorarchive-tabs add-menu-item-tabs">
		<li class="tabs">
			<a class="nav-tab-link" data-type="tabs-panel-authorarchive-all" href="#">
				Categorias
			</a>
		</li><!-- /.tabs -->
	</ul>

	<div id="tabs-panel-authorarchive-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
		<ul id="authorarchive-checklist-all" class="categorychecklist form-no-clear">
		
		<?php
		
			echo walk_nav_menu_tree( $categorias, 0, (object) array( 'walker' => $walker) );
		?>
		</ul>

	</div>

	<p class="button-controls wp-clearfix">
	<span class="add-to-menu">
		<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-authorarchive-menu-item" id="submit-authorarchive" />
		<span class="spinner"></span>
	</span>
</p>

	<?php
	// fim andre
}

function generate_page_init()
{
    $url = '//'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $tratarurl = parse_url( $url);
    
	//if(isset($tratarurl['path']) && strpos($tratarurl['path'],'categorias') !== false){	
        if(isset($tratarurl['path']) && preg_match('/categorias\/(.*)/',$tratarurl['path'])){	
        //$dir = plugin_dir_path( __FILE__ );
        $dir = get_template_directory();
		include($dir."/droide_categories_pages/layout-list.php");
		die();
	}
}

add_action( 'wp_loaded', 'generate_page_init' );