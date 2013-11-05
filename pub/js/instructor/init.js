$(function() {
    
    $(window).resize(function() {
        $("#content").width($(window).width()-$("#left").outerWidth()-30);
    }).resize();
    
    $("input").attr("autocomplete", "off");
    $("input[type=text],textarea").first().focus();
    
    $(".menu").menu();

    // preview images
    $("table img")
        .hover(function() {
            $("<img />")
                .attr("src", $(this).attr("src"))
                .attr("class", "table-image-preview")
                .css({
                    position: "absolute",
                    top: $(this).position().top+"px",
                    left: $(this).position().left+$(this).width()+1+"px", // +1 per il bordo
                    maxWidth: "200px",
                    border: "1px solid #444444",
                    borderRadius: "2px"
                }).appendTo("body");
        }, function() {
            $(".table-image-preview").remove();
        });
    
    refreshUsersOnline();
    setInterval("refreshUsersOnline()", 10000);
    
    // handling form wait
    $("form").submit(printWaitMessage);
    $("a:not([target])").click(printWaitMessage);
    
    $(window).resize(function() {
        
        $("#cover")
            .css({
                width: $("html").width()+"px",
                height: $("html").height()+"px"
            });
        
        $("#cover-message")
            .css({
                left: ($("html").width()-$("#cover-message").width())/2+"px"
            });
        
        $("html").css("min-height", $(window).height()+"px");
        
    }).resize();
});

function refreshUsersOnline() {
    $.post("index.php", {
        m: "instructor",
        a: "online"
    }, function(data) {
        $("#online").html(data);
    });
}

function printWaitMessage() {
        
    $("<div />")
        .attr("id", "cover")
        .css({
            position: "absolute",
            top: "0",
            left: "0",
            width: $("html").width()+"px",
            height: $("html").height()+"px",
            cursor: "wait",
            backgroundColor: "black",
            opacity: 0.5,
            zIndex: 99
        })
        .appendTo("body");

    $(document).scrollTop(0);

    $("<div />")
        .attr("id", "cover-message")
        .css({
            position: "absolute",
            width: "400px",
            top: "200px",
            left: ($("html").width()-480)/2+"px",
            padding: "40px",
            cursor: "wait",
            backgroundColor: "white",
            border: "2px solid black",
            borderRadius: "5px",
            zIndex: 100,
            textAlign: "center",
            fontSize: "16px"
        })
        .html("<p>Loading in progress...</p><p><img src=\"pub/img/ajax-loader.gif\" /></p><p>Don't close the browser!</p>")
        .appendTo("body");
}


/*
$(function() {
                $('.editable').editable(function(value, settings) { 
                    console.log(this);
                    console.log(value);
                    console.log(settings);
                    console.log($(this).attr("question"));
                    return(value);
                }, {
                    name    : 'nome dellattributo da aggiornare',
                    id      : 'id da aggiornare',
                    type    : 'text',
                    submit  : 'OK',
                    //cancel  : 'Cancel',
                    style   : 'display: inline;'
                });
            });
 */