$(document).ready(function() {

    console.log("i'm working");
    $(".tag-list").sticky({
        topSpacing: 10
    });

    $("#tagForm").tagit({
        singleField: true,
    });

});
