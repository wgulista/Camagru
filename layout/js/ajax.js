'use strict';

var getHttpRequest = function ()
{
	var xhr = false;

	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
		if (xhr.overrideMimeType)
			xhr.overrideMimeType("text/xml");
	} else if (window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch(e) {
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch(e) {}
		}
	}
	if (!xhr) {
		console.log("Impossible de creer une instance XMLHTTP");
		return false;
	}
	return xhr;
}

function forget_password() {
	var xhr = getHttpRequest();
	var data = new FormData();
	var login_email = document.querySelector('input#login_email').value;

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			location.reload();
		}
	};

	xhr.open("POST", "forget_password.php", true);
	data.append("login_email", login_email);
	xhr.overrideMimeType("text/plain");
	xhr.send(data);
}

function logout() {
	var xhr = getHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			window.location.replace("index.php");
		}
	};
	xhr.open("GET", "logout.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.overrideMimeType("text/plain");
	xhr.send();
}

function delete_img(img_id) {
	if (confirm("Etes vous sur de vouloir supprimer cet image ?") == true)
	{
		var delete_img = document.querySelector("#delete_img");
		var data = new FormData();
		var xhr = getHttpRequest();

		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				delete_img.parentNode.parentNode.removeChild(delete_img.parentNode);
				location.reload();
			}
		};
		xhr.open("POST", "delete_img.php", true);
		data.append("id", img_id);
		xhr.overrideMimeType("text/plain");
		xhr.send(data);
	}
}

function add_comment() {
	var picture_id = document.querySelector("#picture_id");
	var com = document.querySelector("#comment");
	var data = new FormData();
	var xhr = getHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			console.log(xhr.responseText)
			location.reload();
		}
	};
	xhr.open("POST", "add_comment.php", true);
	data.append("picture_id", picture_id.value);
	data.append("comment", com.value);
	xhr.overrideMimeType("text/plain");
	xhr.send(data);
}

function delete_comment(comment_id, picture_id) {
	if (confirm("Etes vous sur de vouloir supprimer ce commentaire ?") == true)
	{
		var comments = document.querySelector("#comments");
		var id = document.getElementById(comment_id+"_"+picture_id);
		var data = new FormData();
		var xhr = getHttpRequest();

		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				location.reload();
			}
		};
		xhr.open("POST", "delete_comment.php", true);
		data.append("comment_id", comment_id);
		data.append("picture_id", picture_id);
		xhr.overrideMimeType("text/plain");
		xhr.send(data);
	}
}

/* Profile Page Checked Notification */
// var like = document.getElementById("check_notif");
// like.addEventListener('click', function(e) {
// 		check_notif(e);
// });
//
// function check_notif(e) {
// 	var xhr = getHttpRequest();
// 	var data = new FormData();
// 	var user_id = e;
//
// 	xhr.onreadystate = function () {
// 		if (xhr.readyState == 4 && xhr.status == 200)
// 		{
//             var d = JSON.parse(xhr.responseText);
//         }
// 	}
//
//     xhr.open("POST", "check_notif.php", true);
//     data.append("user_id", user_id);
//     xhr.overrideMimeType("text/plain");
//     xhr.send(data);
// }


/* Like And Dislike */
var like = document.querySelectorAll("#like");
like.forEach(function(e, index) {
	e.addEventListener("click", function(e) {
		e.preventDefault();
		vote(e, 1);
	});
});

function vote(e, value) {
	var xhr = getHttpRequest();
	var data = new FormData();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) 
		{
			var d = JSON.parse(xhr.responseText);
			e.target.children[0].innerHTML = d['like_count'];
			location.reload();
		}
		return ;
	};

	xhr.open("POST", "like.php", true);
	data.append("ref", e.target.parentNode.dataset.ref);
	data.append("ref_id", e.target.parentNode.dataset.ref_id);
	data.append("user_id", e.target.parentNode.dataset.user_id);
	data.append("like", value);
	xhr.overrideMimeType("application/json");
	xhr.send(data);
}
