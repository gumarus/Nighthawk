<?php
	$content_layouts = Conductor_Options::get_content_layouts();
?>

<div class="conductor-content-layout-wrap conductor-content-layout-wrap conductor-content-layout-<?php echo $content_layout['field_type'] . '-' . $content_layout['field_id'] . ' ' . $content_layout['field_id'] . ' ' . $content_layout['field_type']; ?>">
	<h4 class="conductor-content-layout-title">
		<?php echo $content_layout['field_label']; ?>

		<?php
			// Edit Link (based on type of content layout)
			$edit_link_url = '';

			switch( $content_layout['field_type'] ) :
				// Built-In
				case 'built-in':
					// Switch field ID
					switch( $content_layout['field_id'] ) :
						// Front Page
						case 'front_page':
							$edit_link_url = trailingslashit( add_query_arg( array( 'url' => home_url() ), wp_customize_url() ) );
						break;
						// Blog
						case 'home':
							$edit_link_url = add_query_arg( array( 'url' => ( $page_for_posts = get_option( 'page_for_posts' ) ) ?  get_permalink( get_option( 'page_for_posts' ) ) : home_url() ), wp_customize_url() );

							// Permalink Structure
							if ( $permalink_structure = get_option( 'permalink_structure' ) )
								$edit_link_url = trailingslashit( $edit_link_url );
						break;
					endswitch;
				break;

				// Category Archive
				case 'category':
					$edit_link_url = add_query_arg( array( 'url' => trailingslashit( home_url() ) . '?cat=' . $content_layout['field_id'] ), wp_customize_url() );
				break;

				// Post Type
				case 'post-type':
					$edit_link_url = add_query_arg( array( 'url' => trailingslashit( get_post_type_archive_link( $content_layout['field_id'] ) ) ), wp_customize_url() );
				break;

				// Single Post
				case 'post':
					$edit_link_url = add_query_arg( array( 'url' => trailingslashit( home_url() ) . '?p=' . $content_layout['field_id'] ), wp_customize_url() );
				break;

				// Single Page
				case 'page':
					$edit_link_url = add_query_arg( array( 'url' => trailingslashit( home_url() ) . '?page_id=' . $content_layout['field_id'] ), wp_customize_url() );
				break;
			endswitch;

			// Allow filtering of the edit link URL
			$edit_link_url = apply_filters( 'conductor_content_layout_edit_link_url', $edit_link_url, $content_layout, $content_layout_id, $content_layouts );
		?>

		<span class="conductor-edit-content-layout-link">- <a href="<?php echo esc_attr( $edit_link_url ); ?>" class="conductor-edit-content-layout" data-field-num="<?php echo $content_layout_id; ?>"><?php _e( 'Conduct', 'conductor' ); ?></a></span>

		<span class="conductor-remove-content-layout-link">- <a href="#remove" class="conductor-remove-content-layout" data-field-num="<?php echo $content_layout_id; ?>"><?php _e( 'Remove', 'conductor' ); ?></a></span>

		<?php do_action( 'conductor_admin_content_layout_action_buttons', $content_layout, $content_layout_id, $content_layouts ); ?>
	</h4>

	<?php do_action( 'conductor_admin_content_layout_before', $content_layout, $content_layout_id, $content_layouts ); ?>

	<?php foreach( $content_layouts as $name => $atts ) : ?>
		<div class="conductor-content-layout conductor-content-layout-<?php echo $name; ?>">
			<label>
				<?php // TODO: issue with duplicate IDs (should be specific to the index #) ?>
				<input type="radio" name="conductor[content_layouts][<?php echo $content_layout_id; ?>][<?php echo $content_layout['field_type']; ?>][<?php echo $content_layout['field_id']; ?>]" value="<?php echo $name; ?>" <?php ( $content_layout['value'] === false && isset( $atts['default'] ) && $atts['default'] ) ? checked( true ) : checked( $content_layout['value'], $name ); ?> <?php echo ( isset( $conductor_content_layout_data_link ) && ! empty( $conductor_content_layout_data_link ) ) ? $conductor_content_layout_data_link : false; ?> />

				<div class="conductor-content-layout-preview">
					<?php
					if ( isset( $atts['preview_values'] ) )
						vprintf( $atts['preview'], $atts['preview_values'] );
					else
						echo $atts['preview'];
					?>
				</div>
			</label>
		</div>
	<?php endforeach; ?>

	<?php do_action( 'conductor_admin_content_layout_after', $content_layout, $content_layout_id, $content_layouts ); ?>
</div>