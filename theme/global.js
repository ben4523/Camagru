function AJAXSend(myself) {
    var elem   = myself.form.elements;
    var url    = myself.form.action;
    var params = "";
    var value;

    for (var i = 0; i < elem.length; i++) {
        if (elem[i].tagName == "SELECT")
            value = elem[i].options[elem[i].selectedIndex].value;
		else
            value = elem[i].value;                
        params += elem[i].name + "=" + encodeURIComponent(value) + "&";
    }
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
	else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.withCredentials = true;
	xmlhttp.onreadystatechange = function (oEvent) {
	    if (xmlhttp.readyState === 4) {  
	        if (xmlhttp.status === 200) {
	          var warning_ok = document.querySelector('.warning_ok');
	          if (xmlhttp.responseText != '')
	         	warning_ok.style.display = "block";
	          warning_ok.innerHTML = xmlhttp.responseText;
	          setTimeout(function(){
				warning_ok.style.display = "none";
			  }, 2000);
	        } else if (xmlhttp.status === 201){      
	           var warning_error_login = document.querySelector('.warning_error');
	           if (xmlhttp.responseText != '')
	           	warning_error_login.style.display = "block";
	           warning_error_login.innerHTML = xmlhttp.responseText;
	           setTimeout(function(){
				    warning_error_login.style.display = "none";
				}, 2000);
	        } else if (xmlhttp.status === 202) {
	           location.href = xmlhttp.responseText;
	        } else if (xmlhttp.status === 203) {
	          	var warning_ok = document.querySelector('.warning_ok_sub');
	          	if (xmlhttp.responseText != '')
	         		warning_ok.style.display = "block";
	          	warning_ok.innerHTML = xmlhttp.responseText;
	          	setTimeout(function(){
				    location.reload();
				}, 2000);
	        } else if (xmlhttp.status === 206) {
	          	var warning_error_login = document.querySelector('.warning_error_sub');
	           	if (xmlhttp.responseText != '')
	           		warning_error_login.style.display = "block";
	           	warning_error_login.innerHTML = xmlhttp.responseText;
	           	setTimeout(function(){
				    warning_error_login.style.display = "none";
				}, 2000);
	        } else 
	        	console.log("Error", xmlhttp.statusText);
	    }  
	};
    xmlhttp.send(params);
}

function AJAXSend_img(myself, target_id) {
    var elem   = myself.form.elements;
    var url    = myself.form.action;
    var formdata = new FormData();

    for (var i = 0; i < elem.length; i++) {
      if (elem[i].type == "radio") {
      	if (elem[i].checked) {
          	formdata.append(elem[i].name, elem[i].value);
      	}
  		} else if (elem[i].type == "file") {
  			var file = elem[i].files[0];
  			formdata.append(elem[i].name, file);
  		} else {
              if (elem[i].value != '')
          		formdata.append(elem[i].name, elem[i].value);
  		}
    }

    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
	else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.open("POST",url+target_id,true);
    xmlhttp.withCredentials = true;
	xmlhttp.onreadystatechange = function (oEvent) {
	    if (xmlhttp.readyState === 4) {  
	        if (xmlhttp.status === 200) {
	        	var column_files = document.querySelector('.add_post_r1_right');
				var img = document.createElement("img");
    			img.src = xmlhttp.responseText+"-thumb.png";
    			var url_big = xmlhttp.responseText+".png";
    			img.setAttribute('onclick', 'javascript:open_share_box(this, "'+url_big+'")');
    			column_files.insertBefore(img, column_files.childNodes[0]);
    			var warning_ok = document.querySelector('.warning_ok');
	         	warning_ok.style.display = "block";
	          	warning_ok.innerHTML = "Cool, Finish ;) !!";
	          	setTimeout(function(){
					warning_ok.style.display = "none";
			  	}, 2000);
	        } else if (xmlhttp.status === 201){      
	           var warning_error_login = document.querySelector('.warning_error');
	           if (xmlhttp.responseText != '')
	           	warning_error_login.style.display = "block";
	           warning_error_login.innerHTML = xmlhttp.responseText;
	           setTimeout(function(){
				    warning_error_login.style.display = "none";
				}, 2000);
	        }
	    }  
	};
    xmlhttp.send(formdata);
}

