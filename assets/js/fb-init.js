window.fbAsyncInit = function() {
        FB.init({
            appId      : '1780400955547577',
            status     : false,
            cookie     : true,
            xfbml      : true
        });
};

(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));