/****************************************************************
Standard HTML functions
****************************************************************/
function findObj(n, d){
   var p,i,x;
   if (!d) d = document;
   if (d.getElementById != null){
      if ((x = d.getElementById(n)) != null)return x;
   }
   if (d.getElementsByName != null){
      if ((x = d.getElementsByName(n)) != null)return x.item(0);
   }
   if ((p = n.indexOf("?"))>0 && parent.frames.length){
      d = parent.frames[n.substring(p+1)].document;
      n = n.substring(0,p);
   }
   if (!(x = d[n]) && d.all){
      x = d.all[n];
   }
   for (i = 0; !x && i < d.forms.length; i++){
      x = d.forms[i][n];
   }
   for(i = 0; !x && d.layers && i < d.layers.length; i++){
      x = findObj(n,d.layers[i].document);
      return x;
   }
   return x;
}

function email(address, domain){
    return "<a href='mailto:" + address + "@" + domain + "'>" + address + "@" +
    domain + "</a>";
}

function imageWindow(img) {
    window.open('images/' + img, 'imagewindow', 'scrollbars=no, height=620, width=420');
}

function imageWindow2(img) {
    window.open('images/' + img, 'imagewindow', 'scrollbars=no, height=420, width=620');
}

function prevPage(obj) {
    obj.selectedIndex = obj.selectedIndex - 1;
    obj.form.submit();
}
function nextPage(obj) {
    obj.selectedIndex = obj.selectedIndex + 1;
    obj.form.submit();
}
