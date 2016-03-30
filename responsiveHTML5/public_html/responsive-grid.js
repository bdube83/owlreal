;var more_bt = document.getElementById('more_bt');
var article2 = document.getElementById('article');
var i = 0;
document.getElementById('more_bt').onclick = function() {
	article2.innerHTML = article2.innerHTML+"<h2>Article title</h2>"+
						"<p>02/12/2013</p>"+
						"<div class='box'>"+
						"That is easy, the answer is <strong>"+i+"</strong>!"+
						"</div>";
	i++;
	return false;
};


var scrollHandler = function() {
	//setTimeout( add your function here , 200 );			
}
//window.addEventListener( 'scroll', scrollHandler, false );