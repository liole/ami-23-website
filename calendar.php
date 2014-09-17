<?php
	include "template.php";
	include "data.php";

	printTop("Календар", "calendar.css");

	$limit = 5; // till 5 AM use previous day date
	// TODO: why not till 0 AM?
	$timestamp = time();
	if (0+date('H')>=$limit)
		$timestamp = strtotime('tomorrow');
	$dateW = date('W',$timestamp);

	// TODO: the first week of September is the 36-th week of year. However, it should be assigned with 1, as it is the first week of an academic year

	$lipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
?>

<table id="schedule_mainTable">
	<?php foreach ($wdays as $id => $title) { ?>
	<!-- TODO: do not show week days with empty event list -->
		<tr>
			<td class="schedule_dayTitleTd"><div class="schedule_dayTitle"><?= $title ?></div></td>
			<td class="schedule_dayContentTd">
				<?php
					$sql = "SELECT * FROM schedule WHERE day = '$id' ORDER BY num ASC, week ASC";
					if (!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				?>
				<table class="schedule_dayContentTable">
					<?php
						while ($row = $result->fetch_assoc()) { 
							if ($row['week'] == 2 || $row['week'] == $dateW % 2) {
					?>
					<tr>
						<td><?= $row['num'] ?></td>
						<td><?= $stime[$row['num']] ?></td>
						<td><?= $row['title'] ?></td>
						<td><?= $row['type'] ?></td>
						<td><?= $row["aud1"] ?><?php echo (($row["aud2"]!='0')?$row["aud2"]:''); ?></td>
						<td><?= $row["teacher1"] ?><?php echo (($row["teacher2"]!='0')?",</br>".$row["teacher2"]:''); ?></td>
						<?php
							$sql = "SELECT text FROM info WHERE month = 9 AND day = 2 AND num = 1";
							if (!$res = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
							$info = $res->fetch_assoc()["text"];
						?>
						<!-- TODO: replace info button with corresponding set of indicators -->
						<td><?php echo "<button onClick=\"popupInfo('$info')\">Info</button>"; ?></td>
					</tr>
					<?php }} ?>
				</table>
			</td>
		</tr>
	<?php } ?>
</table>

<!--dialog -->

<div id="schedule_dialogOverlay"></div>
<div id="schedule_dialogBox">
    <div class="schedule_dialogContent">
        <div id="schedule_dialogMessage"></div>
        <table id="schedule_dialogButtonsTable">
        	<tr>
		        <td><a href="#" class="schedule_dialogButton" id="schedule_dialogButtonClose">Close</a></td>
		        <td><a href="#" class="schedule_dialogButton" id="schedule_dialogButtonEdit">Edit</a></td>
		        <td><a href="#" class="schedule_dialogButton" id="schedule_dialogButtonSave">Save</a></td>
		        <td><a href="#" class="schedule_dialogButton" id="schedule_dialogButtonCancel">Cancel</a></td>
		    </tr>
    	</table>
    </div>
</div>

<script>
	$(document).ready(function () {
	    // if user clicked on close button, the overlay layer or the dialogbox, close the dialog    
	    $('#schedule_dialogButtonClose, #schedule_dialogOverlay').click(function () {        
	        $('#schedule_dialogOverlay, #schedule_dialogBox').hide();        
	        return false;
	    });

	    // if user clicked on edit button, hide the edit and close buttons
	    // show save and cancel buttons, make text editable
	    $('#schedule_dialogButtonEdit').click(function () {
	        $('#schedule_dialogButtonClose').hide(); 
	        $('#schedule_dialogButtonEdit').hide();
	        $('#schedule_dialogButtonSave').show();
	        $('#schedule_dialogButtonCancel').show();
	        // TODO: make text editable    
	        return false;
	    });

	    // TODO: use save button to insert text into the info table
	    // TODO: use cancel button to close the editing mode
	    // TODO: delete the corresponding row from info table if it appeared empty after editing
	    // popup the confirmation dialog before deleting
	    
	    // if user resize the window, call the same function again
	    // to make sure the overlay fills the screen and dialogbox aligned to center    
	    $(window).resize(function () {
	        
	        //only do it if the dialog box is not hidden
	        if (!$('#schedule_dialogBox').is(':hidden')) popup();
	    });          
	});

	function popupInfo(message) {
		// get the screen height and width  
	    var maskHeight = $(document).height();  
	    var maskWidth = $(window).width();
	    
	    // calculate the values for center alignment
	    var dialogTop =  (maskHeight/3) - ($('#schedule_dialogBox').height());  
	    var dialogLeft = (maskWidth/2) - ($('#schedule_dialogBox').width()/2); 
	    
	    // assign values to the overlay and dialog box
	    $('#schedule_dialogOverlay').css({height:maskHeight, width:maskWidth}).show();
	    $('#schedule_dialogBox').css({top:dialogTop, left:dialogLeft}).show();
	    
	    // display the message
	    $('#schedule_dialogMessage').html(message);
	}
</script>

<!--/dialog -->

<?php
	printBottom();
?>