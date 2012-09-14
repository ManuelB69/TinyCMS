<div class="content_edit_header">
<h3><?php echo "Content \"".$name."\"";?></h3>
</div>

<div class="content_edit">
<form action="<?php echo $action_url;?>" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" target="<?php echo $action_target;?>" >

<!-- add hidden input parameter -->
<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="lang_id" value="<?php echo $lang_id;?>" />
<input type="hidden" name="label_ids" value="<?php echo implode(',', $label_ids );?>" />
<?php if( isset( $label_text_ids ) && count( $label_text_ids ) ): ?>
<input type="hidden" name="label_text_ids" value="<?php echo implode(',', $label_text_ids );?>" />
<?php endif;?>
<?php if( isset( $label_image_ids ) && count( $label_image_ids ) ): ?>
<input type="hidden" name="label_image_ids" value="<?php echo implode(',', $label_image_ids );?>" />
<?php endif;?>
<?php if( isset( $label_link_ids ) && count( $label_link_ids ) ): ?>
<input type="hidden" name="label_link_ids" value="<?php echo implode(',', $label_link_ids );?>" />
<?php endif;?>

<!-- list simple labels with short text only BEGIN -->
<?php if( isset( $simple_labels ) && count( $simple_labels ) ): ?>
<div class="simple_label">
<table>
<?php foreach( $simple_labels as $label ): ?>
<?php $labelText = $label['text'];?>
	<tr>
	<td><label><?php echo $label['name'].":";?></label></td>
	<td><input type="text" name="<?php printf("text_%d", $labelText['id'] );?>" value="<?php echo $labelText['text'];?>" /></td>
	</tr>
<?php endforeach;//( $simple_labels as ..)?>
</table>
</div>
<?php endif;?>
<!-- list simple labels with short text only END -->


<?php if( isset( $paragraph_labels ) && count( $paragraph_labels ) ): ?>
<?php foreach( $paragraph_labels as &$label ): ?> 
<!-- list paragraph label with text, images and links BEGIN -->
<div class="paragraph_label">

<!-- paragraph header-->
	<h4><?php echo $label['name'].":";?></h4>

<!-- paragraph text-->
	<?php $labelText = $label['text'];?> 
	<textarea cols="" rows="5" name="<?php printf("text_%d", $labelText['id'] );?>"><?php echo $labelText['text'];?></textarea>

	<?php if( isset( $label['images'] ) && count( $label['images'] ) ): ?> 
<!-- paragraph images BEGIN -->
	<div class="images">
	<?php foreach( $label['images'] as &$labelImage ): ?> 
		<a href="#" id="image_<?php echo $labelImage['id'];?>" title="edit settings for image #<?php echo $labelImage['listid'];?>">
		<img src="<?php echo $labelImage['src_thumb'];?>"
			alt="<?php echo $labelImage['src'];?>" />
		</a>
	<?php endforeach;?>
	<br class="clear" />
	</div>
<!-- paragraph images END -->
	<?php endif;?>

	<?php if( isset( $label['links'] ) && count( $label['links'] ) ): ?> 
<!-- paragraph links BEGIN -->
	<div class="links">
		<h4>Links:</h4>
		<ol>
			<?php foreach( $label['links'] as &$labelLink ): ?>
			<?php $linkTitle = isset( $labelLink['title'] ) ? $labelLink['title']['text'] : $labelLink['href'];?> 
			<li>
			<a href="<?php echo $labelLink['href'];?>" target="_blank" title="<?php echo $labelLink['href'];?>"><?php echo $linkTitle;?></a>
			<span class="separator">&nbsp;/&nbsp;</span>
			<a href="#" title="edit settings for this link" class="edit" id="link_<?php echo $labelLink['id'];?>">Edit</a>
			</li>
			<?php endforeach;?> 
		</ol>
	</div>
<!-- paragraph links END -->
	<?php endif;?>

	<?php if( isset( $label['links'] ) && count( $label['links'] ) ): ?> 
	<?php foreach( $label['links'] as &$labelLink ): ?> 
<!-- paragraph link properties BEGIN -->
	<div class="link_property" id="link_property_<?php echo $labelLink['id'];?>">
		<span class="url">
		<label>Address:</label>
		<input type="text" name="<?php printf("link_%d", $labelLink['id'] );?>" value="<?php echo $labelLink['href'];?>" />
		</span><br/>
		<span class="title">
		<label>Title:</label>
		<?php if( isset( $labelLink['title'] ) ): ?> 
		<input type="text" name="<?php printf("text_%d", $labelLink['title']['id'] );?>" value="<?php echo $labelLink['title']['text'];?>" />
		<?php else:?> 
		<input type="text" name="<?php printf("link_create_title_%d", $labelLink['id'] );?>" value="" />
		<?php endif;?> 
		</span>
	</div>
<!-- paragraph link properties END -->
	<?php endforeach;?>
	<?php endif;?>

	<?php if( isset( $label['images'] ) && count( $label['images'] ) ): ?>
	<?php foreach( $label['images'] as &$labelImage ): ?>
<!-- paragraph image properties BEGIN -->
	<div class="image_property" id="image_property_<?php echo $labelImage['id'];?>">
	<input type="hidden" name="<?php printf("image_align_%d", $labelImage['id'] );?>" value="<?php echo $labelImage['align'];?>" />
	<table><tr>
		<td><a class="icon" href="#"><span class="icon icon_align_left"></span></a></td>
		<td><a class="icon" href="#"><span class="icon icon_align_right"></span></a></td>
		<td><a class="icon" href="#"><span class="icon icon_align_center"></span></a></td>
		<td class="title">
		<label>Title:</label>
		<?php if( isset( $labelImage['title'] ) ): ?>
		<input type="text" name="<?php printf("text_%d", $labelImage['title']['id'] );?>" value="<?php echo $labelImage['title']['text'];?>" />
		<?php else:?>
		<input type="text" name="<?php printf("image_create_title_%d", $labelImage['id'] );?>" value="" />
		<?php endif;?>
		</td>
	</tr></table>
	</div>
<!-- paragraph image properties END -->
	<?php endforeach;?>
	<?php endif;?>

</div>
<!-- list paragraph label with text, images and links END -->
<?php endforeach;?>
<?php endif;?>

<!-- submit button -->
<input type="submit" name="submit" value="Save"/>

</form>
</div>
