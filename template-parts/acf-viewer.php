<?php
// echo '1<code>'.json_encode(get_terms(),JSON_PRETTY_PRINT).'</code><br><br>';
// echo '<code>'.json_encode(get_the_post_thumbnail_url(),JSON_PRETTY_PRINT).'</code><br><br>';
// echo '3<code>'.json_encode(get_the_terms(get_queried_object()->ID,"journaux"),JSON_PRETTY_PRINT).'</code><br><br>';
// echo '4<code>'.json_encode(get_object_taxonomies(get_queried_object()->post_type),JSON_PRETTY_PRINT).'</code><br><br>';

// --blue: #007bff;s
// --indigo: #6610f2;
// --purple: #6f42c1;
// --pink: #e83e8c;
// --red: #dc3545;
// --orange: #fd7e14;
// --yellow: #ffc107;
// --green: #28a745;
// --teal: #20c997;
// --cyan: #17a2b8;
$color = [
"#dc3545",
"#fd7e14",
"#ffc107",
"#28a745",
"#20c997",
"#17a2b8"
]
?>
<?php
if($fields = get_field_objects()){
	foreach(acf_get_field_groups(array('post_type' => get_post_type())) as $field_group){
		// echo '<code>'.json_encode(acf_get_fields($field_group["ID"])[0],JSON_PRETTY_PRINT).'</code>';
	}
?>
<div class="uk-card uk-card-default uk-grid-collapse uk-margin" uk-grid>
	<div class="uk-width-1-3@s uk-card-media-left uk-cover-container" uk-lightbox>
		<a href="<?php echo get_the_post_thumbnail_url()?>">
		<div class="uk-height-medium uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light" data-src="<?php echo get_the_post_thumbnail_url()?>" uk-cover uk-img>
			<h1><span uk-icon="icon: search; ratio: 3.5"></span> ZOOM</h1>
		</div>
		</a>
		<canvas width="600" height="400"></canvas>
	</div>
	<div class="uk-width-2-3@s uk-card-body">
		<dl class="uk-description-list uk-description-list-divider">
			<?php
			foreach($fields as $field){
				?>
				<dt class='term'>
					<b><?php echo $field["label"];?></b>
				</dt>
				<dd class="value">
					<?php 
						switch ($field["type"]) {
							case "oembed":
								// echo $field["value"];
								// echo '<code>'.json_encode(get_field($field["name"],false,false),JSON_PRETTY_PRINT).'</code><br><br>';
								echo '<video src="'.get_field($field["name"],false,false).'" uk-video="automute: true; autoplay: false" controls></video>';
							break;
							case "link":
								$link = $field["value"];
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
								echo '<a href="'.esc_url($link_url).'" target="'.esc_attr($link_target).'" >'.esc_html($link_title).'</a>';
							break;
							default:
								echo $field["value"];
							break;
						}
					?>
				</dd>
				<?php
			}
			?>
		</dl>
	</div>
	<div class="uk-card-footer uk-width-expand"><div class="">
			<?php
			foreach(get_object_taxonomies(get_queried_object()->post_type) as $index=>$taxonomy){
				// echo get_the_terms(get_queried_object()->ID,$taxonomy)->name;
				?>
				<style>
				:root {
					--<?php echo $taxonomy.'_taxonomy_color: '.$color[$index]?>;
				}
				</style>
				<?php
				$values = get_the_terms(get_queried_object()->ID,$taxonomy);
				if(is_array($values) || is_object($values)) {
					foreach($values  as $term) {
						// echo '<code>'.json_encode(get_term_link($term),JSON_PRETTY_PRINT).'</code><br><br>';
						?>
						<a href="<?php echo get_term_link($term)?>">
						<span class="uk-label taxonomy" style='background-color: var(<?php echo '--'.$taxonomy.'_taxonomy_color'?>);'>
							<?php echo $term->name ?>
						</span>
						</a>
						<?php
					}
				}
			}
			?>
		</div>
	</div>
</div>
<?php
}
?>