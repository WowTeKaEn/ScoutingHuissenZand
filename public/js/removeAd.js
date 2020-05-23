window.onload = function(){
    var img = document.getElementsByTagName('img');
    for (var i = 0; i < img.length; i++)       {
        if (img[i].getAttribute('alt') == 'www.000webhost.com') { img[i].parentElement.removeChild(img[i]) }
    }
}
