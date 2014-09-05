function moreMenu (elem)
{
	nav = document.getElementsByTagName('nav')[0];
	nav.style.zIndex = 5;
	nav.style.width = '885px';
	elem.style.display = 'none';
	return false;
}
var prevHeight;
function moreMenu2_up (elem)
{
	nav = document.getElementsByTagName('nav')[0];
	nav.style.zIndex = 2;
	nav.style.height = prevHeight;
	elem.innerHTML = '&#x25BC;';
	elem.style.paddingBottom = '0px';
	elem.onclick = function(){moreMenu2(this)};
}
function moreMenu2 (elem)
{
	nav = document.getElementsByTagName('nav')[0];
	nav.style.zIndex = 5;
	prevHeight = nav.style.height;
	nav.style.height = 'auto';
	elem.innerHTML = '&#x25B2;';
	elem.style.paddingBottom = '5px';
	elem.onclick = function(){moreMenu2_up(this)};
}
function checkPass()
{
	if (document.getElementById('password').value==document.getElementById('conf_password').value)
	{
		document.getElementById('submit').disabled = false;
		document.getElementById('conf_password').style.background = '#fff';
	} else {
		document.getElementById('submit').disabled = true;
		document.getElementById('conf_password').style.background = '#f77';
	}
    
}
function textAreaAdjust(o) {
    o.style.height = "0px";
    o.style.height = (o.scrollHeight)+"px";
}

function changeIcon (src)
{
	var link = document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'shortcut icon';
    link.href = src;
    document.getElementsByTagName('head')[0].appendChild(link);
}

window.textareaActive = false;
window.onfocus = function () { 
  window.isActive = true; 
  changeIcon ('favicon.ico');
}; 
window.onblur = function () { 
  window.isActive = false; 
}; 

/* to reload page when new message exhist
 * only in the main page not in the discuss
 * needs rewrite to proper ajax way 
 */
function checkNewMsg()
{
	var xmlhttp;
	if (window.XMLHttpRequest) xmlhttp=new XMLHttpRequest();
	else xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		var lastMsgTimeNow = parseInt(xmlhttp.responseText);
		if (lastMsgTimeNow != window.lastMsgTime && !window.textareaActive)
			location = '?wasActive='+window.isActive+'&newMsg';
    }}
	xmlhttp.open("GET","lastMsgTime.php",true);
	xmlhttp.send();
	
}
	
function addAns (obj)
{
	var newAns = document.createElement('input');
	newAns.type = 'text';
	newAns.name = 'answer[]';
	newAns.className = 'create_poll_ans';
	newAns.placeholder = 'Введіть текст відововіді...';
	obj.parentNode.insertBefore (newAns, obj);
}

function createAlbum ()
{
	var title = prompt ('Заголовок нового альбому:');
	if (title != null) location = '?new_album='+title;
}
function openUploader (name)
{
	name = typeof a !== 'undefined' ? name : '';
	var uploader = window.open ('uploader.php', name, 'width=325, height=225, top=100, left=100, location=no');
}
function showAttachPanel()
{
	document.getElementById ('attach_panel').style.display = 'block';
}
function changeAttach (sel, edit)
{
	if (sel.value =='photo') {
		if (!edit) {
			document.getElementById ('attach_ID').value = '[]';
			openUploader ('embed_upload');
		}
	} else 
		document.getElementById ('attach_ID').readOnly = false;
	if (sel.value == 'file')
		document.getElementById ('attach_comment').innerHTML = ' <a href="javascript: openFileUploader(\'embed_upload\');">завантажити</a>';
	else if (sel.value == 'photo')
		document.getElementById ('attach_comment').innerHTML = ' <a href="javascript: openUploader(\'embed_upload\');">завантажити</a>';
	else document.getElementById ('attach_comment').innerHTML = '';
}
function openFileUploader ()
{
	var uploader = window.open ('fileUploader.php', '', 'width=325, height=150, top=100, left=100, location=no');
}