function count_post_display() {
	var container_div = document.getElementById('actu_fil_r1');
	var count = container_div.getElementsByTagName('article').length;
	return count;
}

function checkInfiniteScroll(parentSelector, childSelector) {
  var lastDiv = document.querySelector(parentSelector + childSelector),
      lastDivOffset = lastDiv.offsetTop + lastDiv.clientHeight,
      pageOffset = window.pageYOffset + window.innerHeight,
    loader = document.querySelector("#loader_post"),
    offsetForNewContent = -170;
  if(pageOffset > lastDivOffset - offsetForNewContent) {
  if (window.XMLHttpRequest)
    xmlhttp=new XMLHttpRequest();
  else
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  var number_display = count_post_display();
  xmlhttp.open("GET","requests.php?func=get_post&id_last_post="+number_display,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.withCredentials = true;
  xmlhttp.onload = function (oEvent) {
    loader.style.display = "block";
    setTimeout(function(){
      loader.style.display = "none";
    }, 1000);
  };
    xmlhttp.onreadystatechange = function (oEvent) {
    if (xmlhttp.readyState === 4) {
      if (xmlhttp.status === 200) {
        var tab = xmlhttp.responseText.split("˘");
        for (var i=0; i < tab.length; i++) {
          var newDiv = document.createElement("article");
          newDiv.setAttribute('class', 'one_by_one');
          newDiv.innerHTML = tab[i];
          document.querySelector(".actu_fil_r1").appendChild(newDiv);
        }
      } else {
        pause = true;
      }
    }
  };
  xmlhttp.send();
  }
};

function like_post(thiss,id_post) {
  if (window.XMLHttpRequest)
    xmlhttp=new XMLHttpRequest();
  else
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  xmlhttp.open("GET","requests.php?func=like_post&id_post="+id_post,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.withCredentials = true;
  xmlhttp.onreadystatechange = function (oEvent) {
    if (xmlhttp.readyState === 4) {  
      if (xmlhttp.status === 200) {
        var tab = xmlhttp.responseText.split("+");
        thiss.style.backgroundPosition = tab[0];
        thiss.parentNode.parentNode.querySelector("#like_customers_post").innerHTML = tab[1];
      }
    }
  };
  xmlhttp.send();
}

function deselect(element) {
  if (element && /INPUT|TEXTAREA/i.test(element.tagName)) {
    if ('selectionStart' in element) {
      element.selectionEnd = element.selectionStart;
    }
    element.blur();
  }
  if (window.getSelection) {
    window.getSelection().removeAllRanges();
  } else if (document.selection) {
    document.selection.empty();
  }
}

function add_comment(thiss, id_post, e) {
  e = e || event;
  if (e.keyCode === 13 && !e.ctrlKey) {
    var elem   = thiss.form.elements;
    var formdata = new FormData();

    for (var i = 0; i < elem.length; i++) {
      if (elem[i].value != '')
        formdata.append(elem[i].name, elem[i].value);
    }

    if (window.XMLHttpRequest)
      xmlhttp=new XMLHttpRequest();
    else
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.open("POST","requests.php?func=add_comment&id_post="+id_post,true);
    xmlhttp.withCredentials = true;
    xmlhttp.onreadystatechange = function (oEvent) {
      if (xmlhttp.readyState === 4)
        if (xmlhttp.status === 200) {
          var newDiv = document.createElement("span");
          newDiv.setAttribute('id', 'comment_post_onebyone');
          var tab = xmlhttp.responseText.split("˘");
          newDiv.innerHTML = tab[0];
          var list = thiss.parentNode.parentNode.parentNode.querySelector(".post_comment_r1");
          list.appendChild(newDiv);
          thiss.parentNode.parentNode.parentNode.querySelector("#comment_post_onebyone_title").innerHTML = tab[1];
          thiss.value = "";
          deselect(thiss);
        }
    };
    xmlhttp.send(formdata);
  }
}

function logout() {
  if (window.XMLHttpRequest)
      xmlhttp=new XMLHttpRequest();
  else
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  xmlhttp.open("GET","requests.php?func=logout",true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.withCredentials = true;
  xmlhttp.onreadystatechange = function (oEvent) {
    if (xmlhttp.readyState === 4)
      if (xmlhttp.status === 200) {
        location.reload();
      }
  }
  xmlhttp.send();
}
