<?php
include("./config/setup.php");
include('./theme/header.php');

print('<section class="actu_fil">
          <div class="actu_fil_r1" id="actu_fil_r1">
          </div>
          <span id="loader_post"></span>
       </section>');
include('./theme/footer.php');
?>

<script type="text/javascript">
  if (window.XMLHttpRequest)
    xmlhttp=new XMLHttpRequest();
  else
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  xmlhttp.open("GET","requests.php?func=get_post&id_last_post=0",true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.withCredentials = true;
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
        update();
      }
    }
  };
  xmlhttp.send();

  var lastScrollTime = Date.now(), 
      checkInterval = 1000,
      pause = false;
  function update() {
    if(pause)
      return ;
    requestAnimationFrame(update);
    var currScrollTime = Date.now();
    if(lastScrollTime + checkInterval < currScrollTime) {
      checkInfiniteScroll(".actu_fil_r1", " article:last-child");
      lastScrollTime = currScrollTime;
    }
  };

  function delete_post(thiss, id_post) {
    if (confirm("<?php echo NEWS_R18; ?>")) {
      if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
      else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      xmlhttp.open("GET","requests.php?func=delete_post&id_post="+id_post,true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.withCredentials = true;
      xmlhttp.onreadystatechange = function (oEvent) {
        if (xmlhttp.readyState === 4)
          if (xmlhttp.status === 200)
            thiss.parentNode.parentNode.parentNode.remove();
      };
      xmlhttp.send();
    }
  }

  function delete_comment(thiss, id_comment, id_post) {
    if (confirm("<?php echo NEWS_R18; ?>")) {
      if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
      else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      xmlhttp.open("GET","requests.php?func=delete_comment&id_comment="+id_comment+"&id_post="+id_post,true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.withCredentials = true;
      xmlhttp.onreadystatechange = function (oEvent) {
        if (xmlhttp.readyState === 4)
          if (xmlhttp.status === 200) {
            thiss.parentNode.parentNode.parentNode.querySelector("#comment_post_onebyone_title").innerHTML = xmlhttp.responseText;
            thiss.parentNode.remove();
          }
      };
      xmlhttp.send();
    }
  }
</script>