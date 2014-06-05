<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/config/Config.php');
include(BASEPATH.'/includes/ApplicationTop.php');
?>
<?php if(isset($_POST['make_id']) && !empty($_POST['make_id'])): ?>
	<?php $mQuery = mysql_query("SELECT * FROM model WHERE make_id='".$_POST['make_id']."' ORDER BY model ASC"); ?>
	<select name="model_id" id="model" onchange="javascript: ajaxRequest('<?php echo BASEURL; ?>/ajax/getCategory.php', 'make_id=<?php echo $_POST['make_id']; ?>&model_id=' + this.value, '#category-container'); return false;">
		<option value="">Model</option>
		<?php while($mResults = mysql_fetch_array($mQuery, MYSQL_ASSOC)): ?>
			<option value="<?php echo $mResults['model_id']; ?>"><?php echo html_entity_decode($mResults['model'], ENT_QUOTES); ?></option>
		<?php endwhile; ?>
	</select>
<?php endif; ?>