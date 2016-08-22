<?php 
$message_flashdata = $this->session->flashdata('message_flashdata');
if(isset($message_flashdata) && count($message_flashdata))
{ 
?>
	<div class="alert dismissable alert-<?php echo $message_flashdata['type']; ?>"><?php echo $message_flashdata['message']; ?></div>
<?php 
}
 ?>


