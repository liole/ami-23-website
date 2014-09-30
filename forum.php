<?php
	include 'template.php';

	if (isset($_POST["text"]))
	{
		$sql = "INSERT INTO forum_messages (topic_id,author,date,text) VALUES (
			".$_POST["topic_id"].",
			'".$_COOKIE["user"]."', 
			'".date("Y-m-d H:i:s")."', 
			'".str_replace("'", "''", $_POST["text"])."')";
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		else {
			header('Location: forum.php#topic/'.$_POST["topic_id"]);
			exit;
		}
	}
	//TODO: messages edit/delete + topics add/delete
	/* ... basic operations with forum ...
	if (isset($_GET["delete"]))
	{
		$sql = "SELECT user FROM ".$_GET["delete"]." WHERE id = ".$_GET["id"];
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		if ($row["user"] == $currUser["login"] || $currUser["type"] == 'admin')
		{
			$sql = "DELETE FROM ".$_GET["delete"]." WHERE id = ".$_GET["id"];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			if ($_GET["delete"] == 'messages') { //delete all discucss messages for this post
				$sql = "DELETE FROM discuss WHERE msg_id = ".$_GET["id"];
				if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			}
		}
		if ($_GET["delete"] == 'messages' && strpos($_SERVER['HTTP_REFERER'],'discuss') !== false) //not return to deleted page
			header('Location: index.php');
		else header('Location: '.$_SERVER['HTTP_REFERER']); //anyway return to previous page
		exit;
	}
	if (isset($_GET["save"]))
	{
		$sql = "SELECT * FROM ".$_GET["save"]." WHERE id = ".$_GET["id"];
		if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		$row = $result->fetch_assoc();
		if ($row["user"] == $currUser["login"] || $currUser["type"] == 'admin')
		{
			$sql = "UPDATE ".$_GET["save"]." SET 
				text = '".str_replace("'", "''", $_POST["text"])."'";
			if ($_GET["save"] == "messages") {
				$sql .= ", attach = '".$_POST["attach_type"]."', 
				attach_id = '".$_POST["attach_ID"]."'";
			}
			$sql .= " WHERE id = ".$_GET["id"];
			if(!$result = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		}
		$discussID = ($_GET["save"]=='messages')?$_GET["id"]:$row["msg_id"];
		header('Location: ?discuss='.$discussID); 
		exit;
	}
	*/
	printTop("Форум", "forum.css");
?>
<div ng-app="" ng-controller="forumController"> 
	<!--Main page with list of topics-->
	<div class="forum_topic" ng-click="openTopic(topic.id);" ng-repeat="topic in topics" >
		<div class="forum_topicAbout">
			<div class="forum_topicTitle">{{ topic.title }}</div>
			<div class="forum_topicDeskr">{{ topic.description }}</div>
		</div>
		<div class="forum_topicPosts">{{ topic.posts }}</div>
		<div class="forum_topicLastMsg">by {{ users[topic.last.author].name }},
			<div class="forum_topicLastDate">{{ topic.last.date }}</div>
		</div>
	</div>
	<!--Page with list of messages in current topic (using hash url)-->
	<form action="forum.php" method="post" ng-show="displayTopic">
		<script src="nicEdit.js" type="text/javascript"></script>
		<textarea class="forum_textarea" id="text" name="text">Текст вашого повідомлення...</textarea>
		<script type="text/javascript">new nicEditor().panelInstance('text');</script>
		<input type="hidden" name="topic_id" ng-value="topicID">
		<input type="submit" name="submit" value="submit">
	</form>

	<div class="message" ng-repeat="message in messages" >
		<div class="msgIcon"><img ng-src="images/avatars/{{ users[message.author].avatar }}" class="msgIcon"></div>
		<div class="msgInfo">{{ message.date }}</div>
		<div class="msgName">{{ users[message.author].name }}</div>
		<div class="msgText" ng-bind-html="renderHtml(message.text)"></div>
	</div>

</div>


<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js"></script>
<script>
	function forumController($scope,$http,$sce) {
		$http.get("includes/userNames.php?output=true")
			.success(function(response) {$scope.users = response;});

		window.loadMore = function () {
			$http.get("includes/getForumData.php?messages="+$scope.topicID+"&from="+currLoaded+"&limit="+loadLimit)
				.success(function(response) {$scope.messages = $scope.messages.concat(response); currLoaded+=loadLimit});
		};
		window.loadHash = function () {
			var url = location.hash.substr(1).split("/");
			if (url[0] == '') {
				$scope.messages = {};
				$scope.displayTopic = false;
				$scope.topicID = 0;
				$http.get("includes/getForumData.php?topics")
					.success(function(response) {$scope.topics = response;});
			} else if (url[0] == 'topic') {
				$scope.topics = {};
				$scope.displayTopic = true;
				$scope.topicID = url[1];
				$scope.messages = [];
				window.loadLimit = 2;
				window.currLoaded = 0;
				loadMore();
			}
		};
		$(window).scroll(function(){
			if ($(window).scrollTop() + $(window).height() == $(document).height()) 
				loadMore ();
		});
		loadHash(); // initial load on page open
		$(window).on('hashchange', loadHash); // reload every hash change
	
		$scope.openTopic = function (id) {location.hash = 'topic/'+id;};
	  
		$scope.renderHtml = function(html_code) {
			return $sce.trustAsHtml(html_code);
		};
	}
</script>
<?php printBottom(); ?>