<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */
?>
<?php 

$lib = realpath(dirname(__FILE__) . "/../lib");
require_once $lib . "/WufooFormsPluginConfigManager.php";
require_once $lib . "/WufooFormsPluginViewHelper.php";

$viewHelper = new WufooFormsPluginViewHelper();
$configMgr = new WufooFormsPluginConfigManager();
if (isset($_POST['save-config'])) {
	try {
		$configMgr->save($_POST);
		$message = "Saved Configuration";
	}catch (Exception $ex) {
		$message = $ex->getMessage();
	}
}
if (isset($_POST['save-show-forms'])) {
	try {
		$config = (array)$configMgr->load();
		$config = array_merge($config, $_POST);
		$configMgr->save($config);
		$message = "Saved Configuration";
	}catch (Exception $ex) {
		$message = $ex->getMessage();
	}
}

$forms = array();
$config = $configMgr->load();
if (empty($config)) {
	$message = "Update Configuration";
}else{
	try {
		$forms = $viewHelper->getActiveForms();
	}catch (Exception $ex) {
		$message = $ex->getMessage();
	}
}
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<p><?=$message?></p>

	<h2>Wufoo Configuration</h2>
	<form action="" method="post">
		<label>Wufoo API Key</label>
		<input type="text" name="wufoo_api_key" value="<?=$config->wufoo_api_key?>" />
		
		<label>Wufoo Subdomain</label>
		<input type="text" name="wufoo_subdomain" value="<?=$config->wufoo_subdomain?>" />
		
		<input type="submit" name="save-config" value="Save" />
	</form>
	
	<h2>The following forms are active. Pick which forms to <strong>Exclude</strong> from your list.</h2>
	<form action="" method="post">
		<table>
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Url</th>
					<th>Email</th>
					<th>Start Date</th>
					<th>End Date</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($forms as $form) { ?>
				<tr>
					<td><input name="hide_forms[]" type="checkbox" <?=(in_array($form->Hash,$config->hide_forms)? "checked" : "")?> value="<?=$form->Hash?>" /></td>
					<td><?=$form->Name?></td>
					<td><?=$form->Url?></td>
					<td><?=$form->Email?></td>
					<td><?=date('F d, Y', strtotime($form->StartDate))?></td>
					<td><?=date('F d, Y', strtotime($form->EndDate))?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	
		<input type="submit" name="save-show-forms" value="Save" />
	</form>
	
</div>
