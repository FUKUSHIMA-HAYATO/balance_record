//タブをスマホ版とpc版で表示分岐
var phone_change = matchMedia('(max-width: 600px)');
resizeWindow(phone_change);
phone_change.addListener(resizeWindow);
function resizeWindow(m){
  if (m.matches) {
    document.getElementById("log_analyze").click();
  } else {
    document.getElementById("record_analyze").style.display = "block";
    document.getElementById("record_home").style.display = "block";
    document.getElementById("record_entry").style.display = "block";
    let record_height = document.getElementById('record_analyze').offsetHeight;
    document.getElementById('detail_expense').style.maxHeight = record_height + "px";
  }
}
//タブ(スマホでのみ表示)
function tab_btn(evt, tab) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tab).style.display = "block";
  evt.currentTarget.className += " active";
}
//チャート表示領域
function chart_btn(evt, chart) {
  var i, chart_content, chart_num;
  chart_content = document.getElementsByClassName("chart_content");
  for (i = 0; i < chart_content.length; i++) {
    chart_content[i].style.display = "none";
  }
  chart_num = document.getElementsByClassName("chart_num");
  for (i = 0; i < chart_num.length; i++) {
    chart_num[i].className = chart_num[i].className.replace(" active", "");
  }
  document.getElementById(chart).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("chart_month").click();
//カテゴリー別表示領域
function category_btn(evt, category) {
  var i, category_content, category_num;
  category_content = document.getElementsByClassName("category_content");
  for (i = 0; i < category_content.length; i++) {
    category_content[i].style.display = "none";
  }
  category_num = document.getElementsByClassName("category_num");
  for (i = 0; i < category_num.length; i++) {
    category_num[i].className = category_num[i].className.replace(" active", "");
  }
  document.getElementById(category).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("category_month").click();
//高額商品別表示領域
function hpi_btn(evt, hpi) {
  var i, hpi_content, hpi_num;
  hpi_content = document.getElementsByClassName("hpi_content");
  for (i = 0; i < hpi_content.length; i++) {
    hpi_content[i].style.display = "none";
  }
  hpi_num = document.getElementsByClassName("hpi_num");
  for (i = 0; i < hpi_num.length; i++) {
    hpi_num[i].className = hpi_num[i].className.replace(" active", "");
  }
  document.getElementById(hpi).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("hpi_month").click();