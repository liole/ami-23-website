<?php
	include 'template.php';
	
	printTop("Чат");
?>
	<?php
		include 'includes/userNames.php';
		echo '<script type="text/javascript"> window.usersData = '.json_encode($ext_users_data).'; </script>';
	?>
	<form action="?new=message" method="post" class="message" onSubmit="return msgPush();">	
		<div class="msgIcon"><img src="images/avatars/<?php echo $currUser["avatar"]; ?>" alt="Я" /></div>
		<textarea name="text" id='messageInput' onKeyUp="textAreaAdjust(this)" onFocus="window.textareaActive = true;" onBlur="window.textareaActive = false;" placeholder="Текст повідомлення...
Для надсилання натисніть Enter, Shift+Enter для нового рядка." class="msgFormText"></textarea>
		<input type="image" src="images/mail.png" class="msgFormSbm" id="submit">
		<input type='hidden' id='nameInput' value="<?php echo $currUser["login"];?>">
	</form>
	<div id='messagesDiv'></div>
	<script>
      var myDataRef = new Firebase('https://crackling-inferno-5897.firebaseIO.com/');
	  var dataQuery = myDataRef.limit(100);
	  
      function msgPush() {
          var name = $('#nameInput').val();
          var text = $('#messageInput').val();
		  if (text.length() == 0) return false;
          myDataRef.push({name: name, text: text, date: Firebase.ServerValue.TIMESTAMP});
          $('#messageInput').val('');
		  return false;
        };
	window.ChatNewNum = 0;
	window.isActive = true;
      dataQuery.on('child_added', function(snapshot) {
        var message = snapshot.val();
        displayChatMessage(message.name, message.text, message.date);
		if (!window.isActive) {
			var notify = new Audio('images/notify.wav'); notify.play();
			changeIcon('new.ico');
			window.ChatNewNum ++;
			document.title = '(' + window.ChatNewNum + ' new) Чат - ПМІ-23';
		}
      });
	  function zeroPad(num, size) {
		var s = num+"";
		while (s.length < size) s = "0" + s;
		return s;
	  }
	 function nl2br( str ) { // Inserts HTML line breaks before all newlines in a string
		return str.replace(/([^>])\n/g, '$1<br/>');
	}

      function displayChatMessage(name, text, msgDate) {
		var msgDate = new Date(msgDate);
		var dateStr = zeroPad(msgDate.getDate(),2) + '.' + zeroPad(1+msgDate.getMonth(),2) + '.' + msgDate.getFullYear() + ' ' + 
			zeroPad(msgDate.getHours(),2) + ':' + zeroPad(msgDate.getMinutes(),2) + ':' + zeroPad(msgDate.getSeconds(),2);
		$("#messagesDiv").prepend('\
		<div class="message chat">\
		<div class="msgIcon"><img src="images/avatars/' + window.usersData[name].avatar + '" alt="' + window.usersData[name].name + '" /></div>\
		<div class="msgInfo">\
			' + dateStr + '\
		</div>\
		<div class="msgName">' + window.usersData[name].name + '</div>\
		<div class="msgText">' + nl2br(text) + '</div>\
	</div>\
		');
      };
	$.fn.ctrlEnter = function (fn) {
		var thiz = $(this); 
		function performAction (e) {
			fn.call(thiz, e);
		}
		thiz.bind("keydown", function (e) {
			if (e.keyCode === 13 && !e.shiftKey) {
				performAction(e);
				e.preventDefault();
			}
		});
	};
	$("#messageInput").ctrlEnter(function () { return msgPush(); });
    </script>
<?php printBottom(); ?>