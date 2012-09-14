<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="de">
<head>
<title><?php echo $title;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<?php echo link_tag("styles/default.css")."\n";?>
<?php
if( isset( $link_tag_list ) ) {
	foreach( $link_tag_list as &$link_tag ) {
		echo link_tag( $link_tag )."\n";
	}
}
if( isset( $script_tag_list ) ) {
	foreach( $script_tag_list as &$script_tag ) {
		echo script_tag( $script_tag )."\n";
	}
}
?>
<?php if( isset( $script_inline_list ) ) :?>
<script type="text/javascript">
<?php echo implode("\n", $script_inline_list )."\n";?>
</script>
<?php endif;?>
<?php if( isset( $script_onready_list ) ) :?>
<script type="text/javascript">
$(function() {
<?php echo implode("\n", $script_onready_list )."\n";?>
});
</script>
<?php endif;?>
</head>

<body>

<div id="header">
<a href="<?php echo site_url("home");?>">
Logo
</a>
<div class="menu">
<a href="<?php echo site_url("home");?>">Home</a>
<a href="<?php echo site_url("backend/content/edit/2");?>">Edit</a>
</div>
</div>

<div id="content">
<?php echo $content;?>
</div>

<div id="footer">
TinyCMS by Wilfried Reiter is great!
</div>

</body>
</html>