function getkey(e){
  if (window.event)
    return window.event.keyCode;
  else if (e)
    return e.which;
  else
    return null;
}

function angkadanhuruf(e, goods, field){
  var angka, karakterangka;
  angka = getkey(e);
  if (angka == null) return true;  
  karakterangka = String.fromCharCode(angka);
  karakterangka = karakterangka.toLowerCase();
  goods = goods.toLowerCase();
  if (goods.indexOf(karakterangka) != -1)
    return true;
    if ( angka==null || angka==0 || angka==8 || angka==9 || angka==27 )
       return true;
    if (angka == 13){
      var i;
      for (i = 0; i < field.form.elements.length; i++)
      if (field == field.form.elements[i])
          break;
      i = (i + 1) % field.form.elements.length;
      field.form.elements[i].focus();
      return false;
    };
  return false;
}