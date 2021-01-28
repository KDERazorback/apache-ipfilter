var viewmodel = {
    'progressDataCache' : [],
    'progressDataPointer' : 0,
    'setupScriptResponse' : '',
    'aborted' : false
};

var updateLogView = function () {
    fetch('monitor.php').then((response) => {
        if (!response.ok) {
            response.text().then((msg) => {
                $('.progress-header-running').hide();
                $('.progress-header-error').show();
                $('.progress-header-initial').hide();
                $('.progress-header-done').hide();
                
                if (!msg)
                    msg = "No response from server";
                if (msg.length > 72) {
                    console.log("Invalid data received: " + msg);
                    msg = "Invalid response from server";
                }
                $('.progress-header-error-msg').text(msg);
            });
            return null;
        } else {
            return response.json();
        }
    }).then((json) => {
        if (json == null)
            return;

        let refetch = true;
        if (json.status == "running") {
            $('.progress-header-running').show();
            $('.progress-header-error').hide();
            $('.progress-header-initial').hide();
            $('.progress-header-done').hide();
        } else if (json.status == "completed") {
            $('.progress-header-running').hide();
            $('.progress-header-error').hide();
            $('.progress-header-initial').hide();
            $('.progress-header-done').show();
            refetch = false;
        } else if (json.status == "unstarted") {
            $('.progress-header-running').hide();
            $('.progress-header-error').hide();
            $('.progress-header-initial').show();
            $('.progress-header-done').hide();
        } else {
            $('.progress-header-running').hide();
            $('.progress-header-error').show();
            $('.progress-header-initial').hide();
            $('.progress-header-done').hide();
            $('.progress-header-error-msg').text(json.message ?? 'No response from server');
            refetch = false;
        }

        if (json.log != undefined && json.log.length > 0) {
            let logview = $('#logview');
            let main = $('.main');
            let scroll = false;

            if (main[0].scrollTop - main[0].scrollHeight + main[0].offsetHeight > -96)
                scroll = true;
                
            viewmodel.progressDataCache = json.log;
            if (viewmodel.progressDataPointer > viewmodel.progressDataCache.length)
            viewmodel.progressDataPointer = 0;
            
            for (; viewmodel.progressDataPointer < viewmodel.progressDataCache.length; viewmodel.progressDataPointer++) {
                const element = viewmodel.progressDataCache[viewmodel.progressDataPointer];
                logview.append('<span><i class="fas fa-caret-right"></i>' + element + '</span>');
            }

            if (viewmodel.progressDataPointer > 0)
                $('.progress-header-running-msg').text(viewmodel.progressDataCache[viewmodel.progressDataPointer - 1]);
            else
                $('.progress-header-running-msg').text('');
            
            if (scroll) main.scrollTop(main.prop('scrollHeight'));
        }

        if (refetch)
            setTimeout(updateLogView, 2000);
    }).catch((e) => {
        $('.progress-header-running').hide();
        $('.progress-header-error').show();
        $('.progress-header-initial').hide();
        $('.progress-header-done').hide();
        
        let msg = e?.message ?? '';
        if (!msg)
            msg = "No response from server";
        if (msg.length > 72) {
            console.log("Invalid data received: " + msg);
            msg = "Invalid response from server";
        }
        $('.progress-header-error-msg').text(msg);
    });
}

$(function () {
    setTimeout(updateLogView, 3000);
    setTimeout(() => {
        $('.progress-header-running').show();
        $('.progress-header-initial').hide();

        fetch('../setup.php').then((response) => {
            return response.text();
        }).then((text) => {
            viewmodel.setupScriptResponse = text ?? 'NULL';
            updateLogView();
        }).catch(($e) => {
            viewmodel.aborted = true;
            viewmodel.setupScriptResponse = "Client error";
            console.log($e);
        });
    }, 1000);
})