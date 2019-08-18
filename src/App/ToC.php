<?php

namespace EditormdApp;

class ToC
{
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'toc_enqueue_scripts' ) );
        add_action( 'wp_print_footer_scripts', array( $this, 'toc_wp_footer_scripts' ) );
    }

    public function toc_enqueue_scripts()
    {
        // Only single page will display TOC.
        if ( ! is_single() && ! is_page() ) {
            return;
        }

        //兼容模式 - jQuery
        if ( $this->get_option( 'jquery_compatible', 'editor_advanced' ) !== 'off' ) {
            wp_enqueue_script( 'jquery', null, null, array(), false );
        } else {
            wp_deregister_script( 'jquery' );
            wp_enqueue_script( 'jQuery-CDN', $this->get_option('editor_addres','editor_style') . '/assets/jQuery/jquery.min.js', array(), WP_EDITORMD_VER, true );
        }

        wp_enqueue_script('ToC', WP_EDITORMD_URL . '/assets/lib/jquery.toc.min.js', [], WP_EDITORMD_VER, true);
    }

    public function toc_wp_footer_scripts()
    {
        // Only single page will display TOC.
        if ( ! is_single() && ! is_page() ) {
            return;
        }

        $toc_container = $this->get_option('toc_container', 'editor_toc');

        $script = '
			<script id="module-toc">
				(function($) {
					$(function() {
		';

        $script .= '
            $("'.$toc_container.'").initTOC({
                selector: "h1, h2, h3, h4, h5, h6",
                scope: "article.post .content",
            });
        ';

        $script .= '
					});
				})(jQuery);
			</script>
		';

        echo preg_replace( '/\s+/', ' ', $script );
    }

    /**
     * 获取字段值
     *
     * @param string $option  字段名称
     * @param string $section 字段名称分组
     * @param string $default 没搜索到返回空
     *
     * @return mixed
     */
    private function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[ $option ] ) ) {
            return $options[ $option ];
        }

        return $default;
    }
}