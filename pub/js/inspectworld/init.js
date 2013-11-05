
function init() {
    
    ciao = setInterval("refresh()", 1000);
    
    $(".text p").click(function() {

        $(".text textarea").hide();
        $(".text p").show();
        
        $(this).hide();
        
        var temp = $(this).parent().find("textarea").first();
        temp.show(0, function() {
            idAnswer = temp.closest(".question").attr("answer");
        
            ciao2 = setInterval("update(" + temp.closest(".question").attr("answer") + ")", 2000);
        }).focus();
    });
}

var lockUpdate = false;

function update(idAnswer) {

    if(lockUpdate == true) {
        return;
    } else {
        lockUpdate = true;
    }

    answerTextarea = $.trim(nl2br($("#question"+idAnswer).find("textarea").val()));
    answerP = $.trim($("#question"+idAnswer).find("p").html());
    
    if(answerTextarea == answerP) {
        lockUpdate = false;
        return;
    }

    $.post("index.php", {
        "m": "inspectworld",
        "a": "save-answer",
        "id": idAnswer,
        "answer": $("#question"+idAnswer).find("textarea").val()
    }, function(data) {
        lockUpdate = false;
    });
}

var lockReload = false;

function refresh() {

    if(lockReload == true) {
        return;
    } else {
        lockReload = true;
    }
    
    $.post("index.php", {
        "m": "inspectworld",
        "a": "get-answers",
        "step": $(".question").first().attr("step"),
        "team": $(".question").first().attr("team")
    }, function(data) {
        
        data = json_decode(data);
        
        lockReload = false;
        
        for(i=0 ; i<data.length ; i++) {
            $("#question"+data[i]["id"]).find(".text p").html(nl2br(data[i]["answer"]));
        }
    });
}

function initCountdown(startTime, endTime) {
    var t = time();
    
    if(t < startTime) { // the challenge is not started yet
        $("#countdown").addClass("countdown-start").attr("time", startTime);
    } else if(t > endTime) { // the challenge is over
        $("#countdown").removeClass("countdown-start").removeClass("countdown-end").removeAttr("time");
    } else { // the challenge is running
        $("#countdown").removeClass("countdown-start").addClass("countdown-end").attr("time", endTime);
    }
    
    if(t < endTime) { // if the challenge is not over
        $("#countdown").kkcountdown({
            dayText: 'day ',
            daysText: 'days ',
            hoursText: 'h ',
            minutesText: 'm ',
            secondsText: 's',
            displayZeroDays: false,
            callback : function() {
                location.href = location.href;
            }
        });
    } else {
        $("#countdown")
            .addClass("countdown-end")
            .text("Time expired");
    }
}