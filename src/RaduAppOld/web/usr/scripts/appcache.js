$(document).ready(function () {//debugger;

        var cache = window.applicationCache;

        cache.addEventListener("cached", function () {
            console.log("All resources for this web app have now been downloaded. You can run this application while not connected to the internet");
        }, false);
        cache.addEventListener("checking", function () {
            console.log("Checking manifest");
        }, false);
        cache.addEventListener("downloading", function () {
            console.log("Starting download of cached files");
        }, false);
        cache.addEventListener("error", function (e) {
            console.log("There was an error in the manifest, downloading cached files or you're offline: " + e);
        }, false);
        cache.addEventListener("noupdate", function () {
            console.log("There was no update needed");
        }, false);
        cache.addEventListener("progress", function () {
            console.log("Downloading cached files");
        }, false);
        cache.addEventListener("updateready", function () {
            cache.swapCache();
            console.log("Updated cache is ready");
            // Even after swapping the cache the currently loaded page won't use it
            // until it is reloaded, so force a reload so it is current.
            window.location.reload(true);
            console.log("Window reloaded");
        }, false);	

        

    $(document.body).bind("online", checkNetworkStatus);
    $(document.body).bind("offline", checkNetworkStatus);
    checkNetworkStatus();
});

function checkNetworkStatus() {
    if (navigator.onLine) {
        // Just because the browser says we're online doesn't mean we're online. The browser lies.
        // Check to see if we are really online by making a call for a static JSON resource on
        // the originating Web site. If we can get to it, we're online. If not, assume we're
        // offline.
        $.ajaxSetup({
            async: true,
            cache: false,
            context: $("#status"),
            dataType: "json",
            error: function (req, status, ex) {
                console.log("Error: " + ex);
                // We might not be technically "offline" if the error is not a timeout, but
                // otherwise we're getting some sort of error when we shouldn't, so we're
                // going to treat it as if we're offline.
                // Note: This might not be totally correct if the error is because the
                // manifest is ill-formed.
                showNetworkStatus(false);
            },
            success: function (data, status, req) {
                showNetworkStatus(true);
            },
            timeout: 5000,
            type: "GET",
            url: url_ping_js
        });
        $.ajax();
    }
    else {
        showNetworkStatus(false);
    }
}

function showNetworkStatus(online) {
    if (online) {
        $("#online_status").html("Online");
    }
    else {
        $("#online_status").html("Offline");
    }

    console.log("Online status: " + online);
}